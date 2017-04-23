<?php

namespace App\Http\Controllers\User\ScoreCard;

use App\Helpers\Helper;
use App\Http\Controllers\User\ScoreCardController as parentScoreCardController;
use App\Model\CricketPlayerMatchwiseStats;
use App\Model\MatchSchedule;
use App\Model\Photo;
use App\Model\Team;
use App\Model\Tournaments;
use App\Model\Sport;
use App\User;
use Auth;
use Carbon\Carbon;
use Request;

//get all my requests data as object

class CricketScoreCardController extends parentScoreCardController
{

    //function to save cricket details
    public function cricketScoreCard($match_data,$sportsDetails=[],$tournamentDetails=[],$is_from_view=0)
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

        $team_a_scnd_ing_playerids = explode(',',$match_data[0]['player_a_ids']);
        $team_b_scnd_ing_playerids = explode(',',$match_data[0]['player_b_ids']);



        $score_status_array = json_decode($match_data[0]['score_added_by'],true);
        if(!empty($score_status_array['fst_ing_batting']))
        {
            if($score_status_array['fst_ing_batting']==$team_a_id)
            {
                $team_a_playerids = explode(',',$match_data[0]['player_a_ids']);
                $team_b_playerids = explode(',',$match_data[0]['player_b_ids']);
            }
            else
            {
                $team_a_playerids = explode(',',$match_data[0]['player_b_ids']);
                $team_b_playerids = explode(',',$match_data[0]['player_a_ids']);
            }


        }
        if(!empty($score_status_array['scnd_ing_batting']))
        {
            if($score_status_array['scnd_ing_batting']==$team_a_id)
            {
                $team_a_scnd_ing_playerids = explode(',',$match_data[0]['player_a_ids']);
                $team_b_scnd_ing_playerids = explode(',',$match_data[0]['player_b_ids']);
            }
            else
            {
                $team_a_scnd_ing_playerids = explode(',',$match_data[0]['player_b_ids']);
                $team_b_scnd_ing_playerids = explode(',',$match_data[0]['player_a_ids']);
            }


        }
        //get player names
        $a_team_players = User::select()->whereIn('id',$team_a_playerids)->get();
        $b_team_players = User::select()->whereIn('id',$team_b_playerids)->get();

        //get second inning player names
        $a_scng_ing_team_players = User::select()->whereIn('id',$team_a_scnd_ing_playerids)->get();
        $b_scnd_ing_team_players = User::select()->whereIn('id',$team_b_scnd_ing_playerids)->get();

        //get team names
        $team_a_name = Team::where('id',$team_a_id)->pluck('name');
        $team_b_name = Team::where('id',$team_b_id)->pluck('name');

        //get team logos
        $team_a_logo = Photo::select()->where('imageable_id', $team_a_id)->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first(); //get team a logo

        $team_b_logo = Photo::select()->where('imageable_id', $team_b_id)->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first(); //get team b logo


        //bye match
        if($match_data[0]['b_id']=='' && $match_data[0]['match_status']=='completed')
        {
            $sport_class = 'cricket_scorcard';
            $upload_folder = 'teams';
            return view('scorecards.byematchview',array('team_a_name'=>$team_a_name,'team_a_logo'=>$team_a_logo,'match_data'=>$match_data,'upload_folder'=>$upload_folder,'sport_class'=>$sport_class));
        }

        if(!empty($a_team_players))
            $team_a_players = $a_team_players->toArray();
        if(!empty($b_team_players))
            $team_b_players = $b_team_players->toArray();
        $team_a = array();
        $team_b = array();


        //second inning player names
        if(!empty($a_scng_ing_team_players))
            $team_a_scnd_ing_players = $a_scng_ing_team_players->toArray();
        if(!empty($b_scnd_ing_team_players))
            $team_b_scnd_ing_players = $b_scnd_ing_team_players->toArray();
        $team_a = array();
        $team_b = array();


        $team_a_scnd_ing = array();
        $team_b_scnd_ing = array();

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

        //get team a second inning players
        foreach($team_a_scnd_ing_players as $team_a_ing_player)
        {
            $team_a_scnd_ing[$team_a_ing_player['id']] = $team_a_ing_player['name'];
        }

        //get team b second inning players
        foreach($team_b_scnd_ing_players as $team_b_ing_player)
        {
            $team_b_scnd_ing[$team_b_ing_player['id']] = $team_b_ing_player['name'];
        }

        //out_as enum
        $enum = config('constants.ENUM.SCORE_CARD.OUT_AS');

        $fstIngFstBatId = $team_a_id;
        $fstIngsecondBatId = $team_b_id;
        $secondIngFstBatId = $team_a_id;
        $secondIngsecondBatId = $team_b_id;
        $toss_won_by = !empty($score_status_array['toss_won_by']) ? $score_status_array['toss_won_by'] : $team_a_id;

        $first_innings_team_variable['first']   = 'a';
        $first_innings_team_variable['second']  = 'b';
        $second_innings_team_variable['first']  = 'a';
        $second_innings_team_variable['second'] = 'b';

        if(!empty($score_status_array['fst_ing_batting']))
        {
            $fstIngFstBatId = $score_status_array['fst_ing_batting'];
            if($fstIngFstBatId==$team_a_id)
                $fstIngsecondBatId = $team_b_id;
            else
            {
                $first_innings_team_variable['first']  = 'b';
                $first_innings_team_variable['second'] = 'a';
                $fstIngsecondBatId = $team_a_id;
            }
        }

        if(!empty($score_status_array['scnd_ing_batting']))
        {
            $secondIngFstBatId = $score_status_array['scnd_ing_batting'];
            if($secondIngFstBatId==$team_a_id)
                $secondIngsecondBatId = $team_b_id;
            else
            {
                $second_innings_team_variable['first']  = 'b';
                $second_innings_team_variable['second'] = 'a';
                $secondIngsecondBatId = $team_a_id;
            }
        }



        //get team a details first innings
        $team_a_fst_ing_array = array();

        $team_a_fst_innings = CricketPlayerMatchwiseStats::select()
                                                         ->where('match_id',$match_data[0]['id'])
                                                         ->where('team_id',$fstIngFstBatId)
                                                         ->where('innings','first')
                                                         ->where(function($q1){
                                                             $q1->where('totalruns','>',0)->orWhere('balls_played','>',0)->orWhere('fours','>',0)->orWhere('sixes','>',0)->orwhereNotNull('out_as');
                                                         })
                                                         ->orderBy('sr_no_in_batting_team', 'asc')
                                                         ->get();

        if(count($team_a_fst_innings)>0)
        {
            $team_a_fst_ing_array = $team_a_fst_innings->toArray();
        }
        $team_a_fst_ing_bowling_array = array();


