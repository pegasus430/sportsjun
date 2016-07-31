<?php

namespace App\Http\Controllers\User\ScoreCard;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\ScoreCardController as parentScoreCardController;
use App\Model\MatchSchedule;
use App\Model\UserStatistic;
use App\Model\State;
use App\Model\City;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\Sport;
use App\Model\HockeyPlayerMatchwiseStats;
use App\Model\HockeyStatistic;
use App\Model\Photo;
use App\User;
use DB;
use Carbon\Carbon;
use Response;
use Auth;
use App\Helpers\Helper;
use DateTime;
use App\Helpers\AllRequests;
use Session;
use Request;
use App\Model\Tournaments;

class HockeyScorecardController extends parentScoreCardController
{
    

    public function hockeyScoreCard($match_data,$sportsDetails=[],$tournamentDetails=[],$is_from_view=0)
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

        //get hockey scores for team a
        $team_a_hockey_scores = HockeyPlayerMatchwiseStats::select()->where('match_id',$match_data[0]['id'])->where('team_id',$team_a_id)->get();

        $team_a_hockey_scores_array = array();
        if(count($team_a_hockey_scores)>0)
        {
            $team_a_hockey_scores_array = $team_a_hockey_scores->toArray();
        }

        //get hockey scores for team b
        $team_b_hockey_scores = HockeyPlayerMatchwiseStats::select()->where('match_id',$match_data[0]['id'])->where('team_id',$team_b_id)->get();
        $team_b_hockey_scores_array = array();
        if(count($team_b_hockey_scores)>0)
        {
            $team_b_hockey_scores_array = $team_b_hockey_scores->toArray();
        }

        //get player names
        $a_team_players = User::select()->whereIn('id',$team_a_playerids)->get();
        $b_team_players = User::select()->whereIn('id',$team_b_playerids)->get();

