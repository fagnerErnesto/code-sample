<?php

use App\Model\Elegivel;
use App\Model\ModalidadeAfastamento;
use Faker\Generator as Faker;

$factory->define(App\Model\Afastamento::class, function (Faker $faker) {
    return [
        'elegivel_id' => factory(Elegivel::class)->create(),
        'data_inicio' => $faker->date(),
        'data_final' => $faker->optional()->date(),
        'modalidade_afastamento_id' => ModalidadeAfastamento::all()->random(1)->get('id'),
        'justificativa' => $faker->optional()->sentence(),
        'descricao_modalidade' => $faker->optional()->sentence(),
        'criado_carga' => $faker->boolean(),
    ];
});
