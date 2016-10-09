<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamNameToTtPlayerRubberScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tt_player_rubber_score', function (Blueprint $table) {
            //
            $table->string('team_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tt_player_rubber_score', function (Blueprint $table) {
            //
            $table->dropColumn('team_name');
        });
    }
}
