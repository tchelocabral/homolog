<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChaveDePagtoJobsPagamentosTable extends Migration
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
            $table->string('chave_de_pgto')->nullable()->change(); // coloca campo chave_de_pato comom nullable
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
            $table->string('chave_de_pgto')()->change(); 
        });
    }
}
