<?php

namespace App\Listeners;

use App\Events\CargaProcessada;
use App\Services\RegistrarAuxiliosService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class RegistrarAuxilios implements ShouldQueue
{
    protected $service ;

    public function __construct(RegistrarAuxiliosService $service)
    {
        $this->service = $service;
    }

    public function handle(CargaProcessada $event)
    {
        Log::info('Inicio do registro dos creditos');
        $this->service->executar($event->orgaoAdministracaoId);
    }
}
