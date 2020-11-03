<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCandidatura extends Model
{
    //
    protected $table = "jobs_candidaturas";

    // 0-Proposta, 1-Candidatura
    // 0-aberto, 1-aceito, 2-recusado, 3-cancelado, 4-user_sem_slot, 5-expirado 


	protected $fillable = [
        'id', 
        'job_id', 
        'status',
        'user_id',
        'valor',
        'observacao', 
        'tipo', // 0 - proposta / 1 - candidatura
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    public function user() {
        return $this->belongsTo(\App\User::class);
    }

    
    public function job() {
        return $this->belongsTo(Job::class);
    }

}
