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
            $table->integer('is_final_match');
            
         });

        Schema::table('tournaments', function (Blueprint $table) { 
            $table->integer('noofplaces')->default(1);
            $table->integer('roundofplay')->default(1);
         });

        // data update
        DB::statement("update match_schedules set is_knockout=1 where tournament_group_id  IS NULL");

        $results = DB::select("select * from tournaments where  type in ('knockout','multistage','doubleknockout','doublemultistage')");
        foreach( $results as $t )
        {
            $matchs = DB::select("select * from match_schedules where tournament_id='".$t->id."' and tournament_group_id is null ");
            $max_round_no=0;
            $max_match_no=0;

            foreach( $matchs as $m )
            {
                if( $max_round_no < $m->tournament_round_number ) 
                {
                    $max_round_no = $m->tournament_round_number ;
                    $max_match_no = $m->tournament_match_number ;
                }

                if( $max_round_no == $m->tournament_round_number && $max_match_no < $m->tournament_match_number )
                { 
                    $max_match_no = $m->tournament_match_number ;
                }
            }

            // fetch out last team name data ( this is not match )
            $LL = DB::select(" select * from match_schedules  where tournament_id='".$t->id."' and tournament_round_number='".$max_round_no."' and tournament_match_number='".$max_match_no."'");
            foreach($LL as $last)
            {
                $final_team_id = $last->b_id > 0 ? $last->b_id : $last->a_id;
                $final_round_no = $max_round_no - 1;

               // update in final matches  
                DB::statement("update match_schedules set is_final_match=1 where tournament_id='".$t->id."' and tournament_round_number='".$final_round_no."' and  tournament_group_id  IS NULL 
                and ( a_id='".$final_team_id."' or b_id='".$final_team_id."' )");
            }
            DB::table('match_schedules')->where('tournament_id',$t->id)->where('tournament_round_number',$max_round_no)->where('tournament_match_number',$max_match_no)->delete();
        }
        
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
            $table->dropColumn('is_final_match');
        });

        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('noofplaces');
            $table->dropColumn('roundofplay');
         });
    }
}
