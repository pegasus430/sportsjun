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
use DateTime;
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
use App\Model\TennisSet;
use App\Model\ArcheryArrowStats;
use App\Model\ArcheryTeamStats;
use App\Model\ArcheryPlayerStats;


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


	public static function send_email_for_match(){

	}

	public static function tennis_active_set($match_id, $rubber_id, $set_number){
			  $set_scoring = TennisSet::whereMatchId($match_id);
               if($rubber_id) $set_scoring->whereRubberId($rubber_id);
           $set_scoring  = $set_scoring->whereSet($set_number)->first();

           return $set_scoring;
	}

	public static function display_role($user_id, $team_id){
		$role=TeamPlayers::where(['user_id'=>$user_id,'team_id'=>$team_id])->first();
		$text = '';

		 if($role){
			switch ($role->role) {
				case in_array($role->role, ['owner', 'manager']):
				$text ="<span style='color:orange'>$role->role </span>";
					break;

					case in_array($role->role, ['captain', 'physio','coach']):
				$text ="<span style='color:blue'>$role->role </span>";
					break;

						case in_array($role->role, ['vice-captain', 'keeper']):
				$text ="<span style='color:red'>$role->role </span>";
					break;
				
				default:
					# code...
					break;
			}
		}

		return $text;
	}

	public static function get_referees($match_id){
		return MatchSchedule::find($match_id)->referees;
	}

	public static function get_arrow_stats($match_id,$user_id,$round_id,$round_number){

    $check = ArcheryArrowStats::where(['match_id'=>$match_id,'user_id'=>$user_id,'round_id'=>$round_id])->first();
    if($check) return $check;

        $ars = new ArcheryArrowStats;
        $ars->user_id = $user_id;
        $ars->match_id = $match_id;
        $ars->round_id = $round_id;
        $ars->round_number = $round_number;

        $ars->save();

        return $ars;
    }

    public static function get_archery_teams($team_id){
    	$players = [];

    	$match_model = MatchSchedule::find($team_id);

    	$pd_ids = explode(',',$match_model->player_or_team_ids);

    	foreach ($pd_ids as $key => $pd_id) {
    		if($pd_id){

    			if($match_model->schedule_type=='player'){
    				$players[$pd_id] = User::find($pd_id);
    			}
    			else{
    				$players[$pd_id] = Team::find($pd_id);
    			}
    		}
    	}


    	return $players;
    }

    public static function get_match_number_athletics($match_id){
    	$match_model = MatchSchedule::find($match_id);

    	$t_id = $match_model->tournament_id;
    	$t_model = Tournaments::find($t_id);

    	$days = '';
    	$match_number = '';

    	$result=[
    		'match_number'=>'',
    		'tournament_name'=>'',
    		'day'=>''
    	];

    	if($t_model){
    		$t_start = new DateTime($t_model->start_date);
    		$today   = new DateTime(date('Y-m-d'));

    		$diff = $t_start->diff($today);
    		$days = ($diff->m * 31) + ($diff->d);
    		$matches = DB::table('match_schedules')->whereTournamentId($t_id)->lists('id');


    		$number = array_search($match_id, $matches) + 1;

    		$result['match_number'] = "Match $number";
    		$result['tournament_name']= $t_model->name;
    		$result['day'] 			 = 'Day ' . $days; 		
    	}

    	return $result;
    }


    public static function get_archery_tournament_points($tournamentDetails=[], $team_id, $i){
    		$schedule_type = $tournamentDetails['schedule_type'];
    		$tournament_id = $tournamentDetails['id'];

    		$pts = 0;

    	if($schedule_type=='individual'){
    		foreach(ArcheryArrowStats::whereUserId($team_id)->whereTournamentId($tournament_id)->get() as $st){
    			for($j=1; $j<=10; $j++){
    				if($st->{'arrow_'.$j}==$i){
    					$pts++;
    				}
    			}
    			
    		}
    	}
    	else{
    		foreach(ArcheryArrowStats::whereTeamId($team_id)->whereTournamentId($tournament_id)->get() as $st){
    			for($j=1; $j<=10; $j++){
    				if($st->{'arrow_'.$j}==$i){
    					$pts++;
    				}
    			}
    		}
    	}

    	return $pts;
    }

    public static function get_archery_total_points($tournamentDetails, $team_id){
    	$pts = 0; 
    		$schedule_type = $tournamentDetails['schedule_type'];
    		$tournament_id = $tournamentDetails['id'];

    	if($schedule_type=='individual'){
    		$p_ts = ArcheryPlayerStats::whereUserId($team_id)->whereTournamentId($tournament_id)->first();
    		if($p_ts) $pts = $p_ts->total;
    			
    	}
    	else{
    		$p_ts = ArcheryTeamStats::whereTeamId($team_id)->whereTournamentId($tournament_id)->first();
    		if($p_ts) $pts = $p_ts->total;    			
    	}
    	return $pts;
    }

    public static function get_archery_team_player($team_id, $round){
    	$responce=[
    	'id'=>'',
    	'user_id'=>'',
    	'player_name'=>''];

    	$check = ArcheryPlayerStats::where(['team_table_id'=>$team_id,'round_id'=>$round->id])->first();

    	if($check){
    		$responce = $check->toArray();
    	}

    	return $responce;
    }



}

?>