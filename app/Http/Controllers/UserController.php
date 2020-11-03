<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Models\Comment;
use App\Models\UserMeta;
use App\Models\Plano;

use App\Models\ConfiguracoesUser;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Notifications\UserRegisteredSuccessfully;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\UserNotification;
use App\Notifications\AlertAction;
use Carbon\Carbon;

class UserController extends Controller {

    protected $request;
    protected $usuario;

    public function __construct(Request $request, User $usuario) {

        $this->request = $request;
        $this->usuario = $usuario;
        $this->middleware('auth');
        $this->middleware('permission:gerencia-politicas')
             ->except([
                'novaSenha', 
                'gravarSenha', 
                'show', 
                'markReadAllNotification', 
                'markReadNotification',
                'getAllNotifications',
                'listaCoordenador',
                'createCoordenador',
                'store',
                'show',
                'edit',
                'update',
                'encerrarConta',
            ]);
    }

    public function index() {
        
        // Retorna os usuários cadastrados
        $users = User::with('roles')->with('configuracoes')->get();
        $tipo = "Membros";

        // dd($users);

        return view('user.lista', compact('users','tipo'));
    }


    public function listaCoordenador() {

        // Retorna os usuários cadastrados

        $is_publicador = \Auth::user()->isPublicador();
        if($is_publicador) {
            $users = DB::table('users')
                ->select('users.*', 'roles.name as nameRole')
                ->join('user_has_roles', 'users.id', '=', 'user_has_roles.user_id')
                ->join('roles', 'role_id', '=', 'roles.id')
                ->where('publicador_id',\Auth::user()->id)
                ->where('role_id', 3)
                ->get();

        }
        else
        {
            $users = DB::table('users')
                ->select('users.*', 'roles.name as nameRole')
                ->join('user_has_roles', 'users.id', '=', 'user_has_roles.user_id')
                ->join('roles', 'role_id', '=', 'roles.id')
                ->where('publicador_id',null)
                ->where('role_id', 3)
                ->get();

        }
        $tipo = "Coordenadores";

        return view('user.lista', compact('users',  'tipo'));
    }
    
    public function create() {

        $roles = Role::all();
        $tipo = "Membro";
        $role_name = "";
 
        return view('user.create', compact('roles', 'tipo','role_name'));
    }

    public function createCoordenador() {

        $roles = Role::where('name', 'Coordenador');
        $tipo = "Coordenador";
        return view('user.create', compact('roles', 'tipo'));
    }

