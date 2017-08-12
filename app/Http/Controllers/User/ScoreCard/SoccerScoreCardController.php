<?php

namespace App\Http\Controllers\User\ScoreCard;

use App\Helpers\Helper;
use App\Http\Controllers\User\ScoreCardController as parentScoreCardController;
use App\Model\MatchSchedule;
use App\Model\Photo;
use App\Model\SoccerPlayerMatchwiseStats;
use App\Model\SoccerStatistic;

use App\Model\Tournaments;
use App\Model\Sport;
use App\Model\TeamPlayers;
use App\Model\TournamentGroupTeams;

use App\Model\Team;
use App\User;
use Auth;
use Request;
use Session;


//get all my requests data as object

class SoccerScoreCardController extends parentScoreCardController
{
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
        $isAdminEdit = 0;
        if(Session::has('is_allowed_to_edit_match')){
            $session_data = Session::get('is_allowed_to_edit_match');

            if($isValidUser && ($session_data[0]['id']==$match_data[0]['id'])){
                $isAdminEdit=1;
            }
        }


        if(($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser) && !$isAdminEdit)//soccer score view only
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
                    SoccerPlayerMatchwiseStats::insertSoccerScore($user_id_a,$tournament_id,$match_id,$team_a_id,$a_player_name,$team_a_name,$a_yellow_card,$a_red_card,$a_goal);

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
                    SoccerPlayerMatchwiseStats::insertSoccerScore($user_id_b,$tournament_id,$match_id,$team_b_id,$b_player_name,$team_b_name,$b_yellow_card,$b_red_card,$b_goal);
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
                        $matchScheduleDetails->updateBracketDetails();
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

    private function move_forward_schedule( $match_id , $winner_team_id , $looser_team_id )
    {
            $match_data = MatchSchedule::where('id',$match_id)->first()->get();
            // winner go 
            if( isset( $match_data['winner_schedule_id'] ) && $match_data['winner_schedule_id'] * 1 > 0 ) 
            {
                $ab_id = $match_data['winner_schedule_position']."_id";
                MatchSchedule::where('id' , $match_data['winner_schedule_id'] )->update( [ $ab_id=>$winner_team_id ] );
            }

            if( isset( $match_data['loser_schedule_id'] ) && $match_data['loser_schedule_id'] * 1 > 0 ) 
            {
                $ab_id = $match_data['loser_schedule_position']."_id";
                if( $ab_id == 'a' || $ab_id == 'b' )
                    MatchSchedule::where('id' , $match_data['loser_schedule_id'] )->update( [ $ab_id=>$looser_team_id ] );
            }
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
        $match_report = $request['match_report'];
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

            $this->deny_match_edit_by_admin();

            $match_data = MatchSchedule::where('id',$match_id)->first()->get();

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
                                                                  'match_report'	  => $match_report,
                                                                  'score_added_by'=>$json_score_status]);

            
                    $this->move_forward_schedule( $match_id  , $winner_team_id , $looser_team_id  );

                    if(!empty($matchScheduleDetails['tournament_round_number'])) {
                        $matchScheduleDetails->updateBracketDetails();
                    }
                    if($match_status=='completed')
                    {
                        $sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');


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
                                                              'match_report'	  => $match_report,
                                                              'score_added_by' => $json_score_status,
                                                              'scoring_status'=>$approved]);
                $this->move_forward_schedule( $match_id  , $winner_team_id , $looser_team_id  );

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
                                                              'match_report'	  => $match_report,
                                                              'score_added_by'=>$json_score_status]);
                $this->move_forward_schedule( $match_id  , $winner_team_id , $looser_team_id  );
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
        SoccerStatistic::updateUserStatistic($user_id);
 

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
    public function updateSoccerScore($user_id,$match_id,$team_id,$player_name,$yellow_card_count,$red_card_count,$goal_count, $goals_details=[])
    {
        $player_stat=SoccerPlayerMatchwiseStats::where('user_id',$user_id)->where('match_id',$match_id)->where('team_id',$team_id)->update(['user_id'=>$user_id,'player_name'=>$player_name,'yellow_cards'=>$yellow_card_count,'red_cards'=>$red_card_count,'goals_scored'=>$goal_count]);
        //SoccerStatistic::where('user_id',$user_id)->update(['yellow_cards'=>$yellow_card_count,'red_cards'=>$red_card_count,'goals_scored'=>$goal_count]);
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

}




