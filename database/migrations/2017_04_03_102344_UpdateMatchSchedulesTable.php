<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMatchSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('match_schedules', function (Blueprint $table) {
        //     $table->text('a_playing_players')->nullable();
        //     $table->text('b_playing_players')->nullable();

        //     $table->string('a_sub',255)->nullable();
        //     $table->string('b_sub',255)->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('match_schedules', function (Blueprint $table) {
        //     $table->dropColumn('a_playing_players');
        //     $table->dropColumn('b_playing_players');
        //     $table->dropColumn('a_sub');
        //     $table->dropColumn('b_sub');
        // });
    }
}
