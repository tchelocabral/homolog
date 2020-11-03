<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddTaxaPaypalJobsPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('jobs_pagamentos', function (Blueprint $table) {
            $table->decimal('taxa_paypal', 10, 2)->nullable()->after('valor');;
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
            $table->dropColumn('taxa_paypal');
        });
    }
}
