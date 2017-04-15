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

        /** @var CricketOverwiseScore $score */
        $score = CricketOverwiseScore::firstOrCreate(
            [
                'tournamet_id' => $data['Tournament_Id'],
                'team_id' => $data['Team_Id'],
                'match_id' => $data['Match_Id'],
                'bowler_id' => $data['Bowler_Id'],
            ]
        );

        $ball_no = $data['Ball_No'];
        $score->over = $data['Current_Over'];
        $score->total_overs = $data['Total_Overs'];

        $ball_by_ball = $score->ball_by_ball ? $score->ball_by_ball : [];
        $team = Team::whereId($data['Team_Id'])->first();
        $match = MatchSchedule::whereId($data['Match_Id'])->first();

        if ($team && $match) {
            $bowl_data_fields = ['BatsMan_Id', 'CBall_Runs', 'CBall_Fours', 'CBall_Sixes', 'CBall_NoBall', 'CBall_Wide', 'CBall_Bye', 'CBall_Leg_Bye',
                'CBall_Synced', 'Opponent_Player_Id', 'Bowled', 'Caught', 'Handled_The_Ball', 'Hit_The_Ball_Twice', 'Hit_Wicket',
                'LBW', 'Stumped', 'RunOut', 'Retired', 'Obstructing_The_Filed'];

            $bowl_data = [];
            foreach ($bowl_data_fields as $field) {
                $bowl_data[$field] = $data[$field];
            }

            $ball_by_ball[$ball_no] = $bowl_data;

            $player = User::whereId($bowl_data['BatsMan_Id'])->first();

            if ($player) {
                /** @var CricketPlayerMatchwiseStats $stats */
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

                if (isset($data['Bowled']) && $data['Bowled'])
                    $stats->out_as = 'bowled';
                if (isset($data['Caught']) && $data['Caught'])
                    $stats->out_as = 'caught';
                if (isset($data['Handled_The_Ball']) && $data['Handled_The_Ball'])
                    $stats->out_as = 'handled_ball';
                if (isset($data['Hit_The_Ball_Twice']) && $data['Hit_The_Ball_Twice'])
                    $stats->out_as = 'hit_ball_twice';
                if (isset($data['Hit_Wicket']) && $data['Hit_Wicket'])
                    $stats->out_as = 'hit_wicket';
                if (isset($data['LBW']) && $data['LBW'])
                    $stats->out_as = 'lbw';
                if (isset($data['Stumped']) && $data['Stumped'])
                    $stats->out_as = 'stumped';
                if (isset($data['RunOut']) && $data['RunOut'])
                    $stats->out_as = 'run_out';
                if (isset($data['Retired']) && $data['Retired'])
                    $stats->out_as = 'retired';
                if (isset($data['Obstructing_The_Filed']) && $data['Obstructing_The_Filed'])
                    $stats->out_as = 'obstructing_the_field';

                if (isset($data['Bat_PlayerName']) && $data['Bat_PlayerName']) {
                    $stats->innings = array_get($data,'Bat_Innings');
                    $stats->totalruns = array_get($data,'Bat_TotalRuns');
                    $stats->balls_played = array_get($data,'Bat_BallsPlayed');
                    $stats->fifties = array_get($data,'Bat_Fifties');
                    $stats->hundreds = array_get($data,'Bat_Hundreds');
                    # $player_Batman['Total_Fours');
                    # $player_Batman['Total_Sixes');
                    $stats->average_bat = array_get($data,'Bat_AverageBat');
                    $stats->strikerate = array_get($data,'Bat_StrikeRate');
                }


                if (isset($data['Bowler_Name']) && $data['Bowler_Name']) {
                    $stats->overs_bowled = array_get($data,'Bowler_Overs_Bowled');
                    $stats->overs_maiden = array_get($data,'Bowler_Overs_Maiden');
                    $stats->wickets = array_get($data,'Bowler_Wickets');
                    $stats->fours = array_get($data,'Bowler_Fours');
                    $stats->sixes = array_get($data,'Bowler_Sixes');

                    $stats->wides_bowl = array_get($data,'Bowler_Wides_Bowl');
                    $stats->noballs_bowl = array_get($data,'Bowler_NoBalls_Bowl');
                    $stats->average_bowl = array_get($data,'Bowler_Average_Bowl');
                    $stats->ecomony = array_get($data,'Bowler_Ecomony');
                    $stats->runs_conceded = array_get($data,'Bowler_TotalRuns');


                    #$rules["Bowler_Name"] = 'string';
                    #$rules["Bowler_Innings"] = 'string';
                }

                if (isset($data['Fielder_Id']) && $data['Fielder_Id']) {
                    $stats->fielder_id = array_get($data,'Fielder_Id');
                    $stats->catches = array_get($data,'Fielder_Catches');
                    $stats->stumpouts = array_get($data,'Fielder_StumpOuts');
                    $stats->runouts = array_get($data,'Fielder_RunOuts');

                    #  $rules["Fielder_Name"] = 'string';
                    #  $rules["Fielder_Innings"] = 'string';

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
        return self::ApiResponse(['Ok']);
    }

}