    public function store(Request $request) {
        //dd($request);
        // validate
        $validator = $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|unique:users',
            'roles' => 'required',
        ]);

        try{
            
            # Pega o timestamp
            $timestamp = str_replace([' ', ':'], '-', \Carbon\Carbon::now()->toDateTimeString());

            # caminho das pastas de arquivos
            $pasta_avatar = 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'user';

            # arruma e verifica os detalhes sobre a imagem de avatar
            $arquivo_avatar = false;
            $nome_arquivo = false;
            $uri_avatar = $pasta_avatar . DIRECTORY_SEPARATOR . 'avatar-default.png';
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $arquivo_avatar = $request->file('image');
                $extensao  = $arquivo_avatar->getClientOriginalExtension();
                $nome_arquivo = 'avatar_' . $timestamp . '.' . $extensao;
                $upload = $arquivo_avatar->storeAs($pasta_avatar, $nome_arquivo);
                if($upload) {
                    $uri_avatar = str_replace('public', 'storage', $pasta_avatar) . DIRECTORY_SEPARATOR . $nome_arquivo;
                }
            }
            
            // criar o @marcador usando o email do usuario
            $marcador  = $request->has('marcador') ? $request->get('marcador') : '@' . explode('@', $request->get('email'))[0];

           \DB::beginTransaction();

            //cria usuario
            $usuario = User::create([
                'name'            => $request->get('name'),
                'display_name'       => $request->get('display_name'),
                'marcador'        => $marcador,
                'bio'             => $request->get('bio')  ?? 'Não informado',
                'sexo'            => $request->get('sexo') ?? 0,
                'cep'             => $request->get('cep'),
                'logradouro'      => $request->get('logradouro'),
                'bairro'          => $request->get('bairro'),
                'cidade'          => $request->get('cidade'),
                'uf'              => $request->get('uf'),
                'numero'          => $request->get('numero'),
                'complemento'     => $request->get('complemento'),
                'telefone'        => $request->get('telefone'),
                'tel_alternativo' => $request->get('tel_alternativo'),
                'email'           => $request->get('email'),
                'razao_social'    => $request->get('razao_social'),
                'cnpj'            => $request->get('cnpj'),
                'nome_fantasia'   => $request->get('nome_fantasia'),
                'publicador_id'   => $request->get('publicador_id'),
                'site'            => $request->get('site'),
                // 'password'        => bcrypt('fullfreela' . random_int(333,333343)),
                //'password'        => bcrypt('fullfreela'),
                'password'        => bcrypt( $request->get('password') ?? 'FullFreela#133'),
                'image'           => str_replace('public', 'storage', $uri_avatar),
                'tipo_usuario'    => $request->get('tipo_usuario'),
                'activation_code' =>  Str::random(30)
              
            ]);

            //caso marcador já exista, concatena o id do usuário o valor de marcador
            if(User::where('marcador', $marcador)->count() > 1){
                $usuario->marcador .=  $usuario->id ;
                $usuario->save();
            }
            
            // pegando a role do usuario
            $usuario->roles()->sync($request->get('roles'));
            //pega as permissões da role do usuario
            $permissions = Role::where('id', $request->get('roles'))->with('permissions')->get()->first()->permissions;
            //sincroniza as permissões com o usuário            
            $usuario->permissions()->sync($permissions);           

            //busca dados da role freelancer
            $freela_role = Role::where('name', 'freelancer')->get()->first();
            //se o novo usuário tiver a role freelancer cria as configurações de qtde_jobs_andamento  
            //e  qtde_jobs_candidaturas
            if($request->get('roles')[0] == $freela_role->id){
                $config = ConfiguracoesUser::create([
                    'user_id' => $usuario->id,
                    'chave'   => 'qtde_jobs_andamento', 
                    'valor'   => '1'
                ]);
                $config = ConfiguracoesUser::create([
                    'user_id' => $usuario->id,
                    'chave'   => 'qtde_jobs_candidaturas', 
                    'valor'   => '1'
                ]);

                // $config = ConfiguracoesUser::create([
                //     'user_id' => $usuario->id,
                //     'chave'   => 'qtde_jobs_propostas', 
                //     'valor'   => '1'
                // ]);

            }

            //envio notificação de registo 
            $usuario->notify(new UserRegisteredSuccessfully($usuario));
            \DB::commit();

            # status de retorno
            session()->flash('message.level', 'success');
            session()->flash('message.content', $usuario->name . ' ' . __('session.cadastrado! Um e-mail foi enviado ao usuário para ativar a conta') . '.');
            session()->flash('message.erro', '');

            return redirect()->route('home');

        }catch (\Exception $exception) {

            \DB::rollback();
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.O usuário não pôde ser cadastrado') . '.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }

       
    }

    public function show($id) {
        $id = decrypt($id);   

        $usuario_ativo  = \Auth::user();
        $this->user_current_role = $usuario_ativo->roles()->first()->name;

        $planos = Plano::all();


        // Mostra os dados do usuário e os jobs desse usuário
        $user = User::with(['plano','meta','roles:id,name','permissions','jobs.tipo','avaliado'])->where('id', $id)->first();

        //flag se o usuário pode ser apagado e mudar o modal no front
        $user->podeApagar = 
            ($user->jobs                && count($user->jobs) > 0)               ||
            ($user->avaliando           && count($user->avaliando) > 0)          ||
            ($user->coordenando         && count($user->coordenando) > 0)        ||
            ($user->coordenandoProjetos && count($user->coordenandoProjetos) > 0)
            ? false : true;

        $usuario_do_perfil = $usuario_ativo->id == $user->id; 

        $user->lista_usuarios_troca =null; 
        if(!$user->podeApagar)
        {
            $user->lista_usuarios_troca = User::where('id','!=', $id)->get();
        }
        
        $user->mostra_avaliacao = false;
        $user->media_nota = null;
       
        if(in_array($this->user_current_role,['desenvolvedor', 'admin']) || $usuario_do_perfil) {
            $user->avaliacoes = $user->avaliado_id($user->id)->get();
            //dd($user->avaliacoes);
            $user->media_nota = $user->avaliacoes->avg('nota');

            if($user->avaliacoes->count()>0) {
                $user->mostra_avaliacao = true;
            }
        }

        $user->qtd_galeria = $user->galeria()->get()->count();

        $usuario_logado = \Auth::user();

        $user->mostra_porfolio = false;
        $user->editar_perfil = false;
        
        if($user->roles[0]->name == 'freelancer'){
            $user->total_executando = $user->executando()->count();
            $user->total_completo   = $user->concluidos()->count();
            $user->slots_livre_uso  = $user->jobsOrigemNovoExecutandoTotal();
            $user->total_slots_livre = $user->slotsJobsLivresTotal();
            $user->slots_candidaturas_uso = $user->jobsCandidaturaExecutandoTotal();
            $user->total_slots_candidaturas = $user->slotsJobsCandidaturasTotal();
            
            if($usuario_logado->id == $user->id ){
                $user->mostra_porfolio  = true;
                $user->editar_perfil    = true;
            }
        }

        if($user->roles[0]->name == 'publicador'){
            $user->total_executando = $user->jobsPublicadosExecutando($user->roles[0]->name)->count();
            $user->total_completo   = $user->jobsPublicadosConcluidos($user->roles[0]->name)->count();    
            $user->jobs = $user->publicados()->get();        
            
            if($usuario_logado->id == $user->id ){
                $user->editar_perfil = true;
            }
        }
        if($usuario_logado->roles()->first()->name == 'admin' || $usuario_logado->roles()->first()->name == 'desenvolvedor')
        {
            $user->editar_perfil = true;  
            // $user->total_executando = 'x';
            // $user->total_completo   = 'x'; 
        }


        if($usuario_logado->id == $user->id || $user->roles[0]->name == 'admin' || $user->roles[0]->name == 'desenvolvedor')
        {
            $user->logado = true;
        }


       $user->role_name =  $current_role = $usuario_ativo->roles()->first()->name;

        return view('user.perfil', compact(['user','planos','current_role']));
    }

    
    public function edit($id) {
        $id = decrypt($id);
        //
        $roles   = Role::all();
        $usuario = User::with(['plano','meta','roles:id,name','permissions','jobs.tipo'])->where('id', $id)->first();
        $user_roles = $usuario->roles->pluck('id')->all();

        $usuario_ativo  = \Auth::user();
        // Pega a política de acesso do usuário


        $usuario->nome_role = $usuario->roles()->first()->name;


        $usuario->qtd_galeria = 6- $usuario->galeria()->get()->count();
        
        $usuario_logado = \Auth::user();

        $usuario->logado = false;

        $usuario->mudar_tipo = false;

        $role_name =  $usuario_logado->roles()->first()->name;

        $usuario->mostra_porfolio = false;
        if($role_name == 'publicador') {
            $tipo = "Coordenador";
        }
        else{
            $tipo = "";
        }

        if($usuario->roles[0]->name == 'freelancer'  )
        {
            $usuario->mostra_porfolio = true;
        }

        if($usuario_logado->id == $usuario->id || ($usuario->roles[0]->name == 'administrador' || $usuario->roles[0]->name == 'desenvolvedor'))
        {
            $usuario->logado = true;
        }
        if($usuario->roles[0]->name == 'administrador' || $usuario->roles[0]->name == 'desenvolvedor') {
            $usuario->mudar_tipo = true;
        }

        return view('user.edit', compact('usuario', 'roles', 'user_roles', 'tipo', 'role_name'));
    }

    public function update(Request $request, $id) {
        $id = decrypt($id);
        $user = User::where('id', $id)->first();

        //validate - emai unique existente, usar campo mais id
        $validator = $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|unique:users,email,'.$id,
            'roles'    => 'required',
            'marcador' => 'required'
        ]);
        
        try{
            \DB::beginTransaction();

            $custom_update= "";

            // deleta imagens da galeria do perfil
            if(isset($request->excluir_imagem_galeria)) {

                foreach ($request->excluir_imagem_galeria as $e) {
                    $deleta_imagens = UserMeta::where('id', $e)->where('key', 'img_galeria')->get()->first();
                    
                    $caminho_final  = 'app/public/'.$deleta_imagens->value;

                    $exists = \File::exists(storage_path($caminho_final));

                    if($exists) {

                        unlink(storage_path($caminho_final));
                    }
                    $deleta_imagens->delete();
                }
            }

            if ($request->hasFile('image') && $request->file('image')->isValid() ) {
                # Pega o timestamp
                $timestamp = str_replace([' ', ':'], '-', \Carbon\Carbon::now()->toDateTimeString());

                # caminho das pastas de arquivos
                $pasta_avatar = 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR.$id.'_'.$request->get("name") ;
              
                # arruma e verifica os detalhes sobre a imagem de avatar
                $arquivo_avatar = false;
                $nome_arquivo   = false;

                $arquivo_avatar = $request->file('image');
                $extensao       = $arquivo_avatar->getClientOriginalExtension();
                //$nome_arquivo   = 'avatar_' . $timestamp . '.' . $extensao;
                $nome_arquivo   = 'avatar_' . $id . '.' . $extensao;
                $upload         = $arquivo_avatar->storeAs($pasta_avatar, $nome_arquivo);
                if($upload) {
                    $uri_avatar = str_replace('public', 'storage', $pasta_avatar) . DIRECTORY_SEPARATOR . $nome_arquivo;
                    echo $uri_avatar;
                    $user->image = $uri_avatar;
                }

            }

            $custom_update = array('image');            
            if($user->getOriginal('marcador') != $request->get('marcador')){
                
                $old_marc = $user->getOriginal('marcador');
                $new_marc = $request->get('marcador');
                
                if(User::where('marcador', $new_marc)->count() > 0){
                    $new_marc .= $user->id;
                    $user->marcador  = $new_marc . $user->id;
                    $custom_update[] = 'marcador';
                }

                // atualiza comentários
                $comments = Comment::where('descricao', 'LIKE', '%'.$old_marc.'%' )->get();
                foreach ($comments as $c) {
                    $c->descricao = str_replace($old_marc, $new_marc, $c->descricao);
                    $c->save();
                }
            }
      
           
            if(!empty($request->allFiles()) && array_key_exists('galeria', $request->allFiles())){
                // dd($user->getOriginal('marcador'));
               
                $arquivos  = $request->allFiles()['galeria'];

                $dados_img = $request->get('galeria');
                $count     = 0;
                $nome_arquivo  = Controller::tirarAcentos( str_replace(' ', '_', $request->get('nome')) );

                # monta o caminho da pasta

                foreach ($arquivos as $file){

                    $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR.$id.'_'.$request->get("name"). DIRECTORY_SEPARATOR. 'galeria';

                    # retirar acentos e espaços do nome do arquivo
                    $nome = Controller::tirarAcentos( str_replace(' ', '_', $file->getClientOriginalName()) );
                    # salva arquivo na pasta
                    $upload = $file->storeAs($pasta_midias, $nome);

                    # retira 'public/' do caminho do arquivo para salvar no banco de dados
                    $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);

                    if($upload){
                        # nome do tipo de arquivo
                        // $nome_tipo_arquivo = TipoArquivo::where('id', $dados_img['tipo_id'][$count])->get()->first()->nome;

                        $arquivo = UserMeta::create([
                            'user_id' => $id,
                            'key'     => 'img_galeria',
                            'value'   => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                        ]);

                    } else {
                        $request->session()->flash('message.level', 'erro');
                        $request->session()->flash('message.content', __('messages.Problema ao salvar arquivos de referência') . '.');
                        $request->session()->flash('message.erro', 'Falha ao salvar o arquivo ' . $nome . ' na pasta ' . $pasta_midias);
                    }
                    $count++;

                }
           
            }

            $user->fill($request->except($custom_update));
            
            $user->roles()->sync($request->get('roles'));

            $user->nova_senha = 0;

            $user->save();

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', __('session.Usuário atualizado com sucesso') . '.');
            $request->session()->flash('message.erro', '');

            return redirect()->route('users.show', encrypt($user->id));

        }catch(\Exception $exception) {

            \DB::rollback();
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('session.O usuário não pôde ser atualizado') . '.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('users.index');
    }

    // Reavaliar
    public function destroy($id) {

        $id = decrypt($id);

        try{
            $usuario = User::where('id', $id)->get()->first();

            $podeApagar = false;

            $usuario = User::where('id', $id)->with(['jobs','coordenando','coordenandoProjetos','avaliando'])->get()->first();

            $podeApagar = 
                ($usuario->jobs                && count($usuario->jobs) > 0)               ||
                ($usuario->avaliando           && count($usuario->avaliando) > 0)          ||
                ($usuario->coordenando         && count($usuario->coordenando) > 0)        ||
                ($usuario->coordenandoProjetos && count($usuario->coordenandoProjetos) > 0)
                ? false : true;

            if($podeApagar){
                
                \DB::beginTransaction();
                $usuario->delete();
                \DB::commit();

                # status de retorno
                \Session::flash('message.level', 'success');
                \Session::flash('message.content', __('session.Usuário excluído com sucesso!'));
                \Session::flash('message.erro', '');
            }else{
                # status de retorno
                \Session::flash('message.level', 'erro');
                // \Session::flash('message.content', 'Usuário possui projetos cadastrados e não pode ser deletado!');
                \Session::flash('message.content', __('session.Usuário não pode ser deletado pois possui Jobs, Projetos ou Imagens') .'.');
                \Session::flash('message.erro', '');
            }

        } catch (\Exception $exception){

            // dd($exception);

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', __('session.O usuário não pôde ser excluído') .'.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
        }
        return redirect()->route('users.index');
    }

    // Regras senha pra criar o form
    public function novaSenha($id=null) {
        // $id = decrypt($id);
        // TODO: validar ação
        // $user_id = is_null($id) ? \Auth::id() : $id;
        $user_id = is_null($id) ? null : decrypt($id);
        return view('user.nova_senha', compact('user_id'));
    }

    public function gravarSenha(Request $request) {

        $validator = $this->validate($request, [
            'user_id'  => 'required',
            'password' => 'required|string|min:6|confirmed'
        ]);

        try{
            $user = User::where('id',$request->get('user_id'))->get()->first();
            if($user){
                $user->password   = bcrypt($request->get('password'));
                $user->nova_senha = false;
                $user->save();

                 # status de retorno
                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', __('session.Senha atualizada') . '!');
                $request->session()->flash('message.erro', '');

                return redirect()->route('home');



            } else {
                # status de retorno
                $request->session()->flash('message.level', 'erro');
                $request->session()->flash('message.content', __('session.Problemas ao atualizar a Senha do usuário') . '.');
                $request->session()->flash('message.erro', __('session.Usuário com dados incorretos de cadastro') . '.');
                return redirect()->back();                    
            }

           

        }catch (\Exception $exception) {

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('session.A senha não pode ser atualizada') . '.');
            $request->session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

            return redirect()->back();
        }
        return redirect()->action('HomeController@index');
    }

    public function reenviarAtivacaoDeConta(Request $request){

        $validator = $this->validate($request,[ 'user_id' => 'required' ]);

        try{
            $id = decrypt($request->get('user_id'));   

            $user = User::findOrFail($id);
            
            $user['activation_code'] = str_random(30).time();

            $user->save();
            
            $user->notify(new UserRegisteredSuccessfully($user));

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('session.Notificação enviada') . '!');
            session()->flash('message.erro', '');

        }catch(\Exception $e){
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.Problemas ao enviar a ativação da conta') . '!');
            session()->flash('message.erro', '<br>' . $e->getMessage() . ' l:' . $e->getLine());
        }
        return redirect()->back();

    }

    public function ativarConta(Request $request){
        $validator = $this->validate($request,[ 'user_id' => 'required' ]);

        try{
            $id = decrypt($request->get('id'));   

            $user = User::findOrFail($id);
            
            $user->ativo = 1;
            
            $user->save();

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('session.Notificação enviada') . '!');
            session()->flash('message.erro', '');

        }catch(\Exception $e){
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.Problemas ao enviar a ativação da conta') . '!');
            session()->flash('message.erro', '');
        }
        return redirect()->back();
    }


    public function plano($id) {
        
        $id = decrypt($id);
        // Mostra o plano do usuário
        $user = User::where('id', $id)->first();

        $plano = "Usuário não encontrado.";
        if($user){
           $plano = $user->plano;
        }
        return $plano;
    }

    public function mudarStatus($id) {
        $id = decrypt($id);
        
        try {

            $user = User::where('id', $id)->first();
            $user->ativo = !$user->ativo;
           
            if( $user->ativo==false) {
                $user->desativado_em = Carbon::now();
            }
            else{
                $user->desativado_em = null;
            }

            $user->save();
            session()->flash('message.level', 'success');
            session()->flash('message.content', __('session.O usuário foi atualizado') . '.');
            session()->flash('message.erro', '');

            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.O usuário não pôde ser atualizado') . '.');
            session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());

            return redirect()->back()->withInput();

        }

        return view('users.index', compact('user_id'));
    }

    public function encerrarConta($id) {
        $id = decrypt($id);
        
        try {

            $user = User::where('id', $id)->first();
            //dd($user);

            if($user->exclusao_solicitada_em == null) {
                $user->exclusao_solicitada_em = Carbon::now();
                $user->save();
            }

            //encerrar conta notificação
            $rota = route('users.show', encrypt($user->id));

            $param = array(
                'cliente'       =>  null, 
                'imagem'        =>  null, 
                'job'           => null, 
                'task'          => null, 
                'projeto'       => null, 
                'tipo'          => 'usuario_encerra_conta',
                'destinatario'  => $user, 
                'rota'          => $rota,
            );

            //notificação 
            $user_adm = User::role(['admin'])->get(); //->where('publicador_id', null);
            //dd($user_adm);
            if($user_adm) { 
                $notificacao = new UserNotification($param);
                $param['tipo'] = "usuario_encerra_conta";

                foreach ($user_adm as $key => $value) {
                   
                    $param['destinario'] = $value;
                    $value->notify(new AlertAction($notificacao));
                }
            }

            
            session()->flash('message.level', 'success');
            session()->flash('message.content', __('session.A solicitação de encerrar conta foi enviada.') . '.');
            session()->flash('message.erro', '');

            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.A solicitação de encerrar conta  não foi enviada.') . '.');
            session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());
            //dd($e->getMessage().'<br>'.$e->getLine());
            return redirect()->back()->withInput();

        }

        return view('users.index', compact('user_id'));
    }


    public function transferirConta($id, Request $request) {
        $id = decrypt($id);

      
        try {

            $user_atual = User::where('id', $id)->first();
            //dd($user);
            $user_novo = User::where('id', $request->usuarios_troca)->first();

            // Se os usuários não existem ou são iguais
            if(!$user_atual || !$user_novo || $user_atual->id ==  $user_novo->id){
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('messages.Dados dos usuários não estão correto!') . '.');
                session()->flash('message.erro', '');
                return redirect()->route('home');
            }
            echo ($user_atual->id . " - " .$user_novo->id);
            
            if($user_atual->jobs && count($user_atual->jobs) > 0) {
                //mudar jobs delegado
                foreach ($user_atual->jobs as $key => $job) {
                    $job->delegado_para = $user_novo->id;
                    $job->save();
                    # code...
                }
            }              

            if($user_atual->avaliando && count($user_atual->avaliando) > 0) {
                //mudar jobs avaliando
                foreach ($user_atual->avaliando as $key => $job) {
                    $job->avaliador_id = $user_novo->id;
                    $job->save();
                    # code...
                }
            }         
            
            if($user_atual->coordenando && count($user_atual->coordenando) > 0) {
                 //mudar jobs coordenando
                 foreach ($user_atual->coordenando as $key => $job) {
                    $job->coordenador_id = $user_novo->id;
                    $job->save();
                    # code...
                }
            }        
            
            if($user_atual->coordenandoProjetos && count($user_atual->coordenandoProjetos) > 0) {
                 //mudar coordenandoProjetos
                 foreach ($user_atual->coordenandoProjetos as $key => $projeto) {
                    $projeto->coordenador_id = $user_novo->id;
                    $projeto->save();
                    # code...
                }
            }

            //excluir o usuário antigo

            \DB::beginTransaction();
                $user_atual->delete();
            \DB::commit();

            //novo usuario recebe notificação que recebeu novos serviços
            $rota = route('users.show', encrypt($user_novo->id));

            $param = array(
                'cliente'       =>  null, 
                'imagem'        =>  null, 
                'job'           => null, 
                'task'          => null, 
                'projeto'       => null, 
                'tipo'          => 'usuario_dados_transferidos',
                'destinatario'  => $user_novo, 
                'rota'          => $rota,
            );
            $notificacao = new UserNotification($param);
            $user_novo->notify(new AlertAction($notificacao));

            //notificação para os adms que a conta foi encerrada
            $user_adm = User::role(['admin'])->get(); //->where('publicador_id', null);

            if($user_adm) { 
                $notificacao = new UserNotification($param);
                $param['tipo'] = "usuario_encerra_conta";

                foreach ($user_adm as $key => $value) {
                   
                    $param['destinario'] = $value;
                    $value->notify(new AlertAction($notificacao));
                }
            }

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('session.Os dados fora transferidos e a conta foi encerrar.'));
            session()->flash('message.erro', '');

            return redirect()->route('users.index');


        } catch (\Exception $e) {
            
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.Os dados não foram transferidos ou a conta não foi encerrar.'));
            session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());
            //dd($e->getMessage().'<br>'.$e->getLine());
            return redirect()->back()->withInput();

        }

        return view('users.index', compact('user_id'));
    }


    
    public function markReadAllNotification($tipo = 'alert')   {
        try{
            $notificacoes = \Auth::user()->unreadNotifications($tipo)->get();
            // dd($notificacoes);
            foreach ($notificacoes as $notification) {
               
                $notification->markAsRead();
            }

            if(request()->ajax()) {
                return \Response::json(array(
                    'code'    =>  200,
                    'sucesso' =>  true
                ), 200);
            }else{
                session()->flash('message.level', 'success');
                session()->flash('message.content', __('session.Notificações marcadas como lidas') . '.');
                session()->flash('message.erro', '');
            }

        } catch(\Exception $e){
            if(request()->ajax()) {
                return \Response::json(array(
                    'code'    =>  500,
                    'sucesso' =>  false,
                    'msg'     =>  $e->getMessage()
                ), 500);
            }else{
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('session.As Notificações não puderam ser marcadas como lidas') . '.');
                session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());                
            }
        }
        return redirect()->back();
    }
    public function markReadNotification($not_id) {
        $not_id = decrypt($not_id);
        $not = DatabaseNotification::where("id", $not_id)->get();
        $not->markAsRead();
                
    }

    public function getAllNotifications(){
        try{
            $notifications = \Auth::user()->notifications("")->get();
            if(request()->ajax()) {
                return \Response::json(array(
                    'code'    =>  200,
                    'sucesso' =>  true,
                    'notifications' => $notifications
                ), 200);
            
            }else{
                return view('notifications.lista', compact('notifications'));
            }

        } catch(\Exception $e){
            
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.As Notificações não puderam ser marcadas como lidas') . '.');
            session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());

            if(request()->ajax()) {
                return \Response::json(array(
                    'code'    =>  500,
                    'sucesso' =>  false,
                    'msg'     =>  $e->getMessage()
                ), 500);
            }
            return redirect()->back();
        }
    }


    public function markPushNotification() {

        try{

            $notifications = \Auth::user()->notifications()->where('read_at', null)->where('push_at',null);

            foreach ($notifications as $notification) {
                $notification->push_at = \Carbon\Carbon::now();
                $notification->save();
            }
            

        } catch(\Exception $e){
            
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.As Notificações não puderam ser marcadas como lidas') . '.');
            session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());

        }
    }

    // public function addMarcadorAll(){
    //     $users = User::all();
    //     try{
    //         \DB::beginTransaction();
            
    //         foreach ($users as $u) {
    //             $u->marcador = '@' . explode('@', $u->email)[0];
    //             $u->save();
    //         }
            
    //         \DB::commit();
            
    //         echo 'Tudo Certo:<br>';
            
    //         foreach ($users as $u) {
    //             echo '-' . $u->marcador . '<br>';
    //         }

    //     }catch (\Exception $exception) {

    //         \DB::rollback();
    //         echo 'Falha: ' . $exception->getMessage();
    //     }
    // }

}
