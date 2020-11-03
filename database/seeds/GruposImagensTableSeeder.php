<?php

use Illuminate\Database\Seeder;

class GruposImagensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // cria vários grupos de imagens
        
        \App\Models\GrupoImagem::create([
           'nome' => 'Interna',
           'descricao' => 'Grupo de tipos de imagens Internas',
           'observacoes' => 'Criadas pelo sistema'

        ]);
        \App\Models\GrupoImagem::create([
            'nome' => 'Externa',
            'descricao' => 'Grupo de tipos de imagens Externas',
            'observacoes' => 'Criadas pelo sistema'

        ]);
        \App\Models\GrupoImagem::create([
            'nome' => 'Imagem 360',
            'descricao' => 'Grupo de tipos de imagens 360',
            'observacoes' => 'Criadas pelo sistema'

        ]);
        \App\Models\GrupoImagem::create([
            'nome' => 'Planta Baixa',
            'descricao' => 'Grupo de tipos de imagens de Planta Baixa',
            'observacoes' => 'Criadas pelo sistema'

        ]);
        \App\Models\GrupoImagem::create([
            'nome' => 'Filme',
            'descricao' => 'Grupo de imagens para Filmes',
            'observacoes' => 'Criadas pelo sistema'

        ]);
        \App\Models\GrupoImagem::create([
            'nome' => 'App',
            'descricao' => 'Grupo de imagens para App',
            'observacoes' => 'Criadas pelo sistema'

        ]);
    }
}
