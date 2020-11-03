<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMidiasImagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('midias_imagens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('imagem_id');
            $table->unsignedInteger('midia_id');
            $table->foreign('imagem_id')->references('id')->on('imagens')->onDelete('cascade');
            $table->foreign('midia_id')->references('id')->on('midias')->onDelete('cascade');
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
        Schema::table('midias_imagens', function (Blueprint $table) {
            Schema::dropIfExists('midias_imagens');
        });
    }
}
