<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use App\Models\Avaliacao;
use App\User;
use App\Models\Job;
use DB;
use App\Notifications\AlertAction;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AvaliacaoController extends Controller
{
    //
    public function __construct(Request $request, Avaliacao $avaliacao) { 
        
        $this->request = $request;
        $this->avaliacao = $avaliacao;
        // $this->user_current_role = \Auth::user()->roles()->first;
       
        // $this->middleware('auth');
        // $this->middleware('permission:lista-job');
        // $this->middleware('permission:atualiza-job', ['only' => ['edit','update']]);
        //dd($request);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //

        $validator = $this->validate($request, [
            'job_id' => 'required',
            'avaliado_id' => 'required',
            'avaliador_id' => 'required',
            'nota' => 'required',
        ]);

        try{
            DB::beginTransaction();
           
            $job_id = $request->get('job_id');
            
            $avaliacao = Avaliacao::create([
                'avaliado_id'            => $request->get('avaliado_id'),
                'avaliador_id'           => $request->get('avaliador_id'),
                'nota'                   => $request->get('nota'),
                'observacoes'            => $request->get('observacoes'),
                'model_id'               => decrypt($job_id),
                'model_type'             => $request->get('type'),
            ]);
            
            #notificação dos envolvidos
            $rota = route('jobs.show', encrypt($job_id));
            // $nome_obj   = $job->id;

            // $param = array(
            //     'cliente'       => $proj ? $proj->cliente : null, 
            //     'imagem'        => $job->imagens()->get(), 
            //     'job'           => $job, 
            //     'task'          => null, 
            //     'projeto'       => $proj, 
            //     'tipo'          => null,
            //     'destinatario'  => $colab, 
            //     'rota'          => $rota,
            // );        

            // $param['tipo'] = "job_colab_novo_vc";

            // $notificacao = new UserNotification($param);
            // $colab->notify(new AlertAction($notificacao));


            DB::commit();

             # status de retorno
             $request->session()->flash('message.level',   'success');
             $request->session()->flash('message.content',  __('messages.Avaliação cadastrada com sucesso'));
             $request->session()->flash('message.erro', '');

            return redirect()->route('jobs.show', $job_id);

        }catch (\Exception $exception){

            DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('messages.A avaliação não pôde ser efetuada') . '.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            echo $exception->getMessage().'<br>'.$exception->getLine();
            dd($request);
            return redirect()->back()->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function show(Plano $plano) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function edit(Plano $plano) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plano $plano) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plano  $plano
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plano $plano) {
        //
    }
}
