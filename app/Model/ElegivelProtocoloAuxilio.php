<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class ElegivelProtocoloAuxilio extends BaseModel
{
    use SoftDeletes;

    // Define the table associated with the model
    protected $table = 'elegivel_protocolo_auxilio';

    // Define the primary key
    protected $primaryKey = 'id';

    // Fields that are mass assignable
    protected $fillable = [
        'elegivel_id',
        'protocolo_auxilio_id',
        'competencia_inicio',
    ];

    // Fields that should be cast to native types
    protected $casts = [
        'competencia_inicio' => 'date',
    ];

    // Disable default Laravel timestamps
    public $timestamps = false;

    /**
     * Boot method to handle automatic timestamps for creation and updates.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->criado_em = Carbon::now();
        });

        static::updating(function ($model) {
            $model->atualizado_em = Carbon::now();
        });
    }

    /**
     * Relationship: Belongs to Elegivel
     */
    public function elegivel()
    {
        return $this->belongsTo(Elegivel::class);
    }

    /**
     * Relationship: Belongs to ProtocoloAuxilio
     */
    public function protocoloAuxilio()
    {
        return $this->belongsTo(ProtocoloAuxilio::class);
    }
}
