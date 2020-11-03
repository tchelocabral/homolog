<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxaTransacaoPagarmeTable extends Migration
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
            $table->decimal('taxa_transacao', 10, 2)->nullable()->after('taxa_paypal');;
            $table->decimal('taxa_pagar_me', 10, 2)->nullable()->after('taxa_transacao');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracoes', function (Blueprint $table) {
            $table->dropColumn('taxa_transacao');
            $table->dropColumn('taxa_pagar_me');
        });

        //
    }
}
