<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampoHrSoliciadoHrUrlJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('jobs', function (Blueprint $table) {
            $table->text('hr_url')->nullable();
            $table->tinyInteger('hr_solicitado')->nullable()->default(0);
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
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('hr_solcitado');
            $table->dropColumn('hr_url');
        });

    }
}
