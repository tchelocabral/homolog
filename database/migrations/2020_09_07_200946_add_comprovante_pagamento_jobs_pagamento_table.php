<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComprovantePagamentoJobsPagamentoTable extends Migration
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
            $table->text('comprovante_pagamento')->nullable()->after('valor')->comment("url do comprovante");
            $table->dateTime('confirmado_em')->nullable()->after('comprovante_pagamento');
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
            $table->dropColumn('comprovante_pagamento');
            $table->dropColumn('confirmado_em');
        });
    }
}
