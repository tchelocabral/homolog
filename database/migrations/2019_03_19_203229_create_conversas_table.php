<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id_envia');
            $table->foreign('user_id_envia')->references('id')->on('users');
            $table->unsignedInteger('user_id_recebe');
            $table->foreign('user_id_recebe')->references('id')->on('users');
            $table->json('midias')->nullable();
            $table->tinyInteger('lida')->default(0);
            $table->dateTime('lida_em')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('conversas');
    }
}
