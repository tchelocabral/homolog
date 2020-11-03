<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habilidade extends Model{


    protected $fillable = [       
        'nome',
        'descricao',
        'teste',
        'cor'  
    ];



    public function nome_cor(){
    	switch ($this->cor) {
    		case 'primary':
    			return 'Azul';
    			break;
    		
    		case 'info':
    			return 'Azul Claro';
    			break;

    		case 'secondary':
    			return 'Cinza';
    			break;

    		case 'warning':
    			return 'Laranja';
    			break;

    		case 'dark':
    			return 'Preto';
    			break;

    		case 'light':
    			return 'Transparente';
    			break;

    		case 'success':
    			return 'Verde';
    			break;

    		case 'danger':
    			return 'Vermelho';
    			break;

    		default:
    			return 'Não Informado';
    			break;
    	}
    }
}
