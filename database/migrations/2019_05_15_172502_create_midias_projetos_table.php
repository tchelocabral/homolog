<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMidiasProjetosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('midias_projetos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('projeto_id');
            $table->unsignedInteger('midia_id');
            $table->foreign('projeto_id')->references('id')->on('projetos')->onDelete('cascade');
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
        Schema::table('midias_projetos', function (Blueprint $table) {
            Schema::dropIfExists('midias_projetos');
        });
    }
}
