<?php

use Illuminate\Database\Seeder;

class TipoArquivoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // cria vários tipos de arquivos
        
        \App\Models\TipoArquivo::create([
           'nome' => 'Arquitetura',
           'descricao' => 'Tipo de arquivo de Arquitetura'
        ]);

        \App\Models\TipoArquivo::create([
            'nome' => 'Decoração',
            'descricao' => 'Tipo de arquivo de Decoração'
        ]);

        \App\Models\TipoArquivo::create([
            'nome' => 'Paisagismo',
            'descricao' => 'Tipo de arquivo de Paisagismo'
        ]);

        \App\Models\TipoArquivo::create([
            'nome' => 'Referência',
            'descricao' => 'Tipo de arquivo de Referência'
        ]);
        \App\Models\TipoArquivo::create([
            'nome' => 'Boas Práticas',
            'descricao' => 'Tipo de arquivo de Boas Práticas'
        ]);
        \App\Models\TipoArquivo::create([
            'nome' => 'Exemplo',
            'descricao' => 'Tipo de arquivo de Exemplo'
        ]);
    }
}
