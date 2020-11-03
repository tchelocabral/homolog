<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // protected $dateFormat = 'U';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
    ];

    protected $fillable = [
        'dono_id',
        'dono_tipo',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'zona',
        'cidade',
        'uf',
        'pais',
        'meta',
        'lat',
        'long',
    ];


    public function clientes() {
 
        return $this->belongsTo(Cliente::class)->where('dono_tipo', '=', 'clientes')->get();
    }

}
