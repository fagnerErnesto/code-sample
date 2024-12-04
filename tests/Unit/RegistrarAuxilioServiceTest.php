<?php

namespace Unit\Services;

use App\Enums\ModalidadeAfastamentoEnum;
use App\Model\Afastamento;
use App\Model\Auxilio;
use App\Model\Elegivel;
use App\Model\ElegivelProtocoloAuxilio;
use App\Model\FolhaPagamento;
use App\Model\ProtocoloAuxilio;
use App\Services\RegistrarAuxiliosService;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseTransactions;
use TestCase;


class RegistrarAuxilioServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Carbon
     */
    protected $competencia;

    public function setUp()
    {
        parent::setUp();

        $this->competencia = Carbon::now()->subMonth(2)->firstOfMonth();
        factory(FolhaPagamento::class)->create(['competencia' => $this->competencia]);
    }

    public function testRegistraAuxilioParaAtivo()
    {
        $elegivel = factory(Elegivel::class)->create(['data_aposentadoria' => null, 'orgao_administracao_id' => 4]);
        factory(Auxilio::class)->create(['protocolo_auxilio_id' => null]);

        app(RegistrarAuxiliosService::class)->executar(4);

        $this->seeInDatabase('credito', [
            'elegivel_id' => $elegivel->id,
            'extraordinario' => true,
        ]);
    }

    public function testNaoRegistraAuxilioParaAposentadoSemProtocolo()
    {
        $elegivel = factory(Elegivel::class)->create(['data_aposentadoria' => Carbon::now()->subYear(5), 'orgao_administracao_id' => 4]);
        factory(Auxilio::class)->create(['protocolo_auxilio_id' => null]);

        app(RegistrarAuxiliosService::class)->executar(4);

        $this->missingFromDatabase('credito', [
            'elegivel_id' => $elegivel->id,
            'extraordinario' => true,
        ]);
    }

    public function testRegistraAuxilioParaAposentado()
    {
        $elegivel = factory(Elegivel::class)->create([
            'data_aposentadoria' => Carbon::now()->subYear(5),
            'orgao_administracao_id' => 4
        ]);
        $protocolo = factory(ProtocoloAuxilio::class)->create();

        factory(ElegivelProtocoloAuxilio::class)->create([
            'elegivel_id' => $elegivel->id,
            'protocolo_auxilio_id' => $protocolo->id,
            'competencia_inicio' => $this->competencia->subYear(1),
        ]);

        factory(Auxilio::class)->create([
            'protocolo_auxilio_id' => $protocolo->id,
            'competencia_inicio' => $this->competencia->subYear(1)
        ]);

        app(RegistrarAuxiliosService::class)->executar(4);

        $this->seeInDatabase('credito', [
            'elegivel_id' => $elegivel->id,
            'extraordinario' => true,
        ]);
    }

    public function testRegistraAuxilioParaElegivelComProtocolo()
    {
        $elegivel = factory(Elegivel::class)->create(['data_aposentadoria' => null, 'orgao_administracao_id' => 4]);
        $protocolo = factory(ProtocoloAuxilio::class)->create();
        factory(ElegivelProtocoloAuxilio::class)->create([
            'elegivel_id' => $elegivel->id,
            'protocolo_auxilio_id' => $protocolo->id,
        ]);
        factory(Auxilio::class)->create(['protocolo_auxilio_id' => $protocolo->id]);

        app(RegistrarAuxiliosService::class)->executar(4);

        $this->seeInDatabase('credito', [
            'elegivel_id' => $elegivel->id,
            'extraordinario' => true,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegistraAuxilioParaAfastado()
    {
        $elegivel = factory(Elegivel::class)->create(['data_aposentadoria' => null, 'orgao_administracao_id' => 4]);
        factory(Afastamento::class)->create([
            'elegivel_id' => $elegivel->id,
            'data_final' => null,
            'modalidade_afastamento_id' => ModalidadeAfastamentoEnum::CESSAO
        ]);

        factory(Auxilio::class)->create(['protocolo_auxilio_id' => null]);
        app(RegistrarAuxiliosService::class)->executar(4);

        $this->seeInDatabase('credito', [
            'elegivel_id' => $elegivel->id,
            'extraordinario' => true,
        ]);
    }

    public function testNaoRegistraAuxilioParaInativo()
    {
        $elegivel = factory(Elegivel::class)->create([
            'data_aposentadoria' => null,
            'orgao_administracao_id' => 4
        ]);

        factory(Afastamento::class)->create([
            'elegivel_id' => $elegivel->id,
            'data_final' => null,
            'modalidade_afastamento_id' => ModalidadeAfastamentoEnum::OUTROS
        ]);

        factory(Auxilio::class)->create(['protocolo_auxilio_id' => null]);
        app(RegistrarAuxiliosService::class)->executar(4);

        $this->missingFromDatabase('credito', [
            'elegivel_id' => $elegivel->id,
            'extraordinario' => true,
        ]);
    }
}
