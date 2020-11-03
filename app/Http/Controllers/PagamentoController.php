<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\AlertAction;
use App\Models\Task;
use App\Models\Job;
use App\Models\JobPagamento;
use App\Models\UserFinanceiro;
use App\Models\UserNotification;
use App\Models\Configuracao;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;


class PagamentoController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function geraViewPagamentoPayPal($id, Request $request)  {
      
        $id = decrypt($id); 
        try{

            $job_temp = new Job();
            $job = Job::where('id', $id)
            ->with(
                ['tipo','user', 'coordenador', 'jobsPagamentosPos', 'delegado', 'tasks', 'midias', 'avaliacao','candidaturas', 'candidaturaFreela']
            )
            ->get()->first();

                
        //    dd($job);
            if($job->status != $job_temp->getStatus('pagamentopendente') && $job->pg_publicador != null){
                $job = null;
            }
    
            // Se não existir Job na busca
            if(!$job){
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('messages.Job não encontrado') . '.');
                session()->flash('message.erro', '');
                return redirect()->route('home');
            }

            //dd($job);


            $valor = $job->valor_job && !empty($job->valor_job)
            ? str_replace(",",".", str_replace([".", "R$ "], "", $job->valor_job))
            : null;
            $job->valor_taxa = $valor - $job->valor_delegado;
    
            $job->valor_job_clean = $valor;

            $metodos_pagamento = JobPagamento::$metodo_pagamento_array_simples;
            // dd($job->tasks);
            // if($job->tasks0) {
            //     foreach ($job->tasks as $task) {
            //     $tasks_nome[] = Task::find($task)->nome;
            //     }
            //     $job->nomes_tasks = $tasks_nome;
            // }    

            //dd($job);
            $statusarray = Job::$status_array;

