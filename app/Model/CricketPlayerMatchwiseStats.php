<?php

namespace App\Model;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
class CricketPlayerMatchwiseStats extends Model {
	use SoftDeletes,
	Eloquence;	
    
    protected $table = 'cricket_player_matchwise_stats';
    protected $fillable = [
        'tournament_id',
        'team_id',
        'user_id',
        'match_id'
    ];

    public static function insertBatsmenScore($user_id, $tournament_id, $match_id, $team_id, $match_type, $balls_played, $totalruns, $fours, $sixes, $out_as, $strikerate, $team_name, $player_name, $innings, $fielder_id, $bowled_id, $bat_status, $sr_no_in_batting_team = 0, $fifties = 0, $hundreds = 0)
    {
        $model = new CricketPlayerMatchwiseStats();
        $model->user_id = $user_id;
        $model->tournament_id = $tournament_id;
        $model->match_id = $match_id;
        $model->team_id = $team_id;
        $model->match_type = $match_type;
        $model->balls_played = $balls_played;
        $model->totalruns = $totalruns;
        $model->fours = $fours;
        $model->sixes = $sixes;
        $model->out_as = $out_as;
        $model->strikerate = $strikerate;
        $model->team_name = $team_name;
        $model->player_name = $player_name;
        $model->innings = $innings;
        $model->fielder_id = $fielder_id;
        $model->bowled_id = $bowled_id;
        $model->bat_status = $bat_status;
        $model->sr_no_in_batting_team = $sr_no_in_batting_team;
        $model->fifties = $fifties;
        $model->hundreds = $hundreds;
        $model->save();
    }

    public static function insertBowlerScore($bowler_id, $tournament_id, $match_id, $team_id, $match_type, $overs_bowled, $wickets, $runs_conceded, $ecomony, $team_name, $bowler_name, $inning, $wide, $noball, $maidens, $sr_no_in_bowling_team = 0)
    {
        $bowler_model = new CricketPlayerMatchwiseStats();
        $bowler_model->user_id = $bowler_id;
        $bowler_model->tournament_id = $tournament_id;
        $bowler_model->match_id = $match_id;
        $bowler_model->team_id = $team_id;
        $bowler_model->match_type = $match_type;
        $bowler_model->overs_bowled = $overs_bowled;
        $bowler_model->wickets = $wickets;
        $bowler_model->runs_conceded = $runs_conceded;
        $bowler_model->ecomony = $ecomony;
        $bowler_model->team_name = $team_name;
        $bowler_model->player_name = $bowler_name;
        $bowler_model->innings = $inning;
        $bowler_model->wides_bowl = $wide;
        $bowler_model->noballs_bowl = $noball;
        $bowler_model->overs_maiden = $maidens;
        $bowler_model->sr_no_in_bowling_team = $sr_no_in_bowling_team;
        $bowler_model->save();
    }


}
