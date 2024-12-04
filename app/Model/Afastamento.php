<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Afastamento extends BaseModel
{
    use SoftDeletes;

    protected $table = 'afastamento';

    // Fields that are mass assignable
    protected $fillable = [
        'elegivel_id',
        'data_inicio',
        'data_final',
        'modalidade_afastamento_id',
        'justificativa',
        'deletado_em',
        'descricao_modalidade',
        'criado_carga',
    ];

    // Define data type for each column
    protected $casts = [
        'data_inicio' => 'date',
        'data_final' => 'date',
        'criado_carga' => 'boolean',
    ];

    // Indicates if the model should have timestamp columns
    public $timestamps = false;

    // Relationships
    public function elegivel()
    {
        return $this->belongsTo(Elegivel::class);
    }

    public function modalidadeAfastamento()
    {
        return $this->belongsTo(ModalidadeAfastamento::class, 'modalidade_afastamento_id');
    }
}
