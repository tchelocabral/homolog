<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoImagem extends Model{

    protected $table = 'grupos_imagens';

    protected $fillable = [
        'nome',
        'descricao',
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

    public function tipos_imagens() {

        $this->hasMany(\App\ImagemTipo::class);
    }

}
