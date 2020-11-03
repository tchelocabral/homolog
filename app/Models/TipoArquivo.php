<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoArquivo extends Model
{
    protected $table = 'tipos_arquivos';

    protected $fillable = [
        'nome',
        'descricao'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function midias() {
        
        return $this->hasMany('App\Models\Midia');
    }

}
