<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddSoftDeletesAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tipo_jobs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('projetos', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('imagens', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
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
        Schema::table('tipo_jobs', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('projetos', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('imagens', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        
        // Schema::table('jobs', function (Blueprint $table) {
        //     $table->tinyInteger('freela')->default(0)->after('status');
        // });

        // Schema::table('projetos', function (Blueprint $table) {
        //     $table->tinyInteger('freela')->default(0)->after('status');
        // });
    }
}
