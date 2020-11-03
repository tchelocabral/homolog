<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisaoMarcadorMidia extends Model {

    protected $table = 'imagens_revisoes_marcadores_midias';

    protected $fillable = [
        'id', 'imagem_revisoes_marcador_id', 'src', 'caminho_arquivo'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    // midia pertence a um marcador
    public function marcador() {

        return $this->belongsTo(\App\RevisaoMarcador::class, 'imagem_revisoes_marcador_id');
    }

} // end class
