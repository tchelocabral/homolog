<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddPublicadorIdTableJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedInteger('publicador_id')->nullable()->after('user_id');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('publicador_id');
        });
    }
}
