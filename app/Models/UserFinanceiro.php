<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserFinanceiro extends Model
{
    
    protected $fillable = [
        'model_id',
        'model_type',
        'de_id',
        'para_id',
        'pagador_id',
        'centro_de_custo_id',
        'categoria_de_custo_id',
        'taxa',
        'dados_bancarios',
        'valor_de', 
        'valor_para', 
        'valor_taxa', 
        'descricao',
        'observacoes',
        'doc_url',
        'status',
        'pago_em',
        'para_pagador_id',

    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'pago_em',       
        

    ];

    protected $casts = [

    ];



    public function de(){
        return $this->belongsTo(User::class, 'de_id');
    }

    public function para(){
        return $this->belongsTo(User::class, 'para_id');
    }

    public function pagador(){
        return $this->belongsTo(User::class, 'pagador_id');
    }

    


    public function financeiro(){
        return morphTo();
    }

    public function job(){
        return $this->belongsTo(Job::class, 'model_id')->with(['pagamentoEfetivado','jobsPagamentosPos']);
    }

    public function jobPagamento(){
        return $this->belongsTo(JobPagamento::class, 'job_id','model_id');
    }


}
