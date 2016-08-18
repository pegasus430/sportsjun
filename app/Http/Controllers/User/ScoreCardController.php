<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\MatchSchedule;
use App\Model\UserStatistic;
use App\Model\State;
use App\Model\City;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\Sport;
use App\Model\Facilityprofile;
use App\Model\Country;
use App\Model\Photo;
use App\Model\Tournaments;
use App\Model\TennisPlayerMatchScore;
use App\Model\TournamentGroupTeams;
use App\Model\TtPlayerMatchScore;
use App\Model\CricketStatistic;
use App\Model\TennisStatistic;
use App\Model\TtStatistic;
use App\Model\CricketPlayerMatchwiseStats;
//soccer
use App\Model\SoccerPlayerMatchwiseStats;
use App\Model\SoccerStatistic;
//badminton
use App\Model\BadmintonPlayerMatchScore;
use App\Model\BadmintonStatistic;
//hockey
use App\Model\HockeyPlayerMatchwiseStats;
use App\Model\HockeyStatistic;
//squash
use App\Model\SquashPlayerMatchScore;
use App\Model\SquashStatistic;
//Basketball
use App\Model\BasketballPlayerMatchwiseStats;
use App\Model\BasketballStatistic;
use App\Model\Organization;

use App\User;
use DB;
use Request;
use Carbon\Carbon;
use Response;
use Auth;
use App\Helpers\Helper;
use DateTime;
use App\Helpers\AllRequests;
use Session;
class ScoreCardController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store() {
		//
	}

	/**
	 * Display all the sports configured for the user.
	 * User can follow sports. If already followed then the user will be redirected to edit function
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {

	}

	/**
	 * Show the form for editing the sports profile.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id) {

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		//
	}
	//function to create score card
	public function createScorecard($match_id)
	{
		//get scheduled matches data data
		$match_data=array();
		$match_details = MatchSchedule::where('id',$match_id)->get();
		if(count($match_details)>0)
			$match_data = $match_details->toArray();
		$upload_folder = '';
		$is_singles = '';
		if(!empty($match_data))
		{
			$tournamentDetails = [];
			//if bye match, b_id is null

			//if b_id is null update a_id as winner
			if(!empty($match_data[0]['tournament_id']) && $match_data[0]['winner_id']=='' && $match_data[0]['b_id']=='') {
				$matchScheduleDetails = MatchSchedule::where('id',$match_id)->first();
				$tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
				if(Helper::isTournamentOwner($tournamentDetails['manager_id'],$tournamentDetails['tournament_parent_id'])) {
					MatchSchedule::where('id',$match_id)->update(['match_status'=>'completed',
						'winner_id'=>$match_data[0]['a_id'] ]);

					if(!empty($matchScheduleDetails['tournament_round_number'])) {
						$this->updateBracketDetails($matchScheduleDetails,$tournamentDetails,$match_data[0]['a_id']);
					}

				}

			}


			$match_details = MatchSchedule::where('id',$match_id)->get();
			if(count($match_details)>0)
				$match_data = $match_details->toArray();

			//new tournament details for the new sports. soccer, 
			if(!is_null($match_details[0]['tournament_id'])){
				$tournamentDetails=Tournaments::find($match_details[0]['tournament_id'])->toArray();
			}

			$sport_id = $match_data[0]['sports_id'];//get sport id
			$sportsDetails = Sport::where('id',$sport_id)->get()->toArray();//get sports details

			if(!empty($sportsDetails))
			{

				$sport_name = $sportsDetails[0]['sports_name'];
				if(strtolower($sport_name)==strtolower('Tennis'))//if match is related to tennis
				{
					return  $this->tennisOrTableTennisScoreCard($match_data,$match='Tennis',$sportsDetails,$tournamentDetails);
				}else if(strtolower($sport_name)==strtolower('Table Tennis'))//if match is related to table tennis
				{
					return $this->tennisOrTableTennisScoreCard($match_data,$match='Table Tennis',$sportsDetails,$tournamentDetails);
				}else if(strtolower($sport_name)==strtolower('Cricket'))
				{
					return $this->cricketScoreCard($match_data,$sportsDetails,$tournamentDetails);
				}
				else if(strtolower($sport_name)==strtolower('soccer'))
				{
					return $this->soccerScoreCard($match_data,$sportsDetails,$tournamentDetails);
				}
				else if(strtolower($sport_name)==strtolower('badminton'))
				{
					$badminton= new ScoreCard\BadmintonScoreCardController;
					return $badminton->badmintonScoreCard($match_data,$match='Badminton',$sportsDetails,$tournamentDetails);
				}
				else if(strtolower($sport_name)==strtolower('squash')){
					$squash= new ScoreCard\SquashScoreCardController;
					return $squash->squashScoreCard($match_data,$match='Squash',$sportsDetails,$tournamentDetails);
				}
				else if(strtolower($sport_name)==strtolower('hockey'))
				{
						$hockey= new ScoreCard\HockeyScorecardController;
					return $hockey->hockeyScoreCard($match_data,$sportsDetails,$tournamentDetails);
				}
				// else if(strtolower($sport_name)==strtolower('volleyball'))
				// {
				// 	$volleyball = new ScoreCard\VolleyballScoreCardController;
				// 	return $volleyball->volleyballScoreCard($match_data,$sportsDetails,$tournamentDetails);
				// }
				else if(strtolower($sport_name)==strtolower('basketball'))
				{
					$basketball = new ScoreCard\BasketballScoreCardController;
					return $basketball->basketballScoreCard($match_data,$sportsDetails,$tournamentDetails);
				}
			}
		}
	}

	//function to display tennis score card form
	public function tennisOrTableTennisScoreCard($match_data,$match,$sportsDetails=[],$tournamentDetails=[],$is_from_view=0)
	{

		$score_a_array=array();
		$score_b_array=array();

		$loginUserId = '';
		$loginUserRole = '';

		if(isset(Auth::user()->id))
			$loginUserId = Auth::user()->id;

		if(isset(Auth::user()->role))
			$loginUserRole = Auth::user()->role;

		//!empty($matchScheduleDetails['tournament_id'])
		//if($match_data[0]['match_status']=='scheduled')//match should be already scheduled
		//{
		$player_a_ids = $match_data[0]['player_a_ids'];
		$player_b_ids = $match_data[0]['player_b_ids'];

		$decoded_match_details = array();
		if($match_data[0]['match_details']!='')
		{
			$decoded_match_details = json_decode($match_data[0]['match_details'],true);
		}

		$a_players = array();

		$team_a_playerids = (!empty($decoded_match_details[$match_data[0]['a_id']]) && !($match_data[0]['scoring_status']=='' || $match_data[0]['scoring_status']=='rejected'))?$decoded_match_details[$match_data[0]['a_id']]:explode(',',$player_a_ids);

		$a_team_players = User::select('id','name')->whereIn('id',$team_a_playerids)->get();

		if (count($a_team_players)>0)
			$a_players = $a_team_players->toArray();

		$b_players = array();

		$team_b_playerids = (!empty($decoded_match_details[$match_data[0]['b_id']]) && !($match_data[0]['scoring_status']=='' || $match_data[0]['scoring_status']=='rejected'))?$decoded_match_details[$match_data[0]['b_id']]:explode(',',$player_b_ids);


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

			$team_a_city = Helper::getUserCity($match_data[0]['a_id']);
			$team_b_city = Helper::getUserCity($match_data[0]['b_id']);
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

			$team_a_city = Helper::getTeamCity($match_data[0]['a_id']);
			$team_b_city = Helper::getTeamCity($match_data[0]['b_id']);
		}

		//bye match
		if($match_data[0]['b_id']=='' && $match_data[0]['match_status']=='completed')
		{
			$sport_class = 'tennis_scorcard';
			return view('scorecards.byematchview',array('team_a_name'=>$user_a_name,'team_a_logo'=>$user_a_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'sport_class'=>$sport_class));
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
		$rej_note_str = trim($rej_note_str, ",");


		//is valid user for score card enter or edit
		$isValidUser = 0;
		$isApproveRejectExist = 0;
		$isForApprovalExist = 0;
		if(isset(Auth::user()->id)){
			$isValidUser = Helper::isValidUserForScoreEnter($match_data);
			//is approval process exist
			$isApproveRejectExist = Helper::isApprovalExist($match_data);
			$isForApprovalExist = Helper::isApprovalExist($match_data,$isForApproval='yes');
		}

		//ONLY FOR VIEW SCORE CARD
		if($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser)
		{
			if($match=='Tennis')
			{
				return view('scorecards.tennisscorecardview',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city));
			}else
			{

				return view('scorecards.tabletennisscorecardview',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city));
			}
		}
		else //to view and edit tennis/table tennis score card
		{
			if($match=='Tennis')
			{
				//for form submit pass id from controller
				$form_id = 'tennis';
				return view('scorecards.tennisscorecard',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'form_id'=>$form_id));
			}else
			{
				//for form submit pass id from controller
				$form_id = 'tabletennis';
				return view('scorecards.tabletennisscorecard',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'form_id'=>$form_id));
			}
		}

		//}
	}
	//function to insert tennis score card
	public function insertTennisScoreCard()
	{
		$loginUserId = Auth::user()->id;
		$match_id = !empty(Request::get('match_id'))?Request::get('match_id'):NULL;
		$isValid = $this->checkScoreEnterd($match_id);
		$message = '';
		if($isValid==0)
		{
			$user_name = $this->scoreAddedByUserName($match_id);
			$message = 'Scorecard already entered by '.$user_name;
		}

		$request = Request::all();
		$tournament_id = !empty(Request::get('tournament_id'))?Request::get('tournament_id'):NULL;
		$match_id = !empty(Request::get('match_id'))?Request::get('match_id'):NULL;
		$match_type = !empty(Request::get('match_type'))?Request::get('match_type'):NULL;
		$player_ids_a = !empty(Request::get('player_ids_a'))?Request::get('player_ids_a'):NULL;
		$player_ids_b= !empty(Request::get('player_ids_b'))?Request::get('player_ids_b'):NULL;
		$player_ids_a_array = explode(',',$player_ids_a);
		$is_singles = !empty(Request::get('is_singles'))?Request::get('is_singles'):NULL;
		$is_winner_inserted = !empty(Request::get('is_winner_inserted'))?Request::get('is_winner_inserted'):NULL;
		$winner_team_id = !empty(Request::get('winner_team_id'))?Request::get('winner_team_id'):$is_winner_inserted;//winner_id

		$team_a_players = !empty(Request::get('a_player_ids'))?Request::get('a_player_ids'):array();//player id if match type is singles
		$team_b_players = !empty(Request::get('b_player_ids'))?Request::get('b_player_ids'):array();//player id if match type is singles

		//shedule type
		$schedule_type = !empty(Request::get('schedule_type'))?Request::get('schedule_type'):NULL;

		//match a Details
		$user_id_a = !empty(Request::get('user_id_a'))?Request::get('user_id_a'):NULL;
		$player_name_a = !empty(Request::get('player_name_a'))?Request::get('player_name_a'):NULL;
		$set1_a = !empty(Request::get('set_1_a'))?Request::get('set_1_a'):NULL;
		$set2_a = !empty(Request::get('set_2_a'))?Request::get('set_2_a'):NULL;
		$set3_a = !empty(Request::get('set_3_a'))?Request::get('set_3_a'):NULL;
		$set4_a = !empty(Request::get('set_4_a'))?Request::get('set_4_a'):NULL;
		$set5_a = !empty(Request::get('set_5_a'))?Request::get('set_5_a'):NULL;
		$set1_tie_breaker_a = !empty(Request::get('set_1_tiebreaker_a'))?Request::get('set_1_tiebreaker_a'):NULL;
		$set2_tie_breaker_a = !empty(Request::get('set_2_tiebreaker_a'))?Request::get('set_2_tiebreaker_a'):NULL;
		$set3_tie_breaker_a = !empty(Request::get('set_3_tiebreaker_a'))?Request::get('set_3_tiebreaker_a'):NULL;
		$set4_tie_breaker_a = !empty(Request::get('set_4_tiebreaker_a'))?Request::get('set_4_tiebreaker_a'):NULL;
		$set5_tie_breaker_a = !empty(Request::get('set_5_tiebreaker_a'))?Request::get('set_5_tiebreaker_a'):NULL;
		$aces_a = !empty(Request::get('aces_a'))?Request::get('aces_a'):NULL;
		$double_faults_a = !empty(Request::get('double_faults_a'))?Request::get('double_faults_a'):NULL;

		//match a Details
		$user_id_b = !empty(Request::get('user_id_b'))?Request::get('user_id_b'):NULL;
		$player_name_b = !empty(Request::get('player_name_b'))?Request::get('player_name_b'):NULL;
		$set1_b = !empty(Request::get('set_1_b'))?Request::get('set_1_b'):NULL;
		$set2_b = !empty(Request::get('set_2_b'))?Request::get('set_2_b'):NULL;
		$set3_b = !empty(Request::get('set_3_b'))?Request::get('set_3_b'):NULL;
		$set4_b = !empty(Request::get('set_4_b'))?Request::get('set_4_b'):NULL;
		$set5_b = !empty(Request::get('set_5_b'))?Request::get('set_5_b'):NULL;
		$set1_tie_breaker_b = !empty(Request::get('set_1_tiebreaker_b'))?Request::get('set_1_tiebreaker_b'):NULL;
		$set2_tie_breaker_b = !empty(Request::get('set_2_tiebreaker_b'))?Request::get('set_2_tiebreaker_b'):NULL;
		$set3_tie_breaker_b = !empty(Request::get('set_3_tiebreaker_b'))?Request::get('set_3_tiebreaker_b'):NULL;
		$set4_tie_breaker_b = !empty(Request::get('set_4_tiebreaker_b'))?Request::get('set_4_tiebreaker_b'):NULL;
		$set5_tie_breaker_b = !empty(Request::get('set_5_tiebreaker_b'))?Request::get('set_5_tiebreaker_b'):NULL;
		$aces_b = !empty(Request::get('aces_b'))?Request::get('aces_b'):NULL;
		$double_faults_b = !empty(Request::get('double_faults_b'))?Request::get('double_faults_b'):NULL;


		if($is_singles=='yes')
		{
			$team_a_records = TennisPlayerMatchScore::select()->where('match_id',$match_id)->where('user_id_a',$user_id_a)->get();
			$team_b_records = TennisPlayerMatchScore::select()->where('match_id',$match_id)->where('user_id_a',$user_id_b)->get();
			$users_a = $user_id_a; //if singles
			$users_b = $user_id_b;
		}else
		{
			$team_a_records = TennisPlayerMatchScore::select()->where('match_id',$match_id)->where('team_id',$user_id_a)->get();
			$team_b_records = TennisPlayerMatchScore::select()->where('match_id',$match_id)->where('team_id',$user_id_b)->get();
			$users_a = $player_ids_a;
			$users_b = $player_ids_b;
		}


		//insert match a details
		if(count($team_a_records)>0)//if team a record is already exist
		{
			$this->updateTennisScore($user_id_a,$match_id,$set1_a,$set2_a,$set3_a,$set4_a,$set5_a,$set1_tie_breaker_a,$set2_tie_breaker_a,$set3_tie_breaker_a,$set4_tie_breaker_a,$set5_tie_breaker_a,$is_singles,$aces_a,$double_faults_a,$team_a_players,$schedule_type,$match_type);

		}else
		{
			$this->insertTennisScore($user_id_a,$tournament_id,$match_id,$player_name_a,$set1_a,$set2_a,$set3_a,$set4_a,$set5_a,$set1_tie_breaker_a,$set2_tie_breaker_a,$set3_tie_breaker_a,$set4_tie_breaker_a,$set5_tie_breaker_a,$is_singles,$aces_a,$double_faults_a,$team_a_players,$schedule_type,$match_type);

		}


		//insert match b details
		if(count($team_b_records)>0)//if team b record is already exist
		{
			$this->updateTennisScore($user_id_b,$match_id,$set1_b,$set2_b,$set3_b,$set4_b,$set5_b,$set1_tie_breaker_b,$set2_tie_breaker_b,$set3_tie_breaker_b,$set4_tie_breaker_b,$set5_tie_breaker_b,$is_singles,$aces_b,$double_faults_b,$team_b_players,$schedule_type,$match_type);

		}else
		{
			$this->insertTennisScore($user_id_b,$tournament_id,$match_id,$player_name_b,$set1_b,$set2_b,$set3_b,$set4_b,$set5_b,$set1_tie_breaker_b,$set2_tie_breaker_b,$set3_tie_breaker_b,$set4_tie_breaker_b,$set5_tie_breaker_b,$is_singles,$aces_b,$double_faults_b,$team_b_players,$schedule_type,$match_type);

		}


		//match details clmn
		$team_a_details[$user_id_a] = $team_a_players;
		$team_b_details[$user_id_b] = $team_b_players;

		if($schedule_type=='player' && $match_type=='singles')
		{
			$team_a_details[$user_id_a] = array($user_id_a);
			$team_b_details[$user_id_b] = array($user_id_b);
		}

		$match_details = $team_a_details+$team_b_details;
		$json_match_details_array = json_encode($match_details);

		//get previous scorecard status data
		$scorecardDetails = MatchSchedule::where('id',$match_id)->pluck('score_added_by');
		$decode_scorecard_data = json_decode($scorecardDetails,true);

		$modified_users = !empty($decode_scorecard_data['modified_users'])?$decode_scorecard_data['modified_users']:'';

		$modified_users = $modified_users.','.$loginUserId;//scorecard changed users

		$added_by = !empty($decode_scorecard_data['added_by'])?$decode_scorecard_data['added_by']:$loginUserId;

		//score card approval process
		$score_status = array('added_by'=>$added_by,'active_user'=>$loginUserId,'modified_users'=>$modified_users,'rejected_note'=>'');

		$json_score_status = json_encode($score_status);

		//update winner id
		$matchScheduleDetails = MatchSchedule::where('id',$match_id)->first();
		if(count($matchScheduleDetails)) {
			$looser_team_id = NULL;
			$match_status = 'scheduled';
			$approved = '';
			if(isset($winner_team_id)) {
				if($winner_team_id==$matchScheduleDetails['a_id']) {
					$looser_team_id=$matchScheduleDetails['b_id'];
				}else{
					$looser_team_id=$matchScheduleDetails['a_id'];
				}
				$match_status = 'completed';
				$approved = 'approved';
			}

			if(!empty($matchScheduleDetails['tournament_id'])) {
				$tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
				if(Helper::isTournamentOwner($tournamentDetails['manager_id'],$tournamentDetails['tournament_parent_id'])) {
					MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_details_array,'match_status'=>$match_status,
						'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
						'score_added_by'=>$json_score_status]);
//                                Helper::printQueries();

					if(!empty($matchScheduleDetails['tournament_round_number'])) {
						$this->updateBracketDetails($matchScheduleDetails,$tournamentDetails,$winner_team_id);
					}
					if($match_status=='completed')
					{
						$sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');
						$this->insertPlayerStatistics($sportName,$match_id);

						Helper::sendEmailPlayers($matchScheduleDetails, 'Tennis');	

						//notification code
					}

				}

			}else if(Auth::user()->role=='admin')
			{

				MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_details_array,'match_status'=>$match_status,
					'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
					'score_added_by'=>$json_score_status,'scoring_status'=>$approved]);
				if($match_status=='completed')
				{
					$sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');
					$this->insertPlayerStatistics($sportName,$match_id);

					Helper::sendEmailPlayers($matchScheduleDetails, 'Tennis');	

					//notification code
				}

			}
			else
			{
				MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_details_array,'winner_id'=>$winner_team_id ,
					'looser_id'=>$looser_team_id,'score_added_by'=>$json_score_status]);
			}
		}
		//MatchSchedule::where('id',$match_id)->update(['winner_id'=>$winner_team_id,'match_details'=>$json_match_details_array,'score_added_by'=>$json_score_status]);
		//if($winner_team_id>0)
		//return redirect()->route('match/scorecard/view', [$match_id])->with('status', trans('message.scorecard.scorecardmsg'));

		return redirect()->back()->with('status', trans('message.scorecard.scorecardmsg'));
	}

	//function to insert tennis score card
	public function insertTennisScore($user_id,$tournament_id,$match_id,$player_name,$set1,$set2,$set3,$set4,$set5,$set1_tie_breaker,$set2_tie_breaker,$set3_tie_breaker,$set4_tie_breaker,$set5_tie_breaker,$is_singles,$aces,$double_faults,$team_players,$schedule_type,$match_type)
	{
		$model = new TennisPlayerMatchScore();
		if($is_singles=='yes')
		{
			$model->user_id_a = $user_id;

		}else
		{
			$model->team_id = $user_id;
			if($schedule_type=='team' && $match_type=='singles')
			{
				$model->user_id_a = (!empty($team_players[0]))?$team_players[0]:'';
			}

		}
		$model->tournamet_id = $tournament_id;
		$model->match_id = $match_id;
		$model->player_name_a = $player_name;
		$model->set1 = $set1;
		$model->set2 = $set2;
		$model->set3 = $set3;
		$model->set4 = $set4;
		$model->set5 = $set5;
		$model->set1_tie_breaker = $set1_tie_breaker;
		$model->set2_tie_breaker = $set2_tie_breaker;
		$model->set3_tie_breaker = $set3_tie_breaker;
		$model->set4_tie_breaker = $set4_tie_breaker;
		$model->set5_tie_breaker = $set5_tie_breaker;
		$model->aces = $aces;
		$model->double_faults = $double_faults;
		$model->save();
	}
	//function to update tennis score
	public function updateTennisScore($user_id,$match_id,$set1,$set2,$set3,$set4,$set5,$set1_tie_breaker,$set2_tie_breaker,$set3_tie_breaker,$set4_tie_breaker,$set5_tie_breaker,$is_singles,$aces,$double_faults,$team_players,$schedule_type,$match_type)
	{
		if($is_singles=='yes')
		{
			TennisPlayerMatchScore::where('match_id',$match_id)->where('user_id_a',$user_id)->update(['set1'=>$set1,'set2'=>$set2,'set3'=>$set3,'set4'=>$set4,'set5'=>$set5,'set1_tie_breaker'=>$set1_tie_breaker,'set2_tie_breaker'=>$set2_tie_breaker,'set3_tie_breaker'=>$set3_tie_breaker,'set4_tie_breaker'=>$set4_tie_breaker,'set5_tie_breaker'=>$set5_tie_breaker,'aces'=>$aces,'double_faults'=>$double_faults]);
		}else
		{
			$user_id_a='';
			if($schedule_type=='team' && $match_type=='singles')
			{
				$user_id_a = (!empty($team_players[0]))?$team_players[0]:'';
			}
			TennisPlayerMatchScore::where('match_id',$match_id)->where('team_id',$user_id)->update(['set1'=>$set1,'set2'=>$set2,'set3'=>$set3,'set4'=>$set4,'set5'=>$set5,'set1_tie_breaker'=>$set1_tie_breaker,'set2_tie_breaker'=>$set2_tie_breaker,'set3_tie_breaker'=>$set3_tie_breaker,'set4_tie_breaker'=>$set4_tie_breaker,'set5_tie_breaker'=>$set5_tie_breaker,'aces'=>$aces,'double_faults'=>$double_faults,'user_id_a'=>$user_id_a]);
		}

	}
	//function to insert tennis statitistics
	public function tennisStatistics($player_ids_a_array,$match_type,$is_win='')
	{
		//$player_ids_a_array = explode(',',$player_ids);
		foreach($player_ids_a_array as $user_id)
		{
			//check already user id exists or not
			$tennis_statistics_array = array();
			$tennisStatistics = TennisStatistic::select()->where('user_id',$user_id)->where('match_type',$match_type)->get();

			$aces_count = '';
			$double_faults_count = '';

			$player_match_details = TennisPlayerMatchScore::selectRaw('sum(aces) as aces_count')->selectRaw('sum(double_faults) as double_faults_count')->where('user_id_a',$user_id)->groupBy('user_id_a')->get();

			if($match_type=='singles')
			{
				$aces_count = (!empty($player_match_details[0]['aces_count']))?$player_match_details[0]['aces_count']:'';
				$double_faults_count = (!empty($player_match_details[0]['double_faults_count']))?$player_match_details[0]['double_faults_count']:'';
			}

			if(count($tennisStatistics)>0)
			{
				$tennis_statistics_array = $tennisStatistics->toArray();
				$matches = !empty($tennis_statistics_array[0]['matches'])?$tennis_statistics_array[0]['matches']:0;
				$won = !empty($tennis_statistics_array[0]['won'])?$tennis_statistics_array[0]['won']:0;
				$lost = !empty($tennis_statistics_array[0]['lost'])?$tennis_statistics_array[0]['lost']:0;


				TennisStatistic::where('user_id',$user_id)->where('match_type',$match_type)->update(['matches'=>$matches+1,'aces'=>$aces_count,'double_faults'=>$double_faults_count]);

				if($is_win=='yes') //win count
				{
					$won_percentage = number_format((($won+1)/($matches+1))*100,2);
					TennisStatistic::where('user_id',$user_id)->where('match_type',$match_type)->update(['won'=>$won+1,'won_percentage'=>$won_percentage]);

				}else if($is_win=='no')//loss count
				{
					TennisStatistic::where('user_id',$user_id)->where('match_type',$match_type)->update(['lost'=>$lost+1]);
				}

			}else
			{
				$won='';
				$won_percentage='';
				$lost='';
				if($is_win=='yes') //win count
				{
					$won = 1;
					$won_percentage = number_format(100,2);
				}else if($is_win=='no') //lost count
				{
					$lost=1;
				}
				$tennisStatisticsModel = new TennisStatistic();
				$tennisStatisticsModel->user_id = $user_id;
				$tennisStatisticsModel->match_type = $match_type;
				$tennisStatisticsModel->matches = 1;
				$tennisStatisticsModel->won_percentage = $won_percentage;
				$tennisStatisticsModel->won = $won;
				$tennisStatisticsModel->lost = $lost;
				$tennisStatisticsModel->aces = $aces_count;
				$tennisStatisticsModel->double_faults = $double_faults_count;
				$tennisStatisticsModel->save();
			}
		}

	}

	//function to save table tennis score card
	public function insertTableTennisScoreCard()
	{
		$loginUserId = Auth::user()->id;
		$request = Request::all();
		$tournament_id = !empty(Request::get('tournament_id'))?Request::get('tournament_id'):NULL;
		$match_id = !empty(Request::get('match_id'))?Request::get('match_id'):NULL;
		$match_type = !empty(Request::get('match_type'))?Request::get('match_type'):NULL;
		$player_ids_a = !empty(Request::get('player_ids_a'))?Request::get('player_ids_a'):NULL;
		$player_ids_b= !empty(Request::get('player_ids_b'))?Request::get('player_ids_b'):NULL;
		$is_singles = !empty(Request::get('is_singles'))?Request::get('is_singles'):NULL;
		$is_winner_inserted = !empty(Request::get('is_winner_inserted'))?Request::get('is_winner_inserted'):NULL;
		$winner_team_id = !empty(Request::get('winner_team_id'))?Request::get('winner_team_id'):$is_winner_inserted;//winner_id

		$team_a_players = !empty(Request::get('a_player_ids'))?Request::get('a_player_ids'):array();//player id if match type is singles
		$team_b_players = !empty(Request::get('b_player_ids'))?Request::get('b_player_ids'):array();//player id if match type is singles

		$schedule_type = !empty(Request::get('schedule_type'))?Request::get('schedule_type'):NULL;

		//match a Details
		$user_id_a = !empty(Request::get('user_id_a'))?Request::get('user_id_a'):NULL;
		$player_name_a = !empty(Request::get('player_name_a'))?Request::get('player_name_a'):NULL;
		$set1_a = !empty(Request::get('set_1_a'))?Request::get('set_1_a'):NULL;
		$set2_a = !empty(Request::get('set_2_a'))?Request::get('set_2_a'):NULL;
		$set3_a = !empty(Request::get('set_3_a'))?Request::get('set_3_a'):NULL;
		$set4_a = !empty(Request::get('set_4_a'))?Request::get('set_4_a'):NULL;
		$set5_a = !empty(Request::get('set_5_a'))?Request::get('set_5_a'):NULL;
		$double_faults_a = !empty(Request::get('double_faults_a'))?Request::get('double_faults_a'):NULL;


		//match a Details
		$user_id_b = !empty(Request::get('user_id_b'))?Request::get('user_id_b'):NULL;
		$player_name_b = !empty(Request::get('player_name_b'))?Request::get('player_name_b'):NULL;
		$set1_b = !empty(Request::get('set_1_b'))?Request::get('set_1_b'):NULL;
		$set2_b = !empty(Request::get('set_2_b'))?Request::get('set_2_b'):NULL;
		$set3_b = !empty(Request::get('set_3_b'))?Request::get('set_3_b'):NULL;
		$set4_b = !empty(Request::get('set_4_b'))?Request::get('set_4_b'):NULL;
		$set5_b = !empty(Request::get('set_5_b'))?Request::get('set_5_b'):NULL;
		$double_faults_b = !empty(Request::get('double_faults_b'))?Request::get('double_faults_b'):NULL;

		if($is_singles=='yes')
		{
			$team_a_records = TtPlayerMatchScore::select()->where('match_id',$match_id)->where('user_id_a',$user_id_a)->get();
			$team_b_records = TtPlayerMatchScore::select()->where('match_id',$match_id)->where('user_id_a',$user_id_b)->get();
			$users_a = $user_id_a; //if singles
			$users_b = $user_id_b;
		}else
		{
			$team_a_records = TtPlayerMatchScore::select()->where('match_id',$match_id)->where('team_id',$user_id_a)->get();
			$team_b_records = TtPlayerMatchScore::select()->where('match_id',$match_id)->where('team_id',$user_id_b)->get();
			$users_a = $player_ids_a;
			$users_b = $player_ids_b;
		}


		//insert match a details
		if(count($team_a_records)>0)//if team a record is already exist
		{
			$this->updateTableTennisScore($user_id_a,$match_id,$set1_a,$set2_a,$set3_a,$set4_a,$set5_a,$is_singles,$team_a_players,$schedule_type,$match_type,$double_faults_a);

		}else
		{
			$this->insertTableTennisScore($user_id_a,$tournament_id,$match_id,$player_name_a,$set1_a,$set2_a,$set3_a,$set4_a,$set5_a,$is_singles,$team_a_players,$schedule_type,$match_type,$double_faults_a);

		}


		//insert match b details
		if(count($team_b_records)>0)//if team b record is already exist
		{
			$this->updateTableTennisScore($user_id_b,$match_id,$set1_b,$set2_b,$set3_b,$set4_b,$set5_b,$is_singles,$team_b_players,$schedule_type,$match_type,$double_faults_b);

		}else
		{
			$this->insertTableTennisScore($user_id_b,$tournament_id,$match_id,$player_name_b,$set1_b,$set2_b,$set3_b,$set4_b,$set5_b,$is_singles,$team_b_players,$schedule_type,$match_type,$double_faults_b);

		}

		//match details clmn
		$team_a_details[$user_id_a] = $team_a_players;
		$team_b_details[$user_id_b] = $team_b_players;

		if($schedule_type=='player' && $match_type=='singles')
		{
			$team_a_details[$user_id_a] = array($user_id_a);
			$team_b_details[$user_id_b] = array($user_id_b);
		}

		$match_details = $team_a_details+$team_b_details;
		$json_match_details_array = json_encode($match_details);

		//get previous scorecard status data
		$scorecardDetails = MatchSchedule::where('id',$match_id)->pluck('score_added_by');
		$decode_scorecard_data = json_decode($scorecardDetails,true);

		$modified_users = !empty($decode_scorecard_data['modified_users'])?$decode_scorecard_data['modified_users']:'';

		$modified_users = $modified_users.','.$loginUserId;//scorecard changed users

		$added_by = !empty($decode_scorecard_data['added_by'])?$decode_scorecard_data['added_by']:$loginUserId;

		//score card approval process
		$score_status = array('added_by'=>$added_by,'active_user'=>$loginUserId,'modified_users'=>$modified_users,'rejected_note'=>'');

		$json_score_status = json_encode($score_status);

		//update winner id
		$matchScheduleDetails = MatchSchedule::where('id',$match_id)->first();
		if(count($matchScheduleDetails)) {
			$looser_team_id = NULL;
			$match_status = 'scheduled';
			$approved='';
			if(isset($winner_team_id)) {
				if($winner_team_id==$matchScheduleDetails['a_id']) {
					$looser_team_id=$matchScheduleDetails['b_id'];
				}else{
					$looser_team_id=$matchScheduleDetails['a_id'];
				}
				$match_status = 'completed';
				$approved = 'approved';
			}

			if(!empty($matchScheduleDetails['tournament_id'])) {
				$tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
				if(Helper::isTournamentOwner($tournamentDetails['manager_id'],$tournamentDetails['tournament_parent_id'])) {
					MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_details_array,'match_status'=>$match_status,
						'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
						'score_added_by'=>$json_score_status]);
//                                Helper::printQueries();

					if(!empty($matchScheduleDetails['tournament_round_number'])) {
						$this->updateBracketDetails($matchScheduleDetails,$tournamentDetails,$winner_team_id);
					}
					if($match_status=='completed')
					{
						$sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');
						$this->insertPlayerStatistics($sportName,$match_id);

						Helper::sendEmailPlayers($matchScheduleDetails, 'Table Tennis');	

						//notification code
					}

				}

			}else if(Auth::user()->role=='admin')
			{

				MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_details_array,'match_status'=>$match_status,
					'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
					'score_added_by'=>$json_score_status,'scoring_status'=>$approved]);
				if($match_status=='completed')
				{
					$sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');
					$this->insertPlayerStatistics($sportName,$match_id);

					//notification code
					Helper::sendEmailPlayers($matchScheduleDetails, 'Table Tennis');	
				}
			}
			else
			{
				MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_details_array,'winner_id'=>$winner_team_id ,
					'looser_id'=>$looser_team_id,'score_added_by'=>$json_score_status]);
			}
		}
		//MatchSchedule::where('id',$match_id)->update(['winner_id'=>$winner_team_id,'match_details'=>$json_match_details_array,'score_added_by'=>$json_score_status ]);
		//if($winner_team_id>0)
		//return redirect()->route('match/scorecard/view', [$match_id])->with('status', trans('message.scorecard.scorecardmsg'));

		return redirect()->back()->with('status', trans('message.scorecard.scorecardmsg'));
	}
	//function to save table tennis score
	public function insertTableTennisScore($user_id,$tournament_id,$match_id,$player_name,$set1,$set2,$set3,$set4,$set5,$is_singles,$team_players,$schedule_type,$match_type,$double_faults)
	{
		//insert match a details
		$model = new TtPlayerMatchScore();
		if($is_singles=='yes')
		{
			$model->user_id_a = $user_id;
		}else
		{
			if($schedule_type=='team' && $match_type=='singles')
			{
				$model->user_id_a = (!empty($team_players[0]))?$team_players[0]:'';
			}
			$model->team_id = $user_id;
		}

		$model->tournament_id = $tournament_id;
		$model->match_id = $match_id;
		$model->player_name_a = $player_name;
		$model->set1 = $set1;
		$model->set2 = $set2;
		$model->set3 = $set3;
		$model->set4 = $set4;
		$model->set5 = $set5;
		$model->double_faults = $double_faults;
		$model->save();
	}
	//function to update table tennis
	public function updateTableTennisScore($user_id,$match_id,$set1,$set2,$set3,$set4,$set5,$is_singles,$team_players,$schedule_type,$match_type,$double_faults)
	{
		if($is_singles=='yes')
		{
			TtPlayerMatchScore::where('match_id',$match_id)->where('user_id_a',$user_id)->update(['set1'=>$set1,'set2'=>$set2,'set3'=>$set3,'set4'=>$set4,'set5'=>$set5,'double_faults'=>$double_faults]);
		}else
		{
			$user_id_a='';
			if($schedule_type=='team' && $match_type=='singles')
			{
				$user_id_a = (!empty($team_players[0]))?$team_players[0]:'';
			}
			TtPlayerMatchScore::where('match_id',$match_id)->where('team_id',$user_id)->update(['set1'=>$set1,'set2'=>$set2,'set3'=>$set3,'set4'=>$set4,'set5'=>$set5,'user_id_a'=>$user_id_a,'double_faults'=>$double_faults]);
		}
	}

	//function to insert tennis statitistics
	public function tableTennisStatistics($player_ids_array,$match_type,$is_win='')
	{
		//$player_ids_array = explode(',',$player_ids);
		foreach($player_ids_array as $user_id)
		{
			$double_faults_count = '';

			$player_match_details = TtPlayerMatchScore::selectRaw('sum(double_faults) as double_faults_count')->where('user_id_a',$user_id)->groupBy('user_id_a')->get();

			if($match_type=='singles')
			{
				$double_faults_count = (!empty($player_match_details[0]['double_faults_count']))?$player_match_details[0]['double_faults_count']:'';
			}

			//check already user id exists or not
			$tennis_statistics_array = array();
			$tennisStatistics = TtStatistic::select()->where('user_id',$user_id)->where('match_type',$match_type)->get();
			if(count($tennisStatistics)>0)
			{
				$tennis_statistics_array = $tennisStatistics->toArray();
				$matches = !empty($tennis_statistics_array[0]['matches'])?$tennis_statistics_array[0]['matches']:0;
				$won = !empty($tennis_statistics_array[0]['won'])?$tennis_statistics_array[0]['won']:0;
				$lost = !empty($tennis_statistics_array[0]['lost'])?$tennis_statistics_array[0]['lost']:0;
				TtStatistic::where('user_id',$user_id)->where('match_type',$match_type)->update(['matches'=>$matches+1,'double_faults'=>$double_faults_count]);
				if($is_win=='yes') //win count
				{
					$won_percentage = number_format((($won+1)/($matches+1))*100,2);
					TtStatistic::where('user_id',$user_id)->where('match_type',$match_type)->update(['won'=>$won+1,'won_percentage'=>$won_percentage]);

				}else if($is_win=='no')//loss count
				{
					TtStatistic::where('user_id',$user_id)->where('match_type',$match_type)->update(['lost'=>$lost+1]);
				}
			}else
			{
				$won='';
				$won_percentage='';
				$lost='';
				if($is_win=='yes') //win count
				{
					$won = 1;
					$won_percentage = number_format(100,2);
				}else if($is_win=='no') //lost count
				{
					$lost=1;
				}
				$tennisStatisticsModel = new TtStatistic();
				$tennisStatisticsModel->user_id = $user_id;
				$tennisStatisticsModel->match_type = $match_type;
				$tennisStatisticsModel->matches = 1;
				$tennisStatisticsModel->won_percentage = $won_percentage;
				$tennisStatisticsModel->won = $won;
				$tennisStatisticsModel->lost = $lost;
				$tennisStatisticsModel->double_faults = $double_faults_count;
				$tennisStatisticsModel->save();
			}
		}

	}
	//function to save cricket details
	public function cricketScoreCard($match_data,$sportsDetails=[],$tournamentDetails=[],$is_from_view=0)
	{
		$loginUserId = '';
		$loginUserRole = '';

		if(isset(Auth::user()->id))
			$loginUserId = Auth::user()->id;

		if(isset(Auth::user()->role))
			$loginUserRole = Auth::user()->role;

		$team_a_players = array();
		$team_b_players = array();
		$team_a_id = $match_data[0]['a_id'];
		$team_b_id = $match_data[0]['b_id'];

		$team_a_playerids = explode(',',$match_data[0]['player_a_ids']);
		$team_b_playerids = explode(',',$match_data[0]['player_b_ids']);

		$team_a_scnd_ing_playerids = explode(',',$match_data[0]['player_a_ids']);
		$team_b_scnd_ing_playerids = explode(',',$match_data[0]['player_b_ids']);



		$score_status_array = json_decode($match_data[0]['score_added_by'],true);
		if(!empty($score_status_array['fst_ing_batting']))
		{
			if($score_status_array['fst_ing_batting']==$team_a_id)
			{
				$team_a_playerids = explode(',',$match_data[0]['player_a_ids']);
				$team_b_playerids = explode(',',$match_data[0]['player_b_ids']);
			}
			else
			{
				$team_a_playerids = explode(',',$match_data[0]['player_b_ids']);
				$team_b_playerids = explode(',',$match_data[0]['player_a_ids']);
			}


		}
		if(!empty($score_status_array['scnd_ing_batting']))
		{
			if($score_status_array['scnd_ing_batting']==$team_a_id)
			{
				$team_a_scnd_ing_playerids = explode(',',$match_data[0]['player_a_ids']);
				$team_b_scnd_ing_playerids = explode(',',$match_data[0]['player_b_ids']);
			}
			else
			{
				$team_a_scnd_ing_playerids = explode(',',$match_data[0]['player_b_ids']);
				$team_b_scnd_ing_playerids = explode(',',$match_data[0]['player_a_ids']);
			}


		}
		//get player names
		$a_team_players = User::select()->whereIn('id',$team_a_playerids)->get();
		$b_team_players = User::select()->whereIn('id',$team_b_playerids)->get();

		//get second inning player names
		$a_scng_ing_team_players = User::select()->whereIn('id',$team_a_scnd_ing_playerids)->get();
		$b_scnd_ing_team_players = User::select()->whereIn('id',$team_b_scnd_ing_playerids)->get();

		//get team names
		$team_a_name = Team::where('id',$team_a_id)->pluck('name');
		$team_b_name = Team::where('id',$team_b_id)->pluck('name');

		//get team logos
		$team_a_logo = Photo::select()->where('imageable_id', $team_a_id)->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first(); //get team a logo

		$team_b_logo = Photo::select()->where('imageable_id', $team_b_id)->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first(); //get team b logo


		//bye match
		if($match_data[0]['b_id']=='' && $match_data[0]['match_status']=='completed')
		{
			$sport_class = 'cricket_scorcard';
			$upload_folder = 'teams';
			return view('scorecards.byematchview',array('team_a_name'=>$team_a_name,'team_a_logo'=>$team_a_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'sport_class'=>$sport_class));
		}

		if(!empty($a_team_players))
			$team_a_players = $a_team_players->toArray();
		if(!empty($b_team_players))
			$team_b_players = $b_team_players->toArray();
		$team_a = array();
		$team_b = array();


		//second inning player names
		if(!empty($a_scng_ing_team_players))
			$team_a_scnd_ing_players = $a_scng_ing_team_players->toArray();
		if(!empty($b_scnd_ing_team_players))
			$team_b_scnd_ing_players = $b_scnd_ing_team_players->toArray();
		$team_a = array();
		$team_b = array();


		$team_a_scnd_ing = array();
		$team_b_scnd_ing = array();

		//get team a players
		foreach($team_a_players as $team_a_player)
		{
			$team_a[$team_a_player['id']] = $team_a_player['name'];
		}

		//get team b players
		foreach($team_b_players as $team_b_player)
		{
			$team_b[$team_b_player['id']] = $team_b_player['name'];
		}

		//get team a second inning players
		foreach($team_a_scnd_ing_players as $team_a_ing_player)
		{
			$team_a_scnd_ing[$team_a_ing_player['id']] = $team_a_ing_player['name'];
		}

		//get team b second inning players
		foreach($team_b_scnd_ing_players as $team_b_ing_player)
		{
			$team_b_scnd_ing[$team_b_ing_player['id']] = $team_b_ing_player['name'];
		}

		//out_as enum
		$enum = config('constants.ENUM.SCORE_CARD.OUT_AS');

		$fstIngFstBatId = $team_a_id;
		$fstIngsecondBatId = $team_b_id;
		$secondIngFstBatId = $team_a_id;
		$secondIngsecondBatId = $team_b_id;
		$toss_won_by = !empty($score_status_array['toss_won_by']) ? $score_status_array['toss_won_by'] : $team_a_id;

		$first_innings_team_variable['first']   = 'a';
		$first_innings_team_variable['second']  = 'b';
		$second_innings_team_variable['first']  = 'a';
		$second_innings_team_variable['second'] = 'b';

		if(!empty($score_status_array['fst_ing_batting']))
		{
			$fstIngFstBatId = $score_status_array['fst_ing_batting'];
			if($fstIngFstBatId==$team_a_id)
				$fstIngsecondBatId = $team_b_id;
			else
			{
				$first_innings_team_variable['first']  = 'b';
				$first_innings_team_variable['second'] = 'a';
				$fstIngsecondBatId = $team_a_id;
			}
		}

		if(!empty($score_status_array['scnd_ing_batting']))
		{
			$secondIngFstBatId = $score_status_array['scnd_ing_batting'];
			if($secondIngFstBatId==$team_a_id)
				$secondIngsecondBatId = $team_b_id;
			else
			{
				$second_innings_team_variable['first']  = 'b';
				$second_innings_team_variable['second'] = 'a';
				$secondIngsecondBatId = $team_a_id;
			}
		}



		//get team a details first innings
		$team_a_fst_ing_array = array();

		$team_a_fst_innings = CricketPlayerMatchwiseStats::select()
			->where('match_id',$match_data[0]['id'])
			->where('team_id',$fstIngFstBatId)
			->where('innings','first')
			->where(function($q1){
				$q1->where('totalruns','>',0)->orWhere('balls_played','>',0)->orWhere('fours','>',0)->orWhere('sixes','>',0)->orwhereNotNull('out_as');
			})
			->orderBy('sr_no_in_batting_team', 'asc')
			->get();

		if(count($team_a_fst_innings)>0)
		{
			$team_a_fst_ing_array = $team_a_fst_innings->toArray();
		}
		$team_a_fst_ing_bowling_array = array();


		//get team a bowling Details
		$team_a_fst_bowling = CricketPlayerMatchwiseStats::select()
			->where('match_id',$match_data[0]['id'])
			->where('team_id',$fstIngFstBatId)
			->where('innings','first')
			->where(function($q1){
				$q1->where('overs_bowled','>',0)->orWhere('overs_maiden','>',0)->orWhere('wickets','>',0)->orWhere('runs_conceded','>',0)->orWhere('wides_bowl','>',0)->orWhere('noballs_bowl','>',0)->orWhere('ecomony','>',0);
			})
			->orderBy('sr_no_in_bowling_team', 'asc')
			->get();

		if(count($team_a_fst_bowling)>0)
		{
			$team_a_fst_ing_bowling_array = $team_a_fst_bowling->toArray();
		}



		//get team a details first innings
		$team_b_fst_ing_array = array();

		$team_b_fst_innings = CricketPlayerMatchwiseStats::select()
			->where('match_id',$match_data[0]['id'])
			->where('team_id',$fstIngsecondBatId)
			->where('innings','first')
			->where(function($q1){
				$q1->where('totalruns','>',0)->orWhere('balls_played','>',0)->orWhere('fours','>',0)->orWhere('sixes','>',0)->orwhereNotNull('out_as');
			})
			->orderBy('sr_no_in_batting_team', 'asc')
			->get();

		if(count($team_b_fst_innings)>0)
		{
			$team_b_fst_ing_array = $team_b_fst_innings->toArray();
		}

		//get team b bowling Details
		$team_b_fst_ing_bowling_array = array();
		$team_b_fst_bowling = CricketPlayerMatchwiseStats::select()
			->where('match_id',$match_data[0]['id'])
			->where('team_id',$fstIngsecondBatId)
			->where('innings','first')
			->where(function($q2){
				$q2->where('overs_bowled','>',0)->orWhere('overs_maiden','>',0)->orWhere('wickets','>',0)->orWhere('runs_conceded','>',0)->orWhere('wides_bowl','>',0)->orWhere('noballs_bowl','>',0)->orWhere('ecomony','>',0);
			})
			->orderBy('sr_no_in_bowling_team', 'asc')
			->get();

		if(count($team_b_fst_bowling)>0)
		{
			$team_b_fst_ing_bowling_array = $team_b_fst_bowling->toArray();
		}

		//if test match second innings
		$team_a_secnd_ing_array = array();
		$team_b_secnd_ing_array = array();
		$team_a_scnd_ing_bowling_array = array();
		$team_b_scnd_ing_bowling_array = array();
		if($match_data[0]['match_type']=='test')
		{
			//get team a details second innings
			$team_a_second_innings = CricketPlayerMatchwiseStats::select()
				->where('match_id',$match_data[0]['id'])
				->where('team_id',$secondIngFstBatId)
				->where('innings','second')
				->where(function($q1){
					$q1->where('totalruns','>',0)->orWhere('balls_played','>',0)->orWhere('fours','>',0)->orWhere('sixes','>',0)->orwhereNotNull('out_as');
				})
				->orderBy('sr_no_in_batting_team', 'asc')
				->get();

			if(count($team_a_second_innings)>0)
			{
				$team_a_secnd_ing_array = $team_a_second_innings->toArray();
			}

			//get team a bowling details

			$team_a_scnd_bowling = CricketPlayerMatchwiseStats::select()
				->where('match_id',$match_data[0]['id'])
				->where('team_id',$secondIngFstBatId)
				->where('innings','second')
				->where(function($q2){
					$q2->where('overs_bowled','>',0)->orWhere('overs_maiden','>',0)->orWhere('wickets','>',0)->orWhere('runs_conceded','>',0)->orWhere('wides_bowl','>',0)->orWhere('noballs_bowl','>',0)->orWhere('ecomony','>',0);
				})
				->orderBy('sr_no_in_bowling_team', 'asc')
				->get();

			if(count($team_a_scnd_bowling)>0)
			{
				$team_a_scnd_ing_bowling_array = $team_a_scnd_bowling->toArray();
			}


			//get team b details second innings
			$team_b_fst_innings = CricketPlayerMatchwiseStats::select()
				->where('match_id',$match_data[0]['id'])
				->where('team_id',$secondIngsecondBatId)
				->where('innings','second')
				->where(function($q3){
					$q3->where('totalruns','>',0)->orWhere('balls_played','>',0)->orWhere('fours','>',0)->orWhere('sixes','>',0)->orwhereNotNull('out_as');
				})
				->orderBy('sr_no_in_batting_team', 'asc')
				->get();

			if(count($team_b_fst_innings)>0)
			{
				$team_b_secnd_ing_array = $team_b_fst_innings->toArray();
			}

			//get team b bowling Details
			$team_b_scnd_bowling = CricketPlayerMatchwiseStats::select()
				->where('match_id',$match_data[0]['id'])
				->where('team_id',$secondIngsecondBatId)
				->where('innings','second')
				->where(function($q2){
					$q2->where('overs_bowled','>',0)->orWhere('overs_maiden','>',0)->orWhere('wickets','>',0)->orWhere('runs_conceded','>',0)->orWhere('wides_bowl','>',0)->orWhere('noballs_bowl','>',0)->orWhere('ecomony','>',0);
				})
				->orderBy('sr_no_in_bowling_team', 'asc')
				->get();

			if(count($team_b_scnd_bowling)>0)
			{
				$team_b_scnd_ing_bowling_array = $team_b_scnd_bowling->toArray();
			}
		}

		//get match details fall of wickets
		$team_wise_match_details = array();
		$match_details = $match_data[0]['match_details'];
		if($match_details!='' && $match_details!=NULL)
		{
			$json_decode_array = json_decode($match_details,true);
			foreach($json_decode_array as $key => $array)
			{
				$team_wise_match_details[$key] = $array;
			}
		}

		$b_keyCount_fst_ing=0; // get fall of wickets for team a first inning
		$a_keyCount_fst_ing=0; // get fall of wickets for team b first inning

		$b_keycount_scnd_ing = 0;
		$a_keycount_scnd_ing = 0;
		if(count($team_wise_match_details)>0)
		{
			//get b array key count
			$b_keyCount_fst_ing = count(
				array_filter(
					array_keys($team_wise_match_details[$match_data[0]['b_id']]['first']),
					'is_numeric'
				)
			);

			//get a array key count
			$a_keyCount_fst_ing = count(
				array_filter(
					array_keys($team_wise_match_details[$match_data[0]['a_id']]['first']),
					'is_numeric'
				)
			);
			if($match_data[0]['match_type']=='test') //FOR TEST MATCH second ing
			{
				if(!empty($team_wise_match_details[$match_data[0]['b_id']]['second']))
				{
					//get b array key count
					$b_keycount_scnd_ing = count(
						array_filter(
							array_keys($team_wise_match_details[$match_data[0]['b_id']]['second']),
							'is_numeric'
						)
					);

					//get a array key count
					$a_keycount_scnd_ing = count(
						array_filter(
							array_keys($team_wise_match_details[$match_data[0]['a_id']]['second']),
							'is_numeric'
						)
					);
				}

			}
		}



		$team_a_count = count($team_a);
		$team_b_count = count($team_b);

		//second inning team player count
		$team_a_scnd_ing_count = count($team_a_scnd_ing);
		$team_b_scnd_ing_count = count($team_b_scnd_ing);

		$team_a_fst_ing_score='';
		$team_a_fst_ing_wkt='';
		$team_a_fst_ing_overs='';
		$team_a_scnd_ing_score='';
		$team_a_scnd_ing_wkt='';
		$team_a_scnd_ing_overs='';
		//team_a_total_score fst ing
		if(!empty($team_wise_match_details[$fstIngFstBatId]['fst_ing_score']) && $team_wise_match_details[$fstIngFstBatId]['fst_ing_score']!=null)
		{
			$team_a_fst_ing_score = $team_wise_match_details[$fstIngFstBatId]['fst_ing_score'];
		}
		if(!empty($team_wise_match_details[$fstIngFstBatId]['fst_ing_score']) && $team_wise_match_details[$fstIngFstBatId]['fst_ing_score']!=null)
		{
			$team_a_fst_ing_wkt = $team_wise_match_details[$fstIngFstBatId]['fst_ing_wkt'];
		}
		if(!empty($team_wise_match_details[$fstIngFstBatId]['fst_ing_score']) && $team_wise_match_details[$fstIngFstBatId]['fst_ing_score']!=null)
		{
			$team_a_fst_ing_overs = $team_wise_match_details[$fstIngFstBatId]['fst_ing_overs'];
		}

		//team_a_total_score scnd ing
		if(!empty($team_wise_match_details[$secondIngFstBatId]['scnd_ing_score']) && $team_wise_match_details[$secondIngFstBatId]['scnd_ing_score']!=null)
		{
			$team_a_scnd_ing_score = $team_wise_match_details[$secondIngFstBatId]['scnd_ing_score'];
		}
		if(!empty($team_wise_match_details[$secondIngFstBatId]['scnd_ing_wkt']) && $team_wise_match_details[$secondIngFstBatId]['scnd_ing_wkt']!=null)
		{
			$team_a_scnd_ing_wkt = $team_wise_match_details[$secondIngFstBatId]['scnd_ing_wkt'];
		}
		if(!empty($team_wise_match_details[$secondIngFstBatId]['scnd_ing_overs']) && $team_wise_match_details[$secondIngFstBatId]['scnd_ing_overs']!=null)
		{
			$team_a_scnd_ing_overs = $team_wise_match_details[$secondIngFstBatId]['scnd_ing_overs'];
		}

		$team_b_fst_ing_score='';
		$team_b_fst_ing_wkt='';
		$team_b_fst_ing_overs='';
		$team_b_scnd_ing_score='';
		$team_b_scnd_ing_wkt='';
		$team_b_scnd_ing_overs='';
		//team_b_total_score
		if(!empty($team_wise_match_details[$fstIngsecondBatId]['fst_ing_score']) && $team_wise_match_details[$fstIngsecondBatId]['fst_ing_score']!=null)
		{
			$team_b_fst_ing_score = $team_wise_match_details[$fstIngsecondBatId]['fst_ing_score'];
		}
		if(!empty($team_wise_match_details[$fstIngsecondBatId]['fst_ing_wkt']) && $team_wise_match_details[$fstIngsecondBatId]['fst_ing_wkt']!=null)
		{
			$team_b_fst_ing_wkt = $team_wise_match_details[$fstIngsecondBatId]['fst_ing_wkt'];
		}
		if(!empty($team_wise_match_details[$fstIngsecondBatId]['fst_ing_overs']) && $team_wise_match_details[$fstIngsecondBatId]['fst_ing_overs']!=null)
		{
			$team_b_fst_ing_overs = $team_wise_match_details[$fstIngsecondBatId]['fst_ing_overs'];
		}

		//team_b_total_score scnd ing
		if(!empty($team_wise_match_details[$secondIngsecondBatId]['scnd_ing_score']) && $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_score']!=null)
		{
			$team_b_scnd_ing_score = $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_score'];
		}
		if(!empty($team_wise_match_details[$secondIngsecondBatId]['scnd_ing_wkt']) && $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_wkt']!=null)
		{
			$team_b_scnd_ing_wkt = $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_wkt'];
		}
		if(!empty($team_wise_match_details[$secondIngsecondBatId]['scnd_ing_overs']) && $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_overs']!=null)
		{
			$team_b_scnd_ing_overs = $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_overs'];
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
		$rej_note_str = trim($rej_note_str, ",");
		//is valid user for score card enter or edit
		$isValidUser = 0;
		$isApproveRejectExist = 0;
		$isForApprovalExist = 0;
		if(isset(Auth::user()->id)){
			$isValidUser = Helper::isValidUserForScoreEnter($match_data);
			//is approval process exist
			$isApproveRejectExist = Helper::isApprovalExist($match_data);
			$isForApprovalExist = Helper::isApprovalExist($match_data,$isForApproval='yes');
		}


		//team a city
		$team_a_city = Helper::getTeamCity($match_data[0]['a_id']);
		$team_b_city = Helper::getTeamCity($match_data[0]['b_id']);

		if($is_from_view==1  || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser) //only to view cricket score
		{
			$player_name_array = array();
			$users = User::select('id', 'name')->get()->toArray(); //get player names
			foreach ($users as $user) {
				$player_name_array[$user['id']] = $user['name']; //get team names
			}

			$enum_shortcuts = [     'bowled'                => 'b',
				'caught'                => 'c',
				'handled_ball'          => 'htb',
				'hit_ball_twice'        => 'htbt',
				'hit_wicket'            => 'hw',
				'lbw'                   => 'lbw',
				'obstructing_the_field' => 'otf',
				'retired'               => 'r',
				'run_out'               => 'ro',
				'stumped'               => 's',
				'timed_out'             => 'to',
				'not_out'               => 'no'];

			return view('scorecards.cricketscorecardview', array(
				'first_innings_team_variable'   => $first_innings_team_variable,
				'second_innings_team_variable'  => $second_innings_team_variable,
				'toss_won_by'                   => $toss_won_by,
				'fstIngFstBatId'                => $fstIngFstBatId,
				'fstIngsecondBatId'             => $fstIngsecondBatId,
				'secondIngFstBatId'             => $secondIngFstBatId,
				'secondIngsecondBatId'          => $secondIngsecondBatId,
				'tournamentDetails'             => $tournamentDetails,
				'sportsDetails'                 => $sportsDetails,
				'team_a'                        => ['' => 'Select Player'] + $team_a,
				'team_b'                        => ['' => 'Select Player'] + $team_b,
				'match_data'                    => $match_data,
				'team_a_name'                   => $team_a_name,
				'team_b_name'                   => $team_b_name,
				'enum'                          => ['' => 'Select Out As'] + $enum,
				'team_a_fst_ing_array'          => $team_a_fst_ing_array,
				'team_b_fst_ing_array'          => $team_b_fst_ing_array,
				'team_a_secnd_ing_array'        => $team_a_secnd_ing_array,
				'team_b_secnd_ing_array'        => $team_b_secnd_ing_array,
				'team_wise_match_details'       => $team_wise_match_details,
				'team_a_count'                  => $team_a_count,
				'team_b_count'                  => $team_b_count,
				'team_a_logo'                   => $team_a_logo,
				'team_b_logo'                   => $team_b_logo,

				'team_a_fst_ing_score'          => ${'team_'.$first_innings_team_variable['first'].'_fst_ing_score'},
				'team_a_fst_ing_wkt'            => ${'team_'.$first_innings_team_variable['first'].'_fst_ing_wkt'},
				'team_a_fst_ing_overs'          => ${'team_'.$first_innings_team_variable['first'].'_fst_ing_overs'},

				'team_a_scnd_ing_score'         => ${'team_'.$second_innings_team_variable['first'].'_scnd_ing_score'},
				'team_a_scnd_ing_wkt'           => ${'team_'.$second_innings_team_variable['first'].'_scnd_ing_wkt'},
				'team_a_scnd_ing_overs'         => ${'team_'.$second_innings_team_variable['first'].'_scnd_ing_overs'},

				'team_b_fst_ing_score'          => ${'team_'.$first_innings_team_variable['second'].'_fst_ing_score'},
				'team_b_fst_ing_wkt'            => ${'team_'.$first_innings_team_variable['second'].'_fst_ing_wkt'},
				'team_b_fst_ing_overs'          => ${'team_'.$first_innings_team_variable['second'].'_fst_ing_overs'},

				'team_b_scnd_ing_score'         => ${'team_'.$second_innings_team_variable['second'].'_scnd_ing_score'},
				'team_b_scnd_ing_wkt'           => ${'team_'.$second_innings_team_variable['second'].'_scnd_ing_wkt'},
				'team_b_scnd_ing_overs'         => ${'team_'.$second_innings_team_variable['second'].'_scnd_ing_overs'},

				'player_name_array'             => $player_name_array,
				'a_keyCount'                    => $a_keyCount_fst_ing,
				'b_keyCount'                    => $b_keyCount_fst_ing,
				'a_keycount_scnd_ing'           => $a_keycount_scnd_ing,
				'b_keycount_scnd_ing'           => $b_keycount_scnd_ing,
				'enum_shortcuts'                => $enum_shortcuts,
				'score_status_array'            => $score_status_array,
				'loginUserId'                   => $loginUserId,
				'rej_note_str'                  => $rej_note_str,
				'loginUserRole'                 => $loginUserRole,
				'isValidUser'                   => $isValidUser,
				'isApproveRejectExist'          => $isApproveRejectExist,
				'isForApprovalExist'            => $isForApprovalExist,
				'action_id'                     => $match_data[0]['id'],
				'team_a_scnd_ing_count'         => $team_a_scnd_ing_count,
				'team_b_scnd_ing_count'         => $team_b_scnd_ing_count,
				'team_a_scnd_ing'               => ['' => 'Select Player'] + $team_a_scnd_ing,
				'team_b_scnd_ing'               => ['' => 'Select Player'] + $team_b_scnd_ing,
				'team_a_fst_ing_bowling_array'  => $team_a_fst_ing_bowling_array,
				'team_b_fst_ing_bowling_array'  => $team_b_fst_ing_bowling_array,
				'team_a_scnd_ing_bowling_array' => $team_a_scnd_ing_bowling_array,
				'team_b_scnd_ing_bowling_array' => $team_b_scnd_ing_bowling_array,
				'team_a_city'                   => $team_a_city,
				'team_b_city'                   => $team_b_city));
		}
		else //to view and edit cricket score card
		{
			return view('scorecards.cricketscorecard', array(
				'first_innings_team_variable'   => $first_innings_team_variable,
				'second_innings_team_variable'  => $second_innings_team_variable,
				'toss_won_by'                   => $toss_won_by,
				'fstIngFstBatId'                => $fstIngFstBatId,
				'fstIngsecondBatId'             => $fstIngsecondBatId,
				'secondIngFstBatId'             => $secondIngFstBatId,
				'secondIngsecondBatId'          => $secondIngsecondBatId,
				'tournamentDetails'             => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'enum'=> ['' => 'Select Out As'] + $enum,'team_a_fst_ing_array'=>$team_a_fst_ing_array,'team_b_fst_ing_array'=>$team_b_fst_ing_array,'team_a_secnd_ing_array'=>$team_a_secnd_ing_array,'team_b_secnd_ing_array'=>$team_b_secnd_ing_array,'team_wise_match_details'=>$team_wise_match_details,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'team_a_fst_ing_score'=>$team_a_fst_ing_score,'team_a_fst_ing_wkt'=>$team_a_fst_ing_wkt,'team_a_fst_ing_overs'=>$team_a_fst_ing_overs,'team_a_scnd_ing_score'=>$team_a_scnd_ing_score,'team_a_scnd_ing_wkt'=>$team_a_scnd_ing_wkt,'team_a_scnd_ing_overs'=>$team_a_scnd_ing_overs,'team_b_fst_ing_score'=>$team_b_fst_ing_score,'team_b_fst_ing_wkt'=>$team_b_fst_ing_wkt,'team_b_fst_ing_overs'=>$team_b_fst_ing_overs,'team_b_scnd_ing_score'=>$team_b_scnd_ing_score,'team_b_scnd_ing_wkt'=>$team_b_scnd_ing_wkt,'team_b_scnd_ing_overs'=>$team_b_scnd_ing_overs,'a_keyCount'=>$a_keyCount_fst_ing,'b_keyCount'=>$b_keyCount_fst_ing,'a_keycount_scnd_ing'=>$a_keycount_scnd_ing,'b_keycount_scnd_ing'=>$b_keycount_scnd_ing,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_scnd_ing_count'=>$team_a_scnd_ing_count,'team_b_scnd_ing_count'=>$team_b_scnd_ing_count,'team_a_scnd_ing'=>[''=>'Select Player']+$team_a_scnd_ing,'team_b_scnd_ing'=>[''=>'Select Player']+$team_b_scnd_ing,'team_a_fst_ing_bowling_array'=>$team_a_fst_ing_bowling_array,'team_b_fst_ing_bowling_array'=>$team_b_fst_ing_bowling_array,'team_a_scnd_ing_bowling_array'=>$team_a_scnd_ing_bowling_array,'team_b_scnd_ing_bowling_array'=>$team_b_scnd_ing_bowling_array,'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city));
		}

	}

	public function insertCricketScoreCard()
	{
		$loginUserId         = Auth::user()->id;
		$request             = Request::all();

		//team a details
		$team_a_player_count = Request::get('a_player_count');
		$team_b_player_count = Request::get('b_player_count');
		$a_bowler_count      = Request::get('a_bowler_count');
		$b_bowler_count      = Request::get('b_bowler_count');
		$team_a_name         = Request::get('team_a_name');
		$team_b_name         = Request::get('team_b_name');
		$winner_team_id      = !empty(Request::get('winner_team_id')) ? Request::get('winner_team_id') : NULL; //winner team id
		$match_result        = !empty(Request::get('hid_match_result')) ? Request::get('hid_match_result') : NULL; //winner team id

		$team_a_id     = !empty(Request::get('team_a_id')) ? Request::get('team_a_id') : NULL;
		$team_b_id     = !empty(Request::get('team_b_id')) ? Request::get('team_b_id') : NULL;
		$tournament_id = !empty(Request::get('tournament_id')) ? Request::get('tournament_id') : NULL;
		$match_type    = !empty(Request::get('match_type')) ? Request::get('match_type') : NULL;
		$inning        = !empty(Request::get('inning')) ? Request::get('inning') : 'first';
		$match_id      = Request::get('match_id');
		$match_report  = !empty(Request::get('match_report')) ? Request::get('match_report') : NULL;
		$player_of_the_match  = !empty(Request::get('player_of_the_match')) ? Request::get('player_of_the_match') : NULL;

		$match_model=MatchSchedule::find($match_id);
		$team_a_model=Team::find($team_a_id);
		$team_b_model=Team::find($team_b_id);

		if(!is_null($team_a_model)){
			$team_a_name=$team_a_model->name;
		}
		if(!is_null($team_b_model)){
			$team_b_name=$team_b_model->name;
		}
		
		//toss won by
		$tossWonBy = !empty(Request::get('toss_won_by'))?Request::get('toss_won_by'):NULL; //toss won by team id
		$toss_won_team_name = !empty(Request::get('toss_won_team_name'))?Request::get('toss_won_team_name'):NULL; //toss won by team id

		//delete all records before insert or update score card
		$match_score = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('innings',$inning)->get();
		if (count($match_score)>0)
		{
			CricketPlayerMatchwiseStats::where('match_id',$match_id)->where('innings',$inning)->update(['deleted_at'=>Carbon::now()]);
		}
		for($i=1;$i<=$team_a_player_count;$i++)//insert team a batsman score
		{
			$user_id_a = !empty(Request::get('a_player_'.$i))?Request::get('a_player_'.$i):0;

			$totalruns_a = (is_numeric(Request::get('a_runs_'.$i)))?Request::get('a_runs_'.$i):0;
			$balls_played_a = (is_numeric(Request::get('a_balls_'.$i)))?Request::get('a_balls_'.$i):0;
			$fours_a = (is_numeric(Request::get('a_fours_'.$i)))?Request::get('a_fours_'.$i):0;
			$sixes_a = (is_numeric(Request::get('a_sixes_'.$i)))?Request::get('a_sixes_'.$i):0;
			$out_as_a = Request::get('a_outas_'.$i);
			$strikerate_a = Request::get('a_strik_rate_'.$i);
			$fielder_id_a = 0;
			$bowled_id_a = Request::get('a_bowled_'.$i);
			if($out_as_a=='caught' || $out_as_a=='run_out' || $out_as_a=='stumped')
			{
				$fielder_id_a = Request::get('a_fielder_'.$i);
			}
			if($out_as_a=='handled_ball' || $out_as_a=='obstructing_the_field' || $out_as_a=='retired' || $out_as_a=='timed_out' || $out_as_a=='not_out')
			{
				$bowled_id_a = 0;
			}


			if($user_id_a>0)
			{
				$player_name = User::where('id',$user_id_a)->pluck('name');
				//check already player exists r not
				$is_player_exist = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('team_id',$team_a_id)->where('user_id',$user_id_a)->where('innings',$inning)->get()->first();

				//bat status
				$a_bat_status = 'notout';
				if($out_as_a!='')
				{
					$a_bat_status = 'out';
				}else if(!($totalruns_a>0 || $balls_played_a>0 || $fours_a>0 || $sixes_a>0))
				{
					$a_bat_status = 'dnb';
				}

				// 50's and 100's - start
				$fifties = $hundreds = 0;

				if ((int) $totalruns_a > 50)
				{
					$fifties = (int) floor($totalruns_a / 50);
				}

				if ((int) $totalruns_a > 100)
				{
					$hundreds = (int) floor($totalruns_a / 100);
				}

				if (count($is_player_exist)>0)// if player already exist
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
				}else
				{
					$this->insertBatsmenScore($user_id_a,$tournament_id,$match_id,$team_a_id,$match_type,$balls_played_a,$totalruns_a,$fours_a,$sixes_a,$out_as_a,$strikerate_a,$team_a_name,$player_name,$inning,$fielder_id_a,$bowled_id_a,$a_bat_status,$i,$fifties,$hundreds);
				}


			}
		}
		//Team a bowler Detail
		for($j=1;$j<=$a_bowler_count;$j++)
		{
			$bowler_id_a = !empty(Request::get('a_bowler_'.$j))?Request::get('a_bowler_'.$j):0;
			$bowler_name = User::where('id',$bowler_id_a)->pluck('name');
			$a_overs_bowled = Request::get('a_bowler_overs_'.$j);
			$a_wickets = (is_numeric(Request::get('a_bowler_wkts_'.$j)))?Request::get('a_bowler_wkts_'.$j):0;
			$a_runs_conceded = (is_numeric(Request::get('a_bowler_runs_'.$j)))?Request::get('a_bowler_runs_'.$j):0;
			$a_ecomony = Request::get('a_ecomony_'.$j);
			$a_wide = !empty(Request::get('a_bowler_wide_'.$j))?Request::get('a_bowler_wide_'.$j):0;
			$a_noball = !empty(Request::get('a_bowler_noball_'.$j))?Request::get('a_bowler_noball_'.$j):0;
			$a_maidens = !empty(Request::get('a_bowler_maidens_'.$j))?Request::get('a_bowler_maidens_'.$j):0;


			if($bowler_id_a>0 && ($a_overs_bowled>0 || $a_wickets>0 || $a_runs_conceded>0))
			{
				//check already bowler exists r not
				$is_bowler_exist = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('team_id',$team_a_id)->where('user_id',$bowler_id_a)->where('innings',$inning)->get()->first();

				if(count($is_bowler_exist)>0) // if player already exist
				{
					$bowler_id = $is_bowler_exist['id'];
					CricketPlayerMatchwiseStats::where('id',$bowler_id)->update(['overs_bowled'=>$a_overs_bowled,'wickets'=>$a_wickets,'runs_conceded'=>$a_runs_conceded,'ecomony'=>$a_ecomony,'wides_bowl'=>$a_wide,'noballs_bowl'=>$a_noball,'overs_maiden'=>$a_maidens,'sr_no_in_bowling_team'=>$j]);

				}else
				{
					$this->insertBowlerScore($bowler_id_a,$tournament_id,$match_id,$team_a_id,$match_type,$a_overs_bowled,$a_wickets,$a_runs_conceded,$a_ecomony,$team_a_name,$bowler_name,$inning,$a_wide,$a_noball,$a_maidens,$j);

				}

			}

		}
		//Team b innings
		for($k=1;$k<=$team_b_player_count;$k++)//insert team a batsman score
		{
			$user_id_b = !empty(Request::get('b_player_'.$k))?Request::get('b_player_'.$k):0;
			$player_b_name = User::where('id',$user_id_b)->pluck('name');
			$totalruns_b = (is_numeric(Request::get('b_runs_'.$k)))?Request::get('b_runs_'.$k):0;
			$balls_played_b = (is_numeric(Request::get('b_balls_'.$k)))?Request::get('b_balls_'.$k):0;
			$fours_b = (is_numeric(Request::get('b_fours_'.$k)))?Request::get('b_fours_'.$k):0;
			$sixes_b = (is_numeric(Request::get('b_sixes_'.$k)))?Request::get('b_sixes_'.$k):0;
			$out_as_b = Request::get('b_outas_'.$k);
			$strikerate_b = Request::get('b_strik_rate_'.$k);
			$bowled_id_b = Request::get('b_bowled_'.$k);
			$fielder_id_b=0;

			if($out_as_b=='caught' || $out_as_b=='run_out' || $out_as_b=='stumped')
			{
				$fielder_id_b = Request::get('b_fielder_'.$k);
			}
			if($out_as_b=='handled_ball' || $out_as_b=='obstructing_the_field' || $out_as_b=='retired' || $out_as_b=='timed_out' || $out_as_b=='not_out')
			{
				$bowled_id_b=0;
			}
			if($user_id_b>0)
			{
				//check already player exists r not
				$is_b_player_exist = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('team_id',$team_b_id)->where('user_id',$user_id_b)->where('innings',$inning)->get()->first();


				//bat status
				$b_bat_status = 'notout';
				if($out_as_b!='')
				{
					$b_bat_status = 'out';
				}else if(!($totalruns_b>0 || $balls_played_b>0 || $fours_b>0 || $sixes_b>0))
				{
					$b_bat_status = 'dnb';
				}

				// 50's and 100's - start
				$fifties = $hundreds = 0;

				if ((int) $totalruns_b > 50)
				{
					$fifties = (int) ($totalruns_b / 50);
				}

				if ((int) $totalruns_b > 100)
				{
					$hundreds = (int) ($totalruns_b / 100);
				}

				if(count($is_b_player_exist)>0)
				{
					$update_id = $is_b_player_exist['id'];
					CricketPlayerMatchwiseStats::where('id',$update_id)
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
				}else
				{
					$this->insertBatsmenScore($user_id_b,$tournament_id,$match_id,$team_b_id,$match_type,$balls_played_b,$totalruns_b,$fours_b,$sixes_b,$out_as_b,$strikerate_b,$team_b_name,$player_b_name,$inning,$fielder_id_b,$bowled_id_b,$b_bat_status,$k,$fifties,$hundreds);
				}



			}
		}
		//Team b bowler Detail
		for($l=1;$l<=$b_bowler_count;$l++)
		{
			$bowler_id_b = !empty(Request::get('b_bowler_'.$l))?Request::get('b_bowler_'.$l):0;
			$bowler_b_name = User::where('id',$bowler_id_b)->pluck('name');
			$b_overs_bowled = Request::get('b_bowler_overs_'.$l);
			$b_wickets = (is_numeric(Request::get('b_bowler_wkts_'.$l)))?Request::get('b_bowler_wkts_'.$l):0;
			$b_runs_conceded = (is_numeric(Request::get('b_bowler_runs_'.$l)))?Request::get('b_bowler_runs_'.$l):0;
			$b_ecomony = Request::get('b_ecomony_'.$l);
			$b_wide = !empty(Request::get('b_bowler_wide_'.$l))?Request::get('b_bowler_wide_'.$l):0;
			$b_noball = !empty(Request::get('b_bowler_noball_'.$l))?Request::get('b_bowler_noball_'.$l):0;
			$b_maidens = !empty(Request::get('b_bowler_maidens_'.$l))?Request::get('b_bowler_maidens_'.$l):0;

			if($bowler_id_b>0 && ($b_overs_bowled>0 || $b_wickets>0 || $b_runs_conceded>0))
			{
				//check already bowler exists r not
				$is_bowler_b_exist = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('team_id',$team_b_id)->where('user_id',$bowler_id_b)->where('innings',$inning)->get()->first();

				if(count($is_bowler_b_exist)>0)
				{
					$bowler_id = $is_bowler_b_exist['id'];
					CricketPlayerMatchwiseStats::where('id',$bowler_id)->update(['overs_bowled'=>$b_overs_bowled,'wickets'=>$b_wickets,'runs_conceded'=>$b_runs_conceded,'ecomony'=>$b_ecomony,'wides_bowl'=>$b_wide,'noballs_bowl'=>$b_noball,'overs_maiden'=>$b_maidens,'sr_no_in_bowling_team'=>$l]);

				}else
				{
					$this->insertBowlerScore($bowler_id_b,$tournament_id,$match_id,$team_b_id,$match_type,$b_overs_bowled,$b_wickets,$b_runs_conceded,$b_ecomony,$team_b_name,$bowler_b_name,$inning,$b_wide,$b_noball,$b_maidens,$l);
				}


			}

		}
		//Team b bowling

		//Fall of Wikets for Team a
		$team_a_fallofwkt_count = Request::get('a_fall_of_count');
		$team_a_array = array();
		for($m=1;$m<=$team_a_fallofwkt_count;$m++)
		{
			$wkt_a_at = (is_numeric(Request::get('a_wicket_'.$m)))?Request::get('a_wicket_'.$m):0;
			$player_a_id = Request::get('a_wkt_player_'.$m);
			$at_runs_a = (is_numeric(Request::get('a_at_runs_'.$m)))?Request::get('a_at_runs_'.$m):0;
			$at_over_a = Request::get('a_at_over_'.$m);

			if($player_a_id>0)
			{
				$team_a_array[] = array('wicket'=>$wkt_a_at,'batsman'=>$player_a_id,'score'=>$at_runs_a,'over'=>$at_over_a);
			}

		}
		//team a extras
		$team_a_wide = !empty(Request::get('team_a_wide'))?Request::get('team_a_wide'):0;
		$team_a_noball = !empty(Request::get('team_a_noball'))?Request::get('team_a_noball'):0;
		$team_a_legbye = !empty(Request::get('team_a_legbye'))?Request::get('team_a_legbye'):0;
		$team_a_bye = !empty(Request::get('team_a_bye'))?Request::get('team_a_bye'):0;
		$team_a_others = !empty(Request::get('team_a_others'))?Request::get('team_a_others'):0;
		$team_a_extras_array = array('wide'=>$team_a_wide,'noball'=>$team_a_noball,'legbye'=>$team_a_legbye,'bye'=>$team_a_bye,'others'=>$team_a_others);

		$team_level_stats = [];

		//team a scores
		foreach ([$team_a_id,$team_b_id] as $teamStat_team_id)
		{
			foreach (['first','second'] as $teamStat_innings_name)
			{
				foreach (['score','wickets','overs'] as $teamStat_inning_stat_name)
				{
					$value = '';
					if (isset($request[$teamStat_innings_name . '_inning'][$teamStat_team_id][$teamStat_inning_stat_name]))
					{
						$value = $request[$teamStat_innings_name . '_inning'][$teamStat_team_id][$teamStat_inning_stat_name];
					}
					$team_level_stats[$teamStat_team_id][$teamStat_innings_name][$teamStat_inning_stat_name] = (!empty($value) && $value > 0) ? $value : NULL;
				}
			}
		}

		$team_a_fst_ing_score  = $team_level_stats[$team_a_id]['first']['score'];
		$team_a_fst_ing_wkt    = $team_level_stats[$team_a_id]['first']['wickets'];
		$team_a_fst_ing_overs  = $team_level_stats[$team_a_id]['first']['overs'];
		$team_a_scnd_ing_score = $team_level_stats[$team_a_id]['second']['score'];
		$team_a_scnd_ing_wkt   = $team_level_stats[$team_a_id]['second']['wickets'];
		$team_a_scnd_ing_overs = $team_level_stats[$team_a_id]['second']['overs'];

		$team_a_score = array('fst_ing_score'=>$team_a_fst_ing_score,'scnd_ing_score'=>$team_a_scnd_ing_score,'fst_ing_wkt'=>$team_a_fst_ing_wkt,'scnd_ing_wkt'=>$team_a_scnd_ing_wkt,'fst_ing_overs'=>$team_a_fst_ing_overs,'scnd_ing_overs'=>$team_a_scnd_ing_overs);

		//team b scores
		$team_b_fst_ing_score  = $team_level_stats[$team_b_id]['first']['score'];
		$team_b_fst_ing_wkt    = $team_level_stats[$team_b_id]['first']['wickets'];
		$team_b_fst_ing_overs  = $team_level_stats[$team_b_id]['first']['overs'];
		$team_b_scnd_ing_score = $team_level_stats[$team_b_id]['second']['score'];
		$team_b_scnd_ing_wkt   = $team_level_stats[$team_b_id]['second']['wickets'];
		$team_b_scnd_ing_overs = $team_level_stats[$team_b_id]['second']['overs'];

		//team b extras
		$team_b_wide = !empty(Request::get('team_b_wide'))?Request::get('team_b_wide'):0;
		$team_b_noball = !empty(Request::get('team_b_noball'))?Request::get('team_b_noball'):0;
		$team_b_legbye = !empty(Request::get('team_b_legbye'))?Request::get('team_b_legbye'):0;
		$team_b_bye = !empty(Request::get('team_b_bye'))?Request::get('team_b_bye'):0;
		$team_b_others = !empty(Request::get('team_b_others'))?Request::get('team_b_others'):0;
		$team_b_extras_array = array('wide'=>$team_b_wide,'noball'=>$team_b_noball,'legbye'=>$team_b_legbye,'bye'=>$team_b_bye,'others'=>$team_b_others);

		$fallOfCount_a[$team_a_id][$inning] = $team_a_array+$team_a_extras_array;//indvidual team fall of count

		$team_a_two_ings_score[$team_a_id] = $team_a_score; //team a toral score with overs wikets

		$team_a_match_details = array_replace_recursive($fallOfCount_a,$team_a_two_ings_score) ;// merge fall of wkts, match score details

		$decode_json=array();
		//get exists match details
		$get_match_details = MatchSchedule::where('id',$match_id)->pluck('match_details');
		if($get_match_details!='')
			$decode_json = json_decode($get_match_details,true);


		//Fall of Wikets for Team b
		$team_b_fallofwkt_count = Request::get('b_fall_of_count');
		$team_b_array = array();
		for($n=1;$n<=$team_b_fallofwkt_count;$n++)
		{
			$wkt_b_at = (is_numeric(Request::get('b_wicket_'.$n)))?Request::get('b_wicket_'.$n):0;
			$player_b_id = Request::get('b_wkt_player_'.$n);
			$at_runs_b = (is_numeric(Request::get('b_at_runs_'.$n)))?Request::get('b_at_runs_'.$n):0;
			$at_over_b = Request::get('b_at_over_'.$n);
			if($player_b_id>0)
			{
				$team_b_array[] = array('wicket'=>$wkt_b_at,'batsman'=>$player_b_id,'score'=>$at_runs_b,'over'=>$at_over_b);
			}
		}

		$team_b_score = array('fst_ing_score'=>$team_b_fst_ing_score,'scnd_ing_score'=>$team_b_scnd_ing_score,'fst_ing_wkt'=>$team_b_fst_ing_wkt,'scnd_ing_wkt'=>$team_b_scnd_ing_wkt,'fst_ing_overs'=>$team_b_fst_ing_overs,'scnd_ing_overs'=>$team_b_scnd_ing_overs);

		$fallOfCount_b[$team_b_id][$inning] = $team_b_array+$team_b_extras_array;//indvidual team fall of count

		$team_b_two_ings_score[$team_b_id] = $team_b_score; //team a toral score with overs wikets

		$team_b_match_details = array_replace_recursive($fallOfCount_b,$team_b_two_ings_score); // merge fall of wkts, match score details

		$match_details_array = $team_a_match_details+$team_b_match_details;

		$final_match_details = array_replace_recursive($decode_json,$match_details_array);

		$json_match_details_array = json_encode($final_match_details);

		$is_tie         = ($match_result == 'tie')      ? 1 : 0;
        $is_washout     = ($match_result == 'washout')  ? 1 : 0;
        $has_result     = ($is_washout == 1) ? 0 : 1;
        $match_result   = ( !in_array( $match_result, ['tie','win','washout'] ) ) ? NULL : $match_result;

		//get previous scorecard status data
		$scorecardDetails = MatchSchedule::where('id',$match_id)->pluck('score_added_by');
		$decode_scorecard_data = json_decode($scorecardDetails,true);

		$modified_users = !empty($decode_scorecard_data['modified_users'])?$decode_scorecard_data['modified_users']:'';

		$modified_users = $modified_users.','.$loginUserId;//scorecard changed users

		$added_by = !empty($decode_scorecard_data['added_by'])?$decode_scorecard_data['added_by']:$loginUserId;


		//insert toss_won_by  and selected batting or bowling in scoer added by column
		$toss_won_by = !empty($decode_scorecard_data['toss_won_by'])?$decode_scorecard_data['toss_won_by']:$tossWonBy;
		$tossWonTeamName = !empty($decode_scorecard_data['toss_won_team_name'])?$decode_scorecard_data['toss_won_team_name']:$toss_won_team_name;
		$fst_ing_batting = !empty($decode_scorecard_data['fst_ing_batting'])?$decode_scorecard_data['fst_ing_batting']:$team_a_id;
		$scnd_ing_batting = !empty($decode_scorecard_data['scnd_ing_batting'])?$decode_scorecard_data['scnd_ing_batting']:$team_a_id;



		//score card approval process
		$score_status = array('added_by'=>$added_by,'active_user'=>$loginUserId,'modified_users'=>$modified_users,'rejected_note'=>'','toss_won_by'=>$toss_won_by,'toss_won_team_name'=>$tossWonTeamName,'fst_ing_batting'=>$fst_ing_batting,'scnd_ing_batting'=>$scnd_ing_batting);

		$json_score_status = json_encode($score_status);

		//update match details col  && winner id in match schedule table
		$matchScheduleDetails = MatchSchedule::where('id',$match_id)->first();
		if(count($matchScheduleDetails)) {
			$looser_team_id = NULL;
			$match_status = 'scheduled';
			$approved = '';
			if($is_tie==0 || $is_washout == 0) {

				if(isset($winner_team_id)) {
					if($winner_team_id==$matchScheduleDetails['a_id']) {
						$looser_team_id=$matchScheduleDetails['b_id'];
					}else{
						$looser_team_id=$matchScheduleDetails['a_id'];
					}
					$match_status = 'completed';
					$approved = 'approved';
				}

			}

			if (!empty($matchScheduleDetails['tournament_id']))
			{
//                        dd($winner_team_id.'<>'.$looser_team_id);
				$tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
				if (Helper::isTournamentOwner($tournamentDetails['manager_id'], $tournamentDetails['tournament_parent_id']))
				{
					if (($is_tie == 1 || $match_result == "washout") && !empty($matchScheduleDetails['tournament_group_id']))
					{
						$match_status = 'completed';
					}
					MatchSchedule::where('id', $match_id)->update([
						'match_details'  => $json_match_details_array,
						'match_status'   => $match_status,
						'winner_id'      => $winner_team_id,
						'looser_id'      => $looser_team_id,
						'is_tied'        => $is_tie,
                        'has_result'     => $has_result,
                        'match_result'   => $match_result,
						'score_added_by' => $json_score_status,
						'match_report'   => $match_report,
						'player_of_the_match'   => $player_of_the_match]);
//                                Helper::printQueries();

					if (!empty($matchScheduleDetails['tournament_round_number']))
					{
						$this->updateBracketDetails($matchScheduleDetails, $tournamentDetails, $winner_team_id);
					}
					if ($match_status == 'completed')
					{
						$sportName = Sport::where('id', $matchScheduleDetails['sports_id'])->pluck('sports_name');
						$this->insertPlayerStatistics($sportName, $match_id);

						Helper::sendEmailPlayers($matchScheduleDetails, 'Cricket');	

						//notification code
					}
				}
			}
			else if (Auth::user()->role == 'admin')
			{
				if ($is_tie == 1 || $match_result == "washout")
				{
					$match_status = 'completed';
					$approved     = 'approved';
				}

				MatchSchedule::where('id', $match_id)->update([
					'match_details'  => $json_match_details_array,
					'match_status'   => $match_status,
					'winner_id'      => $winner_team_id,
                    'looser_id'      => $looser_team_id,
					'is_tied'        => $is_tie,
                    'has_result'     => $has_result,
                    'match_result'   => $match_result,
                    'score_added_by' => $json_score_status,
					'scoring_status' => $approved,
					'match_report'   => $match_report,
					'player_of_the_match'   => $player_of_the_match]);
				if ($match_status == 'completed')
				{
					$sportName = Sport::where('id', $matchScheduleDetails['sports_id'])->pluck('sports_name');
					$this->insertPlayerStatistics($sportName, $match_id);

					Helper::sendEmailPlayers($matchScheduleDetails, 'Cricket');	

					//notification code
				}
			}
			else
			{
				MatchSchedule::where('id', $match_id)->update([
					'match_details'  => $json_match_details_array,
					'winner_id'      => $winner_team_id,
					'looser_id'      => $looser_team_id,
					'is_tied'        => $is_tie,
                    'has_result'     => $has_result,
                    'match_result'   => $match_result,
					'score_added_by' => $json_score_status,
					'match_report'   => $match_report,
					'player_of_the_match'   => $player_of_the_match]);
			}
		}

		//if($match_result!='')
		//return redirect()->route('match/scorecard/view', [$match_id])->with('status', trans('message.scorecard.scorecardmsg'));

		//return redirect()->back()->with('status', trans('message.scorecard.scorecardmsg'));
		//return Response()->json( array('success' => trans('message.scorecard.scorecardmsg')) );
	}
	//function to insert batsmen score
	public function insertBatsmenScore($user_id,$tournament_id,$match_id,$team_id,$match_type,$balls_played,$totalruns,$fours,$sixes,$out_as,$strikerate,$team_name,$player_name,$innings,$fielder_id,$bowled_id,$bat_status,$sr_no_in_batting_team=0,$fifties=0,$hundreds=0)
	{
		$model                        = new CricketPlayerMatchwiseStats();
		$model->user_id               = $user_id;
		$model->tournament_id         = $tournament_id;
		$model->match_id              = $match_id;
		$model->team_id               = $team_id;
		$model->match_type            = $match_type;
		$model->balls_played          = $balls_played;
		$model->totalruns             = $totalruns;
		$model->fours                 = $fours;
		$model->sixes                 = $sixes;
		$model->out_as                = $out_as;
		$model->strikerate            = $strikerate;
		$model->team_name             = $team_name;
		$model->player_name           = $player_name;
		$model->innings               = $innings;
		$model->fielder_id            = $fielder_id;
		$model->bowled_id             = $bowled_id;
		$model->bat_status            = $bat_status;
		$model->sr_no_in_batting_team = $sr_no_in_batting_team;
		$model->fifties               = $fifties;
		$model->hundreds              = $hundreds;
		$model->save();
	}

	//insert bowler score
	public function insertBowlerScore($bowler_id,$tournament_id,$match_id,$team_id,$match_type,$overs_bowled,$wickets,$runs_conceded,$ecomony,$team_name,$bowler_name,$inning,$wide,$noball,$maidens,$sr_no_in_bowling_team=0)
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

	//function to insert or update batsmen statistics
	public function cricketBatsmenStatistic($user_id,$match_type,$inning)
	{
		//check already record is exists or not
		$cricket_statistics_array = array();
		$cricket_statistics = CricketStatistic::select()->where('user_id',$user_id)->where('match_type',$match_type)->where('innings',$inning)->get();

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
			->where('user_id',$user_id)
			->where('match_type',$match_type)
			->where('innings',$inning)
			->groupBy('user_id')->get();

		$innings_bat = (!empty($batsman_detais[0]['inningscount'])) ? $batsman_detais[0]['inningscount'] : 0;
		$totalruns   = (!empty($batsman_detais[0]['totalruns'])) ? $batsman_detais[0]['totalruns'] : 0;
		$totalballs  = (!empty($batsman_detais[0]['balls_played'])) ? $batsman_detais[0]['balls_played'] : 0;
		$fours       = (!empty($batsman_detais[0]['fours'])) ? $batsman_detais[0]['fours'] : 0;
		$sixes       = (!empty($batsman_detais[0]['sixes'])) ? $batsman_detais[0]['sixes'] : 0;
		$match_count = (!empty($batsman_detais[0]['match_count'])) ? $batsman_detais[0]['match_count'] : 0;
		$fifties     = (!empty($batsman_detais[0]['fifties'])) ? $batsman_detais[0]['fifties'] : 0;
		$hundreds    = (!empty($batsman_detais[0]['hundreds'])) ? $batsman_detais[0]['hundreds'] : 0;
		$notouts     = (!empty($batsman_detais[0]['notouts'])) ? $batsman_detais[0]['notouts'] : 0;
		$highscore   = (!empty($batsman_detais[0]['highscore'])) ? $batsman_detais[0]['highscore'] : 0;

		if(count($cricket_statistics)>0)
		{
			$average_bat='';
			if($totalruns>0 && $innings_bat>0)
			{
				$average_bat = $totalruns/$innings_bat; //total runs / innings bat
			}

			$strikerate='';
			if($totalballs>0)
			{
				$strikerate = ($totalruns/$totalballs)*100;//strikerate calculation [total runs/total ball*100]
			}
			CricketStatistic::where('user_id',$user_id)
				->where('match_type',$match_type)
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
		}
		else
		{
			$matchcount                 = (!empty($batsman_detais[0]['match_count'])) ? $batsman_detais[0]['match_count'] : 0;
			$innings_bat                = (!empty($batsman_detais[0]['inningscount'])) ? $batsman_detais[0]['inningscount'] : 0;
			$objStatistics              = new CricketStatistic();
			$objStatistics->user_id     = $user_id;
			$objStatistics->match_type  = $match_type;
			$objStatistics->matches     = $matchcount;
			$objStatistics->innings_bat = $innings_bat;
			$objStatistics->totalruns   = $totalruns;
			$objStatistics->totalballs  = $totalballs;
			$objStatistics->fours       = $fours;
			$objStatistics->sixes       = $sixes;
			$objStatistics->innings     = $inning;
			$objStatistics->fifties     = $fifties;
			$objStatistics->hundreds    = $hundreds;
			$objStatistics->notouts     = $notouts;
			$objStatistics->highscore   = $highscore;
			$strikerate                 = "";
			if ($totalballs > 0)
			{
				$strikerate             = ($totalruns/$totalballs)*100;//strikerate calculation [total runs/total ball*100]
			}
			$objStatistics->average_bat = $totalruns; //total runs / innings bat
			$objStatistics->strikerate  = $strikerate;
			$objStatistics->save();
		}
	}
	//cricket bowler statistics
	public function cricketBowlerStatistic($bowler_id,$match_type,$inning)
	{
		//check already record is exists or not
		$cricket_statistics_array = array();
		$bowler_cricket_statistics = CricketStatistic::select()->where('user_id',$bowler_id)->where('match_type',$match_type)->where('innings',$inning)->get();

		$bowler_detais = CricketPlayerMatchwiseStats::selectRaw('count(DISTINCT(match_id)) as match_count')->selectRaw('count(innings) as inningscount')->selectRaw('sum(wickets) as wickets')->selectRaw('sum(runs_conceded) as runs_conceded')->selectRaw('sum(overs_bowled) as overs_bowled')->where('user_id',$bowler_id)->where('match_type',$match_type)->where('innings',$inning)->groupBy('user_id')->get();

		$innings_bowl = (!empty($bowler_detais[0]['inningscount']))?$bowler_detais[0]['inningscount']:0;
		$wickets = (!empty($bowler_detais[0]['wickets']))?$bowler_detais[0]['wickets']:0;
		$runs_conceded = (!empty($bowler_detais[0]['runs_conceded']))?$bowler_detais[0]['runs_conceded']:0;
		$overs_bowled = (!empty($bowler_detais[0]['overs_bowled']))?$bowler_detais[0]['overs_bowled']:0;
		$match_count = (!empty($bowler_detais[0]['match_count']))?$bowler_detais[0]['match_count']:0;
		if(count($bowler_cricket_statistics)>0)
		{

			$ecomony  = '';
			if($overs_bowled>0)
			{
				$ecomony = $runs_conceded/$overs_bowled;
			}
			$average_bowl ='';
			if($wickets>0)
			{
				$average_bowl = $runs_conceded/$wickets;
			}
			CricketStatistic::where('user_id',$bowler_id)->where('match_type',$match_type)->update(['matches'=>$match_count,'innings_bowl'=>$innings_bowl,'wickets'=>$wickets,'runs_conceded'=>$runs_conceded,'overs_bowled'=>$overs_bowled,'ecomony'=>$ecomony,'average_bowl'=>$average_bowl]);
		}
		else
		{
			$matchcount = (!empty($bowler_detais[0]['match_count']))?$bowler_detais[0]['match_count']:0;;
			$innings_bowl = (!empty($bowler_detais[0]['inningscount']))?$bowler_detais[0]['inningscount']:0;;

			$objBowlerStatistics = new CricketStatistic();
			$objBowlerStatistics->user_id = $bowler_id;
			$objBowlerStatistics->match_type = $match_type;
			$objBowlerStatistics->matches = $matchcount;
			$objBowlerStatistics->innings_bowl = $innings_bowl;
			$objBowlerStatistics->wickets = $wickets;
			$objBowlerStatistics->runs_conceded = $runs_conceded;
			$objBowlerStatistics->overs_bowled = $overs_bowled;
			$objBowlerStatistics->innings = $inning;
			$ecomony='';
			if($overs_bowled>0)
			{
				$ecomony = $runs_conceded/$overs_bowled;//economy calculation [total runs/total overs]
			}
			$average_bowl ='';
			if($wickets>0)
			{
				$average_bowl = $runs_conceded/$wickets;//[total runs/total wickets]
			}
			$objBowlerStatistics->ecomony = $ecomony;
			$objBowlerStatistics->save();
		}
	}
	//function to get player names
	public function getplayers()
	{
		$player_a_ids = Request::get('player_a_ids');
		$team_a_playerids = explode(',',$player_a_ids);
		$a_team_players = User::select('id','name')->whereIn('id',$team_a_playerids)->get();

		if (count($a_team_players)>0)
			$players = $a_team_players->toArray();

		return Response::json(!empty($players) ? $players : []);
	}
	public function get_outas_enum()
	{
		$enum = config('constants.ENUM.SCORE_CARD.OUT_AS');
		return Response::json(!empty($enum ) ? $enum  : []);
	}
	//function to add players to team
	public function addPlayertoTeam()
	{
		$request = Request::all();
		$team_id = Request::get('team_id');
		$player_id = Request::get('response');
		$match_id = Request::get('match_id');
		$selected_team = Request::get('selected_team');

		if($team_id>0 && $player_id>0)
		{
			$role = 'player';
			$user_id = Request::get('response');
			$TeamPlayer = new TeamPlayers();
			$TeamPlayer->team_id = $team_id;
			$TeamPlayer->user_id = $player_id;
			$TeamPlayer->role = $role;
			if($TeamPlayer->save())
			{
				if($selected_team=='a')//if team a selected
				{
					$get_a_player_ids = MatchSchedule::where('id',$match_id)->where('a_id',$team_id)->pluck('player_a_ids');//get team a players
					$get_a_player_ids = $get_a_player_ids.$player_id.',';
					MatchSchedule::where('id',$match_id)->where('a_id',$team_id)->update(['player_a_ids'=>$get_a_player_ids]);
				}else
				{
					$get_b_player_ids = MatchSchedule::where('id',$match_id)->where('b_id',$team_id)->pluck('player_b_ids');//get team a players
					$get_b_player_ids = $get_b_player_ids.$player_id.',';
					MatchSchedule::where('id',$match_id)->where('b_id',$team_id)->update(['player_b_ids'=>$get_b_player_ids]);
				}

			}
			return Response()->json( array('success' => trans('message.sports.teamplayer')) );
		}else
		{
			return Response()->json( array('failure' => trans('message.sports.teamvalidation')) );
		}
	}
	// function to insert soccer score card
	public function soccerScoreCard($match_data,$sportsDetails=[],$tournamentDetails=[],$is_from_view=0)
	{
		$loginUserId = '';
		$loginUserRole = '';

		if(isset(Auth::user()->id))
			$loginUserId = Auth::user()->id;

		if(isset(Auth::user()->role))
			$loginUserRole = Auth::user()->role;

		$team_a_players = array();
		$team_b_players = array();
		$team_a_id = $match_data[0]['a_id'];
		$team_b_id = $match_data[0]['b_id'];
		$team_a_playerids = explode(',',$match_data[0]['player_a_ids']);
		$team_b_playerids = explode(',',$match_data[0]['player_b_ids']);

		//get match id
		$match_id=$match_data[0]['id'];

		//get soccer scores for team a
		$team_a_soccer_scores = SoccerPlayerMatchwiseStats::select()->where('match_id',$match_data[0]['id'])->where('team_id',$team_a_id)->get();

		$team_a_soccer_scores_array = array();
		if(count($team_a_soccer_scores)>0)
		{
			$team_a_soccer_scores_array = $team_a_soccer_scores->toArray();
		}

		//get soccer scores for team b
		$team_b_soccer_scores = SoccerPlayerMatchwiseStats::select()->where('match_id',$match_data[0]['id'])->where('team_id',$team_b_id)->get();
		$team_b_soccer_scores_array = array();
		if(count($team_b_soccer_scores)>0)
		{
			$team_b_soccer_scores_array = $team_b_soccer_scores->toArray();
		}

		//get player names
		$a_team_players = User::select()->whereIn('id',$team_a_playerids)->get();
		$b_team_players = User::select()->whereIn('id',$team_b_playerids)->get();

		//get players statistics
		$team_a_players_stat=SoccerPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_a_id)->get();
		$team_b_players_stat=SoccerPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_b_id)->get();

		//get team names
		$team_a_name = Team::where('id',$team_a_id)->pluck('name');
		$team_b_name = Team::where('id',$team_b_id)->pluck('name');

		if(!empty($a_team_players))
			$team_a_players = $a_team_players->toArray();
		if(!empty($b_team_players))
			$team_b_players = $b_team_players->toArray();
		$team_a = array();
		$team_b = array();

		//get team a players
		foreach($team_a_players as $team_a_player)
		{
			$team_a[$team_a_player['id']] = $team_a_player['name'];
		}

		//get team b players
		foreach($team_b_players as $team_b_player)
		{
			$team_b[$team_b_player['id']] = $team_b_player['name'];
		}

		$team_a_logo = Photo::select()->where('imageable_id', $match_data[0]['a_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
		$team_b_logo = Photo::select()->where('imageable_id', $match_data[0]['b_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo



		//bye match
		if($match_data[0]['b_id']=='' && $match_data[0]['match_status']=='completed')
		{
			$sport_class = 'soccer_scorecard ss_bg';
			$upload_folder = 'teams';
			return view('scorecards.byematchview',array('team_a_name'=>$team_a_name,'team_a_logo'=>$team_a_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'sport_class'=>$sport_class));
		}

		$team_a_count = count($team_a);
		$team_b_count = count($team_b);



		//get match details fall of wickets
		$team_wise_score_details = array();
		$match_details = $match_data[0]['match_details'];
		if($match_details!='' && $match_details!=NULL)
		{
			$json_decode_array = json_decode($match_details,true);
			foreach($json_decode_array as $key => $array)
			{
				$team_wise_score_details[$key] = $array;
			}
		}
		$team_a_goals=0;
		$team_b_goals=0;

		$team_a_red_count = 0;
		$team_b_red_count = 0;
		$team_a_yellow_count = 0;
		$team_b_yellow_count = 0;
		//team a goals
		if(!empty($team_wise_score_details[$match_data[0]['a_id']]['goals']) && $team_wise_score_details[$match_data[0]['a_id']]['goals']!=null)
		{
			$team_a_goals = $team_wise_score_details[$match_data[0]['a_id']]['goals'];

		}
		if(!empty($team_wise_score_details[$match_data[0]['a_id']]['red_card_count']) && $team_wise_score_details[$match_data[0]['a_id']]['red_card_count']!=null)
		{
			$team_a_red_count = $team_wise_score_details[$match_data[0]['a_id']]['red_card_count'];

		}
		if(!empty($team_wise_score_details[$match_data[0]['a_id']]['yellow_card_count']) && $team_wise_score_details[$match_data[0]['a_id']]['yellow_card_count']!=null)
		{
			$team_a_yellow_count = $team_wise_score_details[$match_data[0]['a_id']]['yellow_card_count'];

		}


		//team b goals
		if(!empty($team_wise_score_details[$match_data[0]['b_id']]['goals']) && $team_wise_score_details[$match_data[0]['b_id']]['goals']!=null)
		{
			$team_b_goals = $team_wise_score_details[$match_data[0]['b_id']]['goals'];
		}
		if(!empty($team_wise_score_details[$match_data[0]['b_id']]['red_card_count']) && $team_wise_score_details[$match_data[0]['b_id']]['red_card_count']!=null)
		{
			$team_b_red_count = $team_wise_score_details[$match_data[0]['b_id']]['red_card_count'];
		}
		if(!empty($team_wise_score_details[$match_data[0]['b_id']]['yellow_card_count']) && $team_wise_score_details[$match_data[0]['b_id']]['yellow_card_count']!=null)
		{
			$team_b_yellow_count = $team_wise_score_details[$match_data[0]['b_id']]['yellow_card_count'];
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
		$rej_note_str = trim($rej_note_str, ",");

		//is valid user for score card enter or edit
		$isValidUser = 0;
		$isApproveRejectExist = 0;
		$isForApprovalExist = 0;
		if(isset(Auth::user()->id)){
			$isValidUser = Helper::isValidUserForScoreEnter($match_data);
			//is approval process exist
			$isApproveRejectExist = Helper::isApprovalExist($match_data);
			$isForApprovalExist = Helper::isApprovalExist($match_data,$isForApproval='yes');
		}

		$team_a_city = Helper::getTeamCity($match_data[0]['a_id']);
		$team_b_city = Helper::getTeamCity($match_data[0]['b_id']);
		$form_id = 'soccer';

		if($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser)//soccer score view only
		{
			$player_name_array = array();
			$users = User::select('id', 'name')->get()->toArray(); //get player names
			foreach ($users as $user) {
				$player_name_array[$user['id']] = $user['name']; //get team names
			}
			$player_of_the_match=$match_data[0]['player_of_the_match'];
			if($player_of_the_match_model=User::find($player_of_the_match))$player_of_the_match=$player_of_the_match_model;
			else $player_of_the_match=NULL;
			return view('scorecards.soccerscorecardview',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'team_a_soccer_scores_array'=>$team_a_soccer_scores_array,'team_b_soccer_scores_array'=>$team_b_soccer_scores_array,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'team_a_goals'=>$team_a_goals,'team_b_goals'=>$team_b_goals,'player_name_array'=> $player_name_array,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'team_a_red_count'=>$team_a_red_count,'team_a_yellow_count'=>$team_a_yellow_count,'team_b_red_count'=>$team_b_red_count,'team_b_yellow_count'=>$team_b_yellow_count,'form_id'=>$form_id,'team_a_players'=>$team_a_players, 'team_b_players'=>$team_b_players, 'player_of_the_match'=>$player_of_the_match));
		}else //soccer score view and edit
		{
			return view('scorecards.soccerscorecard',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'team_a_soccer_scores_array'=>$team_a_soccer_scores_array,'team_b_soccer_scores_array'=>$team_b_soccer_scores_array,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'team_a_goals'=>$team_a_goals,'team_b_goals'=>$team_b_goals,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'team_a_red_count'=>$team_a_red_count,'team_a_yellow_count'=>$team_a_yellow_count,'team_b_red_count'=>$team_b_red_count,'team_b_yellow_count'=>$team_b_yellow_count,'form_id'=>$form_id, 'team_a_players'=>$team_a_players, 'team_b_players'=>$team_b_players));
		}

	}
	//function to save soccer score card
	public function insertSoccerScoreCard()
	{
		$request = Request::all();

		$team_a_count = Request::get('team_a_count');
		$team_b_count = Request::get('team_b_count');
		$tournament_id = !empty(Request::get('tournament_id'))?Request::get('tournament_id'):NULL;
		$match_id = !empty(Request::get('match_id'))?Request::get('match_id'):NULL;
		$team_a_id = !empty(Request::get('team_a_id'))?Request::get('team_a_id'):NULL;
		$team_b_id = !empty(Request::get('team_b_id'))?Request::get('team_b_id'):NULL;
		$team_a_name = !empty(Request::get('team_a_name'))?Request::get('team_a_name'):NULL;
		$team_b_name = !empty(Request::get('team_b_name'))?Request::get('team_b_name'):NULL;
		$winner_team_id = !empty(Request::get('winner_team_id'))?Request::get('winner_team_id'):NULL;//winner_id

		$match_result = !empty(Request::get('match_result'))?Request::get('match_result'):NULL; // match result win or tie
		$team_a_goal_count = 0;
		$team_a_yellow_card_count = 0;
		$team_a_red_card_count = 0;
		$team_b_yellow_card_count = 0;
		$team_b_red_card_count = 0;

		//delete team players
		$delete_ids = !empty(Request::get('delted_ids'))?Request::get('delted_ids'):NULL;
		$deleted_id_array=array();
		if($delete_ids!='')
		{
			$deletedIds = trim($delete_ids,',');
			$deleted_id_array = explode(',',$deletedIds);
		}

		if(count($deleted_id_array)>0) //delete selected players
		{
			foreach($deleted_id_array as $primary_id)
			{
				SoccerPlayerMatchwiseStats::find($primary_id)->delete();//delete player id
			}

		}

		for($i=1;$i<=$team_a_count;$i++)//insert team a player goals
		{
			$user_id_a = !empty(Request::get('a_player_'.$i))?Request::get('a_player_'.$i):0;

			if($user_id_a>0)
			{
				$a_player_name = User::where('id',$user_id_a)->pluck('name');
				$a_yellow_card = !empty(Request::get('a_yellow_card_'.$i))?Request::get('a_yellow_card_'.$i):NULL;
				$a_red_card = !empty(Request::get('a_red_card_'.$i))?Request::get('a_red_card_'.$i):NULL;
				$a_goal = !empty(Request::get('a_goal_'.$i))?Request::get('a_goal_'.$i):NULL;

				$hid_player_id = !empty(Request::get('hid_a_player_'.$i))?Request::get('hid_a_player_'.$i):0;
				$a_primary_id = !empty(Request::get('hid_a_primary_id_'.$i))?Request::get('hid_a_primary_id_'.$i):0;
				if($hid_player_id>0 && $hid_player_id!=$user_id_a && $a_primary_id>0) // if prev player and current player is not same delete old player
				{
					SoccerPlayerMatchwiseStats::find($a_primary_id)->delete();//delete player id
				}

				//check already score is entered or not
				$a_is_score_exist = $this->isScoreEntered($user_id_a,$match_id,$team_a_id);

				if(!$a_is_score_exist)
				{
					//save soccer score card details
					$this->insertSoccerScore($user_id_a,$tournament_id,$match_id,$team_a_id,$a_player_name,$team_a_name,$a_yellow_card,$a_red_card,$a_goal);

				}else
				{
					//update scores if already exist
					$this->updateSoccerScore($user_id_a,$match_id,$team_a_id,$a_player_name,$a_yellow_card,$a_red_card,$a_goal);
				}


				$team_a_goal_count = $team_a_goal_count+$a_goal;//to calculate team goal count by adding individual player goals

				$team_a_yellow_card_count = $team_a_yellow_card_count+$a_yellow_card;
				$team_a_red_card_count = $team_a_red_card_count+$a_red_card;
			}
		}
		$team_b_goal_count =0;
		for($i=1;$i<=$team_b_count;$i++)//insert team b player goals
		{
			$user_id_b = Request::get('b_player_'.$i);

			if($user_id_b>0)
			{
				$b_player_name = User::where('id',$user_id_b)->pluck('name');
				$b_yellow_card = !empty(Request::get('b_yellow_card_'.$i))?Request::get('b_yellow_card_'.$i):NULL;
				$b_red_card = !empty(Request::get('b_red_card_'.$i))?Request::get('b_red_card_'.$i):NULL;
				$b_goal = !empty(Request::get('b_goal_'.$i))?Request::get('b_goal_'.$i):NULL;

				$hid_b_player_id = !empty(Request::get('hid_b_player_'.$i))?Request::get('hid_b_player_'.$i):0;
				$b_primary_id = !empty(Request::get('hid_b_primary_id_'.$i))?Request::get('hid_b_primary_id_'.$i):0;
				if($hid_b_player_id>0 && $hid_b_player_id!=$user_id_b && $b_primary_id>0) // if prev player and current player is not same delete old player
				{
					SoccerPlayerMatchwiseStats::find($b_primary_id)->delete();//delete player id
				}

				//check already score is entered or not
				$b_is_score_exist = $this->isScoreEntered($user_id_b,$match_id,$team_b_id);

				if(!$b_is_score_exist)
				{
					//save soccer score card details
					$this->insertSoccerScore($user_id_b,$tournament_id,$match_id,$team_b_id,$b_player_name,$team_b_name,$b_yellow_card,$b_red_card,$b_goal);
				}
				else
				{
					//update scores if already exist
					$this->updateSoccerScore($user_id_b,$match_id,$team_b_id,$b_player_name,$b_yellow_card,$b_red_card,$b_goal);
				}

				$team_b_goal_count = $team_b_goal_count+$b_goal;//to calculate team goal count by adding individual player goals
				$team_b_yellow_card_count = $team_b_yellow_card_count+$b_yellow_card;
				$team_b_red_card_count = $team_b_red_card_count+$b_red_card;
			}
		}

		//insert team goals in json format
		$team_a_score[$team_a_id] = array('goals'=>$team_a_goal_count,'red_card_count'=>$team_a_red_card_count,'yellow_card_count'=>$team_a_yellow_card_count); //team a goals
		$team_b_score[$team_b_id] = array('goals'=>$team_b_goal_count,'red_card_count'=>$team_b_red_card_count,'yellow_card_count'=>$team_b_yellow_card_count);//team b goals
		//$team_b_score['first_half']=array('')


		$two_teams_scores = array_replace_recursive($team_a_score,$team_b_score);// merge two teams goals


		$json_match_score_array = json_encode($two_teams_scores); //convert json format


		//get previous scorecard status data
		$loginUserId = Auth::user()->id;
		$scorecardDetails = MatchSchedule::where('id',$match_id)->pluck('score_added_by');
		$decode_scorecard_data = json_decode($scorecardDetails,true);

		$modified_users = !empty($decode_scorecard_data['modified_users'])?$decode_scorecard_data['modified_users']:'';

		$modified_users = $modified_users.','.$loginUserId;//scorecard changed users

		$added_by = !empty($decode_scorecard_data['added_by'])?$decode_scorecard_data['added_by']:$loginUserId;

		//score card approval process
		$score_status = array('added_by'=>$added_by,'active_user'=>$loginUserId,'modified_users'=>$modified_users,'rejected_note'=>'');

		$json_score_status = json_encode($score_status);

		$is_tied = 0;
		if($match_result=='tie')
			$is_tied = 1;

		//update winner id in match schedule table
		$matchScheduleDetails = MatchSchedule::where('id',$match_id)->first();
		if(count($matchScheduleDetails)) {
			$looser_team_id = NULL;
			$match_status='scheduled';
			$approved = '';
			if($is_tied==0) {

				if(isset($winner_team_id)) {
					if($winner_team_id==$matchScheduleDetails['a_id']) {
						$looser_team_id=$matchScheduleDetails['b_id'];
					}else{
						$looser_team_id=$matchScheduleDetails['a_id'];
					}
					$match_status='completed';
					$approved = 'approved';
				}

			}

			if(!empty($matchScheduleDetails['tournament_id'])) {
//                        dd($winner_team_id.'<>'.$looser_team_id);
				$tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
				if($is_tied==1 && !empty($matchScheduleDetails['tournament_group_id'])) {
					$match_status = 'completed';
				}
				if(Helper::isTournamentOwner($tournamentDetails['manager_id'],$tournamentDetails['tournament_parent_id'])) {
					MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_score_array,'match_status'=>$match_status,
						'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
						'is_tied'=>$is_tied,'score_added_by'=>$json_score_status]);
//                                Helper::printQueries();

					if(!empty($matchScheduleDetails['tournament_round_number'])) {
						$this->updateBracketDetails($matchScheduleDetails,$tournamentDetails,$winner_team_id);
					}
					if($match_status=='completed')
					{
						$sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');
						$this->insertPlayerStatistics($sportName,$match_id);

						//notification ocde

					}

				}

			}else if(Auth::user()->role=='admin'){
				if($is_tied==1) {
					$match_status = 'completed';
					$approved = 'approved';
				}
				MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_score_array,'match_status'=>$match_status,
					'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
					'is_tied'=>$is_tied,'score_added_by'=>$json_score_status,'scoring_status'=>$approved]);

				if($match_status=='completed')
				{
					$sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');
					$this->insertPlayerStatistics($sportName,$match_id);

					//notification ocde

				}
			}else
			{
				MatchSchedule::where('id',$match_id)->update(['match_details'=>$json_match_score_array,'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
					'is_tied'=>$is_tied,'score_added_by'=>$json_score_status]);
			}
		}
		//MatchSchedule::where('id',$match_id)->update(['winner_id'=>$winner_team_id,'match_details'=>$json_match_score_array,'is_tied'=>$is_tied,'score_added_by'=>$json_score_status]);

		//if($match_result!='')
		//return redirect()->route('match/scorecard/view', [$match_id])->with('status', trans('message.scorecard.scorecardmsg'));
		return redirect()->back()->with('status',trans('message.scorecard.scorecardmsg') );
	}


	public function insertSoccerScore($user_id,$tournament_id,$match_id,$team_id,$player_name,$team_name,$yellow_card_count,$red_card_count,$goal_count, $playing_status='S')
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

	public function insertAndUpdateSoccerScoreCard(){

		$request=Request::all();

		$match_id=$request['match_id'];
		$first_half=isset($request['first_half'])?$request['first_half']:[];
		$second_half=isset($request['second_half'])?$request['second_half']:[];
		$team_a_id=$request['team_a_id'];
		$team_b_id=$request['team_b_id'];
		$last_index=$request['last_index'];
		$match_data=matchSchedule::find($match_id);
		$match_details=$match_data['match_details'];
		$soccer_player=SoccerPlayerMatchwiseStats::whereMatchId($match_id)->first();
		$delted_ids=$request['delted_ids'];
		$match_result=$request['match_result'];
		$winner_team_id = !empty(Request::get('winner_team_id'))?Request::get('winner_team_id'):NULL;//winner_id
		$player_of_the_match=isset($request['player_of_the_match'])?$request['player_of_the_match']:NULL;

		$match_data->player_of_the_match=$player_of_the_match;

		$deleted_ids=explode(',',$delted_ids);


		if(empty($match_details) || !isset(json_decode($match_details)->first_half)){
			$match_details=[
				"{$team_a_id}"	=>	[
					"id"=>$team_a_id,
					"goals"=>0,
					"red_card_count"=>0,
					"yellow_card_count"=>0,
					"ball_percentage"=>0],
				"{$team_b_id}"=>[
					"id"=>$team_b_id,
					"goals"=>0,
					"red_card_count"=>0,
					"yellow_card_count"=>0,
					"ball_percentage"=>0],
				"first_half"=>[
					"goals"=>0,
					"team_{$team_a_id}_goals"=>0,
					"team_{$team_b_id}_goals"=>0,
					"team_{$team_a_id}_yellow_card_count"=>0,
					"team_{$team_b_id}_yellow_card_count"=>0,
					"team_{$team_a_id}_red_card_count"=>0,
					"team_{$team_b_id}_red_card_count"=>0,
					"red_card_count"=>0,
					"yellow_card_count"=>0,
					"goals_details"=>[],
					"red_card_details"=>[],
					"yellow_card_details"=>[]
				],
				"second_half"=>[
					"goals"=>0,
					"team_{$team_a_id}_goals"=>0,
					"team_{$team_b_id}_goals"=>0,
					"team_{$team_a_id}_yellow_card_count"=>0,
					"team_{$team_b_id}_yellow_card_count"=>0,
					"team_{$team_a_id}_red_card_count"=>0,
					"team_{$team_b_id}_red_card_count"=>0,
					"red_card_count"=>0,
					"yellow_card_count"=>0,
					"goals_details"=>[],
					"red_card_details"=>[],
					"yellow_card_details"=>[]
				],
				"penalties"=>[
					'score'=>'',
					'team_a'=>[
						'players'=>[],
						'goals'=>0,
					],
					'team_b'=>[
						'players'=>[],
						'goals'=>0,
					]
				]
			];
			$match_details=json_decode(json_encode($match_details));
		}
		else $match_details=json_decode($match_details);

		//set ball percentage statistics
		$match_details->{$team_a_id}->ball_percentage=$request['ball_percentage_'.$team_a_id];
		$match_details->{$team_b_id}->ball_percentage=$request['ball_percentage_'.$team_b_id];

		$match_data->match_details=json_encode($match_details);
		$match_data->save();




		//get previous scorecard status data
		$loginUserId = Auth::user()->id;
		$scorecardDetails = MatchSchedule::where('id',$match_id)->pluck('score_added_by');
		$decode_scorecard_data = json_decode($scorecardDetails,true);

		$modified_users = !empty($decode_scorecard_data['modified_users'])?$decode_scorecard_data['modified_users']:'';

		$modified_users = $modified_users.','.$loginUserId;//scorecard changed users

		$added_by = !empty($decode_scorecard_data['added_by'])?$decode_scorecard_data['added_by']:$loginUserId;

		//score card approval process
		$score_status = array('added_by'=>$added_by,'active_user'=>$loginUserId,'modified_users'=>$modified_users,'rejected_note'=>'');

		$json_score_status = json_encode($score_status);


		$is_tie         = ($match_result == 'tie')      ? 1 : 0;
         $is_washout     = ($match_result == 'washout')  ? 1 : 0;
         $has_result     = ($is_washout == 1) ? 0 : 1;
         $match_result   = ( !in_array( $match_result, ['tie','win','washout'] ) ) ? NULL : $match_result;
		$matchScheduleDetails = MatchSchedule::where('id',$match_id)->first();
		if(count($matchScheduleDetails)) {
			$looser_team_id = NULL;
			$match_status='scheduled';
			$approved = '';
			if($is_tie==0 || $is_washout == 0 ) {

				if(isset($winner_team_id)) {
					//$match_details=(object)$match_details;
					// if($match_details->{$team_a_id}->goals>$match_details->{$team_b_id}->goals){
					// 	 $looser_team_id=$matchScheduleDetails['b_id'];
					//    }else{
					//        $looser_team_id=$matchScheduleDetails['a_id'];
					//    }
					if($winner_team_id==$matchScheduleDetails['a_id']) {
						$looser_team_id=$matchScheduleDetails['b_id'];
					}else{
						$looser_team_id=$matchScheduleDetails['a_id'];
					}

					$match_status='completed';
					$approved = 'approved';
				}

			}

			if(!empty($matchScheduleDetails['tournament_id'])) {
//                        dd($winner_team_id.'<>'.$looser_team_id);
				$tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
			if (($is_tie == 1 || $match_result == "washout") && !empty($matchScheduleDetails['tournament_group_id'])){
					$match_status = 'completed';
				}
				
				if(Helper::isTournamentOwner($tournamentDetails['manager_id'],$tournamentDetails['tournament_parent_id'])) 
				{
					MatchSchedule::where('id',$match_id)->update(['match_status'=>$match_status,
						'winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
						'is_tied'=>$is_tie,
						 'has_result'     => $has_result,
                         'match_result'   => $match_result,
                         'score_added_by'=>$json_score_status]);
//                                Helper::printQueries();

					if(!empty($matchScheduleDetails['tournament_round_number'])) {
						$this->updateBracketDetails($matchScheduleDetails,$tournamentDetails,$winner_team_id);
					}
					if($match_status=='completed')
					{
						$sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');

					if($match_data->has_result==0){
						$match_data->match_details=null;
						$match_data->save();
						$players_stats=	SoccerPlayerMatchwiseStat::whereMatchId($match_id)->get();
						$this->discardMatchRecords($players_stats);				
					}

						$this->insertPlayerStatistics($sportName,$match_id);

						//notification ocde
						Helper::sendEmailPlayers($matchScheduleDetails, 'Soccer');		
					}

				}

			}else if(Auth::user()->role=='admin'){
				if ($is_tie == 1 || $match_result == "washout") {
					$match_status = 'completed';
					$approved = 'approved';
				}
				MatchSchedule::where('id',$match_id)->update(['match_status'=>$match_status,
					'winner_id'      => $winner_team_id,
                     'looser_id'      => $looser_team_id,
 					'is_tied'        => $is_tie,
                     'has_result'     => $has_result,
                     'match_result'   => $match_result,
                     'score_added_by' => $json_score_status,
                     'scoring_status'=>$approved]);

				if($match_status=='completed')
				{
					$sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');
					if($match_data->has_result==0){
						$match_data->match_details=null;
						$match_data->save();
						$players_stats=	SoccerPlayerMatchwiseStat::whereMatchId($match_id)->get();
						$this->discardMatchRecords($players_stats);				
					}
					$this->insertPlayerStatistics($sportName,$match_id);

					//notification ocde
					Helper::sendEmailPlayers($matchScheduleDetails, 'Soccer');	
				}				 
                    
			}
			else
			{
				MatchSchedule::where('id',$match_id)->update(['winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
					'is_tied'=>$is_tie,
					'has_result'     => $has_result,
                    'match_result'   => $match_result,
                     'score_added_by'=>$json_score_status]);
			}
		}
		
		return $match_data->match_details;
	}

	public function soccerStoreRecord(){
			$request=Request::all();
			$match_id=$request['match_id'];
		$first_half=isset($request['first_half'])?$request['first_half']:[];
		$second_half=isset($request['second_half'])?$request['second_half']:[];
		$team_a_id=$request['team_a_id'];
		$team_b_id=$request['team_b_id'];
		$i=$request['index'];
		$match_data=matchSchedule::find($match_id);
		$match_details=json_decode($match_data['match_details']);
		$soccer_player=SoccerPlayerMatchwiseStats::whereMatchId($match_id)->first();

		$match_details=(array)$match_details;

				$half_time=$request['half_time_'.$i];
				$team_id=$request['team_'.$i];
				$player_stat_id=$request['player_'.$i];
				$user_id=$request['user_'.$i];
				$record_type=$request['record_type_'.$i];
				$time=$request['time_'.$i];
				$player_name=$request['player_name_'.$i];
				$team_type=$request['team_type_'.$i];

				$record_type_count=$record_type=='goals'?$record_type:$record_type.'_count';


				$match_details[$half_time]=(array)$match_details[$half_time];
				$match_details[$half_time][$record_type.'_details']=(array)$match_details[$half_time][$record_type.'_details'];


				$soccer_model=SoccerPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_id)->whereUserId($user_id)->first();
				$goals_count=$soccer_model['goals_scored'];
				$yellow_card_count=$soccer_model['yellow_cards'];
				$red_card_count=$soccer_model['red_cards'];

				${$record_type.'_count'}++;
				$match_details=(object)$match_details;		//temporally convert to object to get numeric property
				$match_details->$team_id->{$record_type_count} +=1;
				$score=$match_details->{$team_a_id}->goals. '-'. $match_details->{$team_b_id}->goals;

				$match_details=(array)$match_details;
				$record_type_details=[
					'player_id'=>$user_id,
					'player_name'=>$player_name,
					'time'=>$time,
					'team_id'=>$team_id,
					'current_score'=>$score,
					'number'=>1,
					'team_type'=>$team_type,

				];
				//if players has 2 yellow cards -> 1 red card
				if($record_type_count=='yellow_card_count'){
					if($yellow_card_count>0 && $red_card_count==0){
						$soccer_model->red_cards=1;
					}
				}

				$match_details[$half_time][$record_type_count]+=1;
				$match_details[$half_time]['team_'.$team_id.'_'.$record_type_count]+=1;		//increments value for goal or yellow card or red card for team in specified halftime

				array_push($match_details[$half_time][$record_type.'_details'], $record_type_details);

				$soccer_model->save();

				$this->updateSoccerScore($user_id,$match_id,$team_id,$player_name,$yellow_card_count,$red_card_count,$goals_count);
				$this->soccerStatistics($user_id);			


		$match_data->match_details=json_encode($match_details);
		$match_data->save();
			return $match_data->match_details;

	}

	public function getSoccerDetails(){
		$request=Request::all();
		$match_id=$request['match_id'];
		$team_a_id=$request['team_a_id'];
		$team_b_id=$request['team_b_id'];
		$match_details=matchSchedule::find($match_id)->match_details;
		$view=(string)view('scorecards.soccerscorecarddetails', compact('match_details','team_a_id', 'team_b_id'));
		
		return [
		'json'=>$match_details,
		'html'=>$view
		];
	}


	//function to update player scores if already exist
	public function updateSoccerScore($user_id,$match_id,$team_id,$player_name,$yellow_card_count,$red_card_count,$goal_count, $goals_details=[])
	{
		$player_stat=SoccerPlayerMatchwiseStats::where('user_id',$user_id)->where('match_id',$match_id)->where('team_id',$team_id)->update(['user_id'=>$user_id,'player_name'=>$player_name,'yellow_cards'=>$yellow_card_count,'red_cards'=>$red_card_count,'goals_scored'=>$goal_count]);
		//SoccerStatistic::where('user_id',$user_id)->update(['yellow_cards'=>$yellow_card_count,'red_cards'=>$red_card_count,'goals_scored'=>$goal_count]);
	}
	//soccer statistics function player wise
	public function soccerStatistics($user_id)
	{
		//check already player has record or not
		$user_soccer_details = SoccerStatistic::select()->where('user_id',$user_id)->get();

		$soccer_details = SoccerPlayerMatchwiseStats::selectRaw('count(match_id) as match_count')->selectRaw('sum(yellow_cards) as yellow_cards')->selectRaw('sum(red_cards) as red_cards')->selectRaw('sum(goals_scored) as goals_scored')->where('user_id',$user_id)->groupBy('user_id')->get();
		$yellow_card_cnt = (!empty($soccer_details[0]['yellow_cards']))?$soccer_details[0]['yellow_cards']:0;
		$red_card_cnt = (!empty($soccer_details[0]['red_cards']))?$soccer_details[0]['red_cards']:0;
		$goals_cnt = (!empty($soccer_details[0]['goals_scored']))?$soccer_details[0]['goals_scored']:0;
		if(count($user_soccer_details)>0)
		{
			$match_count = (!empty($soccer_details[0]['match_count']))?$soccer_details[0]['match_count']:0;
			SoccerStatistic::where('user_id',$user_id)->update(['matches'=>$match_count,'yellow_cards'=>$yellow_card_cnt,'red_cards'=>$red_card_cnt,'goals_scored'=>$goals_cnt]);
		}else
		{
			$soccer_statistics = new SoccerStatistic();
			$soccer_statistics->user_id = $user_id;
			$soccer_statistics->matches = 1;
			$soccer_statistics->yellow_cards = $yellow_card_cnt;
			$soccer_statistics->red_cards = $red_card_cnt;
			$soccer_statistics->goals_scored = $goals_cnt;
			$soccer_statistics->save();
		}
	}
	//check is score enter for match
	public function isScoreEntered($user_id,$match_id,$team_id)
	{
		$request_array = SoccerPlayerMatchwiseStats::where('user_id',$user_id)->where('match_id',$match_id)->where('team_id',$team_id)->first();
		if(count($request_array)>0)
		{
			return 1;
		}
		else
		{
			return 0;
		}
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

			$tournamentDetails = [];
			if (!empty($match_data[0]['tournament_id']))
			{
				$tournamentDetails = Tournaments::where('id', '=', $match_data[0]['tournament_id'])->first();
			}

			if(!empty($sportsDetails))
			{
				$sport_name = $sportsDetails[0]['sports_name'];
				if(strtolower($sport_name)==strtolower('Tennis'))//if match is related to tennis
				{
					return  $this->tennisOrTableTennisScoreCard($match_data,$match='Tennis',$sportsDetails,$tournamentDetails,$is_from_view=1);
				}else if(strtolower($sport_name)==strtolower('Table Tennis'))//if match is related to table tennis
				{
					return $this->tennisOrTableTennisScoreCard($match_data,$match='Table Tennis',$sportsDetails,$tournamentDetails,$is_from_view=1);
				}else if(strtolower($sport_name)==strtolower('Cricket'))
				{
					return $this->cricketScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
				else if(strtolower($sport_name)==strtolower('soccer'))
				{
					return $this->soccerScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
				else if(strtolower($sport_name)==strtolower('hockey'))
				{
					$hockey = new ScoreCard\HockeyScorecardController;
					return $hockey->soccerScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
				else if(strtolower($sport_name)==strtolower('badminton'))
				{
					$badminton = new ScoreCard\BadmintonScoreCardController;
					return $badminton->badmintonScoreCard($match_data,[],$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
				else if(strtolower($sport_name)==strtolower('squash'))
				{
					$squash = new ScoreCard\SquashScoreCardController;
					return $squash->squashScoreCard($match_data,[],$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
				// else if(strtolower($sport_name)==strtolower('volleyball'))
				// {
				// 	$squash = new ScoreCard\VolleyballScoreCardController;
				// 	return $squash->volleyballScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				// }
				else if(strtolower($sport_name)==strtolower('basketball'))
				{
					$squash = new ScoreCard\BasketballScoreCardController;
					return $squash->basketballScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
			}
		}
	}

	public function createScorecardpublicView($match_id)
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

			$tournamentDetails = [];
			if (!empty($match_data[0]['tournament_id']))
			{
				$tournamentDetails = Tournaments::where('id', '=', $match_data[0]['tournament_id'])->first();
			}

			if(!empty($sportsDetails))
			{
				$sport_name = $sportsDetails[0]['sports_name'];
				if(strtolower($sport_name)==strtolower('Tennis'))//if match is related to tennis
				{
					return  $this->tennisOrTableTennisScoreCard($match_data,$match='Tennis',$sportsDetails,$tournamentDetails,$is_from_view=1);
				}else if(strtolower($sport_name)==strtolower('Table Tennis'))//if match is related to table tennis
				{
					return $this->tennisOrTableTennisScoreCard($match_data,$match='Table Tennis',$sportsDetails,$tournamentDetails,$is_from_view=1);
				}else if(strtolower($sport_name)==strtolower('Cricket'))
				{
					return $this->cricketScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
				else if(strtolower($sport_name)==strtolower('soccer'))
				{
					return $this->soccerScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				}

				else if(strtolower($sport_name)==strtolower('hockey'))
				{
					$hockey = new ScoreCard\HockeyScorecardController;
					return $hockey->soccerScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
				else if(strtolower($sport_name)==strtolower('badminton'))
				{
					$badminton = new ScoreCard\BadmintonScoreCardController;
					return $badminton->badmintonScoreCard($match_data,[],$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
				else if(strtolower($sport_name)==strtolower('squash'))
				{
					$squash = new ScoreCard\SquashScoreCardController;
					return $squash->squashScoreCard($match_data,[],$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
				// else if(strtolower($sport_name)==strtolower('volleyball'))
				// {
				// 	$squash = new ScoreCard\VolleyballScoreCardController;
				// 	return $squash->volleyballScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				// }
				else if(strtolower($sport_name)==strtolower('basketball'))
				{
					$squash = new ScoreCard\BasketballScoreCardController;
					return $squash->basketballScoreCard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=1);
				}
			}
		}
	}
	//function to update score card status
	public function scoreCardStatus()
	{
		$status = Request::get('scorecard_status');//status
		$match_id = Request::get('match_id');
		$rej_note = !empty(Request::get('rej_note'))?Request::get('rej_note'):'';
		$sport_name = !empty(Request::get('sport_name'))?Request::get('sport_name'):'';
		$loginUserId = Auth::user()->id;

		$loginUserName = Auth::user()->name;

		$matchDetails=MatchSchedule::find($match_id);
		$sportId=$matchDetails->sports_id;
		$sportDetails=Sport::find($sportId);
		$sportName=$sportDetails->name;

		//get previous scorecard status data
		$scorecardDetails = MatchSchedule::where('id',$match_id)->pluck('score_added_by');
		$decode_scorecard_data = json_decode($scorecardDetails,true);

		$modified_users = !empty($decode_scorecard_data['modified_users'])?$decode_scorecard_data['modified_users']:'';

		$modified_users = $modified_users.','.$loginUserId;//scorecard changed users

		$added_by = !empty($decode_scorecard_data['added_by'])?$decode_scorecard_data['added_by']:$loginUserId;

		$rejected_note = !empty($decode_scorecard_data['rejected_note'])?$decode_scorecard_data['rejected_note']:'';

		if($rej_note!='' && $status == 'rejected')
			$rejected_note = $rejected_note.'@'.$rej_note;

		//score card approval process
		$score_status = array('added_by'=>$added_by,'active_user'=>$loginUserId,'modified_users'=>$modified_users,'rejected_note'=>$rejected_note);

		$json_score_status = json_encode($score_status);

		//update winner id
		MatchSchedule::where('id',$match_id)->update(['scoring_status'=>$status,'score_added_by'=>$json_score_status]);

		//notifications

		//get teams
		$team_a_id = MatchSchedule::where('id',$match_id)->pluck('a_id');
		$team_b_id = MatchSchedule::where('id',$match_id)->pluck('b_id');


		//get team a manager,owner,captain
		$team_a_owner_id = AllRequests::getempidonroles($team_a_id,'owner');
		$team_a_manager_id = AllRequests::getempidonroles($team_a_id,'manager');
		$team_a_captain_id = AllRequests::getempidonroles($team_a_id,'captain');


		//get team b manager,owner,captain
		$team_b_owner_id = AllRequests::getempidonroles($team_b_id,'owner');
		$team_b_manager_id = AllRequests::getempidonroles($team_b_id,'manager');
		$team_b_captain_id = AllRequests::getempidonroles($team_b_id,'captain');

		$match_start_date = MatchSchedule::where('id',$match_id)->pluck('match_start_date');
		$sports_id = MatchSchedule::where('id',$match_id)->pluck('sports_id');
		$match_data=MatchSchedule::where('id', $match_id)->first();
		$sports_name = Sport::where('id',$sports_id)->pluck('sports_name');

		$scorecardDetails = htmlentities("<a href='".('REQURL|'.'/match/scorecard/edit'.'/'.$match_id)."'> scorecard </a>");
		$loginUserNameData = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$loginUserId)."'>$loginUserName</a>");
		//notification code
		$message='';
		if($status=='approval_pending')
		{
			//$message=  trans('message.scorecard.forapprovenotification') ;

			$message = $loginUserNameData.' has sent you a '.$scorecardDetails.' Approval request. <br/> Sport:'.$sports_name.' , Sheduled Date:'.$match_start_date;

		}elseif($status=='rejected')
		{
			//$message=  trans('message.scorecard.rejectnotification') ;
			$message = 'Your '.$scorecardDetails.' has been rejected by '.$loginUserNameData.'. <br/>Sport:'.$sports_name.' , Sheduled Date:'.$match_start_date;
		}

		//if status is approved update match status as completed
		if($status == 'approved')
		{
			MatchSchedule::where('id',$match_id)->update(['match_status'=>'completed']);

			//$message=  trans('message.scorecard.approvenotification') ;
			$message = 'Your '.$scorecardDetails.' has been approved by '.$loginUserNameData.'. <br/>Sport:'.$sports_name.' , Sheduled Date:'.$match_start_date;

			
			//if no result, discard all data;

			// if($match_data->has_result==0){
			// 	$match_data->match_details=null;
			// 	$match_data->save();

			// 	if($sport_name=='Badminton'){
			// 		$players_stats=BadmintonPlayerMatchScore::whereMatchId($match_id)->get();
			// 		$this->discardMatchRecords($players_stats);
			// 	}
			// 	if($sport_name=='Squash'){
			// 		$players_stats=SquashPlayerMatchScore::whereMatchId($match_id)->get();
			// 		$this->discardMatchRecords($players_stats);
			// 	}
			// 	if($sport_name=='Hockey'){
			// 		$players_stats=	HockeyPlayerMatchwiseStat::whereMatchId($match_id)->get();
			// 		$this->discardMatchRecords($player_stats);
			// 	}
			// 	if($sport_name=='Soccer'){
			// 		$players_stats=	SoccerPlayerMatchwiseStat::whereMatchId($match_id)->get();
			// 		$this->discardMatchRecords($players_stats);
			// 	}
			// }

			// call function to insert player wise match details in statistics table
			if($sport_name!='')
				$this->insertPlayerStatistics($sport_name,$match_id);

			//send notification to players
			Helper::sendEmailPlayers($matchDetails, $sportName);	
		}
		//notification code
		$url= '';//url('match/scorecard/edit/'.$match_id) ;


		$schedule_type = MatchSchedule::where('id',$match_id)->pluck('schedule_type');

		if($schedule_type=='team') //IF schedule type is team
		{
			if($team_a_owner_id==$loginUserId || $team_a_manager_id==$loginUserId || $team_a_captain_id==$loginUserId)
			{
				if(!empty($team_b_owner_id)) //condition added if two team owneres are same
				{
					//if($loginUserId!=$team_b_owner_id)
					AllRequests::sendnotifications($team_b_owner_id,$message,$url);	//notification for owner
				}
				if(!empty($team_b_manager_id))
				{
					//if($loginUserId!=$team_b_manager_id)
					AllRequests::sendnotifications($team_b_manager_id,$message,$url);	//notification for manager
				}
				if(!empty($team_b_captain_id))
				{
					//if($loginUserId!=$team_b_captain_id)
					AllRequests::sendnotifications($team_b_captain_id,$message,$url);	//notification for captain
				}
			}else
			{
				if(!empty($team_a_owner_id)) //condition added if two team owneres are same
				{
					//if($loginUserId!=$team_a_owner_id)
					AllRequests::sendnotifications($team_a_owner_id,$message,$url);	//notification for owner
				}
				if(!empty($team_a_manager_id))
				{
					//if($loginUserId!=$team_a_manager_id)
					AllRequests::sendnotifications($team_a_manager_id,$message,$url);	//notification for manager
				}
				if(!empty($team_a_captain_id))
				{
					//if($loginUserId!=$team_a_captain_id)
					AllRequests::sendnotifications($team_a_captain_id,$message,$url); //notification for captain
				}
			}
		}else //if schedule type is player send notification to other user
		{
			if($team_a_id==$loginUserId)
				$user_id = $team_b_id;
			else
				$user_id = $team_a_id;
			AllRequests::sendnotifications($user_id,$message,$url);	//notification for owner
		}

		//return Response::json($results);
		return Response()->json( array('status' => 'success','msg' => trans('message.scorecard.scorecardstatus')) );
	}

	//function to call sport statistics
	public function insertPlayerStatistics($sport_name,$match_id)
	{
		$match_data = MatchSchedule::where('id',$match_id)->get(['winner_id','match_type','match_details','tournament_id','tournament_group_id','a_id','b_id','is_tied']);
		$match_type = !empty($match_data[0]['match_type'])?$match_data[0]['match_type']:'';
		$match_details = !empty($match_data[0]['match_details'])?$match_data[0]['match_details']:'';
		$winner_id = !empty($match_data[0]['winner_id'])?$match_data[0]['winner_id']:'';
		$decoded_match_details = array();
		if($match_details!='')
			$decoded_match_details = json_decode($match_details,true);
		//tennis or table tennis statistics
		if($sport_name=='Tennis' || $sport_name=='Table Tennis')
		{
			if(count($decoded_match_details)>0)
			{
				foreach($decoded_match_details as $key => $players)
				{
					$is_win='no';
					if($winner_id==$key)
					{
						$is_win = 'yes';
					}
					if($sport_name=='Tennis')
						$this->tennisStatistics($players,$match_type,$is_win);
					else if($sport_name=='Table Tennis')
						$this->tableTennisStatistics($players,$match_type,$is_win);
				}
			}

		}else if($sport_name=='Soccer')//soccer statistics Soccer
		{
			$soccer_details = SoccerPlayerMatchwiseStats::where('match_id',$match_id)->get(['user_id']);
			if(!empty($soccer_details) && count($soccer_details)>0)
			{
				foreach($soccer_details as $user_id)
				{
					$this->soccerStatistics($user_id['user_id']);
				}

			}

		}

		else if($sport_name=='Hockey')//soccer statistics Soccer
		{
			$soccer_details = HockeyPlayerMatchwiseStats::where('match_id',$match_id)->get(['user_id']);
			if(!empty($soccer_details) && count($soccer_details)>0)
			{
				foreach($soccer_details as $user_id)
				{
					$this->hockeyStatistics($user_id['user_id']);
				}

			}

		}

		else if($sport_name=='BasketBall')//basketball statistics 
		{
			$basketball_details = BasketballPlayerMatchwiseStats::where('match_id',$match_id)->get(['user_id']);
			if(!empty($basketball_details) && count($basketball_details)>0)
			{
				foreach($basketball_details as $user_id)
				{
					$this->basketballStatistics($user_id['user_id']);
				}

			}

		}


		else if($sport_name=='Cricket')//cricket statistics
		{
			$cricket_details = CricketPlayerMatchwiseStats::where('match_id',$match_id)->where('match_type',$match_type)->where('innings','first')->get(['user_id']);
			if(!empty($cricket_details) && count($cricket_details)>0)
			{
				foreach($cricket_details as $players)
				{
					$this->cricketBatsmenStatistic($players['user_id'],$match_type,$inning='first');//batsmen statistics
					$this->cricketBowlerStatistic($players['user_id'],$match_type,$inning='first');//bowler statistics
				}

			}

			if($match_type=='test')//for test match
			{
				$cricket_second_ing_details = CricketPlayerMatchwiseStats::where('match_id',$match_id)->where('match_type',$match_type)->where('innings','second')->get(['user_id']);
				if(!empty($cricket_second_ing_details) && count($cricket_second_ing_details)>0)
				{
					foreach($cricket_second_ing_details as $users)
					{
						$this->cricketBatsmenStatistic($users['user_id'],$match_type,$inning='second');//batsmen statistics
						$this->cricketBowlerStatistic($users['user_id'],$match_type,$inning='second');//bowler statistics
					}

				}

			}

		}

		//if match is scheduled from tournament
		if($match_data[0]['tournament_id']!='' && $match_data[0]['tournament_group_id']!='')
		{
			$team_a_id = $match_data[0]['a_id'];
			$team_b_id = $match_data[0]['b_id'];

			$tournamentDetails = Tournaments::where('id',$match_data[0]['tournament_id'])->get(['points_win','points_loose']);
			$tournament_won_poins = !empty($tournamentDetails[0]['points_win'])?$tournamentDetails[0]['points_win']:0;
			$tournament_lost_poins = !empty($tournamentDetails[0]['points_loose'])?$tournamentDetails[0]['points_loose']:0;


			$team_a_groupdetails = TournamentGroupTeams::where('tournament_id',$match_data[0]['tournament_id'])->where('tournament_group_id',$match_data[0]['tournament_group_id'])->where('team_id',$team_a_id)->get(['won','lost','points']);

			$team_b_groupdetails = TournamentGroupTeams::where('tournament_id',$match_data[0]['tournament_id'])->where('tournament_group_id',$match_data[0]['tournament_group_id'])->where('team_id',$team_b_id)->get(['won','lost','points']);

			$team_a_won_count = !empty($team_a_groupdetails[0]['won'])?$team_a_groupdetails[0]['won']:0;
			$team_a_lost_count = !empty($team_a_groupdetails[0]['lost'])?$team_a_groupdetails[0]['lost']:0;
			$team_a_points = !empty($team_a_groupdetails[0]['points'])?$team_a_groupdetails[0]['points']:0;

			$team_b_won_count = !empty($team_b_groupdetails[0]['won'])?$team_b_groupdetails[0]['won']:0;
			$team_b_lost_count = !empty($team_b_groupdetails[0]['lost'])?$team_b_groupdetails[0]['lost']:0;
			$team_b_points = !empty($team_b_groupdetails[0]['points'])?$team_b_groupdetails[0]['points']:0;

			//if winner id exists
			if($winner_id!='')
			{
				//if team a wons
				if($team_a_id==$winner_id)
				{
					TournamentGroupTeams::where('tournament_id',$match_data[0]['tournament_id'])->where('tournament_group_id',$match_data[0]['tournament_group_id'])->where('team_id',$team_a_id)->update(['won'=>$team_a_won_count+1,'points'=>$team_a_points+$tournament_won_poins]);
				}else
				{
					TournamentGroupTeams::where('tournament_id',$match_data[0]['tournament_id'])->where('tournament_group_id',$match_data[0]['tournament_group_id'])->where('team_id',$team_a_id)->update(['lost'=>$team_a_lost_count+1,'points'=>$team_a_points+$tournament_lost_poins]);
				}

				//if team b wons
				if($team_b_id==$winner_id)
				{
					TournamentGroupTeams::where('tournament_id',$match_data[0]['tournament_id'])->where('tournament_group_id',$match_data[0]['tournament_group_id'])->where('team_id',$team_b_id)->update(['won'=>$team_b_won_count+1,'points'=>$team_b_points+$tournament_won_poins]);
				}else
				{
					TournamentGroupTeams::where('tournament_id',$match_data[0]['tournament_id'])->where('tournament_group_id',$match_data[0]['tournament_group_id'])->where('team_id',$team_b_id)->update(['lost'=>$team_b_lost_count+1,'points'=>$team_b_points+$tournament_lost_poins]);
				}

			}
			else if ($match_data[0]['is_tied'] > 0 || $match_data[0]['match_result'] == "washout")//if match is tied/washout
			{
				TournamentGroupTeams::where('tournament_id',$match_data[0]['tournament_id'])->where('tournament_group_id',$match_data[0]['tournament_group_id'])->where('team_id',$team_a_id)->update(['points'=>$team_a_points+($tournament_won_poins/2)]);

				TournamentGroupTeams::where('tournament_id',$match_data[0]['tournament_id'])->where('tournament_group_id',$match_data[0]['tournament_group_id'])->where('team_id',$team_b_id)->update(['points'=>$team_b_points+($tournament_won_poins/2)]);

			}

			//update organization points;

		$organization=Organization::join('tournament_parent', 'organization.id', '=', 'tournament_parent.organization_id')
								->join('tournaments', 'tournaments.tournament_parent_id', '=', 'tournament_parent.id')
								->where('tournaments.id', '=', $match_data[0]['tournament_id'])
								->first();

		if(!is_null($organization)){
				Helper::updateOrganizationTeamsPoints($organization->id);
		}

		}

		

	}

	function updateBracketDetails($matchScheduleDetails,$tournamentDetails,$winner_team_id) {
//            dd($matchScheduleDetails);
		$roundNumber = $matchScheduleDetails['tournament_round_number'];
		$matchNumber = $matchScheduleDetails['tournament_match_number'];
		$matchNumberToCheck = ceil($matchNumber / 2);
		$matchScheduleData = MatchSchedule::where('tournament_id',$matchScheduleDetails['tournament_id'])
			->where('tournament_round_number',$roundNumber+1)
			->where('tournament_match_number',$matchNumberToCheck)
			->first();
		if(count($matchScheduleData)) {
			if ($matchScheduleData['schedule_type'] == 'team') {
				$player_b_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_a_ids'))->where('team_id', $winner_team_id)->pluck('player_a_ids');
			}else {
				$player_b_ids = $winner_team_id;
			}
			MatchSchedule::where('id',$matchScheduleData['id'])->update(['b_id'=>$winner_team_id,'player_b_ids'=>!empty($player_b_ids)?(','.trim($player_b_ids).','):NULL]);

		}else{
			if ($matchScheduleData['schedule_type'] == 'team') {
				$player_a_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_a_ids'))->where('team_id', $winner_team_id)->pluck('player_a_ids');
			}else {
				$player_a_ids = $winner_team_id;
			}
			$scheduleArray = [
				'tournament_id' => $matchScheduleDetails['tournament_id'],
				'tournament_round_number' => $roundNumber+1,
				'tournament_match_number' => $matchNumberToCheck,
				'sports_id' => $matchScheduleDetails['sports_id'],
				'facility_id' => $matchScheduleDetails['facility_id'],
				'facility_name' => $matchScheduleDetails['facility_name'],
				'created_by' => $matchScheduleDetails['created_by'],
				'match_category' => $matchScheduleDetails['match_category'],
				'schedule_type' => $matchScheduleDetails['schedule_type'],
				'match_type' => $matchScheduleDetails['match_type'],
				'match_location' => $matchScheduleDetails['match_location'],
				'city_id' => $matchScheduleDetails['city_id'],
				'city' => $matchScheduleDetails['city'],
				'state_id' => $matchScheduleDetails['state_id'],
				'state' => $matchScheduleDetails['state'],
				'country_id' => $matchScheduleDetails['country_id'],
				'country' => $matchScheduleDetails['country'],
				'zip' => $matchScheduleDetails['zip'],
				'match_status' => 'scheduled',
				'a_id' => $winner_team_id,
				'player_a_ids' => !empty($player_a_ids)?(','.trim($player_a_ids).','):NULL,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			];

			$matchSchedule = MatchSchedule::create($scheduleArray);

			// Update the winner Id of the for the winner team.
			$maxRoundNumber = MatchSchedule::
			where('tournament_id', $matchScheduleDetails['tournament_id'])->whereNull('tournament_group_id')
				->orderBy('tournament_round_number')
				->max('tournament_round_number');
			$tournamentDetails = Tournaments::where('id',$matchScheduleDetails['tournament_id'])->first(['final_stage_teams']);
			if(count($tournamentDetails)) {
				$lastRoundWinner = intval(ceil(log($tournamentDetails['final_stage_teams'], 2)));
			}
			if(count($maxRoundNumber) && !empty($lastRoundWinner)) {
				if($maxRoundNumber==$lastRoundWinner+1){
					if(!empty($matchSchedule) && $matchSchedule['id']>0) {
						MatchSchedule::where('id',$matchSchedule['id'])->update([
							'match_status'=>'completed',
							'winner_id'=>$winner_team_id
						]);

					}
				}
			}
		}


	}
	public function scorecardGallery($id)
	{
		return view('scorecards.gallery')->with(array('action_id'=>$id));
	}
	public function checkScoreEnterd($match_id)
	{
		$isvalid = 1;
		$loginUserId = Auth::user()->id;
		$match_details = MatchSchedule::where('id',$match_id)->get(['score_added_by']);
		$score_added_by = !empty($match_details[0]['score_added_by'])?$match_details[0]['score_added_by']:'';
		$decode_scorecard_data = array();
		if($score_added_by!='')
		{
			$decode_scorecard_data = json_decode($score_added_by,true);
			$added_by = $decode_scorecard_data['added_by'];
			if($added_by!=$loginUserId)
			{
				$isvalid = 0;
			}

		}
		return $isvalid;
	}
	public function scoreAddedByUserName($match_id)
	{
		$user_name='';
		$added_by=0;
		$match_details = MatchSchedule::where('id',$match_id)->get(['score_added_by']);
		$score_added_by = !empty($match_details[0]['score_added_by'])?$match_details[0]['score_added_by']:'';
		if($score_added_by!='')
		{
			$decode_scorecard_data = json_decode($score_added_by,true);
			$added_by = $decode_scorecard_data['added_by'];
			$user_name = User::where('id',$added_by)->pluck('name');
		}
		if($user_name!='' && $added_by>0)
		{
			return "<a href='".('/editsportprofile'.'/'.$added_by)."'> ".$user_name." </a>";
		}
		return $user_name;
	}


	//new controllers for soccer

	public function confirmSquad(){
		$request=Request::all();
		$match_id 		=$request['match_id'];

		$tournament_id 	=isset($request['tournament_id'])?$request['tournament_id']:null;
		$team_a_id 		=$request['team_a_id'];
		$team_b_id 		=$request['team_b_id'];

		$match_model=MatchSchedule::find($match_id);
		$match_model->hasSetupSquad=1;
		$match_model->save();

		$team_a_name=$request['team_a_name'];
		$team_b_name=$request['team_b_name'];
		$team_a_playing_players=isset($request['team_a']['playing'])?$request['team_a']['playing']:[];
		$team_b_playing_players=isset($request['team_b']['playing'])?$request['team_b']['playing']:[];

		$team_a_substitute_players=isset($request['team_a']['substitute'])?$request['team_a']['substitute']:[];
		$team_b_substitute_players=isset($request['team_b']['substitute'])?$request['team_b']['substitute']:[];

		foreach($team_a_playing_players as $p){
			$player_name=User::find($p)->name;
			$this->insertSoccerScore($p, $tournament_id, $match_id, $team_a_id,$player_name, $team_a_name,0,0,0,'P');
		}
		foreach($team_a_substitute_players as $p){
			$player_name=User::find($p)->name;
			$this->insertSoccerScore($p, $tournament_id, $match_id, $team_a_id,$player_name, $team_a_name,0,0,0,'S');
				
		}
		foreach($team_b_playing_players as $p){
			$player_name=User::find($p)->name;
			$this->insertSoccerScore($p, $tournament_id, $match_id, $team_b_id,$player_name, $team_b_name,0,0,0,'P');
		}
		foreach($team_b_substitute_players as $p){			
			$player_name=User::find($p)->name;
			$this->insertSoccerScore($p, $tournament_id, $match_id, $team_b_id,$player_name, $team_b_name,0,0,0,'S');
				
		}
		

		$match_model=MatchSchedule::find($match_id);
		$match_details=[
			"team_a"=>[
				"id"=>$team_a_id,
				"name"=>$team_a_name
					],
            "team_b"=>[
            	"id"=>$team_b_id,
            	"name"=>$team_b_name
            		],
			"{$team_a_id}"	=>	[
				"id"=>$team_a_id,
				"goals"=>0,
				"red_card_count"=>0,
				"yellow_card_count"=>0,
				"ball_percentage"=>0],
			"{$team_b_id}"=>[
				"id"=>$team_b_id,
				"goals"=>0,
				"red_card_count"=>0,
				"yellow_card_count"=>0,
				"ball_percentage"=>0],
			"first_half"=>[
				"goals"=>0,
				"team_{$team_a_id}_goals"=>0,
				"team_{$team_b_id}_goals"=>0,
				"team_{$team_a_id}_yellow_card_count"=>0,
				"team_{$team_b_id}_yellow_card_count"=>0,
				"team_{$team_a_id}_red_card_count"=>0,
				"team_{$team_b_id}_red_card_count"=>0,
				"red_card_count"=>0,
				"yellow_card_count"=>0,
				"goals_details"=>[],
				"red_card_details"=>[],
				"yellow_card_details"=>[]
			],
			"second_half"=>[
				"goals"=>0,
				"team_{$team_a_id}_goals"=>0,
				"team_{$team_b_id}_goals"=>0,
				"team_{$team_a_id}_yellow_card_count"=>0,
				"team_{$team_b_id}_yellow_card_count"=>0,
				"team_{$team_a_id}_red_card_count"=>0,
				"team_{$team_b_id}_red_card_count"=>0,
				"red_card_count"=>0,
				"yellow_card_count"=>0,
				"goals_details"=>[],
				"red_card_details"=>[],
				"yellow_card_details"=>[]
			],
			"penalties"=>[
				'score'=>'',
				'team_a'=>[
					'players'=>[],
					'goals'=>0,
					'players_ids'=>[]
				],
				'team_b'=>[
					'players'=>[],
					'goals'=>0,
					'players_ids'=>[]
				]
			]
		];

		$match_model->match_details=json_encode($match_details);
		$match_model->save();
	}



	/**Swap Player status form substitute to playing vice versa.
	 *
	 * @return Response
	 */
	public function soccerSwapPlayers(){
		$request=Request::all();
		$match_id=$request['match_id'];
		$team_id=$request['team_id'];
		$time_substituted=$request['time_substituted'];
		$soccer_model=SoccerPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_id)->get();

		foreach ($soccer_model as $sm ){
			$sm_id=$sm->id;
			$sm_status=$sm->playing_status;

			if(isset($request["substitute_a_".$sm_id]) && $request["substitute_a_".$sm_id]=='on' ){
				if($sm_status=='P'){
					$sm->playing_status='S';
				}
				else $sm->playing_status='P';

				$sm->has_substituted=1;
				$sm->time_substituted=$time_substituted;
				$sm->save();


			}

		}
		return $soccer_model;

	}

	/**
	 * Select penalties players
	 *
	 * @param  Request players
	 * @return Response
	 */

	public function choosePenaltyPlayers(){
		$request=Request::all();
		$index_a=$request['p_index_a'];
		$index_b=$request['p_index_b'];
		$team_a_id=$request['team_a_id'];
		$team_b_id=$request['team_b_id'];
		$match_id=$request['match_id'];

		$match_model=matchSchedule::find($match_id);
		$match_details=json_decode($match_model['match_details'],true);

if(!isset($match_details['penalties']['team_a']['players_ids']))$match_details['penalties']['team_a']['players_ids']=[];
if(!isset($match_details['penalties']['team_b']['players_ids']))$match_details['penalties']['team_b']['players_ids']=[];

		$penalties=$match_details['penalties'];			

		$response_a="";
		$response_b="";
		for($i=0; $i<$index_a; $i++){

		  if(isset($request['penalty_player_a_'.$i]) ){

			 if(!in_array($request['penalty_player_user_id_a_'.$i], $penalties['team_a']['players_ids'])){
				array_push($penalties['team_a']['players_ids'], $request['penalty_player_user_id_a_'.$i]);
				
				$player_id=$request['penalty_player_id_a_'.$i];
				$matchwise_model=SoccerPlayerMatchwiseStats::find($player_id);
				$matchwise_model->penalty=1;
				$matchwise_model->save();
				$player=[
					'name'=>$request['penalty_player_name_a_'.$i],
					'stat_id'=>$request['penalty_player_id_a_'.$i],
					'goal'=>'',
					'user_id'=>$request['penalty_player_user_id_a_'.$i],
				];
				array_push($penalties['team_a']['players'], $player);

				$response_a.="
	  					<tr>
	  					<td colspan=2>{$player['name']}</td><td> 
	  					0 <button class='btn-red-card btn-card btn-circle btn-penalty btn_team_a_$i' value='0' name='penalty_goal_a_$i' index='$i' team_id='$team_a_id' team_type='team_a' onclick='return scorePenalty(this)' > </button> 
	  					1 <button class='btn-green-card btn-card btn-circle btn-penalty btn_team_a_$i' value='1' name='penalty_goal_a_$i' index='$i' team_id='$team_a_id' team_type='team_a' onclick='return scorePenalty(this)' >  
	  					<input type='hidden' name='penalty_goal_player_a_$i' value='$player_id' > </button>
	  					<tr>
	  				";
				}
			}
		}

		for($i=0; $i<$index_b; $i++){
			if(isset($request['penalty_player_b_'.$i])){
			 if(!in_array($request['penalty_player_user_id_b_'.$i], $penalties['team_b']['players_ids'])){

				array_push($penalties['team_b']['players_ids'], $request['penalty_player_user_id_b_'.$i]);
				
				$player_id=$request['penalty_player_id_b_'.$i];
				$matchwise_model=SoccerPlayerMatchwiseStats::find($player_id);
				$matchwise_model->penalty=1;
				$matchwise_model->save();
				$player=[
					'name'=>$request['penalty_player_name_b_'.$i],
					'stat_id'=>$request['penalty_player_id_b_'.$i],
					'goal'=>'',
					'user_id'=>$request['penalty_player_user_id_b_'.$i],
				];
				array_push($penalties['team_b']['players'], $player);

				$response_b.="
	  					<tr>
	  					<td colspan=2>{$player['name']}</td><td> 
	  					0 <button class='btn-red-card btn-card btn-circle btn-penalty btn_team_b_$i'  value='0' name='penalty_goal_b_$i' index='$i' team_type='team_b'  team_id='$team_b_id' onclick='return scorePenalty(this)'> </button>
	  					1 <button class='btn-green-card btn-card btn-circle btn-penalty btn_team_b_$i'  value='1'  name='penalty_goal_b_$i' index='$i' team_id='$team_b_id' team_type='team_b'  onclick='return scorePenalty(this)'> </button> 
	  					<input type='hidden' name='penalty_goal_player_b_$i' value='$player_id'>
	  					<tr>
	  				";
	  			}
			}
		}

		$match_details['penalties']=$penalties;
		$index_a--;

		$match_model['match_details']=json_encode($match_details);
		$match_model->save();
		$response_a.="<input type='hidden' value='$index_a' name='penalty_goal_index_a'>";
		$response_b.="<input type='hidden' value='$index_b' name='penalty_goal_index_b'><input type='hidden' name='set_penalty'>";


		return [
			"message"=>"Players have been Chosen Succesffuly!",
			"response_a"=>$response_a,
			"response_b"=>$response_b
		];

	}

	public function scorePenalty(){
			$request=Request::all();
			$match_id=$request['match_id'];
			$team_type=$request['team_type'];
			$index=$request['index'];
			$value=$request['value'];

			$match_model=MatchSchedule::find($match_id);
			$match_details=json_decode($match_model->match_details, true);

			$match_details['penalties'][$team_type]['players'][$index]['goal']=$value;

			$team_a_penalty_goals=0;
			$team_b_penalty_goals=0;

			foreach ($match_details['penalties'][$team_type]['players'] as $key => $value) {
					${$team_type.'_penalty_goals'}+=$value['goal'];					
			}

			$match_details['penalties'][$team_type]['goals']=${$team_type.'_penalty_goals'};
			$match_details=json_encode($match_details);
			$match_model->match_details=$match_details;
			$match_model->save();
			return $match_details;
	}

//discard match details
	public function discardMatchRecords($players_stats){
			foreach ($players_stats as $ps) {
				//$ps->delete();
			}
	}
}
?>