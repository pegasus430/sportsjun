<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTennisGameAddGameAcesAndDoubleFaults extends Migration
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
            $table->integer('double_faults');
            $table->integer('aces');
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
