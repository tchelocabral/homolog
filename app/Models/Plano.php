<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Permission;


class Plano extends Model
{

 //   use SoftDeletes;


    protected $fillable = [       
        'nome',
        'valor',
        'observacao',
        'sort_order',
        'status'
    ];
    
    public function users() {
        return $this->hasMany(\App\User::class);
    }


    public function permissions() {

        return $this->belongsToMany(Permission::class, 'planos_permissoes', 'plano_id', 'permission_id')->select();
    }

    public function permissionsRole($id_role) {

        return $this->belongsToMany(Permission::class, 'planos_permissoes')->where('role_id',$id_role)->select();
    }




    public function roles() {
        return $this->belongsToMany(Role::class, 'planos_permissoes', 'plano_id', 'role_id')->select();
    }


    public function permission() {
        return $this->belongsToMany(Permission::class, 'planos_permissoes', 'plano_id', 'permission_id')->withPivot('role_id');
    }


}
