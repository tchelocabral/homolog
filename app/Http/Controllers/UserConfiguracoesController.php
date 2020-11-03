<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\User;
use App\Models\ConfiguracoesUser;
use App\Models\JobCandidatura;
use Auth;

class UserConfiguracoesController extends Controller
{
    
    protected $request;
    protected $user;
    protected $user_ativo;
    protected $user_ativo_role;
    protected $user_ativo_permissions;


    public function __construct(Request $request, User $user){
    
        $this->middleware('auth');
        // $this->middleware('role:admin,desenvolvedor');

        $this->request = $request;
        $this->user = $user;
        $this->user_ativo = Auth::user();
        // $this->user_ativo_role = $this->user_ativo->roles();
        // $this->user_ativo_permissions = $this->user_ativo->permissions();

    }

    /**
     * Aumenta a quantidade de jobs que um usuário pode ter ao mesmo tempo
     * Tem dois tipod de Slots: Job Livre e Job por Candidatura
     * O tipo é passado via parâmetro
     *
     * @param User $user
     * @return void
     */
    public function aumentarSlotJobLivre(){

        $validator = $this->validate($this->request, [ 'user_id' => 'required', 'slots' => 'required' ]);
       
        try{

            \DB::beginTransaction();
            $id = decrypt($this->request->get('user_id'));   
            
            $configs = ConfiguracoesUser::where('user_id', $id)
                            ->where('chave', 'qtde_jobs_andamento')
                            ->get()
                            ->first();

                          
            if(!$configs){
                $configs = new ConfiguracoesUser();
                $configs->user_id = $id;
                $configs->chave = 'qtde_jobs_andamento';
            }


            $configs->valor = $this->request->slots;

            $configs->save();

            \DB::commit();

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('session.Slot de Jobs alterado') . '!');
            session()->flash('message.erro', '');

        }catch(\Exception $e){
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.Problemas ao alterar a quantidade do slot de jobs') . '!');
            session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());

        }
        return redirect()->back();
    }

    public function aumentarSlotJobCandidatura(){

        $validator = $this->validate($this->request, [ 'user_id' => 'required', 'slots' => 'required' ]);
        //dd( $validator);
       
        try{
            \DB::beginTransaction();
            $id = decrypt($this->request->get('user_id'));   
            
            $configs = ConfiguracoesUser::where('user_id', $id)
                            ->where('chave', 'qtde_jobs_candidaturas')
                            ->get()->first();
            
            if(!$configs){
                $configs = new ConfiguracoesUser();
                $configs->user_id = $id;
                $configs->chave = 'qtde_jobs_candidaturas';
            }

            //salva quantidade nova e atual de slot de jobs livres 
            $qtdes_jobs_novo =  $this->request->slots;
            $qtdes_jobs_atual = $configs->valor;
   
            //se quantodade  de slot for aumentada coloca candidaturas em status 0;
            if($qtdes_jobs_novo >  $qtdes_jobs_atual) {
                $job_cand_trocar = JobCandidatura::where('user_id',  $id)->where('status', 4)->get();

                if($job_cand_trocar) {
                    foreach ($job_cand_trocar as $value) {
                        $value->status = 0; 
                        $value->save();
                    }
                }
            }


            $configs->valor = $this->request->slots;

            $configs->save();

            \DB::commit();

            session()->flash('message.level', 'success');
            session()->flash('message.content', __('session.Slot de Jobs Candidatura alterado') . '!');
            session()->flash('message.erro', '');

        }catch(\Exception $e){
            session()->flash('message.level', 'erro');
            session()->flash('message.content', __('session.Problemas ao alterar a quantidade do slot de jobs candidatura') . '!');
            session()->flash('message.erro', '<br>'.$e->getMessage().'<br>'.$e->getLine());

        }
        return redirect()->back();
    }




}
