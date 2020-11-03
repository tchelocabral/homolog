<?php

use Illuminate\Database\Seeder;
// use GruposImagensTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(GruposImagensTableSeeder::class);
        $this->call(TipoArquivoTableSeeder::class);
        // $this->call(TipoJobTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ConfiguracoesTableSeeder::class);
    }
}
