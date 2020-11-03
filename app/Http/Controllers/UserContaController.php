<?php

namespace App\Http\Controllers;

use App\Models\UserConta;
use App\Models\Job;
use App\Models\JobPagamento;
use App\Models\UserFinanceiro;
use Illuminate\Http\Request;
use Carbon\Carbon;


class UserContaController extends Controller
{

    protected $request;
    protected $user_current;

    public function __construct(Request $request) { 
        
        $this->request = $request;
        $this->middleware('auth');
        $this->middleware('permission:recebe-pagamento|faz-pagamento');
        // $this->middleware('permission:faz-pagamento');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $usuario_auth = \Auth::user();
        return view('conta.detalhes', compact('usuario_auth'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

            \DB::beginTransaction();

            //
            $user_id = $request->get('user_id');
            $banco = $request->get('banco');
            $agencia = $request->get('agencia');
            $conta = $request->get('conta');
            $tipo_conta = $request->get('tipo_conta');
            $cpf_titular = $request->get('cpf_titular');
            $observaoes = $request->get('observaoes');

            $conta_usuario = UserConta::create([
                'user_id'         => $user_id,
                'banco'           => $banco,
                'agencia'         => $agencia,
                'conta'           => $conta,
                'tipo_conta'      => $tipo_conta,
                'cpf_titular'     => $cpf_titular,              
                'observaoes'      => $observaoes,              
            ]);
            \DB::commit();

            # status de retorno
            session()->flash('message.level',   'success');
            session()->flash('message.content', 'Conta Cadastrada com sucesso!');
            session()->flash('message.erro', '');
 

        }catch (\Exception $exception) {

            \DB::rollback();
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', 'A conta nao poder ser cadastrado.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            dd($exception);
            return redirect()->back()->withInput();
        }

        return redirect()->route('visualizar.conta.user');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserConta  $userConta
     * @return \Illuminate\Http\Response
     */
    public function show(UserConta $userConta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserConta  $userConta
     * @return \Illuminate\Http\Response
     */
    public function edit(UserConta $userConta)
    {
        //
        $usuario_auth = \Auth::user();
        return view('conta.edit', compact('usuario_auth'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserConta  $userConta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserConta $userConta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserConta  $userConta
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserConta $userConta)
    {
        //
    }

    public function storePaypal(Request $request){
        
        $validator = $this->validate($request,[
            'data_nascimento' => 'required',
            'pais_nascimento' => 'required',
            'cpf'             => 'required|unique:users,cpf,'.\Auth::user()->id,
            'conta_paypal'    => 'required|unique:users,conta_paypal,'.\Auth::user()->id,
            'logradouro'      => 'required',
            'cidade'          => 'required',
            'pais'            => 'required'
        ]);
        
        try{

            $user = \Auth::user();

            $user->data_nascimento = $request->get('data_nascimento');
            $user->pais_nascimento = $request->get('pais_nascimento');
            $user->cpf             = $request->get('cpf');
            $user->conta_paypal    = $request->get('conta_paypal');
            $user->logradouro      = $request->get('logradouro');
            $user->cidade          = $request->get('cidade');
            $user->pais            = $request->get('pais');

            $user->save();

            # status de retorno
            session()->flash('message.level',   'success');
            session()->flash('message.content', 'Conta Cadastrada com sucesso!');
            session()->flash('message.erro', '');
 

        }catch (\Exception $exception) {

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', 'A conta nao poder ser cadastrada.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            dd($exception);
            return redirect()->back()->withInput();
        }

        return redirect()->route('visualizar.conta.user');
        
    }
    
    public function movimentacao()
    {
        $usuario_auth = \Auth::user();

        $dado_pag = [
            'lista_recebimentos' => [],
            'lista_pagamentos'  => [],
            'recebidos'  => 0,
            'receber'  => 0,
            'execucao'  => 0,
            'total_recebimento'  => 0,
            'pagos'  => 0,
            'pagar'  => 0,
            'pagamento_execucao'  => 0,
            'total_pagamento'  => 0,
        ];

        $valor_taxado = 0;
        $role = $usuario_auth->roles()->first();
        $roleUsuarioCon = $role->name;
        $statusarray = Job::$status_array;
        
        // Arrumar para permissão
        // if($usuario_auth->roles()->first()->name == "freelancer"){
            // dd($usuario_auth->hasPermission('faz-pagamento'));
        if($usuario_auth->hasPermission('recebe-pagamento')){
            // $conta = UserConta::where('user_id', $usuario_auth->id)->get();  
            
            if($roleUsuarioCon  ==  "freelancer") {
                $dado_pag['lista_recebimentos'] = UserFinanceiro::where('para_id', $usuario_auth->id)->with(['de','para','pagador', 'job'])->get();
                $job_valor_execucao = Job::where('delegado_para', $usuario_auth->id)->whereIn('status', [2,3,4,7,8])->get();
                $permissao = "freelancer";

            }elseif($roleUsuarioCon  ==  "admin"   ||  $roleUsuarioCon   ==    "desenvolvedor"){
                $dado_pag['lista_recebimentos'] = UserFinanceiro::with(['de','para','pagador', 'job'])->get();
                $job_valor_execucao = Job::whereIn('status', [2,3,4,7,8])->get();
                $permissao = 'admin'; 
            }
            $dado_pag['recebidos']          = $dado_pag['lista_recebimentos']->where('status', 2)->sum('valor_para');
            $dado_pag['receber']            = $dado_pag['lista_recebimentos']->where('status', 1)->sum('valor_para');
            
            // $EMEXECUSAO   = 2; $EMREVISAO    = 3; $EMAVALIACAO  = 4; $REABERTO     = 7; $PARADO       = 8;

            foreach ($job_valor_execucao as $index => $value) {

                if($value->valor_job>0) {
                   
                    if($value->taxa > 0) {
                        $valor_taxado += floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
                    }else {
                        $valor_taxado +=  floatval($value->valor_job);
                    }
                }
            }
            $dado_pag['execucao'] =  $valor_taxado;

            $dado_pag['total_recebimento'] = $dado_pag['execucao'] + $dado_pag['recebidos'] + $dado_pag['receber'] ;

        }
        if($usuario_auth->hasPermission('faz-pagamento')){
            
            $permissao = "outros";
            if($roleUsuarioCon  ==  "publicador") {
                $dado_pag['lista_pagamentos'] = UserFinanceiro::where('de_id', $usuario_auth->id)->with(['de','para','pagador', 'job'])->get();
                // dd($dado_pag['lista_pagamentos']);

                $job_valor_execucao = Job::where('user_id', $usuario_auth->id)->whereIn('status', [2,3,4,7,8])->get();
                $permissao = $roleUsuarioCon; 
            }
            elseif($roleUsuarioCon  ==  "admin"   ||  $roleUsuarioCon   ==    "desenvolvedor"){
                $dado_pag['lista_pagamentos'] = UserFinanceiro::with(['de','para','pagador', 'job','jobPagamento'])->get();
                $job_valor_execucao = Job::whereIn('status', [2,3,4,7,8])->get();
                $permissao = 'admin'; 
                #?
            }
            // Pagamentos liberados e para liberar

            // Aqui está errado
            $dado_pag['pagos'] = $dado_pag['lista_pagamentos']->where('status', 2)->sum('valor_de');
            $dado_pag['pagar'] = $dado_pag['lista_pagamentos']->where('status', 1)->sum('valor_de');

            // $conta = UserConta::where('user_id', $usuario_auth->id)->get();
            
                // dd($dado_pag['pagar']);


            foreach ($job_valor_execucao as $index => $value) {

                if($value->valor_job) {
                
                    // if($value->taxa > 0) {
                    //     $valor_taxado += floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
                    // }else {
                        $valor_taxado +=  floatval($value->valor_job);
                    // }
                }
            }

            $dado_pag['pagamento_execucao'] =  $valor_taxado;

            $dado_pag['total_pagamento'] = $dado_pag['pagos'] + $dado_pag['pagar'] + $dado_pag['pagamento_execucao'] ;

            // Pagamentos de Jobs
            $dado_pag['lista_pagamentos_jobs'] = $usuario_auth->jobsPagamentosConcluidos()->get();            

        }

        
        //jobs_aguardando_pagamento
        $jobs   = "";
        $titulo = "";
        $oculta_imagem = "";
        $jobs_pos = null;
        if($roleUsuarioCon == "desenvolvedor" || $roleUsuarioCon == "admin" || $roleUsuarioCon == "publicador") {
            if($roleUsuarioCon == "desenvolvedor" || $roleUsuarioCon == "admin") {
                $jobs = Job::with('coordenador')->with('delegado')->where('status', Job::$status_array['pagamentopendente'] )->get();
                
            }else if($roleUsuarioCon == "publicador") {
                $jobs   = \Auth::user()->jobsPagamentosPendentes()
                            ->with(['delegado', 'coordenador'])
                            ->get();

                // $jobs_pos = Auth::user()->jobsPagamentosPos(\Auth::user()->id)
                //     ->with(['delegado', 'coordenador'])
                //     ->get();

            }
            foreach ($jobs as $value) {
                // $value->valor_desconto = floatval($value->valor_job) - floatval($value->valor_job)*floatval($value->taxa)/100;
                $value->valor_desconto = $value->valorDoJob($roleUsuarioCon);

                //processo para mudar cor das linha da tabela
                $value->pagamento_pendente = false;
                $value->class_formatacao_fundo = '';
                $value->class_formatacao_texto = '';
                $data_prazo = Carbon::now();
                $data_atual = Carbon::now();
                
                if($value->status == $statusarray['concluido'] ) {

                    $job_pag_pos = $value->jobsPagamentosPos;
                    if($job_pag_pos->count()>0) {
                        if($job_pag_pos[0]->prazo_pagamento!=null ) {
                            $data_prazo = $value->data_entrega->addDays($job_pag_pos[0]->prazo_pagamento);
                        }
                    }
                    if($data_atual <= $data_prazo){
                        $value->class_formatacao_fundo = 'warning';
                        $value->class_formatacao_texto = 'texto-preto';
                        $value->pagamento_pendente = true;

                    }elseif($data_atual > $data_prazo){
                        $value->class_formatacao_fundo = 'danger';
                        $value->class_formatacao_texto = 'texto-white';
                        $value->pagamento_pendente = true;
                    }

                    if($value->pagamentoEfetivado!= null){
                        $value->class_formatacao_fundo = '';
                        $value->class_formatacao_texto = '';
                        $value->pagamento_pendente = false;
                    }

                }

            }
            
            $titulo      = 'Aguardando Pagamentos';
            $oculta_imagem = true;
        }
        // dd($dado_pag['lista_pagamentos'][0]->dados_bancarios);
        return view('conta.lista', compact(['permissao','dado_pag','jobs','jobs_pos','role','statusarray','oculta_imagem','titulo']));
    }

}
