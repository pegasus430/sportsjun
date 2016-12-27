<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateArcheryPlayerStatsAddRoundId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archery_player_stats', function (Blueprint $table) {
            //
            $table->integer('round_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archery_player_stats', function (Blueprint $table) {
            //
            $table->dropColumn('round_id');
        });
    }
}
