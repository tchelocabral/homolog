<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPgPublicadorPgFreelanceJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('jobs', function (Blueprint $table) {
            $table->tinyInteger('pg_publicador')->nullable()->after('user_id');
            $table->tinyInteger('pg_freelancer')->nullable()->after('user_id');
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
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('pg_publicaodor');
            $table->dropColumn('pg_freelance');
        });
    }
}
