<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDelegadoParaJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            // $table->unsignedInteger('delegado_id')->nullable();
            // $table->dropColumn('delegado_para');
            $table->foreign('delegado_para', 'fk_delegado_para')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign('fk_delegado_para');
            $table->dropIndex('fk_delegado_para');
        });
    }
}
