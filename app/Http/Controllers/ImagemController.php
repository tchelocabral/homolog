<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Imagem;
use App\Models\ImagemTipo;
use App\Models\Midia;
use App\Models\Job;
use App\Models\TipoJob;
use App\User;
use Session;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Notifications\AlertAction;
use App\Models\UserNotification;


class ImagemController extends Controller{

    protected $request;
    protected $imagem;

    public function __construct(Request $request, Imagem $imagem) {

        $this->request = $request;
        $this->imagem = $imagem;
        $this->middleware('auth');
        $this->middleware('permission:lista-imagem');
        $this->middleware('permission:cria-imagem', ['only' => ['create','store']]);
        $this->middleware('permission:atualiza-imagem', ['only' => ['edit','update']]);
        $this->middleware('permission:deleta-imagem', ['only' => ['destroy']]);
    }

    public function index() {

        $imagens = Imagem::with(['projeto', 'tipo.grupo'])->get();

        return view('imagens.lista', compact('imagens'));
    }

    public function create() {

        $imagem     = new Imagem();
        $tipos_imgs = ImagemTipo::all();
        $clientes   = Cliente::all();
        $projetos   = Projeto::all();
        $finalizadores = User::role(['coordenador', 'equipe', 'freelancer', 'admin'])->where('publicador_id', null)->get();;

        return view('imagens.novo', compact('imagem', 'tipos_imgs', 'projetos', 'finalizadores', 'clientes'));
    }

    public function store(Request $request) {
        
       
        $validator = $this->validate($request, [
            'imagem_tipo_id'  => 'required',
        ]);

        try{
            
            \DB::beginTransaction();

            // dd($request->all());
            
            $imagem = new Imagem(); 
            $imagem->fill($request->all());
            $tipo = ImagemTipo::find($request->get('imagem_tipo_id'));
            $imagem->nome  = $tipo->nome;
            $imagem->valor = is_null($request->get('valor')) ? 0.00 : str_replace(",",".", str_replace([".", "R$ "], "", $request->get('valor')) ) ;
            $imagem->finalizador_id = $request->get('finalizador_id') == -1 ? null : $request->get('finalizador_id');
            $imagem->projeto_id = decrypt($request->get('projeto_id'));;


            $imagem->save();

            $finalizador = $imagem->finalizador_id ? User::find($imagem->finalizador_id) :  false;
            $proj        = Projeto::where('id', $request->get('projeto_id'))->with(['coordenador'])->get()->first() ?? false;
            $coord_proj  = $proj && $proj->coordenador ? $proj->coordenador : false;

            $rota        = route('imagens.show', encrypt($imagem->id));
            // $nome_obj    = $imagem->id;


            $param = array(
                'cliente'       => $proj ? $proj->cliente : null,   
                'imagem'        => $imagem, 
                'job'           => null, 
                'task'          => null, 
                'projeto'       => $proj, 
                'tipo'          => null,
                'destinatario'  => null, 
                'rota'          => $rota,
            );

            if($finalizador){
                # notificação ao direta ao finalizador
                $param['tipo'] = "img_finalizador_vc";
                $param['destinatario'] = $finalizador;
                $notificacao = new UserNotification($param);
                $finalizador->notify(new AlertAction($notificacao));

                # notificação do novo colaborador ao coordenador do projeto se nao for o mesmo
                if($coord_proj && $coord_proj->id != \Auth::id() ){
                    //Comentado dia 09-04-2020 para analise do cliente
                    // $param['tipo'] = "img_finalizador_outros";
                    // $param['destinatario'] = $coord_proj;
                    // $notificacao = new UserNotification($param);
                    // $coord_proj->notify(new AlertAction($notificacao));
                }
            }
            if($coord_proj && $coord_proj->id != \Auth::id() ){
                //Comentado dia 09-04-2020 para analise do cliente
                // $param['tipo'] = "img_nova";
                // $param['destinatario'] = $coord_proj;
                // $notificacao = new UserNotification($param);
                // $coord_proj->notify(new AlertAction($notificacao));
            }

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Imagem incluída ao Projeto com sucesso!');
            $request->session()->flash('message.erro', '');

            return redirect()->route('imagens.add', ($request->get('projeto_id')));

        }catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A imagem não pôde ser adicionada ao projeto!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->route('projetos.index');
    } // end store

