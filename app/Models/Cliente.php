<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cliente extends Model {

    use SoftDeletes;

    protected $casts = [ 
        'dados_bancarios' => 'array', 
    ];

    protected $fillable = [       
        'user_id',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'nome_contato',
        'email_contato',
        'dados_bancarios',
        'logo'
    ];

    public function user() {

        return $this->belongsTo(\App\User::class);
    }

    public function projetos() {

        return $this->hasMany(Projeto::class)->with('faturamentos');
    }

    public function primeiro_contato() {

        return Contato::where('dono_id', '=', $this->id)->where('dono_tipo', '=', 'clientes')->get()->first();
    }

    public function contatos() {

        return Contato::where('dono_id', '=', $this->id)->where('dono_tipo', '=', 'clientes')->get();
    }

    public function primeiro_endereco() {

        return Endereco::where('dono_id', $this->id)->where('dono_tipo', '=', 'clientes')->get()->first();
    }
    public function enderecos() {
   
        return Endereco::where('dono_id', $this->id)->where('dono_tipo', '=', 'clientes')->get();
    }

    public function faturamentos() {
       
        return $this->hasMany(ClienteFaturamento::class);   
    }
    

    /**
     * Set json dados_bancarios.
     *
     * @param  string  $value
     * @return void
     */
    public function setDadosBancariosAttribute($value) {

        $this->attributes['dados_bancarios'] = json_encode($value);
    }

    /**
     * Get array dados_bancarios.
     *
     * @param  string  $value
     * @return void
     */
    public function getDadosBancariosAttribute() {

        return json_decode($this->attributes['dados_bancarios']);
    }

}
