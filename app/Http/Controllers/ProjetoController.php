<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Imagem;
use App\Models\ImagemTipo;
use App\Models\Midia;
use App\Models\Projeto;
use App\Models\Job;
use App\Models\TipoArquivo;
use App\User;
use Illuminate\Http\Request;
use Session;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\UserNotification;
use App\Notifications\AlertAction;

class ProjetoController extends Controller {

    private $projeto;
    protected $request;

    public function __construct(Request $request, Projeto $projeto) {

        $this->request= $request;
        $this->projeto = $projeto;
        $this->middleware('auth');
        $this->middleware('permission:lista-projeto');
        $this->middleware('permission:cria-projeto', 
            ['only' => 
                ['create','store','addImg','addArquivo','vincularImgArquivo',
                'gravarArquivo','desvincularArquivos'
                ]
            ]
        );
        $this->middleware('permission:atualiza-projeto', 
            ['only' => 
                ['edit','update','addImg','addArquivo','vincularImgArquivo',
                'gravarArquivo','desvincularArquivos'
                ]
            ]
        );
        $this->middleware('permission:deleta-projeto', ['only' => ['destroy']]);
    }

    public function index() {

        $projetos = Projeto::with('cliente')->get();

        // dd($projetos);

        return view('projeto.lista', compact('projetos'));
    }

    public function create() {

        $projeto       = Projeto::with('cliente')->with('coordenador')->with('imagens')->with('faturamentos')->get()->first();
        $clientes      = Cliente::all();
        $tipos_imagens = ImagemTipo::all();
        $coordenadores = User::role(['coordenador', 'admin'])->where('publicador_id', \Auth::user()->publicador_id)->get();

        return view('projeto.novo', compact(['clientes', 'coordenadores', 'tipos_imagens','projeto']));
    }

