<?php

namespace App\Models;
use App\User;
use App\Models\Job;
use App\Models\Task;
use App\Models\Projeto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


class PaymentNotification extends Model
{
    //
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
      

        if($tipo=='job_concluido_freela') {
             $this->tipo_array = array(
                'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
                'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi concluído') . '!' . __('notif.Logo seu pagamento será liberado')
             );
        }

        if($tipo=='job_concluido_freela_por_coordenador') {
            $this->tipo_array = array(
               'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
               'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi concluído pelo coordenador') . '!' . __('notif.Logo o pagamento será liberado')
            );
       }

        if($tipo=='job_concluido_admin') {
            $this->tipo_array = array(
               'titulo'    => $titulo .= $this->job ? __('Job') . ' ' . $this->job->id : '',
               'msg'       => __('notif.O Job'). ' ' . $this->job->nome . ' ' . __('notif.foi concluído') . '!' . __('notif.O freelancer já pode receber o pagamento')
            );
       }

       

        // dd($this->tipo_array);
        return $mensagem = $this->tipo_array;
    }
}
