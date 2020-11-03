<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddCamposJobsRevisoesMidiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_revisoes_midias', function (Blueprint $table) {

            // $table->dropForeign('jobs_revisoes_midias_job_revisao_id_foreign');
            $table->dropForeign(['job_revisao_id']);
            $table->dropColumn('job_revisao_id');
            $table->unsignedInteger('job_revisao_marcador_id');
            $table->string('src')->nullable();
            $table->string('caminho_arquivo')->nullable();
            $table->dropColumn('caminho');

            $table->dropColumn('descricao');
            $table->dropColumn('observacao');
            $table->foreign('job_revisao_marcador_id')->references('id')->on('jobs_revisoes_marcadores')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_revisoes_midias', function (Blueprint $table) {
            $table->dropForeign(['job_revisao_marcador_id']);
            $table->text('observacao')->nullable();
            $table->string('descricao')->nullable();

            $table->text('caminho')->nullable();
            $table->dropColumn('caminho_arquivo');
            $table->dropColumn('src');
            $table->dropColumn('job_revisao_marcador_id');

            $table->unsignedInteger('job_revisao_id');
            $table->foreign('job_revisao_id')->references('id')->on('jobs_revisoes')->onDelete('cascade');

        });

        //
    }
}
