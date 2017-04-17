<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
class SoccerPlayerMatchwiseStats extends Model {
	use SoftDeletes,
	Eloquence;	
    //
    protected $table = 'soccer_player_matchwise_stats';


    public static function insertSoccerScore($user_id,$tournament_id,$match_id,$team_id,$player_name,$team_name,$yellow_card_count,$red_card_count,$goal_count, $playing_status='S')
    {
        $soccer_model = new SoccerPlayerMatchwiseStats();
        $soccer_model->user_id 			= $user_id;
        $soccer_model->tournament_id 	= $tournament_id;
        $soccer_model->match_id 		= $match_id;
        $soccer_model->team_id 			= $team_id;
        $soccer_model->player_name 		= $player_name;
        $soccer_model->team_name 		= $team_name;
        $soccer_model->yellow_cards 	= $yellow_card_count;
        $soccer_model->red_cards 		= $red_card_count;
        $soccer_model->goals_scored 	= $goal_count;
        $soccer_model->playing_status 	= $playing_status;
        $soccer_model->save();
    }


}
