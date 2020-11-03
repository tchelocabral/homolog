<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagensRevisoesMarcadoresTable extends Migration
{
    /**
     * Run the migrations.
     * model: RevisaoMarcador
     * @return void
     */
    public function up()
    {
        Schema::create('imagens_revisoes_marcadores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('imagem_revisao_id'); //fk
            $table->string('x');
            $table->string('y');
            $table->string('texto');
            //  Nova tabela imagem_revisoes_marcadores_midias id, src, imagem_revisoes_marcador_id fk
            $table->string('src_arquivo_final')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreign('imagem_revisao_id')->references('id')->on('imagens_revisoes')->onDelete('cascade');

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
        Schema::dropIfExists('imagens_revisoes_marcadores');
    }
}
