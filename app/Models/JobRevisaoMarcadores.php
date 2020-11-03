<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRevisaoMarcadores extends Model
{
    //   
    protected $table = 'jobs_revisoes_marcadores';

    protected $fillable = [
        'id', 'job_revisao_id', 'x', 'y','texto', 'src_arquivo_final', 'caminho_arquivo'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    // o marcador tem várias midias
    public function midias() {

        return $this->hasMany(JobRevisaoMarcadoresMidias::class, 'job_revisao_marcador_id');
    }
}
