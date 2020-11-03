<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddNomeImagensRevisoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imagens_revisoes', function (Blueprint $table) {
            $table->string('nome')->nullable()->after('avaliador_id');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imagens_revisoes', function (Blueprint $table) {
            $table->dropColumn('nome');
        });
    }
}
