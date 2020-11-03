<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\User;

class Comment extends Model
{
	//
	use SoftDeletes;
    
    protected $fillable = [
        'id', 
        'parent_id', 
        'user_id',
        'commentable_type', 
        'commentable_id', 
        'descricao' 

    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

     public function commentable()
    {
        return $this->morphTo()->with(['respostas','user']);
    }

    public function respostas()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user');
    }


    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    
}
