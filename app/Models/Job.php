<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Models\Comment;
use App\Models\JobRevisao;
use App\Models\JobPagamento;
use Carbon\Carbon;

class Job extends Model{

    use SoftDeletes;
    
    protected $fillable = [
        'id', 
        'tipojob_id', 
        'deliveryformat_id',
        'job_delivery_value',
        'user_id', 
        'publicador_id', 
        'coordenador_id', 
        'avaliador_id', 
        'delegado_para', 
        'nome', 
        'descricao', 
        'observacoes',
        'campos_personalizados', 
        'data_inicio', 
        'data_prox_revisao', 
        'data_entrega', 
        'valor_job', 
        'status', 
        'status_inicial', #0-novo, 9-emcandidatura, 11-emproposta
        'avulso', 
        'porcentagem_individual',
        'freela',
        'taxa',
        'data_limite',
        'valor_delegado',
        'pg_publicador',
        'pg_freelancer',
        'hr_url',
        'hr_solicitado',
        'concluido_por',
        'thumb' => 'imagens/jobs/job_default.png'];

    
    protected $dates = [
        'data_inicio', # data quando o job é delegado
        'data_prox_revisao', #dead line do job
        'data_entrega', #data de conclusão do job
        'created_at',
        'updated_at',
        'data_limite' #data limite de candidatura/proposta
    ];

    protected $casts = [
        'campos_personalizados' => 'array',
        'avulso'                => 'boolean',
        'job_delivery_value'    => 'array'
    ];

    /**
     * Todos os relacionamentos a serem "tocados".
     *
     * @var array
    */
    protected $touches = ['imagens'];

    public static $NOVO                 = 0; # novo, publicado
    public static $DELEGADO             = 1; 
    public static $EMEXECUSAO           = 2; # publicador e freela - todos veem. um job nao avulso, delegado, começa aqui
    public static $EMREVISAO            = 3; 
    public static $EMAVALIACAO          = 4; 
    public static $CONCLUIDO            = 5; #CONCLUIDO PARA TODOS
    public static $RECUSADO             = 6; #PUBLICADOR E FREELA
    public static $REABERTO             = 7; 
    public static $PARADO               = 8; #parado
    public static $EMCANDIDATURA        = 9; #em candidatura
    public static $EXPIRADO             = 10; #expirado
    public static $EMPROPOSTA           = 11; #EMPROPOSTA
    public static $AGUARDANDOPAGAMENTO  = 12; #AGUARDANDOPAGAMENTO

    // public static $PUBLICADO    =    NOVO 0; 

    // em_andamento = 

    // OS 4 IRÃO PARA OMENU, E SOMENTE OS QUATRO + todos e publicar novo
    public static $status_array = array(
        '0' => 'Novo',
        '1' => 'Delegado',
        '2' => 'Em Execução',
        '3' => 'Em Revisão',
        '4' => 'Em Avaliação',
        '5' => 'Concluído',
        '6' => 'Recusado',
        '7' => 'Reaberto',
        '8' => 'Parado',
        '9' => 'Em Candidatura',
        '10'=> 'Expirado',
        '11'=> 'Em Proposta',
        '12'=> 'Pagamento Pendente',
        
        'novo'                  =>  '0',
        'delegado'              =>  '1',
        'emexecucao'            =>  '2',
        'emrevisao'             =>  '3',
        'emavaliacao'           =>  '4',
        'concluido'             =>  '5',
        'recusado'              =>  '6',
        'reaberto'              =>  '7',
        'parado'                =>  '8',
        'emcandidatura'         =>  '9', 
        'expirado'              =>  '10', 
        'emproposta'            =>  '11',
        'pagamentopendente'     =>  '12'
    );


    // Relacionamentos
    public function tipo() {
        return $this->belongsTo(TipoJob::class, 'tipojob_id');
    }

    public function user() {
        return $this->belongsTo(\App\User::class);
    }

    public function coordenador() {
        return $this->belongsTo(\App\User::class, 'coordenador_id');
    }

    public function publicador() {
        return $this->belongsTo(\App\User::class, 'publicador_id');
    }

    public function pagamento() {
        return $this->hasOne(JobPagamento::class);
    }


    public function avaliador() {

        return $this->belongsTo(\App\User::class, 'avaliador_id');
    }

    public function delegado() {
        return $this->belongsTo(\App\User::class, 'delegado_para');
    }

    public function projeto(){
           return $this->imagens()->first()->projeto()->first();
    }

    public function tasks() {
        return $this->belongsToMany(Task::class, 'jobs_tasks', 'job_id', 'task_id')->withPivot('status', 'ordem', 'user_id')->orderBy('jobs_tasks.ordem')->withTimestamps();
    }

    public function imagens() {
        return $this->belongsToMany(Imagem::class, 'jobs_imagens', 'job_id', 'imagem_id')->with(['tipo','arquivos', 'projeto'])->withTimestamps();
    }

    public function avaliacao() {
        return $this->hasMany(JobAvaliacao::class);
    }

    public function midias() {
        return $this->belongsToMany(Midia::class, 'midias_jobs', 'job_id', 'midia_id' )->with('tipo_arquivo')->withTimestamps();
    }

