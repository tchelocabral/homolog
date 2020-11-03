<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddTaxaPaypalCondiguracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->decimal('taxa_paypal', 10, 2)->nullable()->after('taxa_adm_job');;
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
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->dropColumn('taxa_paypal');
        });
    }
}
