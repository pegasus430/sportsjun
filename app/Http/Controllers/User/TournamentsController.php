<?php

namespace App\Http\Controllers\User;

//use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\InvitePlayerController;
use App\Http\Requests;
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
use App\Model\TournamentFinalTeams;
use App\Model\TournamentGroups;
use App\Model\TournamentGroupTeams;
use App\Model\TournamentParent;
use App\Model\Tournaments;
use App\Model\UserStatistic;
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


use App\Model\SoccerPlayerMatchwiseStats;
use App\Model\HockeyPlayerMatchwiseStats;
use App\Model\BasketballPlayerMatchwiseStats;
use App\Model\CricketPlayerMatchwiseStats;
use App\Model\WaterpoloPlayerMatchwiseStats;


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
	
		Helper::setMenuToSelect(6, 1);
		return view('tournaments.team', array('joinTeamArray' => $joinTeamArray, 'followingTeamArray' => $followingTeamArray, 'manageTeamArray' => $manageTeamArray,
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

		/** @var Tournaments $Tournaments */
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
		if (Request::has('organization_group_id')) {
			$TournamentParent->orgGroups()
				->attach(Request::input('organization_group_id'));
		}

		$last_inserted_sport_id = 0;
		$last_inserted_sport_id = $TournamentParent->id;
		$user_id= Auth::user()->id;
		//Upload Photos
		$albumID = 1;//Default album if no album is not selected.
		$coverPic = 1;
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
		//return redirect()->back()->with('status', trans('message.tournament.create'));
		return redirect()->route('tournaments.edit', [$last_inserted_sport_id])->with('status', trans('message.tournament.create'));
	}
	public function getUsers()
	{
		$results=array();
		$user_name = Request::get('term');
		// $users = User::where('name','LIKE','%'.$user_name.'%')->where('isactive',1)->get(['id','name']);
		DB::setFetchMode(PDO::FETCH_ASSOC);
		$users = DB::select("SELECT * FROM `users`  WHERE `name` LIKE '%$user_name%' and `isactive`=1") ;
		DB::setFetchMode(PDO::FETCH_CLASS);
		if(count($users)>0)
		{
			foreach ($users as $query)
			{
				$results[] = ['id' => $query['id'], 'value' => $query['name']];
			}
		}
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
		if(!empty($request['isParent']) && $request['isParent']=='yes')
		{

			return $this->updateParentTournament($request,$id);
		}
		$manager_id = Tournaments::where('id', $id)->pluck('manager_id');
		$request['country_id'] = config('constants.COUNTRY_INDIA');
		$request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
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

		Tournaments::whereId($id)->update($request->except(['_method','_token','facility_response','facility_response_name','files','filelist_photos','filelist_gallery','jfiler-items-exclude-files-0','user_id']));
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
//		   $sports = Sport::where('isactive','=',1)->lists('sports_name', 'id')->all();
		$sports = Helper::getDevelopedSport(1,1);
		/** @var TournamentParent $tournament */
		$tournament = TournamentParent::findOrFail($id);

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


		$countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
		$states = State::where('country_id', $tournament->country_id)->orderBy('state_name')->lists('state_name', 'id')->all();
		$cities = City::where('state_id',  $tournament->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
		return view('tournaments.edit',compact('tournament'))->with(array('sports'=> [''=>'Select Sport']+$sports,'id'=>$id,'countries' =>  [''=>'Select Country']+$countries,'states' =>  [''=>'Select State']+$states,'cities' =>  [''=>'Select City']+$cities,'enum'=>['' => 'Tournament Type'] + $enum,'tournament'=>$tournament,'type'=>'edit','roletype'=>'user',
			'schedule_type_enum'=>$schedule_type_enum,
			'game_type_enum' 	=>$game_type_enum,
			'parent_id'=>$id,'tournament_name'=>$tournament['name'],'logo'=>$tournament['logo'],'manager_name'=>$manager_name,'matchTypes'=>$matchTypes,'playerTypes'=>$playerTypes,'match_types'=>['' => 'Select Match Type'] +$match_types,'player_types'=>['' => 'Select Player Type'] +$player_types,'matchScheduleCount'=>$matchScheduleCount,'tournamentGroupCount'=>$tournamentGroupCount));
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
								$query->where('a_id', $teamId)->orWhere('b_id', $teamId);
							})->count();

				
					$match_count_details=Helper::getMatchGroupDetails($tournament_id, $group_id, $scheduled_teams['team_id']);

					//calculate ga, gf and tie.
						$scheduled_teams['tie']=$match_count_details['tie'];
			     			if(in_array($tournaments[0]['sports_id'], [3,4,5,6,2,3,7,13,14,15,16,17,11])){
			     				
									$scheduled_teams['ga']=$match_count_details['ga'];
									$scheduled_teams['gf']=$match_count_details['gf'];
							 }



				 	$team_details[$group_id][$key]=$scheduled_teams;

				 		}

				 	if(in_array($tournaments[0]['sports_id'], [3,4,5,6,2,3,7,13,14,15,16,17,11])){
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

//                    dd($firstRoundBracketArray);
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

				$bracketTeamArray = $this->getBracketTeams($tournament_id, $maxRoundNumber, $schedule_type, $isOwner);

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
//                 dd($bracketTeamArray);
			$selectetdFinalStageTeams = explode(',', $tournaments[0]['final_stage_teams_ids']);
		}


		$requestedFinalTeams = [];
		// For displaying the teams added for final stage teams as well as the requested teams
		if ($tournament_type == 'knockout')
		{
			$tournamentFinalTeams = $this->getFinalStageAddedTeams($tournament_id, $schedule_type);
//                dd($tournamentFinalTeams);
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


//                        dd($requestedFinalTeams);
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




//            dd($tournament_id);
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

	function getBracketTeams($tournament_id, $maxRoundNumber, $scheduleType, $isOwner) {
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
		$teamIds = '';
		if(count($teams)) {
			foreach($teams as $team) {
				$teamIds.= $team->team_id.',';
			}
		}
		if(!empty($teamIds)) {
			$teamIDs = trim($teamIds,',');
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
							->selectRaw('sum(IF(bat_status="notout", 1, 0)) as notouts')
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
							->groupBy('user_id')							
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
				 			}

					}
				 
			}

			return $group_teams;
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
		return redirect()->back()->with('message', 'Group Closed');
	}
}

