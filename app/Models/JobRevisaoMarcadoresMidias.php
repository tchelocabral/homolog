<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRevisaoMarcadoresMidias extends Model
{
    protected $table = 'jobs_revisoes_midias';

    protected $fillable = [
        'id', 'job_revisao_marcador_id', 'src', 'caminho_arquivo'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    // midia pertence a um marcador
    public function marcador() {


        return $this->belongsTo(\App\JoboMarcadores::class, 'job_revisoes_marcador_id');
    }
}
