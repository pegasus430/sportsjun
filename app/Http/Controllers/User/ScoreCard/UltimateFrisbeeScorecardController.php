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
use App\Model\UltimateFrisbeePlayerMatchwiseStats;
use App\Model\UltimateFrisbeePlayerMatchScore;
use App\Model\UltimateFrisbeeStatistic;
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

class UltimateFrisbeeScoreCardController extends parentScoreCardController
{
 
 public function ultimateFrisbeeScoreCard($match_data,$sportsDetails=[],$tournamentDetails=[],$is_from_view=0)
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
        $match_players = ultimateFrisbeePlayerMatchwiseStats::whereMatchId($match_id)->get(['user_id'])->toArray();

        //get ultimateFrisbee scores for team a
        $team_a_ultimateFrisbee_scores = ultimateFrisbeePlayerMatchwiseStats::select()->where('match_id',$match_data[0]['id'])->where('team_id',$team_a_id)->get();

        $team_a_ultimateFrisbee_scores_array = array();
        if(count($team_a_ultimateFrisbee_scores)>0)
        {
            $team_a_ultimateFrisbee_scores_array = $team_a_ultimateFrisbee_scores->toArray();
        }

        //get ultimateFrisbee scores for team b
        $team_b_ultimateFrisbee_scores = ultimateFrisbeePlayerMatchwiseStats::select()->where('match_id',$match_data[0]['id'])->where('team_id',$team_b_id)->get();
        $team_b_ultimateFrisbee_scores_array = array();
        if(count($team_b_ultimateFrisbee_scores)>0)
        {
            $team_b_ultimateFrisbee_scores_array = $team_b_ultimateFrisbee_scores->toArray();
        }

        //get player names
        $a_team_players = User::select()->whereIn('id',$team_a_playerids)->get();
        $b_team_players = User::select()->whereIn('id',$team_b_playerids)->get();

