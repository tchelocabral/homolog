<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddPagoEmUserFinanceirosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //
        Schema::table('user_financeiros', function (Blueprint $table) {
            $table->dateTime('pago_em')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
        Schema::table('user_financeiros', function (Blueprint $table) {
            $table->dropColumn('pago_em');
        });
    }
}
