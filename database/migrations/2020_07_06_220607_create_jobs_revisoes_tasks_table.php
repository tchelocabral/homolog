<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsRevisoesTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_revisoes_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_revisao_id');
            $table->unsignedInteger('user_id')->nullable();

            $table->text("task_name")->nullable();
            $table->text("task_description")->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = nova, 1 = executada');
            $table->integer('ordem')->default(0);

            $table->foreign('job_revisao_id')->references('id')->on('jobs_revisoes')->onDelete('cascade');

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
        Schema::dropIfExists('jobs_revisoes_tasks');
    }
}
