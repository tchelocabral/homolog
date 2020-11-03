<?php

namespace App\Http\Controllers;
use App\Models\JobCandidatura;
use App\Models\UserNotification;
use App\User;
use App\Models\Job;
use DB;
use App\Notifications\AlertAction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JobCandidaturaController extends Controller
{
    //
    protected $request;
    protected $job;
    protected $user_current;
    protected $user_id;
    protected $qtd_job;
    protected $cod_publicador;
    protected $user_current_role;

    public function __construct(Request $request, JobCandidatura $jobcandidatura) { 
        
        $this->request = $request;
        $this->job = $jobcandidatura;
        // $this->user_current_role = \Auth::user()->roles()->first;
       
        $this->middleware('auth');
        $this->middleware('permission:lista-job');
        $this->middleware('permission:atualiza-job', ['only' => ['edit','update']]);
        //dd($request);
    }

    public function mudarStatus($id, $novoStatus, Request $request) {
        $id = decrypt($id);
        try{
            DB::beginTransaction();

            $tipo_notificacao = "";
            $freelancer = "";

            $job_cand = JobCandidatura::where('id', $id)->with(['user', 'job'])->get()->first();
            $job = Job::where('id', $job_cand->job_id)->with(['delegado', 'coordenador'])->get()->first();

            $job_cand->status = $novoStatus; 
            $job_cand->save();
            
            // Aceito
            if($novoStatus==1) {
                //busca as candidaturas em status 0 do freela e candidaturas do job em status 0 
                $job_cand_trocar = JobCandidatura::where('user_id', $job_cand->user_id)->where('status', 0)->orWhere('job_id', $job_cand->job_id)->where('status', 0)->get();
                
                $candidato = User::where('id', $job_cand->user_id)->with(['configuracoes', 'jobsCandidaturaExecutando'])->get()->first();

                //é feito qtde_jobs_freela+1 pois o $job->save() ainda nao ocorre e para garantir que a quantidade nao seja afetada - 18/08/2020
                $qtde_jobs_freela = $candidato->jobsCandidaturaExecutandoTotal()+1;

                $job->delegado_para = $job_cand->user_id; 
               
               
                if($job->status_inicial == Job::$EMCANDIDATURA ) {
                    
                    $job->status = Job::$EMEXECUSAO;
                    $job->data_inicio = Carbon::now();
                    $rota = route('jobs.show', encrypt($job->id));

                     # Notificações
                    $freelancer = $candidato ;//$job->delegado()->get()->first();

                    $tipo_notificacao = "proposta_aceita";
                   
                }
                else
                {
                    $job->status = Job::$AGUARDANDOPAGAMENTO;
                }
               

                $job->valor_delegado =  $job_cand->valor;  
                $job->valor_job = $job_cand->valor / (100- $job->taxa)*100;
                
                // echo (100- $job->taxa);
                $job->save();

                                               
                $muda_status_cadidaturas = false;
                $user_config = $candidato->configuracoes;

                foreach ($user_config as $key => $value) {
                    if($value->chave=="qtde_jobs_candidaturas" && $value->valor <= $qtde_jobs_freela){
                        
                        $muda_status_cadidaturas = true;
                    }
                }
                //echo($qtde_jobs_freela . " - " . $muda_status_cadidaturas);
                //dd($candidato->jobsCandidaturaExecutando);
                if($muda_status_cadidaturas) {
                    foreach ($job_cand_trocar as $index => $value) {
                        $value->status = 4; 
                        $value->save();
                    }
                }
               
            }
            // Recusado
            else if($novoStatus==2) {

                # Notificações
                $freelancer = $job_cand->user()->get()->first();
                
                $tipo_notificacao = "proposta_recusada";
                
                $rota = route('home', encrypt($job->id));

            }
           
         
            if($tipo_notificacao){
                # informa se houver o delegado
                $param = array(
                    'cliente'       =>  null, 
                    'imagem'        =>  null, 
                    'job'           => $job, 
                    'task'          => null, 
                    'projeto'       =>  null, 
                    'tipo'          => $tipo_notificacao,
                    'destinatario'  => null,
                    'rota'          => $rota,
                );

                if($freelancer){
                    $param['destinatario'] = $freelancer;
                    $notificacao = new UserNotification($param);
                    $freelancer->notify(new AlertAction($notificacao));
                }

                //mandar notificação para o publicador do job se foi um coordenandor que aprovou a candidatura
                $usuario_ativo = \Auth::user();
                if($usuario_ativo->id != $job->publicador_id)
                {
                    $publicador = $job->publicador()->get()->first();
                    
                    if($usuario_ativo){
                        $param['destinatario'] = $publicador;
                        $param['tipo'] = 'proposta_aceita_publicador';
                        $notificacao = new UserNotification($param);
                        $publicador->notify(new AlertAction($notificacao));
                    }
                }   
            }

            // echo  $param['tipo'];
            // dd($tipo_notificacao);
            DB::commit();
            if($job->status_inicial == Job::$EMCANDIDATURA ) {
                session()->flash('message.level', 'success');
                session()->flash('message.content', __('messages.Status da Proposta alterado com sucesso') . '!'); #. Job::$status_array[$novoStatus] .
                session()->flash('message.erro', '');
                      
                return redirect()->back()->withInput();
            }
            else {
                if($job->status_inicial == Job::$status_array['emproposta'] && !\Auth::user()->hasPermissionTo('pos-pagamento')) {
                    return redirect()->route('job.publicador.view.pagamento', encrypt($job->id));
                } else {
                    return redirect()->route('job.mudar.pagamento.job.proposta', encrypt($job->id));
                }
            }
        } 
        catch (\Exception $exception) {

            DB::rollBack();
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Status da Proposta não pôde ser alterado') . '.'); #. $job->status_array[$novoStatus]
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            // dd('<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }

    }

}
