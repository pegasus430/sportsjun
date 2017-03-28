<?php

namespace App\Http\Controllers\User;

//use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\InvitePlayerController;
use App\Http\Requests;
use App\Helpers\AllRequests;
use App\Helpers\SendMail;
use App\Model\City;
use App\Model\Country;
use App\Model\Facilityprofile;
use App\Model\Followers;
use App\Model\MatchSchedule;
use App\Model\MatchScheduleRubber;
use App\Model\Photo;
use App\Model\Requestsmodel;
use App\Model\Sport;
use App\Model\State;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\TournamentFinalTeams;
use App\Model\TournamentGroups;
use App\Model\TournamentGroupTeams;
use App\Model\TournamentParent;
use App\Model\Tournaments;
use App\Model\Carts;
use App\Model\CartDetails;
use App\Model\PaymentDetails;
use App\Model\PaymentGateWays;
use App\Model\UserStatistic;
use App\Model\VendorBankAccounts;
use App\Model\BasicSettings;
use App\Model\TournamentMatchPreference as Settings;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PDO;
use Request;
use Response;
use Session;
use View;
use Input;
use Hash;
use Mail;
use Validator;
use ScoreCard;


use App\Model\SoccerPlayerMatchwiseStats;
use App\Model\HockeyPlayerMatchwiseStats;
use App\Model\BasketballPlayerMatchwiseStats;
use App\Model\CricketPlayerMatchwiseStats;
use App\Model\WaterpoloPlayerMatchwiseStats;
use App\Model\KabaddiPlayerMatchwiseStats;
use App\Model\ArcheryStatistic;
use App\Model\ArcheryPlayerStats;
use App\Model\ArcheryTeamStats;


class TournamentsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($id = '') {





		if ($id == '')
			$user_id = Auth::user()->id;
		else
			$user_id = $id;
		$joinTeamArray = array();
		$manageTeamArray = array();
		$followingTeamArray = array();

		$teamDetails = UserStatistic::where('user_id', $user_id)->first();

		//Get following tournaments
		$modelObj = new \App\Model\Followers;

		$follow_tournamentDetails = $modelObj->getFollowingList($user_id,'tournament');
		//echo "<pre>";print_r($follow_tournamentDetails);exit;


		$following_team_array = array();
		if(isset($follow_tournamentDetails) && count($follow_tournamentDetails)>0)
		{
			$following_team_array = array_filter(explode(',',$follow_tournamentDetails[0]->following_list));
		}
		//echo "<pre>";print_r($following_team_array);exit;
		//End


		$managedTournamentDetails = array();
		$subTournamentsDetails = array();
		$subTournamentsArray = array();



		$loginUserId = Auth::user()->id;

		//$managedTournamentDetails = Tournaments::with('photos')->whereIn('id', $managed_team_array)
		//->get(['id', 'name', 'created_by', 'sports_id', 'type', 'final_stage_teams', 'description']);

		//$managedTournamentDetails = TournamentParent::where('manager_id',$loginUserId)->orwhere('owner_id',$loginUserId)->get(['id', 'name', 'created_by','owner_id','manager_id','description','logo']);//parent tournaments

		$managedTournamentDetails = DB::table('tournament_parent')
			->leftjoin('tournaments', 'tournament_parent.id', '=', 'tournaments.tournament_parent_id')
			->where('tournament_parent.manager_id',$loginUserId)
			->orwhere('tournament_parent.owner_id',$loginUserId)
			->orwhere('tournaments.manager_id',$loginUserId)
			->orderby('tournament_parent.created_at', 'desc')
			->get(['tournament_parent.id', 'tournament_parent.name', 'tournament_parent.created_by','tournament_parent.owner_id','tournament_parent.manager_id','tournament_parent.description','tournament_parent.logo']);



		$i=0;
		$parent_tour_id ='';
		if (count($managedTournamentDetails)) {
			foreach ($managedTournamentDetails as $managedTournament) {
				if($parent_tour_id!=$managedTournament->id)
				{

					$manageTeamArray[$i]['id'] = $managedTournament->id;
					$manageTeamArray[$i]['name'] = $managedTournament->name;
					$manageTeamArray[$i]['owner_id'] = $managedTournament->owner_id;
					$manageTeamArray[$i]['manager_id'] = $managedTournament->manager_id;
					$manageTeamArray[$i]['description'] = $managedTournament->description;
					$manageTeamArray[$i]['logo'] = $managedTournament->logo;
					$manageTeamArray[$i]['created_by'] = $managedTournament->created_by;
					$createdUserName = User::where('id', $managedTournament->created_by)->first(['name']);
					if (count($createdUserName))
						$manageTeamArray[$i]['createdUserName'] = $createdUserName->name;
					else
						$manageTeamArray[$i]['createdUserName'] = '';

					$ownerUserName = User::where('id', $managedTournament->owner_id)->first(['name']);
					if (count($ownerUserName))
						$manageTeamArray[$i]['ownerUserName'] = $ownerUserName->name;
					else
						$manageTeamArray[$i]['ownerUserName'] = '';

					$managerUserName = User::where('id', $managedTournament->manager_id)->first(['name']);
					if (count($managerUserName))
						$manageTeamArray[$i]['managerUserName'] = $managerUserName->name;
					else
						$manageTeamArray[$i]['managerUserName'] = '';


					//sub tournaments if login user is owner or manager of parent tournament display all sub tournaments
					if($managedTournament->owner_id==$loginUserId || $managedTournament->manager_id==$loginUserId)
						$subTournamentsDetails = Tournaments::where('tournament_parent_id',$managedTournament->id)->orderby('created_at', 'desc')->get(['id', 'name', 'created_by', 'sports_id', 'manager_id' ,'type', 'final_stage_teams', 'description']);//sub tournaments
					else
						$subTournamentsDetails = Tournaments::where('tournament_parent_id',$managedTournament->id)->where('manager_id',$loginUserId)->orderby('created_at', 'desc')->get(['id', 'name', 'created_by', 'sports_id', 'manager_id' ,'type', 'final_stage_teams', 'description']);//sub tournaments

					if(!empty($subTournamentsDetails) && count($subTournamentsDetails))
					{
						foreach($subTournamentsDetails->toArray() as $key => $subtour)
						{
							$sportsName = Sport::where('id', $subtour['sports_id'])->first(['sports_name']);
							if (count($sportsName))
								$subTournamentsDetails[$key]['sports_name'] = $sportsName->sports_name;
							else
								$subTournamentsDetails[$key]['sports_name'] = '';
							$userName = User::where('id', $subtour['created_by'])->first(['name']);
							if (count($userName))
								$subTournamentsDetails[$key]['user_name'] = $userName->name;
							else
								$subTournamentsDetails[$key]['user_name'] = '';
							$sport_logo = Photo::select()->where('imageable_id', $subtour['sports_id'])->where('imageable_type', config('constants.PHOTO.SPORT_LOGO'))->orderBy('id', 'desc')->first(); //get sport logo
							if(count($sport_logo))
								$subTournamentsDetails[$key]['sports_logo'] = $sport_logo['url'];
							else
								$subTournamentsDetails[$key]['sports_logo'] = '';
							if ($subtour['type'] == 'knockout') {
								if (!empty($subtour['final_stage_teams'])) {
									$subTournamentsDetails[$key]['team_count'] = $subtour['final_stage_teams'];
								} else {
									$subTournamentsDetails[$key]['team_count'] = 0;
								}
							} else {
								$mangedTournamentGroups = TournamentGroups::where('tournament_id', $subtour['id'])->get(['id']);
								if (count($mangedTournamentGroups)) {
									$managedTournamentTeamCount = TournamentGroupTeams::whereIn('tournament_group_id', array_flatten($mangedTournamentGroups->toArray()))->count();
									if ($managedTournamentTeamCount) {
										$subTournamentsDetails[$key]['team_count'] = $managedTournamentTeamCount;
									} else {
										$subTournamentsDetails[$key]['team_count'] = 0;
									}
								} else {
									$subTournamentsDetails[$key]['team_count'] = 0;
								}
							}
						}
						//$subTournamentsArray[$managedTournament->id] = $subTournamentsDetails->toArray();
						$manageTeamArray[$i][$managedTournament->id] = $subTournamentsDetails->toArray();
					}
					$i++;
				}
				$parent_tour_id = $managedTournament->id;
			}


		}
		//echo "<pre/>";print_r($manageTeamArray);die();
		if (isset($teamDetails) && count($teamDetails) > 0) {

			$managed_team_array = array_filter(explode(',', $teamDetails->managing_tournaments));
			$joined_team_array = array_filter(explode(',', $teamDetails->joined_tournaments));

			//$following_team_array = array_filter(explode(',', $teamDetails->following_tournaments));

			/*$joinedTournamentDetails = Tournaments::with('photos')->whereIn('id', $joined_team_array)
                    ->get(['id', 'name', 'created_by', 'sports_id', 'type', 'final_stage_teams', 'description']);*/



			$joinedTournamentDetails = Helper::getJoinedTournaments();

           // dd($joinedTournamentDetails);


			if (count($joinedTournamentDetails)) {
				/*foreach ($joinedTournamentDetails->toArray() as $joinKey => $joinedTournament) {
                    $sportsName = Sport::where('id', $joinedTournament['sports_id'])->first(['sports_name']);
                    if (count($sportsName))
                        $joinedTournamentDetails[$joinKey]['sports_name'] = $sportsName->sports_name;
                    else
                        $joinedTournamentDetails[$joinKey]['sports_name'] = '';

                    $userName = User::where('id', $joinedTournament['created_by'])->first(['name']);
                    if (count($userName))
                        $joinedTournamentDetails[$joinKey]['user_name'] = $userName->name;
                    else
                        $joinedTournamentDetails[$joinKey]['user_name'] = '';
                    if (count($joinedTournament['photos'])) {
                        $photoUrl = array_collapse($joinedTournament['photos']);
                        $joinedTournamentDetails[$joinKey]['url'] = $photoUrl['url'];
                    } else {
                        //                    $matchScheduleData[$key]['scheduleteamone']['url'] = '';
                        $photoUrl = $joinedTournamentDetails[$joinKey];
                        $photoUrl['url'] = '';
                    }

                    if ($joinedTournament['type'] == 'knockout') {
                        if (!empty($joinedTournament['final_stage_teams'])) {
                            $joinedTournamentDetails[$joinKey]['team_count'] = $joinedTournament['final_stage_teams'];
                        } else {
                            $joinedTournamentDetails[$joinKey]['team_count'] = 0;
                        }
                    } else {
                        $joinedTournamentGroups = TournamentGroups::where('tournament_id', $joinedTournament['id'])->get(['id']);
                        if (count($joinedTournamentGroups)) {
                            $joinedTournamentTeamCount = TournamentGroupTeams::whereIn('tournament_group_id', array_flatten($joinedTournamentGroups->toArray()))->count();
                            if ($joinedTournamentTeamCount) {
                                $joinedTournamentDetails[$joinKey]['team_count'] = $joinedTournamentTeamCount;
                            } else {
                                $joinedTournamentDetails[$joinKey]['team_count'] = 0;
                            }
                        } else {
                            $joinedTournamentDetails[$joinKey]['team_count'] = 0;
                        }
                    }
                }
                $joinTeamArray = $joinedTournamentDetails->toArray();*/
				$joinTeamArray = $joinedTournamentDetails;
			}

			$followingTournamentDetails = array();

			if(count($following_team_array) > 0){
				//echo "<pre>";print_r($following_team_array);exit;
				$followingTournamentDetails = Tournaments::with('logo')->whereIn('tournaments.id', $following_team_array)
					->get(['id', 'tournament_parent_id', 'name', 'created_by', 'sports_id', 'type', 'final_stage_teams', 'description']);
			}
			//Helper::printQueries();
			//echo "<pre>";print_r($followingTournamentDetails);exit;
			if (count($followingTournamentDetails)) {
				foreach ($followingTournamentDetails->toArray() as $followKey => $followedTournament) {
					$sportsName = Sport::where('id', $followedTournament['sports_id'])->first(['sports_name']);
					if (count($sportsName))
						$followingTournamentDetails[$followKey]['sports_name'] = $sportsName->sports_name;
					else
						$followingTournamentDetails[$followKey]['sports_name'] = '';

					$userName = User::where('id', $followedTournament['created_by'])->first(['name']);
					if (count($userName))
						$followingTournamentDetails[$followKey]['user_name'] = $userName->name;
					else
						$followingTournamentDetails[$followKey]['user_name'] = '';
					if (count($followedTournament['logo'])) {
						$followingTournamentDetails[$followKey]['url'] = $followedTournament['logo']['url'];
					} else {
						$followingTournamentDetails[$followKey]['url'] = '';
					}

					if ($followedTournament['type'] == 'knockout') {
						if (!empty($followedTournament['final_stage_teams'])) {
							$followingTournamentDetails[$followKey]['team_count'] = $followedTournament['final_stage_teams'];
						} else {
							$followingTournamentDetails[$followKey]['team_count'] = 0;
						}
					} else {
						$followedTournamentGroups = TournamentGroups::where('tournament_id', $followedTournament['id'])->get(['id']);
						if (count($followedTournamentGroups)) {
							$followedTournamentTeamCount = TournamentGroupTeams::whereIn('tournament_group_id', array_flatten($followedTournamentGroups->toArray()))->count();
							if ($followedTournamentTeamCount) {
								$followingTournamentDetails[$followKey]['team_count'] = $followedTournamentTeamCount;
							} else {
								$followingTournamentDetails[$followKey]['team_count'] = 0;
							}
						} else {
							$followingTournamentDetails[$followKey]['team_count'] = 0;
						}
					}
				}
				$followingTeamArray = $followingTournamentDetails->toArray();
			}
//                                dd($manageTeamArray);

			/* foreach($managed_team_array as $manage_team_id)
              {
              $manage_details = Tournaments::where('id', $manage_team_id)->first();
              $manageTeamArray[$manage_details->id] = $manage_details->name;
              }
              foreach($joined_team_array as $join_team_id)
              {
              $team_details = Tournaments::where('id', $join_team_id)->first();
              $joinTeamArray[$team_details->id] = $team_details->name;
              }
              foreach($following_team_array as $follow_team_id)
              {
              $follow_details = Tournaments::where('id', $follow_team_id)->first();
              $followingTeamArray[$follow_details->id] = $follow_details->name;
              } */
		}
		$lef_menu_condition = '';
		$tournament_type='';
		$left_menu_data = array();
		$follow_array=array();
	

       // dd($manageTeamArray);

/*----------------------------------custom code for joined tournaments-----------------------------------*/

    $user_id = Auth::user()->id;
    $register_data = Carts::with('cartDetails.tournaments')->where('user_id',$user_id)->where('payment_token','!=','')->orderBy('id', 'DESC')->get();
    $join_data=array();
    $i=0;
    foreach($register_data as $reg){
    foreach($reg->cartDetails as $carts){
      $join_data[$i]['url']=TournamentParent::where('id',$carts->tournaments->id)->value('logo');
      //$join_data[$i]['url']=TournamentParent::where('id',$carts->event_id)->value('logo');
      $join_data[$i]['name']=$carts->tournaments->name;
      $join_data[$i]['user_name']=User::where('id',$carts->tournaments->created_by)->value('name');
      $join_data[$i]['sports_name']=Sport::where('id',$carts->tournaments->sports_id)->value('sports_name');
      $join_data[$i]['team_count']='';
      $join_data[$i]['description']=$carts->tournaments->description;
      $join_data[$i]['id']=$carts->tournaments->id;
      $i++;
     }
    }
   //'joinTeamArray' => $joinTeamArray,

/*----------------------------------custom code for joined tournaments----------------------------------*/






		Helper::setMenuToSelect(6, 1);
		return view('tournaments.team', array('joinTeamArray' => $join_data, 'followingTeamArray' => $followingTeamArray, 'manageTeamArray' => $manageTeamArray,
			'lef_menu_condition' => $lef_menu_condition, 'managedTournamentDetails' => $managedTournamentDetails,
			'subTournamentsArray'=>$subTournamentsArray,'loginUserId'=>$loginUserId, 'tournament_type'=>$tournament_type, 'follow_array'=>$follow_array));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$enum = config('constants.ENUM.TOURNAMENTS.TYPE');
		$schedule_type_enum = config('constants.ENUM.TOURNAMENTS.SCHEDULE_TYPE');
		$game_type_enum = config('constants.ENUM.TOURNAMENTS.GAME_TYPE');
//		$sports = Sport::where('isactive','=',1)->lists('sports_name', 'id')->all();
		$sports = Helper::getDevelopedSport(1,1);
		$cities = array();
		$states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
		$organizations = Auth::user()->organizations()->lists('name', 'id')->all();
		$disclaimer_content = BasicSettings::where('id',1)->value('description');
		//dd($settings);


