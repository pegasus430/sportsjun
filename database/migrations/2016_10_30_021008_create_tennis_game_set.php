<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTennisGameSet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tennis_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id');
            $table->integer('rubber_id');
            $table->integer('team_a');
            $table->integer('team_b');
            $table->integer('team_a_score');
            $table->integer('team_b_score');
            $table->integer('tie_breaker_a');
            $table->integer('tie_breaker_b');
            $table->integer('winner_id');
            $table->integer('looser_id');
            $table->integer('set');          
            $table->integer('aces');
            $table->integer('double_faults');
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
        Schema::drop('tennis_sets');
    }
}
