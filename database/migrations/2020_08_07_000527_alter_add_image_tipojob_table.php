<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddImageTipojobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipo_jobs', function (Blueprint $table) {
            // ->default('imagens/tipojobs/tipo-padrao.jpg')
            $table->text('imagem')->nullable()->after('finalizador');
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
            $table->dropColumn('imagem');
        });
    }
}
