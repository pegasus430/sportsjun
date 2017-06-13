<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coachings', function (Blueprint $table) {
            //
            $table->softDeletes();
        });

         Schema::table('news', function (Blueprint $table) {
            //
            $table->softDeletes();
        });

        Schema::table('polls', function (Blueprint $table) {
            //
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
        Schema::table('coachings', function (Blueprint $table) {
            //
            $table->dropColumn('deleted_at');
        });

        Schema::table('news', function (Blueprint $table) {
            //
            $table->dropColumn('deleted_at');
        });

        Schema::table('polls', function (Blueprint $table) {
            //
            $table->dropColumn('deleted_at');
        });
    }
}
