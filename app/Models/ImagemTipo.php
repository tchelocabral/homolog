<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemTipo extends Model
{

    protected $table = 'imagens_tipos';

    protected $fillable = [
        'nome',
        'grupo_id',
        'descricao',
        'valor',
        'observacoes',
        'campos_personalizados'
    ];

    protected $casts = [
        'campos_personalizados' => 'array'
    ];

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
     * Seta o nome com maiúscula.
     *
     * @param  string  $value
     * @return void
     */
    
    // public function setNomeAttribute($value) {

    //     $this->attributes['nome'] = strtoupper($value);
    // }

    public function grupo() {

        return $this->belongsTo(\App\Models\GrupoImagem::class);
    }

    public function imagens() {

        return $this->hasMany('App\Models\Imagem');
    }

}
