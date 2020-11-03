<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Database\Eloquent\Builder;


class RoleController extends Controller
{
    public function __construct(Request $request) { 
        $this->middleware('auth');
        $this->middleware('permission:gerencia-politicas');
    }
    
    
    /**
     * Mostrar todas as roles     
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::orderBy('bloco')->get();

        $rolePermissions = DB::table("role_has_permissions")
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $current = '';

        // foreach ($permissions as $per) {
        
            //     $teste = explode('-', $per->name);

            //     if (end($teste) != $current) {
                    
            //         $current = end($teste);
            //     }

            //      $per->bloco = end($teste);
        // }

        $user_permissao  = "";


        $roles_permissioes = Role::with('permissions')->get();


        return view('politicas.lista', compact('roles','permissions','rolePermissions', 'users', 'user_permissao','roles_permissioes'));
    }

    /**
     * Mostrar o form para criar nova role
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
         
        $permissions = Permission::orderBy('bloco')->get();;

        $current = '';
        // foreach ($permissions as $per) {
        //     $teste = explode('-', $per->name);
        //     if (end($teste) != $current) {
        //         $current = end($teste);
        //     }
        //     $per->bloco = end($teste);
        // }

        return view('politicas.lista',compact('permissions'));

    }

    /**
     * Salvar nova role
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, [
            'name'        => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);

        try {
             
            \DB::beginTransaction();

            $role = Role::create(['name' => $request->get('name')]);
            $role->permissions()->sync($request->get('permissions'));

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A política foi cadastrada com sucesso!');
            $request->session()->flash('message.erro', '');

        }
        catch(\Exception $exception) {

            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A política não pode ser cadastrada!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();

        }
        return redirect()->route('politicas.index');
    }
    

    /**
     * Mostrar detalhes da role e permission
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $id = decrypt($id);

        $role = Role::where('id',$id)->with('permissions')->get()->first();

        $current = '';
        foreach ($role->permissions as $per) {
            $teste = explode('-', $per->name);
            if (end($teste) != $current) {
                $current = end($teste);
            }
            $per->bloco = end($teste);
        }
       
        return view('politicas.detalhes',compact('role'));
        
    }

    /**
     * Mostrar o form para editar role
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $id = decrypt($id);

        $role = Role::find($id);
        $permissions     = Permission::orderBy('bloco')->get();
        //dd($permissions);
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $current = '';

        // foreach ($permissions as $per) {
        
        //     $teste = explode('-', $per->name);

        //     if (end($teste) != $current) {
                
        //         $current = end($teste);
        //     }

        //      $per->bloco = end($teste);
        // }

        //dd($permissions->toArray());

        return view('politicas.edit',compact('role','permissions','rolePermissions'));
    }

    /**
     *Salvar a ediçao da role
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $id = decrypt($id);
        
        try {

             $this->validate($request, [
                'name'        => 'required',
                'permissions' => 'required',
            ]);

            \DB::beginTransaction();

            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();

            // Atualiza permissões
            $role->permissions()->sync($request->get('permissions'));
            
            // Busca e atualiza usuários com a permissão 
            $users = User::whereHas('roles', function(Builder $query) use ($role){
                $query->where('id',$role->id);
            })->get();
            if(count($users) > 0){
                foreach ($users as $user) {
                    $user->permissions()->sync($request->get('permissions'));
                }
            }

            \DB::commit();

             # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A política foi atualizada com sucesso.');
            $request->session()->flash('message.erro', '');

        }catch(\Exception $exception) {

            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A política não pode ser atualizada.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('politicas.index');
        
    } // end class

    /**
     * Deletar role
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {

        $id = decrypt($id);
        try{

            DB::table("roles")->where('id',$id)->delete();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Registro deletado com sucesso.');
            $request->session()->flash('message.erro', '');

        }catch(\Exception $exception) {

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O registro não pode ser deletado.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();

        }

        return redirect()->route('politicas.index');
    }

    public function atribuirPolitica(Request $request) {

        try{

            $this->validate($request, [
                'user' => 'required',
                'role' => 'required',
            ]);

            \DB::beginTransaction();

            $usuario = User::find($request->get('user'));
            $usuario->roles()->sync($request->get('role'));
            $permissions = Role::find($request->get('role'))->permissions;
            $usuario->permissions()->sync($permissions);

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Política atribuída com sucesso.');
            $request->session()->flash('message.erro', '');

        }catch(\Exception $exception) {
            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A política não pode ser atribuída.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();

        }

        return redirect()->route('politicas.index');
    }

    public function usuarioPermissao(Request $request) {

        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::orderBy('bloco')->get();

        $user_id = $request->get('user');
        $user_permissao = User::where('id', $user_id)->first();

        //dd($user);


        $user_permissions = DB::table("user_has_permissions")->where("user_has_permissions.user_id",$user_id)
            ->pluck('user_has_permissions.permission_id','user_has_permissions.permission_id')
            ->all();
       // dd($user_permissions);
        $current = '';

        
        foreach ($permissions as $per) {
        
            $teste = explode('-', $per->name);

            if (end($teste) != $current) {
                
                $current = end($teste);
            }

             $per->bloco = end($teste);
        }

        return view('politicas.lista', compact('roles','permissions', 'users', 'user_permissao', 'user_permissions'));
    }

    public function usuarioPermissaoUpdate(Request $request, $id) {

        $id = decrypt($id);
        
        try {

             $this->validate($request, [
                'usuario_id'        => 'required',
                'permissions' => 'required',
            ]);


            \DB::beginTransaction();

            $user = User::find($id);
            // $role->name = $request->input('name');
            // $role->save();

            // Atualiza permissões
            $user->permissions()->sync($request->get('permissions'));
            
            // Busca e atualiza usuários com a permissão             
            // $users = User::whereHas('users', function(Builder $query) use ($user){
            //     $query->where('id',$role->id);
            // })->get();
            // if(count($users) > 0){
            //     foreach ($users as $user) {
            //         $user->permissions()->sync($request->get('permissions'));
            //     }
            // }

            //dd($user->permissions());
            \DB::commit();

             # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A política foi atualizada com sucesso.');
            $request->session()->flash('message.erro', '');

        }catch(\Exception $exception) {

            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A política não pode ser atualizada.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            dd('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }

        return redirect()->route('politicas.index');
        
    } 
   
    
    public function novaPermissao(Request $request){
        
        $this->validate($request, [
            'name' => 'required|unique:permissions,name'
        ]);

        try{
            
            $bloco = $request->get('bloco');

            $permissao = Permission::create([
                'name' => $request->get('name'),
                'bloco' =>     $bloco           
            ]);

            //dd($permissao);
            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Permissão foi cadastrada com sucesso!');

        }
        catch(\Exception $exception) {

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'Permissão não pode ser cadastrada!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();

        }
        return redirect()->route('politicas.index');

    }

    public function permissaoPorPollitica()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('bloco')->get();

        $rolePermissions = DB::table("role_has_permissions")
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $current = '';

        // foreach ($permissions as $per) {
        
        //     $teste = explode('-', $per->name);

        //     if (end($teste) != $current) {
                
        //         $current = end($teste);
        //     }

        //      $per->bloco = end($teste);
        // }

        $user_permissao  = "";

        return view('politicas.permissoes_politicas', compact('roles','permissions','rolePermissions'));
    }

} // end class
