<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnsToFinalTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_final_teams', function (Blueprint $table) {
            //
            $table->integer('points')->default(0);
            $table->integer('bonus')->default(0);
            $table->string('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_final_teams', function (Blueprint $table) {
            //
        });
    }
}
