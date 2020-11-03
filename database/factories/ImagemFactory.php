<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Imagem::class, function (Faker $faker) {
    return [
    	'projeto_id' 			=> 1,
        'imagem_tipo_id'		=> 1,
        'finalizador_id'		=> 5,
        'nome'					=> $faker->name,
        'descricao' 			=> $faker->text,
        'campos_personalizados' => '', 
        'observacoes'			=> $faker->text,
        'data_inicio'			=> now(),
        'data_revisao'			=> $faker->date('Y-m-d'),
        'data_entrega'			=> $faker->date('Y-m-d'),
        'valor' 				=> 0,
        'dados_bancarios'		=> null,
        'status'				=> 0
        //
    ];
});
