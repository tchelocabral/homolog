<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterV7UsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('estado');
            $table->string('cep', 45)->nullable();
            $table->string('logradouro', 255)->nullable();
            $table->string('numero', 45)->nullable();
            $table->string('complemento', 45)->nullable();
            $table->string('bairro')->nullable();
            $table->string('uf')->nullable();
            $table->string('tel_alternativo', 45)->nullable();
            $table->string('observacoes', 255)->nullable();
            $table->tinyInteger('nova_senha')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('estado')->nullable();
            $table->dropColumn('cep');
            $table->dropColumn('logradouro');
            $table->dropColumn('numero');
            $table->dropColumn('complemento');
            $table->dropColumn('bairro');
            $table->dropColumn('uf');
            $table->dropColumn('tel_alternativo');
            $table->dropColumn('observacoes');
            $table->dropColumn('nova_senha');
        });
    }
}
