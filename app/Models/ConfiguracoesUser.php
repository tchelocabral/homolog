<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ConfiguracoesUser extends Model
{
    protected $table = "configuracoes_user";

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'user_id',
        'chave',
        'valor',
        'id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
