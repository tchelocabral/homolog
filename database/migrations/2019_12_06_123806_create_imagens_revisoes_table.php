<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagensRevisoesTable extends Migration
{
    /**
     * Run the migrations.
     * model: RevisaoImagem
     *
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagens_revisoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('imagem_id');
            $table->unsignedInteger('avaliador_id')->nullable();
            $table->integer('numero_revisao')->nullable();
            $table->string('src')->nullable();
            $table->string('observacoes')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreign('imagem_id')->references('id')->on('imagens')->onDelete('cascade');
            $table->foreign('avaliador_id')->references('id')->on('users')->onDelete('set null');
            $table->dateTime('data_conclusao')->nullable();
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
        Schema::dropIfExists('imagens_revisoes');
    }
}
