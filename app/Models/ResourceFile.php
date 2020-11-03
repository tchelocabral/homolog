<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceFile extends Model
{
    //
    protected $table = 'resources_files';

    protected $fillable = [
        'nome',
        'descricao',
        'arquivo',
    ];


    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
