<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTipoJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipo_jobs', function ($table) {
            $table->tinyInteger('gera_custo')->nullable()->default(0);
            $table->tinyInteger('revisao')->nullable()->default(0);
            $table->tinyInteger('finalizador')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipo_jobs', function ($table) {
            $table->dropColumn('gera_custo');
            $table->dropColumn('revisao');
            $table->dropColumn('finalizador');
        });
    }
}