    public function store(Request $request) {
        
        $this->validate($request, [
            'nome'              => 'required',
            'cliente_id'        => 'required'
        ]);

        try{

            \DB::beginTransaction();

            $projeto = Projeto::create([
                'nome'                  => $request->get('nome'),
                'cliente_id'            => $request->get('cliente_id'),
                'coordenador_id'        => $request->get('coordenador_id')!=-1 ? $request->get('coordenador_id') : null,
                'descricao'             => $request->get('descricao'),
                'cnpj'                  => $request->get('cnpj'),
                'observacoes'           => $request->get('observacoes'),
                'data_previsao_entrega' => $request->get('data_previsao_entrega'),
                'dados_faturamento'     => $request->get('dados_faturamento')
            ]);

            if($request->has('imagens')){
                foreach ($request->get('imagens') as $key => $img){
                    $imagem = Imagem::create([
                        'projeto_id'     => $projeto->id,
                        'imagem_tipo_id' => $img['imagem_tipo_id'],
                        'nome'           => $img['nome'],
                        'observacoes'    => $img['observacoes'],
                        'data_revisao'   => $img['data_revisao']
                    ]);
                }
            }

            $coord = $request->get('coordenador_id') == -1 ? null : User::where('id', $request->get('coordenador_id'))->get()->first();

            $rota = route('projetos.show', encrypt($projeto->id));
            $param = array(
                'cliente'       => $projeto->cliente, 
                'imagem'        => null, 
                'job'           => null, 
                'task'          => null, 
                'projeto'       => $projeto, 
                'tipo'          => null,
                'destinatario'  => $coord, 
                'rota'          => $rota,
            );


            // $userAdm =  User::where('id', 3)->get()->first();
            // //enviar adm
            // if($userAdm) {
            //     $param['tipo'] = "prj_novo";;
            //     $newUserNot = new UserNotification($param);
            //     $userAdm->notify(new AlertAction($newUserNot));
            // }
            if($coord) {
                #notificação coordenador selecionado da criação do job
                //Comentado dia 09-04-2020 para analise do cliente
                // $param['tipo'] = "prj_coord";;
                // $newUserNot = new UserNotification($param);
                // $coord->notify(new AlertAction($newUserNot));
            }

            \DB::commit();

            //             $rota = route('projetos.show', encrypt($projeto->id));
            //             $tipo = "novo_projeto";
            // //            ToDo:envio para administrador fixo para o Leo da mint
            //             // $destinatario = User::where('email','dlleobartz@gmail.com')->get()->first();
            //             //    $destinatario->notify(new AlertAction($destinatario, $rota, $tipo));

            //             if($$request->get('coordenador_id')) {
            //                 $destinatario = User::where('id',$job['coordenador_id'])->get()->first();
            //                 $tipo = "coodernador_job";
            //                 $destinatario->notify(new AlertAction($destinatario, $rota, $tipo));
            //             }



            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Projeto cadastrado com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('projetos.show', encrypt($projeto->id));

        }catch (\Exception $exception) {

            \DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O projeto não pôde ser cadastrado.');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->route('projetos.index');

    } // end store


    public function show($id) {
        $id = decrypt($id);

        $concluir_job = false;

        $projeto  = Projeto::with(['arquivos','cliente','coordenador','imagens','faturamentos'])->get()->find($id);
        // dd($projeto);
        foreach ($projeto->imagens as $img) {
            foreach ($img->jobs as $value) {
                if(!$value->verificaStatus('recusado') && !$value->verificaStatus('concluido') && !$value->verificaStatus('parado') && $value->concluido()>=100) {
                    $value->pode_concluir = true;
                    $concluir_job = true;
                }
            }
        }

        
        $clientes = Cliente::all();

        return view('projeto.detalhes', compact('projeto','clientes','concluir_job'));
    }

    public function edit($id) {
        $id = decrypt($id);
        $projeto       = Projeto::with('cliente')->with('coordenador')->with('imagens')->with('faturamentos')->get()->find($id);
        $clientes      = Cliente::all();
        $coordenadores = User::role(['coordenador', 'admin'])->where('publicador_id', \Auth::user()->publicador_id)->get();

        return view('projeto.edit', compact(['projeto', 'clientes', 'coordenadores']));
    }

    public function update(Request $request, $id) {
        $id = decrypt($id);

        try {

            $this->validate($request, [
                'nome'              => 'required',
                'cliente_id'        => 'required',
                'coordenador_id'    => 'required'
            ]);

            \DB::beginTransaction();

            $projeto = Projeto::find($id);
            $projeto_original = $projeto->getOriginal();
            $coord_origi  = $projeto_original['coordenador_id'] ?? false;

            $projeto->fill($request->all());
            $request->get('coordenador_id') != '-1' or $projeto->coordenador_id = null;
            
            $projeto->save();

            $coord = $request->get('coordenador_id') == -1 ? null : User::where('id', $request->get('coordenador_id'))->get()->first();
            
            $novo_coord = $coord && $coord->id != $coord_origi;

            if($novo_coord) {
                $coord = $request->get('coordenador_id') == -1 ? null : User::where('id', $request->get('coordenador_id'))->get()->first();

                $rota = route('projetos.show', encrypt($projeto->id));
                $param = array(
                    'cliente'       => $projeto->cliente, 
                    'imagem'        => null, 
                    'job'           => null, 
                    'task'          => null, 
                    'projeto'       => $projeto, 
                    'tipo'          => null,
                    'destinatario'  => $coord, 
                    'rota'          => $rota,
                );

                if($coord) {
                    #notificação coordenador selecionado da criação do job
                    //Comentado dia 09-04-2020 para analise do cliente
                    // $param['tipo'] = "prj_coord";;
                    // $newUserNot = new UserNotification($param);
                    // $coord->notify(new AlertAction($newUserNot));
                }
            }

            \DB::commit();

            // if($$request->get('coordenador_id')) {
            //     $destinatario = User::where('id',$job['coordenador_id'])->get()->first();
            //     $tipo = "coodernador_job";
            //     $destinatario->notify(new AlertAction($destinatario, $rota, $tipo));
            // }
            
            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'O projeto foi atualizado com sucesso!');
            $request->session()->flash('message.erro', '');

        }catch(\Exception $exception) {
            
            \DB::rollback();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'O projeto não pode ser atualizado!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->route('projetos.index');

    } // end update
    public function destroy($id) {
        $id = decrypt($id);
        try{
            \DB::beginTransaction();

            $projeto = Projeto::with('imagens')->findOrFail($id);

            foreach ($projeto->imagens as $img) {

                foreach ($img->jobs as $job) {
                    # code...

                    $job->delete();

                }

                $img->delete();
            }


            $projeto->delete();

            \DB::commit();

            # status de retorno
            \Session::flash('message.level', 'success');
            \Session::flash('message.content', 'Projeto excluído com sucesso!');
            \Session::flash('message.erro', '');

        } catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', 'O projeto não pôde ser excluído.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
        }
        return redirect()->route('projetos.index');
    } 
    // Funções específicas
    public function emAndamento() {
        $projetos = Projeto::whereIn('status', [0, 1])->with('coordenador')->get();
        return view('projeto.andamento', compact('projetos'));        
    }
    public function concluidos() {
        $projetos = Projeto::where('status', 2)->with('coordenador')->get();
        return view('projeto.concluidos', compact('projetos'));
    }

