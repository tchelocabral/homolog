<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{

    protected $table = 'users_meta';

     
    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    protected $dates = [
        'created_at',
        'updated_at'

    ];
}
