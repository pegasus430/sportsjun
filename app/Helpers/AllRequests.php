<?php
namespace App\Helpers;
use Auth;
use Response;
use App\Http\Requests;
use App\Model\Notifications;
use App\Model\Requestsmodel;
use App\User;
use App\Model\Photo;
use App\Model\Team;
use App\Model\Sport;
use App\Model\Tournaments;
use App\Model\TournamentParent;
use App\Model\TeamPlayers;
use App\Helpers\SendMail;
use Carbon\Carbon;
use HTML;
use DB;

class AllRequests {
	//function to update savetournamentorplayer
	public static function saverequest($request)
	{
		$request_type = !empty($request['flag'])?config('constants.REQUEST_TYPE.'.$request['flag']):null;
		$player_tournament_id = !empty($request['player_tournament_id'])?$request['player_tournament_id']:null;
		$match_schedule_id = !empty($request['match_schedule_id'])?$request['match_schedule_id']:0;
		$team_ids = count($request['team_ids'])?$request['team_ids']:array();
		$sports_id = !empty($request['sports_id'])?$request['sports_id']:0;
		$team_ids_final = array();
		foreach ($team_ids as $team_id)
		{
			if(is_numeric($team_id))
			{
				array_push($team_ids_final, $team_id);
			}
		}
		$team_ids_count = count($team_ids_final);
		$details = array();
		$tournament_player_name = null;
		$response = 'fail';
		if(!empty($request_type) && !empty($player_tournament_id) && count($team_ids_final))
		{
			$requests_array = array();
			$userId = Auth::user()->id;
			$managing_teams_string = Helper::getManagingTeamIds($userId);
			$managing_team_ids = array();
	        if(!empty($managing_teams_string))
	        {
	        	$managing_team_ids = explode(',', trim($managing_teams_string,',')); 	
	        }
	        $exist_count = 0;
	        //checking the duplicate requests
	        foreach ($team_ids_final as $key => $value)
			{
				if(($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_TOURNAMENT')) || ($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_PLAYER')))
				{
					$from_id = $value;
					$to_id = $player_tournament_id;
				}
				else
				{
					$from_id = $player_tournament_id;
					$to_id = $value;
				}
				if(AllRequests::check_duplicate_requests($request_type,$from_id,$to_id) > 0)
				{
					unset($team_ids_final[$key]);
					$exist_count++;
				}
			}
			//if all the requests are duplicate, return the status as existing
			if($exist_count == $team_ids_count)
			{
				return Response::json(['status'=>'exist']);
			}
			//foreach request
	        foreach ($team_ids_final as $value)
			{
				$owner_id = null;
				$manager_id = null;
				$sports_id = null;
				$sports_name = null;
				$from_id = null;
				$to_id = null;
				$email_id_1 = null;
				$email_id_2 = null;
				$email_id_3 = null;
				$emails = array();
				$message = null;
				if(in_array($value, $managing_team_ids) || ($request_type  == config('constants.REQUEST_TYPE.PLAYER_TO_TEAM')) || ($request_type  == config('constants.REQUEST_TYPE.PLAYER_TO_TOURNAMENT')) || ($request_type  == config('constants.REQUEST_TYPE.PLAYER_TO_PLAYER')) || ($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_TEAM')))
				{
					//based on request type assigning the from id and to id
					if(($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_TOURNAMENT')) || ($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_PLAYER')))
					{
						$from_id = $value;
						$to_id = $player_tournament_id;
					}
					else
					{
						$from_id = $player_tournament_id;
						$to_id = $value;
					}
					$message = '';
					$message_sent = '';
					$temp_msg_from = "";
					$temp_msg_to = "";
					$emailTemplate = "";
					//chechking for duplicates
					if(AllRequests::check_duplicate_requests($request_type,$from_id,$to_id) == 0)
					{
						if($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_TOURNAMENT'))//player to tournament
						{
							$details = AllRequests::gettournamentdetails($player_tournament_id);
							$tournament_player_name = !empty($details['name'])?$details['name']:null;
							$owner_id = !empty($details['created_by'])?$details['created_by']:null;
							$manager_id = !empty($details['manager_id'])?$details['manager_id']:null;
							$team_details = Team::where('id',$value)->first(array('name','sports_id'));
							$team_player_name = !empty($team_details['name'])?$team_details['name']:null;
							$sports_id = !empty($team_details['sports_id'])?$team_details['sports_id']:null;
							$tournament_parent_id = !empty($details['tournament_parent_id'])?$details['tournament_parent_id']:0;
							$sports_name = Helper::getSportName($sports_id);
							if(!empty($owner_id))
							{
								$email_id_1 = AllRequests::getemail($owner_id);	
							}
							if(!empty($manager_id))
							{
								$email_id_2 = AllRequests::getemail($manager_id);	
							}
							$email_id_3 = TournamentParent::where('id',$tournament_parent_id)->pluck('email');
							$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$from_id)."'>$team_player_name</a>");
							$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/tournaments/groups'.'/'.$to_id)."'>$tournament_player_name</a>");
							
							$emailTemplate = AllRequests::getEmailTemplate(config('constants.REQUEST_TYPE.TEAM_TO_TOURNAMENT'),$team_player_name,$tournament_player_name);
						}
						elseif($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_PLAYER'))//team to player
						{
							$tournament_player_name = AllRequests::getusername($player_tournament_id);
							$details = Team::with(array(
								'teamplayers'=>function($q1) {
									$q1->select()->whereIn('role',['owner','manager']);
								}
							))->where('id',$value)->get();
							$sports_id = !empty($details[0]['sports_id'])?$details[0]['sports_id']:null;
							$sports_name = Helper::getSportName($sports_id);							
							$team_player_name = !empty($details[0]['name'])?$details[0]['name']:null;
							//player is assigned to owner id to send notification
							$owner_id = $player_tournament_id;
							$email_id_1 = AllRequests::getemail($player_tournament_id);
							//adding to team
							AllRequests::addplayer($player_tournament_id,$from_id);
							$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$from_id)."'>$team_player_name</a>");
							$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$to_id)."'>$tournament_player_name</a>");

							$emailTemplate = AllRequests::getEmailTemplate(config('constants.REQUEST_TYPE.TEAM_TO_PLAYER'),$team_player_name,$tournament_player_name);
						}
						elseif($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_TEAM'))//team to team
						{
							$team_player_name = AllRequests::getteamname($from_id);
							$tournament_player_name = AllRequests::getteamname($to_id);
							$team_sports_id = Team::where('id',$from_id)->pluck('sports_id');
							$sports_id = !empty($team_sports_id)?$team_sports_id:null;
							$sports_name = Helper::getSportName($sports_id);
							$owner_id = AllRequests::getempidonroles($to_id,'owner');
							$manager_id = AllRequests::getempidonroles($to_id,'manager');
							if(!empty($owner_id))
							{
								$email_id_1 = AllRequests::getemail($owner_id);	
							}
							if(!empty($manager_id))
							{
								$email_id_2 = AllRequests::getemail($manager_id);	
							}							
							$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$from_id)."'>$team_player_name</a>");
							$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$to_id)."'>$tournament_player_name</a>");
							
							$emailTemplate = AllRequests::getEmailTemplate(config('constants.REQUEST_TYPE.TEAM_TO_TEAM'),$team_player_name,$tournament_player_name);
						}
						elseif($request_type  == config('constants.REQUEST_TYPE.PLAYER_TO_TEAM'))//player to team
						{
							$team_player_name = AllRequests::getusername($from_id);
							$tournament_player_name = AllRequests::getteamname($to_id);
							$team_sports_id = Team::where('id',$to_id)->pluck('sports_id');
							$sports_id = !empty($team_sports_id)?$team_sports_id:null;
							$sports_name = Helper::getSportName($sports_id);
							$owner_id = AllRequests::getempidonroles($to_id,'owner');
							$manager_id = AllRequests::getempidonroles($to_id,'manager');
							if(!empty($owner_id))
							{
								$email_id_1 = AllRequests::getemail($owner_id);	
							}
							if(!empty($manager_id))
							{
								$email_id_2 = AllRequests::getemail($manager_id);	
							}					
							$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$from_id)."'>$team_player_name</a>");
							$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$to_id)."'>$tournament_player_name</a>");
							
							$emailTemplate = AllRequests::getEmailTemplate(config('constants.REQUEST_TYPE.PLAYER_TO_TEAM'),$team_player_name,$tournament_player_name);
						}
						elseif ($request_type  == config('constants.REQUEST_TYPE.PLAYER_TO_TOURNAMENT'))//player to tournament
						{
							$team_player_name = AllRequests::getusername($from_id);
							$tournament_details = AllRequests::gettournamentdetails($to_id);
							$tournament_player_name = !empty($tournament_details['name'])?$tournament_details['name']:null;
							$team_sports_id = !empty($tournament_details['sports_id'])?$tournament_details['sports_id']:null;
							$sports_id = !empty($team_sports_id)?$team_sports_id:null;
							$sports_name = Helper::getSportName($sports_id);
							$owner_id = !empty($tournament_details['created_by'])?$tournament_details['created_by']:null;
							$manager_id = !empty($tournament_details['manager_id'])?$tournament_details['manager_id']:null;	
							$tournament_parent_id = !empty($tournament_details['tournament_parent_id'])?$tournament_details['tournament_parent_id']:0;
							if(!empty($owner_id))
							{
								$email_id_1 = AllRequests::getemail($owner_id);	
							}
							if(!empty($manager_id))
							{
								$email_id_2 = AllRequests::getemail($manager_id);	
							}	
							$email_id_3 = TournamentParent::where('id',$tournament_parent_id)->pluck('email');
							$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$from_id)."'>$team_player_name</a>");
							$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/tournaments/groups'.'/'.$to_id)."'>$tournament_player_name</a>");
							
							$emailTemplate = AllRequests::getEmailTemplate(config('constants.REQUEST_TYPE.PLAYER_TO_TOURNAMENT'),$team_player_name,$tournament_player_name);
							
						}
						elseif ($request_type  == config('constants.REQUEST_TYPE.PLAYER_TO_PLAYER'))//player to player
						{
							$team_player_name = AllRequests::getusername($from_id);
							$tournament_player_name = AllRequests::getusername($to_id);
							$sports_name = Helper::getSportName($sports_id);
							$owner_id = $to_id;
							$email_id_1 = AllRequests::getemail($to_id);
							$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$from_id)."'>$team_player_name</a>");
							$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$to_id)."'>$tournament_player_name</a>");
							
							$emailTemplate = AllRequests::getEmailTemplate(config('constants.REQUEST_TYPE.PLAYER_TO_PLAYER'),$team_player_name,$tournament_player_name);
						}						
						$send_notificatons_to = array('owner_id'=>$owner_id,'manager_id'=>$manager_id);
						//pushing emails into emails array
						if(!empty($email_id_1))
						{
							array_push($emails, $email_id_1);
						}
						if(!empty($email_id_2))
						{
							array_push($emails, $email_id_2);
						}
						if(!empty($email_id_3))
						{
							array_push($emails, $email_id_3);
						}
						if(!empty($tournament_player_name) && !empty($team_player_name))
						{
							//json encoding the data to store
							$from_to_names = json_encode(array('from_id'=>$from_id,'from_name'=>$team_player_name,'to_id'=>$to_id,'to_name'=>$tournament_player_name,'sport_name'=>$sports_name));
							$message = trans("message.request_type.".$request_type, ['team' => $temp_msg_from,'tournament' => $temp_msg_to]);
							$message_sent = trans("message.request_type_sent.".$request_type, ['team' => $temp_msg_from,'tournament' => $temp_msg_to]);
							//sending requests
							$requests_array_inner = array(
								'type' => $request_type,
								'from_id' => $from_id,
								'to_id' => $to_id,
								'from_to_names' => $from_to_names,
								'id_to_update' => $match_schedule_id,
								'message' => $message,
								'message_sent' => $message_sent,
							);    
							$request_model_result = Requestsmodel::create($requests_array_inner);
							$request_id = isset($request_model_result->id)?$request_model_result->id:0;
							if(!empty($request_id))
							{
								//sending notifications
								$send_notificatons_to = array_unique($send_notificatons_to);
								foreach($send_notificatons_to as $key=>$val )
								{	
									if(is_numeric($val))
									{
										$notifications = array(
											'request_id' => $request_id,
											'type' => $request_type,
											'user_id' => $val,
											'message' => $message,
										);
										Notifications::create($notifications);
									}
								}
								$emails = array_unique($emails);
								//send email
								foreach ($emails as $mail)
								{
									AllRequests::sendemail($mail,$to_id,$tournament_player_name,$emailTemplate);
								}
								$response = 'success';
							}
						}
					}
					else
					{
						$response = 'exist';
					}
				}
			}
		}
		return Response::json(['status'=>$response]);
	}
	//function to check duplicate record in requests
	public static function check_duplicate_requests($request_type,$from_id,$to_id)
	{
		$record_count = Requestsmodel::where('type',$request_type)->where('from_id',$from_id)->where('to_id',$to_id)->where('action_status',0)->count();
		return $record_count;
	}
	//function to get playersrequests
	public static function getrequests($id,$type_ids,$limit=0,$offset=0)
	{
		$condition = '';
		if(!empty($type_ids))
		{
			$condition = " and r.type in ($type_ids) ";
		}
		$limit_condition = '';
		if(!empty($limit))
		{
			$limit_condition = " limit $offset,$limit ";
		}
		$sent_requests = DB::select(DB::raw("
					select r.*,
					case r.type 
						when 1  then (select logo from teams where id=r.from_id)
						when 2  then (select logo from tournaments where id=r.from_id)
						when 3  then (select logo from users where id=r.from_id)
						when 4  then (select logo from tournaments where id=r.from_id)
						when 5  then (select logo from teams where id=r.from_id)
						when 6  then (select logo from users where id=r.from_id)
						else 'logo'
						end as logo,
					case r.type 
						when 1  then 'TEAMS_FOLDER_PATH'
						when 2  then 'TOURNAMENT_PROFILE'
						when 3  then 'USERS_PROFILE'
						when 4  then 'TOURNAMENT_PROFILE'
						when 5  then 'TEAMS_FOLDER_PATH'
						when 6  then 'USERS_PROFILE'
						else 'logo'
						end as logo_type	
					 from request r
			where r.to_id=$id and r.action_status=0 and r.deleted_at is null $condition order by id DESC $limit_condition"));
		
		$result = array();
		if(count($sent_requests))
		{
			$result = $sent_requests;
		}
		return $result;
	}
	//function to get playersrequests
	public static function getrequestscount($id,$type_ids)
	{
		$condition = '';
		if(!empty($type_ids))
		{
			$condition = " and type in ($type_ids) ";
		}
		$sent_requests = DB::select(DB::raw("select count(id) as req_count from request where to_id=$id and action_status=0 and deleted_at is null  $condition"));
		
		$result = 0;
		if(count($sent_requests) && !empty($sent_requests[0]->req_count))
		{
			$result = $sent_requests[0]->req_count;
		}
		return $result;
	}
        
        //fucntion to get user name
	public static function getUserNameAndEmail($id)
	{
		return User::where('id',$id)->first(['id','name','email']);
	}
	//fucntion to get user name
	public static function getusername($id)
	{
		return User::where('id',$id)->pluck('name');
	}
	//fucntion to get user name
	public static function getteamname($id)
	{
		return Team::where('id',$id)->pluck('name');
	}
	//fucntion to get tournament name
	public static function gettournamentdetails($id)
	{
		return Tournaments::where('id',$id)->first();
	}
	//fucntion to get user name
	public static function getempidonroles($id,$flag)
	{
		$user_id = TeamPlayers::where('team_id',$id)->where('role',$flag)->pluck('user_id');
		return $user_id;
	}
	//function to get request details
	public static function getrequestdetails($id)
	{
		return Requestsmodel::where('id',$id)->first();
	}
	//function to add a player
	public static function addplayer($user_id,$team_id)
	{
		$team_players_count = TeamPlayers::where('team_id',$team_id)->where('user_id',$user_id)->whereNotIn('status',['rejected'])->count();
		if(is_numeric($user_id) && is_numeric($team_id) && $team_players_count==0)
		{
			$role = 'player';
			$TeamPlayer = new TeamPlayers();
			$TeamPlayer->team_id = $team_id;
			$TeamPlayer->user_id = $user_id;
			$TeamPlayer->role = $role;
			$TeamPlayer->save();
			//insert if match is scheduled on that team
			Helper::insertTeamPlayersInSchedules($team_id,$user_id);
		}
		
		
	}
	//function to get email
	public static function getemail($id)
	{
		return User::where('id',$id)->pluck('email');
	}
	//function to send email
	public static function sendemail($mail_id,$to_user_id,$name,$message)
	{
		//blade view for sendnotifications from emails folder in views
		$view = 'emails.sendnotifications';
		$subject = 'Sportsjun Notification';
		$view_data = array('user_name'=>$name,'message'=>$message);
		//send the data to sendmail 
		$data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$mail_id,'view_data'=>$view_data,'to_user_id'=>$to_user_id,'flag'=>'user','send_flag'=>1);
		SendMail::sendmail($data);
	}
	public static function sendnotifications($user_id,$message,$url=NULL)
	{
		$notifications = array(
			
			'user_id' => $user_id,
			'message' => $message,
			'url'=>$url
		);
		Notifications::create($notifications);
	}
	public static function updateteamplayerstatus($user_id,$team_id,$flag)
	{
		if(is_numeric($user_id) && is_numeric($team_id) && ($flag == 'a' || $flag == 'r'))
		{
			$status = ($flag=='a')?'accepted':'rejected';
			TeamPlayers::where('user_id',$user_id)->where('team_id',$team_id)->update(['status'=>$status]);
		}
	}
	public static function getsentrequests($id,$type_ids,$limit,$offset)
	{
		$condition = '';
		if(!empty($type_ids))
		{
			$condition = " and r.type in ($type_ids) ";
		}
		$limit_condition = '';
		if(!empty($limit))
		{
			$limit_condition = " limit $offset,$limit ";
		}		
		/*$sent_requests = DB::select(DB::raw("select * from request where from_id=$id and action_status=0 and deleted_at is null $condition order by id DESC $limit_condition"));*/
		$sent_requests = DB::select(DB::raw("
				select r.*,
				case r.type 
					when 1  then (select logo from teams where id=r.to_id)
					when 2  then (select logo from tournaments where id=r.to_id)
					when 3  then (select logo from users where id=r.to_id)
					when 4  then (select logo from tournaments where id=r.to_id)
					when 5  then (select logo from teams where id=r.to_id)
					when 6  then (select logo from users where id=r.to_id)
					else 'logo'
					end as logo,
				case r.type 
					when 1  then 'TEAMS_FOLDER_PATH'
					when 2  then 'TOURNAMENT_PROFILE'
					when 3  then 'USERS_PROFILE'
					when 4  then 'TOURNAMENT_PROFILE'
					when 5  then 'TEAMS_FOLDER_PATH'
					when 6  then 'USERS_PROFILE'
					else 'logo'
					end as logo_type	
				 from request r 
				 where r.from_id=$id and r.action_status=0 and r.deleted_at is null $condition order by id DESC $limit_condition"));

		$result = array();
		if(count($sent_requests))
		{
			$result = $sent_requests;
		}
		return $result;

	}
	public static function getsentrequestscount($id,$type_ids=array())
	{
		$condition = '';
		if(!empty($type_ids))
		{
			$condition = " and type in ($type_ids) ";
		}
		$sent_requests = DB::select(DB::raw("select count(id) as req_count from request where from_id=$id and action_status=0 and deleted_at is null $condition"));
		
		$result = 0;
		if(count($sent_requests) && !empty($sent_requests[0]->req_count))
		{
			$result = $sent_requests[0]->req_count;
		}
		return $result;
	}
       // function to send notification for match to team owner and manager Or player
        public static function sendMatchNotifications($tournament_id,$schedule_type,$a_id,$b_id,$match_start_date) {
                if(!empty($tournament_id)) {
                    $tournamentDetails = AllRequests::gettournamentdetails($tournament_id);
                    $tournamentName = $tournamentDetails->tournament_parent_name.' '.$tournamentDetails->name;
                }
                $matchStartDate = Carbon::createFromFormat('Y-m-d', $match_start_date);
                $matchStartDate = $matchStartDate->toFormattedDateString();
                if($schedule_type=='team') {
                    $teamOneName = AllRequests::getteamname($a_id);
                    $teamOneOwnerId = AllRequests::getempidonroles($a_id,'owner');
                    
                    $teamOneManagerId = AllRequests::getempidonroles($a_id,'manager');
                    if(!empty($b_id)) {
                        $teamTwoName = AllRequests::getteamname($b_id);
                        $teamTwoOwnerId = AllRequests::getempidonroles($b_id,'owner');
                        $teamTwoManagerId = AllRequests::getempidonroles($b_id,'manager');
                        if(!empty($tournament_id)) {
                            $message = trans("message.tournament.tournamentnotification", ['teamonename' => $teamOneName,
                                                'teamtwoname' => $teamTwoName, 'matchStartDate'=>$matchStartDate, 'tournamentname'=>$tournamentName]);
                        }else{
                            $message = trans("message.tournament.matchnotification", ['teamonename' => $teamOneName,
                                                'teamtwoname' => $teamTwoName, 'matchStartDate'=>$matchStartDate]);
                        }    
                    }else{
                        $message = trans("message.tournament.bye", ['teamonename' => $teamOneName,'tournamentname'=>!empty($tournamentName)?$tournamentName:'']);
                    }
                    
                        if(!empty($teamOneOwnerId)) {
                            AllRequests::sendnotifications($teamOneOwnerId,$message,'');
                            $teamOneOwnerDetails = AllRequests::getUserNameAndEmail($teamOneOwnerId);
                            if(count($teamOneOwnerDetails)) {
                                if(!empty($teamOneOwnerDetails->email)) {
                                    AllRequests::sendMatchEmails($teamOneOwnerDetails,$message);
                                }
                            }
                        }
                        if(!empty($teamOneManagerId)) {
                            AllRequests::sendnotifications($teamOneManagerId,$message,'');
                            $teamTwoManagerDetails = AllRequests::getUserNameAndEmail($teamOneManagerId);
                            if(count($teamTwoManagerDetails)) {
                                if(!empty($teamTwoManagerDetails->email)) {
                                    AllRequests::sendMatchEmails($teamTwoManagerDetails,$message);
                                }
                            }
                        }
                        if(!empty($teamTwoOwnerId)) {
                            AllRequests::sendnotifications($teamTwoOwnerId,$message,'');
                            $teamTwoOwnerDetails = AllRequests::getUserNameAndEmail($teamTwoOwnerId);
                            if(count($teamTwoOwnerDetails)) {
                                if(!empty($teamTwoOwnerDetails->email)) {
                                    AllRequests::sendMatchEmails($teamTwoOwnerDetails,$message);
                                }
                            }
                        }
                        if(!empty($teamTwoManagerId)) {
                            AllRequests::sendnotifications($teamTwoManagerId,$message,'');
                            $teamTwoManagerDetails = AllRequests::getUserNameAndEmail($teamTwoManagerId);
                            if(count($teamTwoManagerDetails)) {
                                if(!empty($teamTwoManagerDetails->email)) {
                                    AllRequests::sendMatchEmails($teamTwoManagerDetails,$message);
                                }
                            }
                        }
                } else {
                    $playerOneDetails = AllRequests::getUserNameAndEmail($a_id);
                    if(!empty($b_id)) {
                        $playerTwoDetails = AllRequests::getUserNameAndEmail($b_id);
                        if(!empty($tournament_id)) {
                            $message = trans("message.tournament.tournamentnotification", ['teamonename' => $playerOneDetails->name,
                                                'teamtwoname' => $playerTwoDetails->name, 'matchStartDate'=>$matchStartDate, 'tournamentname'=>$tournamentName]);
                        }else{
                            $message = trans("message.tournament.matchnotification", ['teamonename' => $playerOneDetails->name,
                                                'teamtwoname' => $playerTwoDetails->name, 'matchStartDate'=>$matchStartDate]);   
                        }
                    }else{
                        $message = trans("message.tournament.bye", ['teamonename' => $playerOneDetails->name,'tournamentname'=>!empty($tournamentName)?$tournamentName:'']);
                    }
                    
                    if(count($playerOneDetails)) {
                        AllRequests::sendnotifications($a_id,$message,'');
                                if(!empty($playerOneDetails->email)) {
                                    AllRequests::sendMatchEmails($playerOneDetails,$message);
                            }
                    }
                    if(count($playerTwoDetails)) {
                        AllRequests::sendnotifications($b_id,$message,'');
                        if(!empty($playerTwoDetails->email)) {
                            AllRequests::sendMatchEmails($playerTwoDetails,$message);
                        }   
                    }
                }
        }
        
        // function to send email related to match or tournament
        public static function sendMatchEmails($userDetails,$message) {
                $to_email_id=  $userDetails->email;
		$to_user_id = $userDetails->id;
		$user_name = $userDetails->name;;
		$subject = 'Match Remainder';
		$view_data = array('name'=>$user_name,'message'=>$message);
		$view = 'emails.notification';
		$mail_data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'to_user_id'=>$to_user_id,'view_data'=>$view_data,'flag'=>'user','send_flag'=>0);
		SendMail::sendmail($mail_data);
		
        }
	//function to get email template
	public static function getEmailTemplate($requested_type,$from_name,$to_name)
	{
		 $email_template='';
		 switch ($requested_type) {
            case config('constants.REQUEST_TYPE.TEAM_TO_TOURNAMENT'):
				$email_template = 'Hi,<br/><br/>
									'.$from_name.' has requested to take part in '.$to_name.'. Please 
									respond to the request.<br/><br/>
									Cheers!';
                break;
		    case config('constants.REQUEST_TYPE.TEAM_TO_PLAYER'):
               
			   $email_template =  'Hi,<br/><br/>
								You have been requested to join '.$from_name.'. Please respond to 
								the request.<br/><br/>
								Cheers!';
                 break;	
		    case  config('constants.REQUEST_TYPE.TEAM_TO_TEAM'):
			  	 $email_template = 'Hi,<br/><br/>
									'.$from_name.' has requested to play a match against '.$to_name.'. Please respond to the request.<br/><br/>
									Cheers!';
                break;
		    case config('constants.REQUEST_TYPE.PLAYER_TO_TEAM'):
                  
				 $email_template = 'Hi,<br/><br/>'.
								$from_name.' has requested to join '.$to_name.'. Please respond to 
								the request.<br/><br/>
								Cheers!';
                break;
		    case  config('constants.REQUEST_TYPE.PLAYER_TO_TOURNAMENT'):
            $email_template =  'Hi,<br/><br/>'.
								$from_name.' has requested to take part in '.$to_name.'. Please 
								respond to the request.<br/><br/>
								Cheers!';
                break;
		    case  config('constants.REQUEST_TYPE.PLAYER_TO_PLAYER'):
                 
				 $email_template = 'Hi,<br/><br/>
								You have been requested to play a match against '.$from_name.'. Please 
								respond to the request.<br/><br/>
								Cheers!';
                break;
	        default:
                  $email_template='';
                break;
        }
		return $email_template;
	}
	//function to send response notification
	public static function sendResponseNotification($request_id)
	{
		$result = AllRequests::getrequestdetails($request_id);
		$from_id = !empty($result['from_id'])?$result['from_id']:0;
		$to_id = !empty($result['to_id'])?$result['to_id']:0;
		$from_name = '';
		$to_name = '';
		$request_type = !empty($result['type'])?$result['type']:0;
		$status = !empty($result['action_status'])?($result['action_status']==1?'accepted':'rejected'):'closed';
		$temp_msg_from = '';
		$temp_msg_to = '';
		$send_notificatons_to = array();
		$email_id_1 = '';
		$email_id_2 = '';
		$email_id_3 = '';
		$emails = array();
		if($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_TOURNAMENT'))//player to tournament
		{
			$from_name = AllRequests::getteamname($from_id);
			$details = AllRequests::gettournamentdetails($to_id);
			$to_name = !empty($details['name'])?$details['name']:null;			
			$to_name = AllRequests::getusername($to_id);
			$owner_id = AllRequests::getempidonroles($from_id,'owner');
			$manager_id = AllRequests::getempidonroles($from_id,'manager');
			if(!empty($owner_id))
			{
				$email_id_1 = AllRequests::getemail($owner_id);	
			}
			if(!empty($manager_id))
			{
				$email_id_2 = AllRequests::getemail($manager_id);				
			}
			if(!empty($owner_id))
			{
				array_push($send_notificatons_to, $owner_id);
			}
			if(!empty($manager_id))
			{
				array_push($send_notificatons_to, $manager_id);
			}
			$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/tournaments/groups'.'/'.$to_id)."'>$to_name</a>");
			$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$from_id)."'>$from_name</a>");		
		}
		elseif($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_PLAYER'))//team to player
		{
			$from_name = AllRequests::getteamname($from_id);
			$to_name = AllRequests::getusername($to_id);
			$owner_id = AllRequests::getempidonroles($from_id,'owner');
			$manager_id = AllRequests::getempidonroles($from_id,'manager');
			if(!empty($owner_id))
			{
				$email_id_1 = AllRequests::getemail($owner_id);	
			}
			if(!empty($manager_id))
			{
				$email_id_2 = AllRequests::getemail($manager_id);				
			}			
			if(!empty($owner_id))
			{
				array_push($send_notificatons_to, $owner_id);
			}
			if(!empty($manager_id))
			{
				array_push($send_notificatons_to, $manager_id);
			}
			$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$to_id)."'>$to_name</a>");
			$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$from_id)."'>$from_name</a>");				
		}
		elseif($request_type  == config('constants.REQUEST_TYPE.TEAM_TO_TEAM'))//team to team
		{
			$from_name = AllRequests::getteamname($from_id);
			$to_name = AllRequests::getteamname($to_id);
			$owner_id = AllRequests::getempidonroles($from_id,'owner');
			$manager_id = AllRequests::getempidonroles($from_id,'manager');
			if(!empty($owner_id))
			{
				$email_id_1 = AllRequests::getemail($owner_id);	
			}
			if(!empty($manager_id))
			{
				$email_id_2 = AllRequests::getemail($manager_id);				
			}			
			if(!empty($owner_id))
			{
				array_push($send_notificatons_to, $owner_id);
			}
			if(!empty($manager_id))
			{
				array_push($send_notificatons_to, $manager_id);
			}
			$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$to_id)."'>$to_name</a>");
			$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$from_id)."'>$from_name</a>");					
		}
		elseif($request_type  == config('constants.REQUEST_TYPE.PLAYER_TO_TEAM'))//player to team
		{
			$from_name = AllRequests::getusername($from_id);
			$to_name = AllRequests::getteamname($to_id);
			$email_id_1 = AllRequests::getemail($from_id);
			$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/team/members'.'/'.$to_id)."'>$to_name</a>");
			$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$from_id)."'>$from_name</a>");
			$user_id = $from_id;
			array_push($send_notificatons_to, $user_id);
		}
		elseif ($request_type  == config('constants.REQUEST_TYPE.PLAYER_TO_TOURNAMENT'))//player to tournament
		{	
			$from_name = AllRequests::getusername($from_id);
			$details = AllRequests::gettournamentdetails($to_id);
			$email_id_1 = AllRequests::getemail($from_id);
			$to_name = !empty($details['name'])?$details['name']:null;
			$user_id = $from_id;
			array_push($send_notificatons_to, $user_id);
			$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/tournaments/groups'.'/'.$to_id)."'>$to_name</a>");
			$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$from_id)."'>$from_name</a>");	
		}
		elseif ($request_type  == config('constants.REQUEST_TYPE.PLAYER_TO_PLAYER'))//player to player
		{
			$from_name = AllRequests::getusername($from_id);
			$to_name = AllRequests::getusername($to_id);
			$email_id_1 = AllRequests::getemail($from_id);
			$temp_msg_from = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$to_id)."'>$to_name</a>");
			$temp_msg_to = htmlentities("<a href='".('REQURL|'.'/editsportprofile'.'/'.$from_id)."'>$from_name</a>");
			$user_id = $from_id;
			array_push($send_notificatons_to, $user_id);		
		}	
		$message = trans("message.request_type_response.".$request_type, ['team' => $temp_msg_from,'flag'=>$status,'tournament' => $temp_msg_to]);
		$send_notificatons_to = array_unique($send_notificatons_to);
		foreach ($send_notificatons_to as $value) 
		{
			AllRequests::sendnotifications($value,$message);
		}
		if(!empty($email_id_1))
		{
			array_push($emails, $email_id_1);
		}
		if(!empty($email_id_2))
		{
			array_push($emails, $email_id_2);
		}
		if(!empty($email_id_3))
		{
			array_push($emails, $email_id_3);
		}
		$emails = array_unique($emails);
		//send email
		foreach ($emails as $mail)
		{
			AllRequests::sendemail($mail,$from_id,$from_name, HTML::decode($message));
		}		
	}
}