        //get team a bowling Details
        $team_a_fst_bowling = CricketPlayerMatchwiseStats::select()
                                                         ->where('match_id',$match_data[0]['id'])
                                                         ->where('team_id',$fstIngFstBatId)
                                                         ->where('innings','first')
                                                         ->where(function($q1){
                                                             $q1->where('overs_bowled','>',0)->orWhere('overs_maiden','>',0)->orWhere('wickets','>',0)->orWhere('runs_conceded','>',0)->orWhere('wides_bowl','>',0)->orWhere('noballs_bowl','>',0)->orWhere('ecomony','>',0);
                                                         })
                                                         ->orderBy('sr_no_in_bowling_team', 'asc')
                                                         ->get();

        if(count($team_a_fst_bowling)>0)
        {
            $team_a_fst_ing_bowling_array = $team_a_fst_bowling->toArray();
        }



        //get team a details first innings
        $team_b_fst_ing_array = array();

        $team_b_fst_innings = CricketPlayerMatchwiseStats::select()
                                                         ->where('match_id',$match_data[0]['id'])
                                                         ->where('team_id',$fstIngsecondBatId)
                                                         ->where('innings','first')
                                                         ->where(function($q1){
                                                             $q1->where('totalruns','>',0)->orWhere('balls_played','>',0)->orWhere('fours','>',0)->orWhere('sixes','>',0)->orwhereNotNull('out_as');
                                                         })
                                                         ->orderBy('sr_no_in_batting_team', 'asc')
                                                         ->get();

        if(count($team_b_fst_innings)>0)
        {
            $team_b_fst_ing_array = $team_b_fst_innings->toArray();
        }

        //get team b bowling Details
        $team_b_fst_ing_bowling_array = array();
        $team_b_fst_bowling = CricketPlayerMatchwiseStats::select()
                                                         ->where('match_id',$match_data[0]['id'])
                                                         ->where('team_id',$fstIngsecondBatId)
                                                         ->where('innings','first')
                                                         ->where(function($q2){
                                                             $q2->where('overs_bowled','>',0)->orWhere('overs_maiden','>',0)->orWhere('wickets','>',0)->orWhere('runs_conceded','>',0)->orWhere('wides_bowl','>',0)->orWhere('noballs_bowl','>',0)->orWhere('ecomony','>',0);
                                                         })
                                                         ->orderBy('sr_no_in_bowling_team', 'asc')
                                                         ->get();

        if(count($team_b_fst_bowling)>0)
        {
            $team_b_fst_ing_bowling_array = $team_b_fst_bowling->toArray();
        }

        //if test match second innings
        $team_a_secnd_ing_array = array();
        $team_b_secnd_ing_array = array();
        $team_a_scnd_ing_bowling_array = array();
        $team_b_scnd_ing_bowling_array = array();
        if($match_data[0]['match_type']=='test')
        {
            //get team a details second innings
            $team_a_second_innings = CricketPlayerMatchwiseStats::select()
                                                                ->where('match_id',$match_data[0]['id'])
                                                                ->where('team_id',$secondIngFstBatId)
                                                                ->where('innings','second')
                                                                ->where(function($q1){
                                                                    $q1->where('totalruns','>',0)->orWhere('balls_played','>',0)->orWhere('fours','>',0)->orWhere('sixes','>',0)->orwhereNotNull('out_as');
                                                                })
                                                                ->orderBy('sr_no_in_batting_team', 'asc')
                                                                ->get();

            if(count($team_a_second_innings)>0)
            {
                $team_a_secnd_ing_array = $team_a_second_innings->toArray();
            }

            //get team a bowling details

            $team_a_scnd_bowling = CricketPlayerMatchwiseStats::select()
                                                              ->where('match_id',$match_data[0]['id'])
                                                              ->where('team_id',$secondIngFstBatId)
                                                              ->where('innings','second')
                                                              ->where(function($q2){
                                                                  $q2->where('overs_bowled','>',0)->orWhere('overs_maiden','>',0)->orWhere('wickets','>',0)->orWhere('runs_conceded','>',0)->orWhere('wides_bowl','>',0)->orWhere('noballs_bowl','>',0)->orWhere('ecomony','>',0);
                                                              })
                                                              ->orderBy('sr_no_in_bowling_team', 'asc')
                                                              ->get();

            if(count($team_a_scnd_bowling)>0)
            {
                $team_a_scnd_ing_bowling_array = $team_a_scnd_bowling->toArray();
            }


            //get team b details second innings
            $team_b_fst_innings = CricketPlayerMatchwiseStats::select()
                                                             ->where('match_id',$match_data[0]['id'])
                                                             ->where('team_id',$secondIngsecondBatId)
                                                             ->where('innings','second')
                                                             ->where(function($q3){
                                                                 $q3->where('totalruns','>',0)->orWhere('balls_played','>',0)->orWhere('fours','>',0)->orWhere('sixes','>',0)->orwhereNotNull('out_as');
                                                             })
                                                             ->orderBy('sr_no_in_batting_team', 'asc')
                                                             ->get();

            if(count($team_b_fst_innings)>0)
            {
                $team_b_secnd_ing_array = $team_b_fst_innings->toArray();
            }

            //get team b bowling Details
            $team_b_scnd_bowling = CricketPlayerMatchwiseStats::select()
                                                              ->where('match_id',$match_data[0]['id'])
                                                              ->where('team_id',$secondIngsecondBatId)
                                                              ->where('innings','second')
                                                              ->where(function($q2){
                                                                  $q2->where('overs_bowled','>',0)->orWhere('overs_maiden','>',0)->orWhere('wickets','>',0)->orWhere('runs_conceded','>',0)->orWhere('wides_bowl','>',0)->orWhere('noballs_bowl','>',0)->orWhere('ecomony','>',0);
                                                              })
                                                              ->orderBy('sr_no_in_bowling_team', 'asc')
                                                              ->get();

            if(count($team_b_scnd_bowling)>0)
            {
                $team_b_scnd_ing_bowling_array = $team_b_scnd_bowling->toArray();
            }
        }

        //get match details fall of wickets
        $team_wise_match_details = array();
        $match_details = $match_data[0]['match_details'];
        if($match_details!='' && $match_details!=NULL)
        {
            $json_decode_array = json_decode($match_details,true);
            foreach($json_decode_array as $key => $array)
            {
                $team_wise_match_details[$key] = $array;
            }
        }

        $b_keyCount_fst_ing=0; // get fall of wickets for team a first inning
        $a_keyCount_fst_ing=0; // get fall of wickets for team b first inning

        $b_keycount_scnd_ing = 0;
        $a_keycount_scnd_ing = 0;
        if(count($team_wise_match_details)>0)
        {
            //get b array key count
            $b_keyCount_fst_ing = count(
                array_filter(
                    array_keys($team_wise_match_details[$match_data[0]['b_id']]['first']),
                    'is_numeric'
                )
            );

            //get a array key count
            $a_keyCount_fst_ing = count(
                array_filter(
                    array_keys($team_wise_match_details[$match_data[0]['a_id']]['first']),
                    'is_numeric'
                )
            );
            if($match_data[0]['match_type']=='test') //FOR TEST MATCH second ing
            {
                if(!empty($team_wise_match_details[$match_data[0]['b_id']]['second']))
                {
                    //get b array key count
                    $b_keycount_scnd_ing = count(
                        array_filter(
                            array_keys($team_wise_match_details[$match_data[0]['b_id']]['second']),
                            'is_numeric'
                        )
                    );

                    //get a array key count
                    $a_keycount_scnd_ing = count(
                        array_filter(
                            array_keys($team_wise_match_details[$match_data[0]['a_id']]['second']),
                            'is_numeric'
                        )
                    );
                }

            }
        }



