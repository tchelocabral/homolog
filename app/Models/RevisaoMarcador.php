<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisaoMarcador extends Model {
	
    
    protected $table = 'imagens_revisoes_marcadores';

    protected $fillable = [
        'id', 'imagem_revisao_id', 'x', 'y', 'texto', 'ordem', 'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


	// o marcador pertence a uma revisão
    public function revisao() {

        return $this->belongsTo(Revisao::class, 'imagem_revisao_id');
    }

    // o marcador tem várias midias
    public function midias() {

        return $this->hasMany(RevisaoMarcadorMidia::class, 'imagem_revisoes_marcador_id');
    }

} // end class
