<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddDataEntregaJobsRevioesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('jobs_revisoes', function (Blueprint $table) {
            $table->dateTime('data_entrega')->nullable()->after('status');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_revisoes', function (Blueprint $table) {
            $table->dropColumn('data_entrega');
        });
        //
    }
}
