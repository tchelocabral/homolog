<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Plano Factories
|--------------------------------------------------------------------------
*/

$factory->define(App\Models\Plano::class, function (Faker $faker) {
    return [
        'nome' =>      $faker->name,
        'descricao' => $faker->text,
        'valor' =>     10.2,
        'periodo' =>   'trimestral',
        'periodo_disponivel' =>  $faker->randomDigitNotNull,
        'sort_order' =>          $faker->unique()->randomNumber(3),
        'status' =>              1
    ];
});
