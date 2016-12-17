<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTennisPlayerMatchScoreAddTournamentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tennis_player_match_score', function (Blueprint $table) {
            //
            $table->integer('tournament_id');
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
        Schema::table('tennis_player_match_score', function (Blueprint $table) {
            //
            $table->dropColumn('tournament_id');
            $table->dropColumn('team_name');
        });
    }
}
