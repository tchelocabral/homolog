<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddDatailsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pais_nascimento')->nullable()->after('pais');
            $table->string('cpf')->nullable()->after('observacoes');
            $table->string('conta_paypal')->nullable()->after('ativo');
            $table->date('data_nascimento')->nullable()->after('sexo');
            
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
            $table->dropColumn('pais_nascimento');
            $table->dropColumn('cpf');
            $table->dropColumn('conta_paypal');
            $table->dropColumn('data_nascimento');
        });
    }
}
