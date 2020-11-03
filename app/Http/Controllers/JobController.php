<?php

namespace App\Http\Controllers;

use App\Notifications\AlertAction;
use App\Notifications\PaymentAlert;
use App\Models\Cliente;
use App\Models\Imagem;
use App\Models\Job;
use App\Models\Midia;
use App\Models\Projeto;
use App\Models\Task;
use App\Models\TipoArquivo;
use App\Models\TipoJob;
use App\Models\JobRecusado;
use App\Models\JobParado;
use App\Models\JobCandidatura;
use App\Models\UserNotification;
use App\Models\PaymentNotification;
use App\Models\JobAvaliacao;
use App\Models\Configuracao;
use App\Models\DeliveryFormat;
use App\Models\UserConta;
use App\Models\UserFinanceiro;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Exception;
use Log;

class JobController extends Controller {

    protected $request;
    protected $job;
    protected $user_current;
    protected $user_id;
    protected $qtd_job;
    protected $cod_publicador;
    protected $user_current_role;

    public function __construct(Request $request, Job $job) { 
        
        $this->request = $request;
        $this->job = $job;
        //flag aparecer coluna concluir jobs na lista
        $concluir_job=false;
        
        // $this->user_current_role = \Auth::user()->roles()->first;
       
        $this->middleware('auth');
        $this->middleware('permission:lista-job');
        $this->middleware('permission:cria-job', ['only' => ['create','store', 'storeAvulso', 'storeAvulso']]);
        $this->middleware('permission:atualiza-job', ['only' => ['edit','update']]);
        $this->middleware('permission:menu-abertos-job', ['only' => ['abertos']]);
        //dd($request);
    }

    public function index() {

        $this->todos();

        // $jobs = Job::all();

        // return view('job.lista', compact('jobs'));
    }
    public function create($proj_id = null, $img_id = null,  Request $request) {
        
       
        $this->user_current = \Auth::user()->roles->first();
        
        if($proj_id!=null) {
            $proj_id = decrypt($proj_id);
        }
        if($img_id!=null) {
            $img_id = decrypt($img_id);
        }

        $projeto =null;
        $img_id = $img_id ? $img_id : false;

        //dd($img_id . ' img id  - ' . $proj_id . ' proj id ');
        // Verifica se é vinda de imagem ou projeto
        if($img_id) {
            $projeto  = Imagem::findOrFail($img_id)->projeto()->with('cliente')->first();
   
        }elseif($proj_id) {

            $projeto = Projeto::with('cliente')->with('coordenador')->find($proj_id);
        }

        // $imgs_r00       = $projeto ? $projeto->imagensComR00() : null;
        // $imgs_r00       = $projeto ? $projeto->imagens()->get() : null;
        $imgs_r00 = Imagem::where('projeto_id', $projeto->id)->with('tipo')->with('jobs')->with('arquivos')->get()->filter(function($item){
            return in_array($item->status, [0,1]);
        });

        $clientes       = Cliente::all();
        $tipos_jobs     = TipoJob::all();
        $tipos_delivery = DeliveryFormat::all();

        $tasks          = Task::orderBy('nome', 'asc')->get();
        $tipos_arquivos = TipoArquivo::whereIn('nome', array('Referência', 'Boas Práticas', 'Exemplo'))->get();
        
        $pub_id = \Auth::user()->publicador_id ?? \Auth::user();


        // Politicas que poderao receber jobs: novo job / delegado para
        // $usuarios       = User::role(['coordenador', 'avaliador', 'equipe', 'freelancer', 'admin'])
        //                     ->where('publicador_id', null)
        //                     ->orWhere('publicador_id', $pub_id)
        //                     ->get();
        // $coordenadores  = User::role(['coordenador', 'admin'])
        //                     ->where('publicador_id', null)
        //                     ->orWhere('publicador_id', $pub_id)
        //                     ->get();


        if($this->user_current->name == 'publicador' || $this->user_current->name == 'coordenador'){
            $usuarios       = User::role(['coordenador', 'avaliador', 'equipe', 'freelancer', 'admin'])
                                    ->where('publicador_id', $pub_id)->get();
            $coordenadores  = User::role(['coordenador', 'admin'])
                                    ->where('publicador_id', $pub_id)->get();
        }else {
            $usuarios       = User::role(['coordenador', 'avaliador', 'equipe', 'freelancer', 'admin'])
                                // ->where('publicador_id', null)
                                // ->orWhere('publicador_id', $pub_id)
                                ->get();
            $coordenadores  = User::role(['coordenador', 'admin'])
                                // ->where('publicador_id', null)
                                // ->orWhere('publicador_id', $pub_id)
                                ->get();
        }

        return view('job.novo', compact(['clientes', 'coordenadores', 'tipos_jobs', 'tasks', 'tipos_arquivos', 'usuarios', 'projeto', 'imgs_r00', 'img_id','tipos_delivery']));
    }

    public function store(Request $request) {

        $validator = $this->validate($request, [
            'nome'       => 'required',
            'cliente_id' => 'required',
            'projeto_id' => 'required',
            'tipojob_id' => 'required',
            'imagens'    => 'required',
            'tasks'      => 'required'
        ]);

        try{
            
            DB::beginTransaction();

            // Busco o tipo de job
            
            $tipo = TipoJob::find($request->get('tipojob_id'));

            //usuario ativo
            $this->user_ativo =       \Auth::user();       
            //role do usuário
            $this->user_current = \Auth::user()->roles->first();
            //id do usuário
            $this->user_id = \Auth::user()->id;
            //id do publicador do usuário

            $publicador_id = \Auth::user()->publicador_id;
            //flag se o job é de publicador ou nao
            $job_publicador = false;

            if($this->user_current->name == 'publicador') {
                $publicador_id = $this->user_id;
            }



            // Este tipo define finalizador ou é de revisão?
            if($tipo->finalizador || $tipo->revisao){
                
                // Se for do tipo que define, verifico se veio o delegado para
                // if($request->get('delegado_para') != -1){
                    // Pego todas as imagens deste job
                    foreach ($request->get('imagens') as $img) {
                        $img = Imagem::find($img);
                        // Altera o finalizador_id da imagem para o id do delegado para
                        $tipo->finalizador && $request->get('delegado_para') != -1 ? $img->finalizador_id = $request->get('delegado_para') : '';
                        $tipo->revisao     ? $img->status_revisao = $request->get('tipojob_id')    : '';
                        $img->save();
                    }
                // }
                // Retirada trava a pedido do cliente
                // Senão veio delegado, manda msg de erro.
                // else {
                //     $request->session()->flash('message.level', 'erro');
                //     $request->session()->flash('message.content', __('messages.Tipo de Job precisa de um usuário delegado para ele') . '!');
                //     $request->session()->flash('message.erro', '');
                //     return redirect()->back()->withInput();
                // }
            } 

            $valor =  $request->has('valor_job') && !empty($request->get('valor_job'))
                ? str_replace(",",".", str_replace([".", "R$ "], "", $request->get('valor_job')))
                : null;

            $porc = $request->has('porcentagem_individual') 
                ? str_replace("%","", $request->get('porcentagem_individual')) 
                : null;

            $colab = $request->get('delegado_para')  == -1 ? null : User::where('id', $request->get('delegado_para'))->get()->first();
            
            //Se o coordenador e não foi selecionado um coordenador no cadastro do job, 
            //o usuário ativo (sendo coordenador) se torna o coordenador do job
            $coord = $request->get('coordenador_id') == -1 ? null : User::where('id', $request->get('coordenador_id'))->get()->first();
            if($coord ==null && ! $this->user_ativo->can('define-coordenador-job')  &&  $this->user_current->name == "coordenador" )
            {
                $coord = User::where('id',  $this->user_id)->get()->first();
            }

            //dd($coord);
            
            $job = Job::create([
                'nome'                   => $request->get('nome'),
                'tipojob_id'             => $request->get('tipojob_id'),
                'job_delivery_value'     => $request->get('job_delivery_value'),
                'deliveryformat_id'     => $request->get('deliveryformat_id'),
                'cliente_id'             => $request->get('cliente_id'),
                'delegado_para'          => $colab ? $colab->id : null,
                'coordenador_id'         => $coord ? $coord->id : null,
                'user_id'                => $this->user_id,
                'publicador_id'          => $publicador_id,
                'avaliador_id'           => null,
                'descricao'              => $request->get('descricao'),
                'data_prox_revisao'      => $request->get('data_prox_revisao'),
                //'data_entrega'           => $request->get('data_prox_revisao'), data de conclusao do job
                'valor_job'              => $valor,
                'porcentagem_individual' => $porc,
                'campos_personalizados'  => $request->get('campos_personalizados'),
                'taxa'                   => 0,
                'status'                 => $request->get('delegado_para') == -1 ? 0 : 2, #0=Novo,1=Delegado, 2 em execução
                'freela'                 => $request->get('freela') ?? 0,
            ]);
            # Vincula as imagens

            // dd($job);
            $job->imagens()->sync($request->get('imagens'));

            # Vincula as tasks
            // Inicia o contador na ordem 1
            $ordem = 1;
            foreach($request->get('tasks') as $tk) {
                $job->tasks()->attach($tk, ['ordem' => $ordem]);    
                $ordem++;
            }

            // dd($job->tasks());
            
            # Thumb
            if($request->hasFile('thumb_ref') && $request->file('thumb_ref')->isValid()){
                # monta o caminho da pasta
                $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'midias' . DIRECTORY_SEPARATOR . $job->id . $nome_job;
                # retirar acentos e espaços do nome do arquivo
                $nome = Controller::tirarAcentos( str_replace(' ', '_', $file->getClientOriginalName()) );
                # salva arquivo na pasta
                $upload = $file->storeAs($pasta_midias, $nome);
                # retira 'public/' do caminho do arquivo para salvar no banco de dados
                $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);
                # se fez o upload atualiza o thumb
                if($upload){
                    $job->thumb = $pasta_midias . DIRECTORY_SEPARATOR .  $nome;
                    $job->save();
                }
            }

            # Mantém referências antigas?
            if(!$request->has('alterar_ref') && $request->has('midias_ref')){
                $job->midias()->attach($request->get('midias_ref'));
            }

            # Novos arquivos de referência?
            if(!empty($request->allFiles()) && array_key_exists('arquivos_ref', $request->allFiles())){
                $arquivos  = $request->allFiles()['arquivos_ref'];
                $dados_img = $request->get('novas_ref');
                $count     = 0;
                $nome_job  = Controller::tirarAcentos( str_replace(' ', '_', $request->get('nome')) );

                foreach ($arquivos as $file){

                    # monta o caminho da pasta
                    $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'midias' . DIRECTORY_SEPARATOR . $nome_job;
                    # retirar acentos e espaços do nome do arquivo
                    $nome = Controller::tirarAcentos( str_replace(' ', '_', $file->getClientOriginalName()) );
                    # salva arquivo na pasta
                    $upload = $file->storeAs($pasta_midias, $nome);
                    # retira 'public/' do caminho do arquivo para salvar no banco de dados
                    $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);

                    if($upload){
                        
                        # nome do tipo de arquivo
                        $nome_tipo_arquivo = TipoArquivo::where('id', $dados_img['tipo_id'][$count])->get()->first()->nome;

                        $arquivo = Midia::create([
                            'tipo_arquivo_id' => $dados_img['tipo_id'][$count],
                            'nome'            => $nome_tipo_arquivo,
                            'caminho'         => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                            'descricao'       => $nome_tipo_arquivo . ' do Job',
                            'nome_original'   => $nome,
                            'nome_arquivo'    => $nome
                        ]);
                        # vincula ao job a midia recém inserida
                        $job->midias()->attach($arquivo->id);

                    } else {
                        $request->session()->flash('message.level', 'erro');
                        $request->session()->flash('message.content', __('messages.Problema ao salvar arquivos de referência') . '.');
                        $request->session()->flash('message.erro', 'Falha ao salvar o arquivo ' . $nome . ' na pasta ' . $pasta_midias);
                    }
                    $count++;
                }
            }

            // DB::commit();

            #notificação dos envolvidos
            $rota = route('jobs.show', encrypt($job->id));
            // $nome_obj   = $job->id;
            $proj       = Projeto::where('id', $request->get('projeto_id'))->with(['coordenador'])->get()->first() ?? false;
            $coord_proj = $proj && $proj->coordenador ? $proj->coordenador : false;
 
            $param = array(
                'cliente'       => $proj ? $proj->cliente : null, 
                'imagem'        => $job->imagens()->get(), 
                'job'           => $job, 
                'task'          => null, 
                'projeto'       => $proj, 
                'tipo'          => null,
                'destinatario'  => $colab, 
                'rota'          => $rota,
            );        

            // se existe colaborador e não for o mesmo do anterior, avisa ele
            if($colab) {
                # notificação ao novo colaborador 
                $param['tipo'] = "job_colab_novo_vc";

                $notificacao = new UserNotification($param);
                $colab->notify(new AlertAction($notificacao));
                 
                //Comentado dia 09-04-2020 para analise do cliente
                # notificação do novo colaborador ao coordenador
                // if($coord && $coord->id != \Auth::id()){
                //     $param['tipo'] = "job_colab_novo_outros";
                //     $notificacao = new UserNotification($param);
                //     $coord->notify(new AlertAction($notificacao));
                // }

                //Comentado dia 09-04-2020 para analise do cliente
                # notificação do novo colaborador ao coordenador do projeto se nao for o mesmo
                // if($coord_proj && ($coord && $coord->id != $coord_proj->id) && $coord_proj->id != \Auth::id() ) {
                //     $param['tipo'] = "job_colab_novo_outros";
                //     $notificacao = new UserNotification($param);
                //     $coord_proj->notify(new AlertAction($notificacao));
                // }
            }