    public function show($id) {
        $id = decrypt($id);
        $imagem = Imagem::where('id', $id)
            ->with([ 'projeto', 'projeto.cliente', 'projeto.coordenador', 'finalizador', 'tipo', 'arquivos', 'jobs', 'revisoes' ])
            ->get()->first();

        $pode_add_revisao = true; //dispara ocm job de revisao concluido

        $finalizadores = User::role(['equipe', 'freelancer', 'coordenador', 'admin'])->where('publicador_id', null);
        $avaliadores   = User::role(['avaliador']);


        return \View::make('imagens.detalhes', compact('imagem', 'finalizadores', 'pode_add_revisao', 'avaliadores'));
    }

    public function edit($id) {
        $id = decrypt($id);
        
        $imagem        = Imagem::with('projeto')->with('tipo')->get()->find($id);
        $finalizadores = User::role(['coordenador', 'equipe', 'freelancer', 'admin'])->where('publicador_id', null)->get();
        $tipos_imgs    = ImagemTipo::all();
        return view('imagens.edit', compact('imagem', 'tipos_imgs', 'finalizadores'));  
    }

    public function update(Request $request, $id) {

        $id = decrypt($id);

        try {

            \DB::beginTransaction();

            $imagem = Imagem::find($id);
            $imagem->fill($request->all());
            $imagem->valor = is_null($request->get('valor')) ? 0.00 : str_replace(",",".", str_replace([".", "R$ "], "", $request->get('valor')) ) ;;

            $imagem->finalizador_id = $request->get('finalizador_id') == -1 ? null : $request->get('finalizador_id');
            
            $finalizador_origi = $imagem->getOriginal('finalizador_id');

            $imagem->save();

            // notificação
            $finalizador = $imagem->finalizador_id ? User::find($imagem->finalizador_id) : false;
            $proj        = Projeto::where('id', $imagem->projeto_id)->with(['coordenador'])->get()->first() ?? false;
            $coord_proj  = $proj && $proj->coordenador ? $proj->coordenador : false;

            $rota        = route('imagens.show', encrypt($imagem->id));
            // $nome_obj    = $imagem->id;

            $param = array(
                'cliente'       => $proj ? $proj->cliente : null,  
                'imagem'        => $imagem, 
                'job'           => null, 
                'task'          => null, 
                'projeto'       => $proj, 
                'tipo'          => null,
                'destinatario'  => null, 
                'rota'          => $rota,
            );


            if($finalizador) {
                if($finalizador->id != $finalizador_origi){
                    # notificação ao novo colaborador 
                    $param['tipo'] = "img_finalizador_vc";
                    $param['destinatario'] = $finalizador;
                    $notificacao = new UserNotification($param);
                    $finalizador->notify(new AlertAction($notificacao));
                    
                    # notificação do novo colaborador ao coordenador do projeto se nao for o mesmo
                    if($coord_proj && $coord_proj->id != \Auth::id() ){
                       //Comentado dia 09-04-2020 para analise do cliente
                        // $param['tipo'] = "img_finalizador_outros";
                        // $param['destinatario'] = $coord_proj;9
                        // $notificacao = new UserNotification($param);
                        // $coord_proj->notify(new AlertAction($notificacao));
                    }
                } 
            } elseif($finalizador_origi) {
                # notificação do novo colaborador ao coordenador do projeto se nao for o mesmo
                if($coord_proj && $coord_proj->id != \Auth::id() ){
                    //Comentado dia 09-04-2020 para analise do cliente
                    // $param['tipo'] = "img_finalizador_removido";
                    // $param['destinatario'] = $coord_proj;
                    // $notificacao = new UserNotification($param);
                    // $coord_proj->notify(new AlertAction($notificacao));
                }
            }
            
            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A imagem foi atualizada com sucesso!');
            $request->session()->flash('message.erro', '');

        }catch(\Exception $exception) {

            \DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A imagem não pode ser atualizada!');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('imagens.show', encrypt($imagem->id));

    }// end update

    public function destroy(Request $request, $id) {

        $id = decrypt($id);

        try{
            
            $imagem  = Imagem::findOrFail($id);

            //ToDo: a busca trás outro projeto que não o da imagem.
            //$projeto = $imagem->first()->projeto;
            $pro_id = $imagem->projeto_id;
            //dd($pro_id);
            // $projeto = $imagem->projeto;
            foreach ($imagem->jobs as $job) {
                    # code...

                    $job->delete();

            }
            //ToDo: Colocor delete da imagem no log
            $imagem->delete();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Imagem excluída com sucesso!');
            $request->session()->flash('message.erro', '');

        }catch (\Exception $exception){
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'A imagem não pôde ser excluída');
            $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());
            return redirect()->back()->withInput();
        }

        return redirect()->route('projetos.show', encrypt($pro_id));

