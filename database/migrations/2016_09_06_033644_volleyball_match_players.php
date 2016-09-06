<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VolleyballMatchPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volleyball_player_matchwise_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('tournament_id');
            $table->integer('match_id');
            $table->integer('team_id');
            $table->enum('player_status', ['P', 'S'])->default('P');
            $table->integer('serving_order')->nullable();
            $table->integer('player_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('volleyball_player_matchwise_stats');
    }
}
