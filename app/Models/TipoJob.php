<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TipoJob extends Model {
    //
    use SoftDeletes;
    
    protected $table = 'tipo_jobs';

    protected $fillable = [
        'nome',
        'descricao',
        'boas_praticas',
        'campos_personalizados',
        'gera_custo',
        'revisao',
        'finalizador',
        'imagem',
        'solicite_hr'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'campos_personalizados' => 'array',
        'gera_custo' => 'boolean',
        'revisao' => 'boolean',
        'finalizador' => 'boolean',
        'solicite_hr' => 'boolean'
    ];

    public function midias() {
        return $this->belongsToMany(Midia::class, 'midias_tipojobs', 'midia_id', 'tipojob_id')->with('tipo_arquivo');
    }

    public function tasks() {
        return $this->belongsToMany(Task::class, 'tipo_job_tasks', 'tipo_job_id', 'task_id')->orderBy('tipo_job_tasks.ordem')->withTimestamps();
    }

    public function jobs() {
        return $this->hasMany(Job::class, 'tipojob_id');
    }
}