    public function concluir($id){
        $id = decrypt($id);
        try{
            \DB::beginTransaction();

            $projeto = Projeto::where('id', $id)->get()->first();
            if($projeto->concluido() != 100){
                # status de retorno
                \Session::flash('message.level', 'erro');
                \Session::flash('message.content', 'Projeto não pode ser concluído. ');
                \Session::flash('message.erro', 'Tem imagens e jobs em aberto.');

                return redirect()->back()->withInput();
            }
            $projeto->status = 2; #2-concluído
            $projeto->save();

            \DB::commit();

            # status de retorno
            \Session::flash('message.level', 'success');
            \Session::flash('message.content', 'Projeto concluído com sucesso!');
            \Session::flash('message.erro', '');

            return redirect()->route('projetos.show', encrypt($id));

        } catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', 'Projeto não pode ser concluído.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    public function reabrir($id){
        $id = decrypt($id);
        try{
            \DB::beginTransaction();

            $projeto = Projeto::where('id', $id)->get()->first();
            $projeto->status = 1; #1-Em Andamento
            $projeto->save();

            \DB::commit();

            # status de retorno
            \Session::flash('message.level', 'success');
            \Session::flash('message.content', 'Projeto reaberto com sucesso!');
            \Session::flash('message.erro', '');

            return redirect()->route('projetos.show', encrypt($id));

        } catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', 'Projeto não pode ser reaberto.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    public function addImg($id = null) {
        $id = decrypt($id);

        $projeto    = Projeto::with(['cliente','coordenador','imagens'])->where('id', $id)->get()->first();
        $tipos_imgs = ImagemTipo::all();
        $finalizadores = User::role(['coordenador', 'equipe', 'freelancer', 'admin'])->where('publicador_id', null)->get();
 
        // dd($projeto);
    
        return view('imagens.novo', compact(['projeto', 'tipos_imgs', 'finalizadores']));
    }

    /* Lista as Imagens de um determinado projeto passado por parâmetro */
    public function listarImagens(Request $request) {

        if($request->ajax()){
            return Imagem::with(['projeto', 'tipo'])->where('projeto_id', $request->get('id'))->get();
        } else {
            
            return true;
        }
    }

    /* Lista as Imagens com data de R_00 de um determinado projeto passado por parâmetro */
    public function listarImagensComR00(Request $request) {
        
        if($request->ajax()){
            return Projeto::find($request->get('id'))->imagensComR00();
        } else {
           
            return true;
        }
    }

    // Lista os Arquivos de um determinado projeto passado por parâmetro
    public function listarArquivos(Request $request) {

        $prj = Projeto::with(['arquivos', 'arquivos.tipo_arquivo'])->find($request->get('id'));
        if($request->ajax()){
            
            return $prj->arquivos;
        } else {
            
            return true;
        }
    }

    // Retorna view para adicionar arquivos ao projeto
    public function addArquivo($id = null) {   
        $id = decrypt($id);
        $projeto = Projeto::with('cliente')->with('coordenador')->with('imagens')->where('id', $id)->get()->first();
        $tipos_arquivos = TipoArquivo::all();
        return view('arquivo.add_projeto', compact('projeto'), compact('tipos_arquivos'));
    }

    // Retorna view para vincular arquivos e imagens de um projeto específico
    public function vincularImgArquivo($id = null) {
        $id = decrypt($id);
        $projeto  = Projeto::where('id', $id)->get()->first();
        $projetos = Projeto::with('imagens')->get();
        
        return view('arquivo.add_imagem', compact(['projetos', 'projeto']));
    }

    public function gravarArquivo(Request $request) {

        

        $validator = $this->validate($request, [
            'projeto_id'      => 'required',
            'arquivo_tipo_id' => 'required'
        ]);
        
        $timestamp    = \Carbon\Carbon::now()->timestamp;
        $upload       = false;
        $nome         = false;
        $prj          = Projeto::find(decrypt($request->get('projeto_id')));

       // Existem arquivos e um projeto setado?
        if(empty($request->allFiles()) || !$prj){
            # status de retorno
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Sem arquivos para adicionar.');
            $request->session()->flash('message.erro', '');
            return redirect()->back()->withInput();

        }else{

            try{

                \DB::beginTransaction();

                $arquivos  = $request->allFiles()['lista_arquivos'];
                $upload    = false;
                $nome      = false;

                // Define a pasta de mídias
                $pasta_midias = 
                    'public' . DIRECTORY_SEPARATOR . 'midias' . DIRECTORY_SEPARATOR . 'prj_' . $request->get('projeto_id');
                
                // dd($arquivos);
                foreach ($arquivos as $file) {
                    // $file = $request->file('arquivo');
                    // dd($file);
                    $nome = str_replace(' ', '_', $file->getClientOriginalName());

                    $upload       = $file->storeAs($pasta_midias, $nome);
                    $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR,'',$pasta_midias);
                    
                    if(!$upload){
                        new \Exception('Falha ao fazer upload do arquivo', 500);
                    }

                    # nome do tipo de arquivo
                    $nome_tipo_arquivo = 
                        $request->has('nome') 
                        ? $request->get('nome') 
                        : TipoArquivo::where('id', $request->get('arquivo_tipo_id'))->get()
                            ->first()->nome;

                    # cria o arquivo de Midia no banco
                    $arquivo = Midia::create([
                        'tipo_arquivo_id' => $request->get('arquivo_tipo_id'),
                        'nome'            => $nome_tipo_arquivo,
                        'caminho'         => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                        'descricao'       => $request->has('descricao') ? $request->get('descricao') : 'Não Informado',
                        'nome_original'   => $nome,
                        'nome_arquivo'    => $nome
                    ]);

                    $prj->arquivos()->attach($arquivo->id);
                    $upload    = false;
                    $nome      = false;
                }
                \DB::commit();

                # status de retorno. 
                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', 'Arquivos adicionados com sucesso!');
                $request->session()->flash('message.erro', '');

                # retorna pra rota de adicionar arquivos ao projeto
                return redirect()->route('projeto.add.arquivo', $request->get('projeto_id'));

            } catch (\Exception $exception) {

                \DB::rollBack();

                # status de retorno
                $request->session()->flash('message.level', 'erro');
                $request->session()->flash('message.content', 'Os arquivos não puderam ser adicionados');
                $request->session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

                return redirect()->back()->withInput();
            }
        }
    }

    public function gravarArquivoOld_191029(Request $request) {

        $validator = $this->validate($request, [
            'projeto_id' => 'required',
        ]);
        $pasta_midias = 'public' . DIRECTORY_SEPARATOR . 'midias' . DIRECTORY_SEPARATOR . 'prj_' . $request->get('projeto_id');
        $timestamp    = \Carbon\Carbon::now()->timestamp;
        $upload       = false;
        $nome         = false;

        if($request->ajax()) {
            try{
                \DB::beginTransaction();

                $prj = Projeto::find($request->get('projeto_id'));

                // if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid() && $prj) {
                if(!empty($request->allFiles()) && $prj){
                    $arquivos  = $request->allFiles();
                    $upload    = false;
                    $nome      = false;
                    
                    foreach ($arquivos as $file) {
                        // $file = $request->file('arquivo');

                        $nome = str_replace(' ', '_', $file->getClientOriginalName());

                        $upload       = $file->storeAs($pasta_midias, $nome);
                        $pasta_midias = str_replace('public' . DIRECTORY_SEPARATOR,'',$pasta_midias);
                        
                        if(!$upload){
                            new \Exception('Falha ao fazer upload do arquivo', 500);
                        }

                        # nome do tipo de arquivo
                        $nome_tipo_arquivo = 
                            $request->has('nome') 
                            ? $request->get('nome') 
                            : TipoArquivo::where('id', $dados_img['tipo_id'][$count])->get()->first()->nome;

                        $arquivo = Midia::create([
                            'tipo_arquivo_id' => $request->get('tipo_arquivo'),
                            'nome'            => $nome_tipo_arquivo,
                            'caminho'         => $pasta_midias . DIRECTORY_SEPARATOR .  $nome,
                            'descricao'       => $request->has('descricao') ? $request->get('descricao') : 'Não Informado',
                            'nome_original'   => $nome,
                            'nome_arquivo'    => $nome
                        ]);

                        $prj->arquivos()->attach($arquivo->id);
                        $upload    = false;
                        $nome      = false;
                    }

                    \DB::commit();

                    # status de retorno. Está sendo usado via ajax
                    // $request->session()->flash('message.level', 'success');
                    // $request->session()->flash('message.content', 'Arquivo adicionado com sucesso!');
                    // $request->session()->flash('message.erro', '');

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


        }else {
            try {

                \DB::beginTransaction();

                if ($request->has('arquivo')) {
                    $upload = false;
                    $nome   = false;

                    $file = $request->file('arquivo');

                    $contents = file_get_contents($file);

                    $file = str_replace("\\", '/', $file);

                    $upload = Storage::put($pasta_midias, $nome);

                }

                \DB::commit();

                # status de retorno
                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', 'Arquivos adicionados com sucesso!');
                $request->session()->flash('message.erro', '');

                return redirect()->route('projetos.show', encrypt($request)->get('projeto_id'));

            } catch (\Exception $exception) {

                \DB::rollBack();

                # status de retorno
                $request->session()->flash('message.level', 'erro');
                $request->session()->flash('message.content', 'Os arquivos não puderam ser adicionado.');
                $request->session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

                return redirect()->back()->withInput();
            }
        }
    }

    // Desvincula serie de arquivos a imagens
    public function desvincularArquivos(Request $request, $arquivo, $prj) {

        $arquivo = decrypt($arquivo);
        $prj = decrypt($prj);
        // $request = new Request();
        try{
            \DB::beginTransaction();
     
            $projeto = Projeto::find($prj);
            $projeto->arquivos()->detach($arquivo);
            \DB::commit();

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content', 'Arquivo desvinculado com sucesso!');
            Session::flash('message.erro', '');

        }catch(\Exception $exception){
            \DB::rollBack();

            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'O arquivo não pôde ser desvinculado deste projeto!');
            Session::flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }

    /**
     * Cria 3 registros fakes para teste do banco.
     *
     * @return dd($planos);
     */
    public function factory() {

        $projetos = factory(\App\Models\Projeto::class, 3)->create();
        dd($projetos);
    }



}
