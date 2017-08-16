<?php
namespace App\Http\Controllers\User\ScoreCard;

use Illuminate\Http\Request as ObjectRequest;       //get all my requests data as object

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Tournaments;
use App\Http\Controllers\User\ScoreCardController as parentScoreCardController;
use App\Model\MatchSchedule;
use App\Model\UserStatistic;
use App\Model\State;
use App\Model\City;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\Sport;
use App\Model\ThrowballPlayerMatchwiseStats;
use App\Model\ThrowballScore;
use App\Model\ThrowballStatistic;
use App\Model\ThrowballSetDetails;
use App\Model\SubstituteRecord;

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

class ThrowballscoreCardController extends parentScoreCardController
{
 
  private function move_forward_schedule( $match_id , $winner_team_id , $looser_team_id )
    {
            $match_data = MatchSchedule::where('id',$match_id)->first();
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



 public function throwballScoreCard($match_data,$sportsDetails=[],$tournamentDetails=[],$is_from_view=0)
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

        //get throwball scores for team a
        $team_a_throwball_scores = throwballPlayerMatchwiseStats::select()->where('match_id',$match_data[0]['id'])->where('team_id',$team_a_id)->get();

        $team_a_throwball_scores_array = array();
        if(count($team_a_throwball_scores)>0)
        {
            $team_a_throwball_scores_array = $team_a_throwball_scores->toArray();
        }

        //get throwball scores for team b
        $team_b_throwball_scores = throwballPlayerMatchwiseStats::select()->where('match_id',$match_data[0]['id'])->where('team_id',$team_b_id)->get();
        $team_b_throwball_scores_array = array();
        if(count($team_b_throwball_scores)>0)
        {
            $team_b_throwball_scores_array = $team_b_throwball_scores->toArray();
        }

        //get player names
        $a_team_players = User::select()->whereIn('id',$team_a_playerids)->get();
        $b_team_players = User::select()->whereIn('id',$team_b_playerids)->get();

        //get players statistics
        $team_a_players_stat=throwballPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_a_id)->get();
        $team_b_players_stat=throwballPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_b_id)->get();


        $active_players_a=throwballPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_a_id)->wherePlayingStatus('P')->lists('player_name', 'user_id')->toArray();
        $active_players_b=throwballPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_b_id)->wherePlayingStatus('P')->lists('player_name', 'user_id')->toArray();

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
            $sport_class = 'bascketball_scorecard ss_bg';
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
      

        //get throwball team scoring;
    $throwball_a_score=throwballScore::whereMatchId($match_data[0]['id'])->whereTeamId($match_data[0]['a_id'])->first();
    $throwball_b_score=throwballScore::whereMatchId($match_data[0]['id'])->whereTeamId($match_data[0]['b_id'])->first();


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
        $form_id = 'bascketball';

          $isAdminEdit = 0;
        if(Session::has('is_allowed_to_edit_match')){
            $session_data = Session::get('is_allowed_to_edit_match');

            if($isValidUser && ($session_data[0]['id']==$match_data[0]['id'])){
                $isAdminEdit=1;
            }
        }


        if(($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser) && !$isAdminEdit)//throwball score view only
        {
            $player_name_array = array();
            $users = User::select('id', 'name')->get()->toArray(); //get player names
            foreach ($users as $user) {
                $player_name_array[$user['id']] = $user['name']; //get team names
            }
            $player_of_the_match=$match_data[0]['player_of_the_match'];
            if($player_of_the_match_model=User::find($player_of_the_match))$player_of_the_match=$player_of_the_match_model;
            else $player_of_the_match=NULL;
            return view('scorecards.throwballscorecardview',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'team_a_throwball_scores_array'=>$team_a_throwball_scores_array,'team_b_throwball_scores_array'=>$team_b_throwball_scores_array,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'player_name_array'=> $player_name_array,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'form_id'=>$form_id,'team_a_players'=>$team_a_players, 'team_b_players'=>$team_b_players, 'player_of_the_match'=>$player_of_the_match,
                'throwball_a_score'=>$throwball_a_score,
                'throwball_b_score'=>$throwball_b_score));
        }else //throwball score view and edit
        {
            return view('scorecards.throwballscorecard',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'team_a_throwball_scores_array'=>$team_a_throwball_scores_array,'team_b_throwball_scores_array'=>$team_b_throwball_scores_array,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'form_id'=>$form_id, 
                'team_a_players'=>$team_a_players, 
                'team_b_players'=>$team_b_players, 
                'throwball_a_score'=>$throwball_a_score,
                'throwball_b_score'=>$throwball_b_score,
                'active_players_a'=>$active_players_a,
                'active_players_b'=>$active_players_b));
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

        $number_of_quarters=$request['preferences']['number_of_quarters'];
        $quarter_time=$request['preferences']['quarter_time'];
        $max_fouls=$request['preferences']['max_fouls'];

        $team_a_name=$request['team_a_name'];
        $team_b_name=$request['team_b_name'];
        $team_a_playing_players=isset($request['team_a']['playing'])?$request['team_a']['playing']:[];
        $team_b_playing_players=isset($request['team_b']['playing'])?$request['team_b']['playing']:[];

        $team_a_substitute_players=isset($request['team_a']['substitute'])?$request['team_a']['substitute']:[];
        $team_b_substitute_players=isset($request['team_b']['substitute'])?$request['team_b']['substitute']:[];

        

        foreach($team_a_playing_players as $p){

            $player_name=User::find($p)->name;            
            $this->insertthrowballscore($p, $tournament_id, $match_id, $team_a_id,$player_name, $team_a_name,'P');        }
        foreach($team_a_substitute_players as $p){

            $player_name=User::find($p)->name;
            $this->insertthrowballscore($p, $tournament_id, $match_id, $team_a_id,$player_name, $team_a_name,'S');
            
        }
        foreach($team_b_playing_players as $p){

            $player_name=User::find($p)->name;
           
            $this->insertthrowballscore($p, $tournament_id, $match_id, $team_b_id,$player_name, $team_b_name,'P');
          
        }
        foreach($team_b_substitute_players as $p){          
            $player_name=User::find($p)->name;
            $this->insertthrowballscore($p, $tournament_id, $match_id, $team_b_id,$player_name, $team_b_name,'S');
              
        }
        
        //insert the default match_details for the match
        $match_model=MatchSchedule::find($match_id);
    $match_settings   =   Helper::getMatchSettings($match_model['tournament_id'],$match_model['sports_id']);

    $set=$match_settings->number_of_sets;
    $maximum_points = $match_settings->maximum_points;
        $match_details=[
            "team_a"=>[
                "id"=>$team_a_id,
                "name"=>$team_a_name
                    ],
            "team_b"=>[
                "id"=>$team_b_id,
                "name"=>$team_b_name
                    ],                   
             "preferences"=>[
                        "left_team_id"=>$team_a_id,
                        "right_team_id"=>$team_b_id,                        
                        "number_of_sets"=>$set,                        
                        "end_point"=>$maximum_points,   
                        'score_to_win'=>$maximum_points                    
                    ],
            "match_details"=>[
                "set1"=>[
                        "{$team_a_id}_score"=>0,
                        "{$team_b_id}_score"=>0
                    ],
                "set2"=>[
                         "{$team_a_id}_score"=>0,
                        "{$team_b_id}_score"=>0
                    ],
                "set3"=>[
                        "{$team_a_id}_score"=>0,
                        "{$team_b_id}_score"=>0
                    ],
                "set4"=>[
                          "{$team_a_id}_score"=>0,
                        "{$team_b_id}_score"=>0
                    ],
                "set5"=>[
                         "{$team_a_id}_score"=>0,
                        "{$team_b_id}_score"=>0
                        ]
                ],                   
                "match_type"=>$match_model->match_type,
                "schedule_type"=>$match_model->schedule_type, 
                "current_set"=>1, 
                "scores"=>[
                    "{$team_a_id}_score"=>0,
                    "{$team_b_id}_score"=>0
                ]           
            
        ];

        $match_model->match_details=json_encode($match_details);
        $match_model->save();
        Helper::start_match_email($match_model);
    }


      public function insertthrowballscore($user_id,$tournament_id,$match_id,$team_id,$player_name,$team_name,$playing_status='S')
    {
        $throwball_model = new throwballPlayerMatchwiseStats();
        $throwball_model->user_id          = $user_id;
        $throwball_model->tournament_id    = $tournament_id;
        $throwball_model->match_id         = $match_id;
        $throwball_model->team_id          = $team_id;
        $throwball_model->player_name      = $player_name;
        $throwball_model->team_name        = $team_name;
        $throwball_model->playing_status   = $playing_status;
        $throwball_model->save();
    }


  
    public function manualScoring(ObjectRequest $request){
            $match_id=$request->match_id;

           

            $match_model=Matchschedule::find($match_id);
            $match_details=json_decode($match_model->match_details);

            $team_a=$match_model->a_id;
            $team_b=$match_model->b_id;


            $end_point=$match_details->preferences->end_point;
            $number_of_sets=$match_details->preferences->number_of_sets;


            $score_a_model=throwballScore::whereMatchId($match_id)->whereTeamId($team_a)->first();
            $score_b_model=throwballScore::whereMatchId($match_id)->whereTeamId($team_b)->first();

                    
            $match_details_data=$match_details->match_details;
           


            //start scoring

            for($i=1; $i<=$number_of_sets; $i++){

                if($i==5) $end_point=15; 
                    $score_a_model->{"set".$i}=$request->{"a_set".$i}>$end_point?$end_point:(int)$request->{"a_set".$i};
                    $score_b_model->{"set".$i}=$request->{"b_set".$i}>$end_point?$end_point:(int)$request->{"b_set".$i};

                    $match_details_data->{"set".$i}->{$team_a."_score"}=(int)$request->{"a_set".$i}>$end_point?$end_point:(int)$request->{"a_set".$i};
                    $match_details_data->{"set".$i}->{$team_b."_score"}=(int)$request->{"b_set".$i}>$end_point?$end_point:(int)$request->{"b_set".$i};
               }

            $score_a_model->save();
            $score_b_model->save();

            $match_details->match_details=$match_details_data;
            $match_details->scores=$this->getScoreSet($match_id);
            $match_details->current_set=$this->getCurrentSet($match_id);     //get current active set

            $match_model->match_details=json_encode($match_details);
            $match_model->save();

                       $this->deny_match_edit_by_admin();

        return 'match saved';
    }

     public function throwballStoreRecord(ObjectRequest $Objrequest){

       $request=Request::all();

        $match_id=$request['match_id'];
        $first_half=isset($request['first_half'])?$request['first_half']:[];
        $second_half=isset($request['second_half'])?$request['second_half']:[];
        $team_a_id=$request['team_a_id'];
        $team_b_id=$request['team_b_id'];
        $last_index=$request['last_index'];
        $match_data=matchSchedule::find($match_id);
        $match_details=$match_data['match_details'];
        $throwball_player=throwballPlayerMatchwiseStats::whereMatchId($match_id)->first();
        $delted_ids=$request['delted_ids'];
        $match_result=$request['match_result'];
        $match_report=$request['match_report'];
        $winner_team_id = !empty(Request::get('winner_team_id'))?Request::get('winner_team_id'):NULL;//winner_id
        $player_of_the_match=isset($request['player_of_the_match'])?$request['player_of_the_match']:NULL;

        $match_data->player_of_the_match=$player_of_the_match;

        $deleted_ids=explode(',',$delted_ids);

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

                 $this->deny_match_edit_by_admin();

            if(!empty($matchScheduleDetails['tournament_id'])) {
//                        dd($winner_team_id.'<>'.$looser_team_id);
                $tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
                if (($is_tie == 1 || $match_result == "washout") && !empty($matchScheduleDetails['tournament_group_id'])){

                    $match_status = 'completed';
                }
                if(Helper::isTournamentOwner($tournamentDetails['manager_id'],$tournamentDetails['tournament_parent_id'])) 
                {
                    MatchSchedule::where('id',$match_id)->update(['match_status'=>$match_status,
                        'winner_id'=>$winner_team_id ,
                        'looser_id'=>$looser_team_id,
                        'has_result'     => $has_result,
                        'match_report'=>$match_report,
                        'match_result'   => $match_result,
                        'is_tied'=>$is_tie, 'match_report'=>$match_report,
                        'score_added_by'=>$json_score_status]);
//                                Helper::printQueries();

                $this->move_forward_schedule( $match_id  , $winner_team_id , $looser_team_id  );

                    if(!empty($matchScheduleDetails['tournament_round_number'])) {
                        $matchScheduleDetails->updateBracketDetails();
                    }
                    if($match_status=='completed')
                    {
                    $sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');

                        $this->insertPlayerStatistics($sportName,$match_id);

                        //notification ocde
                        Helper::sendEmailPlayers($matchScheduleDetails, 'throwball');      

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
                    'match_report'=>$match_report,
                     'has_result'     => $has_result,
                     'match_result'   => $match_result,
                     'score_added_by' => $json_score_status,'scoring_status'=>$approved]);
                
                $this->move_forward_schedule( $match_id  , $winner_team_id , $looser_team_id  );

                if($match_status=='completed')
                {
                    $sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');

                    $this->insertPlayerStatistics($sportName,$match_id);

                    //send mail to players
                    Helper::sendEmailPlayers($matchScheduleDetails, 'throwball');      


                    //notification ocde
                }
            }
        else
            {
                MatchSchedule::where('id',$match_id)->update(['winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
                    'is_tied'=>$is_tie, 'match_report'=>$match_report,
                    'has_result'     => $has_result,
                     'match_result'   => $match_result,
                     'score_added_by'=>$json_score_status]);
                $this->move_forward_schedule( $match_id  , $winner_team_id , $looser_team_id  );
                
            }
        }
        return $match_data->match_details;

    }

        public function throwballStatistics($user_id)
    {
        //check already player has record or not
        $user_throwball_details = throwballStatistic::select()->where('user_id',$user_id)->get();

        $throwball_details = throwballPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'throwball_player_matchwise_stats.match_id')->selectRaw('count(match_id) as match_count')->selectRaw(' as points_1')->selectRaw('sum(points_2) as points_2')->selectRaw('sum(points_3) as points_3')->selectRaw('sum(total_points) as total_points')->selectRaw('sum(fouls) as fouls')->where('user_id',$user_id)->groupBy('user_id')->get();


        if(count($user_throwball_details)>0)
        {
            $match_count = (!empty($throwball_details[0]['match_count']))?$throwball_details[0]['match_count']:0;

            throwballStatistic::where('user_id',$user_id)
                ->update([  'matches'=>$match_count,
                            'won'=>$won,
                            'lost'=>$lost,
                            'tie'=>$tie,                            
                            'won_percentage'=>$won_percentage
                         ]);
        }else
        {
            $throwball_statistic = new throwballStatistic();
            $throwball_statistic->user_id = $user_id;
            $throwball_statistic->matches = 1;
            $throwball_statistic->won = 0;
            $throwball_statistic->lost = 0;
            $throwball_statistic->tie = 0;     
            $throwball_statistic->save();
        }
    }

        public function throwballSwapPlayers(){
        $request=Request::all();
        $match_id=$request['match_id'];
        $team_id=$request['team_id'];
        $time_substituted=$request['time_substituted'];
        $match_model=MatchSchedule::find($match_id);
        $throwball_model=throwballPlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_id)->get();

        $playing=[];
        $subst=[];
        $errors=[];

        //get match settings
        $match_settings = Helper::getMatchSettings($match_model['tournament_id'],$match_model['sports_id']);
        $maximum_substitutes = $match_settings->maximum_substitutes;
    
        //start swapping process
        foreach ($throwball_model as $sm ){
            $sm_id=$sm->id;
            $sm_status=$sm->playing_status;

$check_maximum_substitute=SubstituteRecord::whereMatchId($match_id)->whereUserId($sm->user_id)->get()->count();

            if($check_maximum_substitute<=$maximum_substitutes){
                if(isset($request["substitute_a_".$sm_id]) && ($request["substitute_a_".$sm_id]=='on')){
                    if($sm_status=='P'){
                    array_push($playing, ['user_id'=>$sm->user_id, 'id'=>$sm->id, 'serving_order'=>$sm->serving_order]);
                        $sm->playing_status='S';
                    }
                    else {
                    array_push($subst, ['user_id'=>$sm->user_id, 'id'=>$sm->id]);
                        $sm->playing_status='P'; 
                        }             
                   
                    $sm->save();
                }
             }
        }

         for($j=0; $j<count($playing); $j++){

                 $substitute_record=new SubstituteRecord;
                 $substitute_record->user_id=$playing[$j]['user_id'];
                 $substitute_record->substituted_by=$subst[$j]['user_id'];
                 $substitute_record->team_id=$sm->team_id;
                 $substitute_record->match_id=$match_id;
                 $substitute_record->sports_id=$match_model->sports_id;
                 $substitute_record->tournament_id=$match_model->tournament_id;                
                  $substitute_record->substituted_at=$time_substituted;
                  $substitute_record->save();

  throwballPlayerMatchwiseStats::find($playing[$j]['id'])->update(['serving_order'=>null]);
  throwballPlayerMatchwiseStats::find($subst[$j]['id'])->update(['serving_order'=>$playing[$j]['serving_order']]);

            }     

        return $throwball_model;

    }

    
  


    public function submitServingPlayers(ObjectRequest $request){
         $match_model=MatchSchedule::find($request->match_id);
         $team_a=$match_model->a_id;
         $team_b=$match_model->b_id;

         $team_a_score_model=throwballScore::whereMatchId($request->match_id)->whereTeamId($team_a)->first();

         if(!$team_a_score_model){

            //insert team scoring data. 
              $team_a_score_model=new throwballScore;
              $team_a_score_model->match_id=$match_model->id;
              $team_a_score_model->tournament_id    = $match_model->tournament_id;
              $team_a_score_model->team_id          = $match_model->a_id;
              $team_a_score_model->team_name        = Team::find($team_a)->name;

              if($request->team==$team_a){
                    $team_a_score_model->won_toss=1;
                    $team_a_score_model->elected=$request->elected;
              } 
              else {$team_a_score_model->won_toss=0;
                    $request->elected=='serve'?$team_a_score_model->elected='receive':$team_a_score_model->elected='serve';
              }

             
              $team_a_score_model->save();


            //insert team scoring data. 
              $team_b_score_model=new throwballScore;
              $team_b_score_model->match_id=$match_model->id;
              $team_b_score_model->tournament_id    = $match_model->tournament_id;
              $team_b_score_model->team_id          = $team_b;
              $team_b_score_model->team_name        = Team::find($team_b)->name;

            if($request->team==$team_b){
                    $team_b_score_model->won_toss=1;
                    $team_b_score_model->elected=$request->elected;
              } 
              else {$team_b_score_model->won_toss=0;
                    $request->elected=='serve'?$team_b_score_model->elected='receive':$team_b_score_model->elected='serve';
            }

              $team_b_score_model->save();

         }


         for($i=1; $i<=7; $i++){
                $player_model=throwballPlayerMatchwiseStats::whereUserId($request->{'serving_a_'.$i})->whereTeamId($team_a)->whereMatchId($request->match_id)->first()->update(['serving_order'=>$i]);               

                $player_model=throwballPlayerMatchwiseStats::whereUserId($request->{'serving_b_'.$i})->whereTeamId($team_b)->whereMatchId($request->match_id)->first()->update(['serving_order'=>$i]);
         }

         return 'success';
    }


      public function getScoreSet($match_id){
            $match_model=MatchSchedule::find($match_id);
            $team_a=$match_model->a_id;
            $team_b=$match_model->b_id;

            $left_player_model=throwballScore::whereMatchId($match_id)->whereTeamId($team_a)->first();
            $right_player_model=throwballScore::whereMatchId($match_id)->whereTeamId($team_b)->first();

            $left_player_score=0;
            $right_player_score=0;

            for($i=1; $i<=5; $i++){
                if($left_player_model->{'set'.$i}==$right_player_model->{'set'.$i}){
                    //nothing to do
                }
                elseif($left_player_model->{'set'.$i}>$right_player_model->{'set'.$i}){
                    $left_player_score++;
                }
                elseif($left_player_model->{'set'.$i}<$right_player_model->{'set'.$i}){
                   $right_player_score++;
                }
            }

        return [
            "{$team_a}_score"=>$left_player_score,
            "{$team_b}_score"=>$right_player_score
        ];
    }


    public function getCurrentSet($match_id){
            $match_model=MatchSchedule::find($match_id);
            $team_a=$match_model->a_id;
            $team_b=$match_model->b_id;


            $preferences=json_decode($match_model->match_details)->preferences;

            //first and second player(team) or left and right player(team)
    $match_score_model=throwballScore::whereMatchId($match_id)->whereTeamId($team_a)->first();
    $match_score_model_other=throwballScore::whereMatchId($match_id)->whereTeamId($team_b)->first();


            if($this->checkSet('set1', $match_score_model, $match_score_model_other, $preferences)){
                    return 1;                   
            }
            else{           //set1 is complete

                if($this->checkSet('set2', $match_score_model, $match_score_model_other, $preferences)){
                    return 2;  
                }

                else{       //set2 is complete
                        if($this->checkSet('set3', $match_score_model, $match_score_model_other, $preferences)){
                            return 3;  
                            }
                        else{
                      
                                if($this->checkSet('set4', $match_score_model, $match_score_model_other, $preferences)){
                                        return 4;
                                            
                                    }
                                    else{
                                        if($this->checkSet('set5', $match_score_model, $match_score_model_other, $preferences, 15)){
                                              return 5; 
                                         }
                                         else return 0;
                                }
                            
                            }
                        }
                }
    }


    public function checkSet($set, $match_score_model, $match_score_model_other, $preferences , $stw=0){
            $end_point = 1000;
            $score_to_win = $preferences->end_point;
            $number_of_sets = $preferences->number_of_sets;
            $enable_two_points = 'on';

            if($stw!=0) $score_to_win =15; 


            $set1_score=$match_score_model->{$set};
            $set1_opponent_score=$match_score_model_other->{$set};

            if($set1_score<$score_to_win && $set1_opponent_score<$score_to_win){
                return true;                 
                //if user one and two scores are less than the score to win, active set
            }

            else if($set1_score==$end_point || $set1_opponent_score==$end_point){
                return false;  
                //if a user score = end_point score, skip set. or complete set. 
            }

            else if($set1_score>=$score_to_win || $set1_opponent_score>=$score_to_win){
                if($enable_two_points=='on'){
                    if(($set1_score-$set1_opponent_score)>=2) return false;
                    elseif(($set1_opponent_score-$set1_score)>=2) return false;
                    else return true;
                }
                else{
                    return false;
                }
            }
            

    }

        public function addScore(ObjectRequest $request){


            $match_id=$request->match_id;           
            $team_id=(int)$request->team_id;
            $original_team_id=$team_id;
            $player_id=(int)$request->player_id;
            $action=$request->action;    //add or remove; 
            $val   =$request->val;           


            

            $match_model=MatchSchedule::find($match_id);            //match_schedule data
            $match_details=json_decode($match_model->match_details);
            $preferences=$match_details->preferences;
                            //opponent team data

            $end_point = 1000;
            $score_to_win = 25;
            $number_of_sets = 5;          
            $enable_two_points = 'on';

            $team_a=$match_model->a_id;
            $team_b=$match_model->b_id;
            $set_number=0;


           
                if($val=='won'){
                        
                }
                else{
                    if($team_id==$team_a){
                        $team_id=$team_b;
                    } 
                    else{
                        $team_id=$team_a;
                    } 

            //Exchange player positions
        DB::statement("UPDATE throwball_player_matchwise_stats SET serving_order = (serving_order %7) +1 WHERE match_id=$match_id AND playing_status='P'");

                }
           
            //get current active set;
               
            
            // Check if set1 is complete

                //first and second player(team) or left and right player(team)
    $match_score_model=throwballScore::whereMatchId($match_id)->whereTeamId($team_id)->first();
    $match_score_model_other=throwballScore::whereMatchId($match_id)->where('team_id','!=',$team_id)->first();

    $match_score_model->elected='serve';
    $match_score_model_other->elected='receive';

    $match_score_model->save();
    $match_score_model_other->save();

    $left_player_model=$match_score_model;
    $right_player_model=$match_score_model_other;

            if($this->checkSet('set1', $left_player_model, $right_player_model, $preferences)){

                    if($action=='remove' && $match_score_model->set1>0 ){   //remove point if set_score>0
                                $match_score_model->set1--;
                                $match_score_model->save();

                                $match_details->match_details->set1->{$team_id."_score"}=$match_score_model->set1;

                               
                    }
                    elseif($action=='add') {

                                $match_score_model->set1++;
                                $match_score_model->save(); 

                                $match_details->match_details->set1->{$team_id."_score"}=$match_score_model->set1;   $set_number=1;                            
                    }  
            }
            else{           //set1 is complete

                if($this->checkSet('set2',$left_player_model, $right_player_model, $preferences)){
                        if($action=='remove' && $match_score_model->set2>0 ){
                            $match_score_model->set2--;
                            $match_score_model->save(); 
            $match_details->match_details->set2->{$team_id."_score"} =$match_score_model->set2;                         
                        }
                        elseif($action=='add') {
                                    $match_score_model->set2++;
                                    $match_score_model->save();
            $match_details->match_details->set2->{$team_id."_score"} =$match_score_model->set2;
                                     $set_number=2;
                        }  
                }

                else{       //set2 is complete
                        if($this->checkSet('set3', $match_score_model, $match_score_model_other, $preferences)){
                                if($action=='remove' && $match_score_model->set3>0 ){
                                    $match_score_model->set3--;
                                    $match_score_model->save();
            $match_details->match_details->set3->{$team_id."_score"} =$match_score_model->set3;
                                    
                                }
                                elseif($action=='add') {
                                    $match_score_model->set3++;
                                    $match_score_model->save();
            $match_details->match_details->set3->{$team_id."_score"} =$match_score_model->set3;
                                         $set_number=3;
                                }    
                            }
                        else{

                            if($number_of_sets>3){

                                    if($this->checkSet('set4', $match_score_model, $match_score_model_other, $preferences)){
                                            if($action=='remove' && $match_score_model->set4>0 ){

                                                $match_score_model->set4--;
                                                $match_score_model->save();

            $match_details->match_details->set4->{$team_id."_score"} =$match_score_model->set4;                                                
                                            }
                                            elseif($action=='add') {
                                                $match_score_model->set4++;
                                                $match_score_model->save();
            $match_details->match_details->set4->{$team_id."_score"} =$match_score_model->set4;
                                                 $set_number=4;
                                         } 
                                    }
                                    else{
                                        if($this->checkSet('set5', $match_score_model, $match_score_model_other, $preferences, 15)){
                                               if($action=='remove' && $match_score_model->set5>0 ){

                                                    $match_score_model->set5--;
                                                    $match_score_model->save();
            $match_details->match_details->set5->{$team_id."_score"} =$match_score_model->set5;
                                                 
                                                }
                                                elseif($action=='add') {
                                                    $match_score_model->set5++;
                                                    $match_score_model->save();
            $match_details->match_details->set5->{$team_id."_score"} =$match_score_model->set5;
                                                     $set_number=5;
                                              }  
                                         }
                                }
                            
                            }
                        }
                }
            }

        $match_details->current_set=$this->getCurrentSet($match_id);
        $match_details->scores=$this->getScoreSet($match_id);

        $match_details=json_encode($match_details);
        $match_model->match_details=$match_details;
        $match_model->save();
        $match_score_model->total_points=$match_score_model->set1 + $match_score_model->set2 + $match_score_model->set3 + $match_score_model->set4 + $match_score_model->set5;      
        $match_score_model->save();

        //insert record;

        $setDetails=throwballSetDetails::whereMatchId($match_id)->whereTeamId($original_team_id)->whereSetNumber($set_number)->whereServerId($player_id);

        if($val=='won'){                        
                
        }
        else {

        }


       $match_details=json_decode($match_details);
       $match_details->server=Helper::getVolleyballServer($match_id, 'throwball');

        return json_encode($match_details);
    }

 }