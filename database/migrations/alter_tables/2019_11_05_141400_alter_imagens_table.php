<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterImagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imagens', function($table) {
            // coments
            $table->unsignedInteger('status_revisao')->nullable()->after('status');
            $table->foreign('status_revisao')->references('id')->on('tipo_jobs')->onDelete('set null');

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imagens', function ($table) {

            $table->dropColumn('status_revisao');
         
        });
    }
}
