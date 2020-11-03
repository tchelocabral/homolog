<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Projeto::class, function (Faker $faker) {
    return [
        'cliente_id'  => 1,
        'nome'        => $faker->name,
        'descricao'   => $faker->text,
        'cnpj'        => '',
        'observacoes' => $faker->text,
        'data_previsao_entrega' => $faker->date('Y-m-d'),
        'porcentagem_conclusao' => 0,
        'status' =>              1
    ];
});
