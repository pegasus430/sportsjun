<?php
namespace App\Helpers;
use App\Model\Photo;
use App\Model\Team;
use App\Model\Sport;
use App\Model\UserStatistic;
use App\Model\Notifications;
use App\Model\MatchSchedule;
use App\Model\MatchScheduleRubber;
use App\Model\TournamentParent;
use App\Model\Tournaments;
use App\Model\TeamPlayers;
use App\Model\volleyballScore;
use App\Model\VolleyballPlayerMatchwiseStats;
use App\Model\BasketballPlayerMatchwiseStats;
use App\Model\KabaddiPlayerMatchwiseStats;
use App\Model\UltimateFrisbeePlayerMatchwiseStats;
use App\Model\SquashPlayerRubberScore;
use App\Model\WaterpoloPlayerMatchwiseStats;
use App\Model\TournamentMatchPreference;
use App\Helpers\AllRequests;
use App\User;
use App\Model\Organization;
use File;
use Auth;
use DB;
use View;
use Carbon\Carbon;
use Route;
use PDO;
use App\Helpers\SendMail;
use App\Model\TournamentGroupTeams;
use App\Model\OrganizationGroupTeamPoint;

use App\Model\BadmintonPlayerMatchwiseStats;
use App\Model\BadmintonPlayerMatchScore;
use App\Model\BadmintonPlayerRubberScore;
use App\Model\BadmintonStatistic;

use App\Model\TennisPlayerMatchwiseStats;
use App\Model\TennisPlayerMatchScore;
use App\Model\TennisPlayerRubberScore;
use App\Model\TennisStatistic;

use App\Model\TtPlayerMatchwiseStats;
use App\Model\TtPlayerMatchScore;
use App\Model\TtPlayerRubberScore;
use App\Model\TtStatistic;


class ScoreCard {

		//get rubber player statictics
 	public static function	getRubberPlayers($rubber_id, $sports_id=5){
 		$score_a_array=[];
 		$score_b_array=[];
 		$scores_a=[];
 		$scores_b=[];

 		switch ($sports_id) {
 			case 5:		

 		$scores_a = BadmintonPlayerRubberScore::select()->where('rubber_id',$rubber_id)->first();
        $scores_b = BadmintonPlayerRubberScore::select()->where('rubber_id',$rubber_id)->skip(1)->first();

        # code...
 				break;
 			case 2:
				$scores_a = TennisPlayerRubberScore::select()->where('rubber_id',$rubber_id)->first();
				$scores_b = TennisPlayerRubberScore::select()->where('rubber_id',$rubber_id)->skip(1)->first();		

 			break;

 			case 3:
 				$scores_a = TtPlayerRubberScore::select()->where('rubber_id',$rubber_id)->first();
				$scores_b = TtPlayerRubberScore::select()->where('rubber_id',$rubber_id)->skip(1)->first();

 			break;

 			case 13:
 				$scores_a = SquashPlayerRubberScore::select()->where('rubber_id',$rubber_id)->first();
				$scores_b = SquashPlayerRubberScore::select()->where('rubber_id',$rubber_id)->skip(1)->first();

 			break;


        default:
 				# code...
 				break;
 		}


        if(count($scores_a)>0)
            $score_a_array = $scores_a->toArray();

        if(count($scores_b)>0)
            $score_b_array = $scores_b->toArray();


        return [
        	'a'=>$score_a_array,
        	'b'=>$score_b_array
        ];
	}

//get the winner in rubbers;
	public static function getWinnerInRubber($match_id, $sports_id=5, $end_rubber=false){
			$match_model=MatchSchedule::find($match_id);
			$a_id=$match_model->a_id;
			$b_id=$match_model->b_id;			
			$number_of_rubbers = $match_model->number_of_rubber;

			$score_a=0;
			$score_b=0;

			switch ($sports_id) {
				case in_array($sports_id, [5,2,3,13]):
			$score_a=MatchScheduleRubber::where('winner_id', $a_id)->whereMatchId($match_id)->get()->count();
			$score_b=MatchScheduleRubber::where('winner_id', $b_id)->whereMatchId($match_id)->get()->count();
					break;
				
				default:
					# code...
					break;
			}
		$match_model->a_score=$score_a;
		$match_model->b_score=$score_b;
		$match_model->save();

		if($end_rubber){
			  $mid_rubber = floor($number_of_rubbers/2);

			  if($score_a>$mid_rubber){
			  	  return [
			  	  	 'has_winner'=>true,
			  	  	 'winner' => $a_id,
			  	  	 'looser' => $b_id,
			  	  	 'message'	 => Team::find($a_id)->name." <b>wins</b> with <b>$score_a</b> rubbers on <b>". $number_of_rubbers."</b>, will you like to <b>Continue</b> or <b>End match</b>?"
			  	  ];
			  }
			  else if($score_b>$mid_rubber){
			  	  return [
			  	  	 'has_winner'=> true,
			  	  	 'winner' => $b_id,
			  	  	 'looser' => $b_id,
			  	  	 'message'	 => Team::find($b_id)->name." <b>wins</b> with <b>$score_b</b> rubbers on <b>". $number_of_rubbers . "</b>, will you like to <b>continue</b> or <b>end match</b>?"
			  	  ];
			  }
			  else{
			  	  return [
			  	  	 'has_winner'=> false,
			  	  	 'winner_id' => null,
			  	  	 'looser_id' => null,
			  	  	 'message'	 => null
			  	  ];
			  }

		}

		if($score_a>$score_b) return ['winner'=>$a_id, 'looser'=> $b_id];
		else return ['winner'=>$b_id, 'looser'=> $a_id];

	}

	public static function getTotalPoints($match_id, $sports_id, $type, $team_id){

	$total_points= 0 ;
	//$model = [];
			switch ($sports_id) {
				case '6':
	$model =BasketballPlayerMatchwiseStats::where(['match_id'=>$match_id, 'team_id'=>$team_id])->get();
					break;
				case '15':
	$model =UltimateFrisbeePlayerMatchwiseStats::where(['match_id'=>$match_id, 'team_id'=>$team_id])->get();
					break;
				case '16':
	$model =WaterpoloPlayerMatchwiseStats::where(['match_id'=>$match_id, 'team_id'=>$team_id])->get();
					break;
				case '14':
	$model =KabaddiPlayerMatchwiseStats::where(['match_id'=>$match_id, 'team_id'=>$team_id])->get();

				
				
				default:
					# code...
					break;
			}

		foreach ($model as $key => $value) {
				$total_points += $value->{$type};
		}

		return $total_points;
	}



}

?>