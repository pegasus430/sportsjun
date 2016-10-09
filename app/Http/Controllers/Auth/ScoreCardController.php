<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Model\MatchSchedule;
use App\Model\Sport;
use App\User;
use App\Model\TennisPlayerMatchScore;
use App\Model\Photo;
use App\Model\TtPlayerMatchScore;
use App\Model\Team;
use App\Helpers\Helper;
class ScoreCardController extends Controller
{
    public function completedMatches()
	{
		echo "dgdf";
	}
	//function to view the score cards
	public function createScorecardView($match_id)
	{
		//get scheduled matches data data
		$match_data=array();
		$match_details = MatchSchedule::where('id',$match_id)->get();
		if(count($match_details)>0)
			$match_data = $match_details->toArray();
		if(!empty($match_data))
		{
			$sport_id = $match_data[0]['sports_id'];//get sport id
			$sportsDetails = Sport::where('id',$sport_id)->get()->toArray();//get sports details
			if(!empty($sportsDetails))
			{
				$sport_name = $sportsDetails[0]['sports_name'];
				if(strtolower($sport_name)==strtolower('Tennis'))//if match is related to tennis
				{
					return  $this->tennisOrTableTennisScoreCard($match_data,$match='Tennis',$is_from_view=1);
				}else if(strtolower($sport_name)==strtolower('Table Tennis'))//if match is related to table tennis
				{
					return $this->tennisOrTableTennisScoreCard($match_data,$match='Table Tennis',$is_from_view=1);
				}else if(strtolower($sport_name)==strtolower('Cricket'))
				{
					return $this->cricketScoreCard($match_data,$is_from_view=1);
				}
				else if(strtolower($sport_name)==strtolower('soccer'))
				{
					return $this->soccerScoreCard($match_data,$is_from_view=1);
				}
			}
		}
	}
	//function to display tennis score card form
	public function tennisOrTableTennisScoreCard($match_data,$match,$is_from_view=0)
	{
		$score_a_array=array();
		$score_b_array=array();
		$loginUserId = 0;
		$loginUserRole = '';
		//!empty($matchScheduleDetails['tournament_id'])
		//if($match_data[0]['match_status']=='scheduled')//match should be already scheduled
		//{
			$player_a_ids = $match_data[0]['player_a_ids'];
			$player_b_ids = $match_data[0]['player_b_ids'];
			
			$a_players = array();
			
			$team_a_playerids = explode(',',$player_a_ids);
			$a_team_players = User::select('id','name')->whereIn('id',$team_a_playerids)->get();
			
			if (count($a_team_players)>0)
				$a_players = $a_team_players->toArray();
			
			$b_players = array();
			
			$team_b_playerids = explode(',',$player_b_ids);
			$b_team_players = User::select('id','name')->whereIn('id',$team_b_playerids)->get();
			
			if (count($b_team_players)>0)
				$b_players = $b_team_players->toArray();
			
			$team_a_player_images = array();
			$team_b_player_images = array();
			
			//team a player images
			if(count($a_players)>0)
			{
				foreach($a_players as $a_player)
				{
					$team_a_player_images[$a_player['id']]=Photo::select()->where('imageable_id', $a_player['id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
				}
			}
			
			//team b player images
			if(count($b_players)>0)
			{
				foreach($b_players as $b_player)
				{
					$team_b_player_images[$b_player['id']]=Photo::select()->where('imageable_id', $b_player['id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
				}
			}
			if($match_data[0]['schedule_type'] == 'player')//&& $match_data[0]['schedule_type'] == 'player'
			{
				$user_a_name = User::where('id',$match_data[0]['a_id'])->pluck('name');
				$user_b_name = User::where('id',$match_data[0]['b_id'])->pluck('name');
				$user_a_logo = Photo::select()->where('imageable_id', $match_data[0]['a_id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
				$user_b_logo = Photo::select()->where('imageable_id', $match_data[0]['b_id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
				$upload_folder = 'user_profile';
				$is_singles = 'yes';
				if($match=='Tennis')//tennis
				{
					$scores_a = TennisPlayerMatchScore::select()->where('user_id_a',$match_data[0]['a_id'])->where('match_id',$match_data[0]['id'])->get();
					$scores_b = TennisPlayerMatchScore::select()->where('user_id_a',$match_data[0]['b_id'])->where('match_id',$match_data[0]['id'])->get();
				}
				else //table tennis
				{
					$scores_a = TtPlayerMatchScore::select()->where('user_id_a',$match_data[0]['a_id'])->where('match_id',$match_data[0]['id'])->get();
					$scores_b = TtPlayerMatchScore::select()->where('user_id_a',$match_data[0]['b_id'])->where('match_id',$match_data[0]['id'])->get();					
				}
				if(count($scores_a)>0)
					$score_a_array = $scores_a->toArray();
				
				if(count($scores_b)>0)
					$score_b_array = $scores_b->toArray();
			}else
			{
				$user_a_name = Team::where('id',$match_data[0]['a_id'])->pluck('name');//team details
				$user_b_name = Team::where('id',$match_data[0]['b_id'])->pluck('name');//team details
				$user_a_logo = Photo::select()->where('imageable_id', $match_data[0]['a_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
				$user_b_logo = Photo::select()->where('imageable_id', $match_data[0]['b_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
				$upload_folder = 'teams';
				$is_singles = 'no';
				if($match=='Tennis')//TENNIS
				{
					$scores_a = TennisPlayerMatchScore::select()->where('team_id',$match_data[0]['a_id'])->where('match_id',$match_data[0]['id'])->get();
					$scores_b = TennisPlayerMatchScore::select()->where('team_id',$match_data[0]['b_id'])->where('match_id',$match_data[0]['id'])->get();
				} 
				else //table tennis
				{
					$scores_a = TtPlayerMatchScore::select()->where('team_id',$match_data[0]['a_id'])->where('match_id',$match_data[0]['id'])->get();
					$scores_b = TtPlayerMatchScore::select()->where('team_id',$match_data[0]['b_id'])->where('match_id',$match_data[0]['id'])->get();
				}
				if(count($scores_a)>0)
					$score_a_array = $scores_a->toArray();
				
				if(count($scores_b)>0)
					$score_b_array = $scores_b->toArray();
			}
			$decoded_match_details = array();
			if($match_data[0]['match_details']!='')
			{
				$decoded_match_details = json_decode($match_data[0]['match_details'],true);
			}
			
			//score status
			$score_status_array = json_decode($match_data[0]['score_added_by'],true);
			
			 
			$rej_note_str='';
			if($score_status_array['rejected_note']!='')
			{
				$rejected_note_array = explode('@',$score_status_array['rejected_note']);
				$rejected_note_array = array_filter($rejected_note_array);
				foreach($rejected_note_array as $note)
				{
					$rej_note_str = $rej_note_str.$note.' ,';
				}
			}

			
			//is valid user for score card enter or edit
			$isValidUser = '';
			
			//is approval process exist
			$isApproveRejectExist = '';
			$isForApprovalExist = '';
			

			//ONLY FOR VIEW SCORE CARD
			if($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId) || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser) 
			{
				if($match=='Tennis')
				{
					return view('scorecards.tennisscorecardview',array('user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist));
				}else
				{
					
					return view('scorecards.tabletennisscorecardview',array('user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist));
				}
			}
			else //to view and edit tennis/table tennis score card
			{
				if($match=='Tennis')
				{
					return view('scorecards.tennisscorecard',array('user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist));
				}else
				{
					
					return view('scorecards.tabletennisscorecard',array('user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist));
				}
			}
			
		//}
	}
   
}
?>