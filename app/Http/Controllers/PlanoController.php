<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Plano;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Database\Eloquent\Builder;

class PlanoController extends Controller
{
    private $plano;
    private $request;

    public function __construct(Request $request, Plano $plano) {
        $this->plano = $plano;
        $this->request = $request;
        $this->roles = ['publicador','coordenador'];
        $this->middleware('auth');
        $this->middleware('permission:lista-plano');
        $this->middleware('permission:cria-plano', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-plano', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-plano', ['only' => ['destroy']]);
    }

    public function index() {

        $planos = Plano::all();
        

        return view('plano.lista', compact('planos'));
    }

    public function create() {


        $role = Role::whereIn('name',$this->roles)->get();
        $permissions     = Permission::orderBy('bloco')->get();

        foreach ($role as $key => $rol) {
           
            $rolePermissions[$rol->id] = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$rol->id)
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();
        }
        
        $current = '';

        $user_permissao  = "";

        $current = '';

        return view('plano.create', compact(['role','permissions','rolePermissions', 'user_permissao']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public function store(Request $request) {
        //
       
        $this->validate($request, [
            'nome'        => 'required',
            'permissions' => 'required',
        ]);

        try {
            
            DB::beginTransaction();

            $valor = $request->has('valor') && !empty($request->get('valor'))
            ? str_replace(",",".", str_replace([".", "R$ "], "", $request->get('valor')))
            : null;

            $plano = Plano::create([
                'nome' => $request->get('nome'),
                'valor' => $valor,
                'descricao' => $request->get('descricao'),
                'sort_order' =>'0',
                'status' =>'1',
            ]);

            foreach($request->get('permissions') as $perm) {

                $dados_role_permi = explode("-", $perm);

                $tirar = array('[',']');
                $dados_role_permi = str_replace($tirar, '', $dados_role_permi);

                $plano->permission()->attach($dados_role_permi[1], ['role_id' => $dados_role_permi[0]]);    
            }

            DB::commit();
            //dd($request);
            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'O plano foi cadastrado com sucesso!');
            $request->session()->flash('message.erro', '');
        }
        catch(\Exception $exception) {

            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O plano não foi cadastrado!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();

        }
        return redirect()->route('planos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        $id = decrypt($id);
        $plano = Plano::with(['permissions'])->get()->find($id);
        
        $roles = Role::whereIn('name',$this->roles)->get();

        return view('plano.detalhes', compact(['plano','roles']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        $id = decrypt($id);
        $plano = Plano::with('permissions')->with('roles')->get()->find($id);

        dd($plano->roles);

        // dd($cliente);

        return view('plano.edit', compact('plano'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plano $plano) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plano $plano) {
        //
    }

    /**
     * Cria 5 registros fakes para teste do banco.
     *
     * @return dd($planos);
     */
    public function factory() {
        
        // Factory Test
        $planos = factory(\App\Models\Plano::class, 5)->create();
        dd($planos);
    }

    public function atribuirPlano(Request $request)
    {
        try {
            
            $this->validate($request, [
                'id'    => 'required',
                'plano_id' => 'required',
            ]);

            \DB::beginTransaction();

            $user = User::find(decrypt($request->id));

            $user->plano_id = $request->plano_id;
            $user->save();

           
            //busca para pegar as permissões da role do usuário e o plano escolhido 
            $permissions_plano = DB::table('planos_permissoes')->where('role_id',$request->role_id)->where('plano_id',$request->plano_id)
                ->get()
                ->pluck('permission_id'); 
           
            //testa se existem permissões desse plano para esse usuário 
            if($permissions_plano->count()>0)
            {   //sincroniza as permissões do usuário  com as do plano 
                //dd($permissions_plano);
                $user->permissions()->sync($permissions_plano);  
            }
            
            //se usuario for publicador busca os coordenadores para mudar as permissões
            if($request->role_name == "publicador")
            {
                $coordenadores_publicador = $user->coordenadores()->get();
                //testa se existem coordanadores desse usuário 
                if($coordenadores_publicador->count()>0)
                {
                    //pega o id da roles do coordenador
                    $id_role_coordenador = $coordenadores_publicador->first()->roles->first()->id;

                    //busca para pegar as permissões da role para os coordenadores do usuario publicador 
                    $permissions_plano_coord = DB::table('planos_permissoes')
                        ->where('role_id',$id_role_coordenador)->where('plano_id',$request->plano_id)
                        ->get()
                        ->pluck('permission_id');  
                    //testa se existem permissões desse plano para coordenadores 
                    if($permissions_plano_coord->count()>0) {
                        //atualiza as permissões dos usuários
                        foreach($coordenadores_publicador as $coord) {
                            //sincroniza as permissões do usuário com as do plano 
                            $coord->permissions()->sync($permissions_plano_coord);  
                            
                            $coord->plano_id = $request->plano_id;
                            $coord->save();

                            //dd($coord);
                        }
                    }
                } 
            }



            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'O plano foi atribuido com sucesso.');
            $request->session()->flash('message.erro', '');
        }catch(\Exception $exception) {

            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O plano não pode ser atribuido.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            dd('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }

        return redirect()->route('users.show', $request->id);


    }
}
