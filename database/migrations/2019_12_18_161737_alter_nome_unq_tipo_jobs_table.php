<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNomeUnqTipoJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipo_jobs', function (Blueprint $table) {
            try{
                $table->string('nome')->unique()->nullable(false)->change();
            }catch (\Exception $e){}
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipo_jobs', function (Blueprint $table) {
            $table->dropUnique(['nome']);
            $table->string('nome')->nullable()->change();
        });
    }
}
