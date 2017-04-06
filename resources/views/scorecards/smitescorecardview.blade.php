@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
<style type="text/css">
    input:read-only {
        background-color: #f4f4f4;
    }
    .alert{display: none;}
    .show_teams{display: none;}
    .player_selected{
        background: #111111;
        background-color: red;
    }
    .btn-yellow-card{
        background: orange;
        border: none;
    }
    .btn-red-card{
        background: darkred;
    }
    .btn-card{
        border: none;
    }
    .btn-red-card-select{
        color: white;
        background: #ff4f4f;
    }
    .btn-red-card-select:hover{
        color: white;
        background: #ff4f5f;
    }
    .btn-yellow-card-select{
        color: white;
        background: orange;
    }
    .btn-yellow-card-select:hover{
        color: white;
        background: orange;
    }
    .btn-goal-card-select{
        color: white;
        background: #aaa;
    }
    .btn-goal-card-select:hover{
        color: white;
        background: #aaa;
    }
    .icon-check{
        color:green;
        border: 1px double #999;
        display: block;
        height: 18px;
        width: 16px;

    }

    .selected-player-button-show{
        background: #ffddee;
        border: none;
    }
    .substituted-player-button-show{
        background: #ddcccc;
        border: none;
    }

    .fa-share{
        color:green;
    }
    .fa-reply{
        color:red;
    }
    .btn-penalty{
        opacity: .2;
    }
    .btn-green-card, .btn-green-card:hover, .btn-green-card:active{
        background: #6Bc26C;
    }
    .btn-penalty-chosen{
        opacity: 1;
    }

    .btn-secondary-link{
        background: #ddd;
    }
    .fouls{
        color:red;
        font: 23px;
    }

    .not_playing{
        background-color: #f9f9f9;

    }

    .btn-link:disabled{
        background: #f8f8f8;
        background-color: #f8f8f8;
    }
</style>
<?php
    $team_a_id = $match_data[0]['a_id']; $team_b_id= $match_data[0]['b_id'] ;
    $match_id=$match_data[0]['id'];
    $tournament_id=$match_data[0]['tournament_id'];

    $a_score = $match_data[0]['a_score']; $b_score = $match_data[0]['b_score'];

    $player_a_ids=$match_data[0]['player_a_ids'];
    $player_b_ids=$match_data[0]['player_b_ids'];

    $match_details=json_decode($match_data[0]['match_details']);

    isset($match_details->preferences)?$preferences=$match_details->preferences:[];

    ${'team_'.$match_data[0]['a_id'].'_score'}='0';
    ${'team_'.$match_data[0]['b_id'].'_score'}='0';

    $team_a_info='';
    $team_b_info='';

    if(isset($preferences)){
        $current_set=$match_details->current_set;

        ${'team_'.$team_a_id.'_score'}=$match_details->scores->{$team_a_id.'_score'} .'';
        ${'team_'.$team_b_id.'_score'}=$match_details->scores->{$team_b_id.'_score'} .'';
    } else {
        $current_set=0;
    }

?>

