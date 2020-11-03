<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->unsignedInteger('plano_id')->nullable();
            $table->foreign('plano_id')->references('id')->on('planos');

            $table->string('name', 193);
            $table->string('bio')->nullable();
            $table->char('sexo')->nullable();
            $table->string('cidade')->nullable();
            // $table->string('estado')->nullable();
            $table->string('pais')->nullable();
            $table->string('telefone')->nullable();
            $table->char('locale', 5)->default('pt-BR');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();
            // $table->integer('tipo_usuario')->default(2)->comment('1-Administrador, 2-Coordenador, 3-Avaliador, 4-Freelancer, 5-Equipe, 6-Cliente, 7-Dev');
            $table->string('activation_code')->nullable();
            $table->boolean('ativo')->default(0);

            $table->string('cep', 45)->nullable();
            $table->string('logradouro', 255)->nullable();
            $table->string('numero', 45)->nullable();
            $table->string('complemento', 45)->nullable();
            $table->string('bairro')->nullable();
            $table->string('uf')->nullable();
            $table->string('tel_alternativo', 45)->nullable();
            $table->string('observacoes', 255)->nullable();
            $table->tinyInteger('nova_senha')->default(1);

            $table->rememberToken();
            $table->timestamps();

            
            // *** copiado da alter table: alter_tipo_usuario_users
            // $table->dropColumn('tipo_usuario');

            // *** copiado da alter table: alter_v4_users
            // $table->foreign('plano_id')->references('id')->on('planos');

            // *** copiado da alter table: alter_v7_users
            // $table->dropColumn('estado');
            // $table->string('cep', 45)->nullable();
            // $table->string('logradouro', 255)->nullable();
            // $table->string('numero', 45)->nullable();
            // $table->string('complemento', 45)->nullable();
            // $table->string('bairro')->nullable();
            // $table->string('uf')->nullable();
            // $table->string('tel_alternativo', 45)->nullable();
            // $table->string('observacoes', 255)->nullable();
            // $table->tinyInteger('nova_senha')->default(1);

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         DB::statement('SET FOREIGN_KEY_CHECKS = 0');
         Schema::dropIfExists('users');
         DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
