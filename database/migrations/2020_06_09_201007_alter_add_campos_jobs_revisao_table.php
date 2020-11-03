<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddCamposJobsRevisaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_revisoes', function (Blueprint $table) {
            $table->string('src')->nullable();
            $table->string('nome')->nullable();
            $table->dateTime('data_conclusao')->nullable();
            $table->dropColumn('data_saida');
            $table->dropColumn('data_chegada');
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('jobs_revisoes', function (Blueprint $table) {
            $table->timestamp('data_chegada');
            $table->dateTime('data_saida')->nullable();
            $table->dropColumn('src');
            $table->dropColumn('nome');
            $table->dropColumn('data_conclusao');
        });
    }
}
