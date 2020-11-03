<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    //
     //
     protected $table = 'tutoriais';

     protected $fillable = [
         'nome',
         'descricao',
         'url',
     ];
 

     protected $dates = [
         'created_at',
         'updated_at'
     ];
}
