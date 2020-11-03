<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsRevisoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_revisoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('avaliador_id');
            $table->integer('numero_revisao')->default(1);
            $table->timestamp('data_chegada');
            $table->dateTime('data_saida')->nullable();
            $table->text('observacoes')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0-Novo, 1-Delegado, 2-Em Execução, 3-Em Revisão, 4-Em Avaliação, 5-Concluído, 6-Recusado');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('avaliador_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('jobs_revisoes');
    }
}
