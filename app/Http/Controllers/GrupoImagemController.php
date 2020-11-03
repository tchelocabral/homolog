<?php

namespace App\Http\Controllers;

use App\Models\GrupoImagem;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;

class GrupoImagemController extends Controller
{
    
    protected $request;
    protected $grupo_imagem;

    public function __construct(Request $request, GrupoImagem $grupo_imagem){
        $this->request = $request;
        $this->grupo_imagem = $grupo_imagem;
        $this->middleware('auth');
        $this->middleware('permission:lista-tipo-imagem');
        $this->middleware('permission:cria-tipo-imagem', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-tipo-imagem', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-tipo-imagem', ['only' => ['destroy']]);
    }


    public function index() {
        
        $grupo_imagens = GrupoImagem::all();
    
        return view('imagens.grupos.lista', compact('grupo_imagens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
         return view('imagens.grupos.novo');
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
                'nome'        => 'required',
                'descricao'   => 'required',
                'observacoes' => 'required'
            ]);

            \DB::beginTransaction();

            $grupo_imagem = GrupoImagem::create([
                'nome'        => $request->get('nome'),
                'descricao'   => $request->get('descricao'),
                'observacoes' => $request->get('observacoes'),
            ]);

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Grupo de Imagem cadastrado com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('grupo-imagem.show', encrypt($grupo_imagem->id));

        }catch (\Exception $exception){

            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O grupo de imagem pôde ser cadastrado!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('grupo-imagem.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GrupoImagem  $grupoImagem
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
    
         $grupo_imagem = GrupoImagem::where('id', decrypt($id))->first();
     
        if($this->request->wantsJson()) {
            return $grupo_imagem;
        }else {
            return view('imagens.grupos.detalhes', compact('grupo_imagem'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GrupoImagem  $grupoImagem
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
    
        $grupo_imagem= GrupoImagem::find(decrypt($id));
        return view('imagens.grupos.edit', compact('grupo_imagem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GrupoImagem  $grupoImagem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
        try{
            $grupo_imagem = GrupoImagem::find(decrypt($id));
            $grupo_imagem->fill($request->all());

            $grupo_imagem->save();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' atualizado com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('grupo-imagem.index');

        }catch (\Exception $exception){
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O Grupo de Imagem não pôde ser atualizado!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GrupoImagem  $grupoImagem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
        try{
            $grupo_imagem = GrupoImagem::findOrFail(decrypt($id));
            $grupo_imagem->delete();

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content', 'Grupo de Imagem excluído com sucesso!');
            Session::flash('message.erro', '');

            return redirect()->route('grupo-imagem.index');

        } catch (\Exception $exception){
            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'O grupo de imagem não pôde ser excluído!');
            Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->route('grupo-imagem.index');
        }
    }
}
