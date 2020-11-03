<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('tipo_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->string('descricao')->nullable();
            $table->string('boas_praticas')->nullable();
            $table->json('campos_personalizados')->nullable();
            $table->double('porcentagem_individual')->nullable();
  
            // copiado da alter table: alter_tipo_jobs
            $table->tinyInteger('gera_custo')->nullable()->default(0);
            $table->tinyInteger('revisao')->nullable()->default(0);
            $table->tinyInteger('finalizador')->nullable()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         DB::statement('SET FOREIGN_KEY_CHECKS = 0');
         Schema::dropIfExists('tipo_jobs');
         DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
