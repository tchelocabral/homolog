<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDropQtdJobAndamentoConfiguracoesUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('configuracoes_user', function (Blueprint $table) {
            // $table->dropColumn('qtd_job_andamento');
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

        Schema::table('configuracoes_user', function (Blueprint $table) {
            // $table->unsignedInteger('qtd_job_andamento')->nullable();
        });
    }
}
