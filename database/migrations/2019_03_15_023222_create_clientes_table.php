<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('razao_social', 193)->default('Não Informado');
            $table->string('nome_fantasia', 193);
            $table->string('cnpj', 193)->nullable();
            $table->string('nome_contato', 45)->nullable();
            $table->string('email_contato', 45)->nullable();
            $table->json('dados_bancarios')->nullable();
            
            // Em outros arquivos:
            // $table->text('logo')->nullable();
            // $table->foreign('user_id', 'fk_cliente_user')->references('id')->on('users');

            // copiado da alter table: add_logo_clientes
            $table->text('logo')->nullable();

            // copiado da alter table: add_fk_cliente_users
            $table->foreign('user_id', 'fk_cliente_user')->references('id')->on('users');

            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