        // if($this->request->wantsJson()){
        //     return $imagem;
        // }else {
        //     return redirect()->back();
        // }
    }

    public function progresso($id) {

        $id = decrypt($id);

        try{
            if(!isset($id)) {
                return false;
            }
            $progresso = Imagem::find($id)->concluido();
            if(request()->ajax()) {
                return \Response::json(array(
                    'code'      =>  200,
                    'progresso' =>  $progresso
                ), 200);
            }
        }catch (\Exception $exception){

            if(request()->ajax()) {
                return \Response::json(array(
                    'code'      =>  500,
                    'message'   =>  'Não foi possível recuperar o progresso. ' . $exception->getMessage()
                ), 500);
            }
        }
        return redirect()->back();
    }

    // Retorna view para add Mídia ao Arquivo
    public function addArquivo($id = null) {

        if($id){
            $imagem = Imagem::with(['projeto.arquivos.tipo_arquivo', 'tipo.grupo'])->find(decrypt($id));
            return view('arquivo.add_imagem', compact('imagem'));
        } else {
            // dd($projetos);
            $projetos   = Projeto::with('imagens')->get();
            return view('arquivo.add_imagem', compact('projetos'));
        }      

    }

    //todo: Mudar para JobController@create que recebe param
    public function addJob($img_id, $proj_id = null){

        $img_id = decrypt($img_id);
        if($proj_id!=null) {
            $projeto_id = decrypt($proj_id); 
        }
        else
        {
            $proj_id = null;
        }
        if($img_id){
            if(!$proj_id){
                $proj_id = Imagem::find($img_id) ? Imagem::find($img_id)->projeto_id : null;
                $proj_id =  encrypt($proj_id);
            }

            // Passa parametros via session
            // session()->flash('proj_id', $proj_id);
            // session()->flash('img_id',  $img_id);
            $img_id = encrypt($img_id);
            //foi criado uma roda job.create.projeto.imagem para pode enviar 2 parametros
            return redirect()->route('job.create.projeto.imagem', ['proj_id' => $proj_id, 'img_id' => $img_id]);
            //return redirect()->action('JobController@create', ['proj_id' => $proj_id, 'img_id' => $img_id]);

        }else{
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'Falta a imagem para adicionar o job.');
            $request->session()->flash('message.erro', '');

            return redirect()->back()->withInput();
        }
    }

    public function addFinalizador($img_id, $finalizador_id = null, Request $request){

        $img_id = decrypt($img_id);
        $finalizador_id = decrypt($finalizador_id);
        try{
            \DB::beginTransaction();

            // O finalizador não foi passado por parâmetro?
            if(!$finalizador_id){
                // Pega o finalizador do request
                $finalizador_id = $request->get('finalizador_id');
            }
            // Busca a imagem de acordo com o id da imagem que foi passada via parâmetro
            $img = Imagem::where('id', $img_id)->with('projeto', 'projeto.coordenador', 'projeto.cliente')->get()->first();
            // Seta o finalizador da img com o valor inteiro do finalizador_id
            $img->finalizador_id = intval($finalizador_id);
            // $img->save();

            // pegar o último job criado pra esta imagem que seja de finalização
            $job_finalizador_img = $img->jobs()->get()->sortByDesc('created_at')->filter(function($item){
                    return $item->tipo->finalizador == 1;
             })->first();
            // dd($job_finalizador_img);

            // Existem jobs de finalização na lista de jobs da imagem?
            if(!is_null($job_finalizador_img) && !empty($job_finalizador_img)){
                
                // Seta o delegado_para deste job para o finalizador passado por parametro
                $job_finalizador_img->delegado_para = intval($finalizador_id);
                // Salva a porra toda
                $job_finalizador_img->save();

            }else{
                // Pego o primeiro tipo de job criado que tenha finalizador
                $tipo_job_finalizador = TipoJob::where('finalizador','1')->with('tasks')->get()->sortBy('created_at')->first();
                // dd($tipo_job_finalizador);

                $nome_job  = str_replace(' ', '_', $img->projeto->cliente->nome_fantasia);
                $nome_job .= str_replace(' ', '_', $img->projeto->nome);
                $nome_job .= str_replace(' ', '_', $img->nome);
                $nome_job .= str_replace(' ', '_', $img->descricao);
                $nome_job .= str_replace(' ', '_', $tipo_job_finalizador);
                $nome_job  = strtolower($nome_job);

                // Se não existem jobs de finalização para esta imagem, cria um
                $job = Job::create([
                    'nome'                   => $nome_job,
                    'tipojob_id'             => $tipo_job_finalizador->id,
                    'cliente_id'             => $img->projeto->cliente->id,
                    'delegado_para'          => intval($finalizador_id),
                    'coordenador_id'         => $img->projeto->coordenador_id,
                    'user_id'                => \Auth::user()->id,
                    'avaliador_id'           => null,
                    'descricao'              => 'Job criado para o Finalizador da Imagem'
                ]);

                // Vincula a imagem ao job recém criado
                $job->imagens()->attach($img); 
                // Vincula as tasks do tipo de job a este job
                $job->tasks()->attach($tipo_job_finalizador->tasks);
                // Se for um tipo de Job de Revisão, seta o status_revisao da imagem para ele
                $tipo_job_finalizador->revisao ? $img->status_revisao = $tipo_job_finalizador->id : null;
                // dd($img->status_revisao);
            } 

            // atualiza imagem
            $img->save();   

            /////////////////
            // Notificação //
            /////////////////

            $finalizador = User::where('id', $finalizador_id)->get()->first();
            $proj        = $img->projeto ?? false;
            $coord_proj  = $proj && $proj->coordenador ? $proj->coordenador : false;
            $rota        = route('imagens.show', encrypt($img->id));
            // $nome_obj    = $img->id;

            $param = array(
                'cliente'       => $proj ? $proj->cliente : null,  
                'imagem'        => $img, 
                'job'           => null, 
                'task'          => null, 
                'projeto'       => $proj, 
                'tipo'          => null,
                'destinatario'  => null, 
                'rota'          => $rota,
            );


            # notificação ao novo colaborador 
            $param['tipo'] = "img_finalizador_vc";
            $param['destinatario'] = $finalizador;
            $notificacao = new UserNotification($param);
            $finalizador->notify(new AlertAction($notificacao));
            
            # notificação do novo colaborador ao coordenador do projeto se nao for o mesmo
            if($coord_proj && $coord_proj->id != \Auth::id() ){
                //Comentado dia 09-04-2020 para analise do cliente
                // $param['tipo'] = "img_finalizador_outros";
                // $param['destinatario'] = $coord_proj;
                // $notificacao = new UserNotification($param);
                // $coord_proj->notify(new AlertAction($notificacao));
            }


            // $id_preview = TipoJob::where('nome', 'Preview')->get()->first()->id;
            // $job_preview = $img->jobs()->get()->filter(function($item) use ($id_preview){
            //         return $item->tipojob_id == $id_preview;
            //  })->all();
            // if(!is_null($job_preview) && !empty($job_preview)){
            //     $job_preview = current($job_preview);
            //     $job_preview->delegado_para = intval($finalizador_id);
            //     $job_preview->save();
            // }else{
            //     $job = Job::create([
            //         'nome'                   => 'Job de finalização',
            //         'tipojob_id'             => TipoJob::where('nome', 'Preview')->get()->first()->id,
            //         'cliente_id'             => $img->projeto->cliente->id,
            //         'delegado_para'          => intval($finalizador_id),
            //         'coordenador_id'         => $img->projeto->coordenador_id,
            //         'user_id'                => \Auth::user()->id,
            //         'avaliador_id'           => null,
            //         'descricao'              => 'Job de Preview para finalização da Imagem'
            //     ]);
            //     $job->imagens()->attach($img);
            // }


            \DB::commit();

            # status de retorno
            \Session::flash('message.level', 'success');
            \Session::flash('message.content', 'Finalizador alterado com sucesso!');
            \Session::flash('message.erro', '');

        } catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', 'O Finalizador não pode ser alterado.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->back();
    }

    public function concluir($id){
        
        $id = decrypt($id);
        try{
            \DB::beginTransaction();

            $img = Imagem::where('id', $id)->with('projeto')->get()->first();
            if($img->concluido() != 100){
                # status de retorno
                \Session::flash('message.level', 'erro');
                \Session::flash('message.content', 'Imagem não pode ser concluída. ');
                \Session::flash('message.erro', 'Tem Jobs e Tasks em aberto.');

                return redirect()->back()->withInput();
            }
            $img->status = 2; #2-Concluída
            $img->save();

            \DB::commit();

            # status de retorno
            \Session::flash('message.level', 'success');
            \Session::flash('message.content', 'Imagem concluída com sucesso!');
            \Session::flash('message.erro', '');

            return redirect()->route('projetos.show', encrypt($img->projeto->id));

        } catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', 'A imagem não pode ser concluída.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    public function reabrir($id){
        
        $id = decrypt($id);
        try{
            \DB::beginTransaction();

            $img = Imagem::where('id', $id)->with('projeto')->get()->first();
            $img->status = 1; # Em Adamento
            $img->save();

            \DB::commit();

            # status de retorno
            \Session::flash('message.level', 'success');
            \Session::flash('message.content', 'Imagem reaberta com sucesso!');
            \Session::flash('message.erro', '');

            return redirect()->route('projetos.show', encrypt($img->projeto->id));

        } catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', 'A imagem não pode ser reaberta.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back()->withInput();
        }
    }

    // Vincula serie de arquivos a imagens
    public function vincularArquivos(Request $request) {

        $validator = $this->validate($request, [
            'projeto_id' => 'required',
            'arquivos'   => 'required'
        ]);

        try{

            \DB::beginTransaction();
          
            if($request->has('imagens') && $request->get('arquivos')){
                foreach($request->get('imagens') as $key => $img){
                    $imagem = Imagem::find($img);
                    $imagem->arquivos()->attach($request->get('arquivos'));
                }
            } else {
                $request->session()->flash('message.level', 'erro');
                $request->session()->flash('message.content', 'Arquivos e Imagens não puderam ser vinculados.');
                $request->session()->flash('message.erro', 'Parâmetros faltando na requisição!');

                return redirect()->back()->withInput();
            }

            \DB::commit();

            # status de retorno
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Arquivos e Imagens vinculados com sucesso!');
            $request->session()->flash('message.erro', '');

        }catch(\Exception $exception){
            \DB::rollBack();

            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'Arquivos e Imagens não puderam ser vinculados.');
            $request->session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

            return redirect()->back()->withInput();
        }

        return redirect()->route('projeto.vincular.img.arquivo', $request->get('projeto_id'));

    }

    // Desvincula serie de arquivos a imagens
    public function desvincularArquivos($arquivo, $img) {
        $arquivo = decrypt($arquivo);
        $img = decrypt($img);
        $request = new Request();
        try{
            \DB::beginTransaction();
        
                $imagem = Imagem::find($img);
                $imagem->arquivos()->detach($arquivo);
                \DB::commit();

            # status de retorno
            Session::flash('message.level', 'success');
            Session::flash('message.content', 'Arquivos e Imagens desvinculados com sucesso!');
            Session::flash('message.erro', '');

        }catch(\Exception $exception) {
            \DB::rollBack();

            # status de retorno
            Session::flash('message.level', 'erro');
            Session::flash('message.content', 'Arquivos e Imagens não puderam ser desvinculados.');
            Session::flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());

            return redirect()->back()->withInput();
        }
        return redirect()->back();

    }

    // Adicionar tabela e demais Revisões
    // @deprecated
    // Revisão agora é um tipo de Job
    public function adicionaRevisao(Request $request, $id, $rev_num) {
        
        $id = decrypt($id);
        $rev_num = decrypt($rev_num);

        $validator = $this->validate($request, [
            'data_prox_revisao' => 'required'
        ]);

        try{
            
            if (!is_null($id) && !is_null($rev_num)) {

                // Pegar a imagem do id
                $imagem =  Imagem::where('id',$id)->get()->first();

                if ($imagem) {
                   // Atualizar a coluna data_revisao
                    $imagem->data_revisao = $request->get('data_prox_revisao');

                    // save()
                    $imagem->save();

                    # status de retorno
                    $request->session()->flash('message.level', 'success');
                    $request->session()->flash('message.content', 'Revisão adicionada com sucesso!');
                    $request->session()->flash('message.erro', '');
                
                } else{
                    # status de retorno
                    $request->session()->flash('message.level', 'erro');
                    $request->session()->flash('message.content', 'Parâmetro incorretos para solicitação.');
                    $request->session()->flash('message.erro', 'ID da Imagem e Número de Revisão são obrigatórios.');
                    return redirect()->back()->withInput();
                }   
            } else {
                # status de retorno
                $request->session()->flash('message.level', 'erro');
                $request->session()->flash('message.content', 'Parâmetro incorretos para solicitação.');
                $request->session()->flash('message.erro', 'ID da Imagem e Número de Revisão são obrigatórios.');
                return redirect()->back()->withInput();
            }
            
        }catch(\Exception $exception){
            # status de retorno
            $request->session()->flash('message.level', 'erro');
            $request->session()->flash('message.content', 'Problemas ao gerar a data de revisão.');
            $request->session()->flash('message.erro', '<br>' . $exception->getMessage() . '<br>' . $exception->getLine());
            return redirect()->back()->withInput();
        }
        return redirect()->route('imagens.show', encrypt($id));

    }

    public function factory() {

        $imagens = factory(\App\Models\Imagem::class, 3)->create();
        dd($imagens);
    }


}
