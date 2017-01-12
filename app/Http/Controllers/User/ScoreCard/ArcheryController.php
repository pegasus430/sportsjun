<?php

namespace App\Http\Controllers\User\ScoreCard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\Tournaments;
use App\Http\Controllers\User\ScoreCardController as parentScoreCardController;
use App\Http\Controllers\User\ScheduleController;
use App\Model\MatchSchedule;
use App\Model\UserStatistic;
use App\Model\State;
use App\Model\City;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\Sport;
use App\Model\SquashPlayerMatchwiseStats;
use App\Model\SquashPlayerMatchScore;
use App\Model\SquashPlayerRubberScore;
use App\Model\SquashStatistic;
use App\Model\MatchScheduleRubber;
use App\Model\Photo;
use App\User;
use DB;
use Carbon\Carbon;
use Response;
use Auth;
use App\Helpers\Helper;
use App\Helpers\ScoreCard;
use DateTime;
use App\Helpers\AllRequests;
use Session;

use App\Model\ArcheryRound;
use App\Model\ArcheryPlayerStats;
use App\Model\ArcheryArrowStats;
use App\Model\ArcheryTeamStats;

class ArcheryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        

    public function archeryScorecard($match_data,$sportsDetails,$tournamentDetails,$is_from_view=0){
          
            


        $score_a_array=array();
        $score_b_array=array();

        $loginUserId = '';
        $loginUserRole = '';

        $players_a = $players = $players_b = '';

        $match_obj = MatchSchedule::find($match_data[0]['id']);

        if(isset(Auth::user()->id))
            $loginUserId = Auth::user()->id;

        if(isset(Auth::user()->role))
            $loginUserRole = Auth::user()->role;

        $player_a_ids = $match_data[0]['player_a_ids'];
        $player_b_ids = $match_data[0]['player_b_ids'];

        $decoded_match_details = array();
        if($match_data[0]['match_details']!='')
        {
            $decoded_match_details = json_decode($match_data[0]['match_details'],true);
        }

        $a_players = array();

        $team_a_playerids = (!empty($decoded_match_details[$match_data[0]['a_id']]) && !($match_data[0]['scoring_status']=='' || $match_data[0]['scoring_status']=='rejected'))?$decoded_match_details[$match_data[0]['a_id']]:explode(',',$player_a_ids);

        $a_team_players = User::select('id','name')->whereIn('id',$team_a_playerids)->get();

        if (count($a_team_players)>0)
            $a_players = $a_team_players;

        $b_players = array();

        $team_b_playerids = (!empty($decoded_match_details[$match_data[0]['b_id']]) && !($match_data[0]['scoring_status']=='' || $match_data[0]['scoring_status']=='rejected'))?$decoded_match_details[$match_data[0]['b_id']]:explode(',',$player_b_ids);


        $b_team_players = User::select('id','name')->whereIn('id',$team_b_playerids)->get();

        if (count($b_team_players)>0)
            $b_players = $b_team_players;

        $team_a_player_images = array();
        $team_b_player_images = array();

        //team a player images
        if(count($a_players)>0)
        {
            foreach($a_players as $a_player)
            {
                $team_a_player_images[$a_player['id']]=Photo::select()->where('imageable_id', $a_player['id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            }
        }

        //team b player images
        if(count($b_players)>0)
        {
            foreach($b_players as $b_player)
            {
                $team_b_player_images[$b_player['id']]=Photo::select()->where('imageable_id', $b_player['id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            }
        }
        if($match_data[0]['schedule_type'] == 'player')//&& $match_data[0]['schedule_type'] == 'player'
        {


           $user_a_name = User::where('id',$match_data[0]['a_id'])->pluck('name');
          $user_b_name = User::where('id',$match_data[0]['b_id'])->pluck('name');
           $user_a_logo = Photo::select()->where('imageable_id', $match_data[0]['a_id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
           $user_b_logo = Photo::select()->where('imageable_id', $match_data[0]['b_id'])->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            $upload_folder = 'user_profile';
            $is_singles = 'yes';



           // $team_a_city = Helper::getUserCity($match_data[0]['a_id']); 
           // $team_b_city = Helper::getUserCity($match_data[0]['b_id']);

            $team_a_city ='';
            $team_b_city ='';
        }else
        {
            $user_a_name = Team::where('id',$match_data[0]['a_id'])->pluck('name');//team details
            $user_b_name = Team::where('id',$match_data[0]['b_id'])->pluck('name');//team details
            $user_a_logo = Photo::select()->where('imageable_id', $match_data[0]['a_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            $user_b_logo = Photo::select()->where('imageable_id', $match_data[0]['b_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
            $upload_folder = 'teams';
            $is_singles = 'no';            


            $team_a_city = Helper::getTeamCity($match_data[0]['a_id']);
            $team_b_city = Helper::getTeamCity($match_data[0]['b_id']);
        }


        //bye match
        if(($match_data[0]['b_id']=='' || $match_data[0]['a_id']=='') && ($match_data[0]['match_status']=='completed' && $match_data[0]['sports_id']!=18))
        {
            $sport_class = 'archery_scorcard';
            return view('scorecards.byematchview',array('team_a_name'=>$user_a_name,'team_a_logo'=>$user_a_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'sport_class'=>$sport_class));
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

   $isAdminEdit = 0;
        if(Session::has('is_allowed_to_edit_match')){
            $session_data = Session::get('is_allowed_to_edit_match');

            if($isValidUser && ($session_data[0]['id']==$match_data[0]['id'])){
                $isAdminEdit=1;
            }
        }

        // Load Players

        $player_or_team_ids = explode(',',$match_obj->player_or_team_ids);
        $player_or_team_list = [];
        $players_list        =[];

        foreach($player_or_team_ids as $pt_id){
             if(!empty($pt_id)){
                if($match_obj->schedule_type=='player'){               
                    $player_or_team_list[$pt_id] = User::find($pt_id);
                }
                else{
                    $player_or_team_list[$pt_id] = Team::find($pt_id);
                    $players_list[$pt_id]        = $match_obj->archery_players($pt_id);
                }
            }
            else{
               
            }
            

        }

         $players = $match_obj->archery_players();         


        $players_ordered = $match_obj->archery_players(null,'total');
        $winner_namee ='';

        if($match_obj->match_status=='completed' ){
            if($match_obj->schedule_type=='player'){
                $match_obj->winner_id = $players_ordered[0]['user_id'];
                $winner_namee = User::find($players_ordered[0]['user_id'])->name;
            }
            else{
                $match_obj->winner_id = $players_ordered[0]['team_id'];
                $winner_namee = Team::find($players_ordered[0]['team_id'])->name;
            }
            $match_data[0]['winner_id'] = $match_obj->winner_id;
            $match_obj->save();

            $players = $players_ordered;
        }


        if(($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser) && !$isAdminEdit)
        {
            
                return view('scorecards.archery.archeryscorecardview',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'match_obj'=>$match_obj,'players'=>$players,'players_a'=>$players_a,'players_b'=>$players_b,'is_from_view'=>$is_from_view,'players_ordered'=>$players_ordered, 'player_or_team_list'=>$player_or_team_list,'player_or_team_ids'=>$player_or_team_ids,'players_list'=>$players_list, 'winner_namee'=>$winner_namee));
            

        }
        else //to view and edit archery/table archery score card
        {
          
                //for form submit pass id from controller
                $form_id = 'archery';
                return view('scorecards.archery.archeryscorecard',array('tournamentDetails' => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'user_a_name'=>$user_a_name,'user_b_name'=>$user_b_name,'user_a_logo'=>$user_a_logo,'user_b_logo'=>$user_b_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'is_singles'=>$is_singles,'score_a_array'=>$score_a_array,'score_b_array'=>$score_b_array,'b_players'=>$b_players,'a_players'=>$a_players,'team_a_player_images'=>$team_a_player_images,'team_b_player_images'=>$team_b_player_images,'decoded_match_details'=>$decoded_match_details,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city,'form_id'=>$form_id,'match_obj'=>$match_obj,'players'=>$players,'players_a'=>$players_a,'players_b'=>$players_b,'is_from_view'=>$is_from_view,'players_ordered'=>$players_ordered, 'player_or_team_list'=>$player_or_team_list,'player_or_team_ids'=>$player_or_team_ids,'players_list'=>$players_list, 'winner_namee'=>$winner_namee));
           
        }
    }


    public function add_round(Request $request){

        $match_obj = MatchSchedule::find($request->match_id);
        $round = new ArcheryRound;
        $round->number_of_arrows = $request->number_of_arrows;
        $round->distance         = $request->distance;
        $round->match_id         = $request->match_id;
        $round->total            = $request->number_of_arrows * 10;
        $round->round_number     = $match_obj->archery_rounds->count() + 1; 
        $round->save();
        $match_obj = MatchSchedule::find($request->match_id);

        return view('scorecards.archery.rounds', compact('match_obj'));
    }


    public function insert_players(Request $request){
        $match_model = MatchSchedule::find($request->match_id);
        $number_of_players = $request->number_of_players;

        $player_or_team_ids = explode(',',$match_model->player_or_team_ids);

        for($i=1; $i<= $number_of_players; $i++){

            foreach($player_or_team_ids as $pt_id){
                if($pt_id){
                    $this->insert_players_in_db($match_model->tournament_id,$request->match_id,$pt_id,$request->{$pt_id.'_player_'.$i},user::find($request->{$pt_id.'_player_'.$i})->name,team::find($pt_id)->name);
                }
            }
      
            
        }   

        return 'saved';    
    }

    public function insert_players_in_db($tournament_id,$match_id,$team_id,$user_id,$user_name='',$team_name=''){
      
        $check = ArcheryPlayerStats::where(['match_id'=>$match_id,'user_id'=>$user_id])->first();

        if(!$check){
            $aps    = new ArcheryPlayerStats;
            $aps->tournament_id = $tournament_id;
            $aps->match_id      = $match_id;
            $aps->team_id       = $team_id;
            $aps->user_id       = $user_id;
            $aps->player_name   = $user_name;
            $aps->team_name     = $team_name;
            $aps->save();
        }

        return $check;
    }

      public function insert_teams_in_db($tournament_id,$match_id,$team_id,$team_name=''){
      
        $check = ArcheryTeamStats::where(['match_id'=>$match_id,'team_id'=>$team_id])->first();

        if(!$check){
            $aps    = new ArcheryTeamStats;
            $aps->tournament_id = $tournament_id;
            $aps->match_id      = $match_id;
            $aps->team_id       = $team_id;        
            $aps->team_name     = $team_name;
            $aps->save();
        }
    }

    public function start_scoring(Request $request){
        $match_model = MatchSchedule::find($request->match_id);

        $match_details = [
                             'a_id' =>[

                                ],
                             'b_id' =>[

                             ],
                             'schedule_type'=>$match_model->schedule_type,
                             'match_id'     =>$match_model->match_id,

        ];


        $match_model->match_details = json_encode($match_details);
        $match_model->hasSetupSquad=1;
        $match_model->save();
        return 'ok';
    }

    public function get_arrow_stats($match_id,$user_id,$round_id,$round_number,$team_id=null){

    $check = ArcheryArrowStats::where(['match_id'=>$match_id,'user_id'=>$user_id,'round_id'=>$round_id, 'team_id'=>$team_id])->first();
       
  
    if($check) return $check;

    $match_model = MatchSchedule::find($match_id);

        $ars = new ArcheryArrowStats;
        $ars->user_id = $user_id;
        $ars->match_id = $match_id;
        $ars->round_id = $round_id;
        $ars->round_number = $round_number;
        $ars->tournament_id = $match_model->tournament_id;
        $ars->team_id = $team_id;

        $ars->save();

        return $ars;
    }

    public function arrow_scoring(Request $request){
        $match_model = MatchSchedule::find($request->match_id);
        $value = $request->value;

        $player_stats = ArcheryPlayerStats::find($request->player_id);

        $arrow_stats = $this->get_arrow_stats($request->match_id,$request->user_id,$request->round_id,$request->round_number, $player_stats->team_id);
        $arrow_stats->{'arrow_'.$request->arrow_number} = $request->value;
        $arrow_stats->save();

        $player_stats->{'round_'.$request->round_number} = $this->arrow_sum($arrow_stats);

        $player_stats->total = $this->round_sum($player_stats);

        $player_stats->save();


        if($match_model->schedule_type=='team'){
            $team_stats = ArcheryTeamStats::find($request->team_player_id);

            $team_stats->{'round_'.$request->round_number} = ArcheryPlayerStats::where(['team_table_id'=>$team_stats->id,'round_number'=>$request->round_number])->sum('total');
            $team_stats->total = ArcheryPlayerStats::where(['team_table_id'=>$team_stats->id])->sum('total');
            $team_stats->save();

            return $team_stats;
        }

        return $player_stats;

    }

    public function round_sum($player_stats){
        $total=0;
        for($i=1;$i<=10;$i++){
            $total+=$player_stats->{'round_'.$i};
        }   

        return $total;
    }

   

    public function arrow_sum($arrow_stats){
        $total=0;
        for($i=1;$i<=10;$i++){
            $total+=$arrow_stats->{'arrow_'.$i};
        }   

        return $total;
    }

    public function load_arrow(Request $request){

        $match_obj = MatchSchedule::find($request->match_id);
        $player    = ArcheryPlayerStats::find($request->player_id);
        $round     = ArcheryRound::find($request->round_id);

        $isValidUser=1;

     //   if(isset(Auth::user()->id)) $isValidUser = Helper::isValidUserForScoreEnter($match_obj);
        return view('scorecards.archery.round_scoring', compact('match_obj','player','round','isValidUser'));
    }

     public function load_arrow_public(Request $request){

        $match_obj = MatchSchedule::find($request->match_id);
        $player    = ArcheryPlayerStats::find($request->player_id);
        $round     = ArcheryRound::find($request->round_id);

        $isValidUser=0;   
        return view('scorecards.archery.round_scoring', compact('match_obj','player','round','isValidUser'));
    }

    public function end_match(Request $request){
          $loginUserId = Auth::user()->id;
        
        $tournament_id = !empty($request->tournament_id)?$request->tournament_id:NULL;
        $match_id = !empty($request->match_id)?$request->match_id:NULL;
        $match_data=MatchSchedule::find($match_id);
        $match_result=!empty($request->match_result)?$request->match_result:NULL;
        $match_type = !empty($request->match_type)?$request->match_type:NULL;
        $player_ids_a = !empty($request->player_ids_a)?$request->player_ids_a:NULL;
        $player_ids_b= !empty($request->player_ids_b)?$request->player_ids_b:NULL;
        $is_singles = !empty($request->is_singles)?$request->is_singles:NULL;
        $is_winner_inserted = !empty($request->is_winner_inserted)?$request->is_winner_inserted:NULL;
        $winner_team_id = !empty($request->winner_team_id)?$request->winner_team_id:$is_winner_inserted;//winner_id

        $team_a_players = !empty($request->a_player_ids)?$request->a_player_ids:array();//player id if match type is singles
        $team_b_players = !empty($request->b_player_ids)?$request->b_player_ids:array();//player id if match type is singles

        $schedule_type = !empty($request->schedule_type)?$request->schedule_type:NULL;

       
        $match_model = MatchSchedule::find($request->match_id);

        $decode_scorecard_data = json_decode($match_model,true);

        $modified_users = !empty($decode_scorecard_data['modified_users'])?$decode_scorecard_data['modified_users']:'';

        $modified_users = $modified_users.','.$loginUserId;//scorecard changed users

        $added_by = !empty($decode_scorecard_data['added_by'])?$decode_scorecard_data['added_by']:$loginUserId;

        //score card approval process
        $score_status = array('added_by'=>$added_by,'active_user'=>$loginUserId,'modified_users'=>$modified_users,'rejected_note'=>'');

        $json_score_status = json_encode($score_status);

        $match_model = MatchSchedule::find($request->match_id);
        $match_model->match_status='completed';
        $match_model->score_added_by=$json_score_status;
        $match_model->save();

        return 'ok'; 

       
    }

    public function select_team_player(Request $request){

      //  return $request->all();

        $check = ArcheryPlayerStats::where(['round_number'=>$request->round_number,
                                            'round_id'    =>$request->round_id,
                                            'team_id'     =>$request->team_id,
                                            'team_table_id'=>$request->player_id,
                                            'match_id'    => $request->match_id,
                                            'tournament_id' => $request->tournament_id])->first();

        if($check){
             $p = $check;
             $p->user_id      = $request->user_id;
             $p->player_name     = User::find($p->user_id)->name;
             $p->save();
             return $p;
        }

        $p = new ArcheryPlayerStats;
        $p->user_id      = $request->user_id;
        $p->round_number = $request->round_number;
        $p->round_id     = $request->round_id;
        $p->team_id      = $request->team_id;
        $p->team_table_id = $request->player_id;
        $p->player_name     = User::find($p->user_id)->name;
        $p->match_id      = $request->match_id;
        $p->tournament_id = $request->tournament_id;
        $p->save();


        return $p;
    }

    public function update_settings(Request $request){
        $match_id = $request->match_id;

        $match_model = MatchSchedule::find($match_id);

        foreach($match_model->archery_rounds as $round){
                $round->distance = $request->{'round_'.$round->id.'_distance'};
                $round->number_of_arrows = $request->{'round_'.$round->id.'_number_of_arrows'};
                $round->total = $round->distance * $round->number_of_arrows;
                $round->save();
        }

        return 'ok';
    }
}
