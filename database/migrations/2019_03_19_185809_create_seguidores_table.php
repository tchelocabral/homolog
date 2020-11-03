<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeguidoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguidores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id_seguir');
            $table->foreign('user_id_seguir')->references('id')->on('users');
            $table->unsignedInteger('user_id_seguindo');
            $table->foreign('user_id_seguindo')->references('id')->on('users');
            $table->string('tipo_relacionamento', 191)->default('Seguidor');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('seguidores');
    }
}
