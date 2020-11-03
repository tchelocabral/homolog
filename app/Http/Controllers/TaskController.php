<?php

namespace App\Http\Controllers;

use App\Notifications\AlertAction;
use App\Models\UserNotification;
use App\Models\Task;
use Session;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Job;
use App\User;
use App\Models\Imagem;

class TaskController extends Controller
{
    protected $request;
    protected $task;

    public function __construct(Request $request, Task $task) {

        $this->request = $request;
        $this->task = $task;
        $this->middleware('auth');
        $this->middleware('permission:lista-task');
        $this->middleware('permission:cria-task', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-task', ['only' => ['edit','update', 'desfazerTask', 'executarTask']]);
        $this->middleware('permission:deleta-task', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $tasks = Task::all();
        return view('task.lista', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return view('task.novo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $validator = $this->validate($request, [
            'nome' => 'required'
        ]);

        try{
            $task = Task::create([
                'nome'          => $request->get('nome'),
                'descricao'     => $request->get('descricao'),
                'notification'  => $request->get('notification') ?? 0,
                'porcentagem_individual' => $request->get('porcentagem_individual'),

            ]);

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' cadastrado com sucesso!');
            $request->session()->flash('message.erro', '');

        }catch (\Exception $exception) {

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A task não pôde ser cadastrada.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('tasks.index');

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id) {
        //
        $id = decrypt($id);
        $task = Task::find($id);
        return view('task.detalhes', compact('task'));
    }

    /**
     * @param Task $task
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit( $id) {
        //
        $id = decrypt($id);
        $task = Task::find($id);
        return view('task.edit', compact('task'));

    }

    /**
     * @param Request $request
     * @param Task $task
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id) {

        $id = decrypt($id);
        $validator = $this->validate($request, [
            'nome' => 'required'
            ]);
      
        try{

            $task = Task::find($id);
            $task->fill($request->all());
            $task->save();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' atualizado com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('tasks.index');

        }catch (\Exception $exception){
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A task não pôde ser atualizada!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id) {
        //
        $id = decrypt($id);
        try{
            \DB::beginTransaction();

            $task = Task::findOrFail($id);
            $task->delete();

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content', 'Task excluída com sucesso!');
            Session::flash('message.erro', '');

            \DB::commit();


            return redirect()->route('tasks.index');
        } catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'A task não pôde ser excluído.');
            Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->route('tasks.index');
        }
    }



    // Função de retorno em Json
    public function executarTask(Request $request, $job_id, $task_id) {
        $job_id = decrypt($job_id);
        $task_id = decrypt($task_id);
        try{

            \DB::beginTransaction();

            if(!isset($job_id) && isset($task_id)) {
                return false;
            }

            $job = Job::where('id', $job_id)->with(['coordenador', 'imagens'])->get()->first();
            $job->tasks()->updateExistingPivot($task_id, [
                'status'  => 1, 
                'user_id' => \Auth::user()->id
            ]);

            $task = Task::find($task_id); 

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

                $job = Job::find($job_id);
                $job->tasks()->updateExistingPivot($task_id, [
                    'status'  => 0,
                    'user_id' => \Auth::user()->id
                ]);

                $task = Task::find($task_id); 

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
