<?php

namespace App\Models;
use App\User;
use App\Models\Cliente;
use App\Models\Imagem;
use App\Models\Job;
use App\Models\Task;
use App\Models\Projeto;

use Illuminate\Database\Eloquent\Model;

class UserNotifications extends Model
{
	protected $cliente;
	protected $imagem;
	protected $job;
	protected $task;
	protected $projeto; 
	protected $tipo;
    //


    public function __construct($cliente, $imagem, $job, $task, $projeto, $tipo) {
        //
        $this->cliente = $user;
        $this->imagem = $rota;
        $this->job = $tipo;
        $this->task = $nome_obj;
        $this->projeto = $nome_obj;
        $this->tipo = $tipo;

    }
}
