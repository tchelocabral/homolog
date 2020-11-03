<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Cliente::class, function (Faker $faker) {
    return [
        'nome_fantasia' => $faker->name,
        'razao_social' => $faker->company,
    ];
});
