<?php

namespace App\Model;

use App\Helpers\Helper;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use Validator;

class CricketPlayerMatchwiseStats extends Model
{
	use SoftDeletes,
	Eloquence;	
    
    protected $table = 'cricket_player_matchwise_stats';
    protected $fillable = [
        'tournament_id',
        'team_id',
        'user_id',
        'match_id'
    ];

    public static function insertScoreCard($matchSchedule, $data, $userId = false)
    {
        $validator = Validator::make($data, [
            'a_player_count' => 'required|number',
            'b_player_count' => 'required|number',
        ]);
        if ($validator->fails()) {
            return false;
        } else {
            $data = Helper::fillArrayMissingFields($data, [
                'winner_team_id',
                'hid_match_result',
                'team_a_id',
                'team_b_id',
                'tournament_id',
                'match_type',
                'match_report',
                'player_of_the_match',
                'toss_won_by',
                'toss_won_team_name'
            ]);

            $inning = !empty($data) ? $data['inning'] : 'first';
            if (!$userId)
                $userId = \Auth::user()->id;

            //delete old stats
            CricketPlayerMatchwiseStats::where('match_id', $matchSchedule->id)
                                       ->where('innings', $inning)
                                       ->update(['deleted_at' => Carbon::now()]);

            for ($i = 1; $i <= $data['a_player_count']; $i++)//insert team a batsman score
            {
                $user_id_a = !empty(array_get($data, 'a_player_' . $i)) ? array_get($data, 'a_player_' . $i) : 0;

                $totalruns_a = (is_numeric(array_get($data, 'a_runs_' . $i))) ? array_get($data, 'a_runs_' . $i) : 0;
                $balls_played_a = (is_numeric(array_get($data, 'a_balls_' . $i))) ? array_get($data, 'a_balls_' . $i) : 0;
                $fours_a = (is_numeric(array_get($data, 'a_fours_' . $i))) ? array_get($data, 'a_fours_' . $i) : 0;
                $sixes_a = (is_numeric(array_get($data, 'a_sixes_' . $i))) ? array_get($data, 'a_sixes_' . $i) : 0;
                $out_as_a = array_get($data, 'a_outas_' . $i);
                $strikerate_a = array_get($data, 'a_strik_rate_' . $i);
                $fielder_id_a = 0;
                $bowled_id_a = array_get($data, 'a_bowled_' . $i);
                if ($out_as_a == 'caught' || $out_as_a == 'run_out' || $out_as_a == 'stumped') {
                    $fielder_id_a = array_get($data, 'a_fielder_' . $i);
                }
                if ($out_as_a == 'handled_ball' || $out_as_a == 'obstructing_the_field' || $out_as_a == 'retired' || $out_as_a == 'timed_out' || $out_as_a == 'not_out') {
                    $bowled_id_a = 0;
                }


                if ($user_id_a > 0) {
                    $player_name = User::where('id', $user_id_a)->pluck('name');
                    //check already player exists r not
                    $is_player_exist = CricketPlayerMatchwiseStats::select()->where('match_id', $matchSchedule->id)
                                                                  ->where('team_id', $matchSchedule->a_id)
                                                                  ->where('user_id', $user_id_a)
                                                                  ->where('innings', $inning)->get()->first();

                    //bat status
                    $a_bat_status = 'notout';
                    if ($out_as_a != '') {
                        $a_bat_status = 'out';
                    } else if (!($totalruns_a > 0 || $balls_played_a > 0 || $fours_a > 0 || $sixes_a > 0)) {
                        $a_bat_status = 'dnb';
                    }

                    // 50's and 100's - start
                    $fifties = $hundreds = 0;

                    if ((int)$totalruns_a > 50) {
                        $fifties = (int)floor($totalruns_a / 50);
                    }

                    if ((int)$totalruns_a > 100) {
                        $hundreds = (int)floor($totalruns_a / 100);
                    }

                    if (count($is_player_exist) > 0)// if player already exist
                    {
                        $update_id = $is_player_exist['id'];
                        CricketPlayerMatchwiseStats::where('id', $update_id)
                                                   ->update([
                                                       'balls_played'          => $balls_played_a,
                                                       'totalruns'             => $totalruns_a,
                                                       'fours'                 => $fours_a,
                                                       'sixes'                 => $sixes_a,
                                                       'out_as'                => $out_as_a,
                                                       'strikerate'            => $strikerate_a,
                                                       'fielder_id'            => $fielder_id_a,
                                                       'bowled_id'             => $bowled_id_a,
                                                       'bat_status'            => $a_bat_status,
                                                       'sr_no_in_batting_team' => $i,
                                                       'fifties'               => $fifties,
                                                       'hundreds'              => $hundreds
                                                   ]);
                    } else {
                        self::insertBatsmenScore($user_id_a, $matchSchedule->tournament_id, $matchSchedule->id,
                            $matchSchedule->a_id,
                            $matchSchedule->match_type, $balls_played_a,
                            $totalruns_a, $fours_a, $sixes_a, $out_as_a, $strikerate_a,
                            $matchSchedule->sideA->name,
                            $player_name, $inning, $fielder_id_a, $bowled_id_a, $a_bat_status, $i, $fifties, $hundreds);
                    }


                }
            }
            //Team a bowler Detail
            for ($j = 1; $j <= $data['a_bowler_count']; $j++) {
                $bowler_id_a = !empty(array_get($data, 'a_bowler_' . $j)) ? array_get($data, 'a_bowler_' . $j) : 0;
                $bowler_name = User::where('id', $bowler_id_a)->pluck('name');
                $a_overs_bowled = array_get($data, 'a_bowler_overs_' . $j);
                $a_wickets = (is_numeric(array_get($data, 'a_bowler_wkts_' . $j))) ? array_get($data, 'a_bowler_wkts_' . $j) : 0;
                $a_runs_conceded = (is_numeric(array_get($data, 'a_bowler_runs_' . $j))) ? array_get($data, 'a_bowler_runs_' . $j) : 0;
                $a_ecomony = array_get($data, 'a_ecomony_' . $j);
                $a_wide = !empty(array_get($data, 'a_bowler_wide_' . $j)) ? array_get($data, 'a_bowler_wide_' . $j) : 0;
                $a_noball = !empty(array_get($data, 'a_bowler_noball_' . $j)) ? array_get($data, 'a_bowler_noball_' . $j) : 0;
                $a_maidens = !empty(array_get($data, 'a_bowler_maidens_' . $j)) ? array_get($data, 'a_bowler_maidens_' . $j) : 0;


                if ($bowler_id_a > 0 && ($a_overs_bowled > 0 || $a_wickets > 0 || $a_runs_conceded > 0)) {
                    //check already bowler exists r not
                    $is_bowler_exist = CricketPlayerMatchwiseStats::select()->where('match_id', $matchSchedule->id)
                                                                  ->where('team_id', $matchSchedule->a_id)
                                                                  ->where('user_id', $bowler_id_a)
                                                                  ->where('innings', $inning)->get()->first();

                    if (count($is_bowler_exist) > 0) // if player already exist
                    {
                        $bowler_id = $is_bowler_exist['id'];
                        CricketPlayerMatchwiseStats::where('id', $bowler_id)->update([
                            'overs_bowled'  => $a_overs_bowled, 'wickets' => $a_wickets,
                            'runs_conceded' => $a_runs_conceded, 'ecomony' => $a_ecomony, 'wides_bowl' => $a_wide,
                            'noballs_bowl'  => $a_noball, 'overs_maiden' => $a_maidens, 'sr_no_in_bowling_team' => $j]);

                    } else {
                        self::insertBowlerScore($bowler_id_a, $matchSchedule->tournament_id, $matchSchedule->id, $matchSchedule->a_id,
                            $matchSchedule->match_type, $a_overs_bowled, $a_wickets, $a_runs_conceded, $a_ecomony,
                            $matchSchedule->sideA->name, $bowler_name, $inning, $a_wide, $a_noball, $a_maidens, $j);

                    }

                }

            }
            //Team b innings
            for ($k = 1; $k <= $data['b_player_count']; $k++)//insert team a batsman score
            {
                $user_id_b = !empty(array_get($data, 'b_player_' . $k)) ? array_get($data, 'b_player_' . $k) : 0;
                $player_b_name = User::where('id', $user_id_b)->pluck('name');
                $totalruns_b = (is_numeric(array_get($data, 'b_runs_' . $k))) ? array_get($data, 'b_runs_' . $k) : 0;
                $balls_played_b = (is_numeric(array_get($data, 'b_balls_' . $k))) ? array_get($data, 'b_balls_' . $k) : 0;
                $fours_b = (is_numeric(array_get($data, 'b_fours_' . $k))) ? array_get($data, 'b_fours_' . $k) : 0;
                $sixes_b = (is_numeric(array_get($data, 'b_sixes_' . $k))) ? array_get($data, 'b_sixes_' . $k) : 0;
                $out_as_b = array_get($data, 'b_outas_' . $k);
                $strikerate_b = array_get($data, 'b_strik_rate_' . $k);
                $bowled_id_b = array_get($data, 'b_bowled_' . $k);
                $fielder_id_b = 0;

                if ($out_as_b == 'caught' || $out_as_b == 'run_out' || $out_as_b == 'stumped') {
                    $fielder_id_b = array_get($data, 'b_fielder_' . $k);
                }
                if ($out_as_b == 'handled_ball' || $out_as_b == 'obstructing_the_field' || $out_as_b == 'retired' || $out_as_b == 'timed_out' || $out_as_b == 'not_out') {
                    $bowled_id_b = 0;
                }
                if ($user_id_b > 0) {
                    //check already player exists r not
                    $is_b_player_exist = CricketPlayerMatchwiseStats::select()->where('match_id', $matchSchedule->id)
                                                                    ->where('team_id', $matchSchedule->b_id)
                                                                    ->where('user_id', $user_id_b)
                                                                    ->where('innings', $inning)->get()->first();


                    //bat status
                    $b_bat_status = 'notout';
                    if ($out_as_b != '') {
                        $b_bat_status = 'out';
                    } else if (!($totalruns_b > 0 || $balls_played_b > 0 || $fours_b > 0 || $sixes_b > 0)) {
                        $b_bat_status = 'dnb';
                    }

                    // 50's and 100's - start
                    $fifties = $hundreds = 0;

                    if ((int)$totalruns_b > 50) {
                        $fifties = (int)($totalruns_b / 50);
                    }

                    if ((int)$totalruns_b > 100) {
                        $hundreds = (int)($totalruns_b / 100);
                    }

                    if (count($is_b_player_exist) > 0) {
                        $update_id = $is_b_player_exist['id'];
                        CricketPlayerMatchwiseStats::where('id', $update_id)
                                                   ->update([
                                                       'balls_played'          => $balls_played_b,
                                                       'totalruns'             => $totalruns_b,
                                                       'fours'                 => $fours_b,
                                                       'sixes'                 => $sixes_b,
                                                       'out_as'                => $out_as_b,
                                                       'strikerate'            => $strikerate_b,
                                                       'fielder_id'            => $fielder_id_b,
                                                       'bowled_id'             => $bowled_id_b,
                                                       'bat_status'            => $b_bat_status,
                                                       'sr_no_in_batting_team' => $k,
                                                       'fifties'               => $fifties,
                                                       'hundreds'              => $hundreds
                                                   ]);
                    } else {
                        self::insertBatsmenScore($user_id_b, $matchSchedule->tournament_id, $matchSchedule->id, $matchSchedule->b_id, $matchSchedule->match_type,
                            $balls_played_b, $totalruns_b, $fours_b, $sixes_b, $out_as_b, $strikerate_b,
                            $matchSchedule->sideB->name, $player_b_name, $inning, $fielder_id_b, $bowled_id_b, $b_bat_status, $k, $fifties, $hundreds);
                    }


                }
            }
            //Team b bowler Detail
            for ($l = 1; $l <= $data['b_bowler_count']; $l++) {
                $bowler_id_b = !empty(array_get($data, 'b_bowler_' . $l)) ? array_get($data, 'b_bowler_' . $l) : 0;
                $bowler_b_name = User::where('id', $bowler_id_b)->pluck('name');
                $b_overs_bowled = array_get($data, 'b_bowler_overs_' . $l);
                $b_wickets = (is_numeric(array_get($data, 'b_bowler_wkts_' . $l))) ? array_get($data, 'b_bowler_wkts_' . $l) : 0;
                $b_runs_conceded = (is_numeric(array_get($data, 'b_bowler_runs_' . $l))) ? array_get($data, 'b_bowler_runs_' . $l) : 0;
                $b_ecomony = array_get($data, 'b_ecomony_' . $l);
                $b_wide = !empty(array_get($data, 'b_bowler_wide_' . $l)) ? array_get($data, 'b_bowler_wide_' . $l) : 0;
                $b_noball = !empty(array_get($data, 'b_bowler_noball_' . $l)) ? array_get($data, 'b_bowler_noball_' . $l) : 0;
                $b_maidens = !empty(array_get($data, 'b_bowler_maidens_' . $l)) ? array_get($data, 'b_bowler_maidens_' . $l) : 0;

                if ($bowler_id_b > 0 && ($b_overs_bowled > 0 || $b_wickets > 0 || $b_runs_conceded > 0)) {
                    //check already bowler exists r not
                    $is_bowler_b_exist = CricketPlayerMatchwiseStats::select()->where('match_id', $matchSchedule->id)
                                                                    ->where('team_id', $matchSchedule->b_id)
                                                                    ->where('user_id', $bowler_id_b)
                                                                    ->where('innings', $inning)->get()->first();

                    if (count($is_bowler_b_exist) > 0) {
                        $bowler_id = $is_bowler_b_exist['id'];
                        CricketPlayerMatchwiseStats::where('id', $bowler_id)->update([
                            'overs_bowled'  => $b_overs_bowled, 'wickets' => $b_wickets,
                            'runs_conceded' => $b_runs_conceded, 'ecomony' => $b_ecomony, 'wides_bowl' => $b_wide,
                            'noballs_bowl'  => $b_noball, 'overs_maiden' => $b_maidens, 'sr_no_in_bowling_team' => $l]);

                    } else {
                        self::insertBowlerScore($bowler_id_b, $matchSchedule->tournament_id, $matchSchedule->id, $matchSchedule->b_id, 
                            $matchSchedule->match_type, $b_overs_bowled, $b_wickets, $b_runs_conceded, $b_ecomony,
                            $matchSchedule->sideB->name, $bowler_b_name, $inning, $b_wide, $b_noball, $b_maidens, $l);
                    }


                }

            }
            //Team b bowling

            //Fall of Wikets for Team a
            $team_a_fallofwkt_count = array_get($data, 'a_fall_of_count');
            $team_a_array = [];
            for ($m = 1; $m <= $team_a_fallofwkt_count; $m++) {
                $wkt_a_at = (is_numeric(array_get($data, 'a_wicket_' . $m))) ? array_get($data, 'a_wicket_' . $m) : 0;
                $player_a_id = array_get($data, 'a_wkt_player_' . $m);
                $at_runs_a = (is_numeric(array_get($data, 'a_at_runs_' . $m))) ? array_get($data, 'a_at_runs_' . $m) : 0;
                $at_over_a = array_get($data, 'a_at_over_' . $m);

                if ($player_a_id > 0) {
                    $team_a_array[] = [
                        'wicket' => $wkt_a_at, 'batsman' => $player_a_id, 'score' => $at_runs_a, 'over' => $at_over_a];
                }

            }
            //team a extras
            $team_a_wide = !empty(array_get($data, 'team_a_wide')) ? array_get($data, 'team_a_wide') : 0;
            $team_a_noball = !empty(array_get($data, 'team_a_noball')) ? array_get($data, 'team_a_noball') : 0;
            $team_a_legbye = !empty(array_get($data, 'team_a_legbye')) ? array_get($data, 'team_a_legbye') : 0;
            $team_a_bye = !empty(array_get($data, 'team_a_bye')) ? array_get($data, 'team_a_bye') : 0;
            $team_a_others = !empty(array_get($data, 'team_a_others')) ? array_get($data, 'team_a_others') : 0;
            $team_a_extras_array = [
                'wide'   => $team_a_wide, 'noball' => $team_a_noball, 'legbye' => $team_a_legbye, 'bye' => $team_a_bye,
                'others' => $team_a_others];

            $team_level_stats = [];

            //team a scores
            foreach ([$matchSchedule->a_id, $matchSchedule->b_id] as $teamStat_team_id) {
                foreach (['first', 'second'] as $teamStat_innings_name) {
                    foreach (['score', 'wickets', 'overs'] as $teamStat_inning_stat_name) {
                        $value = '';
                        if (isset($request[$teamStat_innings_name . '_inning'][$teamStat_team_id][$teamStat_inning_stat_name])) {
                            $value = $request[$teamStat_innings_name . '_inning'][$teamStat_team_id][$teamStat_inning_stat_name];
                        }
                        $team_level_stats[$teamStat_team_id][$teamStat_innings_name][$teamStat_inning_stat_name] = (!empty($value) && $value > 0) ? $value : NULL;
                    }
                }
            }

            $team_a_fst_ing_score = $team_level_stats[$matchSchedule->a_id]['first']['score'];
            $team_a_fst_ing_wkt = $team_level_stats[$matchSchedule->a_id]['first']['wickets'];
            $team_a_fst_ing_overs = $team_level_stats[$matchSchedule->a_id]['first']['overs'];
            $team_a_scnd_ing_score = $team_level_stats[$matchSchedule->a_id]['second']['score'];
            $team_a_scnd_ing_wkt = $team_level_stats[$matchSchedule->a_id]['second']['wickets'];
            $team_a_scnd_ing_overs = $team_level_stats[$matchSchedule->a_id]['second']['overs'];

            $team_a_score = [
                'fst_ing_score' => $team_a_fst_ing_score, 'scnd_ing_score' => $team_a_scnd_ing_score,
                'fst_ing_wkt'   => $team_a_fst_ing_wkt, 'scnd_ing_wkt' => $team_a_scnd_ing_wkt,
                'fst_ing_overs' => $team_a_fst_ing_overs, 'scnd_ing_overs' => $team_a_scnd_ing_overs];

            //team b scores
            $team_b_fst_ing_score = $team_level_stats[$matchSchedule->b_id]['first']['score'];
            $team_b_fst_ing_wkt = $team_level_stats[$matchSchedule->b_id]['first']['wickets'];
            $team_b_fst_ing_overs = $team_level_stats[$matchSchedule->b_id]['first']['overs'];
            $team_b_scnd_ing_score = $team_level_stats[$matchSchedule->b_id]['second']['score'];
            $team_b_scnd_ing_wkt = $team_level_stats[$matchSchedule->b_id]['second']['wickets'];
            $team_b_scnd_ing_overs = $team_level_stats[$matchSchedule->b_id]['second']['overs'];

            //team b extras
            $team_b_wide = !empty(array_get($data, 'team_b_wide')) ? array_get($data, 'team_b_wide') : 0;
            $team_b_noball = !empty(array_get($data, 'team_b_noball')) ? array_get($data, 'team_b_noball') : 0;
            $team_b_legbye = !empty(array_get($data, 'team_b_legbye')) ? array_get($data, 'team_b_legbye') : 0;
            $team_b_bye = !empty(array_get($data, 'team_b_bye')) ? array_get($data, 'team_b_bye') : 0;
            $team_b_others = !empty(array_get($data, 'team_b_others')) ? array_get($data, 'team_b_others') : 0;
            $team_b_extras_array = [
                'wide'   => $team_b_wide, 'noball' => $team_b_noball, 'legbye' => $team_b_legbye, 'bye' => $team_b_bye,
                'others' => $team_b_others];

            $fallOfCount_a[$matchSchedule->a_id][$inning] = $team_a_array + $team_a_extras_array;//indvidual team fall of count

            $team_a_two_ings_score[$matchSchedule->a_id] = $team_a_score; //team a toral score with overs wikets

            $team_a_match_details = array_replace_recursive($fallOfCount_a, $team_a_two_ings_score);// merge fall of wkts, match score details

            $decode_json = [];
            //get exists match details
            $get_match_details = MatchSchedule::where('id', $matchSchedule->id)->pluck('match_details');
            if ($get_match_details != '')
                $decode_json = json_decode($get_match_details, true);


            //Fall of Wikets for Team b
            $team_b_fallofwkt_count = array_get($data, 'b_fall_of_count');
            $team_b_array = [];
            for ($n = 1; $n <= $team_b_fallofwkt_count; $n++) {
                $wkt_b_at = (is_numeric(array_get($data, 'b_wicket_' . $n))) ? array_get($data, 'b_wicket_' . $n) : 0;
                $player_b_id = array_get($data, 'b_wkt_player_' . $n);
                $at_runs_b = (is_numeric(array_get($data, 'b_at_runs_' . $n))) ? array_get($data, 'b_at_runs_' . $n) : 0;
                $at_over_b = array_get($data, 'b_at_over_' . $n);
                if ($player_b_id > 0) {
                    $team_b_array[] = [
                        'wicket' => $wkt_b_at, 'batsman' => $player_b_id, 'score' => $at_runs_b, 'over' => $at_over_b];
                }
            }

            $team_b_score = [
                'fst_ing_score' => $team_b_fst_ing_score, 'scnd_ing_score' => $team_b_scnd_ing_score,
                'fst_ing_wkt'   => $team_b_fst_ing_wkt, 'scnd_ing_wkt' => $team_b_scnd_ing_wkt,
                'fst_ing_overs' => $team_b_fst_ing_overs, 'scnd_ing_overs' => $team_b_scnd_ing_overs];

            $fallOfCount_b[$matchSchedule->b_id][$inning] = $team_b_array + $team_b_extras_array;//indvidual team fall of count

            $team_b_two_ings_score[$matchSchedule->b_id] = $team_b_score; //team a toral score with overs wikets

            $team_b_match_details = array_replace_recursive($fallOfCount_b, $team_b_two_ings_score); // merge fall of wkts, match score details

            //get previous scorecard status data
            $scorecardDetails = MatchSchedule::where('id', $matchSchedule->id)->pluck('score_added_by');
            $decode_scorecard_data = json_decode($scorecardDetails, true);

            $modified_users = !empty($decode_scorecard_data['modified_users']) ? $decode_scorecard_data['modified_users'] : '';

            $modified_users = $modified_users . ',' . $userId;//scorecard changed users

            $added_by = !empty($decode_scorecard_data['added_by']) ? $decode_scorecard_data['added_by'] : $userId;


            //insert toss_won_by  and selected batting or bowling in scoer added by column
            $toss_won_by = !empty($decode_scorecard_data['toss_won_by']) ? $decode_scorecard_data['toss_won_by'] : $data['tossWonBy'];
            $tossWonTeamName = !empty($decode_scorecard_data['toss_won_team_name']) ? $decode_scorecard_data['toss_won_team_name'] : $data['toss_won_team_name'];
            $fst_ing_batting = !empty($decode_scorecard_data['fst_ing_batting']) ? $decode_scorecard_data['fst_ing_batting'] : $matchSchedule->a_id;
            $scnd_ing_batting = !empty($decode_scorecard_data['scnd_ing_batting']) ? $decode_scorecard_data['scnd_ing_batting'] : $matchSchedule->a_id;


            //score card approval process
            $score_status = [
                'added_by'        => $added_by, 'active_user' => $userId, 'modified_users' => $modified_users,
                'rejected_note'   => '', 'toss_won_by' => $toss_won_by, 'toss_won_team_name' => $tossWonTeamName,
                'fst_ing_batting' => $fst_ing_batting, 'scnd_ing_batting' => $scnd_ing_batting];

            $json_score_status = json_encode($score_status);

            return [
                'team_a_match_details'=>$team_a_match_details,
                'team_b_match_details'=>$team_b_match_details,
                'decoded_json'=>$decode_json,
                'socre_status'=>$json_score_status,
            ];
        }
    }

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
