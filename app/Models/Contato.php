<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // protected $dateFormat = 'U';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
    ];

    protected $fillable = [
        'dono_id',
        'dono_tipo',
        'nome',
        'email',
        'email_alternativo',
        'tel',
        'tel_alternativo',
        'cel',
        'cel_alternativo',
        'observacoes',
        'meta',
    ];

}
