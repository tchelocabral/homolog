<?php

namespace App\Http\Controllers;

use App\Models\Imagem;
use App\Models\Task;
use App\User;
use App\Models\Job;
use App\Models\Projeto;
use App\Models\Cliente;
use App\Models\Segmento;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\TipoJob;
use DB;

class HomeTestController extends Controller {

    protected $user_role            = '';
    protected $auth_id              = '';

    protected $lista_geral_jobs;
    protected $lista_geral_projetos;
    protected $lista_geral_tasks;
    
    protected $projetos      = [];
    protected $projetos_andamento   = [];
    protected $projetos_concluidos  = [];
    protected $projetos_coordenando = [];
    
    protected $jobs_freelas         = [];
    protected $jobs_andamento       = [];
    protected $jobs_concluidos      = [];
    protected $jobs_abertos         = [];
    protected $jobs_parados         = [];
    protected $jobs_recusados       = [];
    protected $coordenando          = [];
    protected $avaliando            = [];
    protected $executando           = [];
    protected $jobsTotal            = [];
    protected $job                  = [];
   

    protected $imgs_andamento       = [];
    protected $imagens_coordenando  = [];

    
    protected $tasks_andamento      = [];
    
    
    
    
    public function __construct(){
        // Controller::isNovaSenha();
        $this->middleware('auth');
        // $this->user_role = \Auth::user()->roles()->first()->name;
    }

    public function index() {

        $usuarioCon = auth()->user();
        $this->auth_id   = $usuarioCon->id;
        $this->user_role = $usuarioCon->roles()->first()->name;
        
        $this->publicador_id = $usuarioCon->publicador_id;


        $status_array = Job::$status_array;

        // Freela atingiu limite de jobs ?
        $limite_job   = true;
        // Freela pode pegar job?
        $pega_job     = false;

        $manda_proposta = false;
        // $qtde_jobs_freela = 0;
        $conta_paypal = $usuarioCon->conta_paypal ?? false;
        $user_config  = $usuarioCon->configuracoes()->get();


        if($this->user_role  ==  "freelancer") {
            # flag para indicar se freela pode pegar job
            $pega_job = true;
            # flag se atingiu o limite de jobs 
            $limite_job = true;
            # quantidade de jobs em execucao pelo freela
            $qtde_jobs_freela = $usuarioCon->jobsOrigemNovoExecutandoTotal();
            # quantidade de propostas enviadas para os jobs
            // $qtde_propostas_jobs = $usuarioCon->propostas()->get()->count();
            // dd($qtde_jobs_freela);

            # Busca as configurações do usuario
            foreach ($user_config as $value) {
                # Se a chave for qtde_job_andamento e o valor for menor 
                if($value->chave=="qtde_jobs_andamento" && $qtde_jobs_freela < $value->valor ){
                    $limite_job = false;
                }
            }
            #pega job se tiver a conta paypal e não atingiu o limite de jobs
            $pega_job = !$limite_job;
            
           
            #lista de jobs que o usuário fez candidatura/proposta
            $ids_candidaturas = $usuarioCon->candidaturaAbertas()->get()->pluck('id')->toArray();
            //dd($ids_candidaturas);
            // $qtde_jobs_cand = $candidaturas->count();
            
            //dd($jobs_freelas);
            foreach ($jobs_freelas as $value) {

                // seta o valor a ser mostrado
                $value->valor_desconto = $value->valorDoJob($roleUsuarioCon);
                //criar money para facilitar no layout
                $value->money = $value->valor_desconto;
                
                // transforma o status para nome
                $value->status_nome = $status_array[$value->status];
                
                // flag para se a proposta foi enviada
                $value->proposta_enviada = true;
                if(in_array($value->id, $ids_candidaturas)) {     
                    if($value->candidaturaFreela) {
                        $value->money = $value->candidaturaFreela->valor;
                    }

                }else{
                    $value->proposta_enviada = false;
                } 
            }
        }



        
        $this->processaListaDeProjetos();
        $this->processaListaDeJobs();

        $this->listaDeTasksEmAndamento();
        $this->listaDeImgsEmAndamento();


        return view('home-test')->with([
            'projetos' => $this->projetos,
            'projetos_andamento' => $this->projetos_andamento,
            'projetos_concluidos' => $this->projetos_concluidos, 
            'projetosCoordenando' => $this->projetos_coordenando,

            'avaliando' => $this->avaliando, 
            'jobs_abertos' => $this->jobs_abertos, 
            'jobs_freelas' => $this->jobs_freelas, 


            'executando' => $this->executando, 
            'coordenando' => $this->coordenando, 
            'cliente' => '', 
            'job' => $this->job,
            'jobsTotal' => $this->jobsTotal,
            
            'jobs_andamento' => $this->jobs_andamento, 
            'imgs_andamento' => $this->imgs_andamento, 
            'tasks_andamento' => $this->tasks_andamento, 
            'jobs_concluidos' => $this->jobs_concluidos, 
            'jobs_recusados' => $this->jobs_recusados, 
            'imagens_coordenando' => $this->imagens_coordenando, 
            'jobs_parados' => $this->jobs_parados,
            'tipos_jobs' => TipoJob::whereHas('jobs')->get(),
            'status_array' => $status_array,
            'limite_job' => $limite_job,
            'pega_job' => $pega_job,
            'conta_paypal' => $conta_paypal, 
            ]
        );
        
    }