		return view("tournaments.tournamentscreate", [
			'sports'             => ['' => 'Select Sport'] + $sports,
			'states'             => ['' => 'Select State'] + $states,
			'cities'             => ['' => 'Select City'] + $cities,
			'enum'               => ['' => 'Tournament Type'] + $enum,
			'type'               => 'create',
			'roletype'           => 'user',
			'schedule_type_enum' => $schedule_type_enum,
			'game_type_enum'	 => $game_type_enum,
			'organization'      => ['' => 'Select Organization'] + $organizations,
			'disclaimer_content'          =>$disclaimer_content
		]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Requests\CreateTournamentRequest $request)
	{

		// echo "<pre>";
		//   print_r($request->all());
		// die;
		
		if(!empty($request['isParent']) && $request['isParent']=='yes')
		{
			return $this->createParentTournament($request);
		}
		//echo Carbon::now()->toDateString();exit;
		$request['country_id'] = config('constants.COUNTRY_INDIA'); 
		$request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
		$request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
		$request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
		$request['location']=trim($location,",");
		$request['tournament_parent_id'] = !empty($request['tournament_parent_id'])?$request['tournament_parent_id']:'';
		$request['tournament_parent_name'] = !empty($request['tournament_parent_name'])?$request['tournament_parent_name']:'';
		$request['manager_id'] = !empty($request['user_id'])?$request['user_id']:'';
		$request['match_type'] = !empty($request['match_type'])?$request['match_type']:'';
		$request['player_type'] = !empty($request['player_type'])?$request['player_type']:'';

		if(!empty($request['start_date']))
		{
			$request['start_date']=Helper::storeDate($request['start_date']);//date('Y-m-d', strtotime($request['start_date']));
		}
		else
		{
			$request['start_date']= Carbon::now()->toDateString();
		}
		if(!empty($request['end_date']))
		{
			$request['end_date']=Helper::storeDate($request['end_date']);//date('Y-m-d', strtotime($request['end_date']));
		}
		else
		{
			$request['end_date']= Carbon::now()->toDateString();
		}

		//$s=Carbon::createFromFormat('d/m/Y', $request['start_date']);
		//echo $s;exit;
		//echo	$request['end_date'];exit;
		//$request['end_date']=date('Y-m-d', strtotime($request['end_date']));
		$request['created_by'] = Auth::user()->id;
		$request['status'] = 1;
		/*if($request->input('facility_response')!=''&& $request->input('facility_response_name')!='')
		{
		$request['facility_id'] = $request->input('facility_response');
		$request['facility_name'] =$request->input('facility_response_name');
		}*/
		// inserting vendor account details
		if(!empty($request['account_holder_name']) && 
			!empty($request['account_number']) &&
			!empty($request['bank_name']) && 
			!empty($request['branch']) &&
			!empty($request['ifsc'])
		 ){
		 	$vendor = new VendorBankAccounts();
			$vendor_bank_account_id = $vendor->saveBankDetails(
				$request['account_holder_name'],
				$request['account_number'],
				$request['bank_name'],
				$request['branch'],
				$request['ifsc'],
				Auth::user()->id
			);
			if($vendor_bank_account_id){
				$request['vendor_bank_account_id'] = $vendor_bank_account_id;
			}
		}


         if(!empty($request['filelist_file_upload'])) {

           //  /*--files moving from temp directory to attachments directory--*/
             $image_moved=Helper::moveImage($request['filelist_file_upload'],$vendor_bank_account_id);
           /*--files moving from temp directory to attachments directory--*/
          
        }  




		$request['reg_opening_date']=Helper::storeDate($request['reg_opening_date']);
		$request['reg_closing_date']=Helper::storeDate($request['reg_closing_date']);
		$request['reg_opening_time'] = !empty($request['reg_opening_time'])?$request['reg_opening_time']:'00:00:00';
		$request['reg_closing_time'] = !empty($request['reg_closing_time'])?$request['reg_closing_time']:'00:00:00';


       $request['reg_opening_time']=date("H:i:s", strtotime($request['reg_opening_time']));
       $request['reg_closing_time'] =date("H:i:s", strtotime($request['reg_closing_time']));

		/** @var Tournaments $Tournaments */
		if( $request['enrollment_type'] == 'online' ){
			$request['enrollment_fee'] = $request['online_enrollment_fee'];
		}
		$Tournaments = Tournaments::create($request->all());
		$last_inserted_sport_id = 0;
		$id= $Tournaments->id ;
		$last_inserted_sport_id = $Tournaments->id;
		$user_id= Auth::user()->id;
		//Upload Photos
		$albumID = 1;//Default album if no album is not selected.
		$coverPic = 1;
		if(isset($input['album_id']) && $input['album_id'])
			$albumID = $input['album_id'];
		if(isset($input['cover_pic']) && $input['cover_pic'])
			$coverPic = $input['cover_pic'];
		Helper::uploadPhotos($request['filelist_photos'],config('constants.PHOTO_PATH.TOURNAMENT'),$last_inserted_sport_id,$albumID,$coverPic,config('constants.PHOTO.TOURNAMENT_LOGO'),$user_id);
		Helper::uploadPhotos($request['filelist_gallery'], config('constants.PHOTO_PATH.TOURNAMENT_PROFILE'),$last_inserted_sport_id, $albumID, $coverPic, config('constants.PHOTO.TOURNAMENT_PROFILE'), $user_id);
		$logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.TOURNAMENT_LOGO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->where('is_album_cover',1)->get()->toArray();
		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				Tournaments::where('id', $id)->update(['logo' => $l['url']]);
				//echo $l['url'];exit;
			}

		}
		$count=$Tournaments->groups_number;
		$tournament_type = $Tournaments->type;


		if($tournament_type == 'league' || $tournament_type == 'multistage' )
		{
			if($last_inserted_sport_id > 0)
			{
				for($i=1;$i<=$count;$i++)
				{

					$tournamentsgroups = new TournamentGroups();
					$tournamentsgroups->tournament_id = $last_inserted_sport_id;
					$tournamentsgroups->name = 'GROUP'.$i;;
					$tournamentsgroups->save();
				}
			}
		}

		//insert managed tournaments in user statistics START
		$user_id = Auth::user()->id;//login user id
		$tournament_id = $Tournaments->id;//last inserted tournament id
		if($tournament_id>0)
		{
			$this->updateManagedTournamentsStatistics($user_id,$tournament_id);
		}
		//END managed tournaments

		return redirect()->back()->with('status', trans('message.tournament.create'));
	}
	//create parent tournament
	public function createParentTournament($request)
	{
		$request['name'] = !empty($request['name'])?$request['name']:'';
		$request['owner_id'] = Auth::user()->id;
		$request['created_by'] = Auth::user()->id;
		$request['contact_number'] = !empty($request['contact_number'])?$request['contact_number']:'';
		$request['logo'] = !empty($request['logo'])?$request['logo']:'';
		$request['alternate_contact_number'] = !empty($request['alternate_contact_number'])?$request['alternate_contact_number']:'';
		$request['email'] = !empty($request['email'])?$request['email']:'';
		$request['description'] = !empty($request['description'])?$request['description']:'';
		$request['manager_id'] = !empty($request['managerId'])?$request['managerId']:'';
		$TournamentParent = TournamentParent::create($request->all());


		if (is_numeric($request['organization_group_id'])) {
				$TournamentParent->orgGroups()
				->attach(Request::input('organization_group_id'));
		}

		$last_inserted_sport_id = 0;
		$last_inserted_sport_id = $TournamentParent->id;
		$user_id= Auth::user()->id;
		//Upload Photos
		$albumID = 1;//Default album if no album is not selected.
		$coverPic = 1;

		if($request->has('filelist_photos')){

		if(isset($input['album_id']) && $input['album_id'])
			$albumID = $input['album_id'];
		if(isset($input['cover_pic']) && $input['cover_pic'])
			$coverPic = $input['cover_pic'];
		Helper::uploadPhotos($request['filelist_photos'],config('constants.PHOTO_PATH.TOURNAMENT'),$last_inserted_sport_id,$albumID,$coverPic,config('constants.PHOTO.TOURNAMENT_LOGO'),$user_id);

		$logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.TOURNAMENT_LOGO'))->where('imageable_id',  $last_inserted_sport_id )->where('user_id', Auth::user()->id)->where('is_album_cover',1)->get()->toArray();
		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				TournamentParent::where('id', $last_inserted_sport_id)->update(['logo' => $l['url']]);
			}

		}
	}
		//return redirect()->back()->with('status', trans('message.tournament.create'));
		return redirect()->route('tournaments.edit', [$last_inserted_sport_id])->with('status', trans('message.tournament.create'));
	}
	public function getUsers()
	{
	    $organization_id = \Request::get('organization_id',false);
        $user_name = Request::get('term');
        $users = User::where('users.name','LIKE','%'.$user_name.'%')->select(['users.id','users.name'])->limit(50);
        if ($organization_id){
           // $users->leftJoin('organization','organization.user_id','=','users.id');
            // $users->where('organization.id',$organization_id);

            $users->join('organization_staffs',function($join){
                $join->on('organization_staffs.user_id','=','users.id');
            });
            $users->where('organization_staffs.organization_id',$organization_id);
        }

        $users = $users ->get();
        $results = $users->map(function($user){
            return ['id'=>$user->id,'value'=>$user->name];
        });
		return Response::json($results);
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CreateTournamentRequest $request, $id)
    {
        
 
		     

		$input_val = Request::all();
       // echo "<pre>"; print_r($input_val); echo "</pre>"; exit;    


		//dd($input_val);
		// dd(date("H:i:s", strtotime($input_val['reg_opening_time'])));


		if(!empty($request['isParent']) && $request['isParent']=='yes')
		{

			return $this->updateParentTournament($request,$id);
		}
		$manager_id = Tournaments::where('id', $id)->pluck('manager_id');
		$country_id = !empty($request['country'])?$request['country']:config('constants.COUNTRY_INDIA');
		//$request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
		$request['country'] = Country::where('id', $country_id)->first()->country_name;
		$request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
		$request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
		$request['location']=trim($location,",");
		//$request['start_date']=date('Y-m-d', strtotime($request['start_date']));
		$request['start_date']=Helper::storeDate($request['start_date']);
		//$request['end_date']=date('Y-m-d', strtotime($request['end_date']));
		$request['end_date']=Helper::storeDate($request['end_date']);
		$request['created_by'] = Auth::user()->id;
		$request['status'] = 1;

		$request['manager_id'] = !empty($request['user_id'])?$request['user_id']:$manager_id;
		$request['match_type'] = !empty($request['match_type'])?$request['match_type']:'';
		$request['player_type'] = !empty($request['player_type'])?$request['player_type']:'';
		if($request->input('facility_response')!=''&& $request->input('facility_response_name')!='')
		{
			$request['facility_id'] = $request->input('facility_response');
			$request['facility_name'] =$request->input('facility_response_name');
		}
		else
		{
			$request['facility_id'] = NULL;
		}
		// new vendor details and form data
		if(!empty($request['account_holder_name']) && 
			!empty($request['account_number']) &&
			!empty($request['bank_name']) && 
			!empty($request['branch']) &&
			!empty($request['ifsc'])
		 ){
		 	$vendor = new VendorBankAccounts();
			$vendor_bank_account_id = $vendor->saveBankDetails(
				$request['account_holder_name'],
				$request['account_number'],
				$request['bank_name'],
				$request['branch'],
				$request['ifsc'],
				Auth::user()->id
			);
			if($vendor_bank_account_id){
				$request['vendor_bank_account_id'] = $vendor_bank_account_id;
			}
		}
		$request['reg_opening_date']=Helper::storeDate($request['reg_opening_date']);
		$request['reg_closing_date']=Helper::storeDate($request['reg_closing_date']);
        $request['reg_opening_time'] = !empty($request['reg_opening_time'])?$request['reg_opening_time']:'00:00:00';
		$request['reg_closing_time'] = !empty($request['reg_closing_time'])?$request['reg_closing_time']:'00:00:00';
		
		if( $request['enrollment_type'] == 'online' ){
			$request['enrollment_fee'] = $request['online_enrollment_fee'];
		}
		/////

		if(!empty($request['filelist_file_upload'])) {

           //  /*--files moving from temp directory to attachments directory--*/
             $image_moved=Helper::moveImage($request['filelist_file_upload'],$vendor_bank_account_id);
           /*--files moving from temp directory to attachments directory--*/
          
        }  

       $request['reg_opening_time']=date("H:i:s", strtotime($request['reg_opening_time']));
       $request['reg_closing_time'] =date("H:i:s", strtotime($request['reg_closing_time']));

      if(!empty($request['is_sold_out']) && $request['is_sold_out']=='1')
		{
          $request['is_sold_out']=1;
			
		} else{
			$request['is_sold_out']=0;
		}

		Tournaments::whereId($id)->update($request->except(['_method','_token','facility_response','facility_response_name','files','filelist_photos','filelist_gallery','jfiler-items-exclude-files-0','user_id','account_holder_name','account_number','bank_name','branch','ifsc','filelist_file_upload','online_enrollment_fee','agree']));
		if(!empty($request['filelist_photos'])) {
			Photo::where(['imageable_id'=>$id,'imageable_type' => config('constants.PHOTO.TOURNAMENT_LOGO')])->update(['is_album_cover' => 0]);
			//Upload Photos
			$albumID = 1;//Default album if no album is not selected.
			$coverPic = 1;
			$user_id= Auth::user()->id;
			if(isset($input['album_id']) && $input['album_id'])
				$albumID = $input['album_id'];
			if(isset($input['cover_pic']) && $input['cover_pic'])
				$coverPic = $input['cover_pic'];
			Helper::uploadPhotos($request['filelist_photos'],config('constants.PHOTO_PATH.TOURNAMENT'),$id,$albumID,$coverPic,config('constants.PHOTO.TOURNAMENT_LOGO'),$user_id);
			//End Upload Photos


           //  /*--files moving from temp directory to attachments directory--*/
           //   $image_moved=Helper::moveImage($request['filelist_file_upload'],$vendor_bank_account_id);
           //  //cho "<pre>"; print_r($image_moved);echo "</pre>"; die;
           // /*--files moving from temp directory to attachments directory--*/



		}
		if (!empty($request['filelist_gallery'])) {
			//Photo::where(['user_id' => Auth::user()->id, 'imageable_type' => config('constants.PHOTO.TOURNAMENT_PROFILE')])->update(['is_album_cover' => 0]);
			//Upload Photos
			$albumID = 1;//Default album if no album is not selected.
			$coverPic = 1;
			$user_id= Auth::user()->id;
			if(isset($input['album_id']) && $input['album_id'])
				$albumID = $input['album_id'];
			if(isset($input['cover_pic']) && $input['cover_pic'])
				$coverPic = $input['cover_pic'];
			Helper::uploadPhotos($request['filelist_gallery'], config('constants.PHOTO_PATH.TOURNAMENT_PROFILE'), $id, $albumID, $coverPic, config('constants.PHOTO.TOURNAMENT_PROFILE'), $user_id);

           







			//End Upload Photos
		}
		$logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.TOURNAMENT_LOGO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->where('is_album_cover',1)->get()->toArray();

		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				Tournaments::where('id', $id)->update(['logo' => $l['url']]);
			}

		}

		return redirect()->back()->with('status', trans('message.tournament.update'));
	}

	//update parent tournament
	public function updateParentTournament($request,$id)
	{
		
		$manager_id = TournamentParent::where('id', $id)->pluck('manager_id');
		//$request = Request::all();
		$request['manager_id'] = !empty($request['managerId'])?$request['managerId']:$manager_id;

        /** @var TournamentParent $tournamentParent */
        $tournamentParent = TournamentParent::findOrFail($id);

		$tournamentParent->update(($request->except(['_method','_token','files','filelist_photos','isParent','manager_name','managerId','filelist_gallery','jfiler-items-exclude-files-0', 'organization_group_id'])));

		if($request->input('organization_group_id')!='')
             $tournamentParent->orgGroups()->sync($request->input('organization_group_id'));

		if (!empty($request['filelist_photos'])) {
			Photo::where(['imageable_id'=>$id, 'imageable_type' => config('constants.PHOTO.TOURNAMENT_LOGO')])->update(['is_album_cover' => 0]);
			//Upload Photos
			$albumID = 1;//Default album if no album is not selected.
			$coverPic = 1;
			$user_id= Auth::user()->id;
			if(isset($input['album_id']) && $input['album_id'])
				$albumID = $input['album_id'];
			if(isset($input['cover_pic']) && $input['cover_pic'])
				$coverPic = $input['cover_pic'];
			Helper::uploadPhotos($request['filelist_photos'],config('constants.PHOTO_PATH.TOURNAMENT'),$id,$albumID,$coverPic,config('constants.PHOTO.TOURNAMENT_LOGO'),$user_id);
			//End Upload Photos
		}
		$logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.TOURNAMENT_LOGO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->get()->toArray();

		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				TournamentParent::where('id', $id)->update(['logo' => $l['url']]);
			}

		}
		return redirect()->back()->with('status', trans('message.tournament.update'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
	public function tournaments_list(Requests $request)
	{

		$facility = $request['term'];
		$facility_array = Facilityprofile::select('id','name')->where('name','LIKE','%'.$facility.'%')->get();
		$results = array();
		foreach($facility_array as $facilitylist_array  )
		{
			$results[] = ['id' =>  $facilitylist_array->id, 'value' =>  $facilitylist_array->name];
		}
		return Response::json($results);
	}
	public function edit($id)
	{

        

		$enum = config('constants.ENUM.TOURNAMENTS.TYPE');
		$schedule_type_enum = config('constants.ENUM.TOURNAMENTS.SCHEDULE_TYPE');
		$game_type_enum = config('constants.ENUM.TOURNAMENTS.GAME_TYPE');
		$enrollment_type = config('constants.ENUM.TOURNAMENTS.ENROLLMENT_TYPE');
//		$sports = Sport::where('isactive','=',1)->lists('sports_name', 'id')->all();
		$sports = Helper::getDevelopedSport(1,1);
		/** @var TournamentParent $tournament */
		$tournament = TournamentParent::findOrFail($id);
//		$tournament_data = Tournaments::findOrFail($id);

		$loginUserId = Auth::user()->id;

		if(!($loginUserId==$tournament['owner_id'] || $loginUserId==$tournament['manager_id']))
		{
			Session::flash('message', trans('message.tournament.access_msg'));
			return redirect("/tournaments");
		}

		$subTournaments = Tournaments::where('tournament_parent_id',$id)->get(['id', 'name', 'created_by', 'sports_id','manager_id','tournament_parent_id']);

		$subTournamentArray = array();
		if(!empty($subTournaments) && count($subTournaments)>0)
			$subTournamentArray = $subTournaments->toArray();

		$sub_tour_details = array();
		foreach($subTournamentArray as $tournaments)
		{
			$sub_tour_details[$tournaments['id']]['name'] =  $tournaments['name'];
			$sportsName = Sport::where('id', $tournaments['sports_id'])->first(['sports_name']);
			if (count($sportsName))
				$sub_tour_details[$tournaments['id']]['sports_name'] = $sportsName->sports_name;
			else
				$sub_tour_details[$tournaments['id']]['sports_name'] = '';
			$userName = User::where('id', $tournaments['created_by'])->first(['name']);
			if (count($userName))
				$sub_tour_details[$tournaments['id']]['user_name'] = $userName->name;
			else
				$sub_tour_details[$tournaments['id']]['user_name'] = '';
			$sport_logo = Photo::select()->where('imageable_id', $tournaments['sports_id'])->where('imageable_type', config('constants.PHOTO.SPORT_LOGO'))->orderBy('id', 'desc')->first(); //get sport logo
			if(count($sport_logo))
				$sub_tour_details[$tournaments['id']]['sports_logo'] = $sport_logo['url'];
			else
				$sub_tour_details[$tournaments['id']]['sports_logo'] = '';
		}

		$manager_name='';
		$managerName = User::where('id',$tournament['manager_id'])->first(['name']);
		if(count($managerName))
			$manager_name = $managerName->name;

		$match_types = array();
		$player_types = array();

		//get sport name
		$sport_name = Sport::where('id', $tournament['sports_id'])->pluck('sports_name');
		// if($isOwner)
		// {
		//building match types array
		$sport_name = !empty($sport_name) ? $sport_name : '';
		$match_types = Helper::getMatchTypes(strtoupper($sport_name));
		//building player types array
		foreach (config('constants.ENUM.SCHEDULE.PLAYER_TYPE') as $key => $val) {
			$player_types[$key] = $val;
		}
		$isOwner = 0;
		if(!empty($subTournaments) && count($subTournaments)>0)
		{
			if(Helper::isTournamentOwner($subTournaments[0]['manager_id'],$subTournaments[0]['tournament_parent_id'])) {
				$isOwner=1;
			}
        }		
			
	       $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
	       $states = State::where('country_id', $tournament->country_id)->orderBy('state_name')->lists('state_name', 'id')->all();
	       $cities = City::where('state_id',  $tournament->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
        $organizations = Auth::user()->organizations()->lists('name', 'id')->all();

        $orgGroups = [];
        if ($tournament->organization) {
            $orgGroups =
                $tournament->organization->groups->lists('name', 'id')->all();
        }

        $orgGroupIds = $tournament->orgGroups->lists('id')->all();
        // new data vendor bank accounts
        $vendorBankAccounts = VendorBankAccounts::where('user_id',$loginUserId)->get();
        $disclaimer_content = BasicSettings::where('id',1)->value('description');

       

        return view('tournaments.tournamentsedit',
            compact('tournament'))->with([
            'sports'              => ['' => 'Select Sport'] + $sports,
            'id'                  => $id,
            'countries'           => ['' => 'Select Country'] + $countries,
            'states'              => ['' => 'Select State'] + $states,
            'cities'              => ['' => 'Select City'] + $cities,
            'enum'                => ['' => 'Tournament Type'] + $enum,
            'tournament'          => $tournament,
            'type'                => 'create',
            'roletype'            => 'user',
            'schedule_type_enum'  => $schedule_type_enum,
            'enrollment_type'     => $enrollment_type,
            'game_type_enum'	  => $game_type_enum,
            'subTournamentArray'  => $sub_tour_details,
            'parent_id'           => $id,
            'tournament_name'     => $tournament['name'],
            'logo'                => $tournament['logo'],
            'parent_manager_name' => $manager_name,
            'player_types'        => ['' => 'Select Player Type'] + $player_types,
            'match_types'         => ['' => 'Select Match Type'] + $match_types,
            'isOwner'             => $isOwner,
            'loginUserId'         => $loginUserId,
            'organization'        => ['' => 'Select Organization'] + $organizations,
            'groupsList'         => ['' => 'Select Team Group'] + $orgGroups,
            'groupId'         => $orgGroupIds,
            'vendorBankAccounts' => $vendorBankAccounts,
          //  'online_enrollment_fee' => $tournament_data->enrollment_fee,
             'online_enrollment_fee' => '',
             'disclaimer_content' => $disclaimer_content
        ]);
    }


	public function subTournamentEdit()
	{

		$id=Request::get('id');
		$enum = config('constants.ENUM.TOURNAMENTS.TYPE');
		$schedule_type_enum = config('constants.ENUM.TOURNAMENTS.SCHEDULE_TYPE');
		$game_type_enum = config('constants.ENUM.TOURNAMENTS.GAME_TYPE');
//		   $sports = Sport::where('isactive','=',1)->lists('sports_name', 'id')->all();
		$sports = Helper::getDevelopedSport(1,1);
		$tournament = Tournaments::findOrFail($id);
		$tournament->start_date = Helper::displayDate($tournament->start_date);
		$tournament->end_date = Helper::displayDate($tournament->end_date);


		$tournament->reg_opening_date = Helper::displayDate($tournament->reg_opening_date);
		$tournament->reg_closing_date = Helper::displayDate($tournament->reg_closing_date);
		//get tournament group count
		$tournamentGroupCount = TournamentGroups::where('tournament_id',$tournament['id'])->count();

		$isOwner = 0;
		if(Helper::isTournamentOwner($tournament['manager_id'],$tournament['tournament_parent_id'])) {
			$isOwner=1;
		}
		if(!$isOwner)
		{
			Session::flash('message', trans('message.tournament.access_msg'));
			return redirect("/tournaments");
		}
		//get count of schedule matched on the tournament
		$matchScheduleCount = MatchSchedule::where('tournament_id', $id)->count();

		$sportsName = Sport::where('id', $tournament['sports_id'])->first(['sports_name']);
		$sportName='';
		if(count($sportsName)>0)
			$sportName = $sportsName->sports_name;
		//building match types array
		$matchTypes = Helper::getMatchTypes(strtoupper($sportName));
		$playerTypes = array();
		//building player types array
		foreach (config('constants.ENUM.SCHEDULE.PLAYER_TYPE') as $key => $val) {
			$playerTypes[$key] = $val;
		}

		$manager_name='';
		$managerName = User::where('id',$tournament['manager_id'])->first(['name']);
		if(count($managerName))
			$manager_name = $managerName->name;


		$match_types = array();
		$player_types = array();

		//get sport name
		$sport_name = Sport::where('id', $tournament['sports_id'])->pluck('sports_name');
		// if($isOwner)
		// {
		//building match types array
		$sport_name = !empty($sport_name) ? $sport_name : '';
		$match_types = Helper::getMatchTypes(strtoupper($sport_name));
		//building player types array
		foreach (config('constants.ENUM.SCHEDULE.PLAYER_TYPE') as $key => $val) {
			$player_types[$key] = $val;
		}

		$vendorBankAccounts = VendorBankAccounts::where('user_id',Auth::user()->id)->get();
		$disclaimer_content = BasicSettings::where('id',1)->value('description');

		

		$countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
		$states = State::where('country_id', $tournament->country_id)->orderBy('state_name')->lists('state_name', 'id')->all();
		$cities = City::where('state_id',  $tournament->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
		return view('tournaments.edit',compact('tournament'))->with(array('sports'=> [''=>'Select Sport']+$sports,'id'=>$id,'countries' =>  [''=>'Select Country']+$countries,'states' =>  [''=>'Select State']+$states,'cities' =>  [''=>'Select City']+$cities,'enum'=>['' => 'Tournament Type'] + $enum,'tournament'=>$tournament,'type'=>'edit','roletype'=>'user',
			'schedule_type_enum'=>$schedule_type_enum,
			'game_type_enum' 	=>$game_type_enum,
                        'enrollment_type' => config('constants.ENUM.TOURNAMENTS.ENROLLMENT_TYPE'),
			'parent_id'=>$id,'tournament_name'=>$tournament['name'],'logo'=>$tournament['logo'],'manager_name'=>$manager_name,'matchTypes'=>$matchTypes,'playerTypes'=>$playerTypes,'match_types'=>['' => 'Select Match Type'] +$match_types,'player_types'=>['' => 'Select Player Type'] +$player_types,'matchScheduleCount'=>$matchScheduleCount,'tournamentGroupCount'=>$tournamentGroupCount,'vendorBankAccounts'=>$vendorBankAccounts,'online_enrollment_fee'=>$tournament->enrollment_fee,'disclaimer_content'=>$disclaimer_content));
	}
	//function to display tournament groups
	public function groups($tournament_id,$type='', $from_api=false) {

       


		$tournaments           = Tournaments::with('groups')->where('id', '=', $tournament_id)->get(); //get tournaments which having the type as league


		$tournament_type       = $tournaments[0]['type'];
		$team_details          = array();
		$tournamentObj 		   = Tournaments::find($tournament_id);
		$match_details         = array();
		$add_score_link        = array();
		$match_startdate_array = array();
		$match_count           = array();
		$team_stats   = [];   // team stats array containing team wise stats needed for its calculation
		$net_run_rate = [];   // net run rate array

		$this->settings($tournament_id);

		foreach ($tournaments as $tournament)
		{
			foreach ($tournament->groups as $groups)
			{
				$group_id    = $groups->id;
				$teamDetails = TournamentGroupTeams::select()->where('tournament_group_id', $group_id)->orderBy('points', 'desc')->get();
                
				if (count((array) $teamDetails) > 0)
					$team_details[$group_id] = $teamDetails->toArray(); //get tournament group teams

 
				//get team match count
				if (!empty($team_details[$group_id]))
				{
					foreach ($team_details[$group_id] as $key=>$scheduled_teams)
					{
						$teamId                          = $scheduled_teams['team_id'];
						$match_count[$group_id][$teamId] = MatchSchedule::where('tournament_id', $tournament_id)->where('tournament_group_id', $group_id)
							->where('match_status', 'completed')->where(function($query) use ($teamId) {
								$query->where('a_id', $teamId)->orWhere('b_id', $teamId)->orWhere('player_or_team_ids', 'like', "%$teamId%");
							})->count();

				
					$match_count_details=Helper::getMatchGroupDetails($tournament_id, $group_id, $scheduled_teams['team_id']);

					//calculate ga, gf and tie.
						$scheduled_teams['tie']=$match_count_details['tie'];
			     			if(in_array($tournaments[0]['sports_id'], [3,4,5,6,2,3,7,13,14,15,16,17,11])){
			     				
									$scheduled_teams['ga']=$match_count_details['ga'];
									$scheduled_teams['gf']=$match_count_details['gf'];
							 }

							// Archery 

							 if(in_array($tournaments[0]['sports_id'], [18])){
							 		for($i=1; $i<=10; $i++){
							 			$scheduled_teams['pts_'.$i] = ScoreCard::get_archery_tournament_points($tournaments[0], $teamId, $i);
							 		}
							 		$scheduled_teams['ga']=0;
							 		$scheduled_teams['gf']=0;
							 		$scheduled_teams['points']=ScoreCard::get_archery_total_points($tournaments[0], $teamId);
							 }




				 	$team_details[$group_id][$key]=$scheduled_teams;

				 		}

				 	if(in_array($tournaments[0]['sports_id'], [3,4,5,6,2,3,7,13,14,15,16,17,11,18])){
				 		$team_details[$group_id]=$this->sortGroupTeams($team_details[$group_id]);
				 	}


				}

				$matchDetails = MatchSchedule::select()->where('tournament_id', $tournament_id)->where('tournament_group_id', $group_id)->orderby('match_start_date', 'desc')->orderby('match_start_time', 'desc')->get();
				if (count($matchDetails) > 0)
				{
					$match_details[$group_id] = $matchDetails->toArray(); //get tournament group teams match schedules
					//add score link conditions
					if (!empty($match_details[$group_id]))
					{
						foreach ($match_details[$group_id] as $matchdata)
						{
							// net run rate changes - start
							if (isset($matchdata['sports_id']) && $matchdata['sports_id'] == config('constants.SPORT_ID.Cricket') && !empty($matchdata['match_details']) && $matchdata['has_result'] == 1)
							{
								if ($matchdata['match_type'] == "t20")
								{
									$maxOverCount = 20;
								}
								else if ($matchdata['match_type'] == "odi")
								{
									$maxOverCount = 50;
								}
								else if ($matchdata['match_type'] == "test")
								{
									$maxOverCount = 90;
								}
								$match_stats = json_decode($matchdata['match_details'], true);

								$team_ids = array_keys($match_stats);

								foreach ($match_stats as $team_id => $team_stat)
								{
									if (empty($team_stats[$team_id]))
									{
										$team_stats[$team_id] = [];
										$team_stats[$team_id]['total_runs_scored'] = $team_stats[$team_id]['total_runs_conceded'] = 0;
										$team_stats[$team_id]['total_overs_faced'] = $team_stats[$team_id]['total_overs_bowled'] = 0;
									}
									$team_stats[$team_id]['total_runs_scored']      += (int) $team_stat['fst_ing_score'] + (int) $team_stat['scnd_ing_score'];
									$team_stats[$team_id]['total_overs_faced']      += ( ( (int) $team_stat['fst_ing_wkt'] == 10 ) ? (float) $maxOverCount : (float) $team_stat['fst_ing_overs'] ) + ( ( (int) $team_stat['scnd_ing_wkt'] == 10 ) ? (float) $maxOverCount : (float) $team_stat['scnd_ing_overs'] );
									$other_team_id                                  = ($team_ids[0] == $team_id) ? $team_ids[1] : $team_ids[0];
									$team_stats[$team_id]['total_runs_conceded']    += (int) $match_stats[$other_team_id]['fst_ing_score'] + (int) $match_stats[$other_team_id]['scnd_ing_score'];
									$team_stats[$team_id]['total_overs_bowled']     += ( ( (int) $match_stats[$other_team_id]['fst_ing_wkt'] == 10 ) ? (float) $maxOverCount : (float) $match_stats[$other_team_id]['fst_ing_overs'] ) + ( ( (int) $match_stats[$other_team_id]['scnd_ing_wkt'] == 10 ) ? (float) $maxOverCount : (float) $match_stats[$other_team_id]['scnd_ing_overs'] );
								}
							}
							// net run rate changes - end

							$scheduledMatchStartDate = Carbon::createFromFormat('Y-m-d', $matchdata['match_start_date']);
							if ($matchdata['match_status'] == 'completed')
							{
								$add_score_link[$matchdata['id']] = trans('message.schedule.viewscore');
							}
							else if (Carbon::now()->gte($scheduledMatchStartDate))
							{
								$matchScheduleDetails = MatchSchedule::where('id', $matchdata['id'])->get();
								$scoreOwner           = Helper::isValidUserForScoreEnter($matchScheduleDetails->toArray());
								if ($scoreOwner)
								{
									$matchScheduleData['score_added_by'] = $matchScheduleDetails[0]['score_added_by'];
									$matchScheduleData['scoring_status'] = $matchScheduleDetails[0]['scoring_status'];
									//$add_score_link[$matchdata['id']] = trans('message.schedule.addscore');
									$add_score_link[$matchdata['id']]    = Helper::getCurrentScoringStatus($matchScheduleData);
								}
								else
								{
									$add_score_link[$matchdata['id']] = trans('message.schedule.viewscore');
								}
							}
							$schedule['match_start_date']            = $matchdata['match_start_date'];
							$schedule['match_start_time']            = $matchdata['match_start_time'];
							$match_startdate_array[$matchdata['id']] = Helper::getFormattedTimeStamp($schedule);
						}
					}
				}

				if (!empty($team_stats))
				{
					foreach ($team_stats as $team_id => $team_stat)
					{
						if ($team_stats[$team_id]['total_overs_faced'] > 0
							&& $team_stats[$team_id]['total_overs_bowled'] > 0)
						{
							$net_run_rate[$team_id] = ($team_stats[$team_id]['total_runs_scored'] / $team_stats[$team_id]['total_overs_faced']);
							$net_run_rate[$team_id] -= ($team_stats[$team_id]['total_runs_conceded'] / $team_stats[$team_id]['total_overs_bowled']);
							$net_run_rate[$team_id] = round($net_run_rate[$team_id], 3);
						}
					}
					unset($match_stats,$team_stats);
				}

				if($tournaments[0]['sports_id']==1){
					$team_details[$group_id] = $this->list_cricket($team_details[$group_id],$net_run_rate);

				}


				
			}
		}

		$sport_id        = $tournaments[0]['sports_id']; //sport id

		$schedule_type   = !empty($tournaments[0]['schedule_type']) ? $tournaments[0]['schedule_type'] : 'team'; //schedule type
		$team_name_array = array();
		$team_logo       = array();
		$user_name       = array();
		$user_profile    = array();
		if ($schedule_type == 'team')
		{
			$teams = Team::select('id', 'name')->where('sports_id', $sport_id)->get()->toArray(); //get teams
			foreach ($teams as $team)
			{
				$team_name_array[$team['id']] = $team['name']; //get team names
				$team_logo[$team['id']]       = Photo::select()->where('imageable_id', $team['id'])->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first(); //get team logo
			}
           
		}
		else
		{
			$users = User::select('id', 'name')->get()->toArray(); //if scheduled type is player
			foreach ($users as $user)
			{
				$user_name[$user['id']]    = $user['name']; //get team names
				$user_profile[$user['id']] = Photo::select()->where('imageable_id', $user['id'])->where('imageable_type', config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first(); //get team logo
			}

			
		}

		Helper::setMenuToSelect(4, 1);
		$lef_menu_condition = 'display_gallery';
		//getting states
		$countries          = Country::orderBy('country_name')->lists('country_name', 'id')->all();
		$states             = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();

		$match_types  = array();
		$player_types = array();

		//get sport name
		$sport_name  = Sport::where('id', $sport_id)->pluck('sports_name');
		// if($isOwner)
		// {
		//building match types array
		$sport_name  = !empty($sport_name) ? $sport_name : '';
		$match_types = Helper::getMatchTypes(strtoupper($sport_name));
		//building player types array
		foreach (config('constants.ENUM.SCHEDULE.PLAYER_TYPE') as $key => $val)
		{
			$player_types[$key] = $val;
		}
		// }
		//Start - Logic for final stage teams
		$isOwner           = 0;
		$lastRoundWinner   = 0;
		$firstRoundBracket = 0;
		$maxRoundNumber    = 1;
		$linkUrl           = '';
		if (Helper::isTournamentOwner($tournaments[0]['manager_id'], $tournaments[0]['tournament_parent_id']))
		{
			$isOwner = 1;
		}

		if (count($tournaments[0]['final_stage_teams']))
		{
			if ($schedule_type == 'team')
			{
				$scheduleTypeOne = 'scheduleteamone';
				$scheduleTypeTwo = 'scheduleteamtwo';
				$linkUrl         = '/team/members';
			}
			else
			{
				$scheduleTypeOne = 'scheduleuserone';
				$scheduleTypeTwo = 'scheduleusertwo';
				$linkUrl         = '/showsportprofile';
			}

			$matchScheduleData = MatchSchedule::with(array($scheduleTypeOne => function($q1) {
				$q1->select('id', 'name');
			},
				$scheduleTypeTwo   => function($q2) {
					$q2->select('id', 'name');
				}, $scheduleTypeOne . '.photos', $scheduleTypeTwo . '.photos'))
				->where('tournament_id', $tournament_id)->whereNull('tournament_group_id')
				->orderBy('tournament_round_number')
				->get(['id', 'tournament_id', 'tournament_round_number',
					'tournament_match_number', 'a_id', 'b_id', 'match_start_date',
					'winner_id', 'match_invite_status', 'looser_id',
					'match_status', 'match_type']);





			$lastRoundWinner   = intval(ceil(log($tournaments[0]['final_stage_teams'], 2)));
			$firstRoundBracket = intval(ceil($tournaments[0]['final_stage_teams'] / 2));
			for ($j = 1; $j <= $firstRoundBracket; $j++)
			{

				$firstRoundBracketArray[$j] = '';
			}

			if (count($matchScheduleData))
			{
				foreach ($matchScheduleData->toArray() as $key => $schedule)
				{
					if ($schedule['tournament_round_number'] == 1)
					{
						if (count($schedule[$scheduleTypeOne]['photos']))
						{
							$teamOneUrl                                       = array_collapse($schedule[$scheduleTypeOne]['photos']);
							$matchScheduleData[$key][$scheduleTypeOne]['url'] = $teamOneUrl['url'];
						}
						else
						{
							$teamOneUrl        = $matchScheduleData[$key][$scheduleTypeOne];
							$teamOneUrl['url'] = '';
						}

						if (count($schedule[$scheduleTypeTwo]['photos']))
						{
							$teamTwoUrl                                       = array_collapse($schedule[$scheduleTypeTwo]['photos']);
							$matchScheduleData[$key][$scheduleTypeTwo]['url'] = $teamTwoUrl['url'];
						}
						else
						{
							$teamTwoUrl        = $matchScheduleData[$key][$scheduleTypeTwo];
							$teamTwoUrl['url'] = '';
						}

						$matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);
						if (!empty($schedule['winner_id']) && $schedule['match_status'] == 'completed')
						{
							$matchScheduleData[$key]['winner_text'] = trans('message.schedule.matchstats');
						}
//                            else if (Carbon::now()->gte($matchStartDate) && $schedule['match_invite_status']=='accepted') {
						else if (Carbon::now()->gte($matchStartDate))
						{
							if ($isOwner)
							{
								$matchScheduleData[$key]['winner_text'] = trans('message.schedule.addscore');
							}
							else{
								$matchScheduleData[$key]['winner_text'] = trans('message.schedule.viewscore');
							}
						}
//                            $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toDayDateTimeString();
						$matchScheduleData[$key]['match_start_date'] = Helper::getFormattedTimeStamp($schedule);
						if ($schedule['match_type'] != 'other')
						{
							$matchScheduleData[$key]['match_type'] = $schedule['match_type'] == 'odi' ? '(' . strtoupper($schedule['match_type']) . ')' : '(' . ucfirst($schedule['match_type']) . ')';
						}
						else
						{
							$matchScheduleData[$key]['match_type'] = '';
						}
					}
				}

				$maxRoundNumber = $matchScheduleData->max('tournament_round_number');
				for ($i = 1; $i <= $maxRoundNumber; $i++)
				{
					$roundArray[$i] = $i;
				}


				for ($i = 1; $i <= $firstRoundBracket; $i++)
				{
					foreach ($matchScheduleData->toArray() as $roundKey => $matchschedule)
					{
						if ($matchschedule['tournament_match_number'] == $i && $matchschedule['tournament_round_number'] == 1)
						{
							$firstRoundBracketArray[$i] = $matchschedule;
						}
					}
				}


				//                        $maxRoundNumberArray = MatchSchedule::where('tournament_id',$tournament_id)
				//                                                 ->whereNull('tournament_group_id')
				//                                                 ->orderBy('id','desc')->take(1)->first(['id','tournament_round_number']);
				//                        $maxRoundNumber = 1;
				//                        if(count($maxRoundNumberArray)) {
				//                            $maxRoundNumber = $maxRoundNumberArray['tournament_round_number'];
				//                            for($i=1;$i<=$maxRoundNumberArray['tournament_round_number'];$i++) {
				//                                $roundArray[$i]=$i;
				//                            }
				//                        }

				$bracketTeamArray = self::getBracketTeams($tournament_id, $maxRoundNumber, $schedule_type, $isOwner);

				if (!empty($bracketTeamArray))
				{
					foreach ($bracketTeamArray as $bracketkey => $bracketTeam)
					{
						foreach ($bracketTeam as $team => $bracket)
						{
							if ($team == 'start_date' && $team !== 0)
							{
								unset($bracketTeamArray[$bracketkey][$team]);
							}
						}
					}
				}
			}

			$selectetdFinalStageTeams = explode(',', $tournaments[0]['final_stage_teams_ids']);
		}


		$requestedFinalTeams = [];
		// For displaying the teams added for final stage teams as well as the requested teams
		if ($tournament_type == 'knockout')
		{
			$tournamentFinalTeams = $this->getFinalStageAddedTeams($tournament_id, $schedule_type);

			$tournamentTeams      = $tournamentFinalTeams['requestedWithPhotos'];
			$requestedFinalTeams  = $this->getFinalStageRequests($tournament_id, $schedule_type, $tournamentFinalTeams['tournamentKnockoutTeamsIds']);
		}
		else
		{

			$tournamentTeamIds = $this->getTournamentTeams($tournament_id, $schedule_type);
			if (count($tournamentTeamIds))
			{
				$teamIDs = explode(',', trim($tournamentTeamIds, ','));
				if ($schedule_type == 'team')
				{
					if ($isOwner)
					{
						$tournamentTeams = Team::whereIn('id', $teamIDs)->orderBy('name')->lists('name', 'id')->all();
					}
					else
					{
						$tournamentTeams = Team::whereIn('id', $teamIDs)->orderBy('name')->get(['id',
							'name', 'logo']);
					}
				}
				else
				{
					if ($isOwner)
					{
						$tournamentTeams = User::whereIn('id', $teamIDs)->orderBy('name')->lists('name', 'id')->all();
					}
					else
					{
						$tournamentTeams = User::whereIn('id', $teamIDs)->orderBy('name')->get(['id',
							'name', 'logo']);
					}
				}
			}
		}
		//get requested teams
		$requestedTeams = $this->getTeamorUsers($tournament_id, $schedule_type);



		// End - Logic for final stage teams

		$byeArray     = [1 => 'Match', 2 => 'Bye'];
		$dispViewFlag = 'group';

		if ($tournament_type == 'league' || $tournament_type == 'multistage')
		{
			$dispViewFlag = 'group';
		}

		if ($tournament_type == 'knockout' || count($tournaments[0]['final_stage_teams']))
		{
			$dispViewFlag = 'final';
		}

		if ($type == 'group')
		{
			$dispViewFlag = 'group';
		}
		elseif ($type == 'final')
		{
			$dispViewFlag = 'final';
		}

		if (!$isOwner && $dispViewFlag == 'final')
		{
			if (!empty($tournaments[0]['final_stage_teams_ids']))
			{
				$teamIDs = explode(',', trim($tournaments[0]['final_stage_teams_ids'], ','));
				if ($schedule_type == 'team')
				{
					$tournamentTeams = Team::whereIn('id', $teamIDs)->orderBy('name')->get(['id',
						'name', 'logo']);
				}
				else
				{
					$tournamentTeams = User::whereIn('id', $teamIDs)->orderBy('name')->get(['id',
						'name', 'logo']);
				}
			}
		}

		$match_is_completed = 0; 
		//update Points; 
		if($lastRoundWinner){
			$first_position_model = MatchSchedule::whereTournamentId($tournament_id)
									  ->where('tournament_round_number', $lastRoundWinner)
									  ->where('tournament_match_number', 1)
									  ->where('match_status', 'completed')->first();
		    $second_position_model = MatchSchedule::whereTournamentId($tournament_id)
		    						  ->where('tournament_round_number', $lastRoundWinner)
		    						  ->where('tournament_match_number', 2)
		    						  ->where('match_status', 'completed')
		    						  ->first();

		    if($first_position_model){
		    	$first_position=$first_position_model->winner_id;
		    	$second_position=$first_position_model->looser_id;
		    		$match_is_completed =1; 
		    	if(!empty($tournamentObj->p_1)){
		    		TournamentGroupTeams::whereTournamentId($tournament_id)->whereTeamId($first_position)->update(['final_points'=>$tournamentObj->p_1]);
		    		TournamentFinalTeams::whereTournamentId($tournament_id)->whereTeamId($first_position)->update(['points'=>$tournamentObj->p_1]);
		    	}

		    	if(!empty($tournamentObj->p_2)){
		    		TournamentGroupTeams::whereTournamentId($tournament_id)->whereTeamId($second_position)->update(['final_points'=>$tournamentObj->p_2]);
		    		TournamentFinalTeams::whereTournamentId($tournament_id)->whereTeamId($second_position)->update(['points'=>$tournamentObj->p_3]);
		    	}

		    }

		    if($second_position_model){
		    	$third_position=$second_position_model->winner_id;
		    	$fourth_position=$second_position_model->looser_id;


		    	if(!empty($tournamentObj->p_3)){
		    		TournamentGroupTeams::whereTournamentId($tournament_id)->whereTeamId($third_position)->update(['final_points'=>$tournamentObj->p_3]);
		    		TournamentFinalTeams::whereTournamentId($tournament_id)->whereTeamId($third_position)->update(['points'=>$tournamentObj->p_3]);
		    	}

		    	if(!empty($tournamentObj->p_4)){
		    		TournamentGroupTeams::whereTournamentId($tournament_id)->whereTeamId($fourth_position)->update(['final_points'=>$tournamentObj->p_4]);
		    		TournamentFinalTeams::whereTournamentId($tournament_id)->whereTeamId($fourth_position)->update(['points'=>$tournamentObj->p_4]);
		    	}
		    }

		}
		


		//left menu
		$parent_tournamet_id = $tournaments[0]['tournament_parent_id'];
		$tournamentManagerId = $tournaments[0]['manager_id'];
		$left_menu_data      = Helper::getLeftMenuData($parent_tournamet_id, $tournamentManagerId, $tournaments);

		$follow_unfollow = Helper::checkFollowUnfollow(isset(Auth::user()->id)?Auth::user()->id:0, 'TOURNAMENT', $tournament_id);

        $final_stage_teams = [];
        if (isset($selectetdFinalStageTeams) && is_array($selectetdFinalStageTeams) && isset($tournamentTeams)) {
            if (is_array($tournamentTeams)) {
                $final_stage_teams = array_only($tournamentTeams, $selectetdFinalStageTeams);
            } else {
                if (is_a($tournamentTeams, Collection::class)) {
                    $final_stage_teams = array_only($tournamentTeams->lists('name','id')->toArray(),$selectetdFinalStageTeams);
                }
            }
        }

		if($from_api){

			return Response::json([
			'tournament'               => $tournaments,
			'team_details'             => $team_details,
			'net_run_rate'             => $net_run_rate,
			'tournament_id'            => $tournament_id,
			'lef_menu_condition'       => $lef_menu_condition,
			'action_id'                => $tournament_id,
			'match_details'            => $match_details,
			'team_name_array'          => $team_name_array,
			'team_logo'                => $team_logo,
			'user_name'                => $user_name,
			'user_profile'             => $user_profile,
			'schedule_type'            => $schedule_type,
			'tournament_type'          => $tournament_type,
			'tournamentDetails'        => $tournaments->toArray(),
			'team_name'                => '',
			'tournamentTeams'          => !empty($tournamentTeams) ? $tournamentTeams : [],
			'sports_id'                => $sport_id,
			'roundArray'               => !empty($roundArray) ? $roundArray : [],
			'scheduleTypeOne'          => !empty($scheduleTypeOne) ? $scheduleTypeOne : '',
			'scheduleTypeTwo'          => !empty($scheduleTypeTwo) ? $scheduleTypeTwo : '',
			'bracketTeamArray'         => !empty($bracketTeamArray) ? $bracketTeamArray : [],
			'isOwner'                  => $isOwner, 'lastRoundWinner'          => $lastRoundWinner,
			'firstRoundBracket'        => $firstRoundBracket,
			'firstRoundBracketArray'   => !empty($firstRoundBracketArray) ? $firstRoundBracketArray : [],
			'selectetdFinalStageTeams' => !empty($selectetdFinalStageTeams) ? $selectetdFinalStageTeams : [],
			'maxRoundNumber'           => $maxRoundNumber,
			'dispViewFlag'             => $dispViewFlag,
			'linkUrl'                  => $linkUrl, 'left_menu_data'           => $left_menu_data,
			'add_score_link'           => $add_score_link,
			'sport_name'               => $sport_name,
			'match_startdate_array'    => $match_startdate_array,
			'match_count'              => $match_count,
				]);

		}





		return view('tournaments.groups', array(
			'tournament'               => $tournaments,
			'team_details'             => $team_details,
			'net_run_rate'             => $net_run_rate,
			'tournament_id'            => $tournament_id,
			'lef_menu_condition'       => $lef_menu_condition,
			'action_id'                => $tournament_id,
			'match_details'            => $match_details,
			'team_name_array'          => $team_name_array,
			'team_logo'                => $team_logo,
			'user_name'                => $user_name,
			'user_profile'             => $user_profile,
			'schedule_type'            => $schedule_type,
			'tournament_type'          => $tournament_type,
			'tournamentDetails'        => $tournaments->toArray(),
            'final_stage_teams'        => $final_stage_teams,
			'team_name'                => '',
			'tournamentTeams'          => !empty($tournamentTeams) ? $tournamentTeams : [],
			'sports_id'                => $sport_id,
			'roundArray'               => !empty($roundArray) ? $roundArray : [],
			'scheduleTypeOne'          => !empty($scheduleTypeOne) ? $scheduleTypeOne : '',
			'scheduleTypeTwo'          => !empty($scheduleTypeTwo) ? $scheduleTypeTwo : '',
			'bracketTeamArray'         => !empty($bracketTeamArray) ? $bracketTeamArray : [],
			'isOwner'                  => $isOwner, 'lastRoundWinner'          => $lastRoundWinner,
			'firstRoundBracket'        => $firstRoundBracket,
			'firstRoundBracketArray'   => !empty($firstRoundBracketArray) ? $firstRoundBracketArray : [],
			'selectetdFinalStageTeams' => !empty($selectetdFinalStageTeams) ? $selectetdFinalStageTeams : [],
			'maxRoundNumber'           => $maxRoundNumber,
			'dispViewFlag'             => $dispViewFlag,
			'linkUrl'                  => $linkUrl, 'left_menu_data'           => $left_menu_data,
			'add_score_link'           => $add_score_link,
			'sport_name'               => $sport_name,
			'match_startdate_array'    => $match_startdate_array,
			'match_count'              => $match_count,
			'match_is_completed'	   => $match_is_completed
			
		))
			->with('match_types', ['' => 'Select Match Type'] + $match_types)
			->with('player_types', ['' => 'Select Player Type'] + $player_types)
			->with('requestedTeams', ['' => 'Select Team'] + $requestedTeams)
			->with('requestedFinalTeams', ['' => 'Select Team'] + $requestedFinalTeams)
			->with('countries', ['' => 'Select Country'] + $countries)
			->with('states', ['' => 'Select State'] + $states)
			->with('cities', ['' => 'Select City'] + array())
			->with('byeArray', $byeArray)
			->with('follow_unfollow', $follow_unfollow)
			->with('matchScheduleData', !empty($matchScheduleData) ? $matchScheduleData : []);
	}

	static function getBracketTeams($tournament_id, $maxRoundNumber, $scheduleType, $isOwner) {
		$bracketTeamArray = [];
		$match_ids=[];
		$k = 0;
		if ($maxRoundNumber > 1) {
			for ($i = 2; $i <= $maxRoundNumber; $i++) {

				$prevRoundMatchesCount = MatchSchedule::where('tournament_id', $tournament_id)->whereNull('tournament_group_id')
					->where('tournament_round_number', ($i - 1))->count();
//                            if ($prevRoundMatchesCount % 2 == 0) {
//                                $prevRoundMatchesCount = $prevRoundMatchesCount+1;
//                            }
				$currentRoundBracket = ceil($prevRoundMatchesCount / 2);
				for ($j = 0; $j < $currentRoundBracket; $j++) {
					$matchAlgorithmCount = ($j * 2) + 2;
					$bracketScheduleData = MatchSchedule::where('tournament_id', $tournament_id)
						->whereNull('tournament_group_id')
						->where('tournament_round_number', ($i - 1))
						->whereIN('tournament_match_number', [$matchAlgorithmCount - 1, $matchAlgorithmCount])
						->orderBy('tournament_match_number')
						->get(['id', 'tournament_id', 'tournament_round_number', 'tournament_match_number', 'a_id', 'b_id', 'match_start_date', 'winner_id','match_type']);
					$bracketTeamArray[$j]['start_date'] = '';
					if (count($bracketScheduleData)) {
						foreach ($bracketScheduleData->toArray() as $brkey => $brschedule) {
							$bracketTeamArray[$j][$k]['match_type'] = '';
							if (isset($brschedule['winner_id'])) {
								if($scheduleType=='team') {
									$winnerTeamDetails = Team::where('id', $brschedule['winner_id'])->first(['name','id']);
									$imageableType = config('constants.PHOTO.TEAM_PHOTO');
								}else{
									$winnerTeamDetails = User::where('id', $brschedule['winner_id'])->first(['name','id']);
									$imageableType = config('constants.PHOTO.USER_PHOTO');
								}
								if (count($winnerTeamDetails)) {
									$bracketTeamArray[$j][$k]['name'] = $winnerTeamDetails->name;
									$bracketTeamArray[$j][$k]['team_or_player_id'] = $winnerTeamDetails->id;
								} else {
									$bracketTeamArray[$j][$k]['name'] = '';
									$bracketTeamArray[$j][$k]['team_or_player_id'] = '';
								}
// Pushing Photos(Team Or Player)
								$winnerTeamPhoto = Photo::where('imageable_id', $brschedule['winner_id'])->where('imageable_type', $imageableType)->where('is_album_cover', '1')->first(['url']);
								if (count($winnerTeamPhoto)) {
									$bracketTeamArray[$j][$k]['url'] = $winnerTeamPhoto->url;
								} else {
									$bracketTeamArray[$j][$k]['url'] = '';
								}

								$bracketTeamArray[$j][$k]['tournament_round_number'] = $i;
								if (empty($bracketTeamArray[$j]['start_date'])) {
									$winnerId = $brschedule['winner_id'];
									$winnerTeamSchedule = MatchSchedule::where('tournament_id', $tournament_id)
										->whereNull('tournament_group_id')
										->where('tournament_round_number', ($i))
										->where(function($query) use ($winnerId) {
											$query->where('a_id', $winnerId)->orWhere('b_id', $winnerId);
										})
										->whereNotIn('id', $match_ids)										

//                                            ->where('a_id', $brschedule['winner_id'])->orWhere('b_id', $brschedule['winner_id'])
										->orderBy('id','asc')
										->first(['id', 'tournament_id', 'tournament_round_number', 'tournament_match_number', 'a_id', 'b_id', 'match_start_date', 'winner_id', 'match_invite_status', 'match_status','match_type']);
//                                    Helper::printQueries();
//                                            dd($winnerTeamSchedule);

// Building links(Add Score,ScoreCard, Edit)
									if (count($winnerTeamSchedule)) {

										$bracketTeamArray[$j][$k]['id'] = $winnerTeamSchedule->id;
										$match_ids[]=$winnerTeamSchedule->id;
										if(count($winnerTeamSchedule->match_start_date)) {
											$startDate = Carbon::createFromFormat('Y-m-d', $winnerTeamSchedule->match_start_date);
											if (!empty($winnerTeamSchedule->winner_id) && $winnerTeamSchedule->match_status=='completed') {
												$bracketTeamArray[$j][$k]['winner_text'] = trans('message.schedule.matchstats');
											}
//                                            else if (Carbon::now()->gte($startDate) && $winnerTeamSchedule->match_invite_status=='accepted') {
											else if (Carbon::now()->gte($startDate)) {
												if ($isOwner) {
													$bracketTeamArray[$j][$k]['winner_text'] = trans('message.schedule.addscore');
												}
											}
//                                            $bracketTeamArray[$j][$k]['match_start_date'] = $startDate->toDayDateTimeString();
											$bracketTeamArray[$j][$k]['match_start_date'] = Helper::getFormattedTimeStamp($winnerTeamSchedule);
											$bracketTeamArray[$j][$k]['schdule_id'] = $winnerTeamSchedule->id;
											if($winnerTeamSchedule->match_type!='other')
											{
												$bracketTeamArray[$j][$k]['match_type'] = $winnerTeamSchedule->match_type=='odi'?'('.strtoupper($winnerTeamSchedule->match_type).')':'('.ucfirst($winnerTeamSchedule->match_type).')';
											}
										}else{
											if ($isOwner) {
												$currentScheduleData = MatchSchedule::where('tournament_id', $tournament_id)
													->whereNull('tournament_group_id')
													->where('tournament_round_number', ($i))
													->where('tournament_match_number', ceil($brschedule['tournament_match_number'] / 2))
													->orderBy('tournament_match_number')
													->first(['id','match_type']);
//                                                $bracketTeamArray[$j][$k]['match_start_date']=  Carbon::now();
												$bracketTeamArray[$j][$k]['match_start_date']=  date(config('constants.DATE_FORMAT.VALIDATION_DATE_TIME_FORMAT'));
												$bracketTeamArray[$j][$k]['winner_text'] = 'edit';
												$bracketTeamArray[$j][$k]['schdule_id'] = $currentScheduleData['id'];
												if($currentScheduleData->match_type!='other')
												{
													$bracketTeamArray[$j][$k]['match_type'] = $currentScheduleData->match_type=='odi'?'('.strtoupper($currentScheduleData->match_type).')':'('.ucfirst($currentScheduleData->match_type).')';
												}
											}
										}
									}
									else
									{
										if ($isOwner) {


											$currentScheduleData = MatchSchedule::where('tournament_id', $tournament_id)
												->whereNull('tournament_group_id')
												->where('tournament_round_number', ($i))
												->where('tournament_match_number', ceil($brschedule['tournament_match_number'] / 2))
												->orderBy('tournament_match_number')								
												->first(['id','match_type']);

									$match_ids[]=$currentScheduleData['id'];
								 
//                                                $bracketTeamArray[$j][$k]['match_start_date']=  Carbon::now();
											$bracketTeamArray[$j][$k]['match_start_date']=  date(config('constants.DATE_FORMAT.VALIDATION_DATE_TIME_FORMAT'));
											$bracketTeamArray[$j][$k]['winner_text'] = 'edit';
											$bracketTeamArray[$j][$k]['schdule_id'] = $currentScheduleData['id'];
											//$bracketTeamArray[$j][$k]['id'] = $currentScheduleData['id'];
											if($currentScheduleData->match_type!='other')
											{
												$bracketTeamArray[$j][$k]['match_type'] = $currentScheduleData->match_type=='odi'?'('.strtoupper($currentScheduleData->match_type).')':'('.ucfirst($currentScheduleData->match_type).')';
											}
										}
									}
									$bracketTeamArray[$j]['start_date'] = 1;
								}
							} else {
								$bracketTeamArray[$j][$k]['name'] = '';
								$bracketTeamArray[$j][$k]['url'] = '';
								$bracketTeamArray[$j][$k]['schdule_id'] = '';
								$bracketTeamArray[$j][$k]['match_type'] = '';
							}
							$k++;
						}
					}
				}
			}
		}
 
  // die(json_encode($match_ids));

//                                            Helper::printQueries();
		return $bracketTeamArray;
	}

	//function to get teams related to given sports , tournament
	public function getSportTeams($sport_id,$tournament_id,$schedule_type='team')
	{
		$team = Request::get('term');//name for user or team
		$results = array();
		$team_ids_array=array();
		$group_teams = TournamentGroups::with('group_teams')->where('tournament_id',$tournament_id)->get();//get team ids which are already given to tournaments groups
		foreach($group_teams as $teams)
		{
			foreach($teams->group_teams as $team_id)
			{
				$team_ids_array[] = $team_id->team_id;
			}
		}
		if($schedule_type=='team')
		{
			$teams = Team::select('id','name','team_level')->where('sports_id',$sport_id)->where('name','LIKE','%'.$team.'%')
				->whereNotIn('id',$team_ids_array)->where('isactive',1)->get();//get teams
			foreach ($teams as $query)
			{
				$results[] = ['id' => $query->id, 'value' => $query->name.' ('.$query->team_level.')'];
			}
		}else
		{
			$teams = User::select('id','name')->where('name','LIKE','%'.$team.'%')->whereNotIn('id',$team_ids_array)->get();//get users if schedule type is general or player
			foreach ($teams as $query)
			{
				$results[] = ['id' => $query->id, 'value' => $query->name];
			}
		}



		return Response::json($results);
	}
	//function to add team to tournament group
	public function addteamtotournament()
	{
		$team_id = Request::get('response');
		$flag 	 = !empty(Request::get('flag'))?Request::get('flag'):null;
		$tournament_id = Request::get('tournament_id');//tournament group team count		
		$team_name = Request::get('team_name');

			if($flag=='invite'){
				$invite_player=new InvitePlayerController;
				$name = !empty(Request::get('name'))?Request::get('name'):null;
				$email = !empty(Request::get('email'))?Request::get('email'):'';
				$user=$invite_player->invitePlayerToTournament($tournament_id,$email, $name);
					if(!$user){
						return [
							'result'=>'error',
							'message'=>'This Email already exist!'
						];
					}	

				$team_id=[$user->id];		
				$team_name=$user->id;		
				
			}

		$team_id_array = array();
		if($team_id!='' && count($team_id ))
			$team_id_array = array_filter($team_id);
		
		
		$team_count = Request::get('team_count');//tournament group team count
		$group_id = Request::get('group_id');
		$team_details = array();
		$groupTeamsArray = array();
		$groupTeamsFinalArray = array();
		$team_details= TournamentGroupTeams::select()->where('tournament_group_id',$group_id)->get()->toArray();
		$exist_team_count = count($team_details);
		$selected_team_count = count($team_id_array);

		$schedule_type = Tournaments::where('id', $tournament_id)->pluck('schedule_type');

		if(count($team_id_array)>0 && ($exist_team_count+$selected_team_count)<=$team_count)
		{
			foreach($team_id_array as $teamId)
			{
				if($schedule_type=='team')
					$team_name = Team::where('id', $teamId)->pluck('name');
				else
					$team_name = User::where('id', $teamId)->pluck('name');			

	
				$TournamentGroups = new TournamentGroupTeams();
				$TournamentGroups->tournament_group_id = $group_id;
				$TournamentGroups->team_id = $teamId;
				$TournamentGroups->tournament_id = $tournament_id;
				$TournamentGroups->name = $team_name;
				$TournamentGroups->save();

				$last_inserted_id = $TournamentGroups->id;

				$teamDetailsArray = TournamentGroupTeams::select()->where('tournament_group_id', $group_id)->where('id',$last_inserted_id)->get();

				foreach($teamDetailsArray as $details)
				{
					$match = !empty($details->match_id)?$details->match_id:'';
					$won = !empty($details->won)?$details->won:'';
					$lost = !empty($details->lost)?$details->lost:'';
					$points = !empty($details->points)?$details->points:'';
					$groupTeamsArray = ['id' => $details->id,'tournament_group_id'=>$details->tournament_group_id,'match_id'=>$match,'name' => $details->name,'won'=>$won,'lost'=>$lost,'points'=>$points,'team_id'=>$details->team_id];
				}
				$groupTeamsFinalArray[] = $groupTeamsArray;
			}

			return Response()->json( $groupTeamsFinalArray);
		}else{
			//return Response()->json( array('fail' => trans('message.tournament.group_fail')) );
			return Response()->json( $groupTeamsFinalArray );
		}
	}
	//function to create tournament group
	public function insertTournamentGroup()
	{
		$tournament_id = Request::get('tournament_id');
		$group_numbers = Request::get('group_numbers');//ALREADY exist group number
		$group = Request::get('group');
		$new_groups = $group+$group_numbers;
		for($i=$group_numbers+1;$i<=$new_groups;$i++)// inserting groups
		{
			$TournamentGroups = new TournamentGroups();
			$TournamentGroups->tournament_id = $tournament_id;
			$TournamentGroups->name = 'GROUP'.$i;
			$TournamentGroups->save();
		}
		//Tournaments::where('id',$tournament_id)->update(array('groups_number'=>$new_groups));
		return Response()->json( array('success' => trans('message.tournament.group_success')) );
	}
	//function to delete team
	public function deleteteam($group_team_id)
	{
		TournamentGroupTeams::find($group_team_id)->delete();
		return Response()->json( array('success' => trans('message.tournament.team_delete')) );
		//return redirect()->back()->with('status', trans('message.tournament.team_delete'));
	}
	//function to delete,edit group of tournament
	public function editgroup($param,$group_id)
	{
		if($param=='edit')
		{
			$group = Request::get('group');
			TournamentGroups::where('id',$group_id)->update(array('name'=>$group));
			return Response()->json( array('success' => trans('message.tournament.group_edit')) );
		}else if($param=='delete')
		{
			TournamentGroups::find($group_id)->delete();
			TournamentGroupTeams::where('tournament_group_id',$group_id)->update(array('deleted_at'=>date('Y-m-d H:i:s')));
			//return redirect()->back()->with('status', trans('message.tournament.group_delete'));
			return Response()->json( array('success' => trans('message.tournament.group_delete')) );
		}

	}


	public function updateFinalStageTeams() {
		$tournamentId = Request::get('tournamentId');
		$finalStageTeams = Request::get('finalStageTeams');
		$flag = Request::get('flag');
		if(empty($tournamentId)) {
			$result['result']='error';
			return Response::json($result);
		}
		if($flag=='group' && empty($finalStageTeams)) {
			$result['result']='error';
			return Response::json($result);
		}
		$finalStageTeamIds = '';
		if($flag=='group') {
			Tournaments::where('id', $tournamentId)->update(['final_stage_teams'=>count($finalStageTeams),
				'final_stage_teams_ids'=>implode(',',$finalStageTeams)]);
		}else{
			$tournamentKnockoutTeams = TournamentFinalTeams::where('tournament_id',$tournamentId)->get(['team_id']);
			if(count($tournamentKnockoutTeams)) {
				$finalStageTeamIds = implode(',',array_flatten($tournamentKnockoutTeams->toArray()));
				Tournaments::where('id', $tournamentId)->update(['final_stage_teams'=>count($tournamentKnockoutTeams),
					'final_stage_teams_ids'=>$finalStageTeamIds]);
			}else{
				$result['result']='error';
				return Response::json($result);
			}
		}
//            $teamIds = $this->getTournamentTeams($tournamentId);
//            $result['teamIds'] = implode(',',$finalStageTeams);
//            $result['finalStageTeams'] = $finalStageTeams;
		$result['result']='success';
		return Response::json($result);

	}

	public function deleteFinalStageTeams() {
		$tournamentId = Request::get('tournamentId');
		$teamId = Request::get('teamId');
		$scheduleType = Request::get('scheduleType');
		if(empty($teamId) || empty($tournamentId) || empty($scheduleType)) {
			$result['result']='error';
			return Response::json($result);
		}
		TournamentFinalTeams::where('tournament_id', $tournamentId)->where('team_id',$teamId)->delete();
		$tournamentFinalTeams = $this->getFinalStageAddedTeams($tournamentId, $scheduleType);
		$result['requestedTeams'] = $this->getFinalStageRequests($tournamentId, $scheduleType,$tournamentFinalTeams['tournamentKnockoutTeamsIds']);
		$result['result']='success';
		return Response::json($result);

	}

	public function addRoundMatches() {
		$tournamentId = Request::get('tournamentId');
		$finalStageTeamsCount = Request::get('finalStageTeamsCount');
		$roundNumber = Request::get('roundNumber');
		$teamIds = Request::get('teamIds');
		if(count($teamIds)) {
			$teamIDs = explode(',',trim($teamIds,','));
		}
		$teams = Team::whereIn('id',$teamIDs)->orderBy('name')->lists('name', 'id')->all();
		return view('tournaments.rounds', ['teams' => ['' => 'Bye']+$teams, 'roundNumber'=>$roundNumber]);
	}


	//function to get my team details
	public function getteamdetails()
	{
		$team_id = Request::get('team_id');
		$tournament_id = Request::get('tournament_id');
		$search_team_ids = Request::get('search_team_ids');
		$tournament_round_number = Request::get('tournament_round_number');
		$tournament_group_id = Request::get('tournament_group_id');
		$schedule_type = Request::get('scheduletype');
		$search_team = Request::get('term');
		$tournamentDetails = Tournaments::where('id', '=', $tournament_id)->first(['schedule_type']);
		$schedule_type = $tournamentDetails->schedule_type;

		$results = array();
		$addedTeamIds='';

		// If group stage then fetch teams with tournament group id else with tournament id
		if(!empty($tournament_group_id)) {
			$teamIDs = $this->getTournamentGroupTeams($tournament_group_id);
		}else {
//                    $teamIDs = $this->getTournamentTeams($tournament_id);
			if(!empty($tournament_round_number)) {
				if($tournament_round_number<=1) {
					$final_stage_teams = Tournaments::where('id',$tournament_id)->first(['final_stage_teams_ids','final_stage_teams']);
					$teamIDs = $final_stage_teams->final_stage_teams_ids;
					/*$addedMatches = MatchSchedule::where('tournament_id',$tournament_id)->whereNull('tournament_group_id')
                             ->where('tournament_round_number',1)
                             ->get(['id','tournament_id','a_id','b_id']);
                    if(count($addedMatches)) {
                        foreach($addedMatches as $match) {
                            $addedTeamIds.=$match['a_id'].',';
                            if(!empty($match['b_id'])) {
                                $addedTeamIds.=$match['b_id'].',';
                            }
                        }
                    }*/
				}else {
					if(!empty($search_team_ids)) {
						$teamIDs = $search_team_ids;
					}
				}
			}
			if(empty($tournament_round_number) && empty($search_team_ids)) {
				$final_stage_teams = Tournaments::where('id',$tournament_id)->first(['final_stage_teams_ids','final_stage_teams']);
				$teamIDs = $final_stage_teams->final_stage_teams_ids;
			}
		}

		if(count($teamIDs)) {
			$teamIDs = explode(',',trim($teamIDs,','));
//                    if(!empty($addedTeamIds)) {
//                        $addedTeamIds = explode(',', rtrim($addedTeamIds, ','));
//                        $teamIDs = array_diff($teamIDs, $addedTeamIds);
//                    }
		}

		//if team id then remove the team id from the existing ids
		if(!empty($team_id))
		{
			$team_id_key = array_search($team_id,$teamIDs);
			if(isset($team_id_key) && $team_id_key >= 0)
			{
				unset($teamIDs[$team_id_key]);
			}
		}

		if($schedule_type == 'team') {
			$teams = Team::whereIn('id',$teamIDs)
				->where('name','LIKE','%'.$search_team.'%')
				->orderBy('name')->get(array('name', 'id', 'team_level'));
			if(!empty($teams))
			{
				foreach ($teams as $query)
				{
					$results[] = ['id' => $query->id, 'value' => $query->name.' ('.$query->team_level.')'];
				}
			}
		}else{
			$teams = User::whereIn('id',$teamIDs)
				->where('name','LIKE','%'.$search_team.'%')
				->orderBy('name')->get(array('name', 'id'));
			if(!empty($teams))
			{
				foreach ($teams as $query)
				{
					$results[] = ['id' => $query['id'], 'value' => $query['name']];
				}
			}
		}
		//if team is selected, get the teams with same sport

		return Response::json($results);
	}

	/**
	 * Get the teams for particular round
	 * Business Logic
	 * If round number is equal to 1 then Match number will be the last match of that particular round and the
	the search teams will be all the teams configured in group stage.
	 * If the round number is greater than one then Match number will be the last match of that particular round and the
	 * the search teams will be calculated based on algortihm
	 * eg: if first macth is to be added for second round then match count will be zero and the matchnumber will be
	 * (matchcount*2)+2 as bracket system is derived from that 16->8->4->2->1.
	 */
	public function getRoundTeams() {
		$roundNumber = Request::get('roundNumber');
		$tournamentId = Request::get('tournamentId');
		if(empty($roundNumber) || empty($tournamentId)) {
			$result['result']='error';
			return Response::json($result);
		}
		$searchTeamIds = '';
		$scheduleType = 'team';
		$tournamentDetails = array();
		//$matchNumber = $this->getTournamentRoundNumber($tournamentId,$roundNumber);
		if($roundNumber<=1) {
			$tournamentDetails = Tournaments::where('id',$tournamentId)->first();
			$searchTeamIds = $tournamentDetails->final_stage_teams_ids;
			$scheduleType = $tournamentDetails->schedule_type;


		}else{
			$currentMatchCount = $matchNumber-1;
			$matchAlgorithmCount =($currentMatchCount*2)+2;
			$searchTeamIdsCollection = MatchSchedule::where('tournament_id',$tournamentId)->whereNull('tournament_group_id')
				->where('tournament_round_number',($roundNumber-1))
				->whereIN('tournament_match_number',[$matchAlgorithmCount,$matchAlgorithmCount-1])
				->orderBy('id')
				->get(['id','tournament_id','tournament_round_number','tournament_match_number','a_id','b_id','match_start_date','winner_id']);
			if(count($searchTeamIdsCollection)) {
				foreach($searchTeamIdsCollection as $searchteam) {
					if(count($searchteam->a_id)) {
						$searchTeamIds.= $searchteam->a_id.',';
					}
					if(count($searchteam->b_id)) {
						$searchTeamIds.= $searchteam->b_id.',';
					}
				}
				$searchTeamIds = trim($searchTeamIds,',');
			}
		}

		$result['searchTeamIds'] = $searchTeamIds;
		//$result['matchNumber'] = $matchNumber;
		$result['scheduleType'] = $scheduleType;
		$result['tournamentDetails'] = $tournamentDetails;
		$result['result']='success';
		return Response::json($result);

	}

	function getTournamentGroupTeams($tournament_group_id) {
		$teams = TournamentGroupTeams::where('tournament_group_id',$tournament_group_id )->get(['team_id']);

//           dd($teams);
		$teamIDs = '';
		if(count($teams)) {
			foreach($teams as $team) {
                $teamIDs.= $team->team_id.',';
			}
		}
		if(!empty($teamIds)) {
			$teamIDs = trim($teamIDs,',');
		}
		return $teamIDs;
	}

	function getTournamentTeams($tournamentId) {
		$teams = TournamentGroups::with('group_teams'
		)->where('tournament_id',$tournamentId)->get();
		$teamIds = '';
		if(count($teams)) {
			foreach($teams as $team) {
				foreach($team->group_teams as $teamid) {
					$teamIds.= $teamid->team_id.',';
				}
			}
		}
		if(!empty($teamIds)) {
			$teamIds = trim($teamIds,',');
		}
		return $teamIds;

	}
	//function to update managed tournament in user statistics
	public function updateManagedTournamentsStatistics($user_id,$tournament_id)
	{
		//if null update the new value by appending a comma before and after else append the value to existing value with a comma after
		$existingManagedTournaments = UserStatistic::where('user_id', $user_id)->pluck('managing_tournaments');
		$updatedManagingTournamentString = null;
		if(!empty($existingManagedTournaments))
		{

			if(!Helper::isExist($existingManagedTournaments,$tournament_id))
			{
				$updatedManagingTournamentString = trim($existingManagedTournaments).$tournament_id.',';
			}
		}
		else
		{
			$updatedManagingTournamentString = ','.$tournament_id.',';
		}
		UserStatistic::where('user_id', $user_id)->update(['managing_tournaments'=>$updatedManagingTournamentString,'updated_at'=>Carbon::now()]);
	}

	function getTournamentRoundNumber($tournamentId,$roundNumber) {
		$roundMatches = MatchSchedule::where('tournament_id',$tournamentId)->where('tournament_round_number',$roundNumber)
			->orderBy('id','desc')->take(1)->first(['id','tournament_round_number','tournament_match_number','a_id','b_id']);
		if(count($roundMatches)) {
			$matchNumber = $roundMatches->tournament_match_number+1;
		}else {
			$matchNumber = 1;
		}
		return $matchNumber;
	}
	//function to delete tournaments delete
	function deleteTournamentSchedule()
	{
		$tournament_id = !empty(Request::get('tournament_id'))?Request::get('tournament_id'):'';
		$tournament_group_id = !empty(Request::get('tournament_group_id'))?Request::get('tournament_group_id'):'';
		$team_id = !empty(Request::get('team_id'))?Request::get('team_id'):'';
		if($tournament_id>0 && $tournament_group_id>0 && $team_id>0)
		{
			//get scheduled match count
			$matchScheduleCount = MatchSchedule::where(function($query) use ($team_id) {
				$query->where('a_id', $team_id)->orWhere('b_id', $team_id);
			})->where('tournament_id', $tournament_id)
				->where('tournament_group_id', $tournament_group_id)
				->where ('match_status','completed')
				->count();
			if($matchScheduleCount>0)
			{
				return Response()->json( array('msg' => trans('message.tournament.schedule_delete_fail')) );
			}
			//get match ids
			$matchScheduleIds = MatchSchedule::where(function($query) use ($team_id) {
				$query->where('a_id', $team_id)->orWhere('b_id', $team_id);
			})->where('tournament_id', $tournament_id)
				->where('tournament_group_id', $tournament_group_id)
				->where ('match_status','!=','completed')
				->get(['id']);
			if(!empty($matchScheduleIds) && count($matchScheduleIds)>0)
			{
				foreach($matchScheduleIds as $match_id)
				{
					MatchSchedule::find($match_id['id'])->delete();
				}
			}
			return Response()->json( array('msg' => 'true') );
		}else
		{
			return Response()->json( array('msg' => trans('message.tournament.team_delete_fail')) );
		}
	}
	//function to delete scheduled matches before delete group
	public function deleteGroupTeams()
	{
		$tournament_id = !empty(Request::get('tournament_id'))?Request::get('tournament_id'):'';
		$tournament_group_id = !empty(Request::get('tournament_group_id'))?Request::get('tournament_group_id'):'';
		$group_team_ids = TournamentGroupTeams::where('tournament_group_id',$tournament_group_id)->get(['team_id']);

		//get count of matches scheduled for group
		$scheduled_count = MatchSchedule::where('tournament_group_id',$tournament_group_id)->where('tournament_id',$tournament_id)->count();
		$success_count = 0;
		if(!empty($group_team_ids) && count($group_team_ids)>0 && $scheduled_count>0)
		{
			foreach($group_team_ids as $team_id)
			{
				$teamId = $team_id['team_id'];

				$matchScheduleCount = MatchSchedule::where(function($query) use ($teamId) {
					$query->where('a_id', $teamId)->orWhere('b_id', $teamId);
				})->where('tournament_id', $tournament_id)
					->where('tournament_group_id', $tournament_group_id)
					->where ('match_status','!=','completed')
					->count();
				if($matchScheduleCount>0)
					$success_count++;
				else
					$success_count=0;
			}

			if($scheduled_count==$success_count)
			{
				$deleted_count = 0;
				//get match id which is not completed
				$matchScheduleIds = MatchSchedule::where(function($query) use ($teamId) {
					$query->where('a_id', $teamId)->orWhere('b_id', $teamId);
				})->where('tournament_id', $tournament_id)
					->where('tournament_group_id', $tournament_group_id)
					->where ('match_status','!=','completed')
					->get(['id']);
				if(!empty($matchScheduleIds) && count($matchScheduleIds)>0)
				{
					foreach($matchScheduleIds as $match_id)
					{
						if(MatchSchedule::find($match_id['id'])->delete())
							$deleted_count++; // increase success count if schedule is deleted
						else
							$deleted_count=0;
					}
				}
				if($deleted_coun==$scheduled_count)
					return Response()->json( array('status' => 'true') );
				else
					return Response()->json( array('status' => 'false') );
			}
			else{
				return Response()->json( array('status' => 'false') );
			}

		}
		else
		{
			return Response()->json( array('status' => 'true') );
		}
	}
	public function getTeamorUsers($tournament_id,$schedule_type)
	{
		$type = config('constants.REQUEST_TYPE.PLAYER_TO_TOURNAMENT');
		if($schedule_type=='team')
		{
			$type = config('constants.REQUEST_TYPE.TEAM_TO_TOURNAMENT');
		}
		$team_or_user = Requestsmodel::where('to_id',$tournament_id)->where('type',$type)->where('action_status',1)->get(['from_id']);

		$team_or_user_array = array();
		$requestedTeams = array();

		if(count($team_or_user)>0)
		{
			foreach($team_or_user as $team)
			{
				$team_or_user_array[] = $team['from_id'];
			}

			$team_ids_array=array();
			$group_teams = TournamentGroups::with('group_teams')->where('tournament_id',$tournament_id)->get();//get team ids which are already given to tournaments groups
			foreach($group_teams as $teams)
			{
				foreach($teams->group_teams as $team_id)
				{
					$team_ids_array[] = $team_id->team_id;
				}
			}
			if($schedule_type == 'team') {
				$requestedTeams = Team::whereIn('id', $team_or_user_array)->whereNotIn('id',$team_ids_array)->orderBy('name')->lists('name', 'id')->all();
			}else{
				$requestedTeams = User::whereIn('id', $team_or_user_array)->whereNotIn('id',$team_ids_array)->orderBy('name')->lists('name', 'id')->all();
			}
		}
		return $requestedTeams;
	}
	//function to get requested teams to tournaments
	public function getRequestedTeams()
	{
		$tournament_id = Request::get('tournament_id');
		$schedule_type = Request::get('schedule_type');
		$requestedTeams = $this->getTeamorUsers($tournament_id,$schedule_type);
		return Response()->json($requestedTeams);
	}

	public function getFinalStageTeams($tournamentId)
	{
		$team = Request::get('term');//name for user or team
		$tournamentKnockoutTeamsIds = [];
		$results = [];
		$tournamentDetails = Tournaments::where('id',$tournamentId)->first(['sports_id','schedule_type']);
		$tournamentKnockoutTeams = TournamentFinalTeams::where('tournament_id',$tournamentId)->get(['team_id']);
		if(count($tournamentKnockoutTeams)) {
			$tournamentKnockoutTeamsIds = array_flatten($tournamentKnockoutTeams->toArray());
		}

		if($tournamentDetails->schedule_type=='team')
		{
			$teams = Team::select('id','name','team_level')->where('sports_id',$tournamentDetails->sports_id)
				->where('name','LIKE','%'.$team.'%')->whereNotIn('id',$tournamentKnockoutTeamsIds)
				->where('isactive',1)->get();//get teams
			foreach ($teams as $query)
			{
				$results[] = ['id' => $query->id, 'value' => $query->name.' ('.$query->team_level.')'];
			}
		}else
		{
			$teams = User::select('id','name')->where('name','LIKE','%'.$team.'%')->whereNotIn('id',$tournamentKnockoutTeamsIds)->get();//get users if schedule type is general or player
			foreach ($teams as $query)
			{
				$results[] = ['id' => $query->id, 'value' => $query->name];
			}
		}



		return Response::json($results);
	}

	public function addFinalStageTeams(){
		$tournamentId = Request::get('tournamentId');
		$teamId = Request::get('teamId');
		$scheduleType = Request::get('scheduleType');
		$flag = Request::get('flag');
		if(empty($teamId) || empty($tournamentId)|| empty($scheduleType)) {
			$result['result']='error';
			return Response::json($result);
		}
		if($flag=='auto') {
			$finalTeamsmodel = new TournamentFinalTeams();
			$finalTeamsmodel->tournament_id = $tournamentId;
			$finalTeamsmodel->team_id = $teamId;
			$finalTeamsmodel->save();
			$result['result']='success';
		}
		if($flag=='select'){
			foreach ($teamId as $team) {
				if(!empty($team)) {
					$finalTeamsArray[] = [
						'tournament_id' => $tournamentId,
						'team_id' => $team,
						'created_at' => Carbon::now(),
						'updated_at' => Carbon::now()
					];
				}
			}
			if(!empty($finalTeamsArray)) {
				TournamentFinalTeams::insert($finalTeamsArray);
			}
		}

		if($flag=='invite'){

			$invite_player=new InvitePlayerController;
			$name = !empty(Request::get('name'))?Request::get('name'):null;
			$email = !empty(Request::get('email'))?Request::get('email'):'';

			$user=$invite_player->invitePlayerToTournament($tournamentId,$email, $name);

			if(!$user){
				return [
					'result'=>'error',
					'message'=>'This Email already exist!'
				];

			}
			
				$finalTeamsmodel = new TournamentFinalTeams();
				$finalTeamsmodel->tournament_id = $tournamentId;
				$finalTeamsmodel->team_id = $user->id;
				$finalTeamsmodel->save();
				$result['result']='success';
			
			
		}

		$tournamentFinalTeams = $this->getFinalStageAddedTeams($tournamentId, $scheduleType);
		$result['tournamentTeams'] = View::make('tournaments.addedknocoutteams')
			->with(array('tournamentTeams'=>$tournamentFinalTeams['requestedWithPhotos'],'tournamentId'=>$tournamentId, 'scheduleType'=>$scheduleType))->render();
		$result['requestedTeams'] = $this->getFinalStageRequests($tournamentId, $scheduleType,$tournamentFinalTeams['tournamentKnockoutTeamsIds']);
		return Response::json($result);

	}

	public function getFinalStageAddedTeams($tournamentId,$schedule_type)
	{
		$tournamentKnockoutTeams = TournamentFinalTeams::where('tournament_id',$tournamentId)->get(['team_id']);
		$tournamentKnockoutTeamsIds =[];
		if(count($tournamentKnockoutTeams)) {
			$tournamentKnockoutTeamsIds = array_flatten($tournamentKnockoutTeams->toArray());
		}

		$requestedWithPhotos = array();
		if($schedule_type == 'team') {
			$requestedWithPhotos = Team::with(array('photos'))
				->whereIn('id', $tournamentKnockoutTeamsIds)->orderBy('name')->get(['id','name']);

		}else{
			$requestedWithPhotos = User::with(array('photos'))
				->whereIn('id', $tournamentKnockoutTeamsIds)->orderBy('name')->get(['id','name']);
		}

		if (count($requestedWithPhotos)) {
			foreach ($requestedWithPhotos->toArray() as $key => $requestedTeam)
			{
				if (count($requestedTeam['photos'])) {
					$teamUrl = array_collapse($requestedTeam['photos']);
					$requestedWithPhotos[$key]['url'] = $teamUrl['url'];
				} else {
					$teamUrl = $requestedWithPhotos[$key];
					$teamUrl['url'] = '';
				}
			}
		}
		return ['requestedWithPhotos'=>$requestedWithPhotos,'tournamentKnockoutTeamsIds'=>$tournamentKnockoutTeamsIds];
	}

	public function getFinalStageRequests($tournamentId,$schedule_type,$tournamentKnockoutTeamsIds)
	{
		if($schedule_type=='team')
		{
			$type = config('constants.REQUEST_TYPE.TEAM_TO_TOURNAMENT');
		}else {
			$type = config('constants.REQUEST_TYPE.PLAYER_TO_TOURNAMENT');
		}
		$team_or_user = Requestsmodel::where('to_id',$tournamentId)->where('type',$type)
			->where('action_status',1)->whereNotIn('from_id',$tournamentKnockoutTeamsIds)->get(['from_id']);

		$requestedWithPhotos = array();
		$requests = array();
		$teamOrUserIds = array();
		if(count($team_or_user)>0)
		{
			$teamOrUserIds = array_flatten($team_or_user->toArray());
		}

		if($schedule_type == 'team') {
			$requests = Team::whereIn('id', $teamOrUserIds)->orderBy('name')->lists('name', 'id')->all();
		}else{
			$requests = User::whereIn('id', $teamOrUserIds)->orderBy('name')->lists('name', 'id')->all();
		}

		return $requests;
	}

	public function getSubTournamentDetails($tournamentId) {
		$tournamentDetails = Tournaments::where('id',$tournamentId)->first();
		$results = array('tournamentDetails' => $tournamentDetails);
		return Response::json($results);
	}

	function getTournamentDetails($id)
	{

		$lef_menu_condition = 'display_gallery';

		$tournamentInfo= Tournaments::select()->where('id',$id)->get();
		$sport_name="";
		$manager_name="";
		$managerarray=array();
		// $Sportsarray=array();
		if(count($tournamentInfo))
		{
			$tournamentInfo=$tournamentInfo->toArray();
			$sports_id=$tournamentInfo[0]['sports_id'];
			$manager_id=$tournamentInfo[0]['manager_id'];
			$Sportsarray= Sport::where('id',$sports_id)->get(['id', 'sports_name'])->toArray();
			if($manager_id>0)
			{
				$managerarray= User::where('id',$manager_id)->get(['id', 'name'])->toArray();
			}

		}
		if(!empty($Sportsarray))
		{
			$sport_name=$Sportsarray[0]['sports_name'];
		}
		if(!empty($managerarray))
		{
			$manager_name=$managerarray[0]['name'];
		}
		$left_menu_data = array();
		$left_menu_data = Helper::getLeftMenuData($tournamentInfo[0]['tournament_parent_id'],$tournamentInfo[0]['manager_id'],$tournamentInfo);
		
	
		return view('tournaments.tournamentsdetails')->with(array( 'tournamentInfo'=>$tournamentInfo,'action_id'=>$id,'left_menu_data'=>$left_menu_data, 'tournament_id' => $id, 'lef_menu_condition'=> $lef_menu_condition, 'tournament_type' => $tournamentInfo[0]['type'],'sport_name'=>$sport_name,'manager_name'=> $manager_name));
	}



	public function playerStanding($tournament_id, $from_api=false){
		$left_menu_data = array();
		

		$id=$tournament_id;
		$action_id=$tournament_id;

		$tournamentInfo= Tournaments::whereId($tournament_id)->get();
		
		$tournament_type=$tournamentInfo[0]['type'];
		$tournament_parent_id=$tournamentInfo[0]['tournament_parent_id'];

		$lef_menu_condition = 'display_gallery';
		$left_menu_data = Helper::getLeftMenuData($tournamentInfo[0]['tournament_parent_id'],$tournamentInfo[0]['manager_id'],$tournamentInfo);

		$sport_id=$tournamentInfo[0]['sports_id'];
		$sport_name=strtolower(Sport::find($sport_id)->sports_name);

		$player_standing=$this->getPlayerStanding($sport_id, $tournament_id);
	
		if($from_api) return Response::json($player_standing);
		

			return view('tournaments.player_standing', compact(
					'tournamentInfo','lef_menu_condition',
					'left_menu_data', 'tournamentInfo','id', 
					'tournament_id' , 'action_id',
					'tournament_parent_id', 'tournament_type', 
					'player_standing', 'sport_id', 'sport_name', 'follow_array'));
	}

	public function getPlayerStanding($sport_id, $tournament_id){
			switch ($sport_id) {
				case  4: 			//soccer

					$player=SoccerPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'soccer_player_matchwise_stats.match_id')
							->join('teams', 'teams.id','=', 'soccer_player_matchwise_stats.team_id')
							->join('users', 'users.id', '=', 'soccer_player_matchwise_stats.user_id')
							->where('match_schedules.tournament_id', $tournament_id)
							->select('soccer_player_matchwise_stats.*','users.*')							
							->selectRaw('sum(yellow_cards) as yellow_cards')
							->selectRaw('count(match_schedules.id) as matches')
							->selectRaw('sum(red_cards) as red_cards')
							->selectRaw('sum(goals_scored) as goals')
							->orderBy('goals', 'desc')
							->groupBy('user_id')
							->get();
					# code...
					break;

				case 11:	//hockey
						$player=HockeyPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'hockey_player_matchwise_stats.match_id')
							->join('teams', 'teams.id','=', 'hockey_player_matchwise_stats.team_id')
							->join('users', 'users.id', '=', 'hockey_player_matchwise_stats.user_id')
							->where('match_schedules.tournament_id', $tournament_id)
							->select('hockey_player_matchwise_stats.*','users.*')							
							->selectRaw('sum(yellow_cards) as yellow_cards')
							->selectRaw('count(match_schedules.id) as matches')
							->selectRaw('sum(red_cards) as red_cards')
							->selectRaw('sum(goals_scored) as goals')
							->orderBy('goals', 'desc')
							->groupBy('user_id')
							->get();
				break;

				case 6:	//basketball
						$player=BasketballPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'basketball_player_matchwise_stats.match_id')
							->join('teams', 'teams.id','=', 'basketball_player_matchwise_stats.team_id')
							->join('users', 'users.id', '=', 'basketball_player_matchwise_stats.user_id')
							->where('match_schedules.tournament_id', $tournament_id)
							->select('basketball_player_matchwise_stats.*','users.*')							
							->selectRaw('sum(points_1) as points_1')
							->selectRaw('count(match_schedules.id) as matches')
							->selectRaw('sum(points_2) as points_2')
							->selectRaw('sum(points_3) as points_3')
							->selectRaw('sum(total_points) as total_points')
							->selectRaw('sum(fouls) as fouls')
							->orderBy('total_points', 'desc')
							->groupBy('user_id')
							->get();


				break;

					case 14; //kabaddi
						$player=KabaddiPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'kabaddi_player_matchwise_stats.match_id')
							->join('teams', 'teams.id','=', 'kabaddi_player_matchwise_stats.team_id')
							->join('users', 'users.id', '=', 'kabaddi_player_matchwise_stats.user_id')
							->where('match_schedules.tournament_id', $tournament_id)
							->select('kabaddi_player_matchwise_stats.*','users.*')							
							->selectRaw('sum(points_1) as points_1')
							->selectRaw('count(match_schedules.id) as matches')
							->selectRaw('sum(points_2) as points_2')
							->selectRaw('sum(points_3) as points_3')
							->selectRaw('sum(total_points) as total_points')
							->selectRaw('sum(fouls) as fouls')
							->orderBy('total_points', 'desc')
							->groupBy('user_id')
							->get();


				break;


				case 16:	//water polo
						$player=WaterpoloPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'waterpolo_player_matchwise_stats.match_id')
							->join('teams', 'teams.id','=', 'waterpolo_player_matchwise_stats.team_id')
							->join('users', 'users.id', '=', 'waterpolo_player_matchwise_stats.user_id')
							->where('match_schedules.tournament_id', $tournament_id)
							->select('waterpolo_player_matchwise_stats.*','users.*')							
							->selectRaw('sum(points_1) as points_1')
							->selectRaw('count(match_schedules.id) as matches')							
							->selectRaw('sum(total_points) as total_points')
							->selectRaw('sum(fouls) as fouls')
							->orderBy('total_points', 'desc')
							->groupBy('user_id')
							->get();


				break;

				case 18:	//water polo
						$player=ArcheryPlayerStats::join('match_schedules', 'match_schedules.id', '=', 'archery_player_stats.match_id')
							->join('teams', 'teams.id','=', 'archery_player_stats.team_id')
							->join('users', 'users.id', '=', 'archery_player_stats.user_id')
							->where('match_schedules.tournament_id', $tournament_id)
							->select('archery_player_stats.*','users.*', 'teams.*')						
							
							->selectRaw('count(match_schedules.id) as matches')							
							->selectRaw('sum(total) as total_points')
					
							->orderBy('total_points', 'desc')
							->groupBy('user_id')
							->get();


				break;
				case 1:	//cricket
						
					$player=[];
						$player['batting']=CricketPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'cricket_player_matchwise_stats.match_id')
							->join('teams', 'teams.id','=', 'cricket_player_matchwise_stats.team_id')
							->join('users', 'users.id', '=', 'cricket_player_matchwise_stats.user_id')
							->where('match_schedules.tournament_id', $tournament_id)
							->select('cricket_player_matchwise_stats.*','users.*')	
							->selectRaw('count(innings) as innings_bat')
							->selectRaw('sum(totalruns) as totalruns')
							->selectRaw('sum(balls_played) as balls_played')
							->selectRaw('sum(fifties) as fifties')
							->selectRaw('sum(hundreds) as hundreds')
							->selectRaw('sum(fours) as fours')
							->selectRaw('sum(sixes) as sixes')
							->selectRaw('sum(IF(bat_status="notout", 1, 0) + IF(out_as="not_out",1,0)) as notouts')
							->selectRaw('((sum(totalruns) / sum(balls_played) ) * 100) as strikerates')
							->selectRaw('max(totalruns) as highscore')
							->orderBy('totalruns', 'desc')
							->groupBy('user_id')						
							->get(); 
							
						
					$player['bowling']=CricketPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'cricket_player_matchwise_stats.match_id')
							->join('teams', 'teams.id','=', 'cricket_player_matchwise_stats.team_id')
							->join('users', 'users.id', '=', 'cricket_player_matchwise_stats.user_id')
							->where('match_schedules.tournament_id', $tournament_id)
							->select('cricket_player_matchwise_stats.*','users.*')

							->selectRaw('count(DISTINCT(match_id)) as matches')							
							->selectRaw('sum(wickets) as wickets')
							->selectRaw('sum(runs_conceded) as runs_conceded')
							->selectRaw('sum(overs_bowled) as overs_bowled')
							->selectRaw('SUM(innings) innings_bowl')
							->selectRaw('count(innings) as innings_bowled')
							->selectRaw('CAST(AVG(average_bowl) AS DECIMAL(10,2))  average_bowl')
							->selectRaw('CAST(AVG(ecomony) AS DECIMAL(10,2)) ecomony')							
							->orderBy('wickets', 'desc')
							->orderBy('ecomony','asc')
							->groupBy('user_id')							
							->get();

					$player['fielding'] = CricketPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'cricket_player_matchwise_stats.match_id')
							->join('team_players', 'team_players.user_id','=','cricket_player_matchwise_stats.fielder_id')						
							->join('teams', 'teams.id','=', 'team_players.team_id')
							->leftjoin('tournament_group_teams','tournament_group_teams.team_id','=','teams.id')
							->leftjoin('tournament_final_teams','tournament_final_teams.team_id','=','teams.id')	
							->where('tournament_group_teams.tournament_id',$tournament_id)							
							->orwhere('tournament_final_teams.tournament_id',$tournament_id)
							//->where('teams.sports_id','=',1)
							->join('users', 'users.id', '=', 'cricket_player_matchwise_stats.fielder_id')
							->where('match_schedules.tournament_id', $tournament_id)
							->select('cricket_player_matchwise_stats.*','users.*')
							->selectRaw('count(DISTINCT(cricket_player_matchwise_stats.match_id)) as matches')		
							->selectRaw('sum(IF(out_as="caught", 1, 0)) as caught')
								->selectRaw('count(innings) as innings_bowled')
							->selectRaw('sum(IF(out_as="stumped", 1, 0)) as stumped')
							->selectRaw('sum(IF(out_as="run_out", 1, 0)) as run_out')
							->selectRaw('sum(IF(out_as="run_out", 1, 0) + IF(out_as="stumped", 1, 0) +IF(out_as="caught", 1, 0) ) as total')
							->selectRaw('teams.name as fielder_team_name')
							->selectRaw('teams.id as fielder_team_id')
							->orderBy('total','desc')
							->groupBy('fielder_id')	
							->groupBy('team_players.id')
							//->groupBy('out_as')					
							->get(); 
							




					

						return $player;

				break;

				
				default:
					# code...
				$player=[];
					break;
			}

			return $player;
	}


	public function sortGroupTeams($group_teams=[]){
			$lenght=count($group_teams);

			for ($i=0; $i<$lenght; $i++) {
					for($j=0; $j<$lenght; $j++){

						//die(json_encode($group_teams[$i]));
							$ga_points=$group_teams[$i]['gf'];
				 			$points=$group_teams[$i]['points'];

				 			$next_ga_points=$group_teams[$j]['gf'];
				 			$next_points=$group_teams[$j]['points'];

				 			$gf_points=$group_teams[$i]['ga'];
				 			$next_gf_points=$group_teams[$j]['ga'];

				 			if( $points == $next_points){
				 					if($next_ga_points<$ga_points){
				 							$temp_team=$group_teams[$i];
				 							$group_teams[$i]=$group_teams[$j];
				 							$group_teams[$j]=$temp_team;
				 					}

				 					if($next_ga_points==$ga_points){
				 						if($next_gf_points>$gf_points){
				 							$temp_team=$group_teams[$i];
				 							$group_teams[$i]=$group_teams[$j];
				 							$group_teams[$j]=$temp_team;
				 						}
				 					}
				 			}else{

				 				if($next_points<$points){
				 					$temp_team=$group_teams[$i];
				 							$group_teams[$i]=$group_teams[$j];
				 							$group_teams[$j]=$temp_team;
				 				}
				 			}

					}
				 
			}

			return $group_teams;
	}

	public function list_cricket($group_teams, $team_stats){
			$lenght=count($group_teams);

			foreach($group_teams as  $key_team=>$team){
					$team['gf']=0;
					$team['ga']=0;

					//dd($team);

					$team_id = $team['team_id'];

					if(isset($team_stats[$team_id])){
						$team['gf'] = $team_stats[$team_id];
					}

					$group_teams[$key_team] = $team;
			}

		
			return $group_teams = $this->sortGroupTeams($group_teams);
	}


	

	public function settings($tournament_id){
		$t_settings=Settings::where('tournament_id', $tournament_id)->first();
		$t_model = Tournaments::find($tournament_id);

		$tournament=$t_model;
		$sports_name = strtolower($t_model->sport->sports_name);

		if(count($t_settings)) $settings=$t_settings->settings;
		else{
			 $settings=json_encode(config('constants.SPORTS_PREFERENCES.'.$t_model->sports_id));
                $tmp    = new Settings;
                $tmp->tournament_id=$tournament_id;
                $tmp->sports_id     = $t_model->sports_id;
                $tmp->settings      = $settings;
                $tmp->save();
		}

		$settings=json_decode($settings);

		if($tournament->matches->count()){
			$readonly = 'readonly';
		}
		else {
			$readonly = '';
		}


		return view('tournaments.settings.'.$sports_name, compact('settings','tournament', 'readonly') );
	}

	public function updateSettings($tournament_id){
		$t_settings=Settings::where('tournament_id', $tournament_id)->first();
		$settings=json_encode(Request::all());
		$t_settings->settings=$settings;
		$t_settings->has_setup_details=1;
		$t_settings->save();

		$tournament=Tournaments::find($tournament_id);
		$tournament->p_1 = Request::get('p_1');
		$tournament->p_2 = Request::get('p_2');
		$tournament->p_3 = Request::get('p_3');
		$tournament->p_4 = Request::get('p_4');
		$tournament->save();
		return redirect()->back();

	}

    public function rubberEdit(){
	$id 	= Request::get('id');
	$rubber = MatchScheduleRubber::find($id);

		//check if match has started
	if(!empty($rubber->match_details)) $readonly='disabled';
	else $readonly='';


	$match_types = array();
	$player_types = array();

	$match_types = Helper::getMatchTypes(strtoupper($rubber->sport->sports_name));
	foreach (config('constants.ENUM.SCHEDULE.PLAYER_TYPE') as $key => $val) {
		$player_types[$key] = $val;
	}

	if($rubber->schedule_type=='team'){
		$team_a=$rubber->scheduleteamone;
		$team_b=$rubber->scheduleteamtwo;
	}
	else{
		$team_a=$rubber->scheduleuserone;
		$team_b=$rubber->scheduleusertwo;
	}


return view('tournaments.edit_rubber', compact('rubber', 'team_a', 'team_b', 'match_types', 'player_types', 'readonly'));
}

	// Update rubber schedule

	public function addRubber($match_id){
		\App\Http\Controllers\User\ScheduleController::insertGroupRubber($match_id);

		return $this->rubberUpdateSchedule(null, $match_id);
	}

	public function rubberUpdateSchedule($id, $match_id=false){

		if($match_id!=false){
			$match=MatchSchedule::find($match_id);
		}
		else{
			$request = Request::all();
			$rubber = MatchScheduleRubber::find($id);
			
			$rubber->match_start_time=empty($request['match_start_time'])?$rubber->match_start_time:Helper::storeDate($request['match_start_time'],'time');
			$rubber->match_start_date=empty($request['match_start_date'])?$rubber->match_start_date:Helper::storeDate($request['match_start_date'],'date');
			$rubber->match_category=empty($request['player_type'])?$rubber->match_category:$request['player_type'];
			$rubber->match_type=empty($request['match_type'])?$rubber->match_type:$request['match_type'];
			$rubber->match_location=empty($request['venue'])?$rubber->match_location:$request['venue'];
			$rubber->save();
			$match=$rubber->match;
		}

		$team_logo       = array();
		$user_name       = array();
		$user_profile    = array();
		$team_name_array = array();
		if ($match->schedule_type == 'team')
		{
			$teams = Team::select('id', 'name')->where('sports_id', $match->sports_id)->get()->toArray(); //get teams
			foreach ($teams as $team)
			{
				$team_name_array[$team['id']] = $team['name']; //get team names
				$team_logo[$team['id']]       = Photo::select()->where('imageable_id', $team['id'])->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first(); //get team logo
			}
		}
		else
		{
			$users = User::select('id', 'name')->get()->toArray(); //if scheduled type is player
			foreach ($users as $user)
			{
				$user_name[$user['id']]    = $user['name']; //get team names
				$user_profile[$user['id']] = Photo::select()->where('imageable_id', $user['id'])->where('imageable_type', config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first(); //get team logo
			}
		}

		$isOwner = 0;
		
		if(Helper::isTournamentOwner($match->tournament->manager_id,$match->tournament->tournament_parent_id)) {
				$isOwner=1;
	    }
       	

		return view('tournaments.sub_match_schedules_rubber', compact('match', 'team_logo', 'team_name_array', 'isOwner') );

	}

	public function endTournament($id){
		$tournament = Tournaments::find($id);
		$tournament->group_is_ended = 1; 
		$tournament->save();


		if(in_array($tournament->sports_id, [18])){
			 
			 if($tournament->schedule_type=='individual'){
			 	$team_stats = ArcheryPlayerStats::whereTournamentId($tournament->id)->groupBy('user_id')->select('*')->selectRaw('sum(total) as total')->orderBy('total', 'desc')->take(3)->get(['*']);			 	
			 }
			 else{
			 	$team_stats = ArcheryTeamStats::whereTournamentId($tournament->id)->select('*')->selectRaw('sum(total) as total')->orderBy('total','desc')->groupBy('team_id')->take(3)->get(['*']);
			 }

			// return $team_stats;

			 foreach ($team_stats as $key => $value) {

			 		if($tournament->schedule_type=='individual'){
			 			$team_statistics = ArcheryStatistic::where('user_id', $value->user_id)->first();
				 			if(!$team_statistics){
				 				$team_statistics = new ArcheryStatistic;
				 				$team_statistics->user_id = $value->user_id;
				 				$team_statistics->save();
				 			}
			 		}
			 		else{
			 			$team_statistics = ArcheryStatistic::where('team_id', $value->team_id)->first();
			 			if(!$team_statistics){
			 				$team_statistics = new ArcheryStatistic;
			 				$team_statistics->team_id = $value->team_id;
			 				$team_statistics->save();
			 			}
			 		}

			 				$team_statistics->events++;

			 			if($key==0){
			 				$team_statistics->first++;
			 			}
			 			if($key==1){
			 				$team_statistics->second++;
			 			}
			 			if($key==2){
			 				$team_statistics->third++;
			 			}

			 		$team_statistics->save();
			 	}
			 }

		Helper::updateOrganizationTeamsPoints($id);
		
		return redirect()->back()->with('message', 'Group Closed');
	}
	// tournament new updates
	public function saveTournamentEnrollData(Requests\EnrolmentSetupRequest $request, $id){


		$request['reg_opening_date']=Helper::storeDate($request['reg_opening_date']);
		$request['reg_closing_date']=Helper::storeDate($request['reg_closing_date']);
		$request['reg_opening_time'] = !empty($request['reg_opening_time'])?$request['reg_opening_time']:'00:00:00';
		$request['reg_closing_time'] = !empty($request['reg_closing_time'])?$request['reg_closing_time']:'00:00:00';

		$request['total_enrollment'] = !empty($request['total_enrollment'])?$request['total_enrollment']:'';
		$request['min_enrollment'] = !empty($request['min_enrollment'])?$request['min_enrollment']:'';
		$request['max_enrollment'] = !empty($request['max_enrollment'])?$request['max_enrollment']:'';
		$request['terms_conditions'] = !empty($request['terms_conditions'])?$request['terms_conditions']:'';
		$request['is_sold_out'] = !empty($request['is_sold_out'])?$request['is_sold_out']:'';
		$request['account_holder_name'] = !empty($request['account_holder_name'])?$request['account_holder_name']:'';
		$request['account_number'] = !empty($request['account_number'])?$request['account_number']:'';
		$request['bank_name'] = !empty($request['bank_name'])?$request['bank_name']:'';
		$request['branch'] = !empty($request['bank_name'])?$request['branch']:'';
		$request['ifsc'] = !empty($request['bank_name'])?$request['ifsc']:'';
		Tournaments::whereId($id)->update($request);
	}


/*-----------------------------event registration with payment--------------------------*/
    
    public function eventregistration($id){
          $tournment_enrollment_type=Tournaments::where('id',$id)->value('enrollment_type');
          $country_id=Tournaments::where('id',$id)->value('country_id');
          $tournament_data=Tournaments::with('logo')->where('id',$id)->first();
          
          if($tournment_enrollment_type!='online'){
          	 return redirect('tournaments')->withErrors(['Select an event with enrollment type online']);
          }

          $parent_tournamet_id = Tournaments::where('id',$id)->value('tournament_parent_id');
          $all_events = Tournaments::with('bankAccount')->where('tournament_parent_id',$parent_tournamet_id)->where('enrollment_type','online')->where('country_id',$country_id)->where('is_sold_out',0)->whereDate('reg_opening_date', '<=', date('Y-m-d'))->whereDate('reg_closing_date', '>=', date('Y-m-d'))->where('vendor_bank_account_id','!=',0)->get();
          
          $parent_tournamet_details = TournamentParent::where('id',$parent_tournamet_id)->first();
          
          if(Auth::user()) {
            $roletype='user';
          } else {
          	 $roletype='guest';
          }

         
         foreach($all_events as $key => $events) {
			$all_id[]=$events->id;
        	$alreday_registered=DB::table('request')->where('to_id',$events->id)->get();
        	$reg_count=count($alreday_registered);
        	if($reg_count >= $events->total_enrollment) {
             unset($all_events[$key]);
        	}
         }
        
        return view('tournaments.eventregistration',compact('all_events','parent_tournamet_details','tournament_data'))->with([
         	'roletype'=>$roletype,
         	]);
         

	}

	public function registrationdata() {

        Session::forget('cart_array');
    	$input = Request::all();
        $post=$input['data'];
		if (Auth::check()) {
           $user_id = Auth::user()->id;
        } else {
          $u_id=str_random(15);
          session(['user_id' => $u_id]);
          $user_id = session('user_id');
         
        }
        
        $carts = new Carts;
        $cart_data=array();
        $i=0;
        $total_pay=0;

        foreach($post as $key=>$value) {
          if($value['count']!=0) {
            $cart_data[$i]['event_id']=$key;
            $cart_data[$i]['enrollment_fee']=Tournaments::where('id',$cart_data[$i]['event_id'])->value('enrollment_fee');
            $cart_data[$i]['match_type']=Tournaments::where('id',$cart_data[$i]['event_id'])->value('match_type');
            $cart_data[$i]['participant_count']=$value['count'];
			$tot_enrollment_count = Tournaments::where('id',$key)->value('total_enrollment');
       		$trn_name = Tournaments::where('id',$key)->value('name');
       		$alreday_singles_registered=DB::table('request')->where('to_id',$key)->get();
       		$alreday_singles_registered_count=count($alreday_singles_registered);
			$avail_registrations= $tot_enrollment_count-$alreday_singles_registered_count;
          		if($value['count'] > $avail_registrations) {
            		return redirect('tournaments')->withErrors(['Number of registration  for  event '.$trn_name.' exceeds the limit.Only '.$avail_registrations.' is available']);
             	}
			$pay=$cart_data[$i]['participant_count'] * $cart_data[$i]['enrollment_fee'];
            $total_pay+=$pay;
          }
         $i++;
        }
          
        if(count($cart_data) > 0 ) {
           $carts['user_id']=$user_id;
           $carts['total_payment']=$total_pay; 
           if($carts->save()) {
           	$cart_id=$carts->id;
           	$j=0;
           	$cart_details=array();
           	foreach($cart_data as $crt) {
           		$cart_details =CartDetails::create(['cart_id' => $cart_id,'event_id' => $crt['event_id'],'enrollment_fee' => $crt['enrollment_fee'],'match_type' => $crt['match_type'],'participant_count' => $crt['participant_count'],'registerd' => 0]);
            	$j++;
            }
			if (Auth::check()) {
               return redirect('tournaments/registerstep2/'. $cart_id);
            } else {
          	   return redirect('guest/tournaments/guestregisterstep2/'. $cart_id);
            }
           
           }
             
           }  else {
			return back()->withErrors(['Please select  atleast one event']);
           }  

    }


	public function registerstep2($id) {

        if (Auth::check()) {
		     $roletype='user';
          } else {
          	  $roletype='guest';
          }
        $register_data = Carts::with('cartDetails.tournaments')->where('id',$id)->first();

        if($register_data=='') {
          return redirect('tournaments')->withErrors(['Invalid Cart id']);
        }
        if(count($register_data->cartDetails)==0){
        	return redirect('tournaments')->withErrors(['Invalid cart id']);
        }
        
        if($register_data->payment_status==1){
        	 return redirect('tournaments')->withErrors(['Payment already completed for  cart id '.$id]);
        }

        $terms_and_conditions = BasicSettings::where('id',2)->value('description');
        if(empty($terms_and_conditions)) $terms_and_conditions = 'I agree Terms & Conditions';
        
        $parent_tournament_details='';
        foreach ($register_data->cartDetails as $value) {
        	$parent_tournament_details = TournamentParent::where('id',$value->tournaments->tournament_parent_id)->first();
        	$trn_country_id=Tournaments::where('id',$value->tournaments->tournament_parent_id)->value('country_id');
        	$trnament_id=$value->tournaments->id;
           break;
        }
     
        $tournament_data=Tournaments::with('logo')->where('id',$trnament_id)->first();
        
        $amount_data = PaymentGateWays::with(['paymentSetups' => function($query){
            $query->where('status','active'); 
            $query->select();
        }])->where('country_id',$trn_country_id)->first();


        $amount=Carts::where('id',$id)->value('total_payment');
        $amount_without_charges=$amount;
        $amount_array=array();
        $i=0;
        $serv_amount=0;
        if($amount_data!='' && count($amount_data) > 0) {
        foreach($amount_data->paymentSetups as $amnt) {
           $amount_array[$i]['name']=$amnt->setup_name;
           $amount_array[$i]['value']=$amnt->setup_value;
           $serv_amount=$serv_amount+($amount*($amnt->setup_value/100));
           $i++;
        }

        }
        $amount=$amount+$serv_amount;
        Carts::where('id',$id)->update(['total_payment'=> $amount]);
        $register_data->total_payment=$amount;

        

      return view('tournaments.registerstep2',compact('register_data','parent_tournament_details','amount_data','tournament_data'))->with([
         	'roletype'=>$roletype,'amount_without_charges'=>$amount_without_charges
         	,'terms_and_conditions'=>$terms_and_conditions]);
	

	}

	public function registerstep3($id) {

		if (Auth::check()) {
          $roletype='user';
        } else {
           $roletype='guest';
        }
     $cart_data = Carts::where('id',$id)->first();
     if($cart_data->payment_status==1){
       return redirect('tournaments')->withErrors(['Payment already completed for  cart id '.$id]);
      }
     $register_data = CartDetails::where('cart_id','=',$id)->where('registerd','=',0)->with('tournaments')->first();
     if(isset($register_data) && $register_data!=null){
        $event_id=$register_data->event_id;
        if (Auth::check()) { 
          return redirect('tournaments/registerstep3/'. $id.'/'.$event_id);
        } else{
          return redirect('guest/tournaments/guestregisterstep3/'. $id.'/'.$event_id);
        }
     } else {
        return redirect('tournaments/paymentform/'. $id);
     } 

	}

   public function registerstep4($id,$event_id) {
  
	 $register_data = CartDetails::where('cart_id',$id)->where('event_id',$event_id)->first();


   	 	if($register_data->registerd==1){
           $total_register_data = CartDetails::where('cart_id',$id)->where('registerd',0)->get();
           $count_total_register_data=count($total_register_data);
           if($count_total_register_data > 0) {
               return redirect('tournaments/registerstep3/'. $id);
           }else{
            return redirect('tournaments/paymentform/'. $id)->withErrors(['Registrations already completed.']);
            }
        }




   	 $parent_tournament_id = Tournaments::where('id',$event_id)->value('tournament_parent_id');
   	 $sports_id = Tournaments::where('id',$event_id)->value('sports_id');
   	 $sports_type = Tournaments::where('id',$event_id)->value('sports_id');
     $parent_tournament_details = TournamentParent::where('id',$parent_tournament_id)->first();
     $tournament_data=Tournaments::with('logo')->where('id',$event_id)->first();

     $user_data=array();
   	 if (Auth::check()) { 
   		$user_id = Auth::user()->id;
   		$user_data = User::where('id',$user_id)->first();
   	}else {
      $user_id = '';
    }


    $reg_teams_query=DB::table('request')->where('to_id',$event_id)->where('type',4)->get();
    $reg_team_ids=array();
    foreach($reg_teams_query as $reg_teams){
    	$reg_team_ids[]=$reg_teams->from_id;
    }

    //dd($reg_team_ids);
    
   	$teams=Team::where('team_owner_id',$user_id)->where('sports_id',$sports_id)->where('isactive',1)->whereNotIn('id', $reg_team_ids)->get();
   	$teams_array=array();
    if($teams!=''){
       foreach($teams as $tm){
          $teams_array[$tm->id]=$tm->name;
       }
    }

 
/*-----------------------------------------------------------------------------------------*/
    $lef_menu_condition = 'display_gallery';

		$tournamentInfo= Tournaments::select()->where('id',$event_id)->get();
		$sport_name="";
		$manager_name="";
		$managerarray=array();
		
		if(count($tournamentInfo))
		{
			$tournamentInfo=$tournamentInfo->toArray();
			$sports_id=$tournamentInfo[0]['sports_id'];
			$manager_id=$tournamentInfo[0]['manager_id'];
			$Sportsarray= Sport::where('id',$sports_id)->get(['id', 'sports_name'])->toArray();
			if($manager_id>0)
			{
				$managerarray= User::where('id',$manager_id)->get(['id', 'name'])->toArray();
			}

		}
		if(!empty($Sportsarray))
		{
			$sport_name=$Sportsarray[0]['sports_name'];
		}
		if(!empty($managerarray))
		{
			$manager_name=$managerarray[0]['name'];
		}
		$left_menu_data = array();
		$left_menu_data = Helper::getLeftMenuData($tournamentInfo[0]['tournament_parent_id'],$tournamentInfo[0]['manager_id'],$tournamentInfo);
		 
	
		return view('tournaments.payregisterform')->with(array( 'tournamentInfo'=>$tournamentInfo,'action_id'=>$event_id,'left_menu_data'=>$left_menu_data, 'tournament_id' => $event_id, 'lef_menu_condition'=> $lef_menu_condition, 'tournament_type' => $tournamentInfo[0]['type'],'sport_name'=>$sport_name,'manager_name'=> $manager_name,'register_data'=>$register_data,'teams_array'=>$teams_array,'user_data'=>$user_data,'tournament_data'=>$tournament_data,'parent_tournament_details'=>$parent_tournament_details));


/*-----------------------------------------------------------------------------------------*/

      }

 public function registerstep5() {
   $register_data = CartDetails::where('cart_id',$_REQUEST['cart_id'])->where('event_id',$_REQUEST['event_id'])->first();

      // if($register_data->registerd==1){
         //     	return redirect('tournaments/paymentform/'. $_REQUEST['cart_id'])->withErrors(['Registrations already completed.']);
      //  }
	
	
    $cart_array=Session::get('cart_array');
    $sports_id = Tournaments::where('id',$_REQUEST['event_id'])->value('sports_id');
   	$sports_type = Tournaments::where('id',$_REQUEST['event_id'])->value('sports_id');
    if (Auth::check()) { 
   	 $user_id = Auth::user()->id;
    }else {
      $user_id = '';
    }
  
    if($_REQUEST['match_type']=='singles'){
   	  foreach($_REQUEST['single'] as $single_array){
        $last_id='';
     	$request=array();
        $request['flag']='PLAYER_TO_TOURNAMENT';
        $mail_exist=User::where('email',$single_array['email'])->value('id');
        if($mail_exist!=''){
           $request['player_tournament_id'] =$mail_exist;
        } else {
         $last_id = DB::table('users')->insertGetId(array('name' => $single_array['name'], 'email' => $single_array['email'],'club' => $single_array['club'], 'contact_number' => $single_array['number'], 'verification_key' => md5($single_array['email'])));
         $request['player_tournament_id']=$last_id;
         $verification_key = md5($single_array['email']);
         $to_email_id      = $single_array['email'];
         $user_name        = $single_array['name'];
         $subject          = 'Welcome to SportsJun';
         $view_data        = array('name' => $user_name, 'verification_key' => $verification_key);
         $view             = 'emails.welcome';
         $mail_data        = array('view' => $view, 'subject' => $subject,
                        'to_email_id' => $to_email_id, 'view_data' => $view_data,
                        'flag' => 'user', 'send_flag' => 1, 'verification_key' => $verification_key, 'to_user_id' => $last_id);
         SendMail::sendmail($mail_data);
        }
        $request['team_ids'][0]=$_REQUEST['event_id'];

       
/*--------------------------------------new-----------------------------------------------*/
        $mn=AllRequests::saverequestdup($request);
        if($mn=='exist'){
        	//return back()->withErrors(['This Registration already exist']);
        } else if($mn=='fail'){
        	//return back()->withErrors(['Registration failed']);
        } else {

         $var_d = $_REQUEST['event_id'];
         $s_array[$var_d][]= array(
        		'flag'=>'PLAYER_TO_TOURNAMENT',
        		'player_tournament_id'=>$request['player_tournament_id'],
        		'team_ids'=>array($_REQUEST['event_id'])
        		);
        	//dd($cart_array);
            //Session::put("cart_array",$cart_array);
            //dd(Session::get('cart_array'));
        }
/*---------------------------------new--------------------------------------------*/ 

       
      }
      
     
      $i=0;
      $cart_array=Session::get('cart_array');
      foreach($s_array[$var_d] as $crt_s){
         $cart_array[$i]=$crt_s;
         $i++;
      }
      //dd($cart_array);
      Session::put("cart_array",$cart_array);
      CartDetails::where('cart_id',$_REQUEST['cart_id'])->where('event_id',$_REQUEST['event_id'])->update(array('registerd' => 1));
      return redirect('tournaments/registerstep3/'. $_REQUEST['cart_id']);
      
    } else if($_REQUEST['match_type']=='doubles') {
       $t_id='';
       $final_id='';
       if(isset($_REQUEST['team_id'])) {	 
         $t_id=$_REQUEST['team_id'];
       }
       if(isset($_REQUEST['team_name'])) {
         $team_exist=Team::where('name',$_REQUEST['team_name'])->value('id');
         if($team_exist==''){
          $final_id = DB::table('teams')->insertGetId(array('team_owner_id' => $user_id, 'sports_id' => $sports_id, 'name' => $_REQUEST['team_name'], 'team_available' => 1,'player_available' => 1));
           $t_id=$final_id;
          } else{
           $t_id=$team_exist;
          }
        }

        $request=array();
        $request['flag']='TEAM_TO_TOURNAMENT';
        $request['player_tournament_id'] =$_REQUEST['event_id'];	 
        $request['team_ids'][0]= $t_id;


 /*---------------------------------new--------------------------------------------*/
        $mn=AllRequests::saverequestdup($request);
        //dd($_REQUEST['event_id']);
        if($mn=='exist'){
        	return back()->withErrors(['This Registration already exist']);
        } else if($mn=='fail'){
        	return back()->withErrors(['Registration failed']);
        } else {

        $cart_array=array();
              
       $cart_array=Session::get('cart_array');
         $var_d = $_REQUEST['event_id'];
         $cart_array[$var_d]= array(
        		'flag'=>'TEAM_TO_TOURNAMENT',
        		'player_tournament_id'=>$_REQUEST['event_id'],
        		'team_ids'=>array($t_id)
        		);
        	
         Session::put("cart_array",$cart_array);
/*---------------------------------new--------------------------------------------*/ 
            $dou=0; 
            $reg=0;
       	    foreach($_REQUEST['doubles'] as $doubles_array){
              $last_id='';
     	      $mail_exist=User::where('email',$doubles_array['email'])->value('id');
              if($mail_exist!='') {
                 $last_id=$mail_exist;
              } else {
                $last_id=DB::table('users')->insertGetId(array('name' => $doubles_array['name'], 'email' => $doubles_array['email'],'club' => $doubles_array['club'], 'contact_number' => $doubles_array['number'], 'verification_key' => md5($doubles_array['email'])));
                $verification_key = md5($doubles_array['email']);
                $to_email_id      = $doubles_array['email'];
                $user_name        = $doubles_array['name'];
                $subject          = 'Welcome to SportsJun';
                $view_data        = array('name' => $user_name, 'verification_key' => $verification_key);
                $view             = 'emails.welcome';
                $mail_data        = array('view' => $view, 'subject' => $subject,
                        'to_email_id' => $to_email_id, 'view_data' => $view_data,
                        'flag' => 'user', 'send_flag' => 1, 'verification_key' => $verification_key, 'to_user_id' => $last_id);
                SendMail::sendmail($mail_data);
        	
        	 }
             $team_players_exist=TeamPlayers::where('team_id',$t_id)->where('user_id',$last_id)->value('id');
             if($dou==0){
        	  $role='owner';
             } else {
        	  $role='player';
             }
            if( $team_players_exist==''){
             $team_players = DB::table('team_players')->insertGetId(array('team_id' => $t_id, 'user_id' => $last_id, 'role' => $role, 'status' => 'accepted'));
            }	
            $dou++;
          }
        
          CartDetails::where('cart_id',$_REQUEST['cart_id'])->where('event_id',$_REQUEST['event_id'])->update(array('registerd' => 1));
           return redirect('tournaments/registerstep3/'. $_REQUEST['cart_id']);
        }
    } else {
      
      $t_id='';
      $final_id='';
      if(isset($_REQUEST['team_id'])) {	 
        $t_id=$_REQUEST['team_id'];
      }
      if(isset($_REQUEST['team_name'])) {
        $team_exist=Team::where('name',$_REQUEST['team_name'])->value('id');
        if($team_exist==''){
          $final_id = DB::table('teams')->insertGetId(array('team_owner_id' => $user_id, 'sports_id' => $sports_id, 'name' => $_REQUEST['team_name'], 'team_available' => 1,'player_available' => 1));
          $t_id=$final_id;
        } else{
           $t_id=$team_exist;
        }
      }

     


      $request['flag']='TEAM_TO_TOURNAMENT';
      $mail_exist=User::where('email',$_REQUEST['team_owner']['email'])->value('id');
      if($mail_exist!=''){
         $last_id=$mail_exist;
       } else {
         $last_id=DB::table('users')->insertGetId(array('name' => $_REQUEST['team_owner']['name'], 'email' => $_REQUEST['team_owner']['email'], 'club' => $_REQUEST['team_owner']['club'], 'contact_number' => $_REQUEST['team_owner']['number'], 'verification_key' => md5($_REQUEST['team_owner']['email'])));
         $verification_key = md5($_REQUEST['team_owner']['email']);
         $to_email_id      = $_REQUEST['team_owner']['email'];
         $user_name        = $_REQUEST['team_owner']['name'];
         $subject          = 'Welcome to SportsJun';
         $view_data        = array('name' => $user_name, 'verification_key' => $verification_key);
         $view             = 'emails.welcome';
         $mail_data        = array('view' => $view, 'subject' => $subject,
                        'to_email_id' => $to_email_id, 'view_data' => $view_data,
                        'flag' => 'user', 'send_flag' => 1, 'verification_key' => $verification_key, 'to_user_id' => $last_id);
         SendMail::sendmail($mail_data);
        }
        $team_players_exist=TeamPlayers::where('team_id',$t_id)->where('user_id',$last_id)->value('id');
        if($team_players_exist==''){
        $team_players = DB::table('team_players')->insertGetId(array('team_id' => $t_id, 'user_id' => $last_id, 'role' => 'owner', 'status' => 'accepted'));
        }	
        $request['player_tournament_id'] =$_REQUEST['event_id'];	 
        $request['team_ids'][0]= $t_id;

        //dd($t_id);
/*---------------------------------new--------------------------------------------*/
        $mn=AllRequests::saverequestdup($request);
        
        if($mn=='exist'){
        	return back()->withErrors(['This Registration already exist']);
        } else if($mn=='fail'){
        	return back()->withErrors(['Registration failed']);
        } else {
        	$cart_array=array();
        	$cart_array=Session::get('cart_array');
        	$var = $_REQUEST['event_id'];
        	
        	$cart_array[$var]= array(
        		'flag'=>'TEAM_TO_TOURNAMENT',
        		'player_tournament_id'=>$_REQUEST['event_id'],
        		'team_ids'=>array($t_id)
        		);
        	
            Session::put("cart_array",$cart_array);
         // dd(Session::get('cart_array'));
/*---------------------------------new--------------------------------------------*/        
         	
         CartDetails::where('cart_id',$_REQUEST['cart_id'])->where('event_id',$_REQUEST['event_id'])->update(array('registerd' => 1));
         return redirect('tournaments/registerstep3/'. $_REQUEST['cart_id']);
        }
        



        

    }

}

public function getGuestRegister($id,$event_id) {
  if (Auth::check()) { 
   	return redirect('tournaments')->withErrors(['Already logged']);
  }
  return view('tournaments.guestregisterform')->with(array('id'=>$id,'event_id'=>$event_id));
}
    
public function postGuestRegister() {
  $data_array=$_REQUEST;
  $data=$_REQUEST['guest'];
  $mail_exist=User::where('email',$data['email'])->value('id');
    if($mail_exist!=''){
      return back()->withErrors(['Email already exist.Please login to continue']);
    } else {
      $random_pwd=uniqid();
      $last_id = DB::table('users')->insertGetId(array('name' =>$data['name'], 'email' => $data['email'],  'location' => $data['location'], 'club' => $data['club'], 'contact_number' => $data['number'], 'password' => bcrypt($random_pwd), 'profile_updated' => 1 ,'is_verified' => 1, 'isactive' => 1));
        	$request['player_tournament_id']=$last_id;
      Carts::where('id',$_REQUEST['id'])->update(array('user_id' => $last_id));
      $login_data=User::where('id',$last_id)->first();
      if (Auth::attempt(['email' => $login_data['email'], 'password' => $random_pwd])) {
             return redirect('tournaments/registerstep3/'. $data_array['id'].'/'. $data_array['event_id']);
       } 
    }
}

public function getPaymentform($id) {

   //dd(Session::get('cart_array'));

  if (Auth::check()) {

    

     $roletype='user';
     $user_id = Auth::user()->id;
   } else {
      $roletype='';
      $user_id='';
   }

    $cart_data = Carts::where('id',$id)->first();
    $tournamentdata=CartDetails::where('cart_id',$id)->first();
    $tournament=Tournaments::where('id',$tournamentdata->event_id)->first();
    if($cart_data->payment_status==1){
      return redirect('tournaments')->withErrors(['Payment already completed for  cart id '.$id]);
    }
    $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
	$states = State::where('country_id', $tournament->country_id)->orderBy('state_name')->lists('state_name', 'id')->all();
    $states=array('' => 'Select State') + $states;
	$cities=array('' => 'Select City');
	$user_data = User::where('id',$user_id)->first(); 
	//dd($states); 
    return view('tournaments.paymentpage')->with(array('roletype' => $roletype,'id' => $id,'user_data' => $user_data,'countries' => $countries,'states' => $states, 'cities' => $cities ));
}

public function postPaymentform() {

  //dd($_REQUEST);

  
  $validator = Validator::make($_REQUEST, [
            'zipcode' => 'required|numeric',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
          //dd($validator);
          return back()->withErrors($validator);               
        }












  $data=Input::except('_token');
  $data['country'] = Country::where('id',$data['country'])->value('country_name');
  $data['state'] = State::where('id',$data['state'])->value('state_name');
  $data['city'] = City::where('id',$data['city'])->value('city_name');
  $payment_id='';
  $payment_id = DB::table('payment_details')->insertGetId(array('cart_id' =>$data['cart_id'], 'payment_firstname' => $data['firstname'],  'payment_address' => $data['address'], 'payment_country' => $data['country'], 'payment_state' => $data['state'],'payment_city' => $data['city'],'payment_zipcode' => $data['zipcode'],'payment_phone' => $data['phone']));
  $payment_params=array();
  $payment_params['key']='gtKFFx';
  $random=uniqid();
  Carts::where('id','=',$data['cart_id'])->update(['payment_token' => $random]);
  $payment_params['txnid']=$random; 
  $payment_params['amount']=Carts::where('id',$data['cart_id'])->value('total_payment'); 
  $productinfo=array();
  $register_data = CartDetails::where('cart_id','=',$data['cart_id'])->where('registerd','=',1)->get();
  $i=0;
  foreach($register_data as $value){
    $productinfo['paymentParts'][$i]['name']=Tournaments::where('id',$value->event_id)->value('name');
    $productinfo['paymentParts'][$i]['description']=Tournaments::where('id',$value->event_id)->value('description');
    $productinfo['paymentParts'][$i]['value']=$value->enrollment_fee * $value->participant_count;
    $productinfo['paymentParts'][$i]['isRequired']='true';
    $productinfo['paymentParts'][$i]['settlementEvent']='EmailConfirmation';
    $i++;
      }
   $productinfo['paymentIdentifiers'][0]['field']='CompletionDate';
   $dt=date('d/m/Y');
   $productinfo['paymentIdentifiers'][0]['value']=$dt;
   $productinfo['paymentIdentifiers'][1]['field']='TxnId';
   $productinfo['paymentIdentifiers'][1]['value']=$data['cart_id'];
   $payment_params['productinfo']=json_encode($productinfo);
   $payment_params['firstname']=$data['firstname'];
   $user_id = Auth::user()->id;
   $user_email = User::where('id',$user_id)->value('email');  
   $payment_params['email']=$user_email;
   $payment_params['phone']=$data['phone'];
   $payment_params['udf1']=$payment_id;
   $payment_params['udf2']=$data['cart_id'];
   $payment_params['surl']=url("tournaments/payment_success");
   $payment_params['furl']=url("tournaments/payment_failure");;
   $SALT = "eCwWELxi";
   $hash = '';
   // Hash Sequence
   $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
   $hashVarsSeq = explode('|', $hashSequence);
   $hash_string = '';  
   foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($payment_params[$hash_var]) ? $payment_params[$hash_var] : '';
      $hash_string .= '|';
    }
    $hash_string .= $SALT;
    $payment_params['hash']= strtolower(hash('sha512', $hash_string));
    $payment_params['service_provider']='';
    
    return view('tournaments.hiddenpaymentpage')->with(array('payment_params' => $payment_params));

   
 }




public function postPaymentsuccess() {
	$dt=$_POST;
    $date=date('Y-m-d h:i:s');
    //$date=date('Y-m-d');

     
     $cart_data=session('cart_array');

    //dd($cart_data);
     
     foreach($cart_data as $crt){
     	$mn=AllRequests::saverequest($crt);
        if($crt['flag']=='PLAYER_TO_TOURNAMENT'){
              $req_table =DB::table('request')->where('to_id',$crt['team_ids'][0])->where('from_id',$crt['player_tournament_id'])->update(['cart_id' => $_POST['udf2'],'action_status' => 1]);
     	} else if($crt['flag']=='TEAM_TO_TOURNAMENT'){
     		$req_table =DB::table('request')->where('to_id',$crt['player_tournament_id'])->where('from_id',$crt['team_ids'][0])->update(['cart_id' => $_POST['udf2'],'action_status' => 1]);
     	}

     	
     }

   

     
    //Session::flush();
    Session::forget('cart_array');

    //dd(session('cart_array'));    

	PaymentDetails::where('id', $_POST['udf1'])->update(['status' => $dt['status'],'mihpayid' => $dt['mihpayid'],'amount' => $dt['amount'],'date' => $date]);

	Carts::where('id',$_POST['udf2'])->update(['payment_status' => 1]);


    $user_id = Auth::user()->id;
    $register_data = Carts::with('cartDetails.tournaments')->where('id',$_POST['udf2'])->where('user_id',$user_id)->where('payment_token','!=','')->where('payment_status',1)->get();
    $data=array();
    $i=0;
    foreach($register_data as $reg){
    foreach($reg->cartDetails as $carts){
    $data[$i]['id']=$reg->id;
    $data[$i]['name']=PaymentDetails::where('cart_id',$reg->id)->value('payment_firstname');
    $data[$i]['email']=User::where('id',$user_id)->value('email');
    $data[$i]['phone']=PaymentDetails::where('cart_id',$reg->id)->value('payment_phone');

    $team_id =DB::table('request')->where('to_id',$carts->tournaments->id)->where('cart_id',$_POST['udf2'])->where('type',4)->pluck('from_id');

    $data[$i]['team']=Team::where('id',$team_id)->value('name');



    $data[$i]['tournament']=$carts->tournaments->name;
    $data[$i]['price']=($carts->enrollment_fee)*($carts->participant_count);
    $data[$i]['date']=PaymentDetails::where('cart_id',$reg->id)->value('date');

    $data[$i]['organiser']=User::where('id',$carts->tournaments->created_by)->value('name');
    $data[$i]['organiser_phone']=User::where('id',$carts->tournaments->created_by)->value('contact_number');
    $data[$i]['organiser_location']=User::where('id',$carts->tournaments->created_by)->value('location');

    $i++;
     }


     }

  


        $header = '<div style="width:100%; min-width:480px;"><div style="background:#ececec; padding:50px 0;"><div style="width:80%; height:70px; background:#65c178; margin:auto;">
       
               <table cellspacing="0" cellpadding="0" border="0" width="100%" height="70">
                   <tbody><tr>
                     <td align="left" valign="middle"><!-- <h1 style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:bold; margin:0 0 0 20px; color:#fff;">Email Text</h1> --></td>
                       <td align="right"><img width="254" height="60" style="margin:0 20px 0 0;" src="'.url().'/images/SportsJun_Logo.png"></td>
                   </tr>
               </tbody></table>

       </div><div style="width:80%; background:#fff; margin:auto; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; line-height:30px;"><div style="padding:30px;"><div style="padding: 0; text-align: left; font-size:14px; color:#333; font-family:Arial, Helvetica, sans-serif;">';
		
		$footer = '<div style="padding:10px 0; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; line-height:30px;">
                        Regards,<br>
                  <b style="color:#2f3c4d;">SportsJun Team</b>
                </div><div style="padding:5px 0; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; line-height:20px;">SportsJun Media &amp; Entertainment Pvt Ltd | Email: contact@sportsjun.com<br>
					The information contained in this email may be confidential and is intended only for the
					addressee. If you are not the intended recipient and have received this communication
					in error, please notify the sender and delete the message. Any unauthorised use of this
					communication is prohibited.</div></div></div></div></div></div>';



      $content="<div class='form-header header-primary register_form_head'><h4 class='register_form_title successpage'>Payment Result</h4></div>

<h3>Thank You. Your order status is ".$dt['status'].".</h3>
<h4>Your Transaction ID for this transaction is ".$dt['mihpayid'].".</h4>
<h4>We have received a payment of INR. " .$dt['amount'].".</h4><br><br>";

$content.="<table style='border:1px solid #555;'>
           <tr style='border-bottom:1px solid #555;'>
           <th style='color: red;padding: 20px;'>Tournament</th>
           <th style='color: red;padding: 20px;'>Payment Name</th>
           <th style='color: red;padding: 20px;'>Payment Email</th>
           <th style='color: red;padding: 20px;'>Payment Phone</th>
           <th style='color: red;padding: 20px;'>Amount</th>
           <th style='color: red;padding: 20px;'>Date</th>
           <th style='color: red;padding: 20px;'>Organiser Name</th>
           <th style='color: red;padding: 20px;'>Organiser Phone</th>
           <th style='color: red;padding: 20px;'>Organiser Location</th>
           </tr>";
  foreach($data as $dat) {
  $content.="<tr style='border-bottom:1px solid #555;'>
           <td style='padding: 4px;'>".$dat['tournament']."</td>
           <td style='padding: 4px;'>".$dat['name']."</td>
           <td style='padding: 4px;'>".$dat['email']."</td>
           <td style='padding: 4px;'>".$dat['phone']."</td>
           <td style='padding: 4px;'>".floatval($dat['price'])."</td>
           <td style='padding: 4px;'>".date('d-M-Y h:i:s', strtotime($dat['date']))."</td>
           <td style='padding: 4px;'>".$dat['organiser']."</td>
           <td style='padding: 4px;'>".$dat['organiser_phone']."</td>
           <td style='padding: 4px;'>".$dat['organiser_location']."</td>
           </tr>";
           }
     $content.='</table>';





//echo $header.$content.$footer; exit;


 $to_email_id = User::where('id',$user_id)->value('email');
 $subject = 'Payment Status';

 $view_data['name']  =User::where('id',$user_id)->value('name'); 
 $view_data['verification_key'] = '';
 $view_data['header'] = $header;
 $view_data['content'] = $content;
 $view_data['footer'] = $footer;
 $view='emails.welcome';

    $i=0;

    foreach($register_data as $reg){
    foreach($reg->cartDetails as $carts){
  
    $team_id =DB::table('request')->where('to_id',$carts->tournaments->id)->where('cart_id',$_POST['udf2'])->where('type',4)->pluck('from_id');

   

    	if($team_model = Team::find($team_id)){
    			foreach(Team::find($team_id)->teamplayers as $sp){
    				 
    				 if($sp->user->email){
	    				 Mail::send(['html' => $view], ['view_data'=>$view_data], function($message) use ($sp,$subject)
							{    
								$message->to($sp->user->email)->subject($subject);    
							});
	    			}
    			}
    	}

	    $i++;
	     }
    }



Mail::send(['html' => $view], ['view_data'=>$view_data], function($message) use ($to_email_id,$subject)
				{    
					$message->to($to_email_id)->subject($subject);    
				});
 
    
	return view('tournaments.paymentsuccess')->with(array('data' => $dt,'details' => $data));
    
}



public function postPaymentfailure() {
  
  $dt=$_POST;
  Session::forget('cart_array');
  $req_table =DB::table('request')->where('cart_id',$_POST['udf2'])->delete();
  return view('tournaments.paymentfailure');
  
}

public function Transactions($id) {
   
   $user_id = Auth::user()->id;
   $register_data = Carts::with('cartDetails.tournaments')->where('user_id',$user_id)->where('payment_token','!=','')->where('payment_status',1)->orderBy('id', 'DESC')->get();
    $data=array();
    $i=0;
    foreach($register_data as $reg){
    foreach($reg->cartDetails as $carts){
    $data[$i]['id']=$reg->id;
    $data[$i]['name']=PaymentDetails::where('cart_id',$reg->id)->value('payment_firstname');


    $team_id =DB::table('request')->where('to_id',$carts->event_id)->where('cart_id',$reg->id)->where('type',4)->pluck('from_id');

    $data[$i]['team']=Team::where('id',$team_id)->value('name');

    $data[$i]['email']=User::where('id',$user_id)->value('email');
    $data[$i]['date']=PaymentDetails::where('cart_id',$reg->id)->value('date');
    $data[$i]['phone']=PaymentDetails::where('cart_id',$reg->id)->value('payment_phone');
    $data[$i]['tournament']=$carts->tournaments->name;
    $data[$i]['price']=($carts->enrollment_fee)*($carts->participant_count);

    $i++;
     }


     }
     
   return view('tournaments.transactions')->with(array('details' => $data,'u_id'=> $user_id));
 }

 public function myTournamentRegistrations($id) {
    $user_id = Auth::user()->id;
    $mytrnaments= Tournaments::where('created_by',$user_id)->get(); 
    $trnament_ids=array();
      foreach($mytrnaments as $reg){
     	$trnament_ids[]=$reg->id;
      }	
      
      $crtdtls= CartDetails::whereIn('event_id', $trnament_ids)->groupBy('cart_id')->get(['cart_id']);
      $cart_ids=array();
      foreach($crtdtls as $reg){
     	$cart_ids[]=$reg->cart_id;
       }	

   $register_data = Carts::with('cartDetails.tournaments')->whereIn('id',$cart_ids)->where('payment_token','!=','')->where('payment_status',1)->orderBy('id', 'DESC')->get();
    $data=array();
    $i=0;
    $mn=array();

   // foreach($register_data as $reg){
   //   foreach($reg->cartDetails as $carts){
   //     $mn[$carts->event_id]=array();
   //    }
   //  }	


    $i=0;
    foreach($register_data as $reg){
     foreach($reg->cartDetails as $carts){

      
    	$data['t_name']=$carts->tournaments->name;
    	$data['tot_enrollmet']=$carts->tournaments->total_enrollment;
    	$alreday_registered=DB::table('request')->where('to_id',$carts->event_id)->where('cart_id','!=','0')->get();
    	$data['current_enrollmet']=count($alreday_registered);
    	$data['remaining_enrollmet']=$carts->tournaments->total_enrollment-count($alreday_registered);
    	$data['data']['id']=$reg->id;
    	$team_id =DB::table('request')->where('to_id',$carts->event_id)->where('cart_id',$carts->cart_id)->where('type',4)->pluck('from_id');

    	$data['data']['team']=Team::where('id',$team_id)->value('name');
    	$data['data']['name']=PaymentDetails::where('cart_id',$reg->id)->value('payment_firstname');
    	$data['data']['email']=User::where('id',$reg->user_id)->value('email');
    	$data['data']['date']=PaymentDetails::where('cart_id',$reg->id)->value('date');
    	$data['data']['phone']=PaymentDetails::where('cart_id',$reg->id)->value('payment_phone');
    	$data['data']['tournament']=$carts->tournaments->name;
    	$data['data']['price']=($carts->enrollment_fee)*($carts->participant_count);


     	$mn[$carts->event_id][]=$data;

     	$i++;
        $data[$carts->event_id]=$carts;

     }
  
    }




 return view('tournaments.myregisteredtournaments')->with(array('details' => $mn,'u_id'=> $user_id));     
}







}