<div class="col_standard soccer_scorecard">
    <div id="team_vs" class="ss_bg">
        <div class="container">
            <div class="row">
                <div class="team team_one col-xs-5">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="team_logo">
                            @if(!empty($team_a_logo))
                                @if($team_a_logo['url']!='')
                                    <!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
                                    {!! Helper::Images($team_a_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                @else
                                    <!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                                    {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}

                                        </td>
                                @endif
                            @else
                                <!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">-->
                                {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                            @endif
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="team_detail">
                                <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $team_a_name }}</a></div>
                                <div class="team_city">{{ $team_a_city }}</div>
                                <div class="team_score" id="team_a_score">{{ $a_score }}</div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <span class="vs"></span>
                </div>
                <div class="team team_two col-xs-5">
                    <div class="row">
                        <div class="col-md-8 col-sm-12 visible-md visible-lg">
                            <div class="team_detail">
                                <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
                                <div class="team_city">{{ $team_b_city }}</div>
                                <div class="team_score" id="team_b_score">{{ $b_score }}</div>

                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="team_logo">
                            @if(!empty($team_b_logo))
                                @if($team_b_logo['url']!='')
                                    <!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
                                    {!! Helper::Images($team_b_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                @else
                                    <!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                                        {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                        </td>
                                @endif
                            @else
                                <!--    <img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">  -->
                                    {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}

                                @endif
                            </div>
                        </div>

                        <div class="col-md-8 col-sm-12 visible-xs visible-sm">
                            <div class="team_detail">
                                <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
                                <div class="team_city">{{ $team_b_city }}</div>
                                <div class="team_score" id="team_b_score">{{ $b_score }}</div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(!is_null($match_data[0]['tournament_id']))
                <div class='row'>
                    <div class='col-xs-12'>
                        <center>
                            <a href="/tournaments/groups/{{$tournamentDetails['id']}}">
                                <h4 class="team_name">    {{$tournamentDetails['name']}} Tournament </h4>
                            </a>

                        </center>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-xs-12">
                    <div class="match_loc">
                        {{ date('jS F , Y',strtotime($match_data[0]['match_start_date'])).' - '.date("g:i a", strtotime($match_data[0]['match_start_time'])).(($match_data[0]['facility_name']!='')?' , '.$match_data[0]['facility_name']:'').(($match_data[0]['address']!='')?' , '.$match_data[0]['address']:'') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pull-up">

        <div class="panel panel-default">
            <div class="col-md-12">
                <h5 class="scoreboard_title">Smite Scorecard
                </h5>

                <div class="clearfix"></div>
                <div class="form-inline">
                    @if($match_data[0]['hasSetupSquad'])
                        <div id='end_match_button'>
                            <button class="btn btn-danger " onclick="return SJ.SCORECARD.soccerSetTimes(this)"></i>End Match</button>
                        </div>
                    @endif
                    @if($match_data[0]['winner_id'] > 0)
                        <div class="form-group">
                            <label class="win_head">Winner</label>
                            <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>
                        </div>
                        <br>
                        @if(!empty($match_data[0]['player_of_the_match']))
                            <div class="form-group">
                                <label class="" style="color:red">PLAYER OF THE MATCH</label>
                                <h4 class="win_team">{{ Helper::getUserDetails($match_data[0]['player_of_the_match'])->name }}</h4>
                            </div>
                        @endif
                    @else
                        @if($match_data[0]['is_tied']>0)

                            <div class="form-group">
                                <label>Match Result : </label>
                                <h3 class="win_team">{{ 'Tie' }}</h3>

                            </div>

                        @elseif($match_data[0]['match_result'] == "washout")
                            <div class="form-group">
                                <label>MATCH ENDED DUE TO</label>
                                <h3 class="win_team">Washout</h3>
                            </div>
                        @else

                            <div class="form-group">
                                <label>Winner has not been updated</label>

                            </div>
                        @endif
                    @endif
                    <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
                    @include('scorecards.share')
                    <p class="match-status">@include('scorecards.scorecardstatusview')</p>
                </div>
            </div>
        </div>

        @if(!$match_data[0]['hasSetupSquad'])
            <div class="row">
                <!-- Selecting Squads Start-->
                <div class="col-sm-8 col-sm-offset-2">
                    <h3 class="team_fall table_head">Playing Squad</h3>
                    <div class='row'>
                        <div class='col-sm-6 col-xs-12'>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="thead">
                                    <tr class='team_fall team_title_head'>
                                        <th>{{$team_a_name}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="team_tr_a" >
                                    @foreach($team_a_players as $player_a)
                                        <tr class="team_a_playing_row playing_a_{{$player_a['id']}}" >
                                            <td class="option block select_player_squad" player_type='playing' team_type="team_a"  player_id="{{$player_a['id']}}" style="cursor: pointer; background: none;">
                                                {{ $player_a['name']   }}
                                                {!!ScoreCard::display_role($player_a['id'], $team_a_id)!!}
                                                <span class='pull-right icon-check'>   </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class='col-sm-6 col-xs-12'>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="thead">
                                    <tr class='team_bat'>
                                        <th>{{$team_b_name}}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="team_tr_b" >
                                    @foreach($team_b_players as $player_b)
                                        <tr class="team_b_playing_row playing_b_{{$player_b['id']}} player_details_{{$player_b['id']}}">
                                            <td class="option block select_player_squad" player_type='playing' team_type="team_b" player_id="{{$player_b['id']}}">
                                                {{ $player_b['name']   }}
                                                {!!ScoreCard::display_role($player_b['id'], $team_b_id)!!}
                                                <span class="pull-right icon-check"> </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @else

            <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <form id='smiteForm' onsubmit='return manualScoring(this)'>
                        {!!csrf_field()!!}
                        @if($isValidUser)
                            <div class="row">
                                <div class='col-sm-12'>
                                    <span class='pull-right'>
                                    <a href='javascript:void(0)' onclick="enableManualEditing(this)"
                                       style="color:#123456;">edit <i class='fa fa-pencil'></i></a>
                                    <span> &nbsp; &nbsp; </span>
                                    <a href='javascript:void(0)' onclick="updatePreferences(this)"
                                       style='color:#123456;'> settings <i class='fa fa-gear fa-danger'></i></a>
                                    <span> &nbsp; &nbsp; </span>
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class='row'>
                            <div class='col-sm-12'>
                                <div class='table-responsive'>
                                    @if(count($smite_match_stats) > 0)
                                    <table class='table table-striped table-bordered'>
                                        <thead>
                                            <tr class='team_fall team_title_head'>
                                                <th bgcolor="#84cd93"></th>
                                                @foreach($team_a_players as $player)
                                                    <th bgcolor="#fff" style="color: #84cd93;" >{{$player['name']}}</th>
                                                @endforeach

                                                @foreach($team_b_players as $player)
                                                    <th bgcolor="#84cd93">{{$player['name']}}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($smite_match_stats[0] as $key=>$val)
                                            @if($key == 'user_id')
                                                <?php continue; ?>
                                            @endif
                                            <tr>
                                                <td>{{$key}}</td>
                                                @foreach($team_a_players as $player)
                                                    <?php $found = false; ?>
                                                    <!-- Connect person with stats -->
                                                    @foreach($smite_match_stats as $smite_match)
                                                        @if($smite_match['user_id'] == $player['id'])
                                                        <td>
                                                            <input readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="{{$smite_match[$key]}}" name='{{$key}}_{{$player['id']}}'>
                                                        </td>
                                                        <?php $found = true; ?>
                                                        @endif
                                                    @endforeach
                                                    <!-- If stats were not saved -->
                                                    @if(!$found)
                                                        <td>
                                                            <input readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='{{$key}}_{{$player['id']}}'>
                                                        </td>
                                                    @endif
                                                @endforeach

                                                @foreach($team_b_players as $player)
                                                    <?php $found = false; ?>
                                                    @foreach($smite_match_stats as $smite_match)
                                                        <!-- Connect person with stats -->
                                                        @if($smite_match['user_id'] == $player['id'])
                                                         <td>
                                                            <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="{{$smite_match[$key]}}" name='{{$key}}_{{$player['id']}}'>
                                                         </td>
                                                                <?php $found = true; ?>
                                                        @endif
                                                    @endforeach
                                                    <!-- If stats were not saved -->
                                                    @if(!$found)
                                                        <td>
                                                            <input readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='{{$key}}_{{$player['id']}}'>
                                                        </td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <table class='table table-striped table-bordered'>
                                        <thead>
                                        <tr class='team_fall team_title_head'>
                                            <th bgcolor="#84cd93"></th>
                                            @foreach($team_a_players as $player)
                                                <th bgcolor="#fff" style="color: #84cd93;" >{{$player['name']}}</th>
                                            @endforeach

                                            @foreach($team_b_players as $player)
                                                <th bgcolor="#84cd93">{{$player['name']}}</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Level</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Level_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                <td>
                                                    <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Level_{{$player['id']}}'>
                                                </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Kills</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Kills_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Kills_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Deaths</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Deaths_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Deaths_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Assists</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Assists_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Assists_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Gold Earned</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Gold Earned_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Gold Earned_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Gold Per Minute</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Gold Per Minute_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Gold Per Minute_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Magical Damage</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Magical Damage_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Magical Damage_{{$player['id']}} '>
                                                    </td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Physical Damage</td>
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Physical Damage_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                                @foreach($team_b_players as $player)
                                                    <td>
                                                        <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set" value="-" name='Physical Damage_{{$player['id']}}'>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                     @endif
                                </div>


                                <input type='hidden' value="{{$match_data[0]['id']}}" name='match_id'>
                                <!--
                                <div class="row" id='saveButton'>
                                    <div class='col-sm-12'>
                                        <center> <input type='submit' class="btn btn-primary" value="Save"></center>
                                    </div>
                                </div>
                                -->

                                <div class="row" id='saveButton'>
                                    <div class='col-sm-12'>
                                        <center> <input type='submit' class="btn btn-primary" value="Save"></center>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                    </form>
                <!-- Selecting Squads Start-->
                    <div class="col-sm-10 col-sm-offset-1">
                        <h3 class="team_bat team_title_head">Playing Squad</h3>

                        <div class='row'>
                            <div class='col-sm-6 col-xs-12'>
                                <div class="table-responsive">
                                    <table class="table table-striped">

                                        <tbody id="team_tr_a" >
                                        @foreach($team_a_players as $player_a)
                                            <tr class="team_a_playing_row " >
                                                <td>
                                                    {{ $player_a['name']   }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class='col-sm-6 col-xs-12'>

                                <div class="table-responsive">
                                    <table class="table table-striped">

                                        <tbody id="team_tr_b" >
                                        @foreach($team_b_players  as $player_b)
                                            <tr class="team_b_playing_row ">
                                                <td>
                                                    {{ $player_b['name']   }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Team A Goals End-->

                    @if(!empty($match_data[0]['match_report']))

                        <div class="clearfix"></div>
                        <div id="match_report_view" class="summernote_wrapper tab-content col-sm-10 col-sm-offset-1">
                            <h3 class="brown1 table_head brown1">Match Report</h3>
                            <div id="match_report_view_inner">
                                {!!$match_data[0]['match_report']!!}
                            </div>
                        </div>
                    @endif
                <!-- if match schedule type is team -->

                    <!-- end -->

                    <div class="sportsjun-forms text-center scorecards-buttons">
                        <input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
                        @if($isValidUser && $isApproveRejectExist)

                            <button style="text-align:center;" type="button" onclick="scoreCardStatus('approved');" class="button green">Approve</button>
                            <button style="text-align:center;" type="button" onclick="scoreCardStatus('rejected');" class="button black">Reject</button><br />

                            <textarea name="rej_note" id="rej_note" rows="4" cols="50" placeholder="Reject Note" style="margin:20px 0 10px 0;"></textarea>
                        @endif
                    </div>

                    @if($isValidUser && $match_data[0]['match_status']=='completed')
                        <div class="sportsjun-forms text-center scorecards-buttons">
                            <input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
                            <br><br>

                            <button class="btn btn-event" type="button" onclick="return  SJ.SCORECARD.allow_match_edit_by_admin({{$match_data[0]['id']}})">
                                Edit Match
                            </button>

                        </div>
                    @endif
                </div>

                <!-- Scoring form -->
                <div id="end_match" class="modal fade">
                    {!! Form::open(array('url' => '', 'method' => 'POST','id'=>'endMatchSmite', 'onsubmit'=>'return endMatchSmite(this)')) !!}
                    <div class="modal-dialog sj_modal sportsjun-forms">
                        <div class="modal-content">
                            <div class="alert alert-danger" id="div_failure1"></div>
                            <div class="alert alert-success" id="div_success1" style="display:none;"></div>
                            <div class="modal-body">
                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="section">
                                            <div class="form-group">
                                                <label for="match_result">End of Match Result:</label>
                                                <select class="form-control " name="match_result" id="match_result" onchange="getTeam();SJ.SCORECARD.selectMatchType(this)">
                                                    <option value="" >Select</option>
                                                    <?php if(empty($match_data[0]['tournament_round_number'])) { ?>
                                                    <option <?php if($match_data[0]['is_tied']>0) echo " selected";?> value="tie" >Tie</option>
                                                    <?php } ?>
                                                    <option value="walkover" {$match_data[0]['match_result']=='walkover'?'selected':''}} >Walkover</option>
                                                    <option {{$match_data[0]['match_result']=='win'?'selected':''}}  value="win">Win</option>
                                                    <option value="washout" {{$match_data[0]['match_result']=='washout'?'selected':''}}>No Result</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="section">
                                            <div class="form-group scorescard_stats" >
                                                <label class="show_teams">Select Winner:</label>
                                                <select name="winner_id" id="winner_id" class="show_teams form-control " onchange="selectWinner();">
                                                    <option <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['a_id']) echo ' selected';?> value="{{ $match_data[0]['a_id'] }}" >{{ $team_a_name }}</option>
                                                    <option <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['b_id']) echo ' selected';?> value="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--********* MATCH REPORT Start **************!-->
                                <div class="summernote_wrapper form-group">
                                    <h3 class="brown1 table_head">Match Report</h3>
                                    <textarea id="match_report" class="summernote" name="match_report" title="Match Report"></textarea>
                                </div>
                            </div>
                            <!--********* MATCH REPORT End **************!-->

                            <div class="modal-footer">
                                <button class='btn btn-primary end_match_btn_submit' onclick="" type='submit'> Save</button>
                                <button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>


                    <input type='hidden' id='selected_player_id_value' value='0' player_id='0' player_name=''>
                    <input type='hidden' id='half_time' value='quarter_1'>
                    <input type='hidden' id='selected_team_type' value='team_a'>
                    <input type='hidden' id='last_index' value="0" name='last_index'>
                    <input type="hidden" id="volleyball_form_data" value="">
                    <input type="hidden" name="tournament_id" value="{{ $match_data[0]['tournament_id'] }}">
                    <input type="hidden" name="team_a_id" value="{{ $match_data[0]['a_id'] }}" id="team_a_id">
                    <input type="hidden" name="team_b_id" value="{{ $match_data[0]['b_id'] }}" id="team_b_id">
                    <input type="hidden" name="match_id" id='match_id' value="{{ $match_data[0]['id'] }}">
                    <input type="hidden" name="team_b_name" value="{{ $team_b_name }}" id="team_b_name">
                    <input type="hidden" name="team_a_name" value="{{ $team_a_name }}" id="team_a_name">
                    <input type="hidden" name="winner_team_id" value="" id="winner_team_id">
                    </form>
            </div>

             @endif

            <div class="sportsjun-forms text-center scorecards-buttons">
                <center>
                    <ul class="list-inline sportsjun-forms">
                        @if($isValidUser)
                            <li>
                                @if(!$match_data[0]['hasSetupSquad'])
                                    <button type='button' class='btn-danger btn .' onclick="return confirmSquad()"><i class="fa fa-floppy-o"></i> Confirm Squad</button>
                                @else
                                @endif
                            </li>
                        @else
                            <li>
                               Squad is yet to be decided.
                            </li>
                        @endif
                    </ul>
                </center>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    //Send Approve
    var manual = false;

    function enableManualEditing(that)
    {
        if(!manual){
            $.confirm({
                title: "Alert",
                content: "Do you want to enter points manually?",
                confirm: function(){
                    $('.tennis_input_new').removeAttr('readonly');
                    $('.tennis_input_new').focus();
                    $('#real_time_scoring').hide();
                    $('#end_match_button').hide();
                    $('#saveButton').show();
                    manual = true;
                },
                cancel: function(){

                }
            })

        }
        else
        {
            $.confirm({
                title: "Alert",
                content: "Do you want to enter points automatically?",
                confirm:function(){
                    $('.tennis_input_new').attr('readonly', 'readonly');
                    $('#real_time_scoring').show();
                    $('#end_match_button').show();
                    $('#saveButton').hide();
                    manual=false;
                },
                cancel:function(){

                }
            })
        }
    }

    function scoreCardStatus(status)
    {
        var msg = ' Reject ';
        if(status=='approved')
            var msg = ' Approve ';
        $.confirm({
            title: 'Confirmation',
            content: 'Are You Sure You Want To '+msg+' This ScoreCard?',
            confirm: function() {
                match_id = $('#match_id').val();
                rej_note = $('#rej_note').val();
                $.ajax({
                    url: base_url+'/match/scoreCardStatus',
                    type: "post",
                    data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'volleyball'},
                    success: function(data) {
                        if(data.status == 'success') {
                            window.location.href = base_url+'/match/scorecard/edit/'+match_id;
                        }
                    }
                });
            },
            cancel: function() {
                // nothing to do
            }
        });
    }


    function getMatchDetails(){

        var data={
            match_id:{{$match_data[0]['id']}}
        }

        var base_url=base_url || secure_url;
        $.ajax({
            url:  base_url+'/viewpublic/match/getvolleyballDetails',
            type:'get',
            dataType:'json',
            data:data,
            success:function(response){
                var left_team_id=response.preferences.left_team_id;
                var right_team_id=response.preferences.right_team_id;

                $.each(response.match_details, function(key, value){
                    $('.a_'+key).html(value[left_team_id+'_score']);
                    $('.b_'+key).html(value[right_team_id+'_score']);
                })
            }
        })
    }

    function manualScoring(that){
        var data=$('#smiteForm').serialize();
        console.log(data);
        $.ajax({
            url:base_url+"/match/manualScoringSmite",
            type:'post',
            data:data,
            success:function(response){
               // window.location=window.location;
            }
        })


        return false;
    }
</script>

<script>
    $(document).ready(function(){
        $('.select_player_squad').css({cursor:'pointer', background:'none'});
        $('.not_playing').css({ background:'#f9f9f9', cursor:'text'});

        $('.select_player').css({cursor:'pointer'})
        window.tempSquadData={
            team_a:{
                playing:[],
                substitute:[]
            },
            team_b:{
                playing:[],
                substitute:[]
            },
            match_id: {{$match_id}},
            team_a_id: {{$team_a_id}},
            team_b_id: {{$team_b_id}},
            team_a_name:'{{$team_a_name}}',
            team_b_name:'{{$team_b_name}}',
            preferences:{
                number_of_quarters:0,
                quarter_time:0,
                max_fouls:0
            },
            tournament_id:'{{$tournament_id}}'
        }



        //console.log(window.tempSquadData);

        Array.prototype.remove = function() {
            var what, a = arguments, L = a.length, ax;
            while (L && this.length) {
                what = a[--L];
                while ((ax = this.indexOf(what)) !== -1) {
                    this.splice(ax, 1);
                }
            }
            return this;
        };
        $('.select_player_squad').click(function(){
            var player_id=$(this).attr('player_id');
            var team_type=$(this).attr('team_type');
            var player_type=$(this).attr('player_type');

            if($(this).hasClass('player_selected'))$(this).removeClass('player_selected').children('.icon-check').html("");
            else {
                $(this).addClass('player_selected').children('.icon-check').html("<i class='fa fa-check'></i>");
            }

            if(!$(this).hasClass('choosen')){
                $(this).addClass('choosen')
                if($(this).parents('tr').hasClass('playing_a_'+player_id))$('.substitute_a_'+player_id).fadeOut();
                if($(this).parents('tr').hasClass('playing_b_'+player_id))$('.substitute_b_'+player_id).fadeOut();
                if($(this).parents('tr').hasClass('substitute_a_'+player_id))$('.playing_a_'+player_id).fadeOut();
                if($(this).parents('tr').hasClass('substitute_b_'+player_id))$('.playing_b_'+player_id).fadeOut();
                if(player_type=='playing'){
                    tempSquadData[team_type].playing.push(player_id);
                }
                else{
                    tempSquadData[team_type].playing.remove(player_id);
                }
            }
            else{
                $(this).removeClass('choosen')
                if($(this).parents('tr').hasClass('playing_a_'+player_id))$('.substitute_a_'+player_id).fadeIn();
                if($(this).parents('tr').hasClass('playing_b_'+player_id))$('.substitute_b_'+player_id).fadeIn();
                if($(this).parents('tr').hasClass('substitute_a_'+player_id))$('.playing_a_'+player_id).fadeIn();
                if($(this).parents('tr').hasClass('substitute_b_'+player_id))$('.playing_b_'+player_id).fadeIn();
                if(player_type=='playing'){
                    tempSquadData[team_type].playing.remove(player_id);
                }
                else {
                }
            }
        })
    })

    function confirmSquad(){
        //get the total players for each team
        var total_players_a=$('#total_players_a').val();
        var total_players_b=$('#total_players_b').val();
        //get the playing players for each team
        var playing_players_a=tempSquadData.team_a.playing.length;
        var playing_players_b=tempSquadData.team_b.playing.length;

        if(playing_players_a != playing_players_b){
            $.alert({
                title:"Alert",
                content:"Size of team should be the same on both sides."
            })
            return false;
        }

        if(!(playing_players_a == "3" || playing_players_a == "5")){
            $.alert({
                title:"Alert",
                content:"Size of team should be 3 or 5."
            })
            return false;
        }

        $.confirm({
            title:"Alert",
            content:"Are you sure you want to save squad?",
            confirm: function() {
                console.log(base_url+'/match/confirmSmitePlayingTeam');
                $(this).attr('disabled', true);
                $.ajax({
                    url:base_url+'/match/confirmSmitePlayingTeam',
                    data:tempSquadData,
                    type:'post',
                    success:function(response){
                       window.location=window.location;
                    },
                    error:function(x,y,z){
                        $(this).attr('disabled', false);
                    }
                })
            },
            cancel:function(){

            }
        });

        return false;
    }

    function saveMatchDetails(){
        var data=$('#volleyball').serialize();
        $.ajax({
            url:base_url+'/match/insertAndUpdatevolleyballCard',
            data:data,
            method:'post',
            success:function(response){
                window.location=window.location.pathname;
            },
            error:function(x,y,z){

            }

        })
        return false;
    }



    function getvolleyballDetails(){
        //load details
        var data={
            match_id:$('#match_id').val(),
            team_a_id:{{$team_a_id}},
            team_b_id:{{$team_b_id}}
        }

        $.ajax({
            url:base_url+'/match/getvolleyballDetails',
            method:'get',
            data:data,
            success:function(response){
                $('#match_details').html(response);
            }
        })
    }

    function getTeam()
    {
        var value = $( "#match_result" ).val();
        if(value=='win' || value=='walkover')
        {
            $(".show_teams").show();
            selectWinner();
        }else
        {
            $(".show_teams").hide();

            $('#winner_team_id').val('');
        }
    }
    function selectWinner()
    {
        $('#winner_team_id').val($('#winner_id').val());
        //$("#winner_id").hide();
    }
    allownumericwithdecimal();
    checkDuplicatePlayers('select_player_a');
    checkDuplicatePlayers('select_player_b');

    function allownumericwithdecimal()
    {
        $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
            $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if (event.which != 08 && (event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

    }

    //check duplicate players selected
    function checkDuplicatePlayers(select_class)
    {
        $('.'+select_class).on('change',function(){
            // Checking Duplicate players
            var pid=[];
            $('.'+select_class).each(function(){
                if(this.value != ''){
                    pid.push(this.value);
                }

            });
            b = {};
            for (var i = 0; i < pid.length; i++) {
                b[pid[i]] = pid[i];
            }
            c = [];
            for (var key in b) {
                c.push(key);
            }
            if(pid.length!=c.length){

                //alert("Duplicate Player Selected.");
                $.alert({
                    title: 'Alert!',
                    content: 'Duplicate Player Selected.'
                });
                $(this).val('');

            }
        });
    }

    function endMatchSmite(that)
    {
        var data=$('#endMatchSmite').serialize();

        $.ajax({
            url:base_url+"/match/endMatchSmite",
            type:'post',
            data:data,
            success:function(response){
               window.location=window.location;
            }
        });

        return false;
    }


</script>
<!-- Put plus and minus buttons on left and rights of sets -->

@endsection