        //get players statistics
        $team_a_players_stat=ultimateFrisbeePlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_a_id)->get();
        $team_b_players_stat=ultimateFrisbeePlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_b_id)->get();

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
        $form_id = 'bascketball';

       $isAdminEdit = 0;
        if(Session::has('is_allowed_to_edit_match')){
            $session_data = Session::get('is_allowed_to_edit_match');

            if($isValidUser && ($session_data[0]['id']==$match_data[0]['id'])){
                $isAdminEdit=1;
            }
        }


        if(($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser) && !$isAdminEdit)//ultimateFrisbee score view only
        {
            $player_name_array = array();
            $users = User::select('id', 'name')->get()->toArray(); //get player names
            foreach ($users as $user) {
                $player_name_array[$user['id']] = $user['name']; //get team names
            }
            $player_of_the_match=$match_data[0]['player_of_the_match'];
            if($player_of_the_match_model=User::find($player_of_the_match))$player_of_the_match=$player_of_the_match_model;
            else $player_of_the_match=NULL;
            return view('scorecards.ultimateFrisbeescorecardview',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'team_a_ultimateFrisbee_scores_array'=>$team_a_ultimateFrisbee_scores_array,'team_b_ultimateFrisbee_scores_array'=>$team_b_ultimateFrisbee_scores_array,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'team_a_goals'=>$team_a_goals,'team_b_goals'=>$team_b_goals,'player_name_array'=> $player_name_array,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'team_a_red_count'=>$team_a_red_count,'team_a_yellow_count'=>$team_a_yellow_count,'team_b_red_count'=>$team_b_red_count,'team_b_yellow_count'=>$team_b_yellow_count,'form_id'=>$form_id,'team_a_players'=>$team_a_players, 'team_b_players'=>$team_b_players, 'player_of_the_match'=>$player_of_the_match, 'match_players'=>$match_players));
        }else //ultimateFrisbee score view and edit
        {
            return view('scorecards.ultimateFrisbeescorecard',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'team_a_ultimateFrisbee_scores_array'=>$team_a_ultimateFrisbee_scores_array,'team_b_ultimateFrisbee_scores_array'=>$team_b_ultimateFrisbee_scores_array,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'team_a_goals'=>$team_a_goals,'team_b_goals'=>$team_b_goals,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'team_a_red_count'=>$team_a_red_count,'team_a_yellow_count'=>$team_a_yellow_count,'team_b_red_count'=>$team_b_red_count,'team_b_yellow_count'=>$team_b_yellow_count,'form_id'=>$form_id, 'team_a_players'=>$team_a_players, 'team_b_players'=>$team_b_players, 'match_players'=>$match_players));
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

        $players_a_details=[];
        $players_b_details=[];

        $default_player_details=[           //default player info
            "points_1"=>0,
            "points_2"=>0,
            "points_3"=>0,
            "fouls"=>0,
            "total_points"=>0,
            "playing_status"=>0,
            "dismissed"=>0, 
            "quarters_played"=>'',
            "quarter_1"=>[
                    "points_1"=>0,
                    "points_2"=>0,
                    "points_3"=>0,
                    "fouls"=>0,
                    "total_points"=>0,
            ],
            "quarter_2"=>[
                    "points_1"=>0,
                    "points_2"=>0,
                    "points_3"=>0,
                    "fouls"=>0,
                    "total_points"=>0,
            ],
            "quarter_3"=>[
                    "points_1"=>0,
                    "points_2"=>0,
                    "points_3"=>0,
                    "fouls"=>0,
                    "total_points"=>0
            ],
            "quarter_4"=>[
                    "points_1"=>0,
                    "points_2"=>0,
                    "points_3"=>0,
                    "fouls"=>0,
                    "total_points"=>0,
                ],
            "quarter_5"=>(object)[
                    "points_1"=>0,
                    "points_2"=>0,
                    "points_3"=>0,
                    "fouls"=>0,
                    "total_points"=>0,
                ],
            "quarter_6"=>(object)[
                    "points_1"=>0,
                    "points_2"=>0,
                    "points_3"=>0,
                    "fouls"=>0,
                    "total_points"=>0,
                ]
        ];

        foreach($team_a_playing_players as $p){

            $player_name=User::find($p)->name;
            $default_player_details['playing_status']=1;
            $this->insertultimateFrisbeeScore($p, $tournament_id, $match_id, $team_a_id,$player_name, $team_a_name,'P');
            $players_a_details['player_'.$p]=$default_player_details;
        }
        foreach($team_a_substitute_players as $p){
            $player_name=User::find($p)->name;
            $this->insertultimateFrisbeeScore($p, $tournament_id, $match_id, $team_a_id,$player_name, $team_a_name,'S');
            $players_a_details['player_'.$p]=$default_player_details; 
        }
        foreach($team_b_playing_players as $p){

            $player_name=User::find($p)->name;
             $default_player_details['playing_status']=1;
            $this->insertultimateFrisbeeScore($p, $tournament_id, $match_id, $team_b_id,$player_name, $team_b_name,'P');
            $players_b_details['player_'.$p]=$default_player_details;
        }
        foreach($team_b_substitute_players as $p){          
            $player_name=User::find($p)->name;
            $this->insertultimateFrisbeeScore($p, $tournament_id, $match_id, $team_b_id,$player_name, $team_b_name,'S');
            $players_b_details['player_'.$p]=$default_player_details;     
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
                "total_points"=>0,
                "fouls"=>0,
                "players"=>$players_a_details,
                ],
            "{$team_b_id}"=>[
                "id"=>$team_b_id,
                "total_points"=>0,
                "fouls"=>0,
                "players"=>$players_b_details
                ] ,           
            "first_half"=>[

            ],
            "second_half"=>[
               
            ],          
            "preferences"=>[
                    'number_of_quarters'=>$number_of_quarters,
                    'quarter_time'=>$quarter_time,
                    'max_fouls'=>$max_fouls
                 ],
            
            
        ];

        $match_model->match_details=json_encode($match_details);
        $match_model->save();
        Helper::start_match_email($match_model);
    }


      public function insertultimateFrisbeeScore($user_id,$tournament_id,$match_id,$team_id,$player_name,$team_name,$playing_status='S')
    {
        $ultimateFrisbee_model = new ultimateFrisbeePlayerMatchwiseStats();
        $ultimateFrisbee_model->user_id          = $user_id;
        $ultimateFrisbee_model->tournament_id    = $tournament_id;
        $ultimateFrisbee_model->match_id         = $match_id;
        $ultimateFrisbee_model->team_id          = $team_id;
        $ultimateFrisbee_model->player_name      = $player_name;
        $ultimateFrisbee_model->team_name        = $team_name;
        $ultimateFrisbee_model->playing_status   = $playing_status;
        $ultimateFrisbee_model->save();
    }


    public function manualScoring(ObjectRequest $request){
            $match_id=$request->match_id;
            $match_model=MatchSchedule::find($match_id);
            $match_details=json_decode($match_model->match_details);
            $team_a_id=$match_model->a_id;
            $team_b_id=$match_model->b_id;

            ${$team_a_id.'_fouls'}=0;
            ${$team_b_id.'_fouls'}=0;

            ${$team_a_id.'_points'}=0;
            ${$team_b_id.'_points'}=0;

            $preferences=$match_details->preferences;
            $number_of_quarters=$preferences->number_of_quarters;
            $max_fouls=$preferences->max_fouls;

            $players_stats=ultimateFrisbeePlayerMatchwiseStats::whereMatchId($match_id)->get();

            foreach ($players_stats as $key => $player) {
                //stores quarters played
                        //$player->quarters_played=$request->{'quarters_'.$player->id};
                        //$match_details->{$player->team_id}->players->{'player_'.$player->user_id}->quarters_played=$request->{'quarters_'.$player->id};

                //number of points type
                    $player->points_1=$request->{'points_1_'.$player->id};
                    $player->points_2=$request->{'points_2_'.$player->id};
                    $player->points_3=$request->{'points_3_'.$player->id};

                $match_details->{$player->team_id}->players->{'player_'.$player->user_id}->points_1=$player->points_1;
                $match_details->{$player->team_id}->players->{'player_'.$player->user_id}->points_2=$player->points_2;
                $match_details->{$player->team_id}->players->{'player_'.$player->user_id}->points_3=$player->points_3;
                

                                //if player fouls is greater than max, return max to the player.
                        if($request->{'fouls_'.$player->id}>$max_fouls) $request->{'fouls_'.$player->id}=$max_fouls;

                                    //stores fouls per player
                        $player->fouls=$request->{'fouls_'.$player->id};

                        $match_details->{$player->team_id}->players->{'player_'.$player->user_id}->fouls=$request->{'fouls_'.$player->id};
                        
                        if($player->fouls>=$max_fouls){
                     $match_details->{$player->team_id}->players->{'player_'.$player->user_id}->dismissed=1;
                           $player->playing_status = 'S';
                            }
                            
                                    //stores points per player
                        $total_points_per_player=0;
                                for($i=1; $i<=$number_of_quarters; $i++){

                            $total_points_per_player+=$request->{'quarters_'.$i.'_player_'.$player->id};
                            $match_details->{$player->team_id}->players->{'player_'.$player->user_id}->{'quarter_'.$i}->total_points=$request->{'quarters_'.$i.'_player_'.$player->id};

                            $player['quarter_'.$i]=$request->{'quarters_'.$i.'_player_'.$player->id};
                        

                                }

                        //stores players total points
                            $player->total_points=$total_points_per_player;
                            $player->save();

                        //store points to team
                        ${$player->team_id.'_points'}+=$player->total_points;
                                //stores fouls to teams
                        ${$player->team_id.'_fouls'}+=$player->fouls;

            }

        $match_details->{$team_a_id}->fouls=${$team_a_id.'_fouls'};
        $match_details->{$team_b_id}->fouls=${$team_b_id.'_fouls'};

        $match_details->{$team_a_id}->total_points=${$team_a_id.'_points'};
        $match_details->{$team_b_id}->total_points=${$team_b_id.'_points'};

        $match_details=json_encode($match_details);

        $match_model->match_details=$match_details;
        $match_model->save();

        return $match_details;
    
    }

     public function ultimateFrisbeeStoreRecord(ObjectRequest $Objrequest){

       $request=Request::all();

        $match_id=$request['match_id'];
        $first_half=isset($request['first_half'])?$request['first_half']:[];
        $second_half=isset($request['second_half'])?$request['second_half']:[];
        $team_a_id=$request['team_a_id'];
        $team_b_id=$request['team_b_id'];
        $last_index=$request['last_index'];
        $match_data=matchSchedule::find($match_id);
        $match_details=$match_data['match_details'];
        $ultimateFrisbee_player=ultimateFrisbeePlayerMatchwiseStats::whereMatchId($match_id)->first();
        $delted_ids=$request['delted_ids'];
        $match_result=$request['match_result'];
        $match_report= $request['match_report'];
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
        $match_result   = ( !in_array( $match_result, ['tie','win','washout', 'walkover'] ) ) ? NULL : $match_result;
        
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

        if($match_result=='walkover'){
                    $sub_match_details = json_decode($matchScheduleDetails->match_details);
                    $sub_match_details->{$winner_team_id}->total_points=13;
                    $sub_match_details->{$looser_team_id}->total_points=0;
                    $matchScheduleDetails->match_details=json_encode($sub_match_details);
                    $matchScheduleDetails->save();
        }

            if(!empty($matchScheduleDetails['tournament_id'])) {
//                        dd($winner_team_id.'<>'.$looser_team_id);
                $tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
                if (($is_tie == 1 || $match_result == "washout" || $match_result =='walkover') && !empty($matchScheduleDetails['tournament_group_id'])){

                    $match_status = 'completed';
                }
                if(Helper::isTournamentOwner($tournamentDetails['manager_id'],$tournamentDetails['tournament_parent_id'])) 
                {
                    MatchSchedule::where('id',$match_id)->update(['match_status'=>$match_status,
                        'winner_id'=>$winner_team_id ,
                        'looser_id'=>$looser_team_id,
                        'has_result'     => $has_result,
                        'match_report'   => $match_report,
                        'match_result'   => $match_result,
                        'is_tied'=>$is_tie,
                        'score_added_by'=>$json_score_status]);
//                                Helper::printQueries();

                    if(!empty($matchScheduleDetails['tournament_round_number'])) {
                        $this->updateBracketDetails($matchScheduleDetails,$tournamentDetails,$winner_team_id);
                    }
                    if($match_status=='completed')
                    {
                    $sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');

                        $this->insertPlayerStatistics($sportName,$match_id);

                        //notification ocde
                        Helper::sendEmailPlayers($matchScheduleDetails, 'ultimateFrisbee');      

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
                     'match_report'   => $match_report,
                     'match_result'   => $match_result,
                     'score_added_by' => $json_score_status,'scoring_status'=>$approved]);

                if($match_status=='completed')
                {
                    $sportName = Sport::where('id',$matchScheduleDetails['sports_id'])->pluck('sports_name');

                    $this->insertPlayerStatistics($sportName,$match_id);

                    //send mail to players
                    Helper::sendEmailPlayers($matchScheduleDetails, 'ultimateFrisbee');      


                    //notification ocde
                }
            }
        else
            {
                MatchSchedule::where('id',$match_id)->update(['winner_id'=>$winner_team_id ,'looser_id'=>$looser_team_id,
                    'is_tied'=>$is_tie,
                    'has_result'     => $has_result,
                     'match_result'   => $match_result,
                     'match_report'   => $match_report,
                     'score_added_by'=>$json_score_status]);
            }
        }
        return $match_data->match_details;

    }

        public function ultimateFrisbeeStatistics($user_id)
    {
        //check already player has record or not
        $user_ultimateFrisbee_details = ultimateFrisbeeStatistic::select()->where('user_id',$user_id)->get();

        $ultimateFrisbee_details = ultimateFrisbeePlayerMatchwiseStats::selectRaw('count(match_id) as match_count')->selectRaw('sum(points_1) as points_1')->selectRaw('sum(points_2) as points_2')->selectRaw('sum(points_3) as points_3')->selectRaw('sum(total_points) as total_points')->selectRaw('sum(fouls) as fouls')->where('user_id',$user_id)->groupBy('user_id')->get();



        $points_1 = (!empty($ultimateFrisbee_details[0]['points_1']))?$ultimateFrisbee_details[0]['points_1']:0;
        $points_2 = (!empty($ultimateFrisbee_details[0]['points_2']))?$ultimateFrisbee_details[0]['points_2']:0;
        $points_3 = (!empty($ultimateFrisbee_details[0]['points_3']))?$ultimateFrisbee_details[0]['points_3']:0;
        $fouls = (!empty($ultimateFrisbee_details[0]['fouls']))?$ultimateFrisbee_details[0]['fouls']:0;
        $total_points = (!empty($ultimateFrisbee_details[0]['total_points']))?$ultimateFrisbee_details[0]['total_points']:0;

        if(count($user_ultimateFrisbee_details)>0)
        {
            $match_count = (!empty($ultimateFrisbee_details[0]['match_count']))?$ultimateFrisbee_details[0]['match_count']:0;

            ultimateFrisbeeStatistic::where('user_id',$user_id)
                ->update([  'matches'=>$match_count,
                            'points_1'=>$points_1,
                            'points_2'=>$points_2,
                            'points_3'=>$points_3,
                            'fouls'=>$fouls,
                            'total_points'=>$total_points
                         ]);
        }else
        {
            $ultimateFrisbee_statistic = new ultimateFrisbeeStatistic();
            $ultimateFrisbee_statistic->user_id = $user_id;
            $ultimateFrisbee_statistic->matches = 1;
            $ultimateFrisbee_statistic->{'points_1'} = $points_1;
            $ultimateFrisbee_statistic->{'points_2'} = $points_2;
            $ultimateFrisbee_statistic->{'points_3'} = $points_3;
            $ultimateFrisbee_statistic->fouls = $fouls;
            $ultimateFrisbee_statistic->total_points = $total_points;
            $ultimateFrisbee_statistic->save();
        }
    }

        public function ultimateFrisbeeSwapPlayers(){
        $request=Request::all();
        $match_id=$request['match_id'];
        $team_id=$request['team_id'];
        $time_substituted=$request['time_substituted'];
        $ultimateFrisbee_model=ultimateFrisbeePlayerMatchwiseStats::whereMatchId($match_id)->whereTeamId($team_id)->get();

        foreach ($ultimateFrisbee_model as $sm ){
            $sm_id=$sm->id;
            $sm_status=$sm->playing_status;

            if(isset($request["substitute_a_".$sm_id]) && ($request["substitute_a_".$sm_id]=='on')){
                if($sm_status=='P'){
                    $sm->playing_status='S';
                }
                else $sm->playing_status='P';

                $sm->has_substituted=1;
                $sm->time_substituted=$time_substituted;
                $sm->save();
            }

        }
        return $ultimateFrisbee_model;

    }

     public function ultimateFrisbeeSaveRecord(){
            $request=Request::all();
            $match_id=$request['match_id'];
        
     
        $team_a_id=$request['team_a_id'];
        $team_b_id=$request['team_b_id'];
        $i=$request['index'];
        $match_data=matchSchedule::find($match_id);
        $match_details=json_decode($match_data['match_details']);
        $max_fouls=$match_details->preferences->max_fouls;
        $ultimateFrisbee_players=ultimateFrisbeePlayerMatchwiseStats::whereMatchId($match_id)->first();


                $quarter=$request['quarter_'.$i];
                $team_id=$request['team_'.$i];
                $player_stat_id=$request['player_'.$i];
                $user_id=$request['user_'.$i];
                $record_type=$request['record_type_'.$i];
               // $time=$request['time_'.$i];
                $player_name=$request['player_name_'.$i];
                $team_type=$request['team_type_'.$i];

       $ultimateFrisbee_model= ultimateFrisbeePlayerMatchwiseStats::find($player_stat_id);
       $team_id=$ultimateFrisbee_model->team_id;

                

                switch ($record_type) {
                    case 'points_1':

          $ultimateFrisbee_model->points_1++;
          $ultimateFrisbee_model->{$quarter}++;
          
          $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->points_1++;
          $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->{$quarter}->points_1++;
          $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->{$quarter}->total_points=$ultimateFrisbee_model->{$quarter};
          
          $ultimateFrisbee_model->total_points=$this->getTotal($ultimateFrisbee_model);

      $match_details->{$team_id}->total_points++;
      $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->total_points=$ultimateFrisbee_model->total_points;

                        break;

                    case 'points_2':
           $ultimateFrisbee_model->points_2++;
           $ultimateFrisbee_model->{$quarter}+=2;

    
       $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->points_2++;
       $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->{$quarter}->points_2++;
       $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->{$quarter}->total_points=$ultimateFrisbee_model->{$quarter};

          $match_details->{$team_id}->total_points+=2;
          $ultimateFrisbee_model->total_points=$this->getTotal($ultimateFrisbee_model);
      $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->total_points=$ultimateFrisbee_model->total_points;


                    break;

                    case 'points_3':
           $ultimateFrisbee_model->points_3++;
           $ultimateFrisbee_model->{$quarter}+=3;
          $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->total_points+=3;
            $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->points_3++;;
          $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->{$quarter}->points=$ultimateFrisbee_model->points_3;

               $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->{$quarter}->total_points=$ultimateFrisbee_model->{$quarter};
               
          $match_details->{$team_id}->total_points+=3;
          $ultimateFrisbee_model->total_points=$this->getTotal($ultimateFrisbee_model);

      $match_details->{$team_id}->players->{'player_'.$ultimateFrisbee_model->user_id}->total_points=$ultimateFrisbee_model->total_points;

                    break;

                    case 'fouls':
               if($ultimateFrisbee_model->fouls>=$max_fouls){
                   $match_details->{$team_id}->players->{'player_'.$user_id}->dismissed=1;
                   $ultimateFrisbee_model->playing_status='S';
               }

          $ultimateFrisbee_model->fouls++;
          $match_details->{$team_id}->players->{'player_'.$user_id}->fouls ++; 
          $match_details->{$team_id}->players->{'player_'.$user_id}->{$quarter}->fouls++;
          $match_details->{$team_id}->fouls++;
        
                    break;
                    
                    default:
                        # code...
                        break;
                }

              $ultimateFrisbee_model->save();

        //$this->updateultimateFrisbeeScore($user_id,$match_id,$team_id,$player_name,$points_1, $points_2, $points_3, $total_points, $fouls);
        $this->ultimateFrisbeeStatistics($user_id);          


        $match_data->match_details=json_encode($match_details);
        $match_data->save();
            return $match_data->match_details;

    }

    public function updateultimateFrisbeeScore($user_id,$match_id,$team_id,$player_name,$points_1,$points_2,$points_3, $total_points, $fouls)
    {
        $player_stat=ultimateFrisbeePlayerMatchwiseStats::where('user_id',$user_id)->where('match_id',$match_id)->where('team_id',$team_id)->update(['user_id'=>$user_id,
            'points_1'=>$points_1,
            'points_2'=>$points_2,
            'points_3'=>$points_3,
            'total_points'=>$total_points,
            'fouls'=>$fouls
            ]);
        //ultimateFrisbeeStatistic::where('user_id',$user_id)->update(['yellow_cards'=>$yellow_card_count,'red_cards'=>$red_card_count,'goals_scored'=>$goal_count]);
    }
    //

    public function getTotal($player_model){
        $total=0; 
        for($i=1; $i<=6; $i++){
                $total+=$player_model->{'quarter_'.$i};
        }
        return $total;
    }



 }