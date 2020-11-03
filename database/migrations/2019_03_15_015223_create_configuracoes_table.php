<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('taxa_adm_job');     
            $table->text("app_description")->nullable();
            
            // Removed on 23.07.20 to seed initial fullfreela
            // $table->unsignedInteger('user_id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->string('chave', 193);
            // $table->json('valor');

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
        Schema::dropIfExists('configuracoes');
    }
}
