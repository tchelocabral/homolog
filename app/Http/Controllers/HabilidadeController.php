<?php

namespace App\Http\Controllers;

use App\Models\Habilidade;
use Illuminate\Http\Request;
use Session;


class HabilidadeController extends Controller
{
    private $habilidade;
    private $request;

     public function __construct(Request $request, Habilidade $habilidade) {
        $this->habilidade = $habilidade;
        $this->request = $request;
        $this->middleware('auth');
        $this->middleware('permission:gerencia-politicas');
      
    }

    public function index() {

        $habilidades = Habilidade::all();
        return view('habilidades.lista', compact('habilidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        return view('habilidades.novo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        // validate
        $validator = $this->validate($request, [
            'nome' => 'required|unique:habilidades',
            'cor'  => 'required'
        ]);
        
         try{

            $habilidade = Habilidade::create([
                'nome'        => $request->get('nome'),
                'descricao'   => $request->get('descricao'),
                'teste'       => $request->get('teste'),
                'cor'         => $request->get('cor')
            ]);

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' cadastrado com sucesso!');
            $request->session()->flash('message.erro', '');

        }catch (\Exception $exception) {

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A habilidade não pôde ser cadastrada.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('habilidades.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $id = decrypt($id);
        $habilidade = Habilidade::find(decrypt($id));
        return view('habilidades.detalhes', compact('habilidade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $id = decrypt($id);
        $habilidade = Habilidade::find(decrypt($id));
        return view('habilidades.edit', compact('habilidade'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $id = decrypt($id);
        $validator = $this->validate($request, [
            'nome' => 'required',
            'cor'  => 'required'
        ]);
      
        try{
            
            $habilidade = Habilidade::find(decrypt($id));
            $habilidade->fill($request->all());
            $habilidade->save();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' atualizado com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('habilidades.index');

        }catch (\Exception $exception){
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A habilidade não pôde ser atualizada!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $id = decrypt($id);
        try{

            $habilidade = Habilidade::findOrFail(decrypt($id));
            $habilidade->delete();

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content', 'Habilidade excluída com sucesso!');
            Session::flash('message.erro', '');

            return redirect()->route('habilidades.index');

        } catch (\Exception $exception){
            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'A habilidade não pôde ser excluída!');
            Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->route('habilidades.index');
        }
    }
}
