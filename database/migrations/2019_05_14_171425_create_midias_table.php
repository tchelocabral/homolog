<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMidiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('midias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tipo_arquivo_id')->nullable();
            $table->foreign('tipo_arquivo_id', 'fk_tipo')->references('id')->on('tipos_arquivos');
            $table->string('nome', 100)->nullable();
            $table->string('descricao', 100)->nullable();
            $table->string('nome_original')->nullable();
            $table->string('nome_arquivo')->nullable();
            $table->text('caminho');
            $table->timestamps();

            // copiado da alter table: alter_midias
            // $table->string('nome', 100)->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('midias');
    }
}
