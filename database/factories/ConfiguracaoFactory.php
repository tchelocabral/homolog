<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Configuracao::class, function (Faker $faker) {
    return [
        'chave' => $faker->text,
        'valor' => json_encode($faker->text),
    ];
});
