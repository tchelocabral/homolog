<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Notifications\UserRegisteredSuccessfully;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/app';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    /**
     * Register new account.
     *
     * @param Request $request
     * @return User
     */
    protected function register(Request $request){

        /** @var User $user */
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'tipo_usuario' => 'required|int',
        ]);

        try {
            $validatedData['password']        = bcrypt(array_get($validatedData, 'password'));
            $validatedData['activation_code'] = str_random(30).time();
            $validatedData['imge']            = 'storage/images/user/avatar-default.png';

            $user                             = app(User::class)->create($validatedData);


        } catch (\Exception $exception) {
            logger()->error($exception);

            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'Não foi possível cadastrar.');
            $request->session()->flash('message.erro', $exception->getMessage());

            return redirect()->back();
        }

        $user->notify(new UserRegisteredSuccessfully($user));

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'Nova conta criada com sucesso! Verifique seu e-mail e ative a sua conta.');
        $request->session()->flash('message.erro', '');

        // return redirect()->back()->with('message', 'Nova conta criada com sucesso! Verifique seu e-mail e ative a sua conta.');
        return redirect()->back();
    }


    /**
     * Activate the user with given activation code.
     * @param string $activationCode
     * @return string
     */
    public function activateUser(Request $request, string $activationCode){
        try {
            $user = app(User::class)->where('activation_code', $activationCode)->first();
            if (!$user) {
                // Criar página com a mensagem para enviar o usuário e um botão para reenviar o token
                return "Este código não está relacionado a nenhum usuário do sistema.";
            }
            $user->ativo = 1;
            // $user->activation_code = null;
            $user->save();

            if(!\Auth::check()) {
                auth()->login($user);
            }

            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Conta ativada com sucesso!');
            $request->session()->flash('message.erro', '');

        } catch (\Exception $exception) {
            logger()->error($exception);

            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'Não foi possível ativar a conta.');
            $request->session()->flash('message.erro', $exception->getMessage());

            # status de retorno
            // Criar página com a mensagem para enviar o usuário e um botão para reenviar o token
            return 'Usuário não pôde ser cadastrado.' . '<br>'.$exception->getMessage().'<br>'.$exception->getLine();

        }
        return redirect()->route('home');
    }



}
