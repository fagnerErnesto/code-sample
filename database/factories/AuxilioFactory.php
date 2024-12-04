<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Auxilio;
use App\Model\ProtocoloAuxilio;
use App\Model\Rubrica;
use Faker\Generator as Faker;

$factory->define(Auxilio::class, function (Faker $faker) {
    return [
        'rubrica_id' => factory(Rubrica::class)->create(),
        'valor' => $faker->randomFloat(2, 100, 10000), // Random value between 100.00 and 10,000.00
        'competencia_inicio' => $faker->date(),
        'retroativo' => $faker->boolean(),
        'protocolo_auxilio_id' => rand(0,1) ? factory(ProtocoloAuxilio::class)->create() : null,
        'justificativa' => $faker->text(),
    ];
});
