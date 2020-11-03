<?php

namespace App\Http\Controllers;

use App\Models\ClienteFaturamento;
use App\Models\Projeto;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Mockery\Exception;
use Session;

class ClienteFaturamentoController extends Controller
{


    private $faturamento;
    private $request;


    public function __construct(Request $request, ClienteFaturamento $faturamento) {
        $this->faturamento = $faturamento;
        $this->request     = $request;
        $this->middleware('auth');

        // permissoes
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
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

        try{

            \DB::beginTransaction();

            // Se estiver faturamento selecionado

            $faturamento = ClienteFaturamento::create([
                'cliente_id' => $request->get('cliente_id'),
                'razao_social' => $request->get("razao_social"),
                'nome_fantasia' => $request->get("nome_fantasia"),
                'cnpj' => $request->get("cnpj"),
                'apelido' => $request->get("apelido"),
                'nome_contato' => $request->get("nome_contato"),
                'email_contato' => $request->get("email_contato")
            ]);
            $id_faturamento = $faturamento->id;
     


            \DB::commit();

            # status de retorno
            session()->flash('message.level', 'success');
            session()->flash('message.content', 'Faturamento cadastrado com sucesso!');
            session()->flash('message.erro', '');

            return redirect()->route('clientes.show', encrypt($request->get('cliente_id')));
            

        }catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', 'O faturamento não pôde ser cadastrado.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClienteFaturamento  $clienteFaturamento
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        
        if($request->ajax()){
          
            return ClienteFaturamento::find($request->get('id'));

        } else {
            return true;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClienteFaturamento  $clienteFaturamento
     * @return \Illuminate\Http\Response
     */
    public function edit(ClienteFaturamento $clienteFaturamento) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClienteFaturamento  $clienteFaturamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClienteFaturamento $clienteFaturamento) {
        //
        // validate
        $validator = $this->validate($request, [
            'faturamento_id' => 'required'
        ]);

        try {
            
            $fat = ClienteFaturamento::find($request->get('faturamento_id'));
            if($fat)
            {
                $fat->fill($request->all());
                $fat->save();


                session()->flash('message.level', 'success');
                session()->flash('message.content', 'Faturamento atualizado com sucesso!');
                session()->flash('message.erro', '');

                return redirect()->route('clientes.show', encrypt($request->get('cliente_id')));
            }

            else {
                 session()->flash('message.level','erro');
                 session()->flash('message.content','O faturamento não foi encontrado para atualização.');
                 session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
            }


        } catch (Exception $e) {

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', 'O faturamento não pôde ser atualizado.');
            session()->flash('message.erro','<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClienteFaturamento  $clienteFaturamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        //
       try{
            $clinFat = ClienteFaturamento::where('id',$request->get('fat_id'))->with('projetos')->get()->first();;


            if(count($clinFat->projetos)>0) {

                Session::flash('message.level', 'erro');
                Session::flash('message.content', 'Faturamento vinculado a um projeto não pode ser excluído!');
                Session::flash('message.erro', '');
            }
            else
            {

                $clinFat->delete();

                Session::flash('message.level', 'success');
                Session::flash('message.content', 'Faturamento Excluido excluída com sucesso!');
                Session::flash('message.erro', '');

            }
            return redirect()->back();


        } catch (\Exception $exception){
            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'Faturamento não pôde ser excluído.');
            Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            //return redirect()->route('tasks.index');
        }
    }

    public function vincularProjeto(Request $request) {
        try {

            $id_faturamento = false;
            if($request->has('faturamento_id') && $request->get('faturamento_id') != -1){
                $id_faturamento = $request->get('faturamento_id');
            }

            $projeto = null; 
            if($request->has('projeto_id') && $request->get('projeto_id') != -1){
                $projeto = Projeto::find($request->get('projeto_id'));
                $projeto->faturamentos()->attach($id_faturamento);
            }

            \DB::commit(); 

            session()->flash('message.level', 'success');
            session()->flash('message.content', 'Faturamento vinculado com sucesso!');
            session()->flash('message.erro', '');

            return redirect()->route('projetos.show', encrypt($request->get('projeto_id')));

        }
        catch (\Exception $exception) {

            \DB::rollBack();

            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', 'O faturamento não pôde ser vinculado.');
            session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }
}
