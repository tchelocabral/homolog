<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserConta extends Model
{
    protected $table = 'user_contas';

    
    protected $fillable = [
        'user_id',
        'banco',
        'agencia',
        'conta',
        'tipo_conta',
        'cpf_titular',
        'observacoes',
    ];

    protected $dates = [
        'created_at',
        'updated_at'

    ];

    protected $casts = [

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
