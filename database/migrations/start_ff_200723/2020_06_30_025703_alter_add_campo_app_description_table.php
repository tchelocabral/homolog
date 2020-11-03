<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddCampoAppDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracoes', function (Blueprint $table) {
            // $table->text("app_description")->nullable();
            // Comentado em 23.07 trazido para criação da tabela. Start Fullfreela
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        //
        Schema::table('configuracoes', function (Blueprint $table) {
            // $table->dropColumn('app_description ');
        });
    }
}
