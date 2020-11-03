<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCampoTextoJobsRevisoesMarcadoressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //jobs_revisoes_marcadores
        Schema::table('jobs_revisoes_marcadores', function (Blueprint $table) {
            $table->text('texto')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('jobs_revisoes_marcadores', function (Blueprint $table) {
            $table->string('texto', 255)->change();
        });
    }
}
