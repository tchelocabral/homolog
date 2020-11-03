<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Imagem extends Model
{

    use SoftDeletes;
    
    protected $table = 'imagens';

    protected $fillable = [
        'projeto_id',
        'imagem_tipo_id',
        'finalizador_id',
        'nome',
        'descricao',
        'campos_personalizados',
        'observacoes',
        'data_inicio',
        'data_revisao',
        'data_entrega',
        'valor',
        'dados_bancarios',
        'status',
    ];

    protected $casts = [
        'campos_personalizados' => 'array'
    ];

    protected $dates = [
        'data_inicio',
        'data_revisao',
        'data_entrega',
        'created_at',
        'updated_at'
    ];

    // Set Valor Format
    // function setValorAttribute($value){
    //     $this->attribute['valor'] = str_replace(",",".", str_replace([".", "R$ "], "", $value) );
    // }

    /**
     * Todos os relacionamentos a serem "tocados".
     *
     * @var array
     */
    protected $touches = ['projeto'];

    // Relacionamentos
    public function projeto() {

        return $this->belongsTo(\App\Models\Projeto::class)->with(['cliente', 'coordenador']);
    }

    public function finalizador() {

        return $this->belongsTo(\App\User::class, 'finalizador_id');
    }

    public function tipo() {

        return $this->belongsTo(ImagemTipo::class, 'imagem_tipo_id')->with('grupo');
    }

    public function arquivos() {

        return $this->belongsToMany(Midia::class, 'midias_imagens', 'imagem_id', 'midia_id');
    }

    public function jobs() {

        return $this->belongsToMany(Job::class, 'jobs_imagens', 'imagem_id', 'job_id')->with('tasks')->with('tipo');
    }

    public function getStatusRevisaoAttribute($value){
        if(!is_null($value)){
            // dd(TipoJob::find($value)->nome);
            $tp = TipoJob::find($value);
            return $tp ? $tp->nome : '';
        }else{
            return $value;
        }
    }

    // Cálculos de Conclusão
    public function concluido() {

        $total_jobs      = $this->jobs()->count();
        $progresso_jobs  = 0;
        
        foreach ($this->jobs as $j){
            $progresso_jobs += $j->concluido();
        }

        return $progresso_jobs <= 0 || $total_jobs <= 0 ? 0 : number_format($progresso_jobs / $total_jobs, 0) ;
    }

    public function situacao(){
        switch ($this->status) {
            case 0:
                return 'Nova';
                break;
            case 1:
                return 'Em Andamento';
                break;
            case 2:
                return 'Concluída';
                break;
            case 3:
                return 'Recusada';
                break;
            default:
                return 'Novo';
                break;
        }
    }

    public function revisoes() {

        return $this->hasMany(Revisao::class)->with('marcadores');
    }
}
