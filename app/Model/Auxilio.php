<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Auxilio extends BaseModel
{
    use SoftDeletes;

    protected $table = 'auxilio';

    protected $primaryKey = 'id';

    protected $fillable = [
        'rubrica_id',
        'valor',
        'competencia_inicio',
        'retroativo',
        'protocolo_auxilio_id',
    ];

    protected $casts = [
        'valor' => 'double',
        'competencia_inicio' => 'date',
        'retroativo' => 'boolean',
    ];

    // Disable default Laravel timestamps
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->criado_em = Carbon::now();
        });

        static::updating(function ($model) {
            // Nao alterar quando realizar o delete do registro
            if (! $model->deletado_em) {
                $model->atualizado_em = Carbon::now();
            }
        });
    }


    public function rubrica()
    {
        return $this->belongsTo(Rubrica::class);
    }

    public function protocoloAuxilio()
    {
        return $this->belongsTo(ProtocoloAuxilio::class);
    }
}
