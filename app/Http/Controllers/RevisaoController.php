<?php

namespace App\Http\Controllers;

use App\Models\Revisao;
use Illuminate\Http\Request;
use App\Models\RevisaoMarcador;
use App\Models\RevisaoMarcadorMidia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RevisaoController extends Controller
{


    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
        $this->middleware('auth');
        $this->middleware('permission:lista-revisao');
        $this->middleware('permission:cria-revisao', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-revisao', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-revisao', ['only' => ['destroy']]);
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
     * Traz o Form para Nova revisão, renderizando
     * se o pedido for por json
     *
     * @return \Illuminate\Http\Response
     */
    public function create($imagem_id) {

        $imagem_id = decrypt($imagem_id);
        if($this->request->wantsJson()) {
            $viewHTML = view('revisoes.novo', compact('imagem_id'))->render();
            return \Response::json(array('success' => true, 'view' => $viewHTML));
        }else{
            return view('revisoes.novo', compact('imagem_id'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
        $this->validate($request, [
            'imagem_id'=> 'required',
            'pins'=> 'required',
            'img_revisao_base' => 'required'
        ]);

        try {

            \DB::beginTransaction();

            $fileRevisao = $request->file('input-arquivo');

            $img_revisao_base  = $request->allFiles()['img_revisao_base'];


            //cria nome Revisão
            $nomeRevisao = "RI01";

            $revisaoLast  = Revisao::where('imagem_id', $request->get('imagem_id'))->orderBy('nome')->get()->last(); 

            if($revisaoLast){

                //dividir nome revisão para acrescentar um.
                $tempId = explode('I', $revisaoLast->nome);
                // dd($tempId);
                if($tempId[1]<10) {

                    $tempNewId = intval($tempId[1]);
                    $nomeRevisao = 'RI0'. ($tempNewId+1);
                }else{
                    $nomeRevisao = 'RI'.$tempId[1]+1;
                }

            }

             # monta o caminho da pasta
            $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'revisoes' . DIRECTORY_SEPARATOR . $request->get('imagem_id').'_'.$nomeRevisao;
         


            # retirar acentos e espaços do nome do arquivo
            $nome = Controller::tirarAcentos( str_replace(' ', '_', $img_revisao_base->getClientOriginalName()) );
            # salva arquivo na pasta
            $upload = $img_revisao_base->storeAs($pasta_midias, $nome);
            # retira 'public/' do caminho do arquivo para salvar no banco de dados
            $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);

            
            

            // dd($revisaoLast);

            if($upload){

                $revisao = Revisao::create([
                    'imagem_id'         => $request->get('imagem_id'),
                    'avaliador_id'      => $request->get('avaliador_id'),
                    'numero_revisao'    => $request->get('numero_revisao'),
                    'nome'              => $nomeRevisao,
                    'src'               => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                    'observacoes'       => $request->get('observacoes'),
                    'status'            => 0,
                ]);


                foreach ($request->get('pins') as $value) {
                    $marcador = RevisaoMarcador::create([
                        'imagem_revisao_id' => $revisao->id,
                        'x'         => $value['x'],
                        'y'         => $value['y'],
                        'texto'     => $value['texto'],
                        'ordem'     => $value['ponto'],
                    ]); 
                    
                    $pin      = $value['ponto'];
                    $caminhos = $request->get("caminho_arquivo"); 

                    if($fileRevisao && array_key_exists($pin,$fileRevisao)) {
                        foreach ($fileRevisao[$pin] as $index => $vl) {

                            $current = Carbon::now()->timestamp;
                            $nome_midia = Controller::tirarAcentos( str_replace(' ', '_', $vl->getClientOriginalName()) );
                            $upload = $vl->storeAs('public'. DIRECTORY_SEPARATOR . $pasta_midias, $current.'-'.$nome_midia);

                             if($upload){
                                $midias = RevisaoMarcadorMidia::create([
                                    'imagem_revisoes_marcador_id' => $marcador->id,
                                    'src'         => $pasta_midias . DIRECTORY_SEPARATOR . $current.'-'.$nome_midia,
                                    'caminho_arquivo' => $caminhos[$pin][$index],

                                ]); 

                            }
                        }
                    }
                }

            }else{
                $request->session()->flash('message.level', 'erro');
                $request->session()->flash('message.content', 'Problema ao salvar arquivos de referência.');
                $request->session()->flash('message.erro', 'Falha ao salvar o arquivo ' . $nome . ' na pasta ' . $pasta_midias);
                return redirect()->back()->withInput();
            }

            if(!empty($request->allFiles())){
                
         /*       $arquivos  = $request->allFiles()['arquivos_ref'];
                $dados_img = $request->get('novas_ref');
                $count     = 0;
                $nome_job  = Controller::tirarAcentos( str_replace(' ', '_', $request->get('nome')) );

                foreach ($arquivos as $file){

                    # monta o caminho da pasta
                    $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'midias' . DIRECTORY_SEPARATOR . $nome_job;
                    # retirar acentos e espaços do nome do arquivo
                    $nome = Controller::tirarAcentos( str_replace(' ', '_', $file->getClientOriginalName()) );
                    # salva arquivo na pasta
                    $upload = $file->storeAs($pasta_midias, $nome);
                    # retira 'public/' do caminho do arquivo para salvar no banco de dados
                    $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);

                    if($upload){
                        
                        # nome do tipo de arquivo
                        $nome_tipo_arquivo = TipoArquivo::where('id', $dados_img['tipo_id'][$count])->get()->first()->nome;

                        $arquivo = Midia::create([
                            'tipo_arquivo_id' => $dados_img['tipo_id'][$count],
                            'nome'            => $nome_tipo_arquivo,
                            'caminho'         => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                            'descricao'       => $nome_tipo_arquivo . ' do Job',
                            'nome_original'   => $nome,
                            'nome_arquivo'    => $nome
                        ]);
                        # vincula ao job a midia recém inserida
                        $job->midias()->attach($arquivo->id);

                    } else {
                        $request->session()->flash('message.level', 'erro');
                        $request->session()->flash('message.content', 'Problema ao salvar arquivos de referência.');
                        $request->session()->flash('message.erro', 'Falha ao salvar o arquivo ' . $nome . ' na pasta ' . $pasta_midias);
                    }
                    $count++;
                }*/
            }


            // foreach ($request->get('pins') as $key => $value) {
            //      echo $value['texto'];
            //      echo $value['x'];
            //      echo $value['y'];
            //      echo $value['ponto'];
            // }
          

          /*  foreach ($request->get('pins') as $key => $value) {
                $marcador = RevisaoMarcador::create([
                    'imagem_revisao_id' => $revisao->id,
                    'x'         => $value['x'],
                    'y'         => $value['y'],
                    'texto'     => $value['texto'],
                ]); 

            }


            DD($request);

            foreach ($request->get('file') as $key => $value) {
                $marcador = RevisaoMarcador::create([
                    'imagem_id' => $request->get('imagem_id'),
                    'x'         => $value['x'],
                    'y'         => $value['y'],
                    'texto'     => $value['texto'],
                ]); 

            }*/

          


          //  $revisao->attach($marcador);


            // $revisao->marcadores()->sync($request->get('pins'));


            \DB::commit();
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Revisão cadastrada com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('imagens.show', encrypt($request->get('imagem_id')));
            
        } catch (Exception $e) {
            \DB::rollback();

             # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A revisão não pôde ser cadastrada.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Revisao  $revisao
     * @return \Illuminate\Http\Response
     */
    public function show($revisao_id) {
        //
        $revisao_id = decrypt($revisao_id);
        $revisao  = Revisao::with('marcadores')->get()->find($revisao_id);

        if($revisao) {
            if($this->request->wantsJson()) {
                $viewHTML = view('revisoes.detalhe', compact('revisao'))->render();
                return \Response::json(array('success' => true, 'view' => $viewHTML));
            }else{
                return view('revisoes.detalhe', compact('revisao'));
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Revisao  $revisao
     * @return \Illuminate\Http\Response
     */
    public function edit($revisao_id) {
     
        $revisao_id = decrypt($revisao_id);
        $revisao  = Revisao::with('marcadores')->get()->find($revisao_id);
        if($revisao) {
            if($this->request->wantsJson()) {
                $viewHTML = view('revisoes.edit', compact('revisao'))->render();
                return \Response::json(array('success' => true, 'view' => $viewHTML));
            }else{
                return view('revisoes.edit', compact('revisao'));
            }
        }   //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Revisao  $revisao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {

       
        $this->validate($request, [
            'revisao_id'=> 'required',
            'pins'=> 'required',
        ]);

        try {
            
            \DB::beginTransaction();

            $fileRevisao = $request->file('input-arquivo');

             # monta o caminho da pasta
            $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'revisoes' . DIRECTORY_SEPARATOR . $request->get('imagem_id').'_'.$request->get('revisao_nome');
            
            // # retirar acentos e espaços do nome do arquivo
            // $nome = Controller::tirarAcentos( str_replace(' ', '_', $img_revisao_base->getClientOriginalName()) );
            
          
            // # retira 'public/' do caminho do arquivo para salvar no banco de dados
            $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);


            // $rev = Revisao::where('imagem_id', $request->get('imagem_id'))->orderBy('nome')->get()->last(); //devolve 404 no fail
            


            foreach ($request->get('pins')  as $index => $value) 
            {
                $tempMarcadorId = $value['marcador_id'];
                $marcador = RevisaoMarcador::updateOrCreate(
                    ['id' => $tempMarcadorId, ],
                    [   
                        'x'    => $value['x'],
                        'y'     => $value['y'],
                        'texto' => $value['texto'],
                        'ordem' => $value['ponto'],
                        'imagem_revisao_id' => $request->get('revisao_id')
                    ]
                ); 

                echo($index." - id marcador - ".$value['marcador_id']." / revisao id - ".$request->get('revisao_id')." / x- ".$value['x']." / y - ".$value['y']." /texto - ".$value['texto']." / ponto - ". $value['ponto']."<br>");
                
                $pin      = $value['ponto'];
                $caminhos = $request->get("caminho_arquivo"); 


                //controle para salvar midis dos pins
                if($fileRevisao && array_key_exists($pin,$fileRevisao)) {
                    foreach ($fileRevisao[$pin] as $index => $vl) {

                        $current = Carbon::now()->timestamp;
                        $nome_midia = Controller::tirarAcentos(str_replace(' ', '_', $vl->getClientOriginalName()) );
                        $upload = $vl->storeAs('public'. DIRECTORY_SEPARATOR . $pasta_midias, $current.'-'.$nome_midia);

                         if($upload){
                            $midias = RevisaoMarcadorMidia::create([
                                'imagem_revisoes_marcador_id' => $marcador->id,
                                'src'         => $pasta_midias . DIRECTORY_SEPARATOR . $current.'-'.$nome_midia,
                                'caminho_arquivo' => $caminhos[$pin][$index],

                            ]); 

                        }
                    }
                }

                
            }

         
            \DB::commit();
            //dd($request);

            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Revisão atualizada com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('imagens.show', encrypt($request->get('imagem_id')));
            
        } catch (Exception $e) {
            \DB::rollback();

             # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A revisão não pôde ser atualizada.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Revisao  $revisao
     * @return \Illuminate\Http\Response
     */
    public function destroy($imagem_id, $revisao_id) {
        //
        $revisao_id = decrypt($revisao_id);
        $imagem_id = decrypt($imagem_id);

        if($revisao_id != null)
        {

            try {
                \DB::beginTransaction();
                $revisao  = Revisao::findOrFail($revisao_id); //devolve 404 no fail
                $revisao->delete();
                \DB::commit();

                session()->flash('message.level', 'success');
                session()->flash('message.content', 'A revisão foi excluida com sucesso!');
                session()->flash('message.erro', '');

                return redirect()->route('imagens.show', encrypt($imagem_id));

            }
            catch (Exception $e) 
            {
                \DB::rollback();

                 # status de retorno
                session()->flash('message.level', 'erro');
                session()->flash('message.content', 'A revisão não pode ser exlcuirda.');
                session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

                return redirect()->back()->withInput();
            }
        }
    }

    public function destroyMarcador($marcador_id)     {
        $marcador_id = decrypt($marcador_id);
        if($marcador_id != null)
        {

            try {
                \DB::beginTransaction();
                $revisaoMarcador  = RevisaoMarcador::findOrFail($marcador_id); //devolve 404 no fail
                $midias_marcador = $revisaoMarcador->midias()->get();
                foreach ($midias_marcador as $midia) {
                    # code...
                    $caminho_final  = 'app/public/'.$midia->src;
                    $exists = File::exists(storage_path($caminho_final));

                    if($exists) {
                        unlink(storage_path($caminho_final));
                    }
                }
                $revisaoMarcador->delete();


                \DB::commit();

                return \Response::json(array(
                    'code'      =>  200,
                    'message'   =>  'Marcador excluido.', 
                ), 200);

            }
            catch (Exception $e) 
            {
                \DB::rollback();

                return \Response::json(array(
                    'code'      =>  500,
                    'message'   =>  'Marcador não excluido. ' . $exception->getMessage(),
                ), 500);            }
        }

    }

    public function destroyMarcadorMidia($midia_id, $marcador_id) {
        $midia_id    = decrypt($midia_id);
        $marcador_id = decrypt($marcador_id);
        if($midia_id != null)
        {

            try {
                \DB::beginTransaction();
                $revisaoMarcadorMidia  = RevisaoMarcadorMidia::findOrFail($midia_id); //devolve 404 no fail
                $caminho_final  = 'app/public/'.$revisaoMarcadorMidia->src;
                $exists = File::exists(storage_path($caminho_final));

                if($exists) {
                    unlink(storage_path($caminho_final));
                }
                $revisaoMarcadorMidia->delete();

                \DB::commit();

                return \Response::json(array(
                    'code'      =>  200,
                    'message'   =>  'Marcador excluido.', 
                ), 200);

            }
            catch (Exception $e) 
            {
                \DB::rollback();

                return \Response::json(array(
                    'code'      =>  500,
                    'message'   =>  'Marcador não excluido. ' . $exception->getMessage(),
                ), 500);            }
        }

    }
}
