<?php

namespace App\Models;
use App\Models\Job;
use App\User;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    //

    protected $table = "avaliacoes";

    protected $fillable = [
        'id', 
        'model_type', 
        'model_id', 
        'avaliador_id', 
        'avaliado_id', 
        'observacoes', 
        'nota'];

    
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    
    public function avaliador() {

        return $this->belongsTo(User::class, 'avaliador_id');
    }

    public function avaliado() {
        return $this->belongsTo(User::class, 'avaliado_id');    
    }

    public function modelable () {
        return $this->morphTo()->with(['avaliador','avaliado']);
    } 

}
