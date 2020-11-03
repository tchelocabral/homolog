<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contatos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dono_id');
            $table->string('dono_tipo');
            $table->string('nome',191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('email_alternativo', 191)->nullable();
            $table->string('tel', 191)->nullable();
            $table->string('tel_alternativo', 191)->nullable();
            $table->string('cel', 191)->nullable();
            $table->string('cel_alternativo', 191)->nullable();
            $table->string('observacoes', 255)->nullable();
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('contatos');
    }
}
