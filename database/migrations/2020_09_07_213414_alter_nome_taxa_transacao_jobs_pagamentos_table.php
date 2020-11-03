<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNomeTaxaTransacaoJobsPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_pagamentos', function (Blueprint $table) {
            $table->renameColumn('taxa_paypal','taxa_transacao');
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
        Schema::table('jobs_pagamentos', function (Blueprint $table) {
            $table->renameColumn('taxa_transacao','taxa_paypal');
        });
    }
}
