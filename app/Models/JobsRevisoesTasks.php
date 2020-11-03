<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobsRevisoesTasks extends Model
{
    //
    protected $table = 'jobs_revisoes_tasks';

    protected $fillable = [
        'task_name',
        'task_description',
        'status',
        'job_revisao_id',
        'user_id',
        'ordem'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function tasksJobRevisao() {

        return $this->belongsTo(JobRevisao::class);
    }
}
