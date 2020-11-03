<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddPrazoPagamentoMetodoPagamentoJobPagamentoTable extends Migration
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
            $table->tinyInteger('prazo_pagamento')->nullable()->after('user_id');
            $table->tinyInteger('metodo_pagamento')->nullable()->after('user_id');
            $table->tinyInteger('tipo_pagamento')->nullable()->after('user_id');            
            //$table->string('chave_de_pgto')->nullable()->change(); // coloca campo chave_de_pato comom nullable
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
            $table->dropColumn('prazo_pagamento');
            $table->dropColumn('metodo_pagamento');
            $table->string('chave_de_pgto')->nullable()->change(); 
        });
    }
}