            return view('pagamento.publica_job', compact(['job', 'metodos_pagamento','statusarray']));
        }
        catch (\Exception $exception) {

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Job não pode ser encontrado'));
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
             dd('<br>'.$exception->getMessage().'<br>'.$exception->getLine());
      
            //return redirect()->back()->withInput();
        }

    }

    public function setaStatusJobPagamento($id, Request $request)   {
        $id = decrypt($id); 

        try{
            //pega o array dos metodos de pagamento
            $metodo_pagamento_array = JobPagamento::$metodo_pagamento_array;
            //busca se tem pagamento do job
            $job_pagamento = JobPagamento::where('job_id', $id)->get()->first();
            //pega a data datual
            $date_new = Carbon::now();
            //pega as configurações
            $configuracoes =  Configuracao::get()->first();
            //pega o metodo de pagamento escolhido
            $metodo_pagamento = $request->get('metodo_pagamento');

            //compara o tipo do pagamento para pegar a taxa
            // echo " - metodo_pagamento " . $metodo_pagamento . " -*-  metodo_pagamento_array " . $metodo_pagamento_array['paypal']." -/";
            if($metodo_pagamento == $metodo_pagamento_array['paypal']) {
                $configuracoes_taxa_transacao = $configuracoes->taxa_paypal;
            } elseif($metodo_pagamento == $metodo_pagamento_array['tranferenciabancaria']) {
                $configuracoes_taxa_transacao = $configuracoes->taxa_transacao;
            } elseif($metodo_pagamento == $metodo_pagamento_array['pagarme']) {
                $configuracoes_taxa_transacao = $configuracoes->taxa_pagar_me;
            }

            //se o job nao tem pagamento
            if(!$job_pagamento) {
                //busca o job
                $job = Job::where('id', $id)->get()->first();
            
                $tipo_notificacao = "";
                $delegado = "";
                $freelancer = "";
            
                //se for um job de emproposta seta a rota
                if($job->status_inicial == $job->getStatus('emproposta')) {
                    $rota = route('jobs.show', encrypt($job->id));                   
                } else {
                    //se o job tem data limite seta o status como em candiddatura, senao como novo
                    if($job->data_limite) {
                        $job->status = $job->getStatus('emcandidatura');
                    }
                    else{
                        $job->status = $job->getStatus('novo');
                    }
                }
               
                //pega dados do request da blade
                $prazo_pagamento = "0";
                $status = $request->get('status');               
                $tipo_pagamento  = $request->get('tipo_pagamento');
                $chave_pagamento = $request->get('chave_de_pgto');
                $job->pg_publicador=0;
                
                //se o tipo de pagamento for pos pago (1)
                if($tipo_pagamento =="1") {
                    $prazo_pagamento = $request->get('prazo_pagamento') ?? '0';
                    $status = 'PENDING';
                    $date_new = null;
                    $chave_pagamento = "";
                    $configuracoes_taxa_transacao = "0";
                }
                 //se o tipo de pagamento for pré pago (0)
                else
                {
                    if($request->get('metodo_pagamento_pre')=="paypal") {
                        //por enquanto pegando valor de 0 pois só tem o paypal criado para pre pagamento
                        $metodo_pagamento = 0;
                        $job->pg_publicador=1;
                    }
                  
                }
                $job->save();

                //registra o pagamento do job
                $job_pagamento = JobPagamento::create([
                    'status'            => $status,
                    'pago_em'           => $date_new,
                    'chave_de_pgto'     => $chave_pagamento,
                    'valor'             => $request->get('valor'),
                    'job_id'            => $id,
                    'taxa_transacao'    => $configuracoes->taxa_transacao,
                    'user_id'           => Auth::user()->id,
                    'prazo_pagamento'   => $prazo_pagamento,
                    'metodo_pagamento'  => $metodo_pagamento,
                    'tipo_pagamento'    => $tipo_pagamento,

                ]);
                
                //notificações
                $coord = $job->coordenador_id == -1 ? null : User::where('id', $job->coordenador_id)->get()->first();

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
                    'destinatario'  => null, 
                    'rota'          => $rota,
                );

                //verifica se o job ja tem coordenador e envia notificação
                if(!is_null($coord)) {

                    # notificação ao novo coordenador 
                    $param['destinatario'] = $coord;
                    $param['tipo'] = "job_coord_novo_vc";
                    $notificacao = new UserNotification($param);
                    $coord->notify(new AlertAction($notificacao));
                }
                //dd($job);
                //vê se tipo notificaçã esta preenchido e manda notificação
                if(!empty($tipo_notificacao) && !is_null($freelancer))
                {
                    $param['destinatario'] = $freelancer;
                    $param['tipo'] = $tipo_notificacao;
                    $notificacao = new UserNotification($param);
                    $freelancer->notify(new AlertAction($notificacao));
                }
                
                
                //dd($job_pagamento);
            }
            else
            {

                //pega o pagamento
                $job_pagamento->metodo_pagamento = $request->get('metodo_pagamento');

                //busca o job do pagamento
                $job = Job::where('id', $id)->get()->first();
            
                $job->pg_publicador=0;
                if($job_pagamento == $metodo_pagamento_array['paypal'])
                {
                    $job->pg_publicador = 1;
                }
                
                $job->save();

                if($job_pagamento == $metodo_pagamento_array['tranferenciabancaria']) {
                    // Verifica se tem o comprovante
                    if($request->has('comprovante_pagamento') && $request->file('comprovante_pagamento')->isValid()){
                        # pega arquivo 
                        $doc = $request->file('comprovante_pagamento');
                        # prepara pasta e monta o caminho da pasta
                        $pasta_midias = 
                            'public' . DIRECTORY_SEPARATOR . 
                            'users'  . DIRECTORY_SEPARATOR . 
                            \Auth::user()->id . '_' . \Auth::user()->name . DIRECTORY_SEPARATOR . 
                            'pgtos';
                        # retirar acentos e espaços do nome do arquivo
                        $nome = Controller::tirarAcentos( str_replace(' ', '_', $doc->getClientOriginalName()) );
                        # salva arquivo na pasta
                        $upload = $doc->storeAs($pasta_midias, $nome);
                        # retira 'public/' do caminho do arquivo para salvar no banco de dados
                        $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);
                        // dd($upload);
                        # se fez o upload atualiza o thumb
                        if($upload){
                            $job_pagamento->comprovante_pagamento = $pasta_midias . DIRECTORY_SEPARATOR .  $nome;
                        }
                    }
                }

                $prazo_pagamento = "";
                
                // $job_pagamento->chave_de_pgto = $request->get('chave_de_pgto');
                $job_pagamento->chave_de_pgto = $job_pagamento->metodo_pagamento != $metodo_pagamento_array['tranferenciabancaria'] && $request->has('chave_de_pgto') ? $request->get('chave_de_pgto') : '';

                if($request->get('pago_em')!=null){
                    $date_new = $request->get('pago_em');
                }
                $job_pagamento->pago_em = $date_new;
                $job_pagamento->taxa_transacao = $configuracoes_taxa_transacao;
                $job_pagamento->valor = str_replace(",", ".", $job->valor_job);

                dd($job);
                $job_pagamento->save();
            }

            // echo "<hr> - ". $configuracoes_taxa_transacao;
            // //dd( $request);
            
            // dd( $job_pagamento);
            $freelancer =$job->delegado()->get()->first();

            $coord = $job->coordenador_id == -1 ? null : User::where('id', $job->coordenador_id)->get()->first();

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
                'destinatario'  => null, 
                'rota'          => $rota,
            );

            //verifica se o job ja tem coordenador e envia notificação
            if(!is_null($coord)) {

                # notificação ao novo coordenador 
                $param['destinatario'] = $coord;
                $param['tipo'] = "job_coord_pagamento_realizado";
                $notificacao = new UserNotification($param);
                $coord->notify(new AlertAction($notificacao));
            }
            //dd($job);
            //vê se tipo notificaçã esta preenchido e manda notificação
            if(!is_null($freelancer))
            {
                $param['destinatario'] = $freelancer;
                $param['tipo'] = "job_freelance_pagamento_realizado";
                $notificacao = new UserNotification($param);
                $freelancer->notify(new AlertAction($notificacao));
            }
            
            DB::commit();

            
            //dd($job_pagamento);
            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', __('messages.Dados do pagamento salvos') . '!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('jobs.show', encrypt($job->id));

        }
        catch (\Exception $exception) {

            DB::rollback();
           
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('messages.Problema para confirmar o pagamento, veja na lista de pagamento') . '!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            dd($exception);
            return redirect()->back()->withInput();

        }


    }


    public function setaStatusJobPagamentoPropostaPos($id, Request $request) {

        $id = decrypt($id); 

        try{
           
            $job = Job::where('id', $id)->get()->first();

            $job->status = Job::$EMEXECUSAO;
            $job->data_inicio = Carbon::now();
            $job->save();

            $rota = route('jobs.show', encrypt($job->id));

            # Notificações
            $freelancer =$job->delegado()->get()->first();

            $tipo_notificacao = "proposta_aceita";

            $coord = $job->coordenador_id == -1 ? null : User::where('id', $job->coordenador_id)->get()->first();

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
                'destinatario'  => null, 
                'rota'          => $rota,
            );
 
            //verifica se o job ja tem coordenador e envia notificação
            if(!is_null($coord)) {

                # notificação ao novo coordenador 
                $param['destinatario'] = $coord;
                $param['tipo'] = "job_coord_proposta_aceita";
                $notificacao = new UserNotification($param);
                $coord->notify(new AlertAction($notificacao));
            }
            //dd($job);
            //vê se tipo notificaçã esta preenchido e manda notificação
            if(!empty($tipo_notificacao) && !is_null($freelancer)) {
                $param['destinatario'] = $freelancer;
                $param['tipo'] = $tipo_notificacao;
                $notificacao = new UserNotification($param);
                $freelancer->notify(new AlertAction($notificacao));
            }

            DB::commit();
            
            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', __('messages.Proposta foi aceita') . '!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('jobs.show', encrypt($job->id));

        } 
        catch (\Exception $exception) {

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('messages.Job não pode ser encontrado'));
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            dd('<br>'.$exception->getMessage().'<br>'.$exception->getLine());
      
            //return redirect()->back()->withInput();
        }


    }


    public function confirmarPagamentoPublicadorJob(Request $request){
        
        $validator = $this->validate($request, [
            'pgto_id' => 'required' #id da movimentação financeira de user
        ]);
        
        try{

            DB::beginTransaction();

            $jps = JobPagamento::where('job_id', $request->get('job_id'))->with(['job'])->get();

            if(!$jps || empty($jps) || $jps->count()<1){
                session()->flash('message.level','error');
                session()->flash('message.content','Pagamento não encontrado.');
                session()->flash('message.erro', '');
                return redirect()->back()->withInput();
            }
            

            $jp = $jps->first();

           
            $job = $jp->job;


            $jp->status = 2; #confirmando pagamento do publicador
            $jp->confirmador_id = Auth::user()->id; #pagador
            $jp->confirmado_em = Carbon::now();
            $jp->status = "COMPLETED";

            $novo_pago_em = $request->get('novo_pago_em');
            if($novo_pago_em != $jp->pago_em )
            {
                $jp->pago_em = $novo_pago_em;
            }

            if($jp->valor == null)
            {
                //ver questão do valor está com ponto flutuante com virgula
                $jp->valor = str_replace(',', '.', $job->valor_job);
            }
           
            if($job->pg_publicador == null) {
                $job->pg_publicador = 1;
                $job->save();
            }
            
            $ufis = UserFinanceiro::where('id', $request->get('pgto_id'))->with(['job'])->get()->first();

            if($ufis || !empty($ufis) || $ufis->count()>0)
            {
                $ufis->status = 2;   
                $ufis->save();
            }


            $jp->save();
            //dd($jp);

            DB::commit();

            session()->flash('message.level','success');
            session()->flash('message.content','Pagamento confirmado!');
            session()->flash('message.erro', '');

            return redirect()->route('visualizar-conta-movimentacao');

        }catch(Exception $exception){
            // dd($exception);

            DB::rollback();

            session()->flash('message.level','error');
            session()->flash('message.content','Não foi possível confirmar o pagamento.');
            session()->flash('message.erro', $exception);
            print $exception->getLine();
            dd($exception);
            return redirect()->back()->withInput();

        }
    }

    
}
