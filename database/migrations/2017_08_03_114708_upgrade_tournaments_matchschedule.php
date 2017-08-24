<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeTournamentsMatchschedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE tournaments CHANGE COLUMN `type` `type` ENUM('league','knockout','multistage','doubleknockout','doublemultistage')");

        Schema::table('match_schedules', function (Blueprint $table) {
            $table->integer('winner_schedule_id');
            $table->string('winner_schedule_position', 5);
            $table->string('winner_go_wl_type', 5);
            $table->integer('loser_schedule_id');
            $table->string('loser_schedule_position', 5);
            $table->string('loser_go_wl_type', 5);
            $table->string('double_wl_type', 5);
            $table->integer('is_knockout');
            
         });

        Schema::table('tournaments', function (Blueprint $table) { 
            $table->integer('noofplaces')->default(1);
            $table->integer('roundofplay')->default(1);
         });

        // data update
        DB::statement("update match_schedules set is_knockout=1 where tournament_group_id  IS NULL");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE tournaments CHANGE COLUMN `type` `type` ENUM('league','knockout','multistage')");

        Schema::table('match_schedules', function (Blueprint $table) {
            //
            $table->dropColumn('winner_schedule_id');
            $table->dropColumn('winner_schedule_position');
            $table->dropColumn('winner_go_wl_type');
            $table->dropColumn('loser_schedule_id');
            $table->dropColumn('loser_schedule_position');
            $table->dropColumn('loser_go_wl_type');
            $table->dropColumn('double_wl_type');
            $table->dropColumn('is_knockout');
        });

        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('noofplaces');
            $table->dropColumn('roundofplay');
         });
    }
}
