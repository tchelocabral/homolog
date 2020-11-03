<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNameConfiguracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Commented on 23.07 devido a falha ao setar novo banco. 
        // Não foi mexido no nome da migration para não rodar em prdo MintStudios
        //     Schema::table('configuracoes', function (Blueprint $table) {
        //         $table->dropForeign(['user_id']);
        //     });
            
        //     Schema::rename('configuracoes', 'configuracoes_user');
        
        // Cria tabela configurações de Usuário
        Schema::create('configuracoes_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('chave', 193);
            $table->text('valor'); 
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
        //
        // Schema::rename('configuracoes_user', 'configuracoes');
        Schema::dropIfExists('configuracoes_user');
    }
}