    public function movimentacoesFinanceiras() {
        return $this->morphMany(UserFinanceiro::class, 'financeiro');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    //histórico de todas as vezes que o job foi parado
    public function parados()
    {
        return $this->hasMany(JobParado::class);
    }

    public function parado() {
        return $this->hasMany(JobParado::class)->orderBy('created_at', 'desc')->get()->first();
    }

    public function revisoes() {
        return $this->hasMany(JobRevisao::class)->with(['marcadores', 'tasksRevisao']);
    }

    public function habilidades() {
        return true;
    }

    // Cálculos de Conclusão
    public function concluido() {
        // Se o status do Job for 8 = parado ou 5 = concluído, retorna 100%
        if($this->status == 8 || $this->status == 5) {
            return 100;

        }else{

            $total     = $this->tasks()->count();
            $concluido = $this->tasks()->where('status', 1)->count();

            $job_revisoes_total =0;
            $job_revisoes_concluido =0;
            if($this->revisoes->count()>0) {

                foreach($this->revisoes as $key => $rev){

                    $job_revisoes_total += $rev->tasksRevisao->count();
                    $job_revisoes_concluido += $rev->tasksRevisao->where('status', 1)->count();
                }
            }

            $total += $job_revisoes_total;
            $concluido += $job_revisoes_concluido;
            //echo $total . " - " . $concluido;
            return $concluido||$total>0 ? number_format($concluido/$total*100,0) : 0;
        }
        // return $concluido/$total*100;
    }


    /**  
     * Cálculos de Conclusão
     * $contagem_geral inclui todas as tasks de todas as revisões do job
     * padrão false traz só da última, corrente.     * 
    */
    public function concluidoRevisao($contagem_geral = false) {
        
        // Se o status do Job for 8 = parado ou 5 = concluído, retorna 100%
        if($this->status == 8 || $this->status == 5) {
            return 100;
        }else{
            $job_revisoes_total =0;
            $job_revisoes_concluido =0;
            $total = 0;
            $concluido = 0;
            if($this->revisoes->count()>0) {

                if($contagem_geral){
                    foreach($this->revisoes as $key => $rev){

                        $job_revisoes_total += $rev->tasksRevisao->count();
                        $job_revisoes_concluido += $rev->tasksRevisao->where('status', 1)->count();
                    }
                } else {
                    $rev = $this->revisoes->last();
                    $job_revisoes_total += $rev->tasksRevisao->count();
                    $job_revisoes_concluido += $rev->tasksRevisao->where('status', 1)->count();
                }

                
            }
            $total += $job_revisoes_total;
            $concluido += $job_revisoes_concluido;
            //echo $total . " - " . $concluido;
            return $concluido||$total>0 ? number_format($concluido/$total*100,0) : 0;
        }
    }


    public function getValorJobAttribute($value)  {
        if($value!=null)
        {
            return str_replace('.', ',',$value); 
        }
        else
        {
            return null;
        }
    }

    //get mutator do campo Descricao do job
    public function getDescricaoAttribute($value)  {
        if($value!=null) {
          return clearFieldJsTag($value); 
            // return $value; 
        }
        else {
            return null;
        }
    }

    //TODO:set mutator do campo Descricao do job
    // public function setDescricaoAttribute($value)  {
    //     $retorno = null;
    //     if($value!=null) {
    //         $retorno = $this->clearFieldJsTag($value); 
    //     }
    //     dd($retorno);
    //     return $retorno;
    // }


    public function verificaStatus($stts){
        return 
            array_key_exists($stts, Job::$status_array) 
            && Job::$status_array[$stts] == $this->status;
    }

    public function getStatus($stts)    {
        return Job::$status_array[$stts];
    }

    
    // public function avaliacao() {

    //     return $this->belongsTo(\App\User::class, 'delegado_para');
    // }

    public function candidaturas() {
        return $this->hasMany(JobCandidatura::class)->with('user')->orderBy('id','DESC');
    }

    public function candidaturaFreela()  {
        return $this->hasOne(JobCandidatura::class)
                    ->where('user_id', \Auth::user()->id)
                    ->whereIn('status', [0,4]);
    }

    public function candidaturaFreelaAberta()  {
        return $this->hasOne(JobCandidatura::class)
                    ->where('user_id', \Auth::user()->id)
                    ->where('status', 0)
                    ->where('jobs.data_limite', '<', Carbon::now()->tomorrow());
    }

    public function candidaturaFreelaSemSlot()  {
        return $this->hasOne(JobCandidatura::class)
                    ->where('user_id', \Auth::user()->id)
                    ->whereIn('status', 4);
    }

    
    public function valorDoJob($role){
        return 
            $role == 'freelancer' 
                ?( 
                    !is_null($this->valor_delegado) && $this->valor_delegado > 0
                    ? floatval($this->valor_delegado) 
                    : floatval($this->valor_job) - floatval($this->valor_job)*floatval($this->taxa)/100
                )
                : floatval($this->valor_job);
    }

    public function pagamentoEfetivado(){
        return $this->hasOne(JobPagamento::class)->whereNotNull('pago_em');
    }


    public function pagamentoPendente(){
        return $this->hasOne(JobPagamento::class)->where('pago_em');
    }


    public function todosPagamentos(){
        return $this->hasMany(JobPagamento::class);
    }

    public function avaliacoes()
    {
        return $this->morphMany(Avaliacao::class, 'model')->orderBy('created_at', 'desc');
    }

    public function jobsPagamentosPos() {

        return $this->hasMany(JobPagamento::class)
        ->where('pago_em', null);
    }

    public function jobsPagamentosPendentes() {

        return $this->hasMany(JobPagamento::class)
        ->where('pago_em', null)
        ->where('jobs_pagamentos.status', 'PENDING');
    }

    public function getFormatoEntrega(){
        return $this->hasOne(DeliveryFormat::class, 'id', 'deliveryformat_id')->get();
    }

}
