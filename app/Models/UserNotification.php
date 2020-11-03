<?php

namespace App\Models;
use App\User;
use App\Models\Cliente;
use App\Models\Imagem;
use App\Models\Job;
use App\Models\Task;
use App\Models\Projeto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


class UserNotification extends Model{
	
    public $cliente         = null;
    public $imagem          = null;
    public $job             = null;
    public $task            = null;
    public $projeto         = null;
    public $tipo            = null;
    public $destinatario    = null;
    public $mensagem = "";
    public $rota     = "";

    public $tipo_array = array();


    // public function __construct($tipo, $destino, $obj) {
    public function __construct($params) {
        //
        // 
        // 
        // $this->tipo = $tipo;
        
        if($params){
            $this->mapearAtributos($params);
            $this->mensagem = $this->geraMensagem($this->tipo);
        }
    }

    // Retira lógica de notificação do Controller
    public function definirNotificados($destino, $obj){
        // switch($this->tipo){
        //     case 'img_finalizador_vc':
                
        //         break;

        //     default:
        //         break;
        // }
    }

    public function mapearAtributos($params){
        // if($params){
        	foreach ($params as $key => $value) {
        		if( $key == 'cliente'){
			        $this->cliente = $value;
        		}
        		if( $key == 'imagem'){
			        $this->imagem = $value;
        		}
        		if( $key == 'job'){
			        $this->job = $value;
        		}
        		if( $key == 'task'){
			        $this->task = $value;
        		}
        		if( $key == 'projeto'){
			        $this->projeto = $value;
        		}
        		if( $key == 'tipo'){
			        $this->tipo = $value;
        		}
        		if( $key == 'rota'){
			        $this->rota = $value;
        		}
        		if( $key == 'destinatario'){
			        $this->destinatario = $value;
        		}
            }
        	// $this->mensagem = $this->geraMensagem($this->tipo);
        // }
    }

