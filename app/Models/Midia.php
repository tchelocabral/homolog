<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Midia extends Model
{

    protected $table = 'midias';

    protected $fillable = [
        'id',
        'tipo_arquivo_id',
        'nome',
        'descricao',
        'nome_original',
        'nome_arquivo',
        'caminho'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    public function tipo_arquivo() {

        return $this->belongsTo('App\Models\TipoArquivo');
    }

    public function projeto() {

        return $this->belongsToMany(Projeto::class, 'midias_projetos', 'midia_id', 'projeto_id')->first();
    }

    public function projetos() {

        return $this->belongsToMany(Projeto::class, 'midias_projetos', 'midia_id', 'projeto_id');
    }

    public function imagens() {

        return $this->belongsToMany(Imagem::class, 'midias_imagens', 'midia_id', 'imagem_id');
    }

}
