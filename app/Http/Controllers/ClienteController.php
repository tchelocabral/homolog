<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contato;
use App\Models\Endereco;
use App\Models\Projeto;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class ClienteController extends Controller {

    private $cliente;
    private $request;

    public function __construct(Request $request, Cliente $cliente) {
        $this->cliente = $cliente;
        $this->request = $request;
        $this->middleware('auth');
        $this->middleware('permission:lista-cliente');
        $this->middleware('permission:cria-cliente', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-cliente', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-cliente', ['only' => ['destroy']]);
    }

    public function index() {

        $clientes = Cliente::all();
        return view('cliente.lista', compact('clientes'));
    }

    public function create() {

        return view('cliente.novo');
    }

    public function store(Request $request) {
        
        // validate
        $validator = $this->validate($request, [
            'nome_fantasia' => 'required|unique:clientes',
        ]);

        try{
            # caminho das pastas de arquivos
            $pasta_midias_clientes = 'public' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'clientes';  
            $timestamp    = \Carbon\Carbon::now()->timestamp;
            $upload       = false;
            $nome         = false;
            if( $request->hasFile('logo') && $request->file('logo')->isValid() ){
                $logo   = $request->file('logo');
                $nome   = str_replace(" ", "_", 'logo_' . $request->get('nome-fantasia') . $timestamp . '.' . $logo->getClientOriginalExtension());
                $upload = $logo->storeAs($pasta_midias_clientes, $nome);
                # retira 'public/' do caminho do arquivo para salvar no banco de dados
                $pasta_midias_clientes = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias_clientes);
            }

            \DB::beginTransaction();

            $cliente = Cliente::create([
                'nome_fantasia' => $request->get('nome_fantasia'),
                'cnpj' => $request->get('cnpj'),
                'logo' => $upload ? $pasta_midias_clientes . DIRECTORY_SEPARATOR . $nome : null,
            ]);

            $endereco = Endereco::create([
                'dono_id' => $cliente->id,
                'dono_tipo' => 'clientes', #nome da tabela
                'cep' => $request->get('cep'),
                'logradouro' => $request->get('logradouro'),
                'bairro' => $request->get('bairro'),
                'cidade' => $request->get('cidade'),
                'uf' => $request->get('uf'),
                'numero' => $request->get('numero'),
                'complemento' => $request->get('complemento'),
                'pais' => 'Brasil',
                'lat' => null,
                'long' => null
            ]);

            $contato = Contato::create([
                'dono_id' => $cliente->id,
                'dono_tipo' => 'clientes', #nome da tabela
                'nome' => $request->get('nome_contato'),
                'email' => $request->get('email_contato'),
                'tel' => $request->get('tel_contato'),
                'cel' => $request->get('cel_contato')
            ]);

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['razao_social'] . ' cadastrado com sucesso!');
            $request->session()->flash('message.erro', '');

        }catch (\Exception $exception){

            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O Cliente não pôde ser cadastrado.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->route('clientes.index');
    }

    public function show($id) {
        $id = decrypt($id);
        $cliente  = Cliente::with(['faturamentos.projetos', 'projetos'])->get()->find($id);

        // dd($cliente);

        return view('cliente.detalhes', compact('cliente'));
    }

    public function edit($id) {
        $id = decrypt($id);
        $cliente = Cliente::with(['projetos'])->find($id)->first();

        // dd($cliente);

        return view('cliente.edit', compact('cliente'));
    }

    public function update(Request $request, $id) {
        //
        // dd($request);

        $id = decrypt($id);
        // validate
        $validator = $this->validate($request, [
            'nome_fantasia' => 'required|unique:clientes,nome_fantasia,'.$id,
        ]);

        try{

            $cliente = Cliente::find($id);
            //dd($cliente);
            $cliente->nome_fantasia   = $request['nome_fantasia'];
            $cliente->cnpj            = $request['cnpj'];

            # caminho das pastas de arquivos
            $pasta_midias_clientes = 'public' . DIRECTORY_SEPARATOR . 'clientes'; 

            if( $request->hasFile('logo') && $request->file('logo')->isValid() ){

                $upload       = false;
                $nome         = false;
                $timestamp    = \Carbon\Carbon::now()->timestamp;
                $logo   = $request->file('logo');
                $nome   = str_replace(" ", "_", 'logo_' . $request->get('nome_fantasia') . $timestamp . '.' . $logo->getClientOriginalExtension());
                $upload = $logo->storeAs($pasta_midias_clientes, $nome);
                # retira 'public/' do caminho do arquivo para salvar no banco de dados
                $pasta_midias_clientes = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias_clientes);

                 $cliente->logo = $upload ? $pasta_midias_clientes . DIRECTORY_SEPARATOR . $nome : null;

            }

            $cliente->save();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['razao_social'] . 'Dados atualizados!');
            $request->session()->flash('message.erro', '');

        }catch (\Exception $exception){

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'Não foi possível atualizar os dados!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->back();

        return $cliente;
    }

    public function destroy($id) {
        //
        $id = decrypt($id);
        $cliente = Cliente::find($id);
        //dd($cliente);
        try{
            $totalProj = count($cliente->projetos()->get());
            if($totalProj >0)
            {
                # status de retorno
                \Session::flash('message.level', 'erro');
                \Session::flash('message.content', 'Cliente possui projetos cadastrados e não pode ser deletado!');
                \Session::flash('message.erro', '');
            }
            else
            {
                \DB::beginTransaction();

                $cliente->delete();

                \DB::commit();

                # status de retorno
                \Session::flash('message.level', 'success');
                \Session::flash('message.content', 'Cliente excluído com sucesso!');
                \Session::flash('message.erro', '');
            }

        } catch (\Exception $exception){

            //dd($exception);

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', 'O cliente não pôde ser excluído.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
        }
        return redirect()->route('clientes.index');
        
    }

    /* Retorna os projetos do cliente em json */
    public function listarProjetos(Request $request) {

        if($request->ajax()){
            return Cliente::find($request->get('id'))->projetos;
        }else {
            return false;
        }
        
}













    /**
     * Cria 5 registros fakes para teste do banco.
     *
     * @return dd($clientes);
     */
    public function factory()
    {
        // Factory Test
        $clientes = factory(\App\Models\Cliente::class, 3)->create();
        dd($clientes);
    }

}
