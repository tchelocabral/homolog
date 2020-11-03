<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsRevisoesMidiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_revisoes_midias', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_revisao_id');
            $table->text('caminho')->nullable();
            $table->string('descricao')->nullable();
            $table->text('observacao')->nullable();
            $table->foreign('job_revisao_id')->references('id')->on('jobs_revisoes')->onDelete('cascade');
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
        Schema::dropIfExists('jobs_revisoes_midias');
    }
}
