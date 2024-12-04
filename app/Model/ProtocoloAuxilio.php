<?php

namespace App\Model;

class ProtocoloAuxilio extends BaseModel
{
    // Define the table associated with the model
    protected $table = 'protocolo_auxilio';

    // Define the primary key
    protected $primaryKey = 'id';

    // Fields that are mass assignable
    protected $fillable = [
        'nome',
        'descricao',
    ];

    // Disable default Laravel timestamps
    public $timestamps = false;
}
