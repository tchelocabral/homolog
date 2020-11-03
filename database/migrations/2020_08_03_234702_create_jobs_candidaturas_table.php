<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsCandidaturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_candidaturas', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id'); //fk
            $table->unsignedInteger('job_id'); //fk
            $table->tinyInteger('status')->default(0)->comment('0-aberto, 1-aceito, 2-recusado, 3-cancelado, 4-user_sem_slot'); 
            $table->decimal('valor', 10, 2)->nullable();
            $table->text('observacao')->nullable();

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
        Schema::dropIfExists('jobs_candidaturas');
    }
}
