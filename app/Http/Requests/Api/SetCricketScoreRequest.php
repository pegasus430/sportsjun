<?php

namespace App\Http\Requests\Api;


class SetCricketScoreRequest extends BaseApiRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'Tournament_Id' => 'required|numeric',
            'Team_Id' => 'required|numeric',
            'Match_Id' => 'required|numeric',
            'Total_Overs' => 'required|numeric',
            'Bowler_Id' => 'required|numeric',
            'Current_Over' => 'required|numeric',
            'Ball_No' => 'required|numeric',
            'synced'=> 'in:YES,NO'
        ];

        $rules["BatsMan_Id"] = 'required|numeric';
        $rules["CBall_Runs"] = 'required|numeric';
        $rules["CBall_Fours"] = 'required|numeric';
        $rules["CBall_Sixes"] = 'required|numeric';
        $rules["CBall_NoBall"] = 'required|numeric';
        $rules["CBall_Wide"] = 'required|numeric';
        $rules["CBall_Bye"] = 'required|numeric';
        $rules["CBall_Leg_Bye"] = 'required|numeric';
        $rules["CBall_Synced"] = '|numeric';

        $rules["Opponent_Player_Id"] = 'numeric';
        $rules["Bowled"] = 'numeric';
        $rules["Caught"] = 'numeric';
        $rules["Handled_The_Ball"] = 'numeric';
        $rules["Hit_The_Ball_Twice"] = 'numeric';
        $rules["Hit_Wicket"] = 'numeric';
        $rules["LBW"] = 'numeric';
        $rules["Stumped"] = 'numeric';
        $rules["RunOut"] = 'numeric';
        $rules["Retired"] = 'numeric';
        $rules["Obstructing_The_Filed"] = 'numeric';

        $rules["Bat_PlayerName"] = 'string';
        $rules["Bat_Innings"] = 'string';
        $rules["Bat_TotalRuns"] = 'numeric';
        $rules["Bat_BallsPlayed"] = 'numeric';
        $rules["Bat_Fifties"] = 'numeric';
        $rules["Bat_Hundreds"] = 'numeric';
        $rules["Bat_TotalFours"] = 'numeric';
        $rules["Bat_TotalSixes"] = 'numeric';
        $rules["Bat_AverageBat"] = 'numeric';
        $rules["Bat_StrikeRate"] = 'numeric';


        $rules["Bowler_Name"] = 'string';
        $rules["Bowler_Innings"] = 'string';
        $rules["Bowler_TotalRuns"] = 'numeric';
        $rules["Bowler_Fours"] = 'numeric';
        $rules["Bowler_Sixes"] = 'numeric';
        $rules["Bowler_Wickets"] = 'numeric';
        $rules["Bowler_Overs_Bowled"] = 'numeric';
        $rules["Bowler_Overs_Maiden"] = 'numeric';
        $rules["Bowler_Wides_Bowl"] = 'numeric';
        $rules["Bowler_NoBalls_Bowl"] = 'numeric';
        $rules["Bowler_Average_Bowl"] = 'numeric';
        $rules["Bowler_Ecomony"] = 'numeric';

        $rules["Fielder_Id"] = 'numeric';
        $rules["Fielder_Name"] = 'string';
        $rules["Fielder_Innings"] = 'string';
        $rules["Fielder_Catches"] = 'numeric';
        $rules["Fielder_StumpOuts"] = 'numeric';
        $rules["Fielder_RunOuts"] = 'numeric';
        return $rules;
    }

}
