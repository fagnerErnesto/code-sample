<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Elegivel;
use App\Model\ElegivelProtocoloAuxilio;
use App\Model\ProtocoloAuxilio;
use Faker\Generator as Faker;

$factory->define(ElegivelProtocoloAuxilio::class, function (Faker $faker) {
    return [
        'elegivel_id' => factory(Elegivel::class)->create(),
        'protocolo_auxilio_id' => rand(0,1) ? factory(ProtocoloAuxilio::class)->create() : null,
        'competencia_inicio' => $faker->date(),
        'criado_em' => $faker->dateTime(),
        'atualizado_em' => $faker->dateTime(),
        'deletado_em' => null, // Set to null for active records
    ];
});
