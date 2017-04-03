<?php

namespace App\Http\Controllers\User\Esports;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Helpers\Helper;

use App\Model\GameUsername;
use App\Model\SmiteMatchStats;
use App\Model\Sport;
use App\User;
use App\Model\Photo;
use App\Model\Team;
use App\Model\VolleyballPlayerMatchwiseStats;
use App\Model\volleyballScore;

use Session;

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
            return view('scorecards.smitescorecard',array(
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

}
