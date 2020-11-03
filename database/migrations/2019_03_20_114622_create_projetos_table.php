<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjetosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projetos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->unsignedInteger('coordenador_id')->nullable();
            $table->foreign('coordenador_id')->references('id')->on('users')->onDelete('set null');
            $table->string('nome',193)->nulllable();
            $table->string('descricao', 255)->default('Não Informado')->nullable();
            $table->string('cnpj', 193)->nullable();
            $table->text('observacoes')->nullable();
            $table->date('data_previsao_entrega')->nullable();
            $table->date('data_entrega')->nullable();
            $table->integer('porcentagem_conclusao')->default(0);
            $table->decimal('valor', 10, 2)->default(0);
            $table->json('dados_faturamento')->nullable()->comments('razao_social, nome_fantasia, forma_pgto');
            $table->integer('status')->default(0)->comments('0-Novo, 1-Em Andamento, 2-Concluído, 3-Recusado');
            $table->timestamps();

            // *** copiado da alter table: alter_coord_id_null_projetos
            // $table->unsignedInteger('coordenador_id')->nullable()->change();

            // *** copiado da alter table: alter_status_projetos
            // $table->integer('status')->default(0)->change();

            // *** copiado da alter table: alter_descricao_projetos
            // $table->string('descricao', 255)->default('Não Informado')->nullable()->change();

            // *** copiado da alter table: alter_fk_projetos
            // $table->dropForeign('projetos_coordenador_id_foreign');
            // $table->dropIndex('projetos_coordenador_id_foreign');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projetos');
    }
}
