<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGruposImagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos_imagens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome',193)->nulllable();
            $table->string('descricao', 255)->default('Não Informado')->nullable();
            $table->string('observacoes', 255)->default('Não Informado')->nullable();
            $table->json('midias_referencia')->nullable();
            $table->json('campos_personalizados')->nullable();
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
        Schema::dropIfExists('grupos_imagens');
    }
}