            if($coord) {
                //Comentado dia 09-04-2020 para analise do cliente
                #notificação coordenador selecionado da criação do job
                // $param['tipo'] = "job_coord_novo_vc";;
                // $notificacao = new UserNotification($param);
                // $coord->notify(new AlertAction($notificacao));
            }

            //     # notificação ao novo coordenador
            //     if($coord->id != \Auth::id()){
                //         $tipo         = "job_coord_novo_vc";
                //   //      $coord->notify(new AlertAction($coord, $rota, $tipo, $nome_obj));
            //     }

            //     # notificação ao novo coordenador
            //     if($coord_proj && $coord_proj->id != \Auth::id()){
                //         $tipo         = "job_coord_novo_outros";
                //  //       $coord_proj->notify(new AlertAction($coord_proj, $rota, $tipo, $nome_obj));
            //     }
            // }


            DB::commit();

            # status de retorno
            $request->session()->flash('message.level',   'success');
            $request->session()->flash('message.content', '<a class="texto-branco" href="' .  route('jobs.show', encrypt($job->id)) . '">' . __('messages.Job cadastrado com sucesso') . '!</a>');
            $request->session()->flash('message.erro', '');

            return redirect()->route('jobs.create', $request->get('projeto_id'));

        }catch (\Exception $exception){

            DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('messages.O job não pôde ser cadastrado') . '.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->route('jobs.index');
    }

    public function show(Request $request, $id) {
        
        try{
            $id = decrypt($id);
            //dd($id);
            // Busca o Job
            $job = Job::where('id', $id)
                    ->with(
                        ['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias', 'comments','avaliacao','revisoes', 'candidaturas', 'candidaturaFreela','avaliacoes']
                    )->get()->first();
            
            
            // Se não existir Job na busca
            if(!$job){
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('messages.Job não encontrado1') . '.');
                session()->flash('message.erro', '');
                return redirect()->route('home');
            }

            // Seta usuario logado como ativo
            $usuario_ativo  = \Auth::user();
            // Pega a política de acesso do usuário
            $this->user_current_role = $usuario_ativo->roles()->first()->name;

            //flag para controle de acesso ao job
            $pode_ver_job = false;

            // se o usuario é o delegado do job ou o job nao tem delegado pode ver
            if($job->delegado_para == $usuario_ativo->id || $job->delegado_para == null)
            {
                $pode_ver_job = true;
            }
            // se o usuario é o coordenador do job
            else if($job->coordenador_id == $usuario_ativo->id)
            {
                $pode_ver_job = true;
            }

            // se o usuario for admin ou desenvolvedor 
            else if(in_array($this->user_current_role,['admin','desenvolvedor']))
            {
                $pode_ver_job = true;
            }

            // se o usuario for o publicador do job
            else if($job->publicador_id == $usuario_ativo->id)
            {
                $pode_ver_job = true;
            }

            // se o usuario for coordenador do publicador do job
            else if($job->publicador_id == $usuario_ativo->publicador_id && in_array($this->user_current_role,['coordenador']))
            {
                $pode_ver_job = true;
            }


            //dd($job->publicador_id . ' - ' . $usuario_ativo->id);

            //dd($pode_ver_job);

            // Job pode ser acesado pelo coordenador do job, o publicador do job, 
            // o freela (delegado) do job, coordenador do publicador do job
            
            if($pode_ver_job == false)
            {
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('messages.Você não tem acesso a este job') . '.');
                session()->flash('message.erro', '');
                return redirect()->route('home');
            }

            // Calcula o valor do Job para freelancer(valor desconto). (valor do job - valor*taxa/100)
            // Cálculo embutido na função
            $job->valor_desconto = $job->valorDoJob($this->user_current_role);

            // flag para saber se cria o input para upload de revisao
            $job->cria_upload             = false;
            // flag se cria uma revisão
            $job->cria_revisao            = false;
            // flag se mostra o box de revisão
            $job->mostra_revisao          = false;
            // flag se permite deletar revisao
            $job->deleta_revisao_atual    = false;
            // flag se as taks serão desativadas
            $job->desativa_tasks          = false;
            // contador de revisoes do job
            $count_revisao                = $job->revisoes->count();
            // contador de avaliações 
            $count_avaliacao              = $job->avaliacao->count();
            // lista de tasks da revisao se tiver
            $tasks_revisao_job            = false;
            // Flag se o job está parado
            $job_parado                   = false;
            // Flag se Podemo Comentar
            $job->pode_comentar           = false;
            // Membros permitidos para receber o Job diretamente
            $membros                      = User::where('ativo', 1)->where('id', '<>', $usuario_ativo->id)->get();
            // Se pode subir novos arquivos de referencia
            $faz_upload_arquivos_ref      = $this->user_current_role == 'admin' || $this->user_current_role == 'desenvolvedor' || $usuario_ativo->id == $job->user_id;
            // Lista de Tipos de Arquivos para adicionar ao Job
            $tipos_arquivos = $faz_upload_arquivos_ref ? TipoArquivo::whereIn('nome', array('Referência', 'Boas Práticas', 'Exemplo'))->get() : false;
            // Aba ativa: Detalhes ou Arquivos
            $aba = $request->get('view') && $request->get('view') != '' ? $request->get('view') : 'detalhes';
            // mostra inputs ou lista de tasks
            $job->tasks_inputs = false;
            // mostra aba de propostas
            $job->ver_propostas = false;
            // Array de status de jobs
            $status_array = Job::$status_array;
            //flag para mostrar o include de avaliação
            $job->faz_avaliacao = false;
            //flag para mostra no corpo a avaliação já realizada
            $job->mostra_avaliacao = false;
            //flag para pode editar o job
            $job->pode_editar = true;
            //flag para pode colocar link no lugar de arquivo no HR (para equipe que tem a permissão job-files-sem-upload)
            $job->pode_link_hr = false;
            //flag para saber se concluir é com ok (true) ou com pergunta(false);
            $job->concluir_ok = false;
            //guarda o valor da progressao do job
            $job->valor_progressao = $job->concluido();

            // se tiverem revisões
            if($count_revisao > 0) {
                // pega as tasks da ultima revisão 
                $tasks_revisao_job = $job->revisoes->first()->tasksRevisao;
            }
            /**    
             * Verifica se desativa as tasks caso:
             * - Job esteja concluído, recusado ou parado
             * - Usuario publicador do job for o usuario ativo       
            */
            if($job->verificaStatus('concluido') || $job->verificaStatus('recusado') || 
                $job->verificaStatus('parado') ||  $job->user_id == $usuario_ativo->id ){
                $job->desativa_tasks = true;
            }
            /**    
             * Sobrescreve a ativação das tasks caso:
             * - Usuario publicador do job for admin/desenvolvedor       
             * - Job NÃO esteja concluído, recusado ou parado
            */
            if(!$job->verificaStatus('concluido') && !$job->verificaStatus('recusado') &&
                !$job->verificaStatus('parado')   && in_array($this->user_current_role,['admin','desenvolvedor'])){
                $job->desativa_tasks = false;
            }
            /**    
             * Verifica se mostra lista de Tasks com input ou sem input:
             * - Job esteja concluído, recusado ou parado
             * - Usuario publicador do job for o usuario ativo       
            */
            if(
                ($job->coordenador && $usuario_ativo->id == $job->coordenador->id)    || 
                ($job->delegado    && $job->delegado->id == $usuario_ativo->id)       || 
                $job->user_id      == $usuario_ativo->id || 
                $usuario_ativo->isAdmin() || $usuario_ativo->isDev() || $usuario_ativo->isCoordenador()
            ){
                $job->tasks_inputs = true;
            } 

            // Se o progresso do job for 100%
            if($job->concluido()>=100) {
                // job nao tem revisao nem avaliacao?
                if($count_revisao==0 && $count_avaliacao==0 ) {
                    // Cria input para subir arquivo de avaliação *ATENÇÃO* 
                    $job->cria_upload = true;
                }
                // Aqui tem uma dupla verificação inválida que sobrescreve a regra anterior
                // Se qtd de revs for igual a qtd de avals e nao for a ultima revisao
                if($count_revisao == $count_avaliacao && $count_revisao < 4 )   {
                    // Cria input para subir arquivo de avaliação *ATENÇÃO* 
                    $job->cria_upload = true;
                }
                //dd($job->cria_upload = true);
                /**    
                 * Verifica se cria ou deleta revisao:
                 * - Usuario publicador do job for o usuario ativo       
                 * - Job NÃO esteja , recusado ou concluído
                */
                
                
                
                // Se ((usuario ativo for o publicador do job (job->publicado_id igual ao $usuario_ativo->id) 
                // ou se for coordenador do publicador do job (job->publicador_id igual ao $usuario_ativo->id e in_array($this->user_current_role,['coordenador']))  
                // e se o status do job não é concluido/recusado/parado
                if(!$job->verificaStatus('concluido') && !$job->verificaStatus('recusado') && !$job->verificaStatus('parado')  ) {

                    if(($job->publicador_id == $usuario_ativo->id && in_array($this->user_current_role,['publicador'])) || 
                        ($job->publicador_id == $usuario_ativo->publicador_id && in_array($this->user_current_role,['coordenador']))  || 
                        in_array($this->user_current_role,['admin','desenvolvedor'])){
                        /** 
                         * cria revisão se: tiver avaliacao, não for a última avaliação possível, e tiver mais aval que rev
                        */    
                        if($count_avaliacao > 0 && $count_avaliacao < 4 && $count_avaliacao > $count_revisao ) {
                            $job->cria_revisao = true;
                        }

                        /**
                         * 
                        */
                        if($count_avaliacao == $count_revisao && $count_revisao > 0 && $job->status != 5) {
                            $job->deleta_revisao_atual = true;
                        }
                    }
                }

            }

            // se for o delegado do job e (id do delegado do job for igual ao do usuario ou for role desenvolvedor ou admin) pode ver as revisões
            if($job->delegado && ($job->delegado->id == $usuario_ativo->id || in_array($this->user_current_role,['desenvolvedor', 'admin'])) )   {
                $job->mostra_revisao = true;
            }

            // se a quantidade de avaliacoes for maior que 0  e (user_id do job for igual ao do usuario ou for role desenvolvedor ou admin) pode ver as revisões
            if($count_avaliacao > 0 && ($job->user_id == $usuario_ativo->id || in_array($this->user_current_role,['desenvolvedor', 'admin'])))  {
                $job->mostra_revisao = true;
            }

            //se o usuario for um coordenador e o publicador_id dele for igual o do job, pode ver as revisões
            if(($job->publicador_id == $usuario_ativo->publicador_id && in_array($this->user_current_role,['coordenador'])))  {
                $job->mostra_revisao = true;
            }

            //se o usuario for um coordenador e for o coordenandor desse job
            if(($job->coordenador_id == $usuario_ativo->publicador_id && in_array($this->user_current_role,['coordenador'])))  {
                $job->mostra_revisao = true;
            }

            //se o usuario for um publicador e for o publicador do job
            if(($job->publicador_id == $usuario_ativo->id && in_array($this->user_current_role,['publicador'])))  {
                $job->mostra_revisao = true;
            }


