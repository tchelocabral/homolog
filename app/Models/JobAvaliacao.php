<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobAvaliacao extends Model
{
	protected $table = "jobs_avaliacoes";

	protected $fillable = [
        'id', 
        'job_id', 
        'imagem',
        'observacoes', 
    ];

    public function job() {
        return $this->belongsTo(Job::class);
   }
}
