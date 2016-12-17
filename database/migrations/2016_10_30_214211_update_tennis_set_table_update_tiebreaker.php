<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTennisSetTableUpdateTiebreaker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tennis_sets', function (Blueprint $table) {
            //
            $table->integer('team_a_tie_breaker');
            $table->integer('team_b_tie_breaker');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tennis_sets', function (Blueprint $table) {
            //
        });
    }
}
