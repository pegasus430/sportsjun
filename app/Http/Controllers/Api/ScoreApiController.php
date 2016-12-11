<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SetCricketScoreRequest;
use App\Model\CricketOverwiseScore;
use App\Model\CricketPlayerMatchwiseStats;
use App\Model\MatchSchedule;
use App\Model\Team;
use App\User;

class ScoreApiController extends BaseApiController
{

    public function setScoreCricket(SetCricketScoreRequest $request)
    {
        $data = $request->all();


        $score = CricketOverwiseScore::firstOrCreate(
            [
                'tournamet_id' => $data['Tournament_Id'],
                'team_id' => $data['Team_Id'],
                'match_id' => $data['Match_Id'],
                'bowler_id' => $data['Bowler_Id'],
            ]
        );

        $score->over = $data['Over'];
        $score->total_overs = $data['Total_Overs'];

        $ball_by_ball = [];
        $team = Team::whereId($data['Team_Id'])->first();
        $match = MatchSchedule::whereId($data['Match_Id'])->first();


        if ($team && $match)
            for ($i = 1; $i < $score->over; $i++) {
                $bowl_data = array_get($data, $i, false);

                $wicket = array_get($bowl_data, "Wicket",[]);
                $player_Batman = array_get($bowl_data, "Player_Batman");
                unset($bowl_data["Player_Batman"]);
                $player_Bowler = array_get($bowl_data, "Player_Bowler");
                unset($bowl_data["Player_Bowler"]);
                $player_Fielder = array_get($bowl_data, "Player_Fielder");
                unset($bowl_data["Player_Fielder"]);
                $ball_by_ball[$i] = $bowl_data;

                $player = User::whereId($bowl_data['BatsMan_Id'])->first();

                if ($player) {
                    $stats = CricketPlayerMatchwiseStats::firstOrCreate(
                        [
                            'user_id' => $player->id,
                            'tournament_id' => $data['Tournament_Id'],
                            'match_id' => $data['Match_Id'],
                            'team_id' => $data['Team_Id'],
                            'player_name' => $player->name,
                        ]
                    );
                    $stats->bowled_id = $data['Bowler_Id'];

                    foreach ($wicket as $key => $value) {
                        if ($value) {
                            switch ($key) {
                                case "Opponent_Player_Id":
                                    break;
                                case "Bowled":
                                    $stats->out_as = 'bowled';
                                    break;
                                case "Caught":
                                    $stats->out_as = 'caught';
                                    break;
                                case "Handled_The_Ball":
                                    $stats->out_as = 'handled_ball';
                                    break;
                                case "Hit_The_Ball_Twice":
                                    $stats->out_as = 'hit_ball_twice';
                                    break;
                                case "Hit_Wicket":
                                    $stats->out_as = 'hit_wicket';
                                    break;
                                case "LBW":
                                    $stats->out_as = 'lbw';
                                    break;
                                case "Stumped":
                                    $stats->out_as = 'stumped';
                                    break;
                                case "RunOut":
                                    $stats->out_as = 'run_out';
                                    break;
                                case "Retired":
                                    $stats->out_as = 'retired';
                                    break;
                                case "Obstructing_The_Filed":
                                    $stats->out_as = 'obstructing_the_field';
                                    break;
                            }
                        }
                    }
                    if ($player_Batman) {
                        $stats->innings = $player_Batman['Innings'];
                        $stats->totalruns = $player_Batman['TotalRuns'];
                        $stats->balls_played = $player_Batman['Balls_Played'];
                        $stats->fifties = $player_Batman['Fifties'];
                        $stats->hundreds = $player_Batman['Hundreds'];
                        # $player_Batman['Total_Fours'];
                        # $player_Batman['Total_Sixes'];
                        $stats->average_bat = $player_Batman['Average_Bat'];
                        $stats->strikerate = $player_Batman['StrikeRate'];

                    }


                    if ($player_Bowler) {
                        $stats->overs_bowled = $player_Bowler['Overs_Bowled'];
                        $stats->overs_maiden = $player_Bowler['Overs_Maiden'];
                        $stats->wickets = $player_Bowler ['Wickets'];
                        $stats->fours = $player_Bowler['Fours'];
                        $stats->sixes = $player_Bowler['Sixes'];

                        $stats->wides_bowl = $player_Bowler['Wides_Bowl'];
                        $stats->noballs_bowl = $player_Bowler['Noballs_Bowl'];
                        $stats->average_bowl = $player_Bowler['Average_Bowl'];
                        $stats->ecomony = $player_Bowler['Ecomony'];
                        $stats->runs_conceded = $player_Bowler['TotalRuns'];

                    }

                    if ($player_Fielder && $player_Fielder['Fielder_Id']) {
                        $stats->fielder_id = $player_Fielder['Fielder_Id'];
                        $stats->catches = $player_Fielder['Catches'];
                        $stats->stumpouts = $player_Fielder['StumpOuts'];
                        $stats->runouts = $player_Fielder['RunOuts'];
                    }

                    $stats->team_name = $team->name;
                    $stats->match_type = $match->match_type;

                    #$stats->palyer_role;//`palyer_role` ENUM('c&wk','c','wk','batsmen','bowler','allrounder') NULL DEFAULT NULL,
                     //`innings` ENUM('first','second') NULL DEFAULT NULL,

                    #$stats->is_updated_to_stats;
                    #$stats->bat_status;//        `bat_status` ENUM('dnb','out','notout') NULL DEFAULT NULL,
                    #$stats->player_match_details;
                    #$stats->sr_no_in_batting_team;
                    #$stats->sr_no_in_bowling_team;

                    $stats->save();
                }
            }


        $score->ball_by_ball = $ball_by_ball;
        $score->save();
        return $this->ApiResponse(['Ok']);
    }

}
