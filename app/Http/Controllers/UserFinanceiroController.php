<?php

namespace App\Http\Controllers;

use App\Models\UserFinanceiro;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserFinanceiroController extends Controller
{
    
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // salva novo model UserFinanceiro quando o job for concluído
        // user->conta()

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserFinanceiro  $userFinanceiro
     * @return \Illuminate\Http\Response
     */
    public function show(UserFinanceiro $userFinanceiro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserFinanceiro  $userFinanceiro
     * @return \Illuminate\Http\Response
     */
    public function edit(UserFinanceiro $userFinanceiro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserFinanceiro  $userFinanceiro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserFinanceiro $userFinanceiro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserFinanceiro  $userFinanceiro
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFinanceiro $userFinanceiro)
    {
        //
    }



    public function confirmaPagamentoFreelancer(Request $request)
    {
       

        $validator = $this->validate($request, [
            'pgto_id' => 'required' #id da movimentação financeira de user
        ]);
        
        try{

            //  dd($request);

            DB::beginTransaction();

            $ufis = UserFinanceiro::where('id', $request->get('pgto_id'))->with(['job'])->get();

            if(!$ufis || empty($ufis) || $ufis->count()<1){
                session()->flash('message.level','error');
                session()->flash('message.content','Pagamento não encontrado.');
                session()->flash('message.erro', '');
                return redirect()->back()->withInput();
            }
            
            $ufi = $ufis->first();

            $ufi->status = 3; #confirmando liberação do pagamento
            $ufi->pagador_id = Auth::user()->id; #pagador
            $ufi->pago_em = $request->get('pago_em');;

           
            
              // Verifica informações complementares
            if($request->has('doc_pgto') && $request->file('doc_pgto')->isValid()){
                # pega arquivo 
                $doc = $request->file('doc_pgto');
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
                    $ufi->doc_url = $pasta_midias . DIRECTORY_SEPARATOR .  $nome;
                }
            }
            
            if($request->has('observacoes')){
                $ufi->observacoes = $request->get('observacoes');
            }

            if($request->has('centro_de_custo_id')){
                $ufi->centro_de_custo_id = $request->get('centro_de_custo_id');
            }

            if($request->has('categoria_de_custo_id')){
                $ufi->categoria_de_custo_id = $request->get('categoria_de_custo_id');
            }

           
            $job = $ufi->job;
            $job->pg_freelancer = 1;
            $job->save();

            $ufi->save();
            // dd($ufi);
            DB::commit();

            session()->flash('message.level','success');
            session()->flash('message.content','Liberação para o Freelancer confirmado!');
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
