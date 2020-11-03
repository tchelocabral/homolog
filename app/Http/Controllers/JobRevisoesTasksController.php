<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobsRevisoesTasks;
use App\Models\Job;


class JobRevisoesTasksController extends Controller
{
    protected $request;
    protected $task;

    public function __construct(Request $request, JobsRevisoesTasks $task) {

        $this->request = $request;
        $this->task = $task;
        $this->middleware('auth');
        $this->middleware('permission:lista-task');
        $this->middleware('permission:cria-task', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-task', ['only' => ['edit','update', 'desfazerTask', 'executarTask']]);
        $this->middleware('permission:deleta-task', ['only' => ['destroy']]);
    }

    // Função de retorno em Json - Tasks Revisoes Job
    public function executarTask(Request $request, $job_id, $task_id) {

        $job_id = decrypt($job_id);
        $task_id = decrypt($task_id);
        try{

            \DB::beginTransaction();
            if(!isset($job_id) && isset($task_id)) {
                return false;
            }

            $job = Job::where('id', $job_id)->with('revisoes')->get()->first();
            $task = JobsRevisoesTasks::find($task_id); 
            if(isset ($task)) {
                $task->status = 1;
                $task->user_id = \Auth::user()->id;
                $task->save();
            }

            if($task->notification){
               
                $tipo       = $job->concluido() == 100 ? "job_pode_concluir" : "job_task_exec" ;
                // $nome_obj   = $job->concluido() == 100 ? $job->id : $task->nome;
                $coord      = $job->coordenador ?? false;
                $coord_proj = $job->imagens->count() > 0 ? $job->imagens->first()->projeto->coordenador : false;

                $delegado   = $job->delegado ?? null;
                // dd($job->tasks());
                if($job->avulso){
                    $proj = null;
                }else{                
                    $proj = $job->imagens->count() > 0 ? $job->imagens->first()->projeto : false;
                }
                // envio de notificação para coordenador da imagem/projeto quando uma task é concluida
                // $proj = Projeto::where('id', $request->get('projeto_id'))->with(['coordenador'])->get()->first() ?? false;
                $rota = route('jobs.show', encrypt($job_id));
                $param = array(
                    'cliente'       => $proj ? $proj->cliente : null, 
                    'imagem'        => $job->imagens, 
                    'job'           => $job, 
                    'task'          => $task, 

                    'projeto'       => $proj, 
                    'tipo'          => $tipo,
                    'destinatario'  => null, 
                    'rota'          => $rota,
                );

                if($coord || $coord_proj){
                    if($coord && $coord_proj && $coord->id != $coord_proj->id){
                        $param['destinatario'] = $coord;
                        $newUserNot = new UserNotification($param);
                        $coord->notify(new AlertAction($newUserNot));

                        $param['destinatario'] = $coord_proj;
                        $newUserNot = new UserNotification($param);
                        $coord_proj->notify(new AlertAction($newUserNot));
                    }else{
                        if($coord){

                            $param['destinatario'] = $coord;
                            $newUserNot = new UserNotification($param);
                            $coord->notify(new AlertAction($newUserNot));
                        } else{

                            $param['destinatario'] = $coord_proj;
                            $newUserNot = new UserNotification($param);
                            $coord_proj->notify(new AlertAction($newUserNot));
                        }
                    }
                }

                if($delegado && $job->freela ==1 )
                {                 
                    $user_job = $job->user()->get()->first();
                    $param['destinatario'] = $user_job;
                    $newUserNot = new UserNotification($param);
                    //dd($newUserNot);
                    $user_job->notify(new AlertAction($newUserNot));
                }
                
            }   
            \DB::commit();

            if($request->ajax()) {
                return \Response::json(array(
                    'code'      =>  200,
                    'message'   =>  'Task executada. '
                ), 200);
            }


        }catch (\Exception $exception){

            \DB::rollBack();

            if($request->ajax()) {
                return \Response::json(array(
                    'code'      =>  500,
                    'message'   =>  'Task não executada. ' . $exception->getMessage(),
                    'req'       =>  $request->get('task_id')
                ), 500);
            }
        }
        
        return redirect()->back();
    } // Função de retorno em Json
    

    public function desfazerTask(Request $request, $job_id, $task_id) {
        $job_id = decrypt($job_id);
        $task_id = decrypt($task_id);
        try{

            if(isset($job_id) && isset($task_id)) {
                \DB::beginTransaction();

                $job = Job::where('id', $job_id)->with('revisoes')->get()->first();
                $task = JobsRevisoesTasks::find($task_id); 
                if(isset ($task)) {
                    $task->status = 0;
                    $task->user_id = \Auth::user()->id;
                    $task->save();
                }
    
                if($job->avulso)
                {
                    $proj = null;
                }
                else {                
                    $proj = $job->imagens->count() > 0 ? $job->imagens->first()->projeto : false;
                }

                if($task->notification){

                    $rota = route('jobs.show', encrypt($job_id));
                    $tipo = "job_task_desf";
                    $nome_obj   = $task->nome;
                    $coord      = $job->coordenador ?? false;
                    $coord_proj = $job->imagens->count() > 0 ? $job->imagens->first()->projeto->coordenador : false;

                      $param = array(
                        'cliente'       => $proj ? $proj->cliente : null, 
                        'imagem'        => $job->imagens, 
                        'job'           => $job, 
                        'task'          => $task, 
                        'projeto'       => $proj, 
                        'tipo'          => $tipo,
                        'destinatario'  => null, 
                        'rota'          => $rota,
                    );
            
                    // envio de notificação para coordenador da imagem/projeto quando uma task é concluida
                    if($coord || $coord_proj){
                        if($coord && $coord_proj && $coord->id != $coord_proj->id){
                            $param['destinatario'] = $coord;
                            $newUserNot = new UserNotification($param);
                            $coord->notify(new AlertAction($newUserNot));


                            $param['destinatario'] = $coord_proj;
                            $newUserNot = new UserNotification($param);
                            $coord_proj->notify(new AlertAction($newUserNot));
                        }else{
                            if($coord){
                                $param['destinatario'] = $coord;
                                $newUserNot = new UserNotification($param);
                                $coord->notify(new AlertAction($newUserNot));

                            } else{
                                $param['destinatario'] = $coord_proj;
                                $newUserNot = new UserNotification($param);
                                $coord_proj->notify(new AlertAction($newUserNot));
                            }
                        }
                    }
                }

                \DB::commit();
            
                // if($request->ajax()) {
                //     return \Response::json(array(
                //         'code'      =>  200,
                //         'message'   =>  'Task desfeita.'
                //     ), 200);
                // }
            }

        }catch (\Exception $exception){

            \DB::rollBack();

            dd($request);
            if($request->ajax()) {
                return \Response::json(array(
                    'code'      =>  500,
                    'message'   =>  'Task não desfeita. ' . $exception->getMessage(),
                    'req'       =>  $request->get('task_id')
                ), 500);
            }
        }

        return redirect()->back();
    }
}
