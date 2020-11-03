<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tipojob_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('coordenador_id')->nullable();
            $table->unsignedInteger('avaliador_id')->nullable();
            $table->unsignedInteger('delegado_para')->nullable();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->text('observacoes')->nullable();
            $table->json('campos_personalizados')->nullable();
            $table->dateTime('data_inicio')->nullable();
            $table->date('data_prox_revisao')->nullable();
            $table->date('data_entrega')->nullable();
            $table->decimal('valor_job', 10, 2)->nullable();
            $table->integer('porcentagem_individual')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0-Novo, 1-Delegado, 2-Em Execução, 3-Em Revisão, 4-Em Avaliação, 5-Concluído, 6-Recusado');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('coordenador_id')->references('id')->on('users')->onDelete('set null');

            // copiado da alter table: alter_delegado_para_jobs
            $table->foreign('delegado_para', 'fk_delegado_para')->references('id')->on('users')->onDelete('set null');
            

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
        Schema::dropIfExists('jobs');
    }
}
