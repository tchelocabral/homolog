<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobParado extends Model
{
	protected $table = "jobs_parados";

	protected $fillable = [
        'id', 
        'job_id', 
        'user_id', 
        'delegado_id', 
        'motivo'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    //
}
