<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsRevisoesMarcadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_revisoes_marcadores', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('job_revisao_id'); //fk
            $table->string('x');
            $table->string('y');
            $table->string('texto');
            $table->string('src_arquivo_final')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('jobs_revisoes_marcadores');
    }
}
