<?php

namespace App\Services;

use App\Enums\ModalidadeAfastamentoEnum;
use App\Model\Auxilio;
use App\Model\Credito;
use App\Model\Elegivel;
use App\Model\FolhaPagamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrarAuxiliosService
{
    private $competencia;
    private $creditoAuxilios = [];
    private $modalidadeAfastamentoAceito = [
        ModalidadeAfastamentoEnum::CESSAO,
        ModalidadeAfastamentoEnum::SUSPENSAO,
    ];
    /**
     * @var Auxilio
     */
    private $auxilios;

    public function executar(int $orgaoAdministracaoId)
    {
        $elegiveis = Elegivel::with(['elegivelProtocoloAuxilio', 'afastamentos'])->where(['orgao_administracao_id' => $orgaoAdministracaoId])->get();
        $this->auxilios = Auxilio::all();
        $this->competencia = FolhaPagamento::recente()->first()->competencia->addMonth();

        foreach($elegiveis as $elegivel) {
            $this->montarCreditoAuxilios($elegivel);
        }

        try {
            DB::transaction(function () {
                Credito::insert($this->creditoAuxilios);
            });
        } catch (\Exception $exception) {
            Log::error(
                'Erro ao registrar créditos de auxílios para o orgão: ' . $orgaoAdministracaoId,
                [$exception->getMessage(), $exception->getTrace()]
            );

            throw $exception;
        }
    }

    private function montarCreditoAuxilios(Elegivel $elegivel)
    {
        $afastamentos = $elegivel->afastamentos;
        $afastamentoAtivo = !$afastamentos->isEmpty() && $afastamentos->first()->where('data_final', null);
        $modalidadeAfastamentoId = $afastamentos->first->where('data_final', null)->modalidade_afastamento_id ?? null;

        if ($afastamentoAtivo && !in_array($modalidadeAfastamentoId, $this->modalidadeAfastamentoAceito) ) {
            return;
        }

        foreach ($this->auxilios as $auxilio) {
            // Criar credito para elegivel ativo sem necessidade de protocolo
            $this->tratarCreditoElegivelAtivo($auxilio, $elegivel);

            // Criar credito de elegivel que tenha protocolo de auxilio ativo
            $this->tratarCreditoElegivelPrototocolo($auxilio, $elegivel);
        }
    }

    private function creditoFactory(int $elegivelId, Auxilio $auxilio): array
    {
        return  [
            'elegivel_id' => $elegivelId,
            'competencia_referencia' => $this->competencia,
            'competencia_inicial_processamento' => $this->competencia,
            'extraordinario' => true,
            'rubrica_id' => $auxilio->rubrica_id,
            'valor_total' => $auxilio->valor,
            'justificativa' => $auxilio->justificativa,
        ];
    }

    private function tratarCreditoElegivelAtivo($auxilio, Elegivel $elegivel): void
    {
        if (!$auxilio->protocolo_auxilio_id && !$elegivel->data_aposentadoria) {
            $this->creditoAuxilios[] = $this->creditoFactory($elegivel->id, $auxilio);
        }
    }

    private function tratarCreditoElegivelPrototocolo($auxilio, Elegivel $elegivel): void
    {
        $protocoloElegivel = $elegivel->elegivelProtocoloAuxilio
            ->pluck('competencia_inicio', 'protocolo_auxilio_id')
            ->toArray();

        if (
            array_key_exists($auxilio->protocolo_auxilio_id, $protocoloElegivel)
            && $protocoloElegivel[$auxilio->protocolo_auxilio_id]->isBefore($this->competencia)
        ) {
            $this->creditoAuxilios[] = $this->creditoFactory($elegivel->id, $auxilio);
        }
    }
}
