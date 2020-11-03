<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampoConcluidoPorJobsTable extends Migration
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
            $table->unsignedInteger('concluido_por')->nullable()->after('data_entrega');

            //$table->foreign('concluido_por', 'fk_concluido_por')->references('id')->on('users')->onDelete('set null');
           
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
            $table->dropColumn('concluido_por');
            //$table->dropForeign('fk_concluido_por');
        });
    }
}
