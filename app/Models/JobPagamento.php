<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPagamento extends Model
{
    
    
    //Tudo que é relativo ao pagamento do job
    protected $table = "jobs_pagamentos";

	protected $fillable = [
        'id', 
        'job_id', 
        'status',
        'pago_em',
        'chave_de_pgto', 
        'observacao',
        'valor',
        'taxa_transacao',
        'user_id',
        'prazo_pagamento',
        'metodo_pagamento',
        'tipo_pagamento',
        'comprovante_pagamento',
        'confirmador_id',   
        'confirmado_em',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    #array com os metodos de pagamentos dos jobs
    public static $metodo_pagamento_array = array(
        '0' => 'Pay Pal',
        '1' => 'Transferencia Bancaria',
        '2' => 'Pagar.me',
        
        'paypal'                =>  '0',
        'tranferenciabancaria'  =>  '1',
        'pagarme'               =>  '2',
    );
    
     #array com os metodos de pagamentos dos jobs
    public static $metodo_pagamento_array_simples = array(
        '0' => 'Pay Pal',
        '1' => 'Transferencia Bancaria',
        '2' => 'Pagar.me',
    );

    public function job(){
        return $this->belongsTo(Job::class, 'job_id')->with(['pagamentoEfetivado','jobsPagamentosPos']);
    }

}
