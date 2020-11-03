<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $table = 'tasks';

    protected $fillable = [
        'nome',
        'descricao',
        'notification'
        // 'porcentagem_individual'
    ];

    protected $casts = [
        'notification' => 'boolean'
       
    ];


    protected $dates = [
        'created_at',
        'updated_at'
    ];

    // public function tipo_jobs() {
    //     return $this->belongsToMany(TipoJob::class, 'tipo_job_tasks', 'tipo_job_id', 'task_id');
    // }

}
