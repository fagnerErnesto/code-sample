<?php

namespace App\Events;

use App\Model\Carga;
use Illuminate\Support\Facades\Log;

class CargaProcessada extends Event
{
    public $orgaoAdministracaoId;
    public function __construct(int $orgaoAdministracaoId)
    {
        Log::info('Evento de carga de créditos iniciado');
        $this->orgaoAdministracaoId = $orgaoAdministracaoId;
    }
}
