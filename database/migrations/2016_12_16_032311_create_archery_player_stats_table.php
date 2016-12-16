<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArcheryPlayerStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archery_player_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id')->nullable();
            $table->integer('match_id');
            $table->integer('team_id')->nullable();
            $table->integer('user_id');
            $table->string('player_name');
            $table->string('team_name');
            $table->integer('round_1')->nullable();
            $table->integer('round_2')->nullable();
            $table->integer('round_3')->nullable();
            $table->integer('round_4')->nullable();
            $table->integer('round_5')->nullable();
            $table->integer('round_6')->nullable();
            $table->integer('round_7')->nullable();
            $table->integer('round_8')->nullable();
            $table->integer('round_9')->nullable();
            $table->integer('round_10')->nullable();
            $table->integer('total')->nullable();
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
        Schema::drop('archery_player_stats');
    }
}
