<?php

namespace App\Http\Controllers;

use App\Models\Midia;
use Illuminate\Http\Request;
use App\Models\TipoJob;
use App\Models\Task;
use App\Models\TipoArquivo;
use Session;

class TipoJobController extends Controller
{

    protected $request;
    protected $tipoJob;

    public function __construct(Request $request, TipoJob $tipoJob) {

        $this->request = $request;
        $this->tipoJob = $tipoJob;
        $this->middleware('auth');
        $this->middleware('permission:lista-tipo-job');
        $this->middleware('permission:cria-tipo-job', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-tipo-job', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-tipo-job', ['only' => ['destroy']]);
    }

    public function index() {
        
        $tiposjob = TipoJob::all();
        return view('tipojob.lista', compact('tiposjob'));
    }

    public function create() {

        $tasks = Task::orderBy('nome', 'asc')->get();

        return view('tipojob.novo', compact('tasks'));
    }

    public function store(Request $request) {

        // dd($request);

        $validator = $this->validate($request, [
            'nome' => 'required|unique:tipo_jobs,nome',
        ]);

        try{
            
            \DB::beginTransaction();

            // dd($request);

            $caminho = 'imagens' . DIRECTORY_SEPARATOR . 'tipojobs';
            $upload  = Controller::upload($request->file('imagem'), $caminho);
            $imagem  = $upload ? $upload : 'imagens/tipojobs/tipo-padrao.jpg' ;
            
            // $request->imagem = $imagem;

            $tipojob = TipoJob::create($request->except(['_token', 'imagem']));
            $tipojob->imagem = $imagem;
            $tipojob->save();

            if($request->has('tasks')){
                $ordem = 1;
                foreach($request->get('tasks') as $tk) {
                    $tipojob->tasks()->attach($tk, ['ordem' => $ordem]);    
                    $ordem++;
                }
            }

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Novo Tipo de Job incluído com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('tipojobs.show', encrypt($tipojob->id));

        }catch (\Exception $exception){
            
            \DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O Tipo de Job não pôde ser incluído.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('tipojobs.index');
    }

    public function show($id) {

        $id = decrypt($id);
        //$tasks = Task::orderBy('nome', 'asc')->get();
        $tipojob = TipoJob::with('tasks')->find($id);

        $tipojob->podeApagar = ($tipojob->jobs && count($tipojob->jobs) > 0) ? false : true;
        
        $tipojob->lista_tipos_troca =null;
        if(!$tipojob->podeApagar)
        {
            $tipojob->lista_tipos_troca = TipoJob::where('id','!=', $id)->get();
        }

        return \View::make('tipojob.detalhes', compact('tipojob'));
    }

    public function edit($id) {
        $id = decrypt($id);
        //
        $tipojob          = TipoJob::with('tasks')->find($id);
        $tipojob_tasks_id = $tipojob->tasks->pluck('id')->all();
        $tasks            = Task::orderBy('nome', 'asc')->get();

        return \View::make('tipojob.edit', compact(['tipojob', 'tasks', 'tipojob_tasks_id']));
    }

    public function update(Request $request, $id) {

        $id = decrypt($id);
        // dd($request);

        try{

            $tipojob = TipoJob::find($id);

            $tipojob->fill($request->except(['imagem']));

            if($request->has('imagem') && $request->file('imagem')->isValid()){
                $caminho = 'imagens' . DIRECTORY_SEPARATOR . 'tipojobs';
                $upload  = Controller::upload($request->file('imagem'), $caminho);
                $imagem  = $upload ? $upload : ($tipojob->imagem ?? 'imagens/tipojobs/tipo-padrao.jpg') ;
                $tipojob->imagem = $imagem;
            }
            
            $tipojob->save();

            # Tasks
            # Atualiza
            $tipojob->tasks()->sync($request->get('tasks'));
            if($request->has('tasks')){
                # Ordena
                $ordem = 1;
                foreach ($request->get('tasks') as $task) {
                    $tipojob->tasks()->updateExistingPivot($task, ['ordem' => $ordem]);
                    $ordem++;
                }
            }


            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', $request['nome'] . ' atualizado com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('tipojobs.index');

        }catch (\Exception $exception) {

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O Tipo de Job não pôde ser atualizado.');
            $request->session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id) {

        $id = decrypt($id);
        try{
            
            $tipojob = TipoJob::findOrFail($id);
            $tipojob->delete();

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content', 'Tipo de Job excluído com sucesso!');
            Session::flash('message.erro', '');


            return redirect()->route('tipojobs.index');

        } catch (\Exception $exception){

            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'O Tipo de Job não pôde ser excluído.');
            Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->route('tipojobs.index');
        }
    }


    public function transferirJobs($id, Request $request) {
        $id = decrypt($id);

        try {

            $tipo_atual = TipoJob::where('id', $id)->first();
            $tipo_novo = TipoJob::where('id', $request->tipos_troca)->first();

            // echo ($tipo_atual->id . " - " .$tipo_novo->id);
            
            // dd($tipo_atual);
            // Se os usuários não existem ou são iguais
            if(!$tipo_atual || !$tipo_novo || $tipo_atual->id ==  $tipo_novo->id){
                session()->flash('message.level', 'erro');
                session()->flash('message.content', __('messages.Dados dos tipos não estão correto!') . '.');
                session()->flash('message.erro', '');
                return redirect()->back()->withInput();
            }
           
            if($tipo_atual->jobs && count($tipo_atual->jobs) > 0) {
                //mudar jobs delegado
                foreach ($tipo_atual->jobs as $key => $job) {
                    $job->tipojob_id = $tipo_novo->id;
                    $job->save();
                    # code...
                }
            }              

       
            //excluir o usuário antigo

            \DB::beginTransaction();
                $tipo_atual->delete();
            \DB::commit();

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('session.Os dados foram transferidos e o tipo foi deletado.'));
            session()->flash('message.erro', '');

            return redirect()->route('tipojobs.index');


        } catch (\Exception $e) {
            
            # status de retorno
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.Os dados não foram transferidos ou a tipo não foi deletado.'));
            session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());
            //dd($e->getMessage().'<br>'.$e->getLine());
            return redirect()->back()->withInput();

        }

