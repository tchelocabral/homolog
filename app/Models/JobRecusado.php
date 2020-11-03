<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRecusado extends Model
{
	protected $table = "jobs_recusados";

	protected $fillable = [
        'id', 
        'job_id', 
        'user_id', 
        'delegado_id', 
        'causa'
    ];

    //
}