        $team_a_count = count($team_a);
        $team_b_count = count($team_b);

        //second inning team player count
        $team_a_scnd_ing_count = count($team_a_scnd_ing);
        $team_b_scnd_ing_count = count($team_b_scnd_ing);

        $team_a_fst_ing_score='';
        $team_a_fst_ing_wkt='';
        $team_a_fst_ing_overs='';
        $team_a_scnd_ing_score='';
        $team_a_scnd_ing_wkt='';
        $team_a_scnd_ing_overs='';
        //team_a_total_score fst ing
        if(!empty($team_wise_match_details[$fstIngFstBatId]['fst_ing_score']) && $team_wise_match_details[$fstIngFstBatId]['fst_ing_score']!=null)
        {
            $team_a_fst_ing_score = $team_wise_match_details[$fstIngFstBatId]['fst_ing_score'];
        }
        if(!empty($team_wise_match_details[$fstIngFstBatId]['fst_ing_score']) && $team_wise_match_details[$fstIngFstBatId]['fst_ing_score']!=null)
        {
            $team_a_fst_ing_wkt = $team_wise_match_details[$fstIngFstBatId]['fst_ing_wkt'];
        }
        if(!empty($team_wise_match_details[$fstIngFstBatId]['fst_ing_score']) && $team_wise_match_details[$fstIngFstBatId]['fst_ing_score']!=null)
        {
            $team_a_fst_ing_overs = $team_wise_match_details[$fstIngFstBatId]['fst_ing_overs'];
        }

        //team_a_total_score scnd ing
        if(!empty($team_wise_match_details[$secondIngFstBatId]['scnd_ing_score']) && $team_wise_match_details[$secondIngFstBatId]['scnd_ing_score']!=null)
        {
            $team_a_scnd_ing_score = $team_wise_match_details[$secondIngFstBatId]['scnd_ing_score'];
        }
        if(!empty($team_wise_match_details[$secondIngFstBatId]['scnd_ing_wkt']) && $team_wise_match_details[$secondIngFstBatId]['scnd_ing_wkt']!=null)
        {
            $team_a_scnd_ing_wkt = $team_wise_match_details[$secondIngFstBatId]['scnd_ing_wkt'];
        }
        if(!empty($team_wise_match_details[$secondIngFstBatId]['scnd_ing_overs']) && $team_wise_match_details[$secondIngFstBatId]['scnd_ing_overs']!=null)
        {
            $team_a_scnd_ing_overs = $team_wise_match_details[$secondIngFstBatId]['scnd_ing_overs'];
        }

        $team_b_fst_ing_score='';
        $team_b_fst_ing_wkt='';
        $team_b_fst_ing_overs='';
        $team_b_scnd_ing_score='';
        $team_b_scnd_ing_wkt='';
        $team_b_scnd_ing_overs='';
        //team_b_total_score
        if(!empty($team_wise_match_details[$fstIngsecondBatId]['fst_ing_score']) && $team_wise_match_details[$fstIngsecondBatId]['fst_ing_score']!=null)
        {
            $team_b_fst_ing_score = $team_wise_match_details[$fstIngsecondBatId]['fst_ing_score'];
        }
        if(!empty($team_wise_match_details[$fstIngsecondBatId]['fst_ing_wkt']) && $team_wise_match_details[$fstIngsecondBatId]['fst_ing_wkt']!=null)
        {
            $team_b_fst_ing_wkt = $team_wise_match_details[$fstIngsecondBatId]['fst_ing_wkt'];
        }
        if(!empty($team_wise_match_details[$fstIngsecondBatId]['fst_ing_overs']) && $team_wise_match_details[$fstIngsecondBatId]['fst_ing_overs']!=null)
        {
            $team_b_fst_ing_overs = $team_wise_match_details[$fstIngsecondBatId]['fst_ing_overs'];
        }

