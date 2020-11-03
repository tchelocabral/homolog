<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagemTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagens_tipos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('grupo_id');
            $table->foreign('grupo_id', 'fk_grupo_img')->references('id')->on('grupos_imagens');
            $table->string('nome',193)->nulllable();
            $table->string('descricao', 255)->default('Não Informado')->nullable();
            $table->string('observacoes', 255)->default('Não Informado')->nullable();
            $table->decimal('valor', 10,2)->nullable()->comments('Valor de Referência.');
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
        Schema::dropIfExists('imagens_tipos');
    }
}
