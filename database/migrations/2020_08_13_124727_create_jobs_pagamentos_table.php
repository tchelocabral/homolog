<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_pagamentos', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('job_id'); //fk
            $table->string('status');
            $table->dateTime('pago_em')->nullable();
            $table->string('chave_de_pgto'); //fk
            $table->text('observacao')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->unsignedInteger('user_id'); //fk

            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('jobs_pagamentos');
    }
}