        return view('tipojobs.index', compact('user_id'));
    }


    /* Retorna view para add Mídia ao Arquivo */
    public function addArquivo($id) {
        $id = decrypt($id);
        $tipojob        = TipoJob::find($id);
        $tipos_arquivos = TipoArquivo::whereIn('nome', array('Referência', 'Boas Práticas', 'Exemplo'))->get();
        return view('arquivo.add_tipojob', compact('tipojob', 'tipos_arquivos'));
    }

    /* Retorna view para add Mídia ao Arquivo */
    public function addArquivos($id) {
        $id = decrypt($id);
        $tipojob        = TipoJob::find($id);
        $tipos_arquivos = TipoArquivo::whereIn('nome', array('Referência', 'Boas Práticas', 'Exemplo'))->get();
        $ids            = $tipos_arquivos->pluck('id');
        $midias         = $tipojob->midias->pluck('id');
        $arquivos       = Midia::whereIn('tipo_arquivo_id', $ids)->whereNotIn('id', $midias)->get();
        return view('tipojob.tipojob_arquivos', compact('tipojob', 'tipos_arquivos', 'arquivos'));
    }

    public function gravarArquivo(Request $request) {

        # validate
        $validator = $this->validate($request, [
            'tipojob_id' => 'required',
        ]);

        # monta o caminho da pasta
        $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'midias' . DIRECTORY_SEPARATOR . 'tipojob_' . $request->get('tipojob_id');

        try{

            \DB::beginTransaction();

            # busca o tipo de job
            $tipojob = TipoJob::find($request->get('tipojob_id'));

            # valida arquivo e tipo de job
            if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid() && $tipojob) {

                # pega arquivo do request
                $file = $request->file('arquivo');

                # retirar espaços do nome do arquivo
                $nome = str_replace(' ', '_', $file->getClientOriginalName());

                # salva arquivo na pasta
                $upload = $file->storeAs($pasta_midias, $nome);
                # retira 'public/' do caminho do arquivo para salvar no banco de dados
                $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR, '', $pasta_midias);

                if(!$upload){
                    new \Exception('Falha ao fazer upload do arquivo', 500);
                }
                $arquivo = \App\Models\Midia::create([
                    'tipo_arquivo_id' => $request->get('tipo_arquivo'),
                    'nome'            => $request->get('nome'),
                    'caminho'         => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                    'descricao'       => $request->has('descricao') ? $request->get('descricao') : 'Não Informado',
                    'nome_original'   => $nome,
                    'nome_arquivo'    => $nome
                ]);
                # vincula midia recém inserida ao tipo de job
                $tipojob->midias()->attach($arquivo->id);

                \DB::commit();

                # status de retorno
                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', 'Arquivo adicionado com sucesso!');
                $request->session()->flash('message.erro', '');

                return $arquivo;

            }

        }catch(\Exception $exception) {

            \DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O arquivo não pôde ser adicionado.');
            $request->session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

            return \Response::json(array(
                'code'      =>  500,
                'message'   =>  'O arquivo não pôde ser adicionado. ' . $exception->getMessage()
            ), 500);
        }
    }

    /* Vincula serie de arquivos a imagens */
    public function vincularArquivos(Request $request) {

        # validate
        $validator = $this->validate($request, [
            'tipojob_id' => 'required',
            'arquivos'   => 'required'
        ]);

        try{

            \DB::beginTransaction();
            # valida arquivos do request
            if($request->get('arquivos')){
                # busca tipo de job
                $tipojob = TipoJob::find($request->get('tipojob_id'));
                # vincula os aruqivos ao tipo de job
                $tipojob->midias()->attach($request->get('arquivos'));
            } else {
                $request->session()->flash('message.level', 'erro');
                $request->session()->flash('message.content', 'Arquivos não puderam ser vinculados ao Tipo de Job.');
                $request->session()->flash('message.erro', 'Parâmetros faltando na requisição!');

                return redirect()->back()->withInput();
            }
            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Arquivos vinculados ao Tipo de Job com sucesso!');
            $request->session()->flash('message.erro', '');

        }catch(\Exception $exception){
            \DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'Arquivos não puderam ser vinculados ao Tipo de Job.');
            $request->session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->route('tipojobs.show', encrypt($request->get('tipojob_id')));

    }

    /* Desvincula serie de arquivos a imagens */
    public function desvincularArquivos($arquivo, $id) {
        $id = decrypt($id);
        try{
           
            $tipojob = TipoJob::find($id);
            $tipojob->midias()->detach($arquivo);

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content', 'Arquivo desvinculado com sucesso!');
            Session::flash('message.erro', '');

        }catch(\Exception $exception){
            \DB::rollBack();

            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'Arquivo não pôde ser desvinculado.');
            Session::flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->back();

    }

    /* Retorna os dados de um Tipo de Job */
    public function dados(Request $request) {
        
        if($request->ajax()){
            return TipoJob::with('midias')->with('tasks')->get()->find($request->get('id'));
        } else {
            return true;
        }
    }

}
