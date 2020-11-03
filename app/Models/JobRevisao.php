<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRevisao extends Model
{
    protected $table  = 'jobs_revisoes';

    protected $fillable = [
        'id', 'job_id', 'avaliador_id', 'user_id', 'numero_revisao', 'nome', 'src', 'data_conclusao', 'observacoes', 'status', 'imagem_revisao', 'data_entrega'
    ];

    protected $dates = [
        'data_conclusao',
        'created_at',
        'updated_at'
    ];

    // Relacionamentos
    public function job() {
         return $this->belongsTo(Job::class);
    }

    public function avaliador() {
        return $this->belongsTo(\App\User::class, 'avaliador_id');
    }

    public function marcadores(){
        return $this->hasMany(JobRevisaoMarcadores::class, 'job_revisao_id')->with('midias');
    }

    public function tasksRevisao() {
        return $this->hasMany(JobsRevisoesTasks::class, 'job_revisao_id')->orderBy('ordem');
    }

    // Cálculos de Conclusão
    // public function concluido() {
        
    //     $total     = $this->tasksRevisao()->count();
    //     $concluido = $this->tasksRevisao()->where('status', 1)->count();

    //     return $concluido||$total>0 ? number_format($concluido/$total*100,0) : 0;

    //     // return $concluido/$total*100;
    // }

    //
}
