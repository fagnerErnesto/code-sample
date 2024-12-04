<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\FolhaPagamento;
use App\Model\StatusFolhaPagamento;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(FolhaPagamento::class, function (Faker $faker) {
    return [
        'competencia' => $faker->dateTimeThisYear(),
        'status_folha_pagamento_id' => StatusFolhaPagamento::all()->random()->first(),
        'data_previa' => $faker->dateTimeThisYear(),
        'data_fechamento' => $faker->dateTimeThisYear(),
        'data_envio_banco' => $faker->dateTimeThisYear(),
        'data_arquivo_retorno' => $faker->dateTimeThisYear(),
        'data_pagamento' => $faker->dateTimeThisYear(),
    ];
});
