<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Elegivel;
use App\Model\Pessoa;
use App\Model\Cargo;
use App\Model\OrgaoAdministracao;
use App\Model\ModalidadeElegivel;
use Faker\Generator as Faker;

$factory->define(Elegivel::class, function (Faker $faker) {
    return [
        'pessoa_id' => function () {
            return factory(Pessoa::class)->create()->id;
        },
        'cargo_id' => function () {
            return Cargo::all()->random()->first()->id;
        },
        'orgao_administracao_id' => function () {
            return OrgaoAdministracao::all()->random()->first()->id;
        },
        'modalidade_elegivel_id' => function () {
            return ModalidadeElegivel::all()->random()->first()->id;
        },
        'data_ingresso' => $faker->date(),
        'data_aposentadoria' => $faker->optional()->date(),
        'percentual_maximo_estorno' => $faker->optional()->randomFloat(2, 0, 1),
        'siape' => $faker->optional()->regexify('[A-Z0-9]{10}'),
        'descricao_modalidade_outros' => $faker->optional()->sentence,
        'deletado_em' => null,
    ];
});