    public function itemHome($item)
    {
        $this->auth_id   = auth()->user()->id;
        $this->user_role = auth()->user()->roles()->first()->name;
        
        $this->processaListaDeProjetos();
        $this->processaListaDeJobs();

        $this->listaDeTasksEmAndamento();
        $this->listaDeImgsEmAndamento();
        
        switch ($item) {
            case 'projetos_andamento':
                $this->projetosEmAndamento();
                $homeDados = $this->projetos_andamento;
                break;
            case 'projetos_coordenando':
                $this->projetosCoordenando();
                $homeDados = $this->projetos_coordenando;
                break; 
            case 'jobs_freelas':
                $this->jobsAbertosFreelas();
                $homeDados = $this->jobs_freelas;
                break;                    
                    
            default:
                # code...
                break;
        }
       

        if($homeDados) {
            if(request()->wantsJson()) {
                $viewHTML = view($item, compact('homeDados'))->render();
                return \Response::json(array('success' => true, 'view' => $viewHTML));
            }else{
                return view('dashboard.'.$item, compact('homeDados'));
            }
        }
    }

    

    /////// PROJETOS //////////
    protected function listaDeProjetos(){
        #chunk()
        # verificar permissões
        switch($this->user_role){
            case "admin":
                $this->lista_geral_projetos = 
                    Projeto::with('coordenador')
                        ->with('cliente')
                        ->orderBy('updated_at', 'desc')
                        ->get();
                
                break;
            case "desenvolvedor":
                $this->lista_geral_projetos = 
                    Projeto::with('coordenador')
                        ->with('cliente')
                        ->orderBy('updated_at', 'desc')
                        ->get();
                break;
            case "cliente":
                $this->lista_geral_projetos = 
                    Projeto::with('coordenador')
                        ->with('cliente')
                        ->where('cliente_id', $this->auth_id)
                        ->orderBy('updated_at', 'desc')
                        ->get();
                break;
            case "coordenador":
                $this->lista_geral_projetos = 
                    Projeto::with('cliente')
                        ->where('coordenador_id', $this->auth_id)
                        ->orderBy('updated_at', 'desc')
                        ->get();
                // dd( $this->lista_geral_projetos);
                break;
            case "publicador":
                // DEVE CRIAR A COLUNA USER_ID NA TABELA PROJETOS
            break;
            default:
                $this->lista_geral_projetos;
                break;
        }
        // $this->lista_geral_jobs =  '';
    }

    protected function processaListaDeProjetos(){

        if(!isset($this->lista_geral_projetos) || empty($this->lista_geral_projetos)){
            $this->listaDeProjetos();
        }

        switch($this->user_role){
            case "admin":
                $this->projetosEmAndamento();
                $this->projetosConcluidos();
                $this->projetosCoordenando();
                break;
            case "desenvolvedor":
                $this->projetosEmAndamento();
                $this->projetosConcluidos();
                $this->projetosCoordenando();
                break;
            case "cliente":
                $this->projetos = $this->lista_geral_projetos;
                break;
            case "coordenador":
                $this->projetosEmAndamento();
                $this->projetosConcluidos();
                $this->projetosCoordenando();
                break;
            case "publicador":
                $this->projetos = [];
                $this->projetos_coordenando = [];
                $this->projetos_andamento = [];
                $this->projetos_concluidos = [];
                break;
            default:
                $this->projetos = [];
                $this->projetos_coordenando = [];
                $this->projetos_andamento = [];
                $this->projetos_concluidos = [];
                break;
        }

    }

