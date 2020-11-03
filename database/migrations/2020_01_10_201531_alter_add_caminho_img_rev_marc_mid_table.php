<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddCaminhoImgRevMarcMidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // //
        Schema::table('imagens_revisoes_marcadores_midias', function (Blueprint $table) {
            $table->string('caminho_arquivo')->nullable()->after('src');
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
        Schema::table('imagens_revisoes_marcadores_midias', function (Blueprint $table) {
            $table->dropColumn('caminho_arquivo');
        });

    }
}
