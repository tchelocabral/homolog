<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmadorIdCofirmadoEmJobsPagamentosTable extends Migration
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
            $table->unsignedInteger('confirmador_id')->nullable()->after('pago_em');
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
            $table->dropColumn('confirmador_id');
        });
    }
}
