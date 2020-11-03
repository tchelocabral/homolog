<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddOrdemRevMarcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('imagens_revisoes_marcadores', function (Blueprint $table) {
            $table->integer('ordem')->nullable()->after('texto');
            $table->dropColumn('src_arquivo_final');
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
        Schema::table('imagens_revisoes_marcadores', function (Blueprint $table) {
            $table->dropColumn('ordem');
            $table->string('src_arquivo_final')->nullable();
        });
    }
}
