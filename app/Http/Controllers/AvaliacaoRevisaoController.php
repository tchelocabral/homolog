<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Revisao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Job;
use App\Models\JobRevisao;
use App\Models\JobRevisaoMarcadores;
use App\Models\JobRevisaoMarcadoresMidias;
use App\Models\JobsRevisoesTasks;
use App\Notifications\AlertAction;
use App\Models\UserNotification;

class AvaliacaoRevisaoController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
        $this->middleware('auth');
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
    public function create($avaliacao_id, $tira_arquivos) {

        $job = Job::where('id', decrypt($avaliacao_id))->with('revisoes')->get()->first();
        $job->tira_arquivos = $tira_arquivos;

        $revisao_atual = $job->revisoes()->get()->count()+1;
        if($this->request->wantsJson()) {

            $viewHTML = view('revisoesavaliacoes.novo', compact('avaliacao'))->render();
            return \Response::json(array('success' => true, 'view' => $viewHTML));
        }else{
            return view('revisoesavaliacoes.novo', compact(['job', 'revisao_atual']));
        }
    }


    public function store(Request $request) {
        //
        $this->validate($request, [
            'job_id'=> 'required',
            'pins'=> 'required',
            'img_revisao_base' => 'required'
        ]);

        try {

            \DB::beginTransaction();
           
            $fileRevisao = $request->file('input-arquivo');

            // $img_revisao_base  = $request->allFiles()['img_revisao_base'];

            //pega o request da base64 encode string da revisao com os pins
            $base64_image = $request->foto_gerada;

            //variavel do caminho da imagem
            $caminho_imagem = "";

            //gera o nome da imagem da revisão 
            $nome_imagem_revisao = $request->job_id ."_r0".$request->revisao_atual.".png";

            // gera o caminho para salvar a imagem da revisao
            $caminho_imagem = "imagens/jobs/". $request->job_id.'_'.$request->job_nome."/revisao/";
           
            //gera o caminho com nome da imagem de revisao
            $imagem_caminho_completo = $caminho_imagem.$nome_imagem_revisao;
              
            //if para confirmarse a imagem é base64 e salvar
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                $data = substr($base64_image, strpos($base64_image, ',') + 1);
                $data = base64_decode($data);
                $upload = Storage::disk('local')->put("public/".$imagem_caminho_completo, $data);

            } else {
                $data = null;
                $upload =  Storage::disk('local')->put("public/".$imagem_caminho_completo, $data);
            }

            //cria nome Revisão
            $nomeRevisao = "RI01";

            // busca se existe revisao daquele job
            $jobrevisaoLast  = JobRevisao::where('job_id', $request->get('job_id'))->get()->last(); 

            // se existe revisao, prepara o nome para a proxima revisao
            if($jobrevisaoLast){

                //dividir nome revisão para acrescentar um.
                $tempId = explode('I', $jobrevisaoLast->nome);
                // dd($tempId);
                if($tempId[1]<10) {

                    $tempNewId = intval($tempId[1]);
                    $nomeRevisao = 'RI0'. ($tempNewId+1);
                }else{
                    $nomeRevisao = 'RI'.$tempId[1]+1;
                }

            }

            # retira 'public/' do caminho do arquivo para salvar no banco de dados
            $pasta_midias_revisao = str_replace('public' . DIRECTORY_SEPARATOR, '', $caminho_imagem);
            

            if($upload){

                $jobrevisao = JobRevisao::create([
                    'job_id'            => $request->get('job_id'),
                    'avaliador_id'      => \Auth::id(), //$request->get('avaliador_id') ?? null
                    'numero_revisao'    => 0, //$request->get('numero_revisao'),
                    'nome'              => $nomeRevisao,
                    'src'               => $imagem_caminho_completo,
                    'observacoes'       => $request->get('observacoes'),
                    'status'            => 0,
                    'user_id'           =>  \Auth::id(),
                    'imagem_revisao'    => $imagem_caminho_completo,
                    'data_entrega'      =>  $request->get('data_entrega'),

                ]);
                //dd($jobrevisao);

                $nova_data = $request->get('data_entrega');
                $job = Job::where("id",$request->get('job_id'))->get()->first();
                if($nova_data != null && $job->data_prox_revisao <= $nova_data)
                {
                    $job->data_prox_revisao = $nova_data;
                    $job->save();
                }

                foreach ($request->get('pins') as $index_revisoes => $value) {
                    $marcador = JobRevisaoMarcadores::create([
                        'job_revisao_id' => $jobrevisao->id,
                        'x'         => $value['x'],
                        'y'         => $value['y'],
                        'texto'     => $value['texto'],
                        'ordem'     => $value['ponto'],
                    ]); 

                    $tasks[] = JobsRevisoesTasks::Create([
                        'task_name' => 'task_'.($index_revisoes),
                        'task_description' =>  $value['texto'],
                        'job_revisao_id' => $jobrevisao->id,
                        'ordem' => $index_revisoes,
                    ]);
                    
                    $pin      = $value['ponto'];
                    $caminhos = $request->get("caminho_arquivo"); 

                    if($fileRevisao && array_key_exists($pin,$fileRevisao)) {
                        foreach ($fileRevisao[$pin] as $index => $vl) {

                            $current = Carbon::now()->timestamp;
                            $nome_midia = Controller::tirarAcentos( str_replace(' ', '_', $vl->getClientOriginalName()) );
                            $caminho_midias = 'public'. DIRECTORY_SEPARATOR . $pasta_midias_revisao. DIRECTORY_SEPARATOR .'marcadores';
                            
                            $upload = $vl->storeAs($caminho_midias, $current.'-'.$nome_midia);
                            // dd($upload);
                             if($upload){
                                $caminho_midias =  str_replace('public' . DIRECTORY_SEPARATOR, '', $caminho_midias);
                                
                                $midias = JobRevisaoMarcadoresMidias::create([
                                    'job_revisao_marcador_id'   => $marcador->id,
                                    'src'                        => $caminho_midias . DIRECTORY_SEPARATOR . $current.'-'.$nome_midia,
                                    'caminho_arquivo'            => $caminhos[$pin][$index],
                                ]); 
                                // dd($caminho_midias. DIRECTORY_SEPARATOR . $current.'-'.$nome_midia);

                            }
                        }
                    }
                }

                //codigo para notificação
                $job = Job::where('id', $request->job_id)->with(['coordenador', 'imagens'])->get()->first();
                
                $rota = route('jobs.show', encrypt($job->id));
                $proj       =  false;
                $coord_proj =  false;

                $delegado   = $job->delegado ?? null;
                $tipo = "job_revisao_upload";
                $param = array(
                    'cliente'       => null, 
                    'imagem'        => null, 
                    'job'           => $job, 
                    'task'          => null, 
                    'projeto'       => $proj, 
                    'tipo'          => $tipo,
                    'destinatario'  => null, 
                    'rota'          => $rota,
                ); 

                if($delegado && $job->freela ==1 )
                {    
                    $param['destinatario'] = $delegado;
                    $newUserNot = new UserNotification($param);
                    //dd($newUserNot);
                    $delegado->notify(new AlertAction($newUserNot));
                }

            }else{
                $request->session()->flash('message.level', 'erro');
                $request->session()->flash('message.content', __('messages.Problema ao salvar arquivos de referência.'));
                $request->session()->flash('message.erro', 'Falha ao salvar o arquivo ' . $nome . ' na pasta ' . $pasta_midias_revisao);
                return redirect()->back()->withInput();
            }


            \DB::commit();
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', __('messages.Revisão do Job cadastrada com sucesso!'));
            $request->session()->flash('message.erro', '');

            return redirect()->route('jobs.show', encrypt($request->get('job_id')));
            
        } catch (Exception $e) {
            \DB::rollback();

             # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('messages.A revisão não pôde ser cadastrada.'));
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }      
    }

    public function show($avaliacao_id, $tira_arquivos) {
        //
        $revisao  = JobRevisao::with('marcadores')->get()->find(decrypt($avaliacao_id));
        $revisao->tira_arquivos = $tira_arquivos;

        if($revisao) {
            if($this->request->wantsJson()) {
                $viewHTML = view('revisoesavaliacoes.detalhe', compact('revisao'))->render();
                return \JobRevisao::json(array('success' => true, 'view' => $viewHTML));
            }else{
                return view('revisoesavaliacoes.detalhe', compact('revisao'));
            }
        }
    }

    public function edit($revisao_id) {
     
        $revisao  = JobRevisao::with('marcadores')->get()->find(decrypt($revisao_id));
        if($revisao) {
            if($this->request->wantsJson()) {
                $viewHTML = view('revisoesavaliacoes.edit', compact('revisao'))->render();
                return \Response::json(array('success' => true, 'view' => $viewHTML));
            }else{
                return view('revisoesavaliacoes.edit', compact('revisao'));
            }
        }   //
    }

    public function update(Request $request) {
       
        $this->validate($request, [
            'revisao_id'=> 'required',
            'pins'=> 'required',
        ]);

        try {

            \DB::beginTransaction();

            $fileRevisao = $request->file('input-arquivo');


            $job_revisao = JobRevisao::where('id', $request->get('revisao_id'))->get()->first(); 

             # monta o caminho da pasta
            $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'revisoes' . DIRECTORY_SEPARATOR . $request->get('job_id').'_'.$request->get('revisao_nome');

            // # retirar acentos e espaços do nome do arquivo
            // $nome = Controller::tirarAcentos( str_replace(' ', '_', $img_revisao_base->getClientOriginalName()) );
            
          
            // # retira 'public/' do caminho do arquivo para salvar no banco de dados
            $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);


            // $rev = Revisao::where('job_id', $request->get('job_id'))->orderBy('nome')->get()->last(); //devolve 404 no fail   


            foreach ($request->get('pins')  as $index => $value) 
            {
                $tempMarcadorId = $value['marcador_id'];
                $marcador = JobRevisaoMarcadores::updateOrCreate(
                    ['id' => $tempMarcadorId, ],
                    [   
                        'x'    => $value['x'],
                        'y'     => $value['y'],
                        'texto' => $value['texto'],
                        'ordem' => $value['ponto'],
                        'job_revisao_id' => $request->get('revisao_id')
                    ]
                ); 

                //echo($index." - id marcador - ".$value['marcador_id']." / revisao id - ".$request->get('revisao_id')." / x- ".$value['x']." / y - ".$value['y']." /texto - ".$value['texto']." / ponto - ". $value['ponto']."<br>");
                
                $pin      = $value['ponto'];
                $caminhos = $request->get("caminho_arquivo"); 


                //controle para salvar midis dos pins
                if($fileRevisao && array_key_exists($pin,$fileRevisao)) {
                    foreach ($fileRevisao[$pin] as $index => $vl) {

                        $current = Carbon::now()->timestamp;
                        $nome_midia = Controller::tirarAcentos(str_replace(' ', '_', $vl->getClientOriginalName()) );
                        $upload = $vl->storeAs('public'. DIRECTORY_SEPARATOR . $pasta_midias, $current.'-'.$nome_midia);

                         if($upload){
                            $midias = JobRevisaoMarcadoresMidias::create([
                                'job_revisao_marcador_id' => $marcador->id,
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
            $request->session()->flash('message.content', __('messages.Revisão do Job atualizada com sucesso!'));
            $request->session()->flash('message.erro', '');

            return redirect()->route('jobs.show', encrypt($job_revisao->job_id));
            
        } catch (Exception $e) {
            \DB::rollback();

             # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', __('messages.A revisão não pôde ser atualizada.'));
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
    public function destroy($revisao_id) {
        //
        if($revisao_id != null)
        {

            try {
                \DB::beginTransaction();
                $revisao  = JobRevisao::findOrFail(decrypt($revisao_id)); //devolve 404 no fail
                $job_id = $revisao->job_id;
                $revisao->delete();
                \DB::commit();

                session()->flash('message.level', 'success');
                session()->flash('message.content', 'A revisão do Job foi excluida com sucesso!');
                session()->flash('message.erro', '');

                return redirect()->route('jobs.show', encrypt($job_id));

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
        if($marcador_id != null)
        {

            try {
                \DB::beginTransaction();
                $revisaoMarcador  = JobRevisaoMarcadores::findOrFail($marcador_id); //devolve 404 no fail
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
        if($midia_id != null)
        {

            try {
                \DB::beginTransaction();
                $revisaoMarcadorMidia  = JobRevisaoMarcadoresMidias::findOrFail(decrypt($midia_id)); //devolve 404 no fail
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
                ), 500); 
            }
        }

    }
}
