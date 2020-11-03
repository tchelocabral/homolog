<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFinanceirosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_financeiros', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('de_id')->nullable()->comments('quem recebe o debito');
            $table->unsignedInteger('para_id')->nullable()->comments('quem recebe o credito');
            $table->unsignedInteger('pagador_id')->nullable()->comments('quem faz a ação de pagar');
            $table->unsignedInteger('taxa')->default(1)->comments('taxa adm no momento do pgto');
            $table->unsignedInteger('centro_de_custo_id')->nullable();
            $table->unsignedInteger('categoria_de_custo_id')->nullable();
            $table->morphs('model');
            // $table->tinyInteger('tipo_mov')->default(1)->comments('1=credito,2=debito');
            $table->json('dados_bancarios')->nullable(); 
            // $table->decimal('valor', 10,2);
            $table->decimal('valor_de', 10,2);
            $table->decimal('valor_para', 10,2);
            $table->decimal('valor_taxa', 10,2);
            $table->text('descricao')->nullable();
            $table->text('observacoes')->nullable();
            $table->text('doc_url')->nullable();
            $table->tinyInteger('status')->default(1)->comments("1=pendente,2=executado");
            $table->timestamps();

            $table->foreign('de_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('para_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('pagador_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('centro_de_custo_id')->references('id')->on('centro_de_custos')->onDelete('set null');
            $table->foreign('categoria_de_custo_id')->references('id')->on('categoria_de_custos')->onDelete('set null');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_financeiros');
    }
}
