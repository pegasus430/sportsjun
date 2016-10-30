<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTennisGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tennis_sets_game', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id');
            $table->integer('rubber_id');
            $table->integer('team_a');
            $table->integer('team_b');
            $table->integer('team_a_score');
            $table->integer('team_b_score');
            $table->integer('table_sets_id');
            $table->integer('winner_id');
            $table->integer('looser_id');
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
        Schema::drop('tennis_sets_game');
    }
}
