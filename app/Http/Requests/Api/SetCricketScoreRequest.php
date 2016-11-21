<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;

class SetCricketScoreRequest extends Request
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
        $overs = $this->Over;

        $rules = [
            'Tournament_Id' => 'required|numeric',
            'Team_Id' => 'required|numeric',
            'Match_Id' => 'required|numeric',
            'Total_Overs' => 'required|numeric',
            'Over' => 'required|numeric',
            'Bowler_Id' => 'required|numeric',
        ];

        if ($overs && $overs > 0 && $overs < 50) {
            for ($i = 1; $i < $overs; $i++) {
                $rules[$i] = 'required|array';
                $rules[$i . ".BatsMan_Id"] = 'required|numeric';
                $rules[$i . ".Runs"] = 'required|numeric';
                $rules[$i . ".4's"] = 'required|numeric';
                $rules[$i . ".6's"] = 'required|numeric';
                $rules[$i . ".No_Ball"] = 'required|numeric';
                $rules[$i . ".Wide"] = 'required|numeric';
                $rules[$i . ".Bye"] = 'required|numeric';
                $rules[$i . ".Leg_Bye"] = 'required|numeric';
                $rules[$i . ".Synced"] = '|numeric';

                $rules[$i . ".Wicket.Opponent_Player_Id"] = 'numeric';
                $rules[$i . ".Wicket.Bowled"] = 'numeric';
                $rules[$i . ".Wicket.Caught"] = 'numeric';
                $rules[$i . ".Wicket.Handled_The_Ball"] = 'numeric';
                $rules[$i . ".Wicket.Hit_The_ball_Twice"] = 'numeric';
                $rules[$i . ".Wicket.Hit_Wicket"] = 'numeric';
                $rules[$i . ".Wicket.LBW"] = 'numeric';
                $rules[$i . ".Wicket.Stumped"] = 'numeric';
                $rules[$i . ".Wicket.RunOut"] = 'numeric';
                $rules[$i . ".Wicket.Retired"] = 'numeric';
                $rules[$i . ".Wicket.Obstructing_The_Filed"] = 'numeric';

                $rules[$i. ".Player_Batman.Player_Name"] = 'string';
                $rules[$i. ".Player_Batman.Innings"] = 'string';
                $rules[$i. ".Player_Batman.TotalRuns"] = 'numeric';
                $rules[$i. ".Player_Batman.Balls_played"] = 'numeric';
                $rules[$i. ".Player_Batman.Fifties"] = 'numeric';
                $rules[$i. ".Player_Batman.Hundreds"] = 'numeric';
                $rules[$i. ".Player_Batman.Total_Fours"] = 'numeric';
                $rules[$i. ".Player_Batman.Total_Sixes"] = 'numeric';
                $rules[$i. ".Player_Batman.Average_Bat"] = 'numeric';
                $rules[$i. ".Player_Batman.StrikeRate"] = 'numeric';



                $rules[$i. ".Player_Bowler.Bowler_Name"] = 'string';
                $rules[$i. ".Player_Bowler.Innings"] = 'string';
                $rules[$i. ".Player_Bowler.TotalRuns"] = 'numeric';
                $rules[$i. ".Player_Bowler.Fours"] = 'numeric';
                $rules[$i. ".Player_Bowler.Sixes"] = 'numeric';
                $rules[$i. ".Player_Bowler.Wickets"] = 'numeric';
                $rules[$i. ".Player_Bowler.Overs_Bowled"] = 'numeric';
                $rules[$i. ".Player_Bowler.Overs_Maiden"] = 'numeric';
                $rules[$i. ".Player_Bowler.Wides_Bowl"] = 'numeric';
                $rules[$i. ".Player_Bowler.NoBalls_Bowl"] = 'numeric';
                $rules[$i. ".Player_Bowler.Average_Bowl"] = 'numeric';
                $rules[$i. ".Player_Bowler.Ecomony"] = 'numeric';

                $rules[$i. ".Player_Fielder.Fielder_Od"] = 'numeric';
                $rules[$i. ".Player_Fielder.Fielder_Name"] = 'string';
                $rules[$i. ".Player_Fielder.Innings"] = 'string';
                $rules[$i. ".Player_Fielder.Catches"] = 'numeric';
                $rules[$i. ".Player_Fielder.StumpOuts"] = 'numeric';
                $rules[$i. ".Player_Fielder.RunOuts"] = 'numeric';
            }
        }
        
        return $rules;
    }


}