    public function geraMensagem($tipo) {
       
        $titulo  = $this->cliente ? $this->cliente->nome_fantasia : '';
        $titulo .= $this->projeto ? ' - ' . $this->projeto->nome  : '';
        if($this->imagem &&  $this->imagem->count() > 0){
            if($this->imagem instanceof Collection && $this->imagem->count() > 0){
                $titulo .= ' - ' . $this->imagem->first()->nome;
            }else if($this->imagem){
                $titulo .= ' - ' . $this->imagem->nome;
            }
        }
        else
        {

            $titulo .=  $this->destinatario->name;
        }
        $titulo .= ' - ';
        $msg    = '';
      

        if($tipo=='job_colab_novo_vc')  {
            $this->tipo_array = array(
                'titulo' => $titulo . " " . __('messages.Job') . " " . $this->job->id ,
                'msg'    => __('notif.Você foi definido como Colaborador para o Job') . ' ' . $this->job->nome . '!'
              );
        }
        else  if($tipo=='job_delegado_novo') {
            $this->tipo_array = array(
                'titulo' => $titulo . " " . __('messages.Job') . " " . $this->job->id ,
                'msg'       => __('notif.Um Freelancer pegou o Job') . ' ' . $this->job->nome.'!'
            );
        } 

        
        else  if($tipo=='job_candidatura_nova') {
            $this->tipo_array = array(
                'titulo' => $titulo . " " . __('messages.Job') . " " . $this->job->id ,
                'msg'       => __('notif.Um Freelancer se candidatou para o Job') . ' ' . $this->job->nome.'!'
            );
        }         

        
        else  if($tipo=='job_colab_novo_outros') {
            $this->tipo_array = array(
                'titulo' => $titulo . " " . __('messages.Job') . " " . $this->job->id ,
                'msg'       => __('notif.Um novo Colaborador foi selecionado para o Job') . ' ' . $this->job->nome . '!'
            );
        } 


        else  if($tipo=='job_novo_comentario') {
            $this->tipo_array = array(
                'titulo' => $titulo . " " . __('messages.Job') . " " . $this->job->id ,
                'msg'       => __('notif.Novo comentário no Job') . ' ' . $this->job->nome . '!'
            );
        } 

        
        else  if($tipo=='job_marcado_comentario') {
            $this->tipo_array = array(
                'titulo' => $titulo . " " . __('messages.Job') . " " . $this->job->id ,
                'msg'       => __('notif.Você foi marcado em um comentário no Job') . ' ' . $this->job->nome . '!'
            );
        } 


         // 
        // essa key nao esta clara do que é
        else  if($tipo=='job_coordenador_novo_proj'){
            if($this->imagem->count()>1) {
                $this->tipo_array = array(
                    'titulo' => $titulo . " " . __('messages.Job') . " " . $this->job->id ,
                    'msg' => __('notif.Você é o coordenador do Job') . ' ' . $this->job->nome . '  das várias Imagens do Projeto '.$this->projeto->id .'!'
                );
            }
            else {
                $this->tipo_array = array(
                    'titulo'    => $titulo . " " . __('messages.Job') . " " . $this->job->id, 
                    'msg'       => __('notif.Você é o coordenador do Job') . ' ' . $this->job->id . ' 
                     da Imagem '.$this->imagem->id .' do Projeto '.$this->projeto->id .'!'
                );
            }
        }
        else  if($tipo=='job_excluido_proj') {
            $this->tipo_array = array(
                'titulo'    => $titulo . " " . __('messages.Job') . " " . $this->job->id, 
                'msg'       => 'Job ' . $this->job->nome .  ' excluído!'
            );
        }
        else  if($tipo=='job_coord_novo_vc') {
            $this->tipo_array = array(
                'titulo'    => $titulo . " " . __('messages.Job') . " " . $this->job->id,
                'msg'       => __('notif.Você foi definido como Coodernador para o Job') . ' ' . $this->job->nome . '!'
            );
        }
        else  if($tipo=='job_coord_novo_outros') {
            $this->tipo_array = array(
                'titulo'    => $titulo . " " . __('messages.Job') . " " . $this->job->id,
                'msg'       => __('notif.Um novo Coodernador para o Job') . ' ' . $this->job->nome . '!'
            );
        }
                     
        else  if($tipo=='job_task_nova') {
            if(count($this->task)>1) {
                $msgTempo = "";
                foreach ($this->task as $key => $value) {
                    # code...
                    $msgTempo = $msgTempo .$value->nome .', ';
                }
                $this->tipo_array = array(
                    'titulo'    => $titulo . " " . __('messages.Job') . " " . $this->job->id .' ' . __('messages.Tasks'),
                    'msg'       => __('notif.Novas tasks') . ' (' . substr($msgTempo, 0, -2) . ') ' . __('notif.para o Job') . ' ' . $this->job->nome
                );
            }
            else
            {
                $this->tipo_array = array(
                    'titulo'    => $titulo . " " . __('messages.Job') . " " . $this->job->id . " "  .$this->task[0]->nome,
                    'msg'       => __('notif.Nova task') . ' ' . $this->task[0]->nome . ' ' .__('notif.para o Job') . ' ' . $this->job->id
                );               
            }
        }
        else  if($tipo=='job_task_excluida') {
            if(count($this->task)>1) {
                $msgTempo = "";
                foreach ($this->task as $key => $value) {
                    # code...
                    $msgTempo = $msgTempo .$value->nome .', ';
                }
                $this->tipo_array = array(
                    'titulo'    => $titulo . " " . __('messages.Job') . " " . $this->job->id .' ' . __('messages.Tasks'),
                    'msg'       => __('messages.Tasks') . ' ('. substr($msgTempo, 0, -2). ') ' . __('notif.foram excluidas do Job') . ' ' . $this->job->nome
                );
            }
            else
            {
                $this->tipo_array = array(
                    'titulo'    => $titulo . " " . __('messages.Job') . " " . $this->job->id . " "  .$this->task->nome,
                    'msg'       => __('Task') . ' ' . $this->task->nome . ' ' . __('notif.do Job') . ' ' . $this->job->id . ' ' . __('notif.excluída') . '!'
                );               
            }
        }
        else  if($tipo=='job_task_exec') {
            $titulo .= $this->job ? __('Job') . ' ' . $this->job->id. " "  .$this->task->nome : ' ';
            $this->tipo_array = array(
                'titulo'    => $titulo,
                'msg'       => __('Task') . ' ' . $this->task->nome . ' ' . __('notif.do Job') . ' ' . $this->job->nome .  ' ' . __('notif.executada') . '!'
            );
        }
        else  if($tipo=='job_task_desf') {
            $titulo .= $this->job ? __('Job') . ' ' . $this->job->id . " "  . $this->task->nome : ' ';
            $this->tipo_array = array(
                'titulo'    => $titulo,
                'msg'       => __('Task') . ' ' . $this->task->nome . ' ' . __('notif.do Job') . ' ' . $this->job->nome . ' ' . __('notif.desfeita') . '!'
            );
        }
        else  if($tipo=='job_pode_concluir') {
            $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '';
            $this->tipo_array = array(
                'titulo'    => $titulo,
                'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.pode ser concluído') . '!'
            );
        }
        else  if($tipo=='job_reaberto') {
             $this->tipo_array = array(
                'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
                'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi reaberto') . '!'
             );
        }
        else  if($tipo=='job_concluido') {
             $this->tipo_array = array(
                'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
                'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi concluído') . '!'
             );
        }
        else  if($tipo=='job_concluido_freela') {
            $this->tipo_array = array(
               'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
               'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi concluído') . '!'
            );
        }

        else  if($tipo=='job_hr_solicitado') {
            $this->tipo_array = array(
            'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
            'msg'       => __('notif.Job'). ' ' . $this->job->nome . ' ' . __('notif.solicitado o HR') . '!'
            );
        }

        else  if($tipo=='job_hr_enviado') {
            $this->tipo_array = array(
            'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
            'msg'       => __('notif.Job'). ' ' . $this->job->nome . ' ' . __('notif.HR enviado') . '!'
            );
        }

       else  if($tipo=='job_concluido_freela_por_coordenador') {
            $this->tipo_array = array(
            'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
            'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi concluído pelo coordenador') . '!'
            );

            dd( $this->tipo_array );
        }
        


       else  if($tipo=='job_recusado') {
             $this->tipo_array = array(
                'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
                'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi recusado') . '!'
             );
        }
        
        
       else if($tipo=='job_recusado_freela') {
            $this->tipo_array = array(
            'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
            'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi recusado') . '!' . __('notif.Seu job foi recusado, veja os detalhes')
            );
        }

        else if($tipo=='job_recusado_admin') {
            $this->tipo_array = array(
                'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
                'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi recusado') . '!' . __('notif.O job foi recursado pelo publicador')
            );
        }

        else  if($tipo=='job_parado') {
             $this->tipo_array = array(
                'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
                'msg'       => __('notif.O Job') . ' ' . $this->job->nome . ' ' . __('notif.foi parado') . '!'
             );
        }        

        else  if($tipo=='job_freela_pega') {
             $this->tipo_array = array(
                'titulo'    => $titulo .= ' - Job ' . $this->job->id,
                'msg'       => __('notif.Um Freelancer pegou o Job') . ' ' . $this->job->nome . '!'
             );
        }


        else  if($tipo=='job_avaliacao_upload_concluido') {
            $this->tipo_array = array(
               'titulo'    => $titulo .= ' - Job ' . $this->job->id,
               'msg'       => __('notif.Novo Arquivo no Job') . ' ' . $this->job->nome . ' ' . __('notif.para avaliação') . '!'
            );
       }

       else  if($tipo=='job_revisao_upload') {
            $this->tipo_array = array(
               'titulo'    => $titulo .= ' - Job ' . $this->job->id,
               'msg'       => __('notif.Nova Revisão para o Job') . ' ' . $this->job->nome . '!'
            );
       }


       else  if($tipo=='usuario_dados_transferidos') {
            $this->tipo_array = array(
               'titulo'    => $titulo .= ' - Nova Atribuição ',
               'msg'       => __('notif.Você recebeu novas atribuiçõoes') .'!'
            );
       }

       
       else  if($tipo=='proposta_aceita') {
            $this->tipo_array = array(
            'titulo'    => $titulo .= ' - Job ' . $this->job->id,
            'msg'       => __('notif.Parabéns! Sua proposta foi aceita para o Job') . ' ' . $this->job->nome . '!'
            );
        }
    
        else  if($tipo=='proposta_aceita_publicador') {
            $this->tipo_array = array(
            'titulo'    => $titulo .= ' - Job ' . $this->job->id,
            'msg'       => __('notif.Uma proposta foi aceita para o Job') . ' ' . $this->job->nome . '!'
            );
        }

        else  if($tipo=='job_coord_proposta_aceita') {
            $this->tipo_array = array(
            'titulo'    => $titulo .= ' - Job ' . $this->job->id,
            'msg'       => __('notif.Uma proposta foi aceita para o Job') . ' ' . $this->job->nome . '!'
            );
        }

        else  if($tipo=='job_coord_pagamento_realizado') {
            $this->tipo_array = array(
            'titulo'    => $titulo .= ' - Job ' . $this->job->id,
            'msg'       => __('notif.Foi realizado o pagamento do Job') . ' ' . $this->job->nome . '!'
            );
        }


        else  if($tipo=='job_freelance_pagamento_realizado') {
            $this->tipo_array = array(
            'titulo'    => $titulo .= ' - Job ' . $this->job->id,
            'msg'       => __('notif.O pagamento do Job') . ' ' . $this->job->nome . ' '. __('notif.está em transação, logo estará liberado') . '!'
            );
        }

       

        else  if($tipo=='proposta_recusada') {
            $this->tipo_array = array(
               'titulo'    => $titulo .= ' - Job ' . $this->job->id,
               'msg'       => __('notif.Obrigado por ter participado da proposta do Job').' '. $this->job->nome.' '. __('notif.e esperamos que você consiga da próxima vez') . ' !'
            );
       }


       else  if($tipo=='img_nova') {
             $this->tipo_array = array(
                 'titulo'   => $titulo,
                 'msg'      => __('notif.Uma nova Imagem') . ' ' . $this->imagem->nome .' ' . __('notif.foi criada') . '!'
             );
        }
        else  if($tipo=='img_finalizador_vc') {
             $this->tipo_array = array(
                 'titulo'   => $titulo ,
                 'msg'      => __('notif.Você foi definido como Finalizador da Imagem') . ' ' . $this->imagem->nome . '!'
             );
        }
        else  if($tipo=='img_finalizador_outros') {
             $this->tipo_array = array(
                 'titulo'   => $titulo ,
                 'msg'      => __('notif.Novo Finalizador para Imagem') . ' ' . $this->imagem->nome . '!'
             );
        }
        else  if($tipo=='img_finalizador_removido') {
             $this->tipo_array = array(
                 'titulo'   => $titulo , 
                 'msg'      => __('notif.Finalizador removido da Imagem') . ' ' . $this->imagem->nome . '!'
             );
        }
        else  if($tipo=='prj_novo') {
             $this->tipo_array = array(
                'titulo'    => $titulo , 
                'msg'       => __('notif.O Projeto') . ' ' . $this->projeto->nome . ' ' . __('notif.foi criado') . '!'
             );
        }

        else  if($tipo=='prj_coord') {
             $this->tipo_array = array(
                'titulo' => $titulo, 
                'msg' => __('notif.Você foi definido como Coordenador para o Projeto') . ' ' . $this->projeto->nome . '!'
             );
        }

        else  if($tipo=='usuario_encerra_conta') {
             $this->tipo_array = array(
                'titulo' => $titulo.= ' - Encerramento de conta', 
                'msg' => __('notif.O Usuário pediu para encerrar a conta') . '!'
             );
        }      

        // dd( $tipo);
        // dd($this->tipo_array);
        return $mensagem = $this->tipo_array;
    }

}
