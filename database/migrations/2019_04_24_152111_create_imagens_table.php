<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('projeto_id')->nullable();
            $table->unsignedInteger('imagem_tipo_id');
            $table->string('nome', 193);
            $table->string('descricao', 255)->nullable();
            $table->decimal('altura', 10, 2)->nullable()->default(0);
            $table->decimal('largura', 10, 2)->nullable()->default(0);
            $table->decimal('profundidade', 10, 2)->nullable()->default(0);
            $table->json('campos_personalizados')->nullable();
            $table->string('observacoes', 255)->nullable();
            $table->dateTime('data_inicio')->nullable();
            $table->dateTime('data_revisao')->nullable();
            $table->dateTime('data_entrega')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->json('dados_bancarios')->nullable();
            $table->integer('status')->default(0)->comments('0-Nova,  1-Em andamento, 2-Concluída, 3-Recusado');
            $table->unsignedInteger('status_revisao')->nullable();
            $table->foreign('status_revisao')->references('id')->on('tipo_jobs')->onDelete('no action');
            $table->unsignedInteger('finalizador_id')->nullable();
            $table->foreign('finalizador_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('projeto_id')->references('id')->on('projetos')->onDelete('cascade');
            $table->timestamps();

            // *** copiado da alter table: alter_imagens
            // $table->unsignedInteger('status_revisao')->nullable()->after('status');
            // $table->foreign('status_revisao')->references('id')->on('tipo_jobs')->onDelete('set null');

            // *** copiado da alter table: alter_finalizador_imagens
            // $table->unsignedInteger('finalizador_id')->nullable();
            // $table->foreign('finalizador_id')->references('id')->on('users')->onDelete('cascade');
            // $table->integer('status')->default(0)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagens');
    }
}
