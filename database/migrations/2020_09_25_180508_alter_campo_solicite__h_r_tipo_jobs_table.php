<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCampoSoliciteHRTipoJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tipo_jobs', function (Blueprint $table) {
            $table->tinyInteger('solicite_hr')->nullable()->default(0);
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
        Schema::table('tipo_jobs', function (Blueprint $table) {
            $table->dropColumn('solicite_hr');
        });

    }
}
