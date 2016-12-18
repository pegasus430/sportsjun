<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCricketScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cricket_overwise_score', function (Blueprint $table) {
            $table->integer('bowler_id')->after('over');
            $table->integer('total_overs')->after('match_id')->nullable();
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cricket_overwise_score', function (Blueprint $table) {
            $table->dropColumn('bowler_id');
            $table->dropColumn('total_overs');
        });
    }
}
