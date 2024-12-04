<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Elegivel extends BaseModel
{
    use SoftDeletes;

    protected $table = 'elegivel';
    protected $fillable = [
        'pessoa_id',
        'cargo_id',
        'orgao_administracao_id',
        'modalidade_elegivel_id',
        'data_ingresso',
        'data_aposentadoria',
        'percentual_maximo_estorno',
        'siape',
        'descricao_modalidade_outros',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function orgaoAdministracao()
    {
        return $this->belongsTo(OrgaoAdministracao::class);
    }

    public function modalidadeElegivel()
    {
        return $this->belongsTo(ModalidadeElegivel::class);
    }

    public function contraCheques()
    {
        return $this->hasMany(ContraCheque::class);
    }

    public function elegivelProtocoloAuxilio()
    {
        return $this->hasMany(ElegivelProtocoloAuxilio::class);
    }

    public function afastamentos()
    {
        return $this->hasMany(Afastamento::class)->orderBy('id', 'DESC');
    }
}