    protected function projetosEmAndamento(){
        if(!isset($this->lista_geral_projetos) || empty($this->lista_geral_projetos)){
            $this->listaDeProjetos();
        }

        $this->projetos_andamento = 
            $this->lista_geral_projetos
                ->filter(
                    function($item){
                        if(in_array($item->status, [0,1])){
                            return $item->concluido() < 100;
                        }   
                        // return false;
                    }
                );
        // dd($this->projetos_andamento);
    }

    protected function projetosCoordenando(){
        if(!isset($this->lista_geral_projetos) || empty($this->lista_geral_projetos)){
            $this->listaDeProjetos();
        }

        $this->projetos_coordenando = 
            $this->lista_geral_projetos
                ->filter(
                    function($item){
                        return $item->coordenador_id == auth()->user()->id;
                    }
                );
    }

    protected function projetosConcluidos(){
        if(!isset($this->lista_geral_projetos) || empty($this->lista_geral_projetos)){
            $this->listaDeProjetos();
        }

        $this->projetos_concluidos = 
            $this->lista_geral_projetos
                ->filter(
                    function($item){
                        if($item->status == 2){
                            return $item->concluido() >= 100;
                        }   
                        return false;
                    }
                );
    }

    //////// JOBS /////
    protected function listaDeJobs(){
        // $this->lista_geral_jobs =  #chunk()
        # verificar permissões
        switch($this->user_role){
            case "admin":
                $this->lista_geral_jobs = 
                    Job::with([
                            'tipo',
                            'user',
                            'coordenador',
                            'delegado',
                            'tasks'
                        ]) 
                        ->orderByDesc('updated_at')
                        ->get();  
                break;
            case "desenvolvedor":
                $this->lista_geral_jobs = 
                        Job::with([
                                'tipo',
                                'user',
                                'coordenador',
                                'delegado',
                                'tasks'
                            ])
                            ->orderByDesc('updated_at')
                            ->get();
                break;
            case "publicador":
                $this->lista_geral_jobs = 
                    Job::with([
                            'tipo',
                            'user',
                            'coordenador',
                            'delegado',
                            'tasks'
                        ])
                        ->where('user_id', $this->auth_id)
                        ->orderByDesc('updated_at')
                        ->get();
                break;
            case "coordenador":
                $this->lista_geral_jobs = 
                    Job::with([
                            'tipo',
                            'user',
                            'coordenador',
                            'delegado',
                            'tasks'
                        ])
                        ->where('publicador_id', $this->publicador_id)
                        ->orderByDesc('updated_at')
                        ->get();
                    break;
            case "equipe":
                $this->lista_geral_jobs = 
                    Job::with([
                            'tipo',
                            'user',
                            'coordenador',
                            'delegado',
                            'tasks'
                        ])
                        ->where('delegado_para', $this->auth_id)
                        ->orderByDesc('updated_at')
                        ->get();
                    break;
            case "freelancer":
                $this->lista_geral_jobs = 
                    Job::with([
                        'tipo',
                        'user',
                        'coordenador',
                        'delegado',
                        'tasks'
                    ])
                    ->where('freela', 1)
                    ->orWhereIn('delegado_para', [null, $this->auth_id])
                    ->orderByDesc('updated_at')
                    ->get();
                    break;
            case "avaliador":
                $this->lista_geral_jobs = 
                    Job::with([
                        'tipo',
                        'user',
                        'coordenador',
                        'delegado',
                        'tasks'
                    ])
                    ->where('avaliador_id', $this->auth_id)
                    ->orderByDesc('updated_at')
                    ->get();
                break;
            default:
                $this->lista_geral_jobs = '';
                break;
            }
    }

