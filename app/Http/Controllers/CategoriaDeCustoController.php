<?php

namespace App\Http\Controllers;

use App\Models\CategoriaDeCusto;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;

class CategoriaDeCustoController extends Controller
{

    protected $request;
    protected $categoria;
    
    public function __construct(Request $request, CategoriaDeCusto $categoria){
        $this->request = $request;
        $this->custo = $categoria;
        $this->middleware('auth');
        $this->middleware('permission:lista-financeiro');
        $this->middleware('permission:cria-financeiro', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-financeiro', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-financeiro', ['only' => ['destroy']]);
    }

    public function index()
    {
         $categorias = CategoriaDeCusto::all();
    
         return view('categoria.lista', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categoria.novo');
        
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
            // validate
            $validator = $this->validate($request, [
                'nome' => 'required',
            ]);

            \DB::beginTransaction();

            $categoria = CategoriaDeCusto::create([
                'nome' => $request->get('nome'),
                'descricao' => $request->get('descricao'),
            ]);

            \DB::commit();
encrypt(
            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Categoria de Custo cadastrada com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('categoria-custo.show', encrypt($categoria->id));

        }catch (\Exception $exception){

            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A categoria de custo não pôde ser cadastrada!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('categoria-custo.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoriaDeCusto  $categoriaDeCusto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $categoria = CategoriaDeCusto::where('id', decrypt($id))->first();
     
        if($this->request->wantsJson()) {
            return $categoria;
        }else {
            return view('categoria.detalhes', compact('categoria'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoriaDeCusto  $categoriaDeCusto
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $categoria = CategoriaDeCusto::find(decrypt($id));
         return view('categoria.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoriaDeCusto  $categoriaDeCusto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{

            $categoria = CategoriaDeCusto::find(decrypt($id));
            $categoria->fill($request->all());

            $categoria->save();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' atualizado com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('categoria-custo.index');

        }catch (\Exception $exception){

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A Categoria de Custo não pôde ser atualizada!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoriaDeCusto  $categoriaDeCusto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $categoria = CategoriaDeCusto::findOrFail(decrypt($id));
            $categoria->delete();

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content', 'Categoria de Custo excluída com sucesso!');
            Session::flash('message.erro', '');

            return redirect()->route('categoria-custo.index');

        } catch (\Exception $exception){
            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'A categoria de custo não pôde ser excluída!');
            Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->route('categoria-custo.index');
        }
    }
}
