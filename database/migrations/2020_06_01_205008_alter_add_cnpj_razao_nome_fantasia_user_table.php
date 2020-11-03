<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddCnpjRazaoNomeFantasiaUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('razao_social')->nullable()->after('observacoes');
            $table->string('nome_fantasia')->nullable()->after('observacoes');
            $table->string('cnpj')->nullable()->after('observacoes');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('razao_social');
            $table->dropColumn('nome_fantasia');
            $table->dropColumn('cnpj');
        });
    }
}
