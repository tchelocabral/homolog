<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteFaturamento extends Model
{

    protected $table = 'clientes_faturamentos';

    protected $fillable = [       
        'cliente_id', 'razao_social', 'nome_fantasia',  'cnpj',  'apelido',  'nome_contato', 'email_contato'
    ];

    public function cliente() {

        return $this->belongsTo(Cliente::class);
    }

    public function projetos(){
        return $this->belongsToMany(Projeto::class, 'projetos_faturamentos', 'cliente_faturamento_id', 'projeto_id');
    }

}
