<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchScheduleAthleticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_schedules_athletics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id');
            $table->integer('tournament_group_id');
            $table->integer('tournament_round_number');
            $table->integer('tournament_match_number');
            $table->integer('sports_id');
            $table->integer('facility_id');
            $table->string('facility_name');
            $table->integer('created_by');
            $table->enum('match_category',['men','women','mixed']);
            $table->enum('schedule_type',['team','player']);
            $table->enum('match_type',['singles','doubles','mixed']);
            $table->date('match_start_date');
            $table->time('match_start_time');
            $table->date('match_end_date');
            $table->time('match_end_time');
            $table->string('match_location');
            $table->float('longitude');
            $table->float('latitude');
            $table->string('address');
            $table->integer('city_id');
            $table->string('city');
            $table->integer('state_id');
            $table->string('state');
            $table->integer('country_id');
            $table->string('country');
            $table->string('zip');
            $table->enum('match_status',['scheduled','completed','cancelled']);
            $table->enum('match_invite_status',['accepted','rejected','pending']);
            $table->integer('a_id');
            $table->integer('b_id');
            $table->text('player_a_ids');
            $table->text('player_b_ids');
            $table->integer('winner_id');
            $table->integer('looser_id');
            $table->tinyInteger('is_tied');
            $table->text('match_details');
            $table->tinyInteger('hasSetupSquad');
            $table->text('match_report');
            $table->integer('player_of_the_match');
            $table->enum('scoring_status',['approval','pending','approved','rejected']);
            $table->text('score_added_by');
            $table->tinyInteger('isactive');
            $table->enum('has_result',[0,1]);
            $table->enum('match_result',['win','tie','washout']);
            $table->enum('game_type',['normal','rubber']);
            $table->integer('number_of_rubber');
            $table->integer('a_score');
            $table->integer('b_score');
            $table->tinyInteger('is_third_position');
            $table->integer('selected_half_or_quarter');
            $table->integer('number_of_players');
            $table->text('players_list');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('match_schedules_athletics');
    }
}
