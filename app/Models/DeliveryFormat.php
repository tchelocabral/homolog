<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryFormat extends Model {

    use SoftDeletes;
    
    protected $table = 'delivery_format';

    protected $fillable = [
        'nome',
        'descricao',
        'campos_personalizados',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'campos_personalizados' => 'array',

    ];

    public function jobs() {
        return $this->hasMany(Job::class, 'deliveryformat_id');
    }

    //
}
