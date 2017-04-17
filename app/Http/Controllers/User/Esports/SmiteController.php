<?php

namespace App\Http\Controllers\User\Esports;

use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;

use Auth;
use Session;

use App\Model\GameUsername;
use App\Model\SmiteMatchStats;
use App\Model\Sport;
use App\Model\Photo;
use App\Model\Team;
use App\Model\Tournaments;
use App\Model\MatchSchedule;
use App\User;

class SmiteController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);

        $this->validate($request, [
            'username' => 'required|max:30',
        ]);

        $user_id = \Auth::user();
        $sport = Sport::where('sports_name', strtolower('smite'))->first();
        $username = $request->input('username');

        if($game_username = GameUsername::where('user_id', $user_id->id)->where('sport_id', $sport->id)->exists())
            $game_username = GameUsername::where('user_id', $user_id->id)->where('sport_id', $sport->id)->first();
        else
            $game_username = new GameUsername;

        $game_username->user_id = $user_id->id;
        $game_username->sport_id = $sport->id;
        $game_username->username = $username;

        $game_username->save();

        return response()->json(['status' => 'true']);
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

    public function smiteScoreCard($match_data,$sportsDetails=[],$tournamentDetails=[],$is_from_view=0)
    {
        $loginUserId = '';
        $loginUserRole = '';

        if(isset(\Auth::user()->id))
            $loginUserId = \Auth::user()->id;

        if(isset(\Auth::user()->role))
            $loginUserRole = \Auth::user()->role;

        // Team data -> player ids
        $team_a_players = array();
        $team_b_players = array();
        $team_a_id = $match_data[0]['a_id'];
        $team_b_id = $match_data[0]['b_id'];
        $team_a_playerids = [];
        $team_b_playerids = [];

        if($match_data[0]['schedule_type'] == 'team')
        {
            $team_a_id_explodes = explode(',', $match_data[0]['player_a_ids']);
            $team_b_id_explodes = explode(',', $match_data[0]['player_b_ids']);

            foreach($team_a_id_explodes as $id)
            {
                if(empty(trim($id)))
                    continue;
                array_push($team_a_playerids,$id);
            }

            foreach($team_b_id_explodes as $id)
            {
                if(empty(trim($id)))
                    continue;
                array_push($team_b_playerids,$id);
            }

            $team_a_name = Team::where('id',$team_a_id)->pluck('name');
            $team_b_name = Team::where('id',$team_b_id)->pluck('name');


            $team_a_city =  Team::where('id',$team_a_id)->pluck('location');
            $team_b_city =  Team::where('id',$team_a_id)->pluck('location');
        }
        else if ($match_data[0]['schedule_type'] == 'individual' || $match_data[0]['schedule_type'] == 'player')
        {
             array_push($team_a_playerids,$team_a_id);
             array_push($team_b_playerids,$team_b_id);

            // Get user name if it's single match
            $team_a_name = User::where('id',$team_a_id)->pluck('name');
            $team_b_name = User::where('id',$team_b_id)->pluck('name');

            $team_a_city =  User::where('id',$team_a_id)->pluck('location');
            $team_b_city =  User::where('id',$team_b_id)->pluck('location');
        }

        // Get match id
        $match_id = $match_data[0]['id'];

        // Get Smite scores for team a
        $smite_match_stats = SmiteMatchStats::select('user_id','final_level as Level','kills as Kills','deaths as Deaths','assists as Assists','gold_earned as GoldEarned','gpm as GoldPerMinute','magical_damage_done as MagicalDamage','physical_damage_done as PhysicalDamage')
            ->where('match_id',$match_data[0]['id'])->get()->toArray();

        // Get player names
        $a_team_players = User::select()->whereIn('id',$team_a_playerids)->get();
        $b_team_players = User::select()->whereIn('id',$team_b_playerids)->get();

        if(!empty($a_team_players))
            $team_a_players = $a_team_players->toArray();
        if(!empty($b_team_players))
            $team_b_players = $b_team_players->toArray();
        $team_a = array();
        $team_b = array();

        // Get team a players
        foreach($team_a_players as $team_a_player)
        {
            $team_a[$team_a_player['id']] = $team_a_player['name'];
        }

        // Get team b players
        foreach($team_b_players as $team_b_player)
        {
            $team_b[$team_b_player['id']] = $team_b_player['name'];
        }

        $team_a_logo = Photo::select()->where('imageable_id', $match_data[0]['a_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo
        $team_b_logo = Photo::select()->where('imageable_id', $match_data[0]['b_id'])->where('imageable_type',config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first();//get user logo

        // Bye match
        if($match_data[0]['b_id']=='' && $match_data[0]['match_status']=='completed')
        {
            $sport_class = 'bascketball_scorecard ss_bg';
            $upload_folder = 'teams';
            return view('scorecards.byematchview',array('team_a_name'=>$team_a_name,'team_a_logo'=>$team_a_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'sport_class'=>$sport_class));
        }

        $team_a_count = count($team_a);
        $team_b_count = count($team_b);

        // Get match details fall of wickets
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

        // Score status
        $score_status_array = json_decode($match_data[0]['score_added_by'],true);
        $rej_note_str = '';
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

        // Is valid user for score card enter or edit
        $isValidUser = 0;
        $isApproveRejectExist = 0;
        $isForApprovalExist = 0;

        if(isset(\Auth::user()->id)){
            $isValidUser = Helper::isValidUserForScoreEnter($match_data);
            // Is approval process exist
            $isApproveRejectExist = Helper::isApprovalExist($match_data);
            $isForApprovalExist = Helper::isApprovalExist($match_data,$isForApproval='yes');
        }

        $form_id = 'bascketball';
        $isAdminEdit = 0;

        if(Session::has('is_allowed_to_edit_match'))
        {
            $session_data = Session::get('is_allowed_to_edit_match');

            if($isValidUser && ($session_data[0]['id']==$match_data[0]['id'])){
                $isAdminEdit=1;
            }
        }

        if(($is_from_view==1 || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser) && !$isAdminEdit)//volleyball score view only
        {
            /* Get player of the match if it exists */
            $player_of_the_match=$match_data[0]['player_of_the_match'];
            if($player_of_the_match_model=User::find($player_of_the_match))
                $player_of_the_match=$player_of_the_match_model;
            else $player_of_the_match=NULL;

            return view('scorecards.smitescorecardview',array(
                'tournamentDetails' => $tournamentDetails,
                'sportsDetails'=> $sportsDetails,
                'team_a'=>[''=>'Select Player']+$team_a,
                'team_b'=>[''=>'Select Player']+$team_b,
                'match_data'=>$match_data,
                'team_a_name'=>$team_a_name,
                'team_b_name'=>$team_b_name,
                'team_a_city' => $team_a_city,
                'team_b_city' => $team_b_city,
                'team_a_players'=>$team_a_players,
                'team_b_players'=>$team_b_players,
                'team_a_count'=>$team_a_count,
                'team_b_count'=>$team_b_count,
                'team_a_logo'=>$team_a_logo,
                'team_b_logo'=>$team_b_logo,
                'score_status_array'=>$score_status_array,
                'loginUserId'=>$loginUserId,
                'rej_note_str'=>$rej_note_str,
                'loginUserRole'=>$loginUserRole,
                'isValidUser'=>$isValidUser,
                'isApproveRejectExist'=>$isApproveRejectExist,
                'isForApprovalExist'=>$isForApprovalExist,
                'action_id'=>$match_data[0]['id'],
                'form_id'=>$form_id,
                'team_a_players'=>$team_a_players,
                'team_b_players'=>$team_b_players,
                'player_of_the_match'=>$player_of_the_match,
                'smite_match_stats' => $smite_match_stats
            ));
        }
        else //volleyball score view and edit
        {
            return view('scorecards.smitescorecardview',array(
                'tournamentDetails' => $tournamentDetails,
                'sportsDetails'=> $sportsDetails,
                'team_a'=>[''=>'Select Player']+$team_a,
                'team_b'=>[''=>'Select Player']+$team_b,
                'match_data'=>$match_data,
                'team_a_name'=>$team_a_name,
                'team_b_name'=>$team_b_name,
                'team_a_city' => $team_a_city,
                'team_b_city' => $team_b_city,
                'team_a_players'=>$team_a_players,
                'team_b_players'=>$team_b_players,
                'team_a_count'=>$team_a_count,
                'team_b_count'=>$team_b_count,
                'team_a_logo'=>$team_a_logo,
                'team_b_logo'=>$team_b_logo,
                'score_status_array'=>$score_status_array,
                'loginUserId'=>$loginUserId,
                'rej_note_str'=>$rej_note_str,
                'loginUserRole'=>$loginUserRole,
                'isValidUser'=>$isValidUser,
                'isApproveRejectExist'=>$isApproveRejectExist,
                'isForApprovalExist'=>$isForApprovalExist,
                'action_id'=>$match_data[0]['id'],
                'form_id'=>$form_id,
                'team_a_players'=>$team_a_players,
                'team_b_players'=>$team_b_players,
                'smite_match_stats' => $smite_match_stats
            ));
        }

    }

    public function confirmSquad()
    {
        $request = Request::all();
        $match_id = $request['match_id'];

        $match_model= MatchSchedule::find($match_id);
        $match_model->hasSetupSquad = 1;
        $match_model->player_a_ids = implode(',', $request['team_a']['playing']);
        $match_model->player_b_ids = implode(',', $request['team_b']['playing']);
        $match_model->save();
    }

    public function manualScoring()
    {
        $request = Request::all();

        $match = MatchSchedule::find($request['match_id']);

        $team_a = $match->player_a_ids;
        $team_b = $match->player_b_ids;

        $team_a_array = explode(',', $team_a);
        $team_b_array = explode(',', $team_b);

        $players = array_merge($team_a_array, $team_b_array);

        foreach($players as $player)
        {
            if(trim($player) == '')
                continue;

            if(!is_numeric($request['Level_'.$player]))
                continue;

            SmiteMatchStats::updateOrCreate(
                ['match_id' => $match->id, 'user_id' => $player],
                [
                    'smite_match' => $match->smite_match,
                    'final_level' => $request['Level_'.$player],
                    'kills' => $request['Kills_'.$player],
                    'deaths' => $request['Deaths_'.$player],
                    'assists' => $request['Assists_'.$player],
                    'gold_earned' => $request['GoldEarned_'.$player],
                    'gpm' => $request['GoldPerMinute_'.$player],
                    'magical_damage_done' => $request['MagicalDamage_'.$player],
                    'physical_damage_done' => $request['PhysicalDamage_'.$player]
                ]);
        }
    }

    public function endMatch()
    {
        $request = Request::all();
        $match_id = $request['match_id'];;
        $match_data = MatchSchedule::find($match_id);

        $match_result=$request['match_result'];
        $match_report=$request['match_report'];
        $winner_team_id = !empty(Request::get('winner_team_id')) ? Request::get('winner_team_id') : NULL; //winner_id
        $player_of_the_match = isset($request['player_of_the_match']) ? $request['player_of_the_match'] : NULL;

        $match_data->player_of_the_match = $player_of_the_match;

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

        if(count($matchScheduleDetails))
        {
            $looser_team_id = NULL;
            $match_status = 'scheduled';
            $approved = '';

            /* Set score */
            if($winner_team_id == $matchScheduleDetails['a_id'])
            {
                $score_a = 1;
                $score_b = 0;
            }
            else
            {
                $score_a = 0;
                $score_b = 1;
            }

            if($is_tie == 0 || $is_washout == 0)
            {
                if(isset($winner_team_id)) {

                    if($winner_team_id == $matchScheduleDetails['a_id'])
                    {
                        $looser_team_id = $matchScheduleDetails['b_id'];
                    }
                    else
                    {
                        $looser_team_id = $matchScheduleDetails['a_id'];
                    }

                    $match_status='completed';
                    $approved = 'approved';
                }
            }

            $this->deny_match_edit_by_admin();

            if(!empty($matchScheduleDetails['tournament_id']))
            {

                $tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
                if (($is_tie == 1 || $match_result == "washout") && !empty($matchScheduleDetails['tournament_group_id']))
                {
                    $match_status = 'completed';
                }

                if(Helper::isTournamentOwner($tournamentDetails['manager_id'],$tournamentDetails['tournament_parent_id']))
                {
                    MatchSchedule::where('id',$match_id)->update(['match_status'=>$match_status,
                        'winner_id' => $winner_team_id ,
                        'looser_id' => $looser_team_id,
                        'has_result' => $has_result,
                        'match_report' => $match_report,
                        'match_result' => $match_result,
                        'is_tied' => $is_tie,
                        'match_report' => $match_report,
                        'a_score' => $score_a,
                        'b_score' => $score_b,
                        'score_added_by' => $json_score_status]);

                    if(!empty($matchScheduleDetails['tournament_round_number']))
                    {
                        $matchScheduleDetails->updateBracketDetails();
                    }

                    if($match_status=='completed')
                    {
                        Helper::sendEmailPlayers($matchScheduleDetails, 'volleyball');
                    }
                }

            }
            else if(Auth::user()->role == 'admin')
            {
                if ($is_tie == 1 || $match_result == "washout")
                {
                    $match_status = 'completed';
                    $approved = 'approved';
                }

                MatchSchedule::where('id',$match_id)->update(['match_status'=>$match_status,
                    'winner_id' => $winner_team_id,
                    'looser_id' => $looser_team_id,
                    'is_tied' => $is_tie,
                    'match_report' => $match_report,
                    'has_result' => $has_result,
                    'match_result' => $match_result,
                    'a_score' => $score_a,
                    'b_score' => $score_b,
                    'score_added_by' => $json_score_status,
                    'scoring_status' => $approved]);

                if($match_status=='completed')
                {
                    //send mail to players
                    Helper::sendEmailPlayers($matchScheduleDetails, 'volleyball');
                }
            }
            else
            {
                MatchSchedule::where('id',$match_id)->update([
                    'winner_id' => $winner_team_id ,
                    'looser_id' => $looser_team_id,
                    'is_tied' => $is_tie,
                    'match_report' => $match_report,
                    'has_result' => $has_result,
                    'match_result' => $match_result,
                    'a_score' => $score_a,
                    'b_score' => $score_b,
                    'score_added_by' => $json_score_status
                ]);
            }
        }
        return $match_data->match_details;
    }

    public function deny_match_edit_by_admin(){
        Session::remove('is_allowed_to_edit_match');
    }
}
