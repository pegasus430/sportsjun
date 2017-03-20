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
use App\Model\Tournaments;
use App\Model\MatchSchedule;
use App\Model\RefereeSchedule; 
use App\Model\Referee;


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
	     $email=trim($request->email);
	     $name=$request->name;
		 $teamid=$request->teamid;

         $rules = array( 'name' => 'required|max:50|'.config('constants.VALIDATION.CHARACTERSANDSPACE'));
         $generatedPassword = false;
         if ($email){
             $user = User::where('email',$email)->first();
         } else
             $user = false;

        if (!$user) {
            $user = new User();
            $user->name = $name;
            $user->firstname = $name;
            $rules['email'] = 'email|unique:users,email';

            if($email!="")
            {
                $generatedPassword= str_random(6);
                $password=  bcrypt( $generatedPassword);
                $user->email =  $email;
                $user->password = $password;
                $user->verification_key = md5($email);
                $user->is_verified =1;
            }

        } else {
            $rules['email'] = 'email';
        }

         $teamname=Team::select('name')->where('id',$teamid)->get()->toArray();
		 $last_inserted_id = 0;
		 $last_inserted_player_id = 0;

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
			$player = TeamPlayers::where(['team_id'=>$teamid,'user_id'=>$user->id])->first();

            if (!$player) {
                $player = new TeamPlayers([
                    'team_id'=>$teamid,
                    'user_id'=>$user->id
                ]);
            }
			
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

		if(!$request->ajax()){
			return redirect()->back();
		}
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

    public function addEmailToPlayers(Request $request){
    		$email=$request->email;
    		$user_id=$request->user_id;
    		$teamid=$request->team_id;

    		$check_if_email_exist=user::where('email', $email)->get()->count();

    		if($check_if_email_exist>0){
    			return [
    			'message'=>'This email already exist',
    			'error'=>'yes'
    			];
    		}

    		$user=User::find($user_id);
    		$user->email=$email;
    		$teamname=Team::select('name')->where('id',$teamid)->get()->toArray();

    		
//generate new password for player
    		$generatedPassword= str_random(6);
			 $password=  bcrypt( $generatedPassword);
			$user->email =  $email; 
			$user->password = $password;
			$user->verification_key = md5($email);
			$user->is_verified =1;
			$user->save();

			$to_user_id = $user->id;
			$to_email_id=  $user->email;
			$user_name = $user->name;
			$subject =  trans('message.inviteplayer.subject');
			$view_data = array('email'=>$to_email_id,'password'=>$generatedPassword ,'user_name'=>$user_name,'team_name'=>$teamname[0]['name']);
			$view = 'emails.invitePlayers';
			$data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>  $to_user_id,'flag'=>'user','send_flag'=>1);
			SendMail::sendmail($data);

    	return ['message'=>'Email Added', 
    			'error'=>'no'];
    }

    public function invitePlayerToTournament($tournament_id, $email, $name, $match_id=null){

		 $user = new User();
		 $user->name =  $name;
		 $user->firstname =  $name;
		 $tournament=Tournaments::find($tournament_id);
		 if($email!="")
		 {
		    $check_user=User::where('email', $email)->first();

		   if(count($check_user)) return false;

			 $generatedPassword= str_random(6);
			 $password=  bcrypt( $generatedPassword);
			$user->email =  $email; 
			$user->password = $password;
			$user->verification_key = md5($email);
			$user->is_verified =1;
		 }
		 $last_inserted_id = 0;
		 $last_inserted_player_id = 0;
	
  	      if($user->save())
		  {
		    $last_inserted_id = $user->id;
		  }	

	    if($last_inserted_id>0)
		{		
			
			
			if($user->email!="")				{	

				$to_user_id = $user->id;
				$to_email_id=  $user->email;
				$user_name = $user->name;
				$subject =  trans('message.inviteplayer.subject');
				$view_data = array('email'=>$to_email_id,'password'=>$generatedPassword ,'user_name'=>$user_name,'team_name'=>$tournament->name);
				$view = 'emails.invitePlayers';
				$data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>  $to_user_id,'flag'=>'user','send_flag'=>1);
				SendMail::sendmail($data);	
			}
		return $user;		    
		}

		return false;

    }

    public function add_referee(Request $request){
    		$match_id = $request->match_id;
    		$user_id  = $request->response; 

    		$match_model = matchSchedule::find($match_id);
    		$referee_schedule = new refereeSchedule; 

    		$referee_schedule->match_id = $match_id; 
    		$referee_schedule->user_id  = $user_id; 
    		$referee_schedule->save();

    		$referee = referee::whereUserId($user_id)->first();
    		if($referee){
    			$referee->scheduled_matches++;
    			$referee->save();
    		}
    		else{
    			$referee = new referee;
    			$referee->user_id = $user_id;
    			$referee->scheduled_matches =1; 
    			$referee->save();
    		}

    		$referee_schedule->referee_id = $referee->id;
    		$referee_schedule->save();

    		return "<tr class='record'>
    					<td>{$referee->user->name}</td>
    					<td><button class='btn btn-circle btn-danger' type='button' onclick='removeReferee($referee_schedule->id,this)'><i class='fa fa-remove'></i></button></td>
    				</tr>";


    }

    public function invite_referee(Request $request){
    		$match_id = $request->match_id;
    		$email  = $request->email;
    		$name   = $request->name; 

    	$match_model = matchSchedule::find($match_id);
    	$user = $this->invitePlayerToTournament($match_model->tournament_id, $email, $name, $match_id);

    	if($user){

    		$request->response = $user->id;
    		$data = $this->add_referee($request);

    			return [
    			'message'=>'This email already exist',
    			'error'=>'no',
    			'data' => $data,
    			'status' => 'success'
    			];
    	}
    	else{
    			return [
    			'message'=>'This email already exist',
    			'error'=>'yes',
    			'status' => 'fail'
    			];
    	}
    }

    public function remove_referee(Request $request){
    		$match_id = $request->match_id;
    		$id  = $request->id; 
    	
    		$referee_schedule = refereeSchedule::find($id); 

    		$referee = $referee_schedule->referee;
    		
			$referee->scheduled_matches--;
			$referee->save(); 	

    		$referee_schedule->delete();

    		return "ok";
    }


}
