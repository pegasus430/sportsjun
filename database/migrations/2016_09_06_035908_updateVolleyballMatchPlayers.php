<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVolleyballMatchPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('volleyball_player_matchwise_stats', function (Blueprint $table) {
            //
            $table->string('player_name');
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
        Schema::table('volleyball_player_matchwise_stats', function (Blueprint $table) {
            //
            $table->dropColumn('player_name');
            $table->dropColumn('team_name');
        });
    }
}
 