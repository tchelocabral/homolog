<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdemToJobsTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::table('jobs_tasks', function (Blueprint $table) {
            
            $table->integer('ordem')->after('user_id')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::table('jobs_tasks', function (Blueprint $table) {

            $table->dropColumn('ordem');
           
        }); 
    }
}
