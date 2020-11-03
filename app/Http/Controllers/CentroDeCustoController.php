<?php

namespace App\Http\Controllers;

use App\Models\CentroDeCusto;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;

class CentroDeCustoController extends Controller
{
    
    protected $request;
    protected $custo;

    public function __construct(Request $request, CentroDeCusto $custo){
        $this->request = $request;
        $this->custo = $custo;
        $this->middleware('auth');
        $this->middleware('permission:lista-financeiro');
        $this->middleware('permission:cria-financeiro', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-financeiro', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-financeiro', ['only' => ['destroy']]);
    }


    public function index() {
        
        $custos = CentroDeCusto::all();
    
        return view('ccs.lista', compact('custos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('ccs.novo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        try{
            // validate
            $validator = $this->validate($request, [
                'nome' => 'required',
            ]);

            \DB::beginTransaction();

            $custo = CentroDeCusto::create([
                'nome' => $request->get('nome'),
                'descricao' => $request->get('descricao'),
            ]);

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Centro de Custo cadastrado com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('centro-custo.show', encrypt($custo->id));

        }catch (\Exception $exception){

            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O custo não pôde ser cadastrado!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('centro-custo.index');

    } // end class

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CentroDeCusto  $centroDeCusto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        
        $custo = CentroDeCusto::where('id', decrypt($id))->first();
     
        if($this->request->wantsJson()) {
            return $custo;
        }else {
            return view('ccs.detalhes', compact('custo'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CentroDeCusto  $centroDeCusto
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
       
         $custo = CentroDeCusto::find(decrypt($id));
         return view('ccs.edit', compact('custo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CentroDeCusto  $centroDeCusto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        try{
            $custo = CentroDeCusto::find(decrypt($id));
            $custo->fill($request->all());

            $custo->save();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' atualizado com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('centro-custo.index');

        }catch (\Exception $exception){
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O Centro de Custo não pôde ser atualizado!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CentroDeCusto  $centroDeCusto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
        try{
            $custo = CentroDeCusto::findOrFail(decrypt($id));
            $custo->delete();

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content', 'Centro de Custo excluído com sucesso!');
            Session::flash('message.erro', '');

            return redirect()->route('centro-custo.index');

        } catch (\Exception $exception){
            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'O centro de custo não pôde ser excluído!');
            Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->route('centro-custo.index');
        }
    }
}
