<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjetosFaturamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projetos_faturamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('projeto_id');
            $table->unsignedInteger('cliente_faturamento_id');
            $table->json('meta')->nullable();
            $table->foreign('projeto_id')->references('id')->on('projetos')->onDelete('cascade');
            $table->foreign('cliente_faturamento_id')->references('id')->on('clientes_faturamentos')->onDelete('cascade');
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
        Schema::table('projetos_faturamentos', function (Blueprint $table) {
            Schema::dropIfExists('projetos_faturamentos');
        });
    }
}