        //get players statistics
        $team_a_players_stat=HockeyPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_a_id)->get();
        $team_b_players_stat=HockeyPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_b_id)->get();

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
            $sport_class = 'hockey_scorecard ss_bg';
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
        $form_id = 'hockey';

        if($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser)//hockey score view only
        {
            $player_name_array = array();
            $users = User::select('id', 'name')->get()->toArray(); //get player names
            foreach ($users as $user) {
                $player_name_array[$user['id']] = $user['name']; //get team names
            }
            $player_of_the_match=$match_data[0]['player_of_the_match'];
            if($player_of_the_match_model=User::find($player_of_the_match))$player_of_the_match=$player_of_the_match_model;
            else $player_of_the_match=NULL;
            return view('scorecards.hockeyscorecardview',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'team_a_hockey_scores_array'=>$team_a_hockey_scores_array,'team_b_hockey_scores_array'=>$team_b_hockey_scores_array,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'team_a_goals'=>$team_a_goals,'team_b_goals'=>$team_b_goals,'player_name_array'=> $player_name_array,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'team_a_red_count'=>$team_a_red_count,'team_a_yellow_count'=>$team_a_yellow_count,'team_b_red_count'=>$team_b_red_count,'team_b_yellow_count'=>$team_b_yellow_count,'form_id'=>$form_id,'team_a_players'=>$team_a_players, 'team_b_players'=>$team_b_players, 'player_of_the_match'=>$player_of_the_match));
        }else //hockey score view and edit
        {
            return view('scorecards.hockeyscorecard',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'team_a_hockey_scores_array'=>$team_a_hockey_scores_array,'team_b_hockey_scores_array'=>$team_b_hockey_scores_array,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'team_a_goals'=>$team_a_goals,'team_b_goals'=>$team_b_goals,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'team_a_red_count'=>$team_a_red_count,'team_a_yellow_count'=>$team_a_yellow_count,'team_b_red_count'=>$team_b_red_count,'team_b_yellow_count'=>$team_b_yellow_count,'form_id'=>$form_id, 'team_a_players'=>$team_a_players, 'team_b_players'=>$team_b_players));
        }

    }


    public function insertAndUpdateHockeyScoreCard(){

        $request=Request::all();

        $match_id=$request['match_id'];
        $first_half=isset($request['first_half'])?$request['first_half']:[];
        $second_half=isset($request['second_half'])?$request['second_half']:[];
        $team_a_id=$request['team_a_id'];
        $team_b_id=$request['team_b_id'];
        $last_index=$request['last_index'];
        $match_data=matchSchedule::find($match_id);
        $match_details=$match_data['match_details'];
        $hockey_player=HockeyPlayerMatchwiseStats::whereMatchId($match_id)->first();
        $delted_ids=$request['delted_ids'];
        $match_result=$request['match_result'];
        $winner_team_id = !empty(Request::get('winner_team_id'))?Request::get('winner_team_id'):NULL;//winner_id
        $player_of_the_match=isset($request['player_of_the_match'])?$request['player_of_the_match']:NULL;

        $match_data->player_of_the_match=$player_of_the_match;

        $deleted_ids=explode(',',$delted_ids);


        if(empty($match_details) || !isset(json_decode($match_details)->first_half)){
            $match_details=[
                "team_a"=>[
                    "id"=>$team_a_id,
                    "name"=>$team_a_name
                    ],
                "team_b"=>[
                    "id"=>$team_b_id,
                    "name"=>$team_b_name
                    ],        
                "{$team_a_id}"  =>  [
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

            //match result = 'no result' ; discard all match details;
        

       $is_tie         = ($match_result == 'tie')      ? 1 : 0;
         $is_washout     = ($match_result == 'washout')  ? 1 : 0;
         $has_result     = ($is_washout == 1) ? 0 : 1;
        $match_result   = ( !in_array( $match_result, ['tie','win','washout'] ) ) ? NULL : $match_result;
        
        $matchScheduleDetails = MatchSchedule::where('id',$match_id)->first();
        if(count($matchScheduleDetails)) {
            $looser_team_id = NULL;
            $match_status='scheduled';
            $approved = '';
             if($is_tie==0 || $is_washout == 0) {

                if(isset($winner_team_id)) {
                    //$match_details=(object)$match_details;
                    // if($match_details->{$team_a_id}->goals>$match_details->{$team_b_id}->goals){
                    //   $looser_team_id=$matchScheduleDetails['b_id'];
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
                        Helper::sendEmailPlayers($matchScheduleDetails, 'Soccer');      

                    }

                }

            }
        else if(Auth::user()->role=='admin'){
             if ($is_tie == 1 || $match_result == "washout"){
                    $match_status = 'completed';
                    $approved = 'approved';
                }
                MatchSchedule::where('id',$match_id)->update(['match_status'=>$match_status,
                    'winner_id'      => $winner_team_id,
                     'looser_id'      => $looser_team_id,
                    'is_tied'        => $is_tie,
                     'has_result'     => $has_result,
                     'match_result'   => $match_result,
                     'score_added_by' => $json_score_status,'scoring_status'=>$approved]);

                if($match_status=='completed')
                {
                    $sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');

                    $this->insertPlayerStatistics($sportName,$match_id);

                    //send mail to players
                    Helper::sendEmailPlayers($matchScheduleDetails, 'Hockey');      


                    //notification ocde
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

    public function hockeyStoreRecord(){
            $request=Request::all();
            $match_id=$request['match_id'];
        $first_half=isset($request['first_half'])?$request['first_half']:[];
        $second_half=isset($request['second_half'])?$request['second_half']:[];
        $team_a_id=$request['team_a_id'];
        $team_b_id=$request['team_b_id'];
        $i=$request['index'];
        $match_data=matchSchedule::find($match_id);
        $match_details=json_decode($match_data['match_details']);
        $hockey_player=HockeyPlayerMatchwiseStats::whereMatchId($match_id)->first();

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


                $hockey_model=HockeyPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_id)->whereUserId($user_id)->first();
                $goals_count=$hockey_model['goals_scored'];
                $yellow_card_count=$hockey_model['yellow_cards'];
                $red_card_count=$hockey_model['red_cards'];

                ${$record_type.'_count'}++;
                $match_details=(object)$match_details;      //temporally convert to object to get numeric property
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
                        $hockey_model->red_cards=1;
                    }
                }

                $match_details[$half_time][$record_type_count]+=1;
                $match_details[$half_time]['team_'.$team_id.'_'.$record_type_count]+=1;     //increments value for goal or yellow card or red card for team in specified halftime

                array_push($match_details[$half_time][$record_type.'_details'], $record_type_details);

                $hockey_model->save();

                $this->updateHockeyScore($user_id,$match_id,$team_id,$player_name,$yellow_card_count,$red_card_count,$goals_count);
                $this->hockeyStatistics($user_id);          


        $match_data->match_details=json_encode($match_details);
        $match_data->save();
            return $match_data->match_details;

    }

    public function getHockeyDetails(){
        $request=Request::all();
        $match_id=$request['match_id'];
        $team_a_id=$request['team_a_id'];
        $team_b_id=$request['team_b_id'];
        $match_details=matchSchedule::find($match_id)->match_details;
        return view('scorecards.hockeyscorecarddetails', compact('match_details','team_a_id', 'team_b_id'));
    }


    //function to update player scores if already exist
    public function updateHockeyScore($user_id,$match_id,$team_id,$player_name,$yellow_card_count,$red_card_count,$goal_count, $goals_details=[])
    {
        $player_stat=HockeyPlayerMatchwiseStats::where('user_id',$user_id)->where('match_id',$match_id)->where('team_id',$team_id)->update(['user_id'=>$user_id,'player_name'=>$player_name,'yellow_cards'=>$yellow_card_count,'red_cards'=>$red_card_count,'goals_scored'=>$goal_count]);
        //hockeyStatistic::where('user_id',$user_id)->update(['yellow_cards'=>$yellow_card_count,'red_cards'=>$red_card_count,'goals_scored'=>$goal_count]);
    }
    //hockey statistics function player wise
    public function hockeyStatistics($user_id)
    {
        //check already player has record or not
        $user_hockey_details = HockeyStatistic::select()->where('user_id',$user_id)->get();

        $hockey_details = HockeyPlayerMatchwiseStats::selectRaw('count(match_id) as match_count')->selectRaw('sum(yellow_cards) as yellow_cards')->selectRaw('sum(red_cards) as red_cards')->selectRaw('sum(goals_scored) as goals_scored')->where('user_id',$user_id)->groupBy('user_id')->get();
        $yellow_card_cnt = (!empty($hockey_details[0]['yellow_cards']))?$hockey_details[0]['yellow_cards']:0;
        $red_card_cnt = (!empty($hockey_details[0]['red_cards']))?$hockey_details[0]['red_cards']:0;
        $goals_cnt = (!empty($hockey_details[0]['goals_scored']))?$hockey_details[0]['goals_scored']:0;
        if(count($user_hockey_details)>0)
        {
            $match_count = (!empty($hockey_details[0]['match_count']))?$hockey_details[0]['match_count']:0;
            HockeyStatistic::where('user_id',$user_id)->update(['matches'=>$match_count,'yellow_cards'=>$yellow_card_cnt,'red_cards'=>$red_card_cnt,'goals_scored'=>$goals_cnt]);
        }else
        {
            $hockey_statistics = new HockeyStatistic();
            $hockey_statistics->user_id = $user_id;
            $hockey_statistics->matches = 1;
            $hockey_statistics->yellow_cards = $yellow_card_cnt;
            $hockey_statistics->red_cards = $red_card_cnt;
            $hockey_statistics->goals_scored = $goals_cnt;
            $hockey_statistics->save();
        }
    }
    //check is score enter for match
    public function isScoreEntered($user_id,$match_id,$team_id)
    {
        $request_array = HockeyPlayerMatchwiseStats::where('user_id',$user_id)->where('match_id',$match_id)->where('team_id',$team_id)->first();
        if(count($request_array)>0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    //select players for the match, substitute and playing. 
    public function confirmSquad(){
        $request=Request::all();
        $match_id       =$request['match_id'];

        $tournament_id  =isset($request['tournament_id'])?$request['tournament_id']:null;
        $team_a_id      =$request['team_a_id'];
        $team_b_id      =$request['team_b_id'];

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
            $this->insertHockeyScore($p, $tournament_id, $match_id, $team_a_id,$player_name, $team_a_name,0,0,0,'P');
        }
        foreach($team_a_substitute_players as $p){
            $player_name=User::find($p)->name;
            $this->insertHockeyScore($p, $tournament_id, $match_id, $team_a_id,$player_name, $team_a_name,0,0,0,'S');
                
        }
        foreach($team_b_playing_players as $p){
            $player_name=User::find($p)->name;
            $this->insertHockeyScore($p, $tournament_id, $match_id, $team_b_id,$player_name, $team_b_name,0,0,0,'P');
        }
        foreach($team_b_substitute_players as $p){          
            $player_name=User::find($p)->name;
            $this->insertHockeyScore($p, $tournament_id, $match_id, $team_b_id,$player_name, $team_b_name,0,0,0,'S');
                
        }
        
        //insert the default match_details for the match
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
            "{$team_a_id}"  =>  [
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



    /**Swap Player status from substitute to playing vice versa.
     *
     * @return Response
     */
    public function hockeySwapPlayers(){
        $request=Request::all();
        $match_id=$request['match_id'];
        $team_id=$request['team_id'];
        $time_substituted=$request['time_substituted'];
        $hockey_model=HockeyPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_id)->get();

        foreach ($hockey_model as $sm ){
            $sm_id=$sm->id;
            $sm_status=$sm->playing_status;

            if(isset($request["substitute_a_".$sm_id]) && ($request["substitute_a_".$sm_id]=='on' && $sm->has_substituted==0)){
                if($sm_status=='P'){
                    $sm->playing_status='S';
                }
                else $sm->playing_status='P';

                $sm->has_substituted=1;
                $sm->time_substituted=$time_substituted;
                $sm->save();

            }

        }
        return $hockey_model;

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
                $matchwise_model=HockeyPlayerMatchwiseStats::find($player_id);
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
                $matchwise_model=HockeyPlayerMatchwiseStats::find($player_id);
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

    //insert choosen players in match statistics
    public function insertHockeyScore($user_id,$tournament_id,$match_id,$team_id,$player_name,$team_name,$yellow_card_count,$red_card_count,$goal_count, $playing_status='S')
    {
        $hockey_model = new HockeyPlayerMatchwiseStats();
        $hockey_model->user_id          = $user_id;
        $hockey_model->tournament_id    = $tournament_id;
        $hockey_model->match_id         = $match_id;
        $hockey_model->team_id          = $team_id;
        $hockey_model->player_name      = $player_name;
        $hockey_model->team_name        = $team_name;
        $hockey_model->yellow_cards     = $yellow_card_count;
        $hockey_model->red_cards        = $red_card_count;
        $hockey_model->goals_scored     = $goal_count;
        $hockey_model->playing_status   = $playing_status;
        $hockey_model->save();
    }


}
