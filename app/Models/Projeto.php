<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Projeto extends Model
{

    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'cliente_id',
        'coordenador_id',
        'descricao',
        'cnpj',
        'observacoes',
        'data_previsao_entrega',
        'data_entrega',
        'porcentagem_conclusao',
        'valor',
        'dados_faturamento',
        'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'data_previsao_entrega',
        'data_entrega',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dados_faturamento' => 'array'
    ];

    //0-Novo, 1-Em Andamento, 2-Concluído, 3-Recusado

    public static $NOVO         = 0; 
    public static $EMANDAMENTO  = 1; 
    public static $CONCLUIDO   = 2; 
    public static $RECUSADO    = 3; 

    public static $status_array = array(
        '0' => 'Novo',
        '1' => 'Em Andamento',
        '2' => 'Concluído',
        '3' => 'Recusado',

        'novo'          =>  '0',
        'emandamneto'   =>  '1',
        'concluido'     =>  '2',
        'recusado'      =>  '3',

    );

    // Relacionamentos
    public function cliente() {

        return $this->belongsTo(Cliente::class);
    }

    public function coordenador() {

        return $this->belongsTo(\App\User::class, 'coordenador_id');
    }

    public function imagens() {

        return $this->hasMany(Imagem::class)->with(['tipo', 'jobs', 'arquivos', 'finalizador']);
    }

    public function imagensComR00() {

        return $this->hasMany(Imagem::class)->orderBy('nome', 'asc')->whereNotNull('data_revisao')->get();
    }

    public function arquivos() {

        return $this->belongsToMany(Midia::class, 'midias_projetos', 'projeto_id', 'midia_id')->with('imagens');
    }

    public function faturamentos() {

        return $this->belongsToMany(ClienteFaturamento::class, 'projetos_faturamentos', 'projeto_id', 'cliente_faturamento_id');
    }


    // Cálculos de Conclusão
    public function concluido() {

        $total_imagens = $this->imagens()->count();
        $progresso_imgs  = 0;
        
        foreach ($this->imagens as $img) {
            $progresso_imgs += $img->concluido();
        }
        return $progresso_imgs <= 0 || $total_imagens <= 0 ? 0 : number_format($progresso_imgs / $total_imagens, 0);
    }

}
