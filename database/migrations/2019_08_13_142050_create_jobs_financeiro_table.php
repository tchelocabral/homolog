<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsFinanceiroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_financeiro', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('centro_de_custo_id');
            $table->string('descricao')->default('Não Informado');
            $table->tinyInteger('tipo_mov')->comment('1-Crédito, 2-Débito');
            $table->json('dados_bancarios')->nullable()->comment('agencia, conta, tipo_conta. Dois Objetos: Origem e Destino. Default é o cadastrado na tabela freelancer_detalhes, mas pode ser sobrescrito.');
            $table->string('nota_fiscal')->nullable()->comments('Se é nulo é porque não foi informado. Se for preenchido é com o número da nota. Se não houver nota incluir - Não possui.');
            $table->text('observacoes')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-Em Aberto, 2-Concluído');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('centro_de_custo_id')->references('id')->on('centro_de_custos')->onDelete('cascade');
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
        Schema::dropIfExists('jobs_financeiro');
    }
}
