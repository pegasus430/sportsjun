<?php

namespace App\Http\Controllers\User;

use App\Events\TeamOwnershipChanged;
use App\Events\UserRegistered;
use App\Helpers\AllRequests;
use App\Helpers\Helper;
use App\Helpers\SendMail;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\City;
use App\Model\Country;
use App\Model\MatchSchedule;
use App\Model\Notifications;
use App\Model\Organization;
use App\Model\OrganizationGroup;
use App\Model\Photo;
use App\Model\Requestsmodel;
use App\Model\Sport;
use App\Model\State;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\UserStatistic;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Request;
use Response;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     *function for create team page load
     */
    public function create()
    {
        $sports = Helper::getDevelopedSport(1, 1);
        $enum = config('constants.ENUM.TEAMS.TEAM_LEVEL');
        $countries = Country::orderBy('country_name')
            ->lists('country_name', 'id')
            ->all();
        $organization_id = Request::input('organization_id');
        $states = [];
        $organization = Organization::orderBy('name')->where('user_id',
            (isset(Auth::user()->id) ? Auth::user()->id : 0))->lists('name', 'id')->all();
        $cities = array();
        return view('teams.createteam')->with(array('sports' => ['' => 'Select Sport'] + $sports))
            ->with('countries', ['' => 'Select Country'] + $countries)
            ->with('states', ['' => 'Select State'] + $states)
            ->with('cities', ['' => 'Select City'] + $cities)
            ->with('organization', ['' => 'Select Organization'] + $organization)
            ->with('enum', $enum)
            ->with('organization_id', $organization_id);
    }

    /**
     * Store a newly created team in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateTeamRequest $request)
    {
        //getting team owner id from session logged in user id
        $request['team_owner_id'] = (isset(Auth::user()->id) ? Auth::user()->id : 0);
        $request['city'] = !empty(Request::get('city_id')) ? City::where('id',
            Request::get('city_id'))->first()->city_name : 'null';
        $request['state'] = !empty(Request::get('state_id')) ? State::where('id',
            Request::get('state_id'))->first()->state_name : 'null';
        $request['country'] = !empty($request['country_id']) ? Country::where('id',
            $request['country_id'])->first()->country_name : 'null';
        $location = Helper::address($request['address'], $request['city'], $request['state'], $request['country']);
        $request['location'] = trim($location, ",");
        //model call to save the data
        $team_details = Team::create($request->all());

        if ($request->has('organization_group_id')) {
            $this->attachOrganizationGroupToTeam($team_details,
                $request->input('organization_group_id'));
        }

        if (!empty($team_details)) {
            $team_id = isset($team_details['id']) ? $team_details['id'] : 0;
            if (count($request['filelist_logo'])) {
                Helper::uploadPhotos($request['filelist_logo'], config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),
                    $team_id, 1, 1, config('constants.PHOTO.TEAM_PHOTO'),
                    (isset(Auth::user()->id) ? Auth::user()->id : 0));
            }
            $logo = Photo::select('url')->where('imageable_type',
                config('constants.PHOTO.TEAM_PHOTO'))->where('imageable_id', $team_details['id'])->where('user_id',
                (isset(Auth::user()->id) ? Auth::user()->id : 0))->where('is_album_cover', 1)->get()->toArray();
            if (!empty($logo)) {
                foreach ($logo as $l) {
                    Team::where('id', $team_details['id'])->update(['logo' => $l['url']]);

                }

            }
            if (is_numeric($team_id)) {
                //insert into team players table
                $teamplayer_details = array(
                    'team_id' => $team_id,
                    'user_id' => (isset(Auth::user()->id) ? Auth::user()->id : 0),
                    'role' => 'owner',
                    'status' => 'accepted',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                );
                if (TeamPlayers::create($teamplayer_details)) {
                    //insert into user statistics table
                    //check if there is any manager existing with that team
                    //if exist insert else update
                    $existing_user_details = UserStatistic::where('user_id',
                        (isset(Auth::user()->id) ? Auth::user()->id : 0))->first();
                    if (count($existing_user_details)) {
                        if (!$this->isExist($existing_user_details['following_sports'], $request['sports_id'])) {
                            $existing_user_details->following_sports = $existing_user_details['following_sports'] . $request['sports_id'] . ',';
                        }
                        // if(!$this->isExist($existing_user_details['following_teams'],$team_id))
                        // {
                        // $existing_user_details->following_teams = $existing_user_details['following_teams'].$team_id.',';
                        // }
                        if (!$this->isExist($existing_user_details['managing_teams'], $team_id)) {
                            $existing_user_details->managing_teams = $existing_user_details['managing_teams'] . $team_id . ',';
                        }
                        if (!$this->isExist($existing_user_details['joined_teams'], $team_id)) {
                            $existing_user_details->joined_teams = $existing_user_details['joined_teams'] . $team_id . ',';
                        }
                        $existing_user_details->save();
                    } else {
                        $user_statistic_details = array(
                            'user_id' => (isset(Auth::user()->id) ? Auth::user()->id : 0),
                            'following_sports' => ',' . $request['sports_id'] . ',',
                            //'following_teams' => ','.$team_id.',',
                            'managing_teams' => ',' . $team_id . ',',
                            'joined_teams' => ',' . $team_id . ','
                        );
                        UserStatistic::create($user_statistic_details);
                    }
                    //redirect to create team page with status message
                    return redirect()->route('team/teams')->with('status', trans('message.team.create'));
                } else {
                    return redirect()->route('team/teams')->with('alert', trans('message.team.createfail'));
                }
            } else {
                return redirect()->route('team/teams')->with('alert', trans('message.team.createfail'));
            }
        } else {
            return redirect()->route('team/teams')->with('alert', trans('message.team.createfail'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//		$sports = Sport::orderBy('sports_name')->lists('sports_name', 'id')->all();
        $sports = Helper::getDevelopedSport(1, 1);
        $cities = array();
        $teams = Team::findOrFail($id);
        $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
        $states = State::where('country_id', $teams->country_id)->orderBy('state_name')->lists('state_name',
            'id')->all();
        $enum = config('constants.ENUM.TEAMS.TEAM_LEVEL');
        if (!empty($teams->state_id)) {
            $cities = City::where('state_id', $teams->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
        }

        return view('teams.editteam')->with('teams', $teams->toArray())
            ->with('sports', ['' => 'Select Sport'] + $sports)
            ->with('countries', ['' => 'Select Country'] + $countries)
            ->with('states', ['' => 'Select State'] + $states)
            ->with('cities', ['' => 'Select City'] + $cities)
            ->with('id', $id)
            ->with('enum', $enum);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateteam(Requests\CreateTeamRequest $request, $id)
    {
        $router = route('team/teams');

        if ($request->has('organization_id')) {
            $router = route('organizationTeamlist', [$request->input('organization_id')]);
        }

        $request = Request::all();
        if (is_numeric($id)) {

            $sports_id = $request['sports_id'];
            $name = $request['name'];
            $address = $request['address'];
            $city_id = $request['city_id'];
            $state_id = $request['state_id'];
            $zip = $request['zip'];
            $description = $request['description'];
            $organization_id = $request['organization_id'];
            $gender = $request['gender'];
            $country_id = config('constants.COUNTRY_INDIA');
            $city = !empty(Request::get('city_id')) ? City::where('id',
                Request::get('city_id'))->first()->city_name : 'null';
            $state = !empty(Request::get('state_id')) ? State::where('id',
                Request::get('state_id'))->first()->state_name : 'null';
            $country = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
            $locationn = Helper::address($address, $city, $state, $country);
            $location = trim($locationn, ",");

            $player_available = !empty($request['player_available']) ? $request['player_available'] : 0;
            $team_available = !empty($request['team_available']) ? $request['team_available'] : 0;
            if (!empty($sports_id) && !empty($name)) {
                //update team table
                Team::where('id', '=', $id)->update([
                    'sports_id' => $sports_id,
                    'name' => $name,
                    'location' => $location,
                    'address' => $address,
                    'city_id' => $city_id,
                    'city' => $city,
                    'state_id' => $state_id,
                    'country_id' => $country_id,
                    'country' => $country,
                    'zip' => $zip,
                    'description' => $description,
                    'player_available' => $player_available,
                    'team_available' => $team_available,
                    'updated_at' => Carbon::now(),
                    'team_level' => $request['team_level'],
                    'gender' => $gender,
                    'organization_id' => $organization_id
                ]);

                $team_details = Team::find($id);
                //assign group to team.

                if (isset($request['organization_group_id'])) {
                    $organization_group_id = $request['organization_group_id'];
                    if (is_numeric($organization_group_id) && $team_details) {
                        /* @var Team $team_details */
                        $team_details->organizationGroups()->sync([$organization_group_id]);
                    }
                }
                if (!empty($request['filelist_logo'])) {
                    //update existing album cover to 0
                    Photo::where('imageable_id', '=', $id)->where('imageable_type', '=',
                        config('constants.PHOTO.TEAM_PHOTO'))->update([
                        'is_album_cover' => 0,
                        'updated_at' => Carbon::now()
                    ]);
                    //update new photo

                    Helper::uploadPhotos($request['filelist_logo'], config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),
                        $id, 1, 1, config('constants.PHOTO.TEAM_PHOTO'),
                        (isset(Auth::user()->id) ? Auth::user()->id : 0));
                }
                $logo = Photo::select('url')->where('is_album_cover', '=', '1')->where('imageable_type',
                    config('constants.PHOTO.TEAM_PHOTO'))->where('imageable_id', $id)->where('user_id',
                    (isset(Auth::user()->id) ? Auth::user()->id : 0))->get()->toArray();
                if (!empty($logo)) {
                    foreach ($logo as $l) {
                        Team::where('id', $id)->update(['logo' => $l['url']]);
                    }

                }
                return redirect($router)->with('status', trans('message.team.update'))->with('div_sel_mgt', 'active');
            } else {
                return redirect($router)->withErrors('alert', trans('message.team.updatefail'))->with('div_sel_mgt',
                    'active');
            }
        } else {
            return redirect($router)->withErrors('alert', trans('message.team.updatefail'))->with('div_sel_mgt',
                'active');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *function for myteam
     */
    public function myteam($team_id, $status = null)
    {
        //get the data by joining teams, teamplayers and users table
        $teams_query = Team::with(array(
            'teamplayers' => function ($q1) use ($status) {
                if (!empty($status)) {
                    $q1->select()->where('status', $status)->orderBy('status', 'asc');
                } else {
                    $q1->select()->whereNotIn('status', ['rejected'])->orderBy('status', 'asc');
                }
            },
            'teamplayers.user' => function ($q2) {
                $q2->select();
            },
            'teamplayers.user.photos' => function ($q3) {
                $q3->select();
            }
        ));
        $managingTeamIds = Helper::getManagingTeamIds((isset(Auth::user()->id) ? Auth::user()->id : 0));
        $managing_team_ids = explode(',', trim($managingTeamIds, ','));
        //if the team id is in managing team ids, then add owner conditioin
        if (in_array($team_id, $managing_team_ids)) {
            //$teams_query->where('team_owner_id',(isset(Auth::user()->id)?Auth::user()->id:0));
        }
        $teams = $teams_query->where('id', $team_id)->get();
        $teams = $teams->toarray();
        $team_owners_managers = array();
        $team_players = array();
        //if team's details are not empty
        if (!empty($teams)) {
            //static array for owner,manager and coach
            $owners_managers = ['owner', 'manager', TeamPlayers::$ROLE_COACH, TeamPlayers::$ROLE_PHYSIO];
            foreach ($teams as $team) {
                //if teamplayer's details are not empty
                if (!empty($team['teamplayers'])) {
                    foreach ($team['teamplayers'] as $teamdetails) {
                        if (!empty($teamdetails['role'])) {
                            $role = trim($teamdetails['role']);
                            //if the role is owner, manager or coach push in to team_owners_managers array
                            if (in_array($role, $owners_managers)) {
                                array_push($team_owners_managers, $teamdetails);
                            } else //else push the data into team_players array
                            {
                                array_push($team_players, $teamdetails);
                            }
                        }
                    }
                }
            }
        }
        //get following sports
        $userId = (isset(Auth::user()->id) ? Auth::user()->id : 0);
        $following_sportids = UserStatistic::where('user_id', $userId)->pluck('following_sports');
        // Helper::setMenuToSelect(2,1);
        $sport = !empty($teams[0]['sports_id']) ? Sport::where('id', $teams[0]['sports_id'])->pluck('sports_name') : '';
        $sport_id = !empty($teams[0]['sports_id']) ? $teams[0]['sports_id'] : 0;
        Helper::leftMenuVariables($team_id);
        $managing_teams = Helper::getManagingTeams($userId, $sport_id);
        if (count($managing_teams)) {
            $managing_teams = $managing_teams->toArray();
        }
        //get role for the logged in user id
        $logged_in_user_role = TeamPlayers::where('user_id', $userId)->where('team_id', $team_id)->pluck('role');

        return view('teams.myteam')
            ->with('teams', $teams)
            ->with('team_owners_managers', $team_owners_managers)
            ->with('team_players', $team_players)
            ->with('logged_in_user_role', $logged_in_user_role)
            ->with('following_sportids', $following_sportids)
            ->with('managing_teams', $managing_teams)
            ->with('sport_id', $sport_id)
            ->with('team_id', $team_id);
    }

    /**
     *function for teams list
     */
    public function teamslist($user_id = null)
    {

        if ($user_id == '') {
            $user_id = (isset(Auth::user()->id) ? Auth::user()->id : 0);
        } else {
            $user_id = $user_id;

        }
        $joinTeamArray = array();
        $followingTeamArray = array();
        $manageTeamArray = array();
     #   $managedOrgArray = array();
     #   $managedOrgArray1 = array();
     #   $managedOrgArray = Organization::select('name', 'id', 'isactive')->where('user_id', $user_id)->get()->toArray();

        //get the details from user statistics based on user id
        //$follow_teamDetails = UserStatistic::where('user_id', $user_id)->first();
        $modelObj = new \App\Model\Followers;

        $follow_teamDetails = $modelObj->getFollowingList($user_id, 'team');
        //echo "<pre>";print_r($follow_teamDetails);exit;


        $following_team_array = array();
        if (isset($follow_teamDetails) && count($follow_teamDetails) > 0) {
            $following_team_array = array_filter(explode(',', $follow_teamDetails[0]->following_list));
        }

        $modelObj = new \App\Model\Team;

        $teamDetails = $modelObj->getTeamsByRole($user_id, 1);
        // echo "<pre>";print_r($teamDetails);exit;

        if (isset($teamDetails) && count($teamDetails) > 0) {
            $joined_team_array = array_filter(explode(',', $teamDetails[0]->joined_teams));
            if (count($joined_team_array)) {
                $joinTeamArray = $this->getteamdetails($joined_team_array);
            }

            $managed_team_array = array_filter(explode(',', $teamDetails[0]->managing_teams));
            if (count($managed_team_array)) {
                $manageTeamArray = $this->getteamdetails($managed_team_array);
            }
        }
        if (count($following_team_array)) {
            $followingTeamArray = $this->getteamdetails($following_team_array);
        }
        /*
        if (count($managedOrgArray)) {
            foreach ($managedOrgArray as $man) {
                $id[] = $man['id'];
            }
            $managedOrgArray1 = $this->getorgdeatils($id);
        }*/

        return view('teams.teamslist', [
            'joinTeamArray' => $joinTeamArray,
            'followingTeamArray' => $followingTeamArray,
            'manageTeamArray' => $manageTeamArray,
          //  'managedOrgArray' => $managedOrgArray1,
            'id' => (isset(Auth::user()->id) ? Auth::user()->id : 0),
            'userId' => $user_id,
        ]);
    }

    function getorgdeatils($org_array = '')
    {
        $result = array();
        $org_details = Organization::with(array(
            'teamplayers' => function ($q1) {
                $q1->select();
            },

            'photos' => function ($q3) {
                $q3->select();
            },
            'user' => function ($q4) {
                $q4->select();
            }

            // ))->whereIn('id',$teams_array)->get();
        ))->whereIn('id', $org_array)->orderBy('isactive', 'desc')->get();
        if (count($org_details)) {
            $result = $org_details->toArray();
        }
        return $result;
    }

    function getteamdetails($teams_array = '')
    {
        $lef_menu_condition = 'display_gallery';
        $result = array();
        $team_details = Team::with(array(
            'teamplayers' => function ($q1) {
                $q1->select();
            },
            'sports' => function ($q2) {
                $q2->select();
            },
            'photos' => function ($q3) {
                $q3->select();
            },
            'user' => function ($q4) {
                $q4->select();
            }
            // ))->whereIn('id',$teams_array)->get();
        ))->whereIn('id', $teams_array)->orderBy('isactive', 'desc')->get();
        if (count($team_details)) {
            $result = $team_details->toArray();
        }
        return $result;
    }

    function getorgDetails($id)
    {
        $user_id = (isset(Auth::user()->id) ? Auth::user()->id : 0);
        $teams = Team::select('id', 'name')->where('organization_id', $id)->get()->toArray();
        $photo = Photo::select('url')->where('imageable_id', '=', $id)->where('imageable_type', '=',
            config('constants.PHOTO.TEAM_PHOTO'))->where('user_id',
            (isset(Auth::user()->id) ? Auth::user()->id : 0))->get()->toArray();
        $orgInfoObj = Organization::find($id);

        return view('teams.teams')->with(array(
            'teams' => $teams,
            'photo' => $photo,
            'orgInfoObj' => $orgInfoObj,
            'id' => $id,
            'userId' => $user_id
        ));
    }

    function organizationTeamlist($id, $group_id = false)
    {

        // $teams = Team::select('id','name','logo','description')->where('organization_id',$id)->get()->toArray();


        $user_id = (isset(Auth::user()->id) ? Auth::user()->id : 0);

        $teamsQuery = Team::
                join('users', 'users.id', '=', 'teams.team_owner_id')
                ->with('teamplayers.user')        ;
        if ($group_id) {
            $teamsQuery->join('organization_group_teams', 'organization_group_teams.team_id', '=', 'teams.id')
                ->where('organization_group_teams.organization_group_id', '=', $group_id);
        }
        // ->join('organization_group_teams', 'organization_group_teams.team_id', '=', 'teams.id')
        // ->join('organization_groups', 'organization_groups.id', '=', 'organization_group_teams.organization_group_id')
        $teams = $teamsQuery->select('teams.id', 'teams.name as teamname', 'teams.team_owner_id', 'teams.logo',
            'teams.description', 'users.name', 'teams.isactive', 'teams.sports_id')
            ->where('teams.organization_id', $id)
            ->orderBy('isactive', 'desc')->get();

        // $photo= Photo::select('url')->where('imageable_id', '=', $id)->where('imageable_type', '=', config('constants.PHOTO.TEAM_PHOTO'))->where('user_id', (isset(Auth::user()->id)?Auth::user()->id:0))->get()->toArray();
        $orgInfoObj = Organization::find($id);

        return view('teams.orgteams')->with(array(
            'teams' => $teams,
            'id' => $id,
            'orgInfoObj' => $orgInfoObj,
            'userId' => $user_id
        ));
    }

    //function to make team makeasteammanager
    public function makeasteammanager($team_id, $user_id)
    {
        if (is_numeric($team_id) && is_numeric($user_id)) {
            //check if there is any manager existing with that team
            $existing_manager_ids = TeamPlayers::where('team_id', $team_id)->where('role', 'manager')->get(array('id'));
            //if managers exist update their roles as players
            if (!empty($existing_manager_ids)) {
                //foreach manager id update as player
                foreach ($existing_manager_ids as $existing_manager_id) {
                    $existing_manager_id->role = 'player';
                    $existing_manager_id->save();
                }
            }
            //update given user id to manager
            $manager_ids = TeamPlayers::where('user_id', $user_id)->where('team_id', $team_id)->get(array('id'));
            if (!empty($manager_ids)) {
                //foreach manager id update as player
                foreach ($manager_ids as $manager_id) {
                    $manager_id->role = 'manager';
                    $manager_id->save();
                }
                return redirect()->back()->with('status', trans('message.team.teammanager'));
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.validation'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }

    //function to remove user from a team
    public function removeplayerfromteam($team_id, $user_id)
    {
        if (is_numeric($team_id) && is_numeric($user_id)) {
            $player_id = TeamPlayers::where('user_id', $user_id)->where('team_id', $team_id)->get(['id']);

            //remove player from the match schedule if score is not entered on that match
            Helper::removePlayersFromMatch($team_id, $user_id);

            if (count($player_id)) {
                if (TeamPlayers::whereIn('id', $player_id)->delete()) {
                    //check whether the player have any pending request and update to rejected
                    $request_ids = Requestsmodel::where('from_id', $team_id)->where('to_id', $user_id)->where('type',
                        config('constants.REQUEST_TYPE.TEAM_TO_PLAYER'))->where('action_status', 0)->get(['id']);
                    if (count($request_ids)) {
                        foreach ($request_ids as $key => $val) {
                            $this->updateNotificatios($val->id);
                            Requestsmodel::find($val->id)->delete();
                        }
                    }
                    return redirect()->back()->with('status', trans('message.team.userremoved'));
                } else {
                    return redirect()->back()->with('error_msg', trans('message.team.userremovefail'));
                }
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.validation'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }

    //function to make team makeasteamcaptain
    public function makeasteamcaptain($team_id, $user_id)
    {
        if (is_numeric($team_id) && is_numeric($user_id)) {
            //check if there is any manager existing with that team
            $existing_captain_ids = TeamPlayers::where('team_id', $team_id)->where('role', 'captain')->get(array('id'));
            //if managers exist update their roles as players
            if (!empty($existing_captain_ids)) {
                //foreach manager id update as player
                foreach ($existing_captain_ids as $existing_captain_id) {
                    $existing_captain_id->role = 'player';
                    $existing_captain_id->save();
                }
            }
            //update given user id to manager
            $captain_ids = TeamPlayers::where('user_id', $user_id)->where('team_id', $team_id)->get(array('id'));
            if (!empty($captain_ids)) {
                //foreach manager id update as player
                foreach ($captain_ids as $captain_id) {
                    $captain_id->role = 'captain';
                    $captain_id->save();
                }
                return redirect()->back()->with('status', trans('message.team.teamcaptain'));
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.validation'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }

    //function to make team makeasteamcoach
    public function makeasteamcoach($team_id, $user_id)
    {
        if (is_numeric($team_id) && is_numeric($user_id)) {
            if (TeamPlayers::setUserRole($team_id,$user_id,TeamPlayers::$ROLE_COACH,false)){
                return redirect()->back()->with('status', trans('message.team.teamcoach'));
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.validation'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }

    //function to make team makeasteamcoach
    public function removeasteamcoach($team_id, $user_id)
    {
        if (is_numeric($team_id) && is_numeric($user_id)) {
            if (TeamPlayers::setUserRole($team_id,$user_id,TeamPlayers::$ROLE_PLAYER,false)){
                return redirect()->back()->with('status', trans('message.team.coachremove'));
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.validation'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }


    //function to make team makeasteamcoach
    public function makeasteamphysio($team_id, $user_id)
    {
        if (is_numeric($team_id) && is_numeric($user_id)) {
            if (TeamPlayers::setUserRole($team_id,$user_id,TeamPlayers::$ROLE_PHYSIO,false)){
                return redirect()->back()->with('status', trans('message.team.teamphysio'));
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.validation'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }

    //function to make team makeasteamcoach
    public function removeasteamphysio($team_id, $user_id)
    {
        if (is_numeric($team_id) && is_numeric($user_id)) {
            if (TeamPlayers::setUserRole($team_id,$user_id,TeamPlayers::$ROLE_PLAYER,false)){
                return redirect()->back()->with('status', trans('message.team.physioremove'));
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.validation'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }

    //function to make team makeasteamvicecaptain
    public function makeasteamvicecaptain($team_id, $user_id)
    {
        if (is_numeric($team_id) && is_numeric($user_id)) {
            if (TeamPlayers::setUserRole($team_id,$user_id,TeamPlayers::$ROLE_VICE_CAPTAIN)){
                return redirect()->back()->with('status', trans('message.team.teamvicecaptain'));
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.validation'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }


    //function to reamove teammanager
    public function removeasteammanager($team_id, $user_id)
    {
        if (is_numeric($team_id) && is_numeric($user_id)) {
            //check if there is any manager existing with that team
            $existing_manager_ids = TeamPlayers::where('team_id', $team_id)->where('user_id', $user_id)->where('role',
                'manager')->get(array('id'));
            //if managers exist update their roles as players
            if (!empty($existing_manager_ids)) {
                //foreach manager id update as player
                foreach ($existing_manager_ids as $existing_manager_id) {
                    $existing_manager_id->role = 'player';
                    $existing_manager_id->save();
                }
                return redirect()->back()->with('success', trans('message.team.managerremove'));
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.managervalidation'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }

    //function to check whether the value exist or not
    public function isExist($existing_ids_str, $new_id)
    {
        $existing_ids_str = rtrim(ltrim($existing_ids_str, ','), ',');
        $existing_ids = explode(',', $existing_ids_str);
        if (in_array($new_id, $existing_ids)) {
            return true;
        } else {
            return false;
        }
    }

    //function to send invite reminder
    public function sendinvitereminder($team_id, $user_id)
    {
        //get the user details
        $user_details = User::where('id', $user_id)->first(array('name', 'email'))->toArray();
        if (!empty($user_details)) {
            $user_name = $user_details['name'];
            $to_email_id = $user_details['email'];
            //blade view for sendinvitereminder from emails folder in views
            $view = 'emails.sendinvitereminder';
            $subject = 'Reminder for invite';
            $view_data = array('user_name' => $user_name);
            //send the data to sendmail
            $data = array(
                'view' => $view,
                'subject' => $subject,
                'to_email_id' => $to_email_id,
                'view_data' => $view_data,
                'to_user_id' => $user_id,
                'flag' => 'user',
                'send_flag' => 1
            );
            if (SendMail::sendmail($data)) {
                return redirect()->back()->with('status', trans('message.team.Invitation'));
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.Invitationfail'));
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.validation'));
        }
    }


    /*		//function to editanddeleteteam the team
        public function editanddeleteteam()
        {
            $request = Request::all();
            $delete_id = Request::get('delete');
            $edit_id = Request::get('modify');
            $enum = config('constants.ENUM.TEAMS.TEAM_LEVEL');
            if(is_numeric($delete_id) && $delete_id > 0)
            {
                if(Team::find($delete_id)->delete())
                {
                    return redirect()->back()->with('status',  trans('message.team.teamdelete'));
                }
                else
                {
                    return redirect()->back()->with('error_msg', trans('message.team.teamdeletefail'));
                }
            }
            else
            {
                $teamdetails = Team::where('id',$edit_id)->first();
                $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
                            $organization = Organization::orderBy('name')->lists('name', 'id')->all();
                $cities = array();
                if ($teamdetails->state_id) {
                    $cities = City::where('state_id', $teamdetails->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
                }
                $sports = Sport::orderBy('sports_name')->lists('sports_name', 'id')->all();
                return view('teams.editteam')->with(array('sports'=>['' => 'Select Sport'] + $sports))
                                                ->with('states', ['' => 'Select State'] + $states)
                                                ->with('cities', ['' => 'Select City'] + $cities)
                                                ->with('teamdetails' , $teamdetails)
                                                ->with('organization', ['' => 'Select Organization'] + $organization)
                                                ->with('id' , $edit_id)
                                                 ->with('enum', ['' => 'Team Level'] + $enum);
            }
        }*/

    //to edit team
    public function editteam($team_id)
    {
        $request = Request::all();
        $edit_id = $team_id;
        $teamdetails = array();
        $cities = array();
        $enum = config('constants.ENUM.TEAMS.TEAM_LEVEL');
        if (is_numeric($edit_id)) {
            $teamdetails = Team::where('id', $edit_id)->first();
        }
        $groupsList = [];
        $groupId = null;
        if ($teamdetails->organization_id) {
            $groupsList =
                OrganizationGroup::whereOrganizationId($teamdetails->organization_id)
                    ->lists('name', 'id');

            $teamGroup = $teamdetails->organizationGroups()
                ->where('organization_id',
                    $teamdetails->organization_id)
                ->first();

            if ($teamGroup) {
                $groupId = $teamGroup->id;
            }
        }

        $organization = Organization::orderBy('name')->where('user_id',
            (isset(Auth::user()->id) ? Auth::user()->id : 0))->lists('name', 'id')->all();
        $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
        $states = State::where('country_id', $teamdetails->country_id)->orderBy('state_name')->lists('state_name',
            'id')->all();
        if (isset($teamdetails->state_id) && is_numeric($teamdetails->state_id)) {
            $cities = City::where('state_id', $teamdetails->state_id)->orderBy('city_name')->lists('city_name',
                'id')->all();
        }
//		$sports = Sport::orderBy('sports_name')->lists('sports_name', 'id')->all();
        $sports = Helper::getDevelopedSport(1, 1);
        return view('teams.editteam')->with(array('sports' => ['' => 'Select Sport'] + $sports))
            ->with('countries', ['' => 'Select Country'] + $countries)
            ->with('states', ['' => 'Select State'] + $states)
            ->with('cities', ['' => 'Select City'] + $cities)
            ->with('teamdetails', $teamdetails)
            ->with('organization', ['' => 'Select Organization'] + $organization)
            ->with('id', $edit_id)
            ->with('enum', ['' => 'Team Level'] + $enum)
            ->with('groupId', $groupId)
            ->with('groupsList', $groupsList);
    }

    //function to delete the team
    public function deleteteam($team_id, $flag)
    {
        $request = Request::all();
        $delete_id = $team_id;
        if (is_numeric($delete_id) && $delete_id > 0) {
            if ($flag == 'd') {
                if (Team::where('id', $delete_id)->update(['isactive' => 0])) {
                    return redirect()->back()->with('status', trans('message.team.teamdelete'))->with('div_sel_mgt',
                        'active');
                } else {
                    return redirect()->back()->with('error_msg',
                        trans('message.team.teamdeletefail'))->with('div_sel_mgt', 'active');
                }
            } elseif ($flag == 'a') {
                if (Team::where('id', $delete_id)->update(['isactive' => 1])) {
                    return redirect()->back()->with('status', trans('message.team.teamactivate'))->with('div_sel_mgt',
                        'active');
                } else {
                    return redirect()->back()->with('error_msg',
                        trans('message.team.teamactivatefail'))->with('div_sel_mgt', 'active');
                }
            } else {
                return redirect()->back()->with('error_msg', trans('message.team.teamupdatefail'))->with('div_sel_mgt',
                    'active');
            }
        } else {
            return redirect()->back()->with('error_msg', trans('message.team.teamupdatefail'))->with('div_sel_mgt',
                'active');
        }
    }

    //function to get the team details, used in team list
    // function getteamdetails($teams_array='')
    // {
    // $result = array();
    // $team_details = Team::with(array(
    // 'teamplayers'=>function($q1){
    // $q1->select();
    // },
    // 'sports'=>function($q2){
    // $q2->select();
    // },
    // 'photos'=>function($q3){
    // $q3->select();
    // },
    // 'user'=>function($q4)
    // {
    // $q4->select();
    // }
    //))->whereIn('id',$teams_array)->get();
    // ))->whereIn('id',$teams_array)->get();
    // if(count($team_details))
    // {
    // $result = $team_details->toArray();
    // }
    //	echo "<pre>";print_r($result );exit;
    // return $result;
    // }
    //function to update the availability of team/player
    public function updateplayerorteamavailability()
    {
        $request = Request::all();
        $team_id = is_numeric($request['team_id']) ? $request['team_id'] : null;
        $flag = !empty($request['flag']) ? trim($request['flag']) : null;
        $column_name = ($flag == 'team_available') ? 'team_available' : (($flag == 'player_available') ? 'player_available' : null);
        $update_value = ($request['update_value'] == 'true') ? 1 : 0;
        $response = 'fail';
        if ($team_id && $flag && $column_name) {
            $result = Team::where('id', $team_id)->update([
                $column_name => $update_value,
                'updated_at' => Carbon::now()
            ]);
            if ($result) {
                $response = 'success';
            }
        }
        $return_val = Team::where('id', $team_id)->pluck($column_name);
        return Response::json(['status' => $response, 'return_val' => $return_val]);
    }

    //function to update savetournamentorplayer
    public function saverequest()
    {
        $request = Request::all();
        return AllRequests::saverequest($request);
    }

    //function to show the requests
    public function getteamrequests($id)
    {
        Helper::leftMenuVariables($id);
        $limit = config('constants.LIMIT',20);
        $type_ids = [
                    config('constants.REQUEST_TYPE.PLAYER_TO_TEAM'),
                    config('constants.REQUEST_TYPE.TEAM_TO_PLAYER'),
                    config('constants.REQUEST_TYPE.TEAM_TO_TOURNAMENT'),
                    config('constants.REQUEST_TYPE.TEAM_TO_TEAM')];

        $type = \Request::get('type');
        $received = false;
        if ($type == null || $type === 'received') {
            $received = Requestsmodel::requestQuery($type_ids)
                ->where('request.to_id', $id)
                ->paginate($limit)
                ->appends('type','received');
        }
        $sent = false;
        if ($type == null || $type === 'sent') {
            $sent = Requestsmodel::requestQuery($type_ids)
                ->where('request.from_id', $id)
                ->paginate($limit)
                ->appends('type','sent');
        }

        if ($type){
            if ($received)
                return view('common.requestsLists',['items'=>$received,'flag'=>'in']);
            if ($sent)
                return view('common.requestsLists',['items'=>$sent]);
            return \App::abort(404);
        };

        return view('teams.teamrequests', compact('received','sent'));
    }

       //fucntion to accept/reject request
    public function acceptrejectrequest()
    {
        $request = Request::all();
        $request_id = (!empty($request['request_id']) && is_numeric($request['request_id'])) ? $request['request_id'] : 0;
        $notification_id = (!empty($request['notid']) && is_numeric($request['notid'])) ? $request['notid'] : 0;
        // flag a=accept, r=reject, d=delete, c=cancel
        $flag = (!empty($request['flag']) && ($request['flag'] == 'a' || $request['flag'] == 'r' || $request['flag'] == 'd' || $request['flag'] == 'c')) ? $request['flag'] : null;
        $response = 'fail';
        $req_data = AllRequests::getrequestdetails($request_id);
        $request_id_status = $req_data['action_status'];
        if (!empty($request_id) && !empty($flag) && $request_id_status == 0) {
            if ($flag == 'd') {
                $result = Requestsmodel::find($request_id)->delete();
                $this->updateNotificatios($request_id);
            } else {
                $act_status = 3;
                if ($flag == 'a') {
                    $act_status = 1;
                } elseif ($flag == 'r') {
                    $act_status = 2;
                }
                $result = Requestsmodel::where('id', $request_id)->where('action_status',
                    0)->update(['action_status' => $act_status]);
                $this->updateNotificatios($request_id);
                if ($request['flag'] != 'c') {
                    $request_data = AllRequests::getrequestdetails($request_id);
                    //IF request is from team to team, update match schedule table
                    if ($request_data['type'] == config('constants.REQUEST_TYPE.TEAM_TO_TEAM') || $request_data['type'] == config('constants.REQUEST_TYPE.PLAYER_TO_PLAYER')) {
                        $match_schedule_id = $this->getmatchshceduleid($request_id);
                        if (!empty($match_schedule_id)) {
                            $this->updatematchschedules($match_schedule_id, ($flag == 'a' ? 'accepted' : 'rejected'));
                        }
                    } elseif ($request_data['type'] == config('constants.REQUEST_TYPE.TEAM_TO_PLAYER')) {
                        AllRequests::updateteamplayerstatus($request_data['to_id'], $request_data['from_id'], $flag);
                    } elseif ($request_data['type'] == config('constants.REQUEST_TYPE.PLAYER_TO_TEAM')) {
                        AllRequests::addplayer($request_data['from_id'], $request_data['to_id']);
                        AllRequests::updateteamplayerstatus($request_data['from_id'], $request_data['to_id'], $flag);
                    }
                    //code to send response notification
                    AllRequests::sendResponseNotification($request_id);
                }

            }
            if ($result) {
                $response = 'success';
            }
        } elseif (!empty($notification_id) && !empty($flag) && $flag == 'd') {
            $this->updateNotificatios($notification_id, 1);
            $response = 'success';
        }
        $notifications_count = Helper::getNotificationsCount();
        return Response::json(['result' => $response, 'notifications_count' => $notifications_count]);
    }

    //function to update match schedules
    public function updatematchschedules($id, $flag)
    {
        //update match schdule
        MatchSchedule::where('id', $id)->where('match_status', 'scheduled')->update(['match_invite_status' => $flag]);
    }

    //function to get the teams
    public function getteams()
    {
        $request = Request::all();
        $sport_id = !empty($request['sport_id']) ? $request['sport_id'] : 0;
        $sport_type = Sport::where('id', $sport_id)->pluck('sports_type');
        $request_type = !empty($request['req_type']) ? $request['req_type'] : null;
        $player_tournament_id = !empty($request['player_tournament_id']) ? $request['player_tournament_id'] : 0;
        $user_id = (isset(Auth::user()->id) ? Auth::user()->id : 0);
        $teams = array();
        if ($request_type == 'TEAM_TO_TOURNAMENT' || $request_type == 'TEAM_TO_PLAYER') {
            $teams = Helper::getManagingTeamsForPopUp($user_id, $sport_id, $request_type, $player_tournament_id);
        }
        return Response::json(['teams' => $teams, 'sport_type' => $sport_type]);
    }

    public function searchUser($sport_id, $team_id)
    {
        $user = Request::get('term');
        //$sport_id = Request::get('sport_id');//sport ids
        //$team_id = Request::get('team_id');
        $results = array();

        $team_player_qry = DB::table('team_players')
            ->select('team_players.user_id')
            ->where('team_id', '=', $team_id)
            ->whereNull('deleted_at')
            ->get();
        $team_user_id = array();
        foreach ($team_player_qry as $team) {
            $team_user_id[] = $team->user_id;
        }
        // removed ->where('users.is_available','=','1')
        // removed ->where('user_statistics.allowed_sports','LIKE','%,'.$sport_id.',%')
        $users = array();
        $users = DB::table('users')
            ->join('user_statistics', 'users.id', '=', 'user_statistics.user_id')
            ->select('users.id', 'users.name')
            ->where('users.name', 'LIKE', '%' . $user . '%')
            ->where('user_statistics.allowed_sports', 'LIKE', '%,' . $sport_id . ',%')
            ->whereNotIn('users.id', $team_user_id)
            ->get();
        if (count($users) > 0) {
            foreach ($users as $query) {
                $results[] = ['id' => $query->id, 'value' => $query->name];
            }
        }
        return Response::json($results);
    }

    public function addplayer()
    {
        $user_id = Request::get('response');
        if ($user_id > 0) {
            $status = null;
            $team_id = Request::get('team_id');
            $role = 'player';
            $user_id = Request::get('response');
            $TeamPlayer = new TeamPlayers();
            $TeamPlayer->team_id = $team_id;
            $TeamPlayer->user_id = $user_id;
            $TeamPlayer->role = $role;
            $TeamPlayer->save();
            $last_inserted_id = $TeamPlayer->id;


            //insert players in match schedule table if match is scheduled on that team
            $insertTeamPlayers = Helper::insertTeamPlayersInSchedules($team_id, $user_id);

            //return Response::json($results);
            //get the data by joining teams, teamplayers and users table
            /*$teams = Team::with(array(
                'teamplayers'=>function($q1) use ($status, $last_inserted_id) {
                    if(!empty($status))
                    {
                        $q1->select()->where('status',$status)->where('id',$last_inserted_id);
                    }
                    else
                    {
                        $q1->select()->where('id',$last_inserted_id);
                    }
                },
                'teamplayers.user'=>function($q2){
                    $q2->select();
                },
                'teamplayers.user.photos'=>function($q3){
                    $q3->select();
                }
            ))->where('team_owner_id',(isset(Auth::user()->id)?Auth::user()->id:0))->where('id',$team_id)->get();
            $teams = $teams->toarray();
            $team_owners_managers = array();
            $team_players = array();
            //if team's details are not empty
            if(!empty($teams))
            {
                //static array for owner,manager and coach
                $owners_managers = array('owner','manager','coach');
                foreach($teams as $team)
                {
                    //if teamplayer's details are not empty
                    if(!empty($team['teamplayers']))
                    {
                        foreach($team['teamplayers'] as $teamdetails)
                        {
                            if(!empty($teamdetails['role']))
                            {
                                $role = trim($teamdetails['role']);
                                //if the role is owner, manager or coach push in to team_owners_managers array
                                if(in_array($role,$owners_managers))
                                {
                                    array_push($team_owners_managers,$teamdetails);
                                }
                                else //else push the data into team_players array
                                {
                                    array_push($team_players,$teamdetails);
                                }
                            }
                        }
                    }
                }
            }
            //get following sports
            $userId = (isset(Auth::user()->id)?Auth::user()->id:0);
            $following_sportids = UserStatistic::where('user_id', $userId)->pluck('following_sports');
            // Helper::setMenuToSelect(2,1);
            $sport = !empty($teams[0]['sports_id']) ? Sport::where('id', $teams[0]['sports_id'])->pluck('sports_name') : '';
            $sport_id = !empty($teams[0]['sports_id'])?$teams[0]['sports_id']:0;
            Helper::leftMenuVariables($team_id);
            $managing_teams = Helper::getManagingTeams($userId,$sport_id);
            if(count($managing_teams))
            {
                $managing_teams	= $managing_teams->toArray();
            }
            //get role for the logged in user id
            $logged_in_user_role = TeamPlayers::where('user_id', $userId)->where('team_id',$team_id)->pluck('role');

            $returnHTML =  view('teams.myteamplayers')->with('teams',$teams)->with('team_owners_managers',$team_owners_managers)->with('team_players',$team_players)->with('logged_in_user_role',$logged_in_user_role)->with('following_sportids',$following_sportids)->with('managing_teams',$managing_teams)->with('sport_id',$sport_id)->render();
            return \Response::json(array('status'=>'success','msg' => trans('message.sports.teamplayer'),'html' =>$returnHTML));
                //return Response()->json( array('status' => 'success','msg' => trans('message.sports.teamplayer')) );*/
            return $this->getTeamPlayersDiv($request_array = array('team_id' => $team_id));
        } else {
            return Response()->json(array('status' => 'fail', 'msg' => trans('message.sports.invalidplayername')));
        }
    }

    public function changeOwnership()
    {
        $data = \Request::all();
        $validator = \Validator::make($data, [
            'teamId' => 'required|exists:teams,id',
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);

        $status = false;
        $error = false;
        if (!$validator->fails()) {
            $user = User::where('email', $data['email'])->first();
            $team = Team::where('id', $data['teamId'])->first();

            $organization = Organization::whereId($team->organization_id)->first();

            // TODO: ?use policies //if (Gate::check(''))

            if ($team && $organization &&
                ($organization->user_id == \Auth::user()->id || $team->team_owner_id == \Auth::user()->id )
            ) {
                if (!$user) {
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email']
                    ]);
                    \Event::fire(new UserRegistered($user));
                }
                if ($user) {
                    TeamPlayers::where('team_id',$team->id)->where('role','owner')->where('user_id','!=',$user->id)->update(['role'=>'player']);
                    $teamplayer = TeamPlayers::where('team_id',$team->id)->where('user_id',$user->id)->first();
                    if ($teamplayer) {
                        $teamplayer->role = 'owner';
                        $teamplayer->save();
                    } else {
                        $teamplayer= new TeamPlayers();
                        $teamplayer->user_id = $user->id;
                        $teamplayer->team_id = $team->id;
                        $teamplayer->role = 'owner';
                        $teamplayer->status = 'accepted';
                        $teamplayer->save();
                    }
                    $team->team_owner_id = $user->id;
                    $team->save();
                    \Event::fire(new TeamOwnershipChanged($user,$team));

                    $status = 'Ownership changed to '.$user->name;
                } else {
                    $error = 'Failed to create user';
                }
            } else {
                $error = 'Permission denied';
            }
        } else {
            $error = $validator->errors()->first();
        }

        if (\Request::isJson()) {
            $result =  [];
            if ($status){
                $result['status']=$status;
            }
            if ($error){
                $result['error']=$error;
            }
            return $result;
        } else {
            if ($status) {
                \Session::flash('status', $status);
            }
            if ($error) {
                \Session::flash('error', $error);
            }
            return redirect()->back();
        }

    }

    //function to get team players for ajax
    public function getTeamPlayersDiv()
    {
        $request = Request::all();
        $team_id = !empty($request['team_id']) ? $request['team_id'] : 0;
        $status = null;
        //get the data by joining teams, teamplayers and users table
        $teams_query = Team::with(array(
            'teamplayers' => function ($q1) use ($status) {
                if (!empty($status)) {
                    $q1->select()->where('status', $status)->orderBy('status', 'asc');
                } else {
                    $q1->select()->whereNotIn('status', ['rejected'])->orderBy('status', 'asc');
                }
            },
            'teamplayers.user' => function ($q2) {
                $q2->select();
            },
            'teamplayers.user.photos' => function ($q3) {
                $q3->select();
            }
        ));
        $managingTeamIds = Helper::getManagingTeamIds((isset(Auth::user()->id) ? Auth::user()->id : 0));
        $managing_team_ids = explode(',', trim($managingTeamIds, ','));
        //if the team id is in managing team ids, then add owner conditioin
        if (in_array($team_id, $managing_team_ids)) {
            $teams_query->where('team_owner_id', (isset(Auth::user()->id) ? Auth::user()->id : 0));
        }
        $teams = $teams_query->where('id', $team_id)->get();
        $teams = $teams->toarray();
        $team_owners_managers = array();
        $team_players = array();


        //if team's details are not empty
        if (!empty($teams)) {
            //static array for owner,manager and coach
            $owners_managers = ['owner', 'manager', TeamPlayers::$ROLE_COACH ,TeamPlayers::$ROLE_PHYSIO];
            foreach ($teams as $team) {
                //if teamplayer's details are not empty
                if (!empty($team['teamplayers'])) {
                    foreach ($team['teamplayers'] as $teamdetails) {
                        if (!empty($teamdetails['role'])) {
                            $role = trim($teamdetails['role']);
                            //if the role is owner, manager or coach push in to team_owners_managers array
                            if (in_array($role, $owners_managers)) {
                                array_push($team_owners_managers, $teamdetails);
                            } else //else push the data into team_players array
                            {
                                array_push($team_players, $teamdetails);
                            }
                        }
                    }
                }
            }
        }
        //get following sports
        $userId = (isset(Auth::user()->id) ? Auth::user()->id : 0);
        $following_sportids = UserStatistic::where('user_id', $userId)->pluck('following_sports');
        // Helper::setMenuToSelect(2,1);
        $sport = !empty($teams[0]['sports_id']) ? Sport::where('id', $teams[0]['sports_id'])->pluck('sports_name') : '';
        $sport_id = !empty($teams[0]['sports_id']) ? $teams[0]['sports_id'] : 0;
        Helper::leftMenuVariables($team_id);
        $managing_teams = Helper::getManagingTeams($userId, $sport_id);
        if (count($managing_teams)) {
            $managing_teams = $managing_teams->toArray();
        }
        //get role for the logged in user id
        $logged_in_user_role = TeamPlayers::where('user_id', $userId)->where('team_id', $team_id)->pluck('role');
        $returnHTML = view('teams.myteamplayers')->with('teams', $teams)->with('team_owners_managers',
            $team_owners_managers)->with('team_players', $team_players)->with('logged_in_user_role',
            $logged_in_user_role)->with('following_sportids', $following_sportids)->with('managing_teams',
            $managing_teams)->with('sport_id', $sport_id)->render();
        return \Response::json(array(
            'status' => 'success',
            'msg' => trans('message.sports.teamplayer'),
            'html' => $returnHTML
        ));
    }

    //function to update notifications table
    public function updateNotificatios($request_id, $flag = 0)
    {
        if ($flag == 0) {
            Notifications::where('request_id', $request_id)->update(['is_read' => 1]);
        } else {
            Notifications::where('id', $request_id)->update(['is_read' => 1]);
        }
    }

    public function getCities($service, $sport, $search_by)
    {
        $term = Request::get('term');
        $results = array();
        if ($service == 'team') {
            $users = DB::table('teams')
                ->select('id', 'location', 'name')
                ->where('location', 'LIKE', '%' . $term . '%')
                ->orwhere('name', 'LIKE', '%' . $term . '%')
                ->get();
        }
        if ($service == 'tournament') {
            $users = DB::table('tournaments')
                ->select('id', 'location', 'name')
                ->where('location', 'LIKE', '%' . $term . '%')
                ->orwhere('name', 'LIKE', '%' . $term . '%')
                ->get();
        }
        if ($service == 'user') {
            $users = DB::table('users')
                ->select('id', 'location', 'name')
                ->where('location', 'LIKE', '%' . $term . '%')
                ->orwhere('name', 'LIKE', '%' . $term . '%')
                ->get();
        }
        if ($service == 'marketplace') {
            $users = DB::table('marketplace')
                ->select('id', 'location', 'item')
                ->where('location', 'LIKE', '%' . $term . '%')
                ->orwhere('item', 'LIKE', '%' . $term . '%')
                ->get();
        }


        if (count($users) > 0) {
            foreach ($users as $query) {

                if ($service == 'team') {
                    $name = $query->name;
                    $link = url('team/members/' . $query->id);
                }
                if ($service == 'tournament') {
                    $name = $query->name;
                    $link = url('tournaments/groups/' . $query->id);

                }
                if ($service == 'user') {
                    $name = $query->name;
                    $link = url('editsportprofile/' . $query->id);
                }
                if ($service == 'marketplace') {
                    $name = $query->item;
                    $link = url('marketplace');
                }


                $results[] = ['id' => $query->id, 'value' => '(' . $name . ')' . $query->location, 'link' => $link];
            }
        }
        return Response::json($results);
    }

    //function to get match schedule id form request table
    public function getmatchshceduleid($request_id)
    {
        $match_schedule_id = Requestsmodel::where('id', $request_id)->pluck('id_to_update');
        if (!empty($match_schedule_id)) {
            return $match_schedule_id;
        }
        return false;
    }

    /**
     * @param \App\Model\Team $team_details
     * @param $groupId
     */
    private function attachOrganizationGroupToTeam(Team $team_details, $groupId)
    {
        $team_details->organizationGroups()->attach($groupId);
    }
}

