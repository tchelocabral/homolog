<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    //
    public function __construct(Request $request) {

        $this->middleware('auth');
        // $this->middleware('permission:cria-tutorial', ['only' => ['create','store', 'edit','update','destroy']]);
    }

    public function index() {
        // Retorna os usuários cadastrados
        $tutoriais = Tutorial::get();

        $editar_tutorial = false;

        $user = \Auth::user();

        if($user->isDev() || $user->isAdmin())
        {
            $editar_tutorial = true;
        }


        return view('tutorial.lista', compact('tutoriais', 'editar_tutorial'));
    }

    public function create() {
        return view('tutorial.create');
    }

    public function store(Request $request) {


        // validate
        $validator = $this->validate($request, [
            'url'  => 'required',
        ]);

        try{

            \DB::beginTransaction();

            $tutorial = Tutorial::create([
                'nome'      => $request->get('nome'),
                'url'       => $request->get('url'),
                'descricao' => $request->get('descricao'),
            ]);
            \DB::commit();

            # status de retorno
            session()->flash('message.level', 'success');
            session()->flash('message.content', ' ' . __('session.Tutorial cadastrado com sucesso!') . '.');
            session()->flash('message.erro', '');

            return redirect()->route('index.tutorial');

        }catch (\Exception $exception) {

            \DB::rollback();
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.O Tutorual não pôde ser cadastrado') . '.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            dd($exception);
            return redirect()->back()->withInput();
        }

    }

    public function show($id) {}

    public function edit($id) {

        $id = decrypt($id);
        $tutorial = Tutorial::where('id', $id)->first();
        return view('tutorial.edit', compact('tutorial'));

    }

    public function update(Request $request, $id) {

        $id = decrypt($id);
        $tutorial = Tutorial::where('id', $id)->first();
       
        $validator = $this->validate($request, [
            'url'     => 'required',
        ]);

        try{
            \DB::beginTransaction();

            $tutorial->nome = $request->get('nome');
            $tutorial->url = $request->get('url');
            $tutorial->descricao = $request->get('descricao');
            $tutorial->save();

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', __('session.Tutorial atualizado com sucesso!'));
            $request->session()->flash('message.erro', '');


        }catch(\Exception $exception) {
            \DB::rollback();
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('session.Tutorial não foi atualizado!') );
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('index.tutorial');


    }

    public function destroy($id) {

        $id = decrypt($id);

        try{
            $tutorial = Tutorial::where('id', $id)->get()->first();

            if($tutorial){
                
                \DB::beginTransaction();
                $tutorial->delete();
                \DB::commit();

                # status de retorno
                \Session::flash('message.level', 'success');
                \Session::flash('message.content',  __('session.Tutorial excluído com sucesso!'));
                \Session::flash('message.erro', '');
            }

        } catch (\Exception $exception){

            // dd($exception);

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', __('session.O tutorial não pôde ser excluído') .'.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
        }
        return redirect()->route('index.tutorial');

    }


}
