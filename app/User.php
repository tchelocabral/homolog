<?php

namespace App;

use App\Models\ConfiguracoesUser;
use App\Models\Job;
use App\Models\JobPagamento;
use App\Models\Plano;
use App\Models\Projeto;
use App\Models\UserConta;
use App\Models\UserFinanceiro;
use App\Models\UserMeta;
use App\Models\JobCandidatura;
use App\Models\Avaliacao;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;


use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    // reavaliar is_admin
    protected $fillable = [
        'name', 'marcador', 'email', 'password', 'ativo', 'activation_code', 'is_admin', 'bio', 'sexo',
        'cep','logradouro','bairro','cidade','uf','numero','complemento','telefone','tel_alternativo',
        'observacoes', 'image', 'nova_senha', 'razao_social','cnpj', 'nome_fantasia', 'publicador_id',
        'pais', 'data_nascimento', 'pais_nascimento', 'cpf', 'conta_paypal','url_portfolio', 'site', 'desativado_em', 'exclusao_solicitada_em','display_name'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'data_nascimento',
        'last_login_at',
        'created_at',
        'updated_at',
        'banned_at',
        'deleted_at',
        'desativado_em'
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // protected $dateFormat = 'U';

    protected $casts = [
        'is_admin'   => 'boolean',
        'nova_senha' => 'boolean',
    ];

    protected $tipos_notificacao = array(
        'alert' => 'AlertAction',
        'comment' => 'CommentAlert',
        'payment' => 'PaymentAlert',
    );

    public function plano() {
        return $this->belongsTo(Plano::class);
    }
    public function meta() {
        return $this->hasOne(UserMeta::class);
    }
    public function configuracoes() {
        return $this->hasMany(ConfiguracoesUser::class);
    }
    public function slotsJobsLivresTotal(){
        return 
            $this->configuracoes()->where('chave', 'qtde_jobs_andamento')->get()->first()->valor
            ??
            0;    
    }
    public function slotsJobsCandidaturasTotal(){
        return $this->configuracoes()->where('chave', 'qtde_jobs_candidaturas')->get()->first()->valor
                ??
                0;
    }
    
    public function coordenadores() {
        return  $this->hasMany(User::class, 'publicador_id', 'id');
    }

    // Jobs delegados a this
    public function jobs() {
        return $this->hasMany(Job::class, 'delegado_para', 'id');
    }

    public function coordenando() {
        return $this->hasMany(Job::class, 'coordenador_id', 'id');
    }

    // Jobs delegado a this em execução
    public function executando() {
        return $this->hasMany(Job::class, 'delegado_para', 'id')->where('jobs.status', Job::$EMEXECUSAO);
    }

    // Jobs delegado a this concluídos
    public function concluidos() {
        return $this->hasMany(Job::class, 'delegado_para', 'id')->where('status', Job::$CONCLUIDO);
    }

    // Jobs delegado a this em candidatura
    public function propostas() {
        return $this->hasMany(JobCandidatura::class, 'user_id', 'id')->where('status', 0);
    }

    // Jobs delegado a this em andamento (novo, delegado, em execução, emrevisao, emavaliacao, reaberto)
    public function jobsEmAndamento() {
        return $this->hasMany(Job::class, 'delegado_para', 'id')
                    ->whereIn('jobs.status', [Job::$NOVO, Job::$DELEGADO, Job::$EMEXECUSAO, Job::$EMREVISAO, Job::$EMAVALIACAO, Job::$REABERTO]);
    }

    public function jobsRecusados() {
        return $this->hasMany(Job::class, 'delegado_para', 'id')->where('jobs.status', 6);
    }

    public function coordenandoProjetos() {
        return $this->hasMany(Projeto::class, 'coordenador_id', 'id');
    }   

    public function avaliando() {
        return $this->hasMany(Job::class, 'avaliador_id', 'id');
    }

    public function publicados(){
        return $this->hasMany(Job::class, 'user_id', 'id');
    }



    //mudança na função para reconhecer a role do usuário e buscar pelo publicador_id usando o id ou publicador_id 31082020
    public function jobsPublicadosAbertos($roleUsuarioCon){
        // Adicionado delegado_para = null em 21.09.20
        if($roleUsuarioCon == "publicador") {
            $jobs = $this->hasMany(Job::class, 'publicador_id', 'id')->whereIn('jobs.status', [0,9,11])->where('jobs.delegado_para', null); //Adicionado delegado_para = null em 21.09.20
        } elseif($roleUsuarioCon == "coordenador")  {
            $jobs = $this->hasMany(Job::class, 'publicador_id', 'publicador_id')->whereIn('jobs.status', [0,9,11])->where('jobs.delegado_para', null);
        }
        return $jobs;
    }

    public function jobsPublicadosExecutando($roleUsuarioCon){
        if($roleUsuarioCon == "publicador") {
            $jobs = $this->hasMany(Job::class, 'publicador_id', 'id')->whereIn('jobs.status', [2,7]);
        } elseif($roleUsuarioCon == "coordenador")  {
            $jobs = $this->hasMany(Job::class, 'publicador_id', 'publicador_id')->whereIn('jobs.status', [2,7]);
        }
        return  $jobs;
    }

    public function jobsPublicadosConcluidos($roleUsuarioCon){
        if($roleUsuarioCon == "publicador") {
            $jobs = $this->hasMany(Job::class, 'publicador_id', 'id')->where('jobs.status', 5);
        } elseif($roleUsuarioCon == "coordenador")  {
            $jobs = $this->hasMany(Job::class, 'publicador_id', 'publicador_id')->where('jobs.status', 5);
        }
        return  $jobs;

    }

    public function jobsPublicadosRecusados($roleUsuarioCon){
        if($roleUsuarioCon == "publicador") {
            $jobs = $this->hasMany(Job::class, 'publicador_id', 'id')->where('jobs.status', 6);
        } elseif($roleUsuarioCon == "coordenador")  {
            $jobs = $this->hasMany(Job::class, 'publicador_id', 'publicador_id')->where('jobs.status', 6);
        }
        return  $jobs;
    }

    public function jobsPublicadosEmCandidatura(){
        return $this->hasMany(Job::class, 'publicador_id', 'id')
                    ->whereIn('jobs.status', [Job::$EMCANDIDATURA, Job::$EMPROPOSTA, Job::$EXPIRADO]);
    }

    public function jobsPublicadosEmCandidaturaAbertos(){
        return $this->hasMany(Job::class, 'user_id', 'id')
                    ->whereIn('jobs.status', [Job::$EMCANDIDATURA, Job::$EMPROPOSTA])
                    ->where('jobs.data_limite', '>', Carbon::now());
    }

    public function jobsPagamentosPendentes(){
        return $this->hasMany(Job::class, 'user_id', 'id')
            ->whereHas('pagamentoPendente');
            // ->with(['jobsPagamentosPendentes','pagamentoEfetivado'])
            // ->where('jobs.status', Job::$AGUARDANDOPAGAMENTO);
            // ->orWhere('jobs.pg_publicador', null);
    }

    public function jobsPagamentosConcluidos(){
        return $this->hasMany(Job::class, 'user_id', 'id')
            ->whereHas('pagamentoEfetivado');
            // ->where('jobs.status', Job::$AGUARDANDOPAGAMENTO);
            // ->orWhere('jobs.pg_publicador', null);
    }

    // public FUNCTION jobsPagamentosPos() {
    //     // return $this->hasManyThrough(
    //     //     JobPagamento::class, 
    //     //     Job::class, 
    //     //     'publicador_id',  //Foreign key job pagamento
    //     //     'id', // Foreign key jobs
    //     //     'id', // user id
    //     //     'job_id');

    //         return $this->hasMany(Job::class, 'user_id', 'id')->whereHas('JobPagamento');
    // }


    public function sendPasswordResetNotification($token){
        $this->notify(new ResetPassword($token));
    }
   
    public function isPublicador(){
        return \Auth::user()->roles()->first()->name == "publicador";
    }

    public function isDev(){
        return \Auth::user()->roles()->first()->name == "desenvolvedor";
    }

    public function isAdmin(){
        return \Auth::user()->roles()->first()->name == "admin";
    }

    public function isFreela(){
        return \Auth::user()->roles()->first()->name == "freelancer";
    }

    public function isCoordenador(){
        return \Auth::user()->roles()->first()->name == "coordenador";
    }

    public function conta(){
        return $this->hasOne(UserConta::class);
    }

    public function financeiro(){
        return $this->hasMany(UserFinanceiro::class);
    }

    public function galeria(){
        return $this->hasMany(UserMeta::class, 'user_id', 'id')->where('key', 'img_galeria');
    }

    //função quantidade de jobs status inicial 0 sendo executado
    public function jobsOrigemNovo()
    {
        return $this->hasMany(Job::class, 'delegado_para', 'id')->where('jobs.status_inicial', Job::$status_array['novo']);
    }

    public function jobsOrigemNovoExecutando()
    {
        return $this->hasMany(Job::class, 'delegado_para', 'id')
        ->where('jobs.status_inicial', Job::$status_array['novo'])
        ->where('jobs.status', Job::$status_array['emexecucao']);
    }

    public function  jobsOrigemNovoExecutandoTotal()
    {
        return $this->hasMany(Job::class, 'delegado_para', 'id')
        ->where('jobs.status_inicial', Job::$status_array['novo'])
        ->where('jobs.status', Job::$status_array['emexecucao'])
        ->get()->count();
    }

    //função trás todos os jobs status inicial 9 e 11
    public function jobsCandidatura()
    {   
        // return $this->hasManyThrough(
        //     Job::class, 
        //     JobCandidatura::class,
        //     'job_id',
        //     'delegado_para',
        //     'id',
        //     'id'
        // );
        return $this->hasMany(Job::class, 'delegado_para', 'id')
                    ->whereIn('jobs.status_inicial', [Job::$status_array['emcandidatura'], Job::$status_array['emproposta']]);
    }

    public function jobsCandidaturaExecutando() {
        return $this->hasMany(Job::class, 'delegado_para', 'id')
            ->whereIn('jobs.status_inicial', [Job::$status_array['emcandidatura'],Job::$status_array['emproposta']])
            ->where('jobs.status', Job::$status_array['emexecucao']);;
    }   

    public function jobsCandidaturaExecutandoTotal() {
        return $this->jobsCandidaturaExecutando()->count();
    }   

    //função quantidade de candidaturas abertas status (0 ou 4) e data_limite < que dia +1
    public function candidaturaAbertas()  {
        return $this->hasMany(JobCandidatura::class)
                    ->whereIn('jobs_candidaturas.status', [0,4])
                    ->join('jobs', 'jobs_candidaturas.job_id', '=', 'jobs.id')
                    ->where('jobs.data_limite','>=', Carbon::now())->orWhere('jobs.data_limite', null)
                    ->with('job');
    }

    public function candidaturaAbertasTotal()  {
        return $this->candidaturaAbertas()->count();
    }


    //função sobrescrita para receber o tipo de notificação 
    public function notifications($tipo="")
    {   
        $notifs = $this->morphMany(DatabaseNotification::class, 'notifiable');
        if($tipo !="")
        {
            $tipo_certo = $this->tipos_notificacao[$tipo];
            $notifs = $notifs->where('type', 'like',  '%'.$tipo_certo.'%');
        }
        $notifs = $notifs->orderBy('created_at', 'desc');
        return $notifs;
    }

    //função sobrescrita para receber o tipo de notificação 
    public function unreadNotifications($tipo = 'alert')
    {   
        $tipo_certo = $this->tipos_notificacao[$tipo];
        $notifs = $this->notifications($tipo)->whereNull('read_at')->where('type', 'like',  '%'.$tipo_certo.'%');
        return $notifs;
    }

    
    //função avaliacoes que o usuario fez
    public function avaliado() {
        return $this->hasMany(Avaliacao::class, 'avaliador_id');
    }

    //função avaliacoes sobre o usuario
    public function avaliacoes() {
        return $this->hasMany(Avaliacao::class, 'avaliado_id', 'id');
    }

    public function avaliado_id($id)
    {
        return Avaliacao::where('avaliado_id', $this->id);
            //função avaliacoes sobre o usuario

    }

    public function avaliacaoRecebidaPorModel($model) {

        $tipo = strtolower (class_basename($model));

        return Avaliacao::where('model_type', $tipo)->where('model_id',  $model->id)->where('avaliado_id', $this->id)->get();
    }

    public function avaliacaoRealizadaPorModel($model) {

        $tipo = strtolower (class_basename($model));

        return Avaliacao::where('model_type', $tipo)->where('model_id',  $model->id)->where('avaliador_id', $this->id);
    }

} // end class
