<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planos', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('nome', 193);
            $table->string('descricao', 193)->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->string('periodo', 193)->nullable(); // Diz se é por mês, semanal, trimestral anual, etc.
            $table->smallInteger('periodo_disponivel')->nullable(); // Diz se é por mês, semanal, trimestral anual, etc.
            $table->smallInteger('sort_order'); // Ordem de visualização
            $table->integer('status'); // Ordem de visualização
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
         Schema::dropIfExists('planos');
         DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
