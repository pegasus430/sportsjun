<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Sport;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\UserStatistic;
use App\Model\Facilityprofile;
use App\Model\Organization;
use App\User;
use Auth;
use URL;
use App\Model\Photo;
use Zofe\Rapyd\RapydServiceProvider;
use DB;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Helpers\SendMail;
use View;
use App\Helpers\AllRequests;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
		$sports_array2 = Sport::select('sports_name', 'id')->get();
		$sports_array1 = array('0'=>'select sport');
		$sport_id_arr=array();
		
		foreach($sports_array2  as $cat)
		{
			$sports_array1[$cat->id] = $cat->sports_name;
		}
		
       	$filter = \DataFilter::source(Team::with('sport'));	
			
        $filter->add('name', 'Name', 'text');
        $filter->add('sports_id','Sports name','select')->options($sports_array1)
        ->scope( function ($query, $value) use ($sports_array2) {
			
			 if($value>0)
			 {
				
				 return $query->whereIn('sports_id', [$value] );  
			 }else if($value==0)
			 {
				 $sports_array2 = Sport::where('isactive','=',1)->lists('sports_name', 'id')->toArray();
				 foreach($sports_array2 as $key => $val)
				 {
					 $sport_id_arr[] = $key;
				 }
				  return $query->whereIn('sports_id', $sport_id_arr);  
				  
			 }
			
			});	
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
        $grid = \DataGrid::source($filter);
        $grid->attributes(array("class" => "table table-striped"));
      
	//	$grid->add('<a href="/adminteam/members/{{ $id }}">{{ $name }}</a>','NAME');
		$grid->add('{{ $name }}','NAME');
		//$grid->add('name', 'NAME', true);
	    $grid->add('{{ implode(", ", $sport->lists("sports_name")->all()) }}','sports Name');
				$grid->add('Status','Status')->cell( function( $value, $row) {
			if($row->isactive==1)
			{
				return 'Active';
			}else{
				return 'InActive';
			}
			
		}); 
        $grid->edit( url('admin/teamedit'), 'Operation', 'modify|delete');
        $grid->orderBy('id', 'desc');
        $grid->link('admin/team/create', "New Team", "TR");
        $grid->paginate(config('constants.DEFAULT_PAGINATION'));
        return view('admin.teams.filtergrid', compact('filter', 'grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $sports = Sport::orderBy('sports_name')->lists('sports_name', 'id')->all();
        $sports = Helper::getDevelopedSport(1,1);
		$enum = config('constants.ENUM.TEAMS.TEAM_LEVEL');    
		$states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
                $organization = Organization::orderBy('name')->lists('name', 'id')->all();
                $cities = array();
		return view('admin.teams.createteam')->with(array('sports'=>['' => 'Select Sport'] + $sports))
										->with('states', ['' => 'Select State'] + $states)
										->with('cities', ['' => 'Select City'] + $cities)
                                        ->with('organization', ['' => 'Select Organization'] + $organization)
										->with('enum', $enum);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateTeamRequest $request)
    {
		//echo Auth::user()->id;exit;
        		//getting team owner id from session logged in user id
        //$request['team_owner_id'] = Auth::user()->id;
         $request['country_id'] = config('constants.COUNTRY_INDIA');
        $request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
        $request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
		 $request['team_owner_id']= !empty($request['user_id']) ? $request['user_id'] : '';
		//model call to save the data
		$team_details = Team::create($request->all());
			if(!empty($request['team_owner_id']))
				{
									
					//new owner notification code
					$message=  trans('message.team.new_owner_notification');
					$url= url('team/members/'.$team_details['id']) ;
					AllRequests::sendnotifications($request['team_owner_id'],$message,$url);
														
					//add to user statistics table
					$existing_user_details = UserStatistic::where('user_id', $request['team_owner_id'])->first();
					if(count($existing_user_details))
					{
						if(!$this->isExist($existing_user_details['managing_teams'],$team_details['id']))
						{
							$existing_user_details->managing_teams = $existing_user_details['managing_teams'].$team_details['id'].',';
						}
						$existing_user_details->save();
					}
					else
					{
						$user_statistic_details = array(
						'user_id' => $request['team_owner_id'],
						'managing_teams' => ','.$team_details['id'].',');
						UserStatistic::create($user_statistic_details);						
					}
					
				}
		if(!empty($team_details))
		{
			$team_id = isset($team_details['id'])?$team_details['id']:0;
                        if(count($request['filelist_logo'])) {
                            Helper::uploadPhotos($request['filelist_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),$team_id,1,1,config('constants.PHOTO.TEAM_PHOTO'),Auth::user()->id);
                        }
			$logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->where('imageable_id',  $team_details['id'])->where('user_id', Auth::user()->id)->where('is_album_cover',1)->get()->toArray();
			if(!empty($logo))
			{
				foreach($logo as $l)
				{
					  Team::where('id', $team_details['id'])->update(['logo' => $l['url']]);
					
				}
				
			 }				
						
			if(is_numeric($team_id))
			{
				//insert into team players table
                $teamplayer_details = array(
					'team_id' => $team_id,
					'user_id' => $request['user_id'],
					'role' => 'owner',
					'status' => 'accepted',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now());
				if(TeamPlayers::create($teamplayer_details))
				{
					//insert into user statistics table
					//check if there is any manager existing with that team
					//if exist insert else update
					$existing_user_details = UserStatistic::where('user_id', Auth::user()->id)->first();
					if(count($existing_user_details))
					{
						
						if(!$this->isExist($existing_user_details['following_sports'],$request['sports_id']))
						{
							$existing_user_details->following_sports = $existing_user_details['following_sports'].$request['sports_id'].',';
						}
						if(!$this->isExist($existing_user_details['following_teams'],$team_id))
						{
							$existing_user_details->following_teams = $existing_user_details['following_teams'].$team_id.',';							
						}
						if(!$this->isExist($existing_user_details['managing_teams'],$team_id))
						{
							$existing_user_details->managing_teams = $existing_user_details['managing_teams'].$team_id.',';							
						}
						if(!$this->isExist($existing_user_details['joined_teams'],$team_id))
						{
							$existing_user_details->joined_teams = $existing_user_details['joined_teams'].$team_id.',';							
						}
						$existing_user_details->save();
					}
					else
					{
						$user_statistic_details = array(
						'user_id' => Auth::user()->id,
						'following_sports' => ','.$request['sports_id'].',',
						'following_teams' => ','.$team_id.',',
						'managing_teams' => ','.$team_id.',',
						'joined_teams' => ','.$team_id.',');
						UserStatistic::create($user_statistic_details);						
					}
					//redirect to create team page with status message
					return redirect()->route('admin.team.index')->with('status', trans('message.team.create'));
				}
				else
				{
					return redirect()->route('admin.team.index')->with('status', trans('message.team.createfail'));
				}
            }
			else
			{
				return redirect()->route('admin.team.index')->with('status', trans('message.team.createfail'));
			}
		}
		else
		{
			return redirect()->route('admin.team.index')->with('status', trans('message.team.createfail'));
		}
    }
	public function isExist($existing_ids_str,$new_id)
	{
		$existing_ids_str = rtrim(ltrim($existing_ids_str,','),',');
		$existing_ids = explode(',',$existing_ids_str);
		if(in_array($new_id,$existing_ids))
		{
			return true;
		}
		else
		{
			return false;
		}
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
      $request = $request->all();
		if(is_numeric($id))
		{
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
			$country = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
			$state = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
			$city  = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
			$team_owner_id  = !empty($request['user_id']) ? $request['user_id'] : '';
			
			$old_owner_id = Team::where('id', $id)->pluck('team_owner_id');
			
			$location=Helper::address(	$address,$city,$state,$country );
	        $location=trim($location,",");
			$player_available = !empty($request['player_available'])?$request['player_available']:0;
			$team_available = !empty($request['team_available'])?$request['team_available']:0;
			if(!empty($sports_id) && !empty($name))
			{
				//update team table
				Team::where('id', '=', $id)->update(['sports_id'=>$sports_id,'name'=>$name,'location'=>$location,'address'=>$address,'city_id'=>$city_id,'city'=>$city,'state_id'=>$state_id,'country_id'=>$country_id,'country'=>$country,'zip'=>$zip,'description'=>$description,'player_available'=>$player_available,'team_available'=>$team_available,'updated_at'=>Carbon::now(),'team_level'=>$request['team_level'],'gender'=>$gender,'organization_id'=>$organization_id,'team_owner_id'=>$team_owner_id]);
				
				
				TeamPlayers::where('team_id', '=', $id)->update(['team_id' => $id, 'user_id' =>$team_owner_id ,'role' => 'owner','status' => 'accepted','updated_at'=>Carbon::now()]);
		
				//send notification to user if team owner has been changed by admin
				if($old_owner_id!=$team_owner_id)
				{
					
					//old owner notification code
					$old_owner_message=  trans('message.team.old_owner_notification');
					$old_owner_url= url('team/members/'.$id) ;
					AllRequests::sendnotifications($old_owner_id,$old_owner_message,$old_owner_url);
					
					//new owner notification code
					$message=  trans('message.team.new_owner_notification');
					$url= url('team/members/'.$id) ;
					AllRequests::sendnotifications($team_owner_id,$message,$url);
					
					
					//add to user statistics table
					$existing_user_details = UserStatistic::where('user_id', $team_owner_id)->first();
					if(count($existing_user_details))
					{
						if(!$this->isExist($existing_user_details['managing_teams'],$id))
						{
							$existing_user_details->managing_teams = $existing_user_details['managing_teams'].$id.',';
						}
						$existing_user_details->save();
					}
					else
					{
						$user_statistic_details = array(
						'user_id' => $team_owner_id,
						'managing_teams' => ','.$id.',');
						UserStatistic::create($user_statistic_details);						
					}
					
					//delete previous user
					 $existingManagedTeamString = UserStatistic::where('user_id', $old_owner_id)->pluck('managing_teams');
					$updatedmanaging_teamsString = $this->removeFromArray($existingManagedTeamString,$id);
					UserStatistic::where('user_id', $old_owner_id)->update(['managing_teams'=>$updatedmanaging_teamsString,'updated_at'=>Carbon::now()]);
				}
				
				
				if(!empty($request['filelist_logo']))
				{
					//update existing album cover to 0
					Photo::where('imageable_id', '=', $id)->where('imageable_type', '=', config('constants.PHOTO.TEAM_PHOTO'))->update(['is_album_cover'=>0,'updated_at'=>Carbon::now()]);
					//update new photo
					Helper::uploadPhotos($request['filelist_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),$id,1,1,config('constants.PHOTO.TEAM_PHOTO'),Auth::user()->id);
				}
				$logo=Photo::select('url')->where('is_album_cover','=','1')->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->get()->toArray();
					if(!empty($logo))
					{
						foreach($logo as $l)
						{
							  Team::where('id', $id)->update(['logo' => $l['url']]);
						}
						
					}
		
				    return redirect()->route('admin.team.index')->with('status', trans('message.team.update'));
			}
			else
			{
				
					 return redirect()->route('admin.team.index')->with('status', trans('message.team.updatefail'));
			}			
		}
		else
		{
			
			return redirect()->route('admin.team.index')->with('status', trans('message.team.updatefail'));
		}
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
	public function editTeam(Request $request)
    {
		
        $delete_team_id = $request->delete;
		$edit_team_id = $request->modify; 
		if( $delete_team_id !='' && $delete_team_id >0)
		{
		   // $team=Team::find($delete_team_id)->delete();
		   $team_status  = Team::where('id',$delete_team_id)->pluck('isactive');
		   if($team_status==1)
		   {
			   Team::where('id', '=', $delete_team_id)->update(['isactive'=>0]);
				return redirect()->route('admin.team.index')->with('error_msg', 'Team is Inactivated.');
		   }else
		   {
			   Team::where('id', '=', $delete_team_id)->update(['isactive'=>1]);
				return redirect()->route('admin.team.index')->with('status', 'Team is Activated.');
		   }
			
		}
		else if(	$edit_team_id!='' && 	$edit_team_id>0)
		{
			 $team=Team::findOrFail($edit_team_id );
//			 	$sports = Sport::orderBy('sports_name')->lists('sports_name', 'id')->all();
                         $sports = Helper::getDevelopedSport(1,1);
				                $organization = Organization::orderBy('name')->lists('name', 'id')->all();
									$enum = config('constants.ENUM.TEAMS.TEAM_LEVEL');    
			$owner_name='';
			$ownerName = User::where('id',$team['team_owner_id'])->first(['name']);
			if(count($ownerName))
				$owner_name = $ownerName->name;						
	        $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
	        $cities = City::where('state_id',  $team->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
	        return view('admin.teams.editteam',compact('team'))->with(array('id'=>	$edit_team_id ,'states' =>  $states,'cities' => $cities,'roletype'=>'admin','sports'=> ['' => 'Select Sport'] + $sports,'organization'=> ['' => 'Select Organization'] + $organization,'enum'=>$enum,'owner_name'=>$owner_name));
		}
    }
	  /**
     *function for myteam
     */
    public function myteam($team_id,$status=null)
    {
		//get the data by joining teams, teamplayers and users table
		$teams = Team::with(array(
			'teamplayers'=>function($q1) use ($status) {
				if(!empty($status))
				{
					$q1->select()->where('status',$status);
				}
				else
				{
					$q1->select();
				}													
			},
			'teamplayers.user'=>function($q2){
				$q2->select();
			},
			'teamplayers.user.photos'=>function($q3){
				$q3->select();
			}
		))->where('team_owner_id',Auth::user()->id)->where('id',$team_id)->get();
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
		$userId = Auth::user()->id;
		$following_sportids = UserStatistic::where('user_id', $userId)->pluck('following_sports');
		// Helper::setMenuToSelect(2,1);
		$sport = !empty($teams[0]['sports_id']) ? Sport::where('id', $teams[0]['sports_id'])->first()->sports_name : '';
		Helper::leftMenuVariables($team_id);
		//get role for the logged in user id
		$logged_in_user_role = TeamPlayers::where('user_id', $userId)->where('team_id',$team_id)->pluck('role');
        return view('admin.teams.myteam')->with('teams',$teams)->with('team_owners_managers',$team_owners_managers)->with('team_players',$team_players)->with('logged_in_user_role',$logged_in_user_role)->with('following_sportids',$following_sportids);
    }
	//function to remove an element from an array
    public function removeFromArray($existing_ids_str,$new_id)
    {
        $existing_ids_str = trim($existing_ids_str,',');
        $existing_ids = explode(',',$existing_ids_str);
        $key = array_search($new_id, $existing_ids);
        unset($existing_ids[$key]);
        $final_str = null;
        if(count($existing_ids))
        {
            $final_str = implode(',', $existing_ids);
            $final_str = ','.$final_str.',';            
        }
        return $final_str;
    } 
	  
}