//            dd($job->mostra_revisao . ' - ' . $this->user_current_role);

            foreach ($job->comments as $value) {
                $value->name_role = $value->user->roles()->first()->name;
            }

            if($job->status==8) {
                $job_parado = $job->parado();
            }

            if( in_array($this->user_current_role,['desenvolvedor', 'admin']) || $job->user_id == $usuario_ativo->id || $job->publicador_id == $usuario_ativo->publicador_id){
                $job->ver_propostas = true;
            }

            if(in_array($this->user_current_role,['desenvolvedor', 'admin']) || $job->publicador_id == $usuario_ativo->id || 
                $job->delegado_para == $usuario_ativo->id  || $job->coordenador_id == $usuario_ativo->id || $job->publicador_id == $usuario_ativo->publicador_id) {
                $job->pode_comentar = true;
            }
            foreach ($job->candidaturas as $value) {
                $value->valor_proposta_calculo =   floatval($value->valor) / floatval((100- $job->taxa)/100);
            }

            $job->avaliacoes = false;
            if($job->verificaStatus('concluido')) {

                //verifica se o usuario tem as permissões para avaliar o freela ou publicador
                if($usuario_ativo->can('avalia-freela-job') || $usuario_ativo->can('avalia-publicador-job')) {
                    $tem_avaliacao = $job->avaliacoes();

                    //Se for freelancer (desenvolvedor e admin só para teste inicial), verifica se ele já avaliou o job/publicador 
                    if(in_array($this->user_current_role,['desenvolvedor', 'admin','freelancer'])) {
                        $job->avaliacoes = $tem_avaliacao->where('avaliado_id', $job->publicador_id)->get();
                    }
                    //Se não for freelancer verifica se ele já avaliou o job/freelancer
                    else {
                        $job->avaliacoes = $tem_avaliacao->where('avaliado_id', $job->delegado_para)->get();
                    }
                    
                    //caso o count seja maior que >0 (ja tem avaliação), mostra a avvaliação. Caso não chama blade para fazer avaliação
                    if($job->avaliacoes->count()>0) {
                        $job->mostra_avaliacao = true;
                    }
                    else{
                        $job->faz_avaliacao = true;
                    }

                    //Trava para que o freelancer nao pudesse fazer avaliação. Retirada com a inclusão da permissões - 01/09/2020
                    // if(in_array($this->user_current_role,['freelancer'])) {
                    //     $job->mostra_avaliacao = false;
                    //     $job->faz_avaliacao = false;
                    // }
                }

            }
            $usuario_ativo_role = $this->user_current_role;

            //se delegado ja foi definido não deixa editar
            if(!$job->delegado_para == null){
                $job->pode_editar = false;
            }
            if($usuario_ativo->can('editar-job-delegado'))
            {
                $job->pode_editar = true;
            }
 
            if(in_array($this->user_current_role,['admin','desenvolvedor']))
            {
                $job->pode_editar = true;
            }
            

            if($usuario_ativo->can('job-files-sem-upload'))
            {
                $job->pode_link_hr = true;
            }

            //se o delegado tem a role recebe-pagamento o concluir é só com OK
            if(!$job->delegado_para == null && !$job->delegado()->get()->first()->can('recebe-pagamento'))
            {
                $job->concluir_ok = true;
            }

            
            //dd($job);


            return view('job.detalhes', compact(['aba', 'job', 'membros', 'job_parado', 'usuario_ativo', 'tasks_revisao_job', 'status_array', 'faz_upload_arquivos_ref', 'tipos_arquivos','usuario_ativo_role']));
            // $data = array('job' => $job, 'membros' => $membros, 'job_parado' => $job_parado);
            // return response()->json($data, 200);
        
        }catch(Exception $exception) {
            DB::rollback();
            //dd($exception);
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Problemas ao abrir o Job. Tente novamente mais tarde') . '.');
            session()->flash('message.erro', '');

            Log::error('Open Job FAIL at: ' . $exception->getMessage().'<br>'.$exception->getLine());
            //dd($exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }

    }
    public function edit($id) {

        $id = decrypt($id);

        $job            = Job::with(['tipo','coordenador','delegado','tasks','imagens','midias'])->get()->find($id);
 
        $tasks_id_job   = $job->tasks->pluck('id', 'id')->all(); 
        $tipos_delivery = DeliveryFormat::all();

        $imagens = [];
        $imgs_job = [];

        if(!$job->avulso){
            $imgs_job       = $job->imagens->pluck('id', 'id')->all();
            $imagens        = $job->imagens->first()->projeto->imagens()->get();
        }

        $tasks          = Task::orderBy('nome', 'asc')->get();
         
        $this->user_current = \Auth::user()->roles()->first();
        $this->user_id = \Auth::user()->id;


        if($this->user_current->name == 'publicador' || $this->user_current->name == 'coordenador'){
            
            $users          = User::role(['coordenador', 'avaliador', 'equipe', 'freelancer', 'admin'])
                                    ->where('publicador_id', $job->publicador_id)->get();

            $coordenadores  = User::role(['coordenador', 'admin'])
                                    ->where('publicador_id', $job->publicador_id)->get();

        }else {
            
            $users          = User::role(['coordenador', 'avaliador', 'equipe', 'freelancer', 'admin'])->get();
            $coordenadores  = User::role(['coordenador', 'admin'])->get();

        }      

        $tipos_arquivos = TipoArquivo::whereIn('nome', array('Referência', 'Boas Práticas', 'Exemplo'))->get();

        // $job->valor_desconto = floatval($job->valor_job) - floatval($job->valor_job)*floatval($job->taxa)/100;
        $job->valor_desconto = $job->valorDoJob($this->user_current->name);

        return view('job.edit', compact('job','imagens', 'imgs_job', 'tasks_id_job','tasks','users','coordenadores','tipos_arquivos','tipos_delivery'));

    }
    public function update(Request $request, $id) {

        $id = decrypt($id);

        $regras = [
            'nome'              => 'required',
            'tipojob_id'        => 'required',
            // 'coordenador_id'    => 'required',
            'tasks'             => 'required'
        ];

        if(is_null($request->get('avulso'))){
            $regras['imagens'] = 'required';
            $request['avulso'] = 0;
        }

        $this->validate($request, $regras);

        try {

            DB::beginTransaction();

            $valor = $request->has('valor_job') && !empty($request->get('valor_job'))
                        ? str_replace(",",".", str_replace([".", "R$ "], "", $request->get('valor_job')))
                        : null;

            $job = Job::find($id);
            
            $colab_origi  = $job->getOriginal('delegado_para')   ?? false;
            $coord_origi  = $job->getOriginal('coordenador_id') ?? false;
            
            //se veio um delegado no request e o job nao tem delegado, mudar status do job para 2
            if($request->get('delegado_para')!= -1 && $job->delegado_para==null) {
                $job->status = 2;
            }

            //dd($request);
            $job->fill($request->all());
            $job->valor_job = $valor;

            $colab = $request->get('delegado_para')  == -1 ? null : User::where('id', $request->get('delegado_para'))->get()->first();
            $coord = $request->get('coordenador_id') == -1 ? null : User::where('id', $request->get('coordenador_id'))->get()->first();

            $job->delegado_para  = $colab ? $colab->id : null;
            $job->coordenador_id = $coord ? $coord->id : null;

            if( $job->deliveryformat_id != $request->get('deliveryformat_id')) {
                $job->deliveryformat_id = $request->get('deliveryformat_id');
            }
            // 'job_delivery_value'     => $request->get('job_delivery_value'),
            // 'deliveryformat_id'     => $request->get('deliveryformat_id'),


            $job->save();

            //dd($job->descricao);

            $job->imagens()->sync($request->get('imagens'));

            $taskNovas =[];
            foreach ($request->get('tasks') as $task) {
                if(!$job->tasks()->find($task)) {
                    $taskNovas [] = Task::find($task);
                }
            }

            $taskdetached = $job->tasks()->sync($request->get('tasks'));
            $taskDeletadas =[];
            foreach ($taskdetached['detached'] as $task) {
               $taskDeletadas [] = Task::find($task);
            }
            # Ordena
            $ordem = 1;
            foreach ($request->get('tasks') as $task) {
                $job->tasks()->updateExistingPivot($task, ['ordem' => $ordem]);
                $ordem++;
            }

            // Busco o tipo de job
            $tipo = TipoJob::find($request->get('tipojob_id'));

            // Este tipo define finalizador?
            if($tipo->finalizador && !$job->avulso){
                // Se for do tipo que define, verifico se veio o delegado para
                // if($request->get('delegado_para') != -1){
                    // Pego todas as imagens deste job
                    foreach ($request->get('imagens') as $img) {
                        $img = Imagem::find($img);
                        // Altera o finalizador_id da imagem para o id do delegado para
                        $tipo->finalizador && $job->delegado_para ? $img->finalizador_id = $job->delegado_para : '';
                        $tipo->revisao     ?  $img->status_revisao = $request->get('tipojob_id')    : '';
                        $img->save();
                    }
                // }
                // Retirada trava a pedido do cliente
                // Senao veio, manda msg de erro
                // else {
                //     $request->session()->flash('message.level', 'erro');
                //     $request->session()->flash('message.content', __('messages.Tipo de Job precisa de um usuário delegado para o job') . '.');
                //     $request->session()->flash('message.erro', '');
                //     return redirect()->back()->withInput();
                // }
            }

            # Thumb
            if($request->hasFile('thumb_ref') && $request->file('thumb_ref')->isValid()){
                # pega arquivo thumb
                $thumb = $request->file('thumb_ref');
                # prepara nome do job para pasta
                $nome_job = '_' . Controller::tirarAcentos( str_replace(' ', '_', $job->nome));
                # monta o caminho da pasta
                $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'jobs' . DIRECTORY_SEPARATOR . $job->id . $nome_job;
                # retirar acentos e espaços do nome do arquivo
                $nome = Controller::tirarAcentos( str_replace(' ', '_', $thumb->getClientOriginalName()) );
                # salva arquivo na pasta
                $upload = $thumb->storeAs($pasta_midias, $nome);
                # retira 'public/' do caminho do arquivo para salvar no banco de dados
                $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);
                // dd($upload);
                # se fez o upload atualiza o thumb
                if($upload){
                    $job->thumb = $pasta_midias . DIRECTORY_SEPARATOR .  $nome;
                    $job->save();
                }
            }

            #referências
            if(!empty($request->allFiles()) && array_key_exists('arquivos_ref', $request->allFiles())){
                $arquivos  = $request->allFiles()['arquivos_ref'];
                $dados_img = $request->get('novas_ref');
                $count     = 0;
                $nome_job  = Controller::tirarAcentos( str_replace(' ', '_', $request->get('nome')) );

                foreach ($arquivos as $file){

                    # monta o caminho da pasta
                    $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'midias' . DIRECTORY_SEPARATOR . $nome_job;
                    # retirar acentos e espaços do nome do arquivo
                    $nome = Controller::tirarAcentos( str_replace(' ', '_', $file->getClientOriginalName()) );
                    # salva arquivo na pasta
                    $upload = $file->storeAs($pasta_midias, $nome);
                    # retira 'public/' do caminho do arquivo para salvar no banco de dados
                    $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);

                    if($upload){
                        
                        # nome do tipo de arquivo
                        $nome_tipo_arquivo = TipoArquivo::where('id', $dados_img['tipo_id'][$count])->get()->first()->nome;

                        $arquivo = Midia::create([
                            'tipo_arquivo_id' => $dados_img['tipo_id'][$count],
                            'nome'            => $nome_tipo_arquivo,
                            'caminho'         => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                            'descricao'       => $nome_tipo_arquivo . ' do Job',
                            'nome_original'   => $nome,
                            'nome_arquivo'    => $nome
                        ]);
                        # vincula ao job a midia recém inserida
                        $job->midias()->attach($arquivo->id);

                    } else {
                        $request->session()->flash('message.level', 'erro');
                        $request->session()->flash('message.content', __('messages.Problema ao salvar arquivos de referência') . '.');
                        $request->session()->flash('message.erro', 'Falha ao salvar o arquivo ' . $nome . ' na pasta ' . $pasta_midias);
                    }
                    $count++;
                }
            }

            #notificação dos envolvidos
            $rota = route('jobs.show', encrypt($job->id));
            $novo_colab = $colab && $colab->id != $colab_origi;
            $novo_coord = $coord && $coord->id != $coord_origi;
            
            if(!$job->avulso) {
                $proj_id    = $job->imagens() && $job->imagens()->first()->projeto ? $job->imagens()->first()->projeto->id : '-1';
            }
            else{
                $proj_id  = 1;
            }
            
            $proj       = Projeto::where('id', $proj_id)->with(['coordenador'])->get()->first() ?? false;
            $coord_proj = $proj && $proj->coordenador ? $proj->coordenador : false;

            $param = array(
                'cliente'       => $proj ? $proj->cliente : null, 
                'imagem'        => $job->imagens() ? $job->imagens()->get() : false, 
                'job'           => $job, 
                'task'          => null, 
                'projeto'       => $proj, 
                'tipo'          => '',
                'destinatario'  => $colab, 
                'rota'          => $rota,
            );

            // se existe colaborador e não for o mesmo do anterior, avisa ele
            if($novo_colab) {

                # notificação ao novo colaborador 
                $param['tipo'] = "job_colab_novo_vc";

                $notificacao = new UserNotification($param);
                //dd($colab);
                
                $colab->notify(new AlertAction($notificacao));

                # notificação do novo colaborador ao coordenador
                if($coord && $coord->id != \Auth::id()){
                    //Comentado dia 09-04-2020 para analise do cliente

                    // $param['tipo'] = "job_colab_novo_outros";
                    // $notificacao = new UserNotification($param);
                    // $coord->notify(new AlertAction($notificacao));
                }

                # notificação do novo colaborador ao coordenador do projeto se nao for o mesmo
                if($coord_proj && ($coord && $coord->id != $coord_proj->id) && $coord_proj->id != \Auth::id() ){
                    //Comentado dia 09-04-2020 para analise do cliente
                    // $param['tipo'] = "job_colab_novo_outros";;
                    // $notificacao = new UserNotification($param);
                    // $coord_proj->notify(new AlertAction($notificacao));
                }
            }

            // se existe coordenador pro job, não for igual ao antigo e não for o usuário corrente, avisa ele
            if($novo_coord) {
                # notificação ao colaborador do novo coordenador
                if($colab){
                    //Comentado dia 09-04-2020 para analise do cliente
                    // $param['tipo'] = "job_coord_novo_outros";;
                    // $notificacao = new UserNotification($param);
                    // $colab->notify(new AlertAction($notificacao));
                }

                # notificação ao novo coordenador
                if($coord->id != \Auth::id()){
                //Comentado dia 09-04-2020 para analise do cliente
                    // $param['tipo'] = "job_coord_novo_vc";;
                    // $notificacao = new UserNotification($param);
                    // $coord->notify(new AlertAction($notificacao));

                }

                # notificação ao novo coordenador
                if($coord_proj && $coord_proj->id != \Auth::id()){
                //Comentado dia 09-04-2020 para analise do cliente
                    // $param['tipo'] = "job_coord_novo_outros";;
                    // $notificacao = new UserNotification($param);
                    // $coord_proj->notify(new AlertAction($notificacao));
                }
            }

            // $rota = route('jobs.show', encrypt($job->id));
            // if($job['delegado_para'] && $colab_origi != $job['delegado_para']) {
            //     $destinatario = User::where('id',$job['delegado_para'])->get()->first();
            //     $tipo = "delegado_job";
            //     $destinatario->notify(new AlertAction($destinatario, $rota, $tipo));
            // }

            // if($job['coordenador_id'] && $coord_origi != $job['coordenador_id']) {
            //     $destinatario = User::where('id',$job['coordenador_id'])->get()->first();
            //     $tipo = "coodernador_job";
            //     $destinatario->notify(new AlertAction($destinatario, $rota, $tipo));
            // }
           // notificação  taskNovas;
            if($taskNovas){
                $rota = route('jobs.show',encrypt($job->id));
                $tipo = "job_task_nova";
                
                $param = array(
                    'cliente'       => $proj->cliente ?? false, 
                    'imagem'        => $job->imagens()->get() ?? false, 
                    'job'           => $job, 
                    'task'          => $taskNovas, 
                    'projeto'       => $proj ?? false, 
                    'tipo'          => $tipo,
                    'destinatario'  => $colab, 
                    'rota'          => $rota,
                );

                $notificacao = new UserNotification($param);
                $colab->notify(new AlertAction($notificacao));

            }

            //notificações task retiradas do job
            if($taskDeletadas){
                $rota = route('jobs.show', encrypt($job->id));
                $tipo = "job_task_excluida";
               
                $param = array(
                    'cliente'       => $proj? $proj->cliente : null, 
                    'imagem'        => $job->imagens()->get() ?? false,  
                    'job'           => $job, 
                    'task'          => $taskDeletadas, 
                    'projeto'       => $proj ?? false,
                    'tipo'          => $tipo,
                    'destinatario'  => $colab, 
                    'rota'          => $rota,
                );

                $notificacao = new UserNotification($param);
                $colab->notify(new AlertAction($notificacao));

            }


            DB::commit();

             # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', __('messages.O job foi atualizado com sucesso') . '!');
            $request->session()->flash('message.erro', '');

        }catch(Exception $exception) {
            DB::rollback();
            // dd($exception);
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('messages.O projeto não pode ser atualizado') . '!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->route('jobs.show', encrypt($job->id));
    }
    
    public function destroy($id) {
        $id = decrypt($id);
        try{
            DB::beginTransaction();

            $job = Job::with(['tipo', 'imagens'])->findOrFail($id);

            if(!$job->avulso) {
                $projeto = $job->imagens()->first()->projeto;
                if ($job->tipo->finalizador || $job->tipo->revisao) {

                    foreach ($job->imagens as $img) {
                        $status_revisao = $img->jobs->where("tipo.revisao", "1")->whereNotIn("id", [$id])->sortByDesc("created_at");
                        if(count($status_revisao)>0)
                        {
                            $img->status_revisao = $status_revisao->first()->tipo->id;

                        }
                        else
                        {
                            $img->status_revisao = null;
                        }
                        $job->tipo->finalizador ? $img->finalizador_id = null : '';
                        $img->save();
                    }
                }
            }

            $colab = $job->delegado_para == -1 ? null : User::where('id', $job->delegado_para)->get()->first();;
            
            $coord = $job->coordenador_id == -1 ? null : User::where('id', $job->coordenador_id)->get()->first();;

            #notificação dos envolvidos - job deletado
            if($job->avulso) {
                //enviar para tela de todas notificações a ser criada
                $rota = route('notifications.index');
            }else{   
                $projeto = $job->imagens()->first()->projeto;
                $rota = route('projetos.show', encrypt($projeto->id));            
            }
            // $nome_obj   = $job->id;

            $param = array(
                'cliente'       => isset($projeto) && $projeto ? $projeto->cliente : null, 
                'imagem'        => $job->imagens() ? $job->imagens()->get() : false,   
                'job'           => $job, 
                'task'          => null, 
                'projeto'       => isset($projeto) && $projeto ? $projeto : false,
                'tipo'          => null,
                'destinatario'  => $colab, 
                'rota'          => $rota,
            );

            // se existe colaborador e não for o mesmo do anterior, avisa ele
            if($colab) {
                # notificação ao novo colaborador 
                $param['tipo'] = "job_excluido_proj";
                $notificacao = new UserNotification($param);
                $colab->notify(new AlertAction($notificacao));
            
            }
            if($coord) {
                # notificação ao novo coordenador 
                //Comentado dia 09-04-2020 para analise do cliente
                // $param['tipo'] = "job_excluido_proj";
                // $notificacao = new UserNotification($param);
                // $coord->notify(new AlertAction($notificacao));
            }

            //fim notificação

            $job->delete();

            DB::commit();

            # status de retorno
            session()->flash('message.level', 'success');
            session()->flash('message.content', __('messages.Job excluído com sucesso') . '!');
            session()->flash('message.erro', '');

            if(!$job->avulso) {
                return redirect()->route('projetos.show', encrypt($projeto->id));
            }else{
                return redirect()->route('home');
            }

        } catch (\Exception $exception){

            DB::rollBack();

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.O Job não pôde ser excluído') . '.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back();
        }
    }
    public function progresso($id) {
        $id = decrypt($id);
        try{
            if(!isset($id)) {
                return false;
            }
            $progresso = Job::find($id)->concluido();
            if(request()->ajax()) {
                return \Response::json(array(
                    'code'      =>  200,
                    'progresso' =>  $progresso
                ), 200);
            }
        }catch (\Exception $exception){

            if(request()->ajax()) {
                return \Response::json(array(
                    'code'      =>  500,
                    'message'   =>  'Não foi possível recuperar o progresso. ' . $exception->getMessage()
                ), 500);
            }
        }
        return redirect()->back();
    }  
    public function progressoRevisao($id) {
        $id = decrypt($id);
        try{
            if(!isset($id)) {
                return false;
            }
            $progresso = Job::find($id)->concluidoRevisao();
            if(request()->ajax()) {
                return \Response::json(array(
                    'code'      =>  200,
                    'progresso' =>  $progresso
                ), 200);
            }
        }catch (\Exception $exception){

            if(request()->ajax()) {
                return \Response::json(array(
                    'code'      =>  500,
                    'message'   =>  'Não foi possível recuperar o progresso. ' . $exception->getMessage()
                ), 500);
            }
        }
        return redirect()->back();
    }
    public function abertos() {

        $this->user_current = \Auth::user()->roles()->first();
        $this->user_id = \Auth::user()->id;

        if($this->user_current->name == "desenvolvedor" || $this->user_current->name == "admin") {
            $jobs = Job::with('coordenador')->with('delegado')->whereIn('status', [0,9] )->get();
        
        }else if($this->user_current->name == "publicador") {
            $jobs = \Auth::user()->jobsPublicadosAbertos($this->user_current->name)->with(['delegado', 'coordenador'])->get();
        
        }else if($this->user_current->name == "coordenador") {
            $jobs = \Auth::user()->jobsPublicadosAbertos($this->user_current->name)->with(['delegado', 'coordenador'])->get();
        
        }else if($this->user_current->name == "freelancer"){
            $jobs = Job::whereIn('status', [0,9])
                        ->where('freela', 1)
                        ->where('delegado_para', null)
                        ->with(['user', 'coordenador'])
                        ->get();
        }else {
            $jobs = \Auth::user()
                            ->jobs()
                            ->with(['coordenador', 'delegado'])
                            ->where('jobs.status', [0,9])
                            ->get();
        }

        foreach ($jobs as $value) {
            // $value->valor_desconto = floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
            $value->valor_desconto = $value->valorDoJob($this->user_current->name);
        }
                
        // $jobs = Job::with('coordenador')->whereIn('status', [0,1])->get();
        $statusarray = Job::$status_array;
        $titulo      = 'Abertos';
        $role        = $this->user_current;
        $concluir_job = false;
        return view('job.lista', compact('titulo', 'jobs', 'statusarray', 'role','concluir_job'));
    }
    public function emExecucao() {
        
        $role = \Auth::user()->roles()->first();
        // $this->user_id      = \Auth::user()->id;
        $this->user_current = $role->name;

        if($this->user_current == "desenvolvedor" || $this->user_current == "admin") {
            $jobs = Job::with('coordenador')->with('delegado')->whereIn('status', [7,2] )->get();
            // dd($jobs);
        }else if($this->user_current == "publicador" || $this->user_current == "coordenador") {
            $jobs = \Auth::user()->jobsPublicadosExecutando($this->user_current)->with(['delegado', 'coordenador'])->get();
        
        }else {
            $jobs = \Auth::user()
                            ->jobs()
                            ->with('delegado')
                            ->whereIn('jobs.status', [2,7])
                            ->get();
        }

        foreach ($jobs as $value) {
            // $value->valor_desconto = floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
            // Calcula o valor do Job para freelancer(valor desconto). (valor do job - valor*taxa/100)
            // dd($this->user_current);
            $value->valor_desconto = $value->valorDoJob($this->user_current);
            $value->pode_concluir = false;

            //Se o jog não é recusado, parado ou concluido e 
            //seu progresso é igual ou maior que 100% 
            //ele pode ser concluido pela lista
            if(!$value->verificaStatus('recusado') && !$value->verificaStatus('concluido') && !$value->verificaStatus('parado') && $value->concluido()>=100) {
                $value->pode_concluir = true;
                $concluir_job = true;
            }
        }
        $statusarray = Job::$status_array;
                
        // $jobs = Job::with('coordenador')->whereIn('status', [0,1])->get();
        $titulo      = 'Em Execução';
        return view('job.lista', compact('titulo', 'jobs', 'statusarray', 'role','concluir_job'));

    }
    public function recusados() {

        $this->user_current = \Auth::user()->roles()->first();
        $this->user_id = \Auth::user()->id;

        if($this->user_current->name == "desenvolvedor" || $this->user_current->name == "admin") {
            $jobs = Job::with('delegado')->where('status', 6 )->get();
        
        }else if($this->user_current->name == "publicador" || $this->user_current->name == "coordenador"  ) {
            $jobs = \Auth::user()->jobsPublicadosRecusados($this->user_current->name)->with(['delegado'])->get();
        
        // }else if($this->user_current->name == "freelancer"){
        //     $jobs = Job::where('status', 6)
        //                 ->where('freela', 1)
        //                 ->where('delegado_para', $this->user_id)
        //                 ->with('user')
        //                 ->get();
        }else {
            $jobs = \Auth::user()
                            ->jobsRecusados()
                            ->with('delegado')
                            ->get();
        }

        foreach ($jobs as $value) {
            // $value->valor_desconto = floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
            $value->valor_desconto = $value->valorDoJob($this->user_current->name);
        }
                
        // $jobs = Job::with('coordenador')->whereIn('status', [0,1])->get();
        $titulo      = 'Recusados';
        $role        = $this->user_current;
        $statusarray = Job::$status_array;
        $concluir_job = false;
        return view('job.lista', compact('titulo', 'jobs', 'statusarray', 'role','concluir_job'));

    }
    
    public function emAndamento() {

        $this->user_current = \Auth::user()->roles()->first();
        $this->user_id = \Auth::user()->id;

        if($this->user_current->name == "desenvolvedor" || $this->user_current->name == "admin") {
            $jobs = Job::with('coordenador')->with('delegado')->whereNotIn('status', [0,1,5,6,8] )->get();
        }
        else if($this->user_current->name == "publicador") {
            $jobs = Job::with('coordenador')->with('delegado')->whereNotIn('status', [0,1,5,6,8] )->where('user_id', $this->user_id)->get();
        }    
        else if($this->user_current->name == "freelancer"){
            $jobs = \Auth::user()->jobs()->with('delegado')->whereNotIn('jobs.status', [0,1,5,6,8] )->get();
        }
        else {
            $jobs = \Auth::user()->jobs()->with(['coordenador', 'delegado'])->whereNotIn('jobs.status', [0,1,5,6,8] )->get();
        }

        foreach ($jobs as $value) {
            // $value->valor_desconto = floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
            $value->valor_desconto = $value->valorDoJob($this->user_current->name);
        }
                
        $statusarray = Job::$status_array;
        $titulo      = 'Em Andamento';
        $role        = $this->user_current;
        $concluir_job = false;
        return view('job.lista', compact('titulo', 'jobs', 'statusarray', 'role','concluir_job'));
    }
    
    public function todos() {

        $this->user_current = \Auth::user()->roles()->first();
        $this->user_id = \Auth::user()->id;
        $user_role_name = $this->user_current->name;
        
        $jobtemp = [];
        $job     = [];

        $this->user_publicador_id = \Auth::user()->publicador_id;
        
        //echo $this->user_current->name;
        if($user_role_name == "desenvolvedor" || $user_role_name == "admin"  || ($user_role_name == "coordenador" && $this->user_publicador_id ==null)) 
        {
            $jobtemp = Job::with('coordenador')->with('delegado')->where('status', '!=', Job::$status_array['pagamentopendente']);
        }
        else if($user_role_name == "freelancer" || $user_role_name == "equipe" ) {
            $jobtemp =  Job::with('coordenador')->with('delegado')->where('delegado_para',$this->user_id)->where('status', '!=', Job::$status_array['pagamentopendente']);
        }
        else if($user_role_name == "publicador") {
            $jobtemp = Job::with('coordenador')->with('delegado')->where('user_id',$this->user_id)->where('status', '!=', Job::$status_array['pagamentopendente']);
        }       
        else if($user_role_name == "coordenador" && $this->user_publicador_id !=null) {
            $jobtemp = Job::with('coordenador')->with('delegado')->where('status', '!=', Job::$status_array['pagamentopendente'])->where('publicador_id', $this->user_publicador_id);
        }

        $jobs = $jobtemp->get();
       
        foreach ($jobs as $value) {
            // $value->valor_desconto = floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
            $value->valor_desconto = $value->valorDoJob($user_role_name);
        }

        $statusarray = Job::$status_array;
        $titulo      = '';
        $role        = $this->user_current;
        $concluir_job = false;
        return view('job.lista', compact('titulo', 'jobs', 'statusarray', 'role','concluir_job'));
    }
    
    public function concluidos() {

        $this->user_current = \Auth::user()->roles()->first();

        $job =[];

        if($this->user_current->name == "desenvolvedor" || $this->user_current->name == "admin") {
            $jobs = Job::with('delegado')->where('status', 5 )->get();
        
        } elseif($this->user_current->name == "publicador" || $this->user_current->name == "coordenador"){
            $jobs = \Auth::user()->jobsPublicadosConcluidos($this->user_current->name)->with('delegado')->get();
        }else {
            $jobs = \Auth::user()->jobs()->with('delegado')->where('status', 5 )->get();
        }       

        foreach ($jobs as $value) {
            // $value->valor_desconto = floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
            $value->valor_desconto = $value->valorDoJob($this->user_current->name);
        }
        $statusarray = Job::$status_array;
        $titulo      = 'Concluídos';
        $role        = $this->user_current;
        $concluir_job = false;
        return view('job.lista', compact('titulo', 'jobs', 'statusarray', 'role','concluir_job'));
    }
    
    // @deprecated
    public function concluir($id){
        $id = decrypt($id);
        // dd($id);

        try{    
            $usuario_ativo  = \Auth::user();

            if(!$usuario_ativo->can('concluir-job'))
            {
                # status de retorno
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('messages.Você não tem permissão para concluir o Job') . '.');
                session()->flash('message.erro', '<br>');

                  return redirect()->back()->withInput();
            }


            DB::beginTransaction();

            $job = Job::where('id', $id)->get()->first();
            // dd($job->projeto());

            $job->status = 5; #5-concluído
            $job->concluido_por = \Auth::user()->id;
            $job->data_entrega = Carbon::now();
            $job->save();

            if($job->freela == 1){
                // busco a conta do individuo delegado
                $conta = UserConta::where('user_id', $job->delegado_para->id)->first();
                
                $valor_taxa = floatval($job->valor_job)*floatval($job->taxa)/100;
                $valor_para = floatval($job->valor_job) - $valor_taxa;


                // adiciono movimentação de credito para o freela
                $uf = UserFinanceiro::create([
                    'de_id' => $job->user_id,
                    'para_id' => $job->delegado_para,
                    'pagador_id' => \Auth::id(),
                    'centro_de_custo_id' => null,
                    'categoria_de_custo_id' => null,
                    'taxa' => $job->taxa,
                    'dados_bancarios' => $conta ?? null,
                    'valor_de' => $job->valor_job, 
                    'valor_para' => $valor_para,  
                    'valor_taxa' => $valor_taxa
                ]);
                
                // dd($uf);

                // notificações
            }

            # status de retorno
            session()->flash('message.level', 'success');
            session()->flash('message.content', __('messages.Job concluído com sucesso') . '!');
            session()->flash('message.erro', '');


            DB::commit();

            if($job->avulso==1){

                return redirect()->route('jobs.show', encrypt($id));
            
            }else{

                return redirect()->route('projetos.show', encrypt($job->projeto()->id));
            }
        } 
        catch (\Exception $exception){

            DB::rollBack();

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Job não pode ser concluído') . '.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }
    // @deprecated
    public function reabrir($id) {
        $id = decrypt($id);
        try{

            DB::beginTransaction();

            $job = Job::where('id', $id)->with(['delegado', 'coordenador', 'imagens'])->get()->first();
            // dd($job);

            if(!$job){
                # status de retorno
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('messages.Job não encontrado') . '.');
                session()->flash('message.erro', '<br>');

                return redirect()->back()->withInput();
            } else {
                
                $job->status = 7; #5-concluído
                $job->concluido_por = null;
                $job->data_entrega = null;
                $job->save();

                // dd($job);

                # Notificações
                $rota = route('jobs.show', encrypt($job->id));
                // $nome_obj   = $job->id;
                $coord_proj = $job->imagens->count() > 0 ? $job->imagens->first()->projeto->coordenador : false;

                #notificação dos envolvidos - job reaberto

                $param = array(
                    'cliente'       => null, 
                    'imagem'        => null, 
                    'job'           => $job, 
                    'task'          => null, 
                    'projeto'       => null, 
                    'tipo'          => null,
                    'destinatario'  => $colab, 
                    'rota'          => $rota,
                );


                $param['tipo'] = "job_reaberto";
                $notificacao = new UserNotification($param);
                
                if($job->delegado){
                    $job->delegado->notify(new AlertAction($notificacao));
                }

                if($job->coordenador && $job->coordenador->id != \Auth::id()){
                    //Comentado dia 09-04-2020 para analise do cliente
                    // $job->coordenador->notify(new AlertAction($notificacao));
                }

                if($coord_proj && $coord_proj->id != \Auth::id()){
                    //Comentado dia 09-04-2020 para analise do cliente
                    // $coord_proj->notify(new AlertAction($notificacao));
                }


                DB::commit();

                # status de retorno
                session()->flash('message.level', 'success');
                session()->flash('message.content', __('messages.Job reaberto com sucesso') . '!');
                session()->flash('message.erro', '');
            }

            return redirect()->route('jobs.show', encrypt($id));
        } 
        catch (\Exception $exception){

            DB::rollBack();
            
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Job não pode ser reaberto') . '.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    //ToDo:Função mudar status / usar como base para mudar reabrir e concluir()
    public function mudarStatus($id, $novoStatus, Request $request) {
        $id = decrypt($id);
        try{

            $usuario_ativo  = \Auth::user();

            DB::beginTransaction();

            $job = Job::where('id', $id)->with(['delegado', 'coordenador', 'imagens'])->get()->first();

            //dd($novoStatus .' - ' .$job);

            $job->status = $novoStatus; 

            if($novoStatus==7)
            {
                $job->concluido_por = null;
                $job->data_entrega = null;
            }

            if($novoStatus==5 && !$usuario_ativo->can('concluir-job'))
            {
                # status de retorno
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('messages.Você não tem permissão para concluir o Job') . '.');
                session()->flash('message.erro', '<br>');

                  return redirect()->back()->withInput();
            }

            
            $job->save();

           

            # Notificações
            $rota = route('jobs.show', encrypt($job->id));

            $this->user_current = \Auth::user()->roles->first();

            # status de retorno
            //se o novo status for 6 (recusado) insere informações na tabela job recusados
            if($novoStatus==6){
                $jobRecusado = JobRecusado::create([
                    'job_id'        => $job->id,
                    'user_id'       => \Auth::user()->id,
                    'delegado_id'   => $job->delegado_para,
                    'causa'         => $request->get('causa')
                ]);

                $tipo = 'job_recusado_freela';

                $param = array(
                    'cliente'       =>  null, 
                    'imagem'        =>  null, 
                    'job'           => $job, 
                    'task'          => null, 
                    'projeto'       => null, 
                    'tipo'          => $tipo,
                    'destinatario'  => $job->delegado, 
                    'rota'          => $rota,
                );

                $notificacao = new UserNotification($param);
                //notificação para o freela do job                
                if($job->delegado){
                    $job->delegado->notify(new AlertAction($notificacao));
                }
                
                //notificação 
                $user_adm = User::role(['admin'])->get(); //->where('publicador_id', null);
                    
                if($user_adm) { 
                    $notificacao = new UserNotification($param);
                    $param['tipo'] = "job_recusado_admin";

                    foreach ($user_adm as $key => $value) {
                       
                        if($value->id != \Auth::id()){
                            //Comentado dia 09-04-2020 para analise do cliente
                            echo $value->id;
                            $param['destinario'] = $value;
                            $value->notify(new AlertAction($notificacao));                          
                        }
                    }
                }

            }else
            //se o novo status for 8 (parado) insere informações na tabela job
            if($novoStatus==8){

                $jobParado = JobParado::create([
                    'job_id'        => $job->id,
                    'user_id'       => \Auth::user()->id,
                    'delegado_id'   => $job->delegado_para,
                    'motivo'         => $request->get('motivo')
                ]);
            }else
            // se o novo status for 5 (concluido) e for job de freelancer, insere informações de pagamento
            if($novoStatus==5) {
                //salva a data de entrega quando o job for concluido
                $job->data_entrega = Carbon::now();
                $job->concluido_por = \Auth::user()->id;
                $job->save();
                //dd($job);
                if($job->freela==1){

                    

                    //Se o job tem status inicial em candidatura/proposta busca as candidatura do freela 
                    //e muda as que são status 4 (sem slot) para status 0 (aberto)
                    if($job->status_inicial == Job::$status_array['emcandidatura'] || $job->status_inicial == Job::$status_array['emproposta']) {
                        $job_cand_trocar = JobCandidatura::where('user_id', $job->delegado_para)->where('status', 4)->get();

                        if($job_cand_trocar) {
                            foreach ($job_cand_trocar as $value) {
                                $value->status = 0; 
                                $value->save();
                            }
                        }
                    }

                    // busco a conta do individuo delegado
                    // $conta = UserConta::where('user_id', $job->delegado_para)->first();
                    $conta = json_encode(array('conta_paypal' => $job->delegado->conta_paypal));
                    // dd($conta);
                    $valor_taxa = floatval($job->valor_job)*floatval($job->taxa)/100;
                    $valor_para = floatval($job->valor_job) - $valor_taxa;
                    $valor =  !empty($job->valor_job) ? str_replace(",",".", str_replace([".", "R$ "], "", $job->valor_job)) : null;

                    // adiciono movimentação de credito para o freela
                    $uf = UserFinanceiro::create([
                        'de_id' => $job->user_id,
                        'para_id' => $job->delegado_para,
                        'pagador_id' => null,
                        'model_id' => $job->id,
                        'model_type' => 'job',
                        'centro_de_custo_id' => null,
                        'categoria_de_custo_id' => null,
                        'taxa' => $job->taxa,
                        'dados_bancarios' => $conta ?? null,
                        'valor_de' => $valor, 
                        'valor_para' => $valor_para,  
                        'valor_taxa' => $valor_taxa
                    ]);

                    //Seta data de entrega do job como data da ultima avaliação
                    //transformar data entrega em datetime
                    //$jobb->data_entrega = $job->avaliacoes()->get()->last()->creatad_at;

                    //pagamento notificação
                    $tipo = 'job_concluido_freela';

                    $param = array(
                        'cliente'       =>  null, 
                        'imagem'        =>  null, 
                        'job'           => $job, 
                        'task'          => null, 
                        'projeto'       => null, 
                        'tipo'          => $tipo,
                        'destinatario'  => $job->delegado, 
                        'rota'          => $rota,
                    );
    
                    $notificacao = new PaymentNotification($param);
                    //notificação para o freela do job                
                    if($job->delegado){
                        $job->delegado->notify(new PaymentAlert($notificacao));
                    }
    
                    //notificação 
                    $user_adm = User::role(['admin'])->get(); //->where('publicador_id', null);
                    

                    if($user_adm) { 
                        $notificacao = new PaymentNotification($param);
                        $param['tipo'] = "job_concluido_admin";

                        foreach ($user_adm as $key => $value) {
                           
                            if($value->id != \Auth::id()){
                                //Comentado dia 09-04-2020 para analise do cliente
                                $param['destinario'] = $value;
                                $value->notify(new PaymentAlert($notificacao));
                            }
                        }
                    }
                    
                    if($this->user_current->name == 'coordenador') {
                        $tipo = 'job_concluido_freela_por_coordenador';

                        $publicador_job = $job->user()->get()->first();
                        $param = array(
                            'cliente'       =>  null, 
                            'imagem'        =>  null, 
                            'job'           => $job, 
                            'task'          => null, 
                            'projeto'       => null, 
                            'tipo'          => $tipo,
                            'destinatario'  => $publicador_job, 
                            'rota'          => $rota,
                        );
        
                        $notificacao = new PaymentNotification($param);
                        //notificação para o freela do job                
                        $publicador_job->notify(new PaymentAlert($notificacao));
                    }
                    //dd($publicador_job);
                   
    

                // }else{
                    // $data_atual = Carbon::now();
                    // dd($data_atual->toDateString());
                    //transformar data entrega em datetime
                    //$jobb->data_entrega = $data_atual->toDateString();
                }
                
            }


            // dd($job);

           
            // $nome_obj   = $job->id;
            $coord_proj = $job->imagens->count() > 0 ? $job->imagens->first()->projeto->coordenador : false;
            $proj = null;
            
            if(!$job->avulso){
                $proj = $job->imagens->count() > 0 ? $job->imagens->first()->projeto : false;
            }

            $tipo = false;
            switch ($novoStatus) {
                case 5 && $job->freela==0:
                    $tipo = 'job_concluido';
                    break;

                case 6:
                    $tipo = 'job_recusado';
                    break;
                
                case 7:
                    $tipo = 'job_reaberto';
                    break;

                case 8:
                    $tipo = 'job_parado';
                    break;
    
                default:
                    $tipo = false;
                    break;
            }
            // dd($tipo);
            if($tipo){
                # informa se houver o delegado
                 $param = array(
                    'cliente'       => $proj ? $proj->cliente : null, 
                    'imagem'        => $job->imagens ? $job->imagens : null, 
                    'job'           => $job, 
                    'task'          => null, 
                    'projeto'       => $proj ? $proj : null, 
                    'tipo'          => $tipo,
                    'destinatario'  => $job->delegado, 
                    'rota'          => $rota,
                );

                $notificacao = new UserNotification($param);
                
                if($job->delegado &&  $job->user->id != \Auth::id()){
                    $job->delegado->notify(new AlertAction($notificacao));
                }

                //se o usuário não é o coordenador do job, notifica o coordenador
                if($job->coordenador && $job->coordenador->id != \Auth::id()){
                    $param['destinario'] = $job->coordenador;
                    $job->coordenador->notify(new AlertAction($notificacao));
                }

                //se o usuário é o coordenador do job e não é o publicador do job, notifica o publicador
                if($job->coordenador && $job->coordenador->id == \Auth::id() &&  $job->user->id != \Auth::id()){
                    $param['destinario'] = $job->user;
                    $job->user->notify(new AlertAction($notificacao));
                }

                if($coord_proj && $coord_proj->id != \Auth::id()){
                    //Comentado dia 09-04-2020 para analise do cliente
                    // $coord_proj->notify(new AlertAction($notificacao));
                }
            }

            $frase_job_concluido =  __('messages.Status do Job alterado com sucesso'). '!';
            if($novoStatus==5 &&$job->freela==1 ){
                $frase_job_concluido =  __('messages.Status do Job alterado com sucesso'). '! '.__('messages.O freelancer receberá o pagamento');
            }

            session()->flash('message.level', 'success');
            session()->flash('message.content', $frase_job_concluido ); #. Job::$status_array[$novoStatus] .
            session()->flash('message.erro', '');

            
            DB::commit();

            if($job->avulso==1)
            {
                return redirect()->route('jobs.show', encrypt($id));
            }
            else
            {

                return redirect()->route('projetos.show', encrypt($job->projeto()->id));
            }

        } 
        catch (\Exception $exception){

            DB::rollBack();

            // dd($exception);

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Status do Job não pôde ser alterado') . '.'); #. $job->status_array[$novoStatus]
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            // dd('<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }
    }

    public function prorrogarDataEntrega($id, Request $request) {
        $id = decrypt($id);
        try{

            DB::beginTransaction();

            $nova_data = $request->nova_data;

            $job = Job::with('revisoes')->where('id', $id)->get()->first();

            $job->data_prox_revisao = $nova_data; 
            $job->save();

            if($job->revisoes->count() > 0){
                $rev = $job->revisoes->last();
                $rev->data_entrega = $nova_data; 
                $rev->save(); 

            }

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('messages.Data de entrega prorogada com sucesso') . '!');
            session()->flash('message.erro', '');

            
            DB::commit();

            return redirect()->route('jobs.show', encrypt($id));

        }
        catch (\Exception $exception) {

            DB::rollBack();

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Data de entrega do Job não pode ser prorrogada'));
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            // dd('<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }
    }
    
    public function prorrogarDataProposta($id, Request $request) {
        $id = decrypt($id);
        try{

            DB::beginTransaction();
            
            $nova_data = $request->nova_data;

            $job = Job::where('id', $id)->get()->first();

            $job->data_limite = $nova_data; 
            $job->save();

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('messages.Data limite prorogada com sucesso') . '!');
            session()->flash('message.erro', '');

            
            DB::commit();

            return redirect()->route('jobs.show', encrypt($id));

        }
        catch (\Exception $exception) {

            DB::rollBack();

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Data limite do Job não pode ser prorrogada'));
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            // dd('<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }
    }

    public function mudarStatusVarios(Request $request) {

        try{

            DB::beginTransaction();

            //dd($request->job_selecionado);
            $job = Job::whereIn('id', $request->job_selecionado)->with(['delegado', 'coordenador', 'imagens'])->get();
            //dd($request->job_selecionado);
            //dd($job);
            $tipo = 'job_concluido';

            
            foreach ($job as $key => $value) {
                # code...
                $value->status = 5; 
                $value->concluido_por = \Auth::user()->id;
                $value->data_entrega = Carbon::now();
                $value->save();
                
                # Notificações
                $rota = route('jobs.show', encrypt($value->id));
                
                //dd($value->imagens->first()->projeto->coordenador);
                $coord_proj = $value->imagens->count() > 0 ? $value->imagens->first()->projeto->coordenador : false;
                $proj = null;
                
                if(!$value->avulso){
                    $proj = $value->imagens->count() > 0 ? $value->imagens->first()->projeto : false;
                }
            
                if($tipo){
                    # informa se houver o delegado
                    $param = array(
                        'cliente'       => $proj ? $proj->cliente : null, 
                        'imagem'        => $value->imagens ? $value->imagens : null, 
                        'job'           => $value, 
                        'task'          => null, 
                        'projeto'       => $proj ? $proj : null, 
                        'tipo'          => $tipo,
                        'destinatario'  => $value->delegado, 
                        'rota'          => $rota,
                    );

                    $notificacao = new UserNotification($param);
                    
                    if($value->delegado){
                        $value->delegado->notify(new AlertAction($notificacao));
                    }

                    if($value->coordenador && $value->coordenador->id != \Auth::id()){
                        //Comentado dia 09-04-2020 para analise do cliente
                        $param['destinario'] = $value->coordenador;
                        $value->coordenador->notify(new AlertAction($notificacao));
                    }

                    if($value->coordenador && $value->coordenador->id == \Auth::id()){
                        //Comentado dia 09-04-2020 para analise do cliente
                        $param['destinario'] = $value->user;
                        $value->user->notify(new AlertAction($notificacao));
                    }

                }
            }

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('messages.Status alterado com sucesso') . '!');
            session()->flash('message.erro', '');

            
            DB::commit();
            return redirect()->back()->withInput();

        } 
        catch (\Exception $exception){

            DB::rollBack();

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Job não pode ser concluído'));
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            // dd('<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }
    }

    public function addArquivo(Request $request){
        
        $validator = $this->validate($request,[
            'job_id'  => 'required',
            'arquivos' => 'required'
        ]);

        try{
            DB::beginTransaction();

            // dd($request->getFi());
            $job_id = decrypt($request->get('job_id'));
            
            if(!empty($request->allFiles()) && array_key_exists('arquivos', $request->allFiles())){
                $arquivos = $request->allFiles()['arquivos'];
                foreach($arquivos as $file){
                    if($file->isValid()){
                        $job       = Job::where('id', $job_id)->get()->first();
                        $nome_job  = $job->nome;

                        # monta o caminho da pasta
                        $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'jobs' . DIRECTORY_SEPARATOR . $job->id . $nome_job . DIRECTORY_SEPARATOR . 'midias';
                        # retirar acentos e espaços do nome do arquivo
                        $nome = Controller::tirarAcentos( str_replace(' ', '_', $file->getClientOriginalName()) );
                        # salva arquivo na pasta
                        $upload = $file->storeAs($pasta_midias, $nome);
                        # retira 'public/' do caminho do arquivo para salvar no banco de dados
                        $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);

                        if($upload){
                    
                            # nome do tipo de arquivo
                            $nome_tipo_arquivo = TipoArquivo::where('id', $request->get('tipo_id'))->get()->first()->nome;
        
                            $arquivo = Midia::create([
                                'tipo_arquivo_id' => $request->get('tipo_id'),
                                'nome'            => $nome_tipo_arquivo,
                                'caminho'         => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                                'descricao'       => $nome_tipo_arquivo . ' do Job',
                                'nome_original'   => $nome,
                                'nome_arquivo'    => $nome
                            ]);
                            # vincula ao job a midia recém inserida
                            $job->midias()->attach($arquivo->id);
                        }
                    }
                }
            }

            // if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid()) {
                
            //     $file = $request->file('arquivo');
            //     $job       = Job::where('id', $job_id)->get()->first();
            //     $nome_job  = $job->nome;

            //     # monta o caminho da pasta
            //     $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'midias' . DIRECTORY_SEPARATOR . $nome_job;
            //     # retirar acentos e espaços do nome do arquivo
            //     $nome = Controller::tirarAcentos( str_replace(' ', '_', $file->getClientOriginalName()) );
            //     # salva arquivo na pasta
            //     $upload = $file->storeAs($pasta_midias, $nome);
            //     # retira 'public/' do caminho do arquivo para salvar no banco de dados
            //     $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);

            //     if($upload){
                    
            //         # nome do tipo de arquivo
            //         $nome_tipo_arquivo = TipoArquivo::where('id', $request->get('tipo_id'))->get()->first()->nome;

            //         $arquivo = Midia::create([
            //             'tipo_arquivo_id' => $request->get('tipo_id'),
            //             'nome'            => $nome_tipo_arquivo,
            //             'caminho'         => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
            //             'descricao'       => $nome_tipo_arquivo . ' do Job',
            //             'nome_original'   => $nome,
            //             'nome_arquivo'    => $nome
            //         ]);
            //         # vincula ao job a midia recém inserida
            //         $job->midias()->attach($arquivo->id);

            //         DB::commit();

            //         session()->flash('message.level', 'success');
            //         session()->flash('message.content', __('messages.Arquivo adicionado com sucesso') . '.');
            //         session()->flash('message.erro', '');

            //     } else {
            //         session()->flash('message.level', 'erro');
            //         session()->flash('message.content', __('messages.Problema ao salvar arquivos de referência') . '.');
            //         session()->flash('message.erro', 'Falha ao salvar o arquivo ' . $nome . ' na pasta ' . $pasta_midias);
            //     }
            // }
            DB::commit();

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('messages.Arquivo adicionado com sucesso') . '.');
            session()->flash('message.erro', '');

        }catch(Exception $exception){
            DB::rollBack();
            dd($exception);
            Log::error('Problema ao salvar arquivos de referência: ' . $exception->getMessage() . ' Line: ' . $exception->getLine());

            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Problema ao salvar arquivos de referência') . '.');
            session()->flash('message.erro', '');
        }

        return redirect()->back();

    }
    
    public function desvincularArquivo(Request $request, $arquivo, $job) {

        // $request = new Request();
        // dd($request);
        try{
     
            $job = Job::find($request->get('job'));
            $job->midias()->detach($request->get('arquivo'));

            # status de retorno
            session()->flash('message.level', 'success');
            session()->flash('message.content', __('messages.Arquivo desvinculado com sucesso') . '!');
            session()->flash('message.erro', '');

        }catch(\Exception $exception){

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.O arquivo não pôde ser desvinculado deste job') . '!');
            session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }

    //  Publicar job (antigo Criar Job Avulso)
    public function createAvulso() {

        $clientes       = Cliente::all();
        $tipos_jobs     = TipoJob::all();
        $tipos_delivery = DeliveryFormat::all();
        $tasks          = Task::orderBy('nome', 'asc')->get();
        $tipos_arquivos = TipoArquivo::whereIn('nome', array('Referência', 'Boas Práticas', 'Exemplo'))->get();
        $configuracoes =  Configuracao::get()->first();



        // Politicas que poderao receber jobs: novo job / delegado para
        $usuarios       = User::role(['coordenador', 'avaliador', 'equipe', 'freelancer', 'admin', 'desenvolvedor'])->get();

        //role do usuário
        $this->user_current = \Auth::user()->roles->first();
        //id do usuário
        $this->user_id = \Auth::user()->id;
        //id dopublicador do usuário
        $this->publicador_id = \Auth::user()->publicador_id;

        //flag se o job é de publicador ou nao
        $job_publicador = false;

        //compara se usuario que esta publicando o job é um publicador (ou um coordenador de publicado) 
        if($this->user_current->name == 'publicador' || ($this->user_current->name == 'coordenador' && $this->publicador_id != null) ||
        ($this->user_current->name == "desenvolvedor" || $this->user_current->name == "admin") ) {
            $job_publicador = true;
        }
        //quantidade de jobs
        $qtd_job  = 0;
        $user_id = $this->user_id;

        if($job_publicador){

            //id  buscar os jobs do user_id ou do publicador_id
            $id_busca_job = $this->user_id;
            //campo para buscar os jobs do user_id ou do publicador_id
            $campo_busca_job = 'user_id';

            if($this->user_current->name == 'coordenador') {
                $id_busca_job = $this->publicador_id;
                $campo_busca_job = 'publicador_id';
            }
            //dd($id_busca_job . ' - ' . $campo_busca_job);

            $qtd_job  = Job::withTrashed()->where('user_id', $id_busca_job)->get()->count();
            $coordenadores  = User::role(['coordenador', 'admin'])->where('publicador_id', $id_busca_job)->get();
            
        }
        else {
            $coordenadores  = User::role(['coordenador', 'admin'])->where('publicador_id', null)->get();
        }            

        $publicador_id = $this->publicador_id ?? null;

        return view('job.novo_avulso', compact(['clientes', 'coordenadores', 'tipos_jobs', 'tipos_delivery','tasks', 'tipos_arquivos', 'usuarios', 'configuracoes','qtd_job','user_id','publicador_id','job_publicador']));
    }

    public function storeAvulso(Request $request) {
    
        $validator = $this->validate($request, [
            'nome'       => 'required',
            'tipojob_id' => 'required',
            'tasks'      => 'required'
        ]);

        try{

            DB::beginTransaction();

            $this->user_ativo = \Auth::user();
            //role do usuário
            $this->user_current = \Auth::user()->roles->first();
            //id do usuário
            $this->user_id = \Auth::user()->id;
            //id dopublicador do usuário
            $publicador_id = \Auth::user()->publicador_id;
            //flag se o job é de publicador ou nao
            $job_publicador = false;

            if($this->user_current->name == 'publicador') {
                $publicador_id = $this->user_id;
            }


            $qtd_jobs =  Job::where('publicador_id', $publicador_id)->get()->count();
            $novo_nome = "";
            
            //function cria o nome do job no backend para evitar repetição
            $nome_job = $this->formataNomeJob($publicador_id, $request->get('tipojob_id'), $qtd_jobs);

            $valor = $request->has('valor_job') && !empty($request->get('valor_job')) 
            ? str_replace(",",".", str_replace([".", "R$ "], "", $request->get('valor_job')))
            : null;

            $data_limite = $request->has('data_limite') && !empty($request->get('data_limite')) 
                ? $request->get('data_limite') . ' 23:59:59'
                : null;
            
           
            // Guarda status inicial do Job
            $status = Job::$status_array['pagamentopendente'];
            if($request->solicita_proposta) {
                $status = Job::$status_array['emproposta'];
                $status_inicial =  $status;
                $valor = 0;
                //se data_limte nao for definida coloca a data atual mais 7 dias
                if($data_limite ==null) {
                    $data_limite = Carbon::now()->addDays(7);
                }
            }else if($request->avaliar_perfil) {
                $status_inicial =  Job::$status_array['emcandidatura'];
                //se data_limte nao for definida coloca a data atual mais 7 dias
                if($data_limite ==null) {
                    $data_limite = Carbon::now()->addDays(7);
                }
            }else{               
                $status_inicial = Job::$status_array['novo'];
            }

            $valor_delegado = null;
            if($valor){
                $valor_delegado = $valor - $valor * $request->get('taxa')/100;
            }

            $porc = $request->has('porcentagem_individual') 
                ? str_replace("%","", $request->get('porcentagem_individual')) 
                : null;
            
            //NÃESTA EM USO compara se usuario que esta publicando o job é um publicador (ou um coordenador de publicado) 
            if($this->user_current->name == 'publicador' || ($this->user_current->name == 'coordenador' && $publicador_id != null) ) {
                $job_publicador = true;
            }

            
            //Se é o usuario é coordenador e não foi selecionado um coordenador no cadastro do job, 
            //o usuário ativo (sendo coordenador) se torna o coordenador do job
            $coord = $request->get('coordenador_id') == -1 ? null : User::where('id', $request->get('coordenador_id'))->get()->first();
            if($coord ==null && !$this->user_ativo->can('define-coordenador-job') &&  $this->user_current->name == "coordenador" )
            {
                $coord = User::where('id',  $this->user_id)->get()->first();
            }

            //busca colaborador escolhido no cadastro
            $colab = $request->get('delegado_para')  == -1 ? null : User::where('id', $request->get('delegado_para'))->get()->first();


            //dd($request->get('job_delivery_value'));
            // ToDo: quando publicador puder selecionar um freela na criação do job, só mostrar freelancers que o limite de jobs não tenha sido alcançado - 18/08/2020
            $job = Job::create([
                'nome'                   => $nome_job,
                'tipojob_id'             => $request->get('tipojob_id'),
                'cliente_id'             => null,
                'delegado_para'          =>  $colab ? $colab->id : null,
                'coordenador_id'         => $coord ? $coord->id : null,
                'job_delivery_value'     => $request->get('job_delivery_value'),
                'deliveryformat_id'      => $request->get('deliveryformat_id'),
                'user_id'                => $this->user_id,
                'publicador_id'          => $publicador_id,
                'avaliador_id'           => null,
                'descricao'              => $request->get('descricao'),
                'data_prox_revisao'      => $request->get('data_prox_revisao'),
                'valor_job'              => $valor,
                'data_limite'            => $data_limite,
                'porcentagem_individual' => $porc,
                'campos_personalizados'  => $request->get('campos_personalizados'),
                'taxa'                   => $request->get('taxa'),
                'valor_delegado'         => $valor_delegado,
                // 'status'                 => $request->get('delegado_para') == -1 ? 0 : 1, #0=Movo, 1=Delegado
                'status'                 => $status, 
                'status_inicial'         => $status_inicial,
                'freela'                 => $request->get('freela'),
                'avulso'                 => 1
            ]);

            // dd($job);
            
            # Vincula as tasks
            // Inicia o contador na ordem 1
            $ordem = 1;
            foreach($request->get('tasks') as $tk) {
                $job->tasks()->attach($tk, ['ordem' => $ordem]);    
                $ordem++;
            }

            # Mantém referências antigas?
            if(!$request->has('alterar_ref') && $request->has('midias_ref')){

                $job->midias()->attach($request->get('midias_ref'));
            }
            // dd(array_key_exists('arquivos_ref', $request->allFiles()));

            # Thumb
            if($request->hasFile('thumb_ref') && $request->file('thumb_ref')->isValid()){
                # pega arquivo thumb
                $thumb = $request->file('thumb_ref');
                # prepara nome do job para pasta
                $nome_job = '_' . Controller::tirarAcentos( str_replace(' ', '_', $job->nome));
                # monta o caminho da pasta
                $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'jobs' . DIRECTORY_SEPARATOR . $job->id . $nome_job;
                # retirar acentos e espaços do nome do arquivo
                $nome = Controller::tirarAcentos( str_replace(' ', '_', $thumb->getClientOriginalName()) );
                # salva arquivo na pasta
                $upload = $thumb->storeAs($pasta_midias, $nome);
                # retira 'public/' do caminho do arquivo para salvar no banco de dados
                $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);
                // dd($upload);
                # se fez o upload atualiza o thumb
                if($upload){
                    $job->thumb = $pasta_midias . DIRECTORY_SEPARATOR .  $nome;
                    $job->save();
                }
            }
            # Novos arquivos de referência?
            if(!empty($request->allFiles()) && array_key_exists('arquivos_ref', $request->allFiles())){
                $arquivos_ref = $request->allFiles()['arquivos_ref'];
                $dados_img    = $request->get('novas_ref');
                $count        = 0;
                $nome_job     = '_' . Controller::tirarAcentos( str_replace(' ', '_', $request->get('nome')) );

                foreach ($arquivos_ref as $file){
                    # monta o caminho da pasta
                    $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'jobs' . DIRECTORY_SEPARATOR . $job->id . $nome_job . DIRECTORY_SEPARATOR . 'midias';
                    # retirar acentos e espaços do nome do arquivo
                    $nome = Controller::tirarAcentos( str_replace(' ', '_', $file->getClientOriginalName()) );
                    # salva arquivo na pasta
                    $upload = $file->storeAs($pasta_midias, $nome);
                    # retira 'public/' do caminho do arquivo para salvar no banco de dados
                    $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);

                    if($upload){
                        
                        # nome do tipo de arquivo
                        $nome_tipo_arquivo = TipoArquivo::where('id', $dados_img['tipo_id'][$count])->get()->first()->nome;

                        $arquivo = Midia::create([
                            'tipo_arquivo_id' => $dados_img['tipo_id'][$count],
                            'nome'            => $nome_tipo_arquivo,
                            'caminho'         => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                            'descricao'       => $nome_tipo_arquivo . ' do Job',
                            'nome_original'   => $nome,
                            'nome_arquivo'    => $nome
                        ]);
                        # vincula ao job a midia recém inserida
                        $job->midias()->attach($arquivo->id);

                    } else {
                        $request->session()->flash('message.level', 'erro');
                        $request->session()->flash('message.content', __('messages.Problema ao salvar arquivos de referência') . '.');
                        $request->session()->flash('message.erro', 'Falha ao salvar o arquivo ' . $nome . ' na pasta ' . $pasta_midias);
                    }
                    $count++;
                }
            }

            if($job->status != $job->getStatus('pagamentopendente')) {
                #notificação dos envolvidos - job publicador (avulso)
                $rota = route('jobs.show', encrypt($job->id));
                // $nome_obj   = $job->id;

                $param = array(
                    'cliente'       => null, 
                    'imagem'        => null, 
                    'job'           => $job, 
                    'task'          => null, 
                    'projeto'       => null, 
                    'tipo'          => null,
                    'destinatario'  => $colab, 
                    'rota'          => $rota,
                );

                // se existe colaborador e não for o mesmo do anterior, avisa ele

                // Mudar notificação para depois do do pagamento do job
                if($colab) {
                    # notificação ao novo colaborador 
                    $param['tipo'] = "job_colab_novo_vc";
                    $notificacao = new UserNotification($param);
                    $colab->notify(new AlertAction($notificacao));
                
                }
                if($coord) {
                    # notificação ao novo coordenador 
                    //Comentado dia 09-04-2020 para analise do cliente
                    // $param['tipo'] = "job_coord_novo_vc";
                    // $notificacao = new UserNotification($param);
                    // $coord->notify(new AlertAction($notificacao));
                }
            }
            //fim notificação
            DB::commit();

            if($job->status == $job->getStatus('pagamentopendente') || ($status == Job::$status_array['emproposta'] && \Auth::user()->hasPermissionTo('pos-pagamento')))
            {
                return redirect()->route('job.publicador.view.pagamento', encrypt($job->id));
            }

            # status de retorno
            $request->session()->flash('message.level',   'success');
            $request->session()->flash('message.content', '<a class="texto-branco" href="' .  route('jobs.show', encrypt($job->id)) . '">'  . __('messages.Job cadastrado com sucesso') . '!</a>');
            $request->session()->flash('message.erro', '');

            return redirect()->route('job.avulso.create');

        }catch (\Exception $exception){

            DB::rollBack();
            // dd($exception);

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('messages.O job não pôde ser cadastrado') . '.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    public function freelaPegaJob(Request $request){
        // dd($request);
        $validator = $this->validate($request, ['job_id' => 'required']);

        try{
            DB::beginTransaction();

           
            if(\Auth::user()->roles()->first()->name == "freelancer"){
                $pega_job    = true;
                $usuarioCon = \Auth::user();


                $user_config = $usuarioCon->configuracoes()->get();
                $qtde_jobs_freela = $usuarioCon->jobsOrigemNovoExecutandoTotal();
                $qtde_propostas_jobs_exec = $usuarioCon->jobsCandidaturaExecutandoTotal();
                $candidatura_status = 0;
                
               
                // muda status das candidaturas do freela para 4 se o limite de jobs candiddatura for alcançado
                // if($request->has('job_em_candidatura') && $request->get('job_em_candidatura')=='0') { 
                foreach ($user_config as $key => $value) {
                    if($value->chave=="qtde_jobs_andamento" && $value->valor <= $qtde_jobs_freela){
                        $pega_job = false;
                    }
                    if($value->chave=="qtde_jobs_candidaturas" && $value->valor <= $qtde_propostas_jobs_exec){
                        $candidatura_status = 4;
                    }
                }
                // }
                // echo $pega_job . ' - ' . $candidatura_status . ' - qtde_jobs_freela: ' . $qtde_jobs_freela. ' - qtde_propostas_jobs_exec: ' . $qtde_propostas_jobs_exec;
                //dd($request);

                $job = Job::where('id', $request->get('job_id'))->get()->first();
                
                // dd($job->status_inicial == (int) Job::$status_array['novo']);
                // echo $pega_job;
                // dd($job);
                if(!$pega_job && $job->status_inicial == intval(Job::$status_array['novo'])){
                    session()->flash('message.level', 'erro');
                    session()->flash('message.content', __('messages.Job não pode ser aceito') . '.');
                    session()->flash('message.erro', 'Limite de Jobs excedidos.');
                    return redirect()->back()->withInput();
                }
                
              
                $valor_proposta = $request->has('valor_proposta_job') && !empty($request->get('valor_proposta_job'))
                ? 
                str_replace(",",".", str_replace([".", "R$ "], "", $request->get('valor_proposta_job')))
                : null;

                // colocar proposta na lista para analise do publicador. 
                if($request->job_em_candidatura==1 || ($request->job_em_proposta==1 && $valor_proposta > 0)) {

                    $tipo = 0;
                    if($request->job_em_candidatura==1) {
                        $tipo = 1;
                    }

                    $job_candidatura = JobCandidatura::create([
                        'job_id'              => $request->get('job_id'),
                        'user_id'             => \Auth::id(),
                        'valor'               => $valor_proposta,
                        'tipo'                => $tipo,
                        'status'              => $candidatura_status
                        //'observacao'          => $request->get('observacao'),
                    ]);


                    $publicador = $job->user;

                    #notificação dos envolvidos - job avulso
                    $rota = route('jobs.show', encrypt($job->id));
                    $tipo = "job_candidatura_nova";
                    $param = array(
                        'cliente'       =>  null, 
                        'imagem'        =>  null, 
                        'job'           =>  $job, 
                        'task'          =>  null, 
                        'projeto'       =>  null, 
                        'tipo'          =>  $tipo,
                        'destinatario'  =>  null,
                        'rota'          =>  $rota,
                    );

                    // dd($publicador);

                    if($publicador && $publicador->id != \Auth::id()){
                        //Comentado dia 09-04-2020 para analise do cliente
                        $param['destinatario'] = $publicador;
                        $notificacao = new UserNotification($param);
                        $publicador->notify(new AlertAction($notificacao));
                    }

                    DB::commit();
                    # status de retorno
                    session()->flash('message.level', 'success');
                    session()->flash('message.content', __('messages.Proposta para o Job enviada com sucesso') . '!');
                    session()->flash('message.erro', '');
    
                    return redirect()->route('jobs.show', encrypt($job->id));

                }//job save - padrão pega direto. não é de candidatura nem de proposta
                else {
                   
                    $id_logado = \Auth::id();
                    $job->delegado_para = $id_logado; #5-concluído
                    $job->status = 2;
                    $job->data_inicio = Carbon::now();
                    $job->save();

                    $publicador = $job->user;
                    $coord = $request->get('coordenador_id') == -1 ? null : User::where('id', $request->get('coordenador_id'))->get()->first();

                    #notificação dos envolvidos - job avulso
                    $rota = route('jobs.show', encrypt($job->id));
                    $tipo = "job_delegado_novo";
                    $param = array(
                        'cliente'       =>  null, 
                        'imagem'        =>  null, 
                        'job'           => $job, 
                        'task'          => null, 
                        'projeto'       =>  null, 
                        'tipo'          => $tipo,
                        'destinatario'  => null,
                        'rota'          => $rota,
                    );

                    if($job->coordenador && $job->coordenador->id != \Auth::id()){
                        //Comentado dia 09-04-2020 para analise do cliente
                        $param['destinatario'] = $job->coordenador;
                        $notificacao = new UserNotification($param);
                        $job->coordenador->notify(new AlertAction($notificacao));
                    }
                    // dd($publicador);
                    if($publicador && $publicador->id != \Auth::id()){
                        //Comentado dia 09-04-2020 para analise do cliente
                        $param['destinatario'] = $publicador;
                        $notificacao = new UserNotification($param);
                        $publicador->notify(new AlertAction($notificacao));
                    }

                    DB::commit();
                    # status de retorno
                    session()->flash('message.level', 'success');
                    session()->flash('message.content', __('messages.Job aceito com sucesso') . '!');
                    session()->flash('message.erro', '');
    
                    return redirect()->route('jobs.show', encrypt($job->id));
                }
                
               
            }

        } catch (\Exception $exception){

            DB::rollBack();

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Job não pode ser aceito') . '.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    public function uploadAvaliacao(Request $request, $id) {
        // dd($request);
        
        $regras = [
            'imagem_avaliacao'   =>  'required'
            // 'nome_job'   =>  'required',
        ];


        try {
           
            $id = decrypt($id);
           
        //    dd($request);

           DB::beginTransaction();

           // Seta usuario logado como ativo
            $usuario_ativo  = \Auth::user();


           $job = Job::where('id', $id)->with(['coordenador', 'imagens', 'user'])->get()->first();

            if(!$usuario_ativo->can('job-files-sem-upload')) {
                # monta o caminho da pasta
                $caminho = 'imagens' . DIRECTORY_SEPARATOR . 'jobs' . DIRECTORY_SEPARATOR . $id . '_' . $job->nome . DIRECTORY_SEPARATOR . 'uploads';
                
                # Pega o arquivo que veio par upload
                $file = $request->file('imagem_avaliacao') ?? false;

                #faz upload do arquivo
                $upload = Controller::upload( $file, $caminho );
                # se fez o upload atualiza o thumb
                if($upload){
                            
                    $job_avaliacao = JobAvaliacao::create([
                        'job_id'    =>  $id,
                        'imagem'    =>  $upload
                    ]);
                    
                    // dd($job_avaliacao);

                } else {
                    $request->session()->flash('message.level', 'erro');
                    $request->session()->flash('message.content', __('messages.Arquivo da avaliação não encontrado') . '!');
                    return redirect()->back()->withInput();
                }
            }
            else {

                // se tem a permissão job-files-sem-upload vai enviar um link da rede
                if($request->get('imagem_avaliacao')) {

                    $upload = $request->get('imagem_avaliacao');
                    $job_avaliacao = JobAvaliacao::create([
                        'job_id'    =>  $id,
                        'imagem'    =>  $upload
                    ]);
                }
                else
                {
                    $request->session()->flash('message.level', 'erro');
                    $request->session()->flash('message.content', __('messages.Link precisa ser enviado') . '!');
                    return redirect()->back()->withInput();
                }
            }

            //codigo para notificação
            
            $rota = route('jobs.show', encrypt($job->id));
            $proj       = Projeto::where('id', $request->get('projeto_id'))->with(['coordenador'])->get()->first() ?? false;
            $coord_proj = $proj && $proj->coordenador ? $proj->coordenador : false;

            $delegado   = $job->delegado ?? null;
            $tipo = "job_avaliacao_upload_concluido";
            $param = array(
                'cliente'       => null, 
                'imagem'        => null, 
                'job'           => $job, 
                'task'          => null, 
                'projeto'       => $proj, 
                'tipo'          => $tipo,
                'destinatario'  => null, 
                'rota'          => $rota,
            ); 

            if($delegado && $job->freela ==1 ) {                 

                $user_job = $job->user;
                $param['destinatario'] = $user_job;
                $newUserNot = new UserNotification($param);
                //dd($newUserNot);
                $user_job->notify(new AlertAction($newUserNot));
                
            }

            if($job->coordenador && $job->coordenador->id != \Auth::id()){
                //Comentado dia 09-04-2020 para analise do cliente
                $param['destinatario'] = $job->coordenador;
                $notificacao = new UserNotification($param);
                $job->coordenador->notify(new AlertAction($notificacao));
            }

            // dd($request);
            DB::commit();


             # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', __('messages.Arquivo de avaliação do Job adicionado com sucesso') . '!');
            $request->session()->flash('message.erro', '');


        }catch(\Exception $exception) {
        
            DB::rollback();

            dd($exception);

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('messages.Arquivo da avaliação do Job não pode ser adiconada') . '!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->route('jobs.show', encrypt($id));
       
    }

    public function aguardandoPagamento()   {
        $this->user_current = \Auth::user()->roles()->first();
        $this->user_id = \Auth::user()->id;

        $this->user_publicador_id = \Auth::user()->publicador_id;

        if($this->user_current->name == "desenvolvedor" || $this->user_current->name == "admin") {
            $jobs = Job::with('coordenador')->with('delegado')->where('status', Job::$status_array['pagamentopendente'] )->get();
        
        }else if($this->user_current->name == "publicador") {
            $jobs = \Auth::user()->jobsPagamentosPendentes()
                        ->with(['delegado', 'coordenador'])
                        ->where('jobs.status', Job::$status_array['pagamentopendente'])
                        ->get();
        
        // }else if($this->user_current->name == "freelancer"){
            // $jobs = Job::where('status', Job::$status_array['pagamentopendente'])
            //             ->where('freela', 1)
            //             ->where('delegado_para', null)
            //             ->with(['user', 'coordenador'])
            //             ->get();
        
        }else if($this->user_current->name == "coordenador"){
            $jobs = Job::where('status', Job::$status_array['pagamentopendente'])
                        ->where('freela', 1)
                        ->where('user_id',  $this->user_publicador_id)
                        ->with(['user', 'coordenador'])
                        ->get();      
         }else {
            $jobs = \Auth::user()
                            ->jobs()
                            ->with(['coordenador', 'delegado'])
                            ->where('jobs.status', Job::$status_array['pagamentopendente'])
                            ->get();
        }

        foreach ($jobs as $value) {
            // $value->valor_desconto = floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
            $value->valor_desconto = $value->valorDoJob($this->user_current->name);
        }
                
        // $jobs = Job::with('coordenador')->whereIn('status', [0,1])->get();
        $statusarray = Job::$status_array;
        $titulo      = 'Aguardando Pagamentos';
        $role        = $this->user_current;

        $concluir_job = false;
        return view('job.lista', compact('titulo', 'jobs', 'statusarray', 'role','concluir_job'));
    }

    public function emCandidatura()   {
        $this->user_current = \Auth::user()->roles()->first();
        $this->user_id = \Auth::user()->id;

        $this->user_publicador_id = \Auth::user()->publicador_id;

        if($this->user_current->name == "desenvolvedor" || $this->user_current->name == "admin") {
            $jobs = Job::whereIn('status', [Job::$status_array['emcandidatura'], Job::$status_array['emproposta']])->get();
        
        }else if($this->user_current->name == "publicador") {
            $jobs = \Auth::user()
                        ->jobsPublicadosEmCandidatura()
                        ->get();
        
        }else if($this->user_current->name == "freelancer"){
            $jobs = \Auth::user()
                        ->candidaturaAbertas()
                        ->get()->pluck('job');
                        // dd($jobs);
        
        }else if($this->user_current->name == "coordenador"){
            $jobs = Job::whereIn('jobs.status', [Job::$status_array['emcandidatura'], Job::$status_array['emproposta']])
                        ->where('freela', 1)
                        ->where('publicador_id',  $this->user_publicador_id)
                        ->with(['user', 'coordenador'])
                        ->get();
                        
        //  }else {
        //     $jobs = \Auth::user()
        //                 ->jobs()
        //                 // ->with(['coordenador', 'delegado'])
        //                 ->whereIn('jobs.status', [Job::$status_array['emcandidatura'], Job::$status_array['emproposta']])
        //                 ->get();
        }

        foreach ($jobs as $value) {
            // $value->valor_desconto = floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
            $value->valor_desconto = $value->valorDoJob($this->user_current->name);
        }
                
        // $jobs = Job::with('coordenador')->whereIn('status', [0,1])->get();
        $statusarray = Job::$status_array;
        $titulo      = 'Em Propostas/Candidatura';
        $role        = $this->user_current;

        $concluir_job = false;
        return view('job.lista', compact('titulo', 'jobs', 'statusarray', 'role','concluir_job'));
        //return $jobs;
    }


    function formataNomeJob($publicador_id, $tipo_job, $qtd_jobs){
         //cria o nome do job no backend para evitar repetição
         $temp = "0000" . $publicador_id;
         $nome_job = substr($temp, -4);

         $temp = "0000" . $tipo_job;
         $nome_job .= substr($temp, -3);

         $temp = "0000" . $qtd_jobs;
         $nome_job .= substr($temp, -4);

         return $nome_job;
    } 

    // metodo que marca o hr_solicitado com 1
    function solicitarHR($id, Request $request) {
        $job_id = decrypt($id);
        try{    

            DB::beginTransaction();

            $job = Job::where('id', $job_id)->get()->first();
            //marcar hr_solicitado como 1
            $job->hr_solicitado = 1; 
            $job->save();

            #enviar notificação para o freelancer subir o HR
            $rota = route('jobs.show', $id);

            $tipo = 'job_hr_solicitado';

            $param = array(
                'cliente'       => null, 
                'imagem'        => null, 
                'job'           => $job, 
                'task'          => null, 
                'projeto'       => null, 
                'tipo'          => $tipo,
                'destinatario'  => $job->delegado, 
                'rota'          => $rota,
            );

            $notificacao = new UserNotification($param);
            //notificação para o freela do job                
            if($job->delegado){
                $job->delegado->notify(new AlertAction($notificacao));
            }

            DB::commit();

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('messages.Solicitação de HR enviada') . '!');
            session()->flash('message.erro', '');

            

            return redirect()->route('jobs.show', $id);

        }catch (\Exception $exception){

            DB::rollBack();
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.A Solicitação do HR não foi enviada') . '.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    // Funçao de upload do arquivo HR enviado pelo freelancer
    function uploadHR(Request $request, $id)
    {

        $job_id = decrypt($id);
        try{    

            DB::beginTransaction();

            $job = Job::where('id', $job_id)->where('hr_solicitado', 1)->get()->first();

            // Seta usuario logado como ativo
            $usuario_ativo  = \Auth::user();

            // Senao tem a permissao job-files-sem-upload deve fazer upload de um arquivo HR.
            if(!$usuario_ativo->can('job-files-sem-upload'))
            {
                # monta o caminho da pasta
                $caminho = 'imagens' . DIRECTORY_SEPARATOR . 'jobs' . DIRECTORY_SEPARATOR . $job_id . '_' . $job->nome . DIRECTORY_SEPARATOR . 'uploads'. DIRECTORY_SEPARATOR . 'hr';
                
                # Pega o arquivo que veio par upload
                $file = $request->file('imagem_hr') ?? false;

                #faz upload do arquivo
                $upload = Controller::upload( $file, $caminho );

                # se fez o upload atualiza o thumb
                if($upload){
                    $job->hr_url = $upload;

                } else {
                    $request->session()->flash('message.level', 'erro');
                    $request->session()->flash('message.content', __('messages.Arquivo HR não encontrado') . '!');
                    return redirect()->back()->withInput();
                }
            }
            else
            {
                // se tem a permissão job-files-sem-upload vai enviar um link da rede
                if($request->get('hr_link')) {
                    $upload = $request->get('hr_link');
                    $job->hr_url = $upload;
                }
                else
                {
                    $request->session()->flash('message.level', 'erro');
                    $request->session()->flash('message.content', __('messages.Link precisa ser enviado') . '!');
                    return redirect()->back()->withInput();
                }
            }

            //dd($request);


            $job->save();

            # envia notificação para o publicador
            $rota = route('jobs.show', $id);

            $tipo = 'job_hr_enviado';

            $param = array(
                'cliente'       => null, 
                'imagem'        => null, 
                'job'           => $job, 
                'task'          => null, 
                'projeto'       => null, 
                'tipo'          => $tipo,
                'destinatario'  => $job->publicador, 
                'rota'          => $rota,
            );

            $notificacao = new UserNotification($param);

            if($job->publicador){
                $job->publicador->notify(new AlertAction($notificacao));
            }

            DB::commit();

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('messages.HR do Job foi enviado') . '!');
            session()->flash('message.erro', '');

            

            return redirect()->route('jobs.show', $id);

        }catch (\Exception $exception){

            DB::rollBack();
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.O HR não foi enviado') . '.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }
}
