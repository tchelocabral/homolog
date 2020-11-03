<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddStatusJobCandidatura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('jobs_candidaturas', function (Blueprint $table) {
            $table->tinyInteger('tipo')->comment('0-Proposta, 1-Candidatura')->after('valor');
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
        Schema::table('jobs_candidaturas', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
}
