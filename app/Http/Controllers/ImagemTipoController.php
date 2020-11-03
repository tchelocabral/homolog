<?php

namespace App\Http\Controllers;

use App\Models\GrupoImagem;
use App\Models\ImagemTipo;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ImagemTipoController extends Controller
{

    protected $request;
    protected $tipo_imagem;

    public function __construct(Request $request, ImagemTipo $tipo_imagem){
        $this->request = $request;
        $this->tipo_imagem = $tipo_imagem;
        $this->middleware('auth');
        $this->middleware('permission:lista-tipo-imagem');
        $this->middleware('permission:cria-tipo-imagem', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-tipo-imagem', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-tipo-imagem', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tipos_imagens = ImagemTipo::all();
        if($this->request->wantsJson()){
            return $tipos_imagens;
        }else{
            return view('imagens.tipos.lista', compact('tipos_imagens'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $tipo_imagem = new ImagemTipo();
        $grupos_imgs = GrupoImagem::all();
        return view('imagens.tipos.novo', compact('tipo_imagem'), compact('grupos_imgs'));
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
                'grupo_id' => 'required'
            ]);

            $tipo_imagem = ImagemTipo::create([
                'nome'        => $request->get('nome'),
                'descricao'   => $request->get('descricao'),
                'grupo_id'    => $request->get('grupo_id'),
                'valor'       => $request->get('valor'),
                'campos_personalizados' => $request['campos_personalizados']
         
            ]);

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' cadastrado com sucesso!');
            $request->session()->flash('message.erro', '');

        }catch (\Exception $exception){

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O Tipo de Imagem não pôde ser cadastrado.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('tiposimagens.create');

    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
    
        $id = decrypt($id);

        $tipo_imagem = ImagemTipo::where('id', $id)->with(['grupo', 'imagens'])->first();
        $grupos_imgs = GrupoImagem::all();

        //dd($tipo_imagem);
        $tipo_imagem->podeApagar = ($tipo_imagem->imagens && count($tipo_imagem->imagens) > 0) ? false : true;
        
        $tipo_imagem->lista_tipos_troca =null;
        if(!$tipo_imagem->podeApagar)
        {
            $tipo_imagem->lista_tipos_troca = ImagemTipo::where('id','!=', $id)->get();
        }


     
        if($this->request->wantsJson()) {
            return $tipo_imagem;
        }else {
            return view('imagens.tipos.detalhes', compact('tipo_imagem'), compact('grupos_imgs'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImagemTipo  $imagem_tipo
     * @return \Illuminate\Http\Response
     */
    public function edit(ImagemTipo $imagem_tipo, $id) {

        $id = decrypt($id);

        $tipo_imagem = ImagemTipo::where('id', $id)->with('grupo')->first();
        $grupos_imgs = GrupoImagem::all();

        if($this->request->wantsJson()) {
            return $tipo_imagem;
        }else {
            return view('imagens.tipos.edit', compact('tipo_imagem', 'grupos_imgs'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImagemTipo  $imagem_tipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImagemTipo $imagem_tipo, $id) {
        $id = decrypt($id);

        try{

            $tipo_imagem = ImagemTipo::find($id);
            $tipo_imagem->fill($request->all());
            $tipo_imagem->save();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' atualizado com sucesso!');
            $request->session()->flash('message.erro', '');

            if($this->request->wantsJson()){
                return $tipo_imagem;
            }else {
                return redirect()->route('tiposimagens.index');
            }

        }catch (\Exception $exception){
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O tipo de imagem não pôde ser atualizada.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $id = decrypt($id);

        try{
            $tipo_imagem = ImagemTipo::findOrFail($id);
            $tipo_imagem->delete();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Tipo de Imagem excluído com sucesso!');
            $request->session()->flash('message.erro', '');

            if($this->request->wantsJson()){
                return $tipo_imagem;
            }else {
                return redirect()->route('tiposimagens.index');
            }

        }catch (\Exception $exception){
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O tipo de imagem não pôde ser excluído.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    public function transferirImagens($id, Request $request) {
        $id = decrypt($id);

        try {

            $tipo_atual = ImagemTipo::where('id', $id)->first();
            $tipo_novo = ImagemTipo::where('id', $request->tipos_troca)->first();

            //echo ($tipo_atual->id . " - " .$tipo_novo->id);
            
           
            // Se os usuários não existem ou são iguais
            if(!$tipo_atual || !$tipo_novo || $tipo_atual->id ==  $tipo_novo->id){
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('messages.Dados dos tipos não estão correto!') . '.');
                session()->flash('message.erro', '');
                return redirect()->back()->withInput();
            }
           
            if($tipo_atual->imagens && count($tipo_atual->imagens) > 0) {
                //mudar jobs delegado
                foreach ($tipo_atual->imagens as $key => $imagem) {
                    $imagem->imagem_tipo_id = $tipo_novo->id;
                    $imagem->save();
                    # code...
                }
            }              

       
            //excluir o usuário antigo
            // dd($tipo_atual);
            \DB::beginTransaction();
                $tipo_atual->delete();
            \DB::commit();

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('session.Os dados foram transferidos e o tipo foi deletado.'));
            session()->flash('message.erro', '');

            return redirect()->route('tiposimagens.index');


        } catch (\Exception $e) {
            
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.Os dados não foram transferidos ou a tipo não foi deletado.'));
            session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());
            //dd($e->getMessage().'<br>'.$e->getLine());
            return redirect()->route('tiposimagens.index');
            //return redirect()->back()->withInput();

        }

    
    }



}
