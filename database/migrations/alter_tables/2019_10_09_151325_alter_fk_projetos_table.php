<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFkProjetosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projetos', function (Blueprint $table) {
            
            // Deletar foreign key
            $table->dropForeign('projetos_coordenador_id_foreign');
            $table->dropIndex('projetos_coordenador_id_foreign');
            
            
            // criar foreign key
            $table->foreign('coordenador_id', 'fk_coordenador_projetos_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projetos', function (Blueprint $table) {
            
            $table->foreign('coordenador_id', 'projetos_coordenador_id_foreign')->references('id')->on('clientes')->onDelete('cascade');
            $table->dropIndex('fk_coordenador_projetos_id');
            $table->dropForeign('fk_coordenador_projetos_id');
            
        });
    }
}
