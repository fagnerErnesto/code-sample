<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\ProtocoloAuxilio;
use Faker\Generator as Faker;

$factory->define(ProtocoloAuxilio::class, function (Faker $faker) {
    return [
        'nome' => $faker->word,
        'descricao' => $faker->sentence,
    ];
});
