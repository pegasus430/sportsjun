<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTennisGameAddGameNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tennis_sets_game', function (Blueprint $table) {
            //
            $table->integer('game_number');
            $table->integer('set');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tennis_sets_game', function (Blueprint $table) {
            //
        });
    }
}
