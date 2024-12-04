<?php

namespace App\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

class FolhaPagamento extends BaseModel
{
    use SoftDeletes;

    protected $table = 'folha_pagamento';

    protected $primaryKey = 'id';

    protected $fillable = [
        'modalidade_folha_id',
        'competencia',
        'descricao',
        'status_folha_pagamento_id',
        'data_previa',
        'data_fechamento',
        'data_envio_banco',
        'data_arquivo_retorno',
        'data_pagamento',
        'abate_teto'
    ];

    protected $dates = [
        'competencia',
        'data_previa',
        'data_fechamento',
        'data_envio_banco',
        'data_arquivo_retorno',
        'data_pagamento',
    ];

    public $timestamps = false;

    public function fluxoCaixa(){
        return $this->hasMany(FluxoCaixa::class);
    }

    public function modalidadeFolha()
    {
        return $this->belongsTo(ModalidadeFolha::class);
    }

    public function statusFolhaPagamento()
    {
        return $this->belongsTo(StatusFolhaPagamento::class);
    }

    // Optionally, you can define a scope to filter by deleted_at
    public function scopeRecente($query)
    {
        return $query->orderBy('id', 'DESC'); // If using soft deletes
    }

}
