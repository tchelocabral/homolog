<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('dono_id');
            $table->string('dono_tipo');
            $table->text('nome')->nullable();
            $table->string('cep')->nullable();
            $table->text('logradouro')->nullable();
            $table->string('numero', 45)->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('zona', 45)->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 255)->nullable();
            $table->string('pais')->nullable();
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('enderecos');
    }
}
