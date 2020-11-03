<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revisao extends Model{


    protected $table  = 'imagens_revisoes';

    protected $fillable = [
        'id', 'imagem_id', 'avaliador_id', 'numero_revisao', 'nome', 'src', 'data_conclusao', 'observacoes', 'status'
    ];

    protected $dates = [
        'data_conclusao',
        'created_at',
        'updated_at'
    ];

    // Relacionamentos
    public function job() {

        // return $this->belongsTo(Job::class);
    }

    public function avaliador() {

        return $this->belongsTo(\App\User::class, 'avaliador_id');
    }

    public function imagem() {

        return $this->belongsTo(Imagem::class);
    }

    public function marcadores(){
        return $this->hasMany(RevisaoMarcador::class, 'imagem_revisao_id')->with('midias');
    }

} // end class
