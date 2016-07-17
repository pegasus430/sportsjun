<?php

namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Model\InvitePlayer;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Response;
use App\Helpers\SendMail;
use App\Helpers\Helper;
use App\Helpers\AllRequests;
use App\Model\UserStatistic;
use App\Model\Sport;

class InvitePlayerController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('widgets.inviteplayer');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
   
    public function invitePlayers(Request $request)
    {
		   //'password' => bcrypt($data['password']),
		 
	     $email=$request->email;
	     $name=$request->name;
		 $teamid=$request->teamid;
		 $user = new User();
		 $user->name =  $name;
		  $user->firstname =  $name;
		 $teamname=Team::select('name')->where('id',$teamid)->get()->toArray();
		 if($email!="")
		 {
			 $generatedPassword= str_random(6);
			 $password=  bcrypt( $generatedPassword);
			$user->email =  $email; 
			$user->password = $password;
			$user->verification_key = md5($email);
			$user->is_verified =1;
		 }
		 $last_inserted_id = 0;
		 $last_inserted_player_id = 0;
		 $rules = array( 'name' => 'required|max:50|'.config('constants.VALIDATION.CHARACTERSANDSPACE'),'email' => 'email|unique:users,email');
         $validator = Validator::make($request->all(), $rules);

		if ($validator->fails())
		{
		   return Response::json(array(
			'status' => 'fail',
			'msg' => $validator->getMessageBag()->toArray()

			  ));
		 }
  	      if($user->save())
		  {
		    $last_inserted_id = $user->id;
		  }	

	    if($last_inserted_id>0)
		{
			
			$player = new TeamPlayers();
			$player->team_id= $teamid;
			$player->user_id=$user->id;
			
			if($player->save())
			{
			   // return $this->getTeamPlayersDiv($request_array = array('team_id'=>$teamid));
			   $last_inserted_player_id =  $player->id;
			}
			
			//insert players in match schedule table if match is scheduled on that team
			$insertTeamPlayers = Helper::insertTeamPlayersInSchedules($teamid,$user->id);
			
			if( $last_inserted_player_id > 0 &&  $user->email!="")
			{					      	
				 $to_user_id = $user->id;
				$to_email_id=  $user->email;
				$user_name = $user->name;
				$subject =  trans('message.inviteplayer.subject');
				$view_data = array('email'=>$to_email_id,'password'=>$generatedPassword ,'user_name'=>$user_name,'team_name'=>$teamname[0]['name']);
				$view = 'emails.invitePlayers';
				$data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>  $to_user_id,'flag'=>'user','send_flag'=>1);
				SendMail::sendmail($data);
				//$message="invite player";
				//$url="";
				// AllRequests::sendnotifications($to_user_id,	$message,$url);
				
				$a_id=Auth::user()->id;
				
				 $request_array = array('flag'=>'TEAM_TO_PLAYER','player_tournament_id'=>$to_user_id,'team_ids'=>array($teamid));
                  AllRequests::saverequest($request_array);
				  

			}
			// $response = array(
                  // 'status' => 'success',
                   // 'msg' => 'user invited successfully',
				   // 'user_id' => $last_inserted_id,// added user id 
                       // );

		            // return \Response::json($response);
					 return $this->getTeamPlayersDiv($teamid);
	  
		    
		}
	  
    }
	public function getTeamPlayersDiv($teamid)
	{
		//$request = Request::all();

		$team_id = !empty($teamid)?$teamid:0;
		$status = null;
		//get the data by joining teams, teamplayers and users table
		$teams_query = Team::with(array(
			'teamplayers'=>function($q1) use ($status) {
				if(!empty($status))
				{
					$q1->select()->where('status',$status)->orderBy('status','asc');
				}
				else
				{
					$q1->select()->whereNotIn('status',['rejected'])->orderBy('status','asc');
				}													
			},
			'teamplayers.user'=>function($q2){
				$q2->select();
			},
			'teamplayers.user.photos'=>function($q3){
				$q3->select();
			}
		));
		$managingTeamIds = Helper::getManagingTeamIds(Auth::user()->id);
		$managing_team_ids = explode(',', trim($managingTeamIds,','));
		//if the team id is in managing team ids, then add owner conditioin
		if(in_array($team_id, $managing_team_ids))
		{
			$teams_query->where('team_owner_id',Auth::user()->id);
		}
		$teams = $teams_query->where('id',$team_id)->get();
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
		return \Response::json(array('status'=>'success','msg' => 'User Invited Successfully','html' =>$returnHTML));	
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
        //
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
}
