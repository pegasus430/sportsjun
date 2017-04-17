<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class CricketStatistic extends Model {
    use SoftDeletes, Eloquence;
    //
    protected $table = 'cricket_statistic';

    //protected $fillable = array('user_id', 'following_sports', 'following_teams', 'managing_teams', 'joined_teams', 'following_tournaments', 'managing_tournaments', 'joined_tournaments', 'following_players', 'following_facilities', 'provider_id', 'provider_token', 'avatar',);


    public static function cricketBatsmenStatistic($user_id, $match_type, $inning)
    {
        $cricket_statistics = CricketStatistic::where('user_id', $user_id)
                                              ->where('match_type', $match_type)
                                              ->where('innings', $inning)
                                              ->first();

        $batsman_detais = CricketPlayerMatchwiseStats::selectRaw('count(DISTINCT(match_id)) as match_count')
                                                     ->selectRaw('count(innings) as inningscount')
                                                     ->selectRaw('sum(totalruns) as totalruns')
                                                     ->selectRaw('sum(balls_played) as balls_played')
                                                     ->selectRaw('sum(fifties) as fifties')
                                                     ->selectRaw('sum(hundreds) as hundreds')
                                                     ->selectRaw('sum(fours) as fours')
                                                     ->selectRaw('sum(sixes) as sixes')
                                                     ->selectRaw('sum(IF(bat_status="notout", 1, 0)) as notouts')
                                                     ->selectRaw('max(totalruns) as highscore')
                                                     ->where('user_id', $user_id)
                                                     ->where('match_type', $match_type)
                                                     ->where('innings', $inning)
                                                     ->groupBy('user_id')->get();

        $innings_bat = (!empty($batsman_detais[0]['inningscount'])) ? $batsman_detais[0]['inningscount'] : 0;
        $totalruns = (!empty($batsman_detais[0]['totalruns'])) ? $batsman_detais[0]['totalruns'] : 0;
        $totalballs = (!empty($batsman_detais[0]['balls_played'])) ? $batsman_detais[0]['balls_played'] : 0;
        $fours = (!empty($batsman_detais[0]['fours'])) ? $batsman_detais[0]['fours'] : 0;
        $sixes = (!empty($batsman_detais[0]['sixes'])) ? $batsman_detais[0]['sixes'] : 0;
        $match_count = (!empty($batsman_detais[0]['match_count'])) ? $batsman_detais[0]['match_count'] : 0;
        $fifties = (!empty($batsman_detais[0]['fifties'])) ? $batsman_detais[0]['fifties'] : 0;
        $hundreds = (!empty($batsman_detais[0]['hundreds'])) ? $batsman_detais[0]['hundreds'] : 0;
        $notouts = (!empty($batsman_detais[0]['notouts'])) ? $batsman_detais[0]['notouts'] : 0;
        $highscore = (!empty($batsman_detais[0]['highscore'])) ? $batsman_detais[0]['highscore'] : 0;

        if ($cricket_statistics) {
            $average_bat = '';
            if ($totalruns > 0 && $innings_bat > 0) {
                $average_bat = $totalruns / $innings_bat; //total runs / innings bat
            }

            $strikerate = '';
            if ($totalballs > 0) {
                $strikerate = ($totalruns / $totalballs) * 100;//strikerate calculation [total runs/total ball*100]
            }
            CricketStatistic::where('user_id', $user_id)
                            ->where('match_type', $match_type)
                            ->update([
                                'matches'     => $match_count,
                                'innings_bat' => $innings_bat,
                                'totalruns'   => $totalruns,
                                'totalballs'  => $totalballs,
                                'fours'       => $fours,
                                'sixes'       => $sixes,
                                'strikerate'  => $strikerate,
                                'average_bat' => $average_bat,
                                'fifties'     => $fifties,
                                'hundreds'    => $hundreds,
                                'notouts'     => $notouts,
                                'highscore'   => $highscore
                            ]);
        } else {
            $matchcount = (!empty($batsman_detais[0]['match_count'])) ? $batsman_detais[0]['match_count'] : 0;
            $innings_bat = (!empty($batsman_detais[0]['inningscount'])) ? $batsman_detais[0]['inningscount'] : 0;

            $objStatistics = new CricketStatistic();
            $objStatistics->user_id = $user_id;
            $objStatistics->match_type = $match_type;
            $objStatistics->matches = $matchcount;
            $objStatistics->innings_bat = $innings_bat;
            $objStatistics->totalruns = $totalruns;
            $objStatistics->totalballs = $totalballs;
            $objStatistics->fours = $fours;
            $objStatistics->sixes = $sixes;
            $objStatistics->innings = $inning;
            $objStatistics->fifties = $fifties;
            $objStatistics->hundreds = $hundreds;
            $objStatistics->notouts = $notouts;
            $objStatistics->highscore = $highscore;
            $strikerate = "";
            if ($totalballs > 0) {
                $strikerate = ($totalruns / $totalballs) * 100;//strikerate calculation [total runs/total ball*100]
            }
            $objStatistics->average_bat = $totalruns; //total runs / innings bat
            $objStatistics->strikerate = $strikerate;
            $objStatistics->save();
        }
    }

    //cricket bowler statistics
    public static function cricketBowlerStatistic($bowler_id, $match_type, $inning)
    {
        $bowler_cricket_statistics = CricketStatistic::where('user_id', $bowler_id)->where('match_type', $match_type)
                                                     ->where('innings', $inning)->first();

        $bowler_detais = CricketPlayerMatchwiseStats::selectRaw('count(DISTINCT(match_id)) as match_count')
                                                    ->selectRaw('count(innings) as inningscount')
                                                    ->selectRaw('sum(wickets) as wickets')
                                                    ->selectRaw('sum(runs_conceded) as runs_conceded')
                                                    ->selectRaw('sum(overs_bowled) as overs_bowled')
                                                    ->where('user_id', $bowler_id)->where('match_type', $match_type)
                                                    ->where('innings', $inning)->groupBy('user_id')->get();

        $innings_bowl = (!empty($bowler_detais[0]['inningscount'])) ? $bowler_detais[0]['inningscount'] : 0;
        $wickets = (!empty($bowler_detais[0]['wickets'])) ? $bowler_detais[0]['wickets'] : 0;
        $runs_conceded = (!empty($bowler_detais[0]['runs_conceded'])) ? $bowler_detais[0]['runs_conceded'] : 0;
        $overs_bowled = (!empty($bowler_detais[0]['overs_bowled'])) ? $bowler_detais[0]['overs_bowled'] : 0;
        $match_count = (!empty($bowler_detais[0]['match_count'])) ? $bowler_detais[0]['match_count'] : 0;
        if ($bowler_cricket_statistics) {
            $ecomony = '';
            if ($overs_bowled > 0) {
                $ecomony = $runs_conceded / $overs_bowled;
            }
            $average_bowl = '';
            if ($wickets > 0) {
                $average_bowl = $runs_conceded / $wickets;
            }
            CricketStatistic::where('user_id', $bowler_id)->where('match_type', $match_type)
                            ->update(['matches'       => $match_count, 'innings_bowl' => $innings_bowl,
                                      'wickets'       => $wickets, 'runs_conceded' => $runs_conceded,
                                      'overs_bowled'  => $overs_bowled, 'ecomony' => $ecomony,
                                      'average_bowl'  => $average_bowl]);
        } else {
            $matchcount = (!empty($bowler_detais[0]['match_count'])) ? $bowler_detais[0]['match_count'] : 0;;
            $innings_bowl = (!empty($bowler_detais[0]['inningscount'])) ? $bowler_detais[0]['inningscount'] : 0;;

            $objBowlerStatistics = new CricketStatistic();
            $objBowlerStatistics->user_id = $bowler_id;
            $objBowlerStatistics->match_type = $match_type;
            $objBowlerStatistics->matches = $matchcount;
            $objBowlerStatistics->innings_bowl = $innings_bowl;
            $objBowlerStatistics->wickets = $wickets;
            $objBowlerStatistics->runs_conceded = $runs_conceded;
            $objBowlerStatistics->overs_bowled = $overs_bowled;
            $objBowlerStatistics->innings = $inning;
            $ecomony = '';
            if ($overs_bowled > 0) {
                $ecomony = $runs_conceded / $overs_bowled;//economy calculation [total runs/total overs]
            }
            $average_bowl = '';
            if ($wickets > 0) {
                $average_bowl = $runs_conceded / $wickets;//[total runs/total wickets]
            }
            $objBowlerStatistics->ecomony = $ecomony;
            $objBowlerStatistics->save();
        }
    }

}