    protected function processaListaDeJobs(){

        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        switch($this->user_role){
            case "admin":
                $this->jobsEmAndamento();
                $this->jobsCoordenando();
                $this->jobsConcluidos();
                $this->jobsAbertosAvulsos();
                $this->jobsAbertosFreelas();
                $this->jobsExecutando();
                $this->jobsParados();
                $this->jobsRecusados();                        
                break;
            case "desenvolvedor":
                $this->jobsEmAndamento();
                $this->jobsCoordenando();
                $this->jobsConcluidos();
                $this->jobsAbertosAvulsos();
                $this->jobsAbertosFreelas();
                $this->jobsExecutando();
                $this->jobsParados();
                $this->jobsRecusados();                    
                break;
            case "coordenador":
                $this->jobsEmAndamento();
                $this->jobsCoordenando();
                $this->jobsConcluidos();
                $this->jobsAbertosAvulsos();
                $this->jobsAbertosFreelas();
                $this->jobsExecutando();
                $this->jobsParados();
                $this->jobsRecusados();
                break;
            case "publicador":
                $this->jobsEmAndamento();
                $this->jobsCoordenando();
                $this->jobsConcluidos();
                $this->jobsAbertosFreelas();
                $this->jobsExecutando();
                $this->jobsParados();
                $this->jobsRecusados();
                break;
            case "equipe":
                $this->jobsEmAndamento();
                $this->jobsConcluidos();
                $this->jobsExecutando();
                $this->jobsParados();
                $this->jobsRecusados();
                $this->jobsAbertosAvulsos();
                break;
            case "freelancer":
                $this->jobsEmAndamento();
                $this->jobsConcluidos();
                $this->jobsExecutando();
                $this->jobsParados();
                $this->jobsRecusados();
                $this->jobsAbertosFreelas();
                break;
            case "avaliador":
                $this->jobsEmAndamento();
                $this->jobsConcluidos();
                $this->jobsExecutando();
                $this->jobsParados();
                $this->jobsRecusados();
                $this->jobsAvaliando();
                break;
            default:
            break;
            
        }

    }

    protected function jobsEmAndamento(){
        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        $this->jobs_andamento = 
            $this->lista_geral_jobs
                ->filter(
                    function($item){
                        return in_array($item->status, [1,5,6,8]);
                    }
                );
    }

    protected function jobsCoordenando(){
        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        $this->projetos_coordenando = 
            $this->lista_geral_jobs
                ->filter(
                    function($item){
                        return $item->coordenador_id == $this->auth_id && !in_array($item->status, [5,6]);
                    }
                );
    }

    protected function jobsAvaliando(){
        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        $this->avaliando = 
            $this->lista_geral_jobs
                ->filter(
                    function($item){
                        return $item->avaliador_id == $this->auth_id && !in_array($item->status, [5,6]);
                    }
                );
    }

    protected function jobsConcluidos(){
        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        $this->jobs_concluidos = 
            $this->lista_geral_jobs
                ->filter(
                    function($item){
                        return $item->status == 5;
                    }
                );
    }

    protected function jobsAbertosAvulsos(){
        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        $this->jobs_abertos = 
            $this->lista_geral_jobs
                ->filter(
                    function($item){
                        return $item->status == 0 && $item->avulso == 1 && is_null($item->delegado_para) && $item->freela == 0;
                    }
                );
    }

    protected function jobsAbertosFreelas(){
        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        $this->jobs_freelas = 
            $this->lista_geral_jobs
                ->filter(
                    function($item){
                        return ($item->status == 0 || $item->status == 9|| $item->status == 11 ) && $item->avulso == 1 && $item->freela == 1 && is_null($item->delegado_para);
                    }
                );
    }

    protected function jobsExecutando(){
        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        $this->executando = 
            $this->lista_geral_jobs
                ->filter(
                    function($item){
                        return $item->user_id == $item->auth_id  && $item->status == 2;
                    }
                );
    }

    protected function jobsParados(){
        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        $this->jobs_parados = 
            $this->lista_geral_jobs
                ->filter(
                    function($item){
                        return $item->status == 8;
                    }
                );
    }

    protected function jobsRecusados(){
        if(!isset($this->lista_geral_jobs) || empty($this->lista_geral_jobs)){
            $this->listaDeJobs();
        }

        $this->jobs_recusados = 
            $this->lista_geral_jobs
                ->filter(
                    function($item){
                        return $item->status == 6;
                    }
                );
    }

    
    ////// TASKS //////
    protected function listaDeTasksEmAndamento(){
        if(in_array($this->user_role, ['admin', 'desenvolvedor'])){
            $this->tasks_andamento = DB::table('jobs_tasks')->where('status', '0')->get();
        }
    }

    ////// IMAGENS //////
    protected function listaDeImgsEmAndamento(){
        if(in_array($this->user_role, ['admin', 'desenvolvedor'])){
            $this->imgs_andamento = DB::table('imagens')->whereIn('status', [0,1])->get();
        }
    }

}
