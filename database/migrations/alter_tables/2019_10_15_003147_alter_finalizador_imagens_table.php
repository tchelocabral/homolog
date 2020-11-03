<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFinalizadorImagensTable extends Migration
{
    

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imagens', function ($table) {
            $table->unsignedInteger('finalizador_id')->nullable();
            $table->foreign('finalizador_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('status')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imagens', function ($table) {
            $table->dropIndex('imagens_finalizador_id_foreign');
            $table->dropForeign('imagens_finalizador_id_foreign');
            $table->dropColumn('finalizador_id');
            $table->integer('status')->default(1)->change();
        });
    }
}
