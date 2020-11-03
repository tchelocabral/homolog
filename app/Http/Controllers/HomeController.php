<?php

namespace App\Http\Controllers;

use App\Models\Imagem;
use App\Models\Job;
use App\Models\Projeto;
use App\Models\Cliente;
use App\Models\TipoJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller {

    protected $lista_geral_jobs     = [];
    protected $lista_geral_projetos = [];
    protected $user_role            = '';
    protected $filtro_tipo_job      = false;
    

    public function __construct(){
        // parent::isNovaSenha();
        $this->middleware('auth');
        // $this->user_role = \Auth::user()->roles()->first()->name;
    }


    // Mostra os jobs de acordo com o usuário logado    
    public function index(Request $request) {

        # valida se é para alterar a senha
        $this->filtro_tipo_job = $request->tipojob_id ? (in_array('todos', $request->tipojob_id) ? 'todos' : $request->tipojob_id) : false;

        //TODO: arrumar selects 
        # Se não for freela ou dev

        //dados do usuario autenticado
        $usuarioCon = \Auth::user();
        //pega as permissoes do usuário autenticado
        $usuarioPermissions = $usuarioCon ->permissions()->pluck('name')->toArray();
        //pega a role (politica) do usuário autenticado
        $roleUsuarioCon = $usuarioCon->roles()->first()->name;
        //pega o id do publicador do usuário, caso tenha
        $publicador_id = $usuarioCon->publicador_id;

        //variaveis que recebe os retotnos de cada função para apresentar na view
        $imgs_andamento      = [];
        $cliente             = [];
        $projetos            = [];
        $projetos_andamento  = [];
        $projetos_concluidos = [];
        $avaliando           = [];
        $executando          = [];
        $tasks_andamento     = [];
        $jobs_freelas        = [];
        $jobs_andamento      = [];
        $jobsTotal           = [];
        $job                 = [];
        $jobs_concluidos     = [];
        $jobs_abertos        = [];
        $jobs_parados        = [];
        $coordenando         = [];
        $jobs_recusados      = [];
        $projetosCoordenando = [];
        $imagens_coordenando = [];

        // Freela atingiu limite de jobs ?
        $limite_job   = true;
        // Freela pode pegar job?
        $pega_job     = false;
        // freela pode mandar propostas
        $manda_proposta = false;

        // Flag: vê se o freela ja tem conta do paypal
        $conta_paypal = \Auth::user()->conta_paypal ?? false;
        // Dados de configuração de usuario
        $user_config  = \Auth::user()->configuracoes()->get();

        //variaver para passar para a view o metodo para o status do job
        $status_array = Job::$status_array;
        

        if($roleUsuarioCon  ==  "cliente") {
            //vê os projetos do clientes
            $projetos           =   $this->projetosCliente();
            
        }elseif($roleUsuarioCon  ==  "freelancer") {
            //vê jobs que o usuário - freelancer - esta executando
            $executando         =   $this->jobsExecutando($roleUsuarioCon);
            // vê os jobs disponiveis para serem pegos pelo freelancer
            $jobs_freelas       =   $this->jobsAbertos(1, $roleUsuarioCon);
            //dd($jobs_freelas);
            //dd($jobs_freelas[5]->pagamento);
            //jobs() comentando pois nao é usado - 01/09/2020
            //$job                =   $this->jobs();
 
            # flag para indicar se freela pode pegar job
            $pega_job = true;
            # flag se atingiu o limite de jobs 
            $limite_job = true;
            # quantidade de jobs em execucao pelo freela
            $qtde_jobs_freela = $usuarioCon->jobsOrigemNovoExecutandoTotal();

            # quantidade de propostas enviadas para os jobs
            // $qtde_propostas_jobs = $usuarioCon->propostas()->get()->count();

            # Busca as configurações do usuario
            foreach ($user_config as $value) {
                # Se a chave for qtde_job_andamento e o valor for menor 
                if($value->chave=="qtde_jobs_andamento" && $qtde_jobs_freela < $value->valor ){
                    $limite_job = false;
                }
                // if($value->chave=="qtde_jobs_propostas" && $qtde_propostas_jobs < $value->valor){
                //     $manda_proposta = true;
                // }
            }
            #pega job se tiver a conta paypal e não atingiu o limite de jobs
            $pega_job = !$limite_job;
            
           
            #lista de jobs que o usuário fez candidatura/proposta
            //dd($usuarioCon->candidaturaAbertas()->get());

            $ids_candidaturas = $usuarioCon->candidaturaAbertas()->get()->pluck('id')->toArray();
            //dd($ids_candidaturas);
            foreach ($jobs_freelas as $value) {
                // seta o valor a ser mostrado
                $value->valor_desconto = $value->valorDoJob($roleUsuarioCon);
                //criar money para facilitar no layout
                $value->money = $value->valor_desconto;
                
                // transforma o status para nome
                $value->status_nome = $status_array[$value->status];
                
                // flag para se a proposta jpa foi enviada
                $value->proposta_enviada = true;
                if(in_array($value->id, $ids_candidaturas)) {     
                   //se possui candidatura desse job passo valor da candidatura. 
                   //Senao tem, coloca prposta_enviada como false
                    if($value->candidaturaFreela) {
                        $value->money = $value->candidaturaFreela->valor;
                    }
                    else{
                        $value->proposta_enviada = false;
                    }

                }else{
                    $value->proposta_enviada = false;
                } 

                //echo ($value->nome . ' - ' . $value->proposta_enviada . ' - '. $value->data_limite . '<br>');
              
                // dd($value);
                
                // se status inicial for novo e tem limite para pegar job
                    // elseif($value->status_inicial == Job::$status_array['novo'] && $limite_job==false) {
                        // $value->proposta_enviada = false;                
                    // } 
                    // se job em proposta
                    // elseif($value->status_inicial == Job::$status_array['emproposta']) {
                        // $value->proposta_enviada = false;

                    // } 
                    // se job em proposta
                    // elseif($value->status_inicial == Job::$status_array['emcandidatura']) {
                        // $value->proposta_enviada = false;
                    // }
                                    
                    //flag para jobs em proposta/candidatura receberem proposta
                    // if($value->status_inicial == Job::$status_array['emproposta'] || $value->status_inicial == Job::$status_array['emcandidatura'])
                    // {
                        // $value->manda_proposta = true;
                    // }
               // echo $value->status_inicial .'<br>'; 
            }
            

        }elseif($roleUsuarioCon  ==  "equipe") {

            //pega jobs que o usuário foi delegado e já está executando
            // $executando         =   $this->jobsExecutando($roleUsuarioCon);
            //pega jobs que o usuário foi delegado e estão em andamento
            $jobs_andamento     =   $this->jobEmAndamento($roleUsuarioCon);
            //pega jobs que o usuário foi delegado e estão em aberto
            // $jobs_abertos       =   $this->jobsAbertos(false, $roleUsuarioCon);
            
            //comentado pois nao é utilizado - 01/09/220
            //$job                =   $this->jobs();
            
            //codigo comentado antes de 01/09/2020
            //$jobs_concluidos    =   $this->jobsConcluidos()->where('delegado_para',\Auth::id())->get();

            //jobs que foram recusados que o usuario foi delegado
            $jobs_recusados     =   $this->jobRecusado()->where('delegado_para',\Auth::id())->get();

        }elseif($roleUsuarioCon  ==  "admin"   ||  $roleUsuarioCon   ==    "desenvolvedor"){
            
            //trás todos projetos em andamento
            $projetos_andamento  = $this->projetosAndamento($roleUsuarioCon);
            //trás todos projetos concluidos
            $projetos_concluidos = $this->projetosConcluidos($roleUsuarioCon);
            //trás todos projetos que o usuário esta coordenando
            $projetosCoordenando = $this->projetosCoordenando();
            
            //comentado pois nao é utilizado - 01/09/220 
            // $job                 = $this->jobs();

            // Marketplace
            //tras todos os jobs que estão abertos para os freelas
            $jobs_abertos        = $this->jobAbertosAvulso();

            //tras todos os jobs que o usuarios esta avaliando
            $avaliando           = $this->avaliando();
                        
            //tras todas tasks em andamento - Status 0,1,5,6 e 8
            $tasks_andamento     = $this->tasksAndamento();
            //tras os jobs que esta coordenando
            $coordenando         = $this->coordenando();
            //tras as imagens em status 0 e 1
            $imgs_andamento      = $this->imgsAndamento();
            //função vê se o usuário ativo é um cliente
            $cliente             = $this->isCliente();
            //funcão trás os  jobs em execução delegados ao usuário ativo, ou, os jobs em execução do publicador
            $executando          = $this->jobsExecutando($roleUsuarioCon);
            //quantidade total de jobs
            $jobsTotal           = $this->jobsTotal();
            //comentado antes de 01/09/2020
            //$jobs_concluidos     = $this->jobsConcluidos()->get();
            //quantidade de jobs recusados
            $jobs_recusados      = $this->jobRecusado()->get();
            //quantidade de jobs parados           
            $jobs_parados        = $this->jobParado($roleUsuarioCon);

         //   $imagens_coordenando=   $this->imagensCoordenando();
        }elseif($roleUsuarioCon  ==  "coordenador" ) {

            //mudanças nas consultas considerando a role - 31082020
            //buscar jobs em aberto status 0,9 e 11 - considerando a role
            $jobs_abertos        = $this->jobsAbertos(1, $roleUsuarioCon);
            //buscar jobs sendo executados do do publicador do coordenandor
            $executando          = $this->jobsExecutando($roleUsuarioCon);
            //buscar jobs recusados do publicador do coordenandor
            $jobs_recusados      =  $this->jobRecusado()->where('publicador_id',$publicador_id)->get();
            //buscar jobs parados do publicador do coordenandor
            $jobs_parados        =   $this->jobParado($roleUsuarioCon);
            //busca jobs que esta coordenando
            $coordenando         =   $this->coordenando();

            //Se tem a permissão avalia-job, vê os jobs que esta avaliando
            if(in_array('avalia-job', $usuarioPermissions)) {
                $avaliando           =   $this->avaliando();
            }
                    
            //Se tem a permissão cria-projeto, vê os projetos
            if(in_array('cria-projetos', $usuarioPermissions)) {
                $projetos_andamento  =   $this->projetosAndamento($roleUsuarioCon);
                $projetos_concluidos =   $this->projetosConcluidos($roleUsuarioCon);
                $projetosCoordenando =   $this->projetosCoordenando();
            }
                
        }elseif($roleUsuarioCon  ==  "publicador") {
            //buscar jobs em aberto status 0,9 e 11 - considerando a role
            $jobs_abertos        = $this->jobsAbertos(1, $roleUsuarioCon);
            //buscar jobs sendo executados do publicador 
            $executando          = $this->jobsExecutando($roleUsuarioCon);
            //buscar jobs recusados do publicador 
            $jobs_recusados      =  $this->jobRecusado()->where('user_id',\Auth::id())->get();

            
        }elseif($roleUsuarioCon  ==   "avaliador") {

            //função trás os jobs que o usuário ativo coordena que não estão concluidos
            $coordenando         =   $this->coordenando();
            //função trás os jobs que estão no status avaliando
            $avaliando           =   $this->avaliando();
            //trás os projetos em andamento que o usuário está coordenando
            $projetos_andamento  =   $this->projetosAndamento($roleUsuarioCon);
            //trás os projetos concluidos que o usuário está coordenando
            $projetos_concluidos =   $this->projetosConcluidos($roleUsuarioCon);
            //trás jobs que o usuário foi delegado
            $executando          =   $this->jobsExecutando($roleUsuarioCon);
        }

        //trás os tipos de jobs
        $tipos_jobs  = TipoJob::whereHas('jobs')->get();

        $concluir_job = false;

        if($executando) {
            foreach ($executando as $value) {

                $value->pode_concluir = false;

                //Se o jog não é recusado, parado ou concluido e 
                //seu progresso é igual ou maior que 100% 
                //ele pode ser concluido pela lista
                if(!$value->verificaStatus('recusado') && !$value->verificaStatus('concluido') && !$value->verificaStatus('parado') && $value->concluido()>=100) {
                    $value->pode_concluir = true;

                    //marcado como falso para subir em produção - 14/10/2020
                    $concluir_job = true;
                }

            }
        }

        return view('home', compact(
            'tipos_jobs',
            'pega_job',
            // 'manda_proposta', 
            'conta_paypal', 
            'limite_job', 
            // 'qtde_jobs_freela', 
            'executando', 
            'avaliando', 
            'coordenando', 
            'cliente', 
            'projetos',
            'job',
            'jobsTotal',
            'projetos_andamento',
            'projetos_concluidos', 
            'jobs_andamento', 
            'imgs_andamento', 
            'tasks_andamento', 
            'jobs_freelas', 
            'jobs_concluidos', 
            'jobs_recusados', 
            'projetosCoordenando',
            'imagens_coordenando', 
            'jobs_abertos', 
            'jobs_parados',
            'roleUsuarioCon','status_array','concluir_job')
        );
    }

    //Funcões das ações e status para cada perfil de usuário

    //função de job concluido do usuário ativo
    public function jobsConcluidos() {
        return  Job::where('jobs.status', 5)
                ->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias']);
  }

    //função trás os  jobs em aberto para serem pegos pelos freelas
    public function jobsAbertos($isFreela, $roleUsuarioCon) {
        $j = '';

        if($roleUsuarioCon == 'publicador' || ($roleUsuarioCon == 'coordenador')){

            $j = \Auth::user()->jobsPublicadosAbertos($roleUsuarioCon);
            
            // Filtro Tipo de Job
            if($this->filtro_tipo_job && $this->filtro_tipo_job != 'todos'){ $j->whereIn('tipojob_id', $this->filtro_tipo_job); }
            
            $j->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias','pagamento']);

        }else{
            $j = Job::whereIn('status', [Job::$NOVO, Job::$EMCANDIDATURA, Job::$EMPROPOSTA])
                    ->where('freela', $isFreela)
                    ->where('delegado_para', null)
                    ->where(function($query) {
                        $query->where('data_limite','>=', Carbon::now())->orWhere('data_limite', null);
                    });
            
            // Filtro Tipo de Job
            if($this->filtro_tipo_job && $this->filtro_tipo_job != 'todos'){ $j->whereIn('tipojob_id', $this->filtro_tipo_job); }

            $j->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias','candidaturas','candidaturaFreela','pagamento']);
        }

        $j->orderBy('created_at', 'desc');

        // dd($j->get());
        return $j->get();
    }

    //função trás os  jobs em aberto de freela e não freela
    public function jobAbertosAvulso() {
        $j = Job::where('avulso', 1)
                ->whereIn('status', [Job::$NOVO, Job::$EMCANDIDATURA, Job::$EMPROPOSTA])
                ->where('delegado_para', null)
                ->where(function($query) {
                    $query->where('data_limite','>=', Carbon::now())->orWhere('data_limite', null);
                });;
        
        if($this->filtro_tipo_job && $this->filtro_tipo_job != 'todos'){ $j->whereIn('tipojob_id', $this->filtro_tipo_job); }
                
        $j->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias']);

        return $j->get();
    }

    //função trás os  jobs em andamento delegados ao usuário ativo 
    public function jobEmAndamento($roleUsuarioCon) {

        if($roleUsuarioCon == "admin"||$roleUsuarioCon == "desenvolvedor"){
            // alterado 28/09/2020 return Job::whereNotIn('status', [0,1,5,6,8] )
            return Job::whereNotIn('status', [0,5,6,8,9,10,11,12] )
                ->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
                ->get();
                //->filter(function($item){ return $item->concluido() < 100; });
        }
        else if($roleUsuarioCon == "publicador"){
            //alterado 28/09/2020 return Job::whereNotIn('status', [0,1,5,6,8] )
            return Job::whereNotIn('status', [0,5,6,8,9,10,11,12] )
                ->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
                ->where('user_id',\Auth::id())
                ->get();
        }
        else
        {
            return \Auth::user()
                ->jobs()
                ->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
                //alterado 28/09/2020  ->whereNotIn('status', [5,6,8] )  
                ->whereNotIn('status', [0,5,6,8,9,10,11,12] )
                ->get();
                //->filter(function($item){ return $item->concluido() < 100; });
        }
    }
        
    //função trás os  jobs em execução delegados ao usuário ativo, ou, os jobs em execução do publicador
    public function jobsExecutando($roleUsuarioCon) {
        if($roleUsuarioCon == 'publicador' || $roleUsuarioCon == 'coordenador'){
            return \Auth::user()
                ->jobsPublicadosExecutando($roleUsuarioCon)
                ->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
                ->orderBy('created_at', 'desc')
                ->get();

        }elseif($roleUsuarioCon == 'admin' || $roleUsuarioCon == 'desenvolvedor'){
            return Job::whereIn('status', [2,7])
                        ->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        }

        return \Auth::user()
                ->jobs()
                ->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
                ->whereIn('status', [2,7])
                ->orderBy('created_at', 'desc')
                ->get();
    }
            
    //função trás os  jobs em parados  
    public function jobParado($roleUsuarioCon) {

        if($roleUsuarioCon == "admin"||$roleUsuarioCon == "desenvolvedor"){
            return Job::where('status', 8 )
                ->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
                ->get();
                //->filter(function($item){ return $item->concluido() < 100; });
        }
        else if($roleUsuarioCon == "publicador"){
            return Job::with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
                ->where('user_id',\Auth::id())
                ->where('status',8)
                ->get();
        }
        else
        {
            return \Auth::user()
                ->jobs()
                ->with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
                ->where('status', 8)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    //função trás os  jobs em execução delegados ao usuário ativo 
    public function jobRecusado() {
        return Job::where('status', 6)
                ->orderBy('created_at', 'desc');
    }

    //Função trás os jobs
    public function jobs() {
        return Job::with(['tipo','user', 'coordenador', 'delegado', 'tasks', 'imagens', 'midias'])
            ->get()->first();
    }

    public function jobsTotal() {
        return Job::get()->count();
    }

    //função trás as imagens em andamento
    public function imgsAndamento()  {
        return Imagem::whereBetween('status', [0,1])
        ->get();
    }

    //função trás os jobs que o usuário ativo coordena que não estão concluidos
    public function coordenando() {
        return \Auth::user()->coordenando()->whereNotIn('status', [5,6])->orderBy('created_at', 'desc')
            ->get();
    }

    //função trás os jobs que estão no status avaliando
    public function avaliando() {
        return \Auth::user()->avaliando()->where('status', '4')->orderBy('created_at', 'desc')
            ->get()->filter(function($item){ return $item->concluido() < 100; });
    }

    //função trás as task em andamento
    public function tasksAndamento() {
        return DB::table('jobs_tasks')
                ->join('jobs', 'jobs_tasks.job_id', '=', 'jobs.id')
                ->whereNotIn('jobs.status', [0,1,5,6,8] )
                ->where('jobs_tasks.status', '0')
                ->select('jobs_tasks.*')
                ->get();
    }

    //função vê se o usuário ativo é um cliente
    public function isCliente() {
        return  Cliente::where('user_id', \Auth::user()->id)->get()->first();
    }

    //função trás os projetos do cliente
    public function projetosCliente() {
        return  Projeto::where('cliente_id', \Auth::user()->id)
            ->get();
    }

    //função trás os  jobs em execução delegados ao usuário ativo 
    public function projetosCoordenando() {
        return Projeto::where('coordenador_id', \Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->get();
    }

    public function projetosAndamento($roleUsuarioCon) {
        if($roleUsuarioCon == "admin"||$roleUsuarioCon == "desenvolvedor"){

            return Projeto::whereIn('status', [0, 1])->with('coordenador')->with('cliente')
                    ->orderBy('created_at', 'desc')->get()->
                    filter(function($item){ return $item->concluido() < 100; });
        }else{
            return Projeto::where('coordenador_id', \Auth::user()->id)
                    ->whereIn('status', [0, 1])->with('coordenador')->with('cliente')
                    ->orderBy('created_at', 'desc')->get()
                    ->filter(function($item){ return $item->concluido() < 100; });
        }
    }

    public function projetosConcluidos($roleUsuarioCon) {
        if($roleUsuarioCon == "admin"|| $roleUsuarioCon == "desenvolvedor"){
            return Projeto::where('status', 2)->with('coordenador')->with('cliente')
                    ->orderBy('created_at', 'desc')->get();
        }else{
            return Projeto::where('coordenador_id', \Auth::user()->id)
                    ->where('status', 2)->with('coordenador')->with('cliente')
                    ->orderBy('created_at', 'desc')->get();
        }
    }

    protected function listaDeJobs(){
        $this->lista_geral_jobs =  '';

    }

    protected function listaDeProjetos(){
        switch($this->user_role){
            case "admin":
                $this->lista_geral_jobs = 
                    DB::table('projetos')
                        ->join('users', '') ;
                break;
                    #chunk()
            default:
                $this->lista_geral_jobs =  [];
                break;

                
        }
        // $this->lista_geral_jobs =  '';
    }

}
