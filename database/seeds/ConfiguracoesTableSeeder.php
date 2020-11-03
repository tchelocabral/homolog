<?php

use Illuminate\Database\Seeder;

class ConfiguracoesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Configuracao::create([
           'taxa_adm_job'    => 25,
           'app_description' => 'FullFreela' 
        ]);
    }
}
