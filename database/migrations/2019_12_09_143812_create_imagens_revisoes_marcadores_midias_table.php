<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagensRevisoesMarcadoresMidiasTable extends Migration
{
    /**
     * Run the migrations.
     * model: RevisaoMarcadorMidia
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagens_revisoes_marcadores_midias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('imagem_revisoes_marcador_id'); //fk
            $table->string('src')->nullable();
            $table->foreign('imagem_revisoes_marcador_id', 'fk_img_rev_mid')->references('id')->on('imagens_revisoes_marcadores')
                ->onDelete('cascade');

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
        Schema::dropIfExists('imagens_revisoes_marcadores_midias');
    }
}