        //team_b_total_score scnd ing
        if(!empty($team_wise_match_details[$secondIngsecondBatId]['scnd_ing_score']) && $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_score']!=null)
        {
            $team_b_scnd_ing_score = $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_score'];
        }
        if(!empty($team_wise_match_details[$secondIngsecondBatId]['scnd_ing_wkt']) && $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_wkt']!=null)
        {
            $team_b_scnd_ing_wkt = $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_wkt'];
        }
        if(!empty($team_wise_match_details[$secondIngsecondBatId]['scnd_ing_overs']) && $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_overs']!=null)
        {
            $team_b_scnd_ing_overs = $team_wise_match_details[$secondIngsecondBatId]['scnd_ing_overs'];
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


        //team a city
        $team_a_city = Helper::getTeamCity($match_data[0]['a_id']);
        $team_b_city = Helper::getTeamCity($match_data[0]['b_id']);

        if($is_from_view==1  || (!empty($score_status_array['added_by']) && $score_status_array['added_by']!=$loginUserId && $match_data[0]['scoring_status']!='rejected') || $match_data[0]['match_status']=='completed' || $match_data[0]['scoring_status']=='approval_pending' || $match_data[0]['scoring_status']=='approved' || !$isValidUser) //only to view cricket score
        {
            $player_name_array = array();
            $users = User::select('id', 'name')->get()->toArray(); //get player names
            foreach ($users as $user) {
                $player_name_array[$user['id']] = $user['name']; //get team names
            }

            $enum_shortcuts = [     'bowled'                => 'b',
                                    'caught'                => 'c',
                                    'handled_ball'          => 'htb',
                                    'hit_ball_twice'        => 'htbt',
                                    'hit_wicket'            => 'hw',
                                    'lbw'                   => 'lbw',
                                    'obstructing_the_field' => 'otf',
                                    'retired'               => 'r',
                                    'run_out'               => 'ro',
                                    'stumped'               => 's',
                                    'timed_out'             => 'to',
                                    'not_out'               => 'no'];

            return view('scorecards.cricketscorecardview', array(
                'first_innings_team_variable'   => $first_innings_team_variable,
                'second_innings_team_variable'  => $second_innings_team_variable,
                'toss_won_by'                   => $toss_won_by,
                'fstIngFstBatId'                => $fstIngFstBatId,
                'fstIngsecondBatId'             => $fstIngsecondBatId,
                'secondIngFstBatId'             => $secondIngFstBatId,
                'secondIngsecondBatId'          => $secondIngsecondBatId,
                'tournamentDetails'             => $tournamentDetails,
                'sportsDetails'                 => $sportsDetails,
                'team_a'                        => ['' => 'Select Player'] + $team_a,
                'team_b'                        => ['' => 'Select Player'] + $team_b,
                'match_data'                    => $match_data,
                'team_a_name'                   => $team_a_name,
                'team_b_name'                   => $team_b_name,
                'enum'                          => ['' => 'Select Out As'] + $enum,
                'team_a_fst_ing_array'          => $team_a_fst_ing_array,
                'team_b_fst_ing_array'          => $team_b_fst_ing_array,
                'team_a_secnd_ing_array'        => $team_a_secnd_ing_array,
                'team_b_secnd_ing_array'        => $team_b_secnd_ing_array,
                'team_wise_match_details'       => $team_wise_match_details,
                'team_a_count'                  => $team_a_count,
                'team_b_count'                  => $team_b_count,
                'team_a_logo'                   => $team_a_logo,
                'team_b_logo'                   => $team_b_logo,

                'team_a_fst_ing_score'          => ${'team_'.$first_innings_team_variable['first'].'_fst_ing_score'},
                'team_a_fst_ing_wkt'            => ${'team_'.$first_innings_team_variable['first'].'_fst_ing_wkt'},
                'team_a_fst_ing_overs'          => ${'team_'.$first_innings_team_variable['first'].'_fst_ing_overs'},

                'team_a_scnd_ing_score'         => ${'team_'.$second_innings_team_variable['first'].'_scnd_ing_score'},
                'team_a_scnd_ing_wkt'           => ${'team_'.$second_innings_team_variable['first'].'_scnd_ing_wkt'},
                'team_a_scnd_ing_overs'         => ${'team_'.$second_innings_team_variable['first'].'_scnd_ing_overs'},

                'team_b_fst_ing_score'          => ${'team_'.$first_innings_team_variable['second'].'_fst_ing_score'},
                'team_b_fst_ing_wkt'            => ${'team_'.$first_innings_team_variable['second'].'_fst_ing_wkt'},
                'team_b_fst_ing_overs'          => ${'team_'.$first_innings_team_variable['second'].'_fst_ing_overs'},

                'team_b_scnd_ing_score'         => ${'team_'.$second_innings_team_variable['second'].'_scnd_ing_score'},
                'team_b_scnd_ing_wkt'           => ${'team_'.$second_innings_team_variable['second'].'_scnd_ing_wkt'},
                'team_b_scnd_ing_overs'         => ${'team_'.$second_innings_team_variable['second'].'_scnd_ing_overs'},

                'player_name_array'             => $player_name_array,
                'a_keyCount'                    => $a_keyCount_fst_ing,
                'b_keyCount'                    => $b_keyCount_fst_ing,
                'a_keycount_scnd_ing'           => $a_keycount_scnd_ing,
                'b_keycount_scnd_ing'           => $b_keycount_scnd_ing,
                'enum_shortcuts'                => $enum_shortcuts,
                'score_status_array'            => $score_status_array,
                'loginUserId'                   => $loginUserId,
                'rej_note_str'                  => $rej_note_str,
                'loginUserRole'                 => $loginUserRole,
                'isValidUser'                   => $isValidUser,
                'isApproveRejectExist'          => $isApproveRejectExist,
                'isForApprovalExist'            => $isForApprovalExist,
                'action_id'                     => $match_data[0]['id'],
                'team_a_scnd_ing_count'         => $team_a_scnd_ing_count,
                'team_b_scnd_ing_count'         => $team_b_scnd_ing_count,
                'team_a_scnd_ing'               => ['' => 'Select Player'] + $team_a_scnd_ing,
                'team_b_scnd_ing'               => ['' => 'Select Player'] + $team_b_scnd_ing,
                'team_a_fst_ing_bowling_array'  => $team_a_fst_ing_bowling_array,
                'team_b_fst_ing_bowling_array'  => $team_b_fst_ing_bowling_array,
                'team_a_scnd_ing_bowling_array' => $team_a_scnd_ing_bowling_array,
                'team_b_scnd_ing_bowling_array' => $team_b_scnd_ing_bowling_array,
                'team_a_city'                   => $team_a_city,
                'team_b_city'                   => $team_b_city));
        }
        else //to view and edit cricket score card
        {
            return view('scorecards.cricketscorecard', array(
                'first_innings_team_variable'   => $first_innings_team_variable,
                'second_innings_team_variable'  => $second_innings_team_variable,
                'toss_won_by'                   => $toss_won_by,
                'fstIngFstBatId'                => $fstIngFstBatId,
                'fstIngsecondBatId'             => $fstIngsecondBatId,
                'secondIngFstBatId'             => $secondIngFstBatId,
                'secondIngsecondBatId'          => $secondIngsecondBatId,
                'tournamentDetails'             => $tournamentDetails, 'sportsDetails'=> $sportsDetails, 'team_a'=>[''=>'Select Player']+$team_a,'team_b'=>[''=>'Select Player']+$team_b,'match_data'=>$match_data,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name,'enum'=> ['' => 'Select Out As'] + $enum,'team_a_fst_ing_array'=>$team_a_fst_ing_array,'team_b_fst_ing_array'=>$team_b_fst_ing_array,'team_a_secnd_ing_array'=>$team_a_secnd_ing_array,'team_b_secnd_ing_array'=>$team_b_secnd_ing_array,'team_wise_match_details'=>$team_wise_match_details,'team_a_count'=>$team_a_count,'team_b_count'=>$team_b_count,'team_a_logo'=>$team_a_logo,'team_b_logo'=>$team_b_logo,'team_a_fst_ing_score'=>$team_a_fst_ing_score,'team_a_fst_ing_wkt'=>$team_a_fst_ing_wkt,'team_a_fst_ing_overs'=>$team_a_fst_ing_overs,'team_a_scnd_ing_score'=>$team_a_scnd_ing_score,'team_a_scnd_ing_wkt'=>$team_a_scnd_ing_wkt,'team_a_scnd_ing_overs'=>$team_a_scnd_ing_overs,'team_b_fst_ing_score'=>$team_b_fst_ing_score,'team_b_fst_ing_wkt'=>$team_b_fst_ing_wkt,'team_b_fst_ing_overs'=>$team_b_fst_ing_overs,'team_b_scnd_ing_score'=>$team_b_scnd_ing_score,'team_b_scnd_ing_wkt'=>$team_b_scnd_ing_wkt,'team_b_scnd_ing_overs'=>$team_b_scnd_ing_overs,'a_keyCount'=>$a_keyCount_fst_ing,'b_keyCount'=>$b_keyCount_fst_ing,'a_keycount_scnd_ing'=>$a_keycount_scnd_ing,'b_keycount_scnd_ing'=>$b_keycount_scnd_ing,'score_status_array'=>$score_status_array,'loginUserId'=>$loginUserId,'rej_note_str'=>$rej_note_str,'loginUserRole'=>$loginUserRole,'isValidUser'=>$isValidUser,'isApproveRejectExist'=>$isApproveRejectExist,'isForApprovalExist'=>$isForApprovalExist,'action_id'=>$match_data[0]['id'],'team_a_scnd_ing_count'=>$team_a_scnd_ing_count,'team_b_scnd_ing_count'=>$team_b_scnd_ing_count,'team_a_scnd_ing'=>[''=>'Select Player']+$team_a_scnd_ing,'team_b_scnd_ing'=>[''=>'Select Player']+$team_b_scnd_ing,'team_a_fst_ing_bowling_array'=>$team_a_fst_ing_bowling_array,'team_b_fst_ing_bowling_array'=>$team_b_fst_ing_bowling_array,'team_a_scnd_ing_bowling_array'=>$team_a_scnd_ing_bowling_array,'team_b_scnd_ing_bowling_array'=>$team_b_scnd_ing_bowling_array,'team_a_city'=>$team_a_city,'team_b_city'=>$team_b_city));
        }

    }

    public function insertCricketScoreCard()
    {
        $loginUserId         = Auth::user()->id;
        $request             = Request::all();

        //team a details
        $team_a_player_count = Request::get('a_player_count');
        $team_b_player_count = Request::get('b_player_count');
        $a_bowler_count      = Request::get('a_bowler_count');
        $b_bowler_count      = Request::get('b_bowler_count');
        $team_a_name         = Request::get('team_a_name');
        $team_b_name         = Request::get('team_b_name');
        $winner_team_id      = !empty(Request::get('winner_team_id')) ? Request::get('winner_team_id') : NULL; //winner team id
        $match_result        = !empty(Request::get('hid_match_result')) ? Request::get('hid_match_result') : NULL; //winner team id

        $team_a_id     = !empty(Request::get('team_a_id')) ? Request::get('team_a_id') : NULL;
        $team_b_id     = !empty(Request::get('team_b_id')) ? Request::get('team_b_id') : NULL;
        $tournament_id = !empty(Request::get('tournament_id')) ? Request::get('tournament_id') : NULL;
        $match_type    = !empty(Request::get('match_type')) ? Request::get('match_type') : NULL;
        $inning        = !empty(Request::get('inning')) ? Request::get('inning') : 'first';
        $match_id      = Request::get('match_id');
        $match_report  = !empty(Request::get('match_report')) ? Request::get('match_report') : NULL;
        $player_of_the_match  = !empty(Request::get('player_of_the_match')) ? Request::get('player_of_the_match') : NULL;

        $match_model=MatchSchedule::find($match_id);
        $team_a_model=Team::find($team_a_id);
        $team_b_model=Team::find($team_b_id);

        if(!is_null($team_a_model)){
            $team_a_name=$team_a_model->name;
        }
        if(!is_null($team_b_model)){
            $team_b_name=$team_b_model->name;
        }

        //toss won by
        $tossWonBy = !empty(Request::get('toss_won_by'))?Request::get('toss_won_by'):NULL; //toss won by team id
        $toss_won_team_name = !empty(Request::get('toss_won_team_name'))?Request::get('toss_won_team_name'):NULL; //toss won by team id

        //delete all records before insert or update score card
        $match_score = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('innings',$inning)->get();
        if (count($match_score)>0)
        {
            CricketPlayerMatchwiseStats::where('match_id',$match_id)->where('innings',$inning)->update(['deleted_at'=>Carbon::now()]);
        }
        for($i=1;$i<=$team_a_player_count;$i++)//insert team a batsman score
        {
            $user_id_a = !empty(Request::get('a_player_'.$i))?Request::get('a_player_'.$i):0;

            $totalruns_a = (is_numeric(Request::get('a_runs_'.$i)))?Request::get('a_runs_'.$i):0;
            $balls_played_a = (is_numeric(Request::get('a_balls_'.$i)))?Request::get('a_balls_'.$i):0;
            $fours_a = (is_numeric(Request::get('a_fours_'.$i)))?Request::get('a_fours_'.$i):0;
            $sixes_a = (is_numeric(Request::get('a_sixes_'.$i)))?Request::get('a_sixes_'.$i):0;
            $out_as_a = Request::get('a_outas_'.$i);
            $strikerate_a = Request::get('a_strik_rate_'.$i);
            $fielder_id_a = 0;
            $bowled_id_a = Request::get('a_bowled_'.$i);
            if($out_as_a=='caught' || $out_as_a=='run_out' || $out_as_a=='stumped')
            {
                $fielder_id_a = Request::get('a_fielder_'.$i);
            }
            if($out_as_a=='handled_ball' || $out_as_a=='obstructing_the_field' || $out_as_a=='retired' || $out_as_a=='timed_out' || $out_as_a=='not_out')
            {
                $bowled_id_a = 0;
            }


            if($user_id_a>0)
            {
                $player_name = User::where('id',$user_id_a)->pluck('name');
                //check already player exists r not
                $is_player_exist = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('team_id',$team_a_id)->where('user_id',$user_id_a)->where('innings',$inning)->get()->first();

                //bat status
                $a_bat_status = 'notout';
                if($out_as_a!='')
                {
                    $a_bat_status = 'out';
                }else if(!($totalruns_a>0 || $balls_played_a>0 || $fours_a>0 || $sixes_a>0))
                {
                    $a_bat_status = 'dnb';
                }

                // 50's and 100's - start
                $fifties = $hundreds = 0;

                if ((int) $totalruns_a > 50)
                {
                    $fifties = (int) floor($totalruns_a / 50);
                }

                if ((int) $totalruns_a > 100)
                {
                    $hundreds = (int) floor($totalruns_a / 100);
                }

                if (count($is_player_exist)>0)// if player already exist
                {
                    $update_id = $is_player_exist['id'];
                    CricketPlayerMatchwiseStats::where('id', $update_id)
                                               ->update([
                                                   'balls_played'          => $balls_played_a,
                                                   'totalruns'             => $totalruns_a,
                                                   'fours'                 => $fours_a,
                                                   'sixes'                 => $sixes_a,
                                                   'out_as'                => $out_as_a,
                                                   'strikerate'            => $strikerate_a,
                                                   'fielder_id'            => $fielder_id_a,
                                                   'bowled_id'             => $bowled_id_a,
                                                   'bat_status'            => $a_bat_status,
                                                   'sr_no_in_batting_team' => $i,
                                                   'fifties'               => $fifties,
                                                   'hundreds'              => $hundreds
                                               ]);
                }else
                {
                    CricketPlayerMatchwiseStats::insertBatsmenScore($user_id_a,$tournament_id,$match_id,$team_a_id,$match_type,$balls_played_a,$totalruns_a,$fours_a,$sixes_a,$out_as_a,$strikerate_a,$team_a_name,$player_name,$inning,$fielder_id_a,$bowled_id_a,$a_bat_status,$i,$fifties,$hundreds);
                }


            }
        }
        //Team a bowler Detail
        for($j=1;$j<=$a_bowler_count;$j++)
        {
            $bowler_id_a = !empty(Request::get('a_bowler_'.$j))?Request::get('a_bowler_'.$j):0;
            $bowler_name = User::where('id',$bowler_id_a)->pluck('name');
            $a_overs_bowled = Request::get('a_bowler_overs_'.$j);
            $a_wickets = (is_numeric(Request::get('a_bowler_wkts_'.$j)))?Request::get('a_bowler_wkts_'.$j):0;
            $a_runs_conceded = (is_numeric(Request::get('a_bowler_runs_'.$j)))?Request::get('a_bowler_runs_'.$j):0;
            $a_ecomony = Request::get('a_ecomony_'.$j);
            $a_wide = !empty(Request::get('a_bowler_wide_'.$j))?Request::get('a_bowler_wide_'.$j):0;
            $a_noball = !empty(Request::get('a_bowler_noball_'.$j))?Request::get('a_bowler_noball_'.$j):0;
            $a_maidens = !empty(Request::get('a_bowler_maidens_'.$j))?Request::get('a_bowler_maidens_'.$j):0;


            if($bowler_id_a>0 && ($a_overs_bowled>0 || $a_wickets>0 || $a_runs_conceded>0))
            {
                //check already bowler exists r not
                $is_bowler_exist = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('team_id',$team_a_id)->where('user_id',$bowler_id_a)->where('innings',$inning)->get()->first();

                if(count($is_bowler_exist)>0) // if player already exist
                {
                    $bowler_id = $is_bowler_exist['id'];
                    CricketPlayerMatchwiseStats::where('id',$bowler_id)->update(['overs_bowled'=>$a_overs_bowled,'wickets'=>$a_wickets,'runs_conceded'=>$a_runs_conceded,'ecomony'=>$a_ecomony,'wides_bowl'=>$a_wide,'noballs_bowl'=>$a_noball,'overs_maiden'=>$a_maidens,'sr_no_in_bowling_team'=>$j]);

                }else
                {
                    CricketPlayerMatchwiseStats::insertBowlerScore($bowler_id_a,$tournament_id,$match_id,$team_a_id,$match_type,$a_overs_bowled,$a_wickets,$a_runs_conceded,$a_ecomony,$team_a_name,$bowler_name,$inning,$a_wide,$a_noball,$a_maidens,$j);

                }

            }

        }
        //Team b innings
        for($k=1;$k<=$team_b_player_count;$k++)//insert team a batsman score
        {
            $user_id_b = !empty(Request::get('b_player_'.$k))?Request::get('b_player_'.$k):0;
            $player_b_name = User::where('id',$user_id_b)->pluck('name');
            $totalruns_b = (is_numeric(Request::get('b_runs_'.$k)))?Request::get('b_runs_'.$k):0;
            $balls_played_b = (is_numeric(Request::get('b_balls_'.$k)))?Request::get('b_balls_'.$k):0;
            $fours_b = (is_numeric(Request::get('b_fours_'.$k)))?Request::get('b_fours_'.$k):0;
            $sixes_b = (is_numeric(Request::get('b_sixes_'.$k)))?Request::get('b_sixes_'.$k):0;
            $out_as_b = Request::get('b_outas_'.$k);
            $strikerate_b = Request::get('b_strik_rate_'.$k);
            $bowled_id_b = Request::get('b_bowled_'.$k);
            $fielder_id_b=0;

            if($out_as_b=='caught' || $out_as_b=='run_out' || $out_as_b=='stumped')
            {
                $fielder_id_b = Request::get('b_fielder_'.$k);
            }
            if($out_as_b=='handled_ball' || $out_as_b=='obstructing_the_field' || $out_as_b=='retired' || $out_as_b=='timed_out' || $out_as_b=='not_out')
            {
                $bowled_id_b=0;
            }
            if($user_id_b>0)
            {
                //check already player exists r not
                $is_b_player_exist = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('team_id',$team_b_id)->where('user_id',$user_id_b)->where('innings',$inning)->get()->first();


                //bat status
                $b_bat_status = 'notout';
                if($out_as_b!='')
                {
                    $b_bat_status = 'out';
                }else if(!($totalruns_b>0 || $balls_played_b>0 || $fours_b>0 || $sixes_b>0))
                {
                    $b_bat_status = 'dnb';
                }

                // 50's and 100's - start
                $fifties = $hundreds = 0;

                if ((int) $totalruns_b > 50)
                {
                    $fifties = (int) ($totalruns_b / 50);
                }

                if ((int) $totalruns_b > 100)
                {
                    $hundreds = (int) ($totalruns_b / 100);
                }

                if(count($is_b_player_exist)>0)
                {
                    $update_id = $is_b_player_exist['id'];
                    CricketPlayerMatchwiseStats::where('id',$update_id)
                                               ->update([
                                                   'balls_played'          => $balls_played_b,
                                                   'totalruns'             => $totalruns_b,
                                                   'fours'                 => $fours_b,
                                                   'sixes'                 => $sixes_b,
                                                   'out_as'                => $out_as_b,
                                                   'strikerate'            => $strikerate_b,
                                                   'fielder_id'            => $fielder_id_b,
                                                   'bowled_id'             => $bowled_id_b,
                                                   'bat_status'            => $b_bat_status,
                                                   'sr_no_in_batting_team' => $k,
                                                   'fifties'               => $fifties,
                                                   'hundreds'              => $hundreds
                                               ]);
                }else
                {
                    CricketPlayerMatchwiseStats::insertBatsmenScore($user_id_b,$tournament_id,$match_id,$team_b_id,$match_type,$balls_played_b,$totalruns_b,$fours_b,$sixes_b,$out_as_b,$strikerate_b,$team_b_name,$player_b_name,$inning,$fielder_id_b,$bowled_id_b,$b_bat_status,$k,$fifties,$hundreds);
                }



            }
        }
        //Team b bowler Detail
        for($l=1;$l<=$b_bowler_count;$l++)
        {
            $bowler_id_b = !empty(Request::get('b_bowler_'.$l))?Request::get('b_bowler_'.$l):0;
            $bowler_b_name = User::where('id',$bowler_id_b)->pluck('name');
            $b_overs_bowled = Request::get('b_bowler_overs_'.$l);
            $b_wickets = (is_numeric(Request::get('b_bowler_wkts_'.$l)))?Request::get('b_bowler_wkts_'.$l):0;
            $b_runs_conceded = (is_numeric(Request::get('b_bowler_runs_'.$l)))?Request::get('b_bowler_runs_'.$l):0;
            $b_ecomony = Request::get('b_ecomony_'.$l);
            $b_wide = !empty(Request::get('b_bowler_wide_'.$l))?Request::get('b_bowler_wide_'.$l):0;
            $b_noball = !empty(Request::get('b_bowler_noball_'.$l))?Request::get('b_bowler_noball_'.$l):0;
            $b_maidens = !empty(Request::get('b_bowler_maidens_'.$l))?Request::get('b_bowler_maidens_'.$l):0;

            if($bowler_id_b>0 && ($b_overs_bowled>0 || $b_wickets>0 || $b_runs_conceded>0))
            {
                //check already bowler exists r not
                $is_bowler_b_exist = CricketPlayerMatchwiseStats::select()->where('match_id',$match_id)->where('team_id',$team_b_id)->where('user_id',$bowler_id_b)->where('innings',$inning)->get()->first();

                if(count($is_bowler_b_exist)>0)
                {
                    $bowler_id = $is_bowler_b_exist['id'];
                    CricketPlayerMatchwiseStats::where('id',$bowler_id)->update(['overs_bowled'=>$b_overs_bowled,'wickets'=>$b_wickets,'runs_conceded'=>$b_runs_conceded,'ecomony'=>$b_ecomony,'wides_bowl'=>$b_wide,'noballs_bowl'=>$b_noball,'overs_maiden'=>$b_maidens,'sr_no_in_bowling_team'=>$l]);

                }else
                {
                    CricketPlayerMatchwiseStats::insertBowlerScore($bowler_id_b,$tournament_id,$match_id,$team_b_id,$match_type,$b_overs_bowled,$b_wickets,$b_runs_conceded,$b_ecomony,$team_b_name,$bowler_b_name,$inning,$b_wide,$b_noball,$b_maidens,$l);
                }


            }

        }
        //Team b bowling

        //Fall of Wikets for Team a
        $team_a_fallofwkt_count = Request::get('a_fall_of_count');
        $team_a_array = array();
        for($m=1;$m<=$team_a_fallofwkt_count;$m++)
        {
            $wkt_a_at = (is_numeric(Request::get('a_wicket_'.$m)))?Request::get('a_wicket_'.$m):0;
            $player_a_id = Request::get('a_wkt_player_'.$m);
            $at_runs_a = (is_numeric(Request::get('a_at_runs_'.$m)))?Request::get('a_at_runs_'.$m):0;
            $at_over_a = Request::get('a_at_over_'.$m);

            if($player_a_id>0)
            {
                $team_a_array[] = array('wicket'=>$wkt_a_at,'batsman'=>$player_a_id,'score'=>$at_runs_a,'over'=>$at_over_a);
            }

        }
        //team a extras
        $team_a_wide = !empty(Request::get('team_a_wide'))?Request::get('team_a_wide'):0;
        $team_a_noball = !empty(Request::get('team_a_noball'))?Request::get('team_a_noball'):0;
        $team_a_legbye = !empty(Request::get('team_a_legbye'))?Request::get('team_a_legbye'):0;
        $team_a_bye = !empty(Request::get('team_a_bye'))?Request::get('team_a_bye'):0;
        $team_a_others = !empty(Request::get('team_a_others'))?Request::get('team_a_others'):0;
        $team_a_extras_array = array('wide'=>$team_a_wide,'noball'=>$team_a_noball,'legbye'=>$team_a_legbye,'bye'=>$team_a_bye,'others'=>$team_a_others);

        $team_level_stats = [];

        //team a scores
        foreach ([$team_a_id,$team_b_id] as $teamStat_team_id)
        {
            foreach (['first','second'] as $teamStat_innings_name)
            {
                foreach (['score','wickets','overs'] as $teamStat_inning_stat_name)
                {
                    $value = '';
                    if (isset($request[$teamStat_innings_name . '_inning'][$teamStat_team_id][$teamStat_inning_stat_name]))
                    {
                        $value = $request[$teamStat_innings_name . '_inning'][$teamStat_team_id][$teamStat_inning_stat_name];
                    }
                    $team_level_stats[$teamStat_team_id][$teamStat_innings_name][$teamStat_inning_stat_name] = (!empty($value) && $value > 0) ? $value : NULL;
                }
            }
        }

        $team_a_fst_ing_score  = $team_level_stats[$team_a_id]['first']['score'];
        $team_a_fst_ing_wkt    = $team_level_stats[$team_a_id]['first']['wickets'];
        $team_a_fst_ing_overs  = $team_level_stats[$team_a_id]['first']['overs'];
        $team_a_scnd_ing_score = $team_level_stats[$team_a_id]['second']['score'];
        $team_a_scnd_ing_wkt   = $team_level_stats[$team_a_id]['second']['wickets'];
        $team_a_scnd_ing_overs = $team_level_stats[$team_a_id]['second']['overs'];

        $team_a_score = array('fst_ing_score'=>$team_a_fst_ing_score,'scnd_ing_score'=>$team_a_scnd_ing_score,'fst_ing_wkt'=>$team_a_fst_ing_wkt,'scnd_ing_wkt'=>$team_a_scnd_ing_wkt,'fst_ing_overs'=>$team_a_fst_ing_overs,'scnd_ing_overs'=>$team_a_scnd_ing_overs);

        //team b scores
        $team_b_fst_ing_score  = $team_level_stats[$team_b_id]['first']['score'];
        $team_b_fst_ing_wkt    = $team_level_stats[$team_b_id]['first']['wickets'];
        $team_b_fst_ing_overs  = $team_level_stats[$team_b_id]['first']['overs'];
        $team_b_scnd_ing_score = $team_level_stats[$team_b_id]['second']['score'];
        $team_b_scnd_ing_wkt   = $team_level_stats[$team_b_id]['second']['wickets'];
        $team_b_scnd_ing_overs = $team_level_stats[$team_b_id]['second']['overs'];

        //team b extras
        $team_b_wide = !empty(Request::get('team_b_wide'))?Request::get('team_b_wide'):0;
        $team_b_noball = !empty(Request::get('team_b_noball'))?Request::get('team_b_noball'):0;
        $team_b_legbye = !empty(Request::get('team_b_legbye'))?Request::get('team_b_legbye'):0;
        $team_b_bye = !empty(Request::get('team_b_bye'))?Request::get('team_b_bye'):0;
        $team_b_others = !empty(Request::get('team_b_others'))?Request::get('team_b_others'):0;
        $team_b_extras_array = array('wide'=>$team_b_wide,'noball'=>$team_b_noball,'legbye'=>$team_b_legbye,'bye'=>$team_b_bye,'others'=>$team_b_others);

        $fallOfCount_a[$team_a_id][$inning] = $team_a_array+$team_a_extras_array;//indvidual team fall of count

        $team_a_two_ings_score[$team_a_id] = $team_a_score; //team a toral score with overs wikets

        $team_a_match_details = array_replace_recursive($fallOfCount_a,$team_a_two_ings_score) ;// merge fall of wkts, match score details

        $decode_json=array();
        //get exists match details
        $get_match_details = MatchSchedule::where('id',$match_id)->pluck('match_details');
        if($get_match_details!='')
            $decode_json = json_decode($get_match_details,true);


        //Fall of Wikets for Team b
        $team_b_fallofwkt_count = Request::get('b_fall_of_count');
        $team_b_array = array();
        for($n=1;$n<=$team_b_fallofwkt_count;$n++)
        {
            $wkt_b_at = (is_numeric(Request::get('b_wicket_'.$n)))?Request::get('b_wicket_'.$n):0;
            $player_b_id = Request::get('b_wkt_player_'.$n);
            $at_runs_b = (is_numeric(Request::get('b_at_runs_'.$n)))?Request::get('b_at_runs_'.$n):0;
            $at_over_b = Request::get('b_at_over_'.$n);
            if($player_b_id>0)
            {
                $team_b_array[] = array('wicket'=>$wkt_b_at,'batsman'=>$player_b_id,'score'=>$at_runs_b,'over'=>$at_over_b);
            }
        }

        $team_b_score = array('fst_ing_score'=>$team_b_fst_ing_score,'scnd_ing_score'=>$team_b_scnd_ing_score,'fst_ing_wkt'=>$team_b_fst_ing_wkt,'scnd_ing_wkt'=>$team_b_scnd_ing_wkt,'fst_ing_overs'=>$team_b_fst_ing_overs,'scnd_ing_overs'=>$team_b_scnd_ing_overs);

        $fallOfCount_b[$team_b_id][$inning] = $team_b_array+$team_b_extras_array;//indvidual team fall of count

        $team_b_two_ings_score[$team_b_id] = $team_b_score; //team a toral score with overs wikets

        $team_b_match_details = array_replace_recursive($fallOfCount_b,$team_b_two_ings_score); // merge fall of wkts, match score details

        $match_details_array = $team_a_match_details+$team_b_match_details;

        $final_match_details = array_replace_recursive($decode_json,$match_details_array);

        $json_match_details_array = json_encode($final_match_details);

        $is_tie         = ($match_result == 'tie')      ? 1 : 0;
        $is_washout     = ($match_result == 'washout')  ? 1 : 0;
        $has_result     = ($is_washout == 1) ? 0 : 1;
        $match_result   = ( !in_array( $match_result, ['tie','win','washout'] ) ) ? NULL : $match_result;

        //get previous scorecard status data
        $scorecardDetails = MatchSchedule::where('id',$match_id)->pluck('score_added_by');
        $decode_scorecard_data = json_decode($scorecardDetails,true);

        $modified_users = !empty($decode_scorecard_data['modified_users'])?$decode_scorecard_data['modified_users']:'';

        $modified_users = $modified_users.','.$loginUserId;//scorecard changed users

        $added_by = !empty($decode_scorecard_data['added_by'])?$decode_scorecard_data['added_by']:$loginUserId;


        //insert toss_won_by  and selected batting or bowling in scoer added by column
        $toss_won_by = !empty($decode_scorecard_data['toss_won_by'])?$decode_scorecard_data['toss_won_by']:$tossWonBy;
        $tossWonTeamName = !empty($decode_scorecard_data['toss_won_team_name'])?$decode_scorecard_data['toss_won_team_name']:$toss_won_team_name;
        $fst_ing_batting = !empty($decode_scorecard_data['fst_ing_batting'])?$decode_scorecard_data['fst_ing_batting']:$team_a_id;
        $scnd_ing_batting = !empty($decode_scorecard_data['scnd_ing_batting'])?$decode_scorecard_data['scnd_ing_batting']:$team_a_id;



        //score card approval process
        $score_status = array('added_by'=>$added_by,'active_user'=>$loginUserId,'modified_users'=>$modified_users,'rejected_note'=>'','toss_won_by'=>$toss_won_by,'toss_won_team_name'=>$tossWonTeamName,'fst_ing_batting'=>$fst_ing_batting,'scnd_ing_batting'=>$scnd_ing_batting);

        $json_score_status = json_encode($score_status);

        //update match details col  && winner id in match schedule table
        $matchScheduleDetails = MatchSchedule::where('id',$match_id)->first();
        if(count($matchScheduleDetails)) {
            $looser_team_id = NULL;
            $match_status = 'scheduled';
            $approved = '';
            if($is_tie==0 || $is_washout == 0) {

                if(isset($winner_team_id)) {
                    if($winner_team_id==$matchScheduleDetails['a_id']) {
                        $looser_team_id=$matchScheduleDetails['b_id'];
                    }else{
                        $looser_team_id=$matchScheduleDetails['a_id'];
                    }
                    $match_status = 'completed';
                    $approved = 'approved';
                }

            }

            if (!empty($matchScheduleDetails['tournament_id']))
            {
//                        dd($winner_team_id.'<>'.$looser_team_id);
                $tournamentDetails = Tournaments::where('id', '=', $matchScheduleDetails['tournament_id'])->first();
                if (Helper::isTournamentOwner($tournamentDetails['manager_id'], $tournamentDetails['tournament_parent_id']))
                {
                    if (($is_tie == 1 || $match_result == "washout") && !empty($matchScheduleDetails['tournament_group_id']))
                    {
                        $match_status = 'completed';
                    }
                    MatchSchedule::where('id', $match_id)->update([
                        'match_details'  => $json_match_details_array,
                        'match_status'   => $match_status,
                        'winner_id'      => $winner_team_id,
                        'looser_id'      => $looser_team_id,
                        'is_tied'        => $is_tie,
                        'has_result'     => $has_result,
                        'match_result'   => $match_result,
                        'score_added_by' => $json_score_status,
                        'match_report'   => $match_report,
                        'player_of_the_match'   => $player_of_the_match]);
//                                Helper::printQueries();

                    if (!empty($matchScheduleDetails['tournament_round_number']))
                    {
                        $matchScheduleDetails->updateBracketDetails();
                    }
                    if ($match_status == 'completed')
                    {
                        $sportName = Sport::where('id', $matchScheduleDetails['sports_id'])->pluck('sports_name');
                        $this->insertPlayerStatistics($sportName, $match_id);

                        Helper::sendEmailPlayers($matchScheduleDetails, 'Cricket');

                        //notification code
                    }
                }
            }
            else if (Auth::user()->role == 'admin')
            {
                if ($is_tie == 1 || $match_result == "washout")
                {
                    $match_status = 'completed';
                    $approved     = 'approved';
                }

                MatchSchedule::where('id', $match_id)->update([
                    'match_details'  => $json_match_details_array,
                    'match_status'   => $match_status,
                    'winner_id'      => $winner_team_id,
                    'looser_id'      => $looser_team_id,
                    'is_tied'        => $is_tie,
                    'has_result'     => $has_result,
                    'match_result'   => $match_result,
                    'score_added_by' => $json_score_status,
                    'scoring_status' => $approved,
                    'match_report'   => $match_report,
                    'player_of_the_match'   => $player_of_the_match]);
                if ($match_status == 'completed')
                {
                    $sportName = Sport::where('id', $matchScheduleDetails['sports_id'])->pluck('sports_name');
                    $this->insertPlayerStatistics($sportName, $match_id);

                    Helper::sendEmailPlayers($matchScheduleDetails, 'Cricket');

                    //notification code
                }
            }
            else
            {
                MatchSchedule::where('id', $match_id)->update([
                    'match_details'  => $json_match_details_array,
                    'winner_id'      => $winner_team_id,
                    'looser_id'      => $looser_team_id,
                    'is_tied'        => $is_tie,
                    'has_result'     => $has_result,
                    'match_result'   => $match_result,
                    'score_added_by' => $json_score_status,
                    'match_report'   => $match_report,
                    'player_of_the_match'   => $player_of_the_match]);
            }
        }

        //if($match_result!='')
        //return redirect()->route('match/scorecard/view', [$match_id])->with('status', trans('message.scorecard.scorecardmsg'));

        //return redirect()->back()->with('status', trans('message.scorecard.scorecardmsg'));
        //return Response()->json( array('success' => trans('message.scorecard.scorecardmsg')) );
    }


}




