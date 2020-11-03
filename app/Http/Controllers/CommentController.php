<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\CommentAlert;
use App\Models\CommentNotification;
use App\User;
use App\Models\Job;
use App\Models\Projeto;
use App\Models\Cliente;
use App\Models\Imagem;
use App\Models\ImagemTipo;


class CommentController extends Controller
{
    protected $request;
    protected $comment;

    public function __construct(Request $request, comment $comment) {

        $this->request = $request;
        $this->comment = $comment;

        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $validator = $this->validate($request, [
            'descricao' => 'required',
            'type'      => 'required'
        ]);
        try{
            \DB::beginTransaction();
            $comment = Comment::create([
                'parent_id'         => $request->get('parent_id') ?? null,
                'user_id'           => \Auth()->user()->id,
                'commentable_type'  => $request->get('type'),
                'commentable_id'    => $request->get('commentable_id'),
                'descricao'         => $request->get('descricao'),
            ]);

            $this->user_current = \Auth::user();
            $coordenadores = null;


            $jobid = $request->get('commentable_id');
            $job    = Job::where("id", $jobid)->get()->first();


            $delegado = $job->delegado ?? null;;
            $coord = $job->coordenador ?? null;;
            $publicador =  $job->user()->get()->first();

            $enviado_delegado = false;
            $enviado_coord = false;
            $enviado_publicador = false;

            if($job->freela == 0) {
                if($request->get('marcados') !== "") {
                    $listaMarcados = explode(',', $request->get('marcados'));


                    $rota = route('jobs.show', encrypt($jobid));
                    $tipo = "job_marcado_comentario";

                    $proj_id    = $job->imagens() && $job->imagens()->first()->projeto ? $job->imagens()->first()->projeto->id : '-1';
                    $proj       = Projeto::where('id', $proj_id)->with(['coordenador'])->get()->first() ?? false;
                    
                    $param = array(
                        'cliente'       =>  $proj ? $proj->cliente : null,  
                        'imagem'        =>  $job->imagens() ? $job->imagens()->get() : false, 
                        'job'           =>  $job, 
                        'task'          =>  null, 
                        'projeto'       =>  $proj, 
                        'tipo'          =>  null,   
                        'destinatario'  =>  null, 
                        'rota'          =>  $rota,
                    );


                    foreach ($listaMarcados as $key => $value) {
                        # code...
                        if($value !== "") {
                        
                            $colab  = User::where("marcador", $value)->get()->first();

                            if($colab->id == $delegado->id)
                            {
                                $enviado_delegado = true;
                            }

                            if($colab->id == $coord->id)
                            {
                                $enviado_coord = true;
                            }

                            if($colab->id == $publicador->id)
                            {
                                $enviado_publicador = true;
                            }

                            $param['tipo'] = $tipo;
                            $param['destinatario'] = $colab;

                            $notificacao = new CommentNotification($param);
                            $colab->notify(new CommentAlert($notificacao));
                        }
                    }

                    //echo ($enviado_delegado . " - enviado_delegado / " .$enviado_publicador . " - enviado_publicador / " .$enviado_coord . " - enviado_coord / " );
                    //dd($request);
                    if($enviado_delegado == false && $delegado->id != \Auth::id())  {
                        
                        $tipo = "job_novo_comentario";
                        $param['tipo'] = $tipo;
                        $param['destinatario'] = $delegado;

                        $notificacao = new CommentNotification($param);
                        $delegado->notify(new CommentAlert($notificacao));
                    }

                    if($enviado_coord == false && $coord->id != \Auth::id()) {
                        $tipo = "job_novo_comentario";
                        $param['tipo'] = $tipo;
                        $param['destinatario'] = $coord;

                        $notificacao = new CommentNotification($param);
                        $coord->notify(new CommentAlert($notificacao));
                    }

                    if($enviado_publicador == false && $publicador->id != \Auth::id()) {
                        $tipo = "job_novo_comentario";
                        $param['tipo'] = $tipo;
                        $param['destinatario'] = $publicador;

                        $notificacao = new CommentNotification($param);
                        $publicador->notify(new CommentAlert($notificacao));

                    }

                    // if(strpos($request->get('descricao'), '@') !== false ){

                        // $marc_index = explode($request->get('descricao'), '@');
                        // $marcador   = substr()
                        // parei aqui, pegar a string  
                    // }
                }
            }else{

                #notificação dos envolvidos - job publicador (avulso)
                $rota = route('jobs.show', encrypt($job->id));
                // $nome_obj   = $job->id;

                $param = array(
                    'cliente'       => null, 
                    'imagem'        => null, 
                    'job'           => $job, 
                    'task'          => null, 
                    'projeto'       => null, 
                    'tipo'          => null,
                    'destinatario'  => null, 
                    'rota'          => $rota,
                );

                // se existe colaborador e não for o mesmo do anterior, avisa ele
                if($delegado && $delegado->id != \Auth::id()){
                    # notificação ao novo colaborador 
                    $param['tipo'] = "job_novo_comentario";
                    $param['destinatario'] = $delegado;
                    $notificacao = new CommentNotification($param);
                    $delegado->notify(new CommentAlert($notificacao));
                
                }
                if($coord && $job->coordenador->id != \Auth::id()){
                    $param['tipo'] = "job_novo_comentario";
                    $param['destinatario'] = $coord;
                    $notificacao = new CommentNotification($param);
                    $coord->notify(new CommentAlert($notificacao));
                }
                elseif(!$job->coordenador_id)
                {
                    $coordenadores  = User::role(['coordenador', 'admin'])->where('publicador_id', $job->publicador_id)->where('id', '!=',  $this->user_current->id)->get();
                    foreach ($coordenadores as $coord_outro) {
                        $param['tipo'] = "job_novo_comentario";
                        $param['destinatario'] = $coord_outro;
                        $notificacao = new CommentNotification($param);
                        $coord_outro->notify(new CommentAlert($notificacao));
                    }

                }

                if($publicador && $publicador->id != \Auth::id()) {
                    # notificação ao novo colaborador 
                    $param['tipo'] = "job_novo_comentario";
                    $param['destinatario'] = $publicador;
                    $notificacao = new CommentNotification($param);
                    $publicador->notify(new CommentAlert($notificacao));
                
                }




            }
            \DB::commit();

            if($request->ajax()) {
                return \Response::json(array(
                    'code'      =>  200,
                    'message'   =>  'Comentário inserido.',
                    'id_comment'=>  encrypt($comment->id)
                  //  'comments'  =>  $comment->commentable();
                ), 200);
            }


        }catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            // $request->session()->flash('message.level', 'erro');
            // $request->session()->flash('message.content', 'O cometário  não pôde ser cadastrado.');
            // $request->session()->flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            // return redirect()->back()->withInput();
            if($request->ajax()) {
                return \Response::json(array(
                    'code'      =>  500,
                    'message'   =>  'Comentário não foi inserido. ' . $exception->getMessage(),
                    'linha'     =>  'Linha:  ' . $exception->getLine(),
                    'dd'        =>  $job,
                    'dd2'       =>  $coordenadores,
                ), 500);
            }
        }
        
        return redirect()->back();
    } // Função de retorno em Json

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            \DB::beginTransaction();

            $comment = Comment::findOrFail(decrypt($id));

            $comment->delete();

            \DB::commit();

            # status de retorno
            \Session::flash('message.level', 'success');
            \Session::flash('message.content', 'Comentário excluído com sucesso!');
            \Session::flash('message.erro', '');

            return redirect()->back();

        } catch (\Exception $exception){

            \DB::rollBack();

            # status de retorno
            \Session::flash('message.level', 'erro');
            \Session::flash('message.content', 'O comentário não pôde ser excluído.');
            \Session::flash('message.erro', '<br>'.$exception->getMessage().'<br>'.$exception->getLine());

            return redirect()->back();
        }
    }

    public static function MarcarTexto() {
        return "Hello World!";
    }

}
