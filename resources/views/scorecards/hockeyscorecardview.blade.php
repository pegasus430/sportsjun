@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
    <?php $team_a_count = 'Red Card Count:'.$team_a_red_count.','.'Yellow Card Count:'.$team_a_yellow_count;?>
    <?php $team_b_count = 'Red Card Count:'.$team_b_red_count.','.'Yellow Card Count:'.$team_b_yellow_count;
    $team_a_id = $match_data[0]['a_id']; $team_b_id= $match_data[0]['b_id'] ;
    $player_of_the_match=$player_of_the_match==NULL? 0 : $player_of_the_match;?>

    <?php
    $match_details=json_decode($match_data[0]['match_details']);
    $first_half=isset($match_details->first_half)?$match_details->first_half:[];
    $second_half=isset($match_details->second_half)?$match_details->second_half:[];
    $penalties=isset($match_details->penalties)?json_decode(json_encode($match_details->penalties), true):[];
    $ball_percentage_a=isset($match_details->{$team_a_id}->ball_percentage)?$match_details->{$team_a_id}->ball_percentage:50;
    $ball_percentage_b=isset($match_details->{$team_b_id}->ball_percentage)?$match_details->{$team_b_id}->ball_percentage:50;
    ?>

    <style type="text/css">
        .alert{display: none;}
        .show_teams{display: none;}
        .player_selected{
            background: #111111;
            background-color: red;
        }
        .btn-yellow-card{
            background: #f4cd73;
            border: none;
        }
        .btn-red-card{
            background: #f42d23;
        }
        .btn-green-card{
            background: #84cd93;
        }
        .btn-card{
            border: none;
        }
        .lose_goal{
            opacity: .2;
        }
        .fa-share{
            color: red;
        }
        .fa-reply{
            color: green;
        }

        .btn-penalty{
            opacity: .2;
        }
        .btn-green-card{
            background: #1B926C;
        }
        .btn-penalty-chosen{
            opacity: 1;
        }



    </style>
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
                                    <div class="team_score" id="team_a_score">{{$team_a_goals}} <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_a_count;?>"></i></span></div>

                                    @if(isset($penalties['team_a']['players']) && count($penalties['team_a']['players'])>0 )
                                        <div class='team_city'>  Penalties:  {{$penalties['team_a']['goals']}}</div>
                                    @endif
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
                                    <div class="team_score" id="team_b_score">{{$team_b_goals}} <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_b_count;?>"></i></span></div>
                                    @if(isset($penalties['team_b']['players']) && count($penalties['team_b']['players'])>0 )
                                        <div class='team_city'>  Penalties:    {{$penalties['team_b']['goals']}}</div>
                                    @endif
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
                                    <div class="team_score" id="team_b_score">{{$team_b_goals}} <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_b_count;?>"></i></span></div>


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
                                    <h4>    {{$tournamentDetails['name']}} Tournament </h4>
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
                    <h5 class="scoreboard_title">Hockey Scorecard
                            @if(!empty($match_data[0]['match_category']))
                             <span class='match_type_text'>
                             ({{ucfirst($match_data[0]['match_category']) }})
                             </span>
                                @endif
                    </h5>

                    <div class="clearfix"></div>
                    <div class="form-inline">
                        @if($match_data[0]['winner_id']>0)

                            <div class="form-group">
                                <label class="win_head">Winner</label>
                                <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>
                            </div>
                            <BR>
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
                                    <label>Winner is Not Updated</label>

                                </div>
                            @endif
                        @endif
                        <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
                        @include('scorecards.share')
                        <p class="match-status">@include('scorecards.scorecardstatusview')</p>
                    </div>
                </div>
            </div>
    <!-- Match has no results -->
@if(!$match_data[0]['has_result'])
    {{--
        <div class='row' >
            <div class="col-sm-8 col-sm-offset-2" style="background:#ffeeee">
                
                    <div class='col-sm-12'>
                @if($match_data[0]['scoring_status']!='approved')
                    This match has  been saved as 'no result'. All the changes and records for this match shall be discarded after approval.
                @else
                  
                @endif

                </div>
            
            
            </div>
        </div>  
    --}}
@endif


            <!-- Lineup and substitutes -->
            <div class="row">
                <!-- Selecting Squads Start-->
                <div class="col-sm-10 col-sm-offset-1">
                    <h3 class="team_bat team_title_head">Playing Squad</h3>


                    <div class='row'>
                        <div class='col-sm-6 col-xs-12'>
                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <tbody id="team_tr_a" >
                                    @foreach($team_a_hockey_scores_array  as $player_a)
                                        @if($player_a['playing_status']=='P' && $player_a['red_cards']==0)
                                            <tr class="team_a_playing_row " >
                                                <td>
                                                    {{ $player_a['player_name']   }} {!!$player_a['has_substituted']?" <i class='fa fa-reply'></i> {$player_a['time_substituted']}\"":''!!}
                                                </td>
                                                <td>{!!$player_a['goals_scored']>0?" {$player_a['goals_scored']} <img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'> ":'' !!}</td>
                                                <td>   {!!$player_a['yellow_cards']>0?" {$player_a['yellow_cards']} <button class='btn-yellow-card btn-card' disabled=''>&nbsp;</button> ":'' !!}</td>
                                                <td>   {!! $player_a['red_cards']>0?" {$player_a['red_cards']} <button class='btn-red-card btn-card' disabled=''>&nbsp;</button >":'' !!} </td>

                                            </tr>
                                        @endif
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class='col-sm-6 col-xs-12'>

                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <tbody id="team_tr_b" >
                                    @foreach($team_b_hockey_scores_array  as $player_b)
                                        @if($player_b['playing_status']=='P' && $player_b['red_cards']==0)
                                            <tr class="team_b_playing_row ">

                                                <td>
                                                    {{ $player_b['player_name']   }} {!!$player_b['has_substituted']?"<i class='fa fa-reply'></i> {$player_b['time_substituted']}\"":''!!}
                                                </td>
                                                <td>{!!$player_b['goals_scored']>0?" {$player_b['goals_scored']} <img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'> ":'' !!}</td>
                                                <td>   {!!$player_b['yellow_cards']>0?" {$player_b['yellow_cards']} <button class='btn-yellow-card btn-card' disabled=''>&nbsp;</button> ":'' !!}</td>
                                                <td>    {!!$player_b['red_cards']>0?" {$player_b['red_cards']} <button class='btn-red-card btn-card' disabled=''>&nbsp;</button> ":'' !!}</td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Team A Goals End-->

                <div class="col-sm-10 col-sm-offset-1">
                    <h3 id='team_' class="team_fall team_title_head">Substitute Squad</h3>

                    <div class='row'>
                        <div class='col-sm-6 col-xs-12'>
                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <tbody id="team_tr_a" >
                                    @foreach($team_a_hockey_scores_array  as $player_a)
                                        @if($player_a['playing_status']=='S' || $player_a['red_cards']>0)
                                            <tr class="team_a_playing_row  ">
                                                <td>
                                                    {{ $player_a['player_name']   }}  {!! $player_a['has_substituted']?"<i class='fa fa-share'></i> {$player_a['time_substituted']}\"":''!!}
                                                </td>
                                                <td>{!!$player_a['goals_scored']>0?" {$player_a['goals_scored']} <img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'> ":'' !!}</td>
                                                <td>   {!!$player_a['yellow_cards']>0?" {$player_a['yellow_cards']} <button class='btn-yellow-card btn-card' disabled=''>&nbsp;</button> ":'' !!}</td>
                                                <td>    {!!$player_a['red_cards']>0?" {$player_a['red_cards']} <button class='btn-red-card btn-card' disabled=''>&nbsp;</button> ":'' !!}</td>

                                            </tr>
                                        @endif
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class='col-sm-6 col-xs-12'>

                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <tbody id="team_tr_b" >
                                    @foreach($team_b_hockey_scores_array  as $player_b)
                                        @if($player_b['playing_status']=='S' || $player_b['red_cards']>0)
                                            <tr class="team_a_playing_row">
                                                <td>
                                                    {{ $player_b['player_name']   }} {!!$player_b['has_substituted']?"<i class='fa fa-share'></i> {$player_b['time_substituted']}\"":''!!}
                                                </td>
                                                <td>{!!$player_b['goals_scored']>0?" {$player_b['goals_scored']} <img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'> ":'' !!}</td>
                                                <td>   {!!$player_b['yellow_cards']>0?" {$player_b['yellow_cards']} <button class='btn-yellow-card btn-card' disabled=''>&nbsp;</button> ":'' !!}</td>
                                                <td>    {!!$player_b['red_cards']>0?" {$player_b['red_cards']} <button class='btn-red-card btn-card' disabled=''>&nbsp;</button> ":'' !!}</td>

                                            </tr>
                                        @endif
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-sm-10 col-sm-offset-1">
                    <h3 id='team_b' class="team_bowl table_head">MATCH STATITICS</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr class='team_bow'>
                                <th colspan="5"></th>
                            </tr>
                            </thead>
                            
                                <tbody>

                            @if(count($first_half)>0)
                                <tr>
                                    <td colspan="2">{{$match_details->first_half->{"team_{$team_a_id}_goals"} }}</td>
                                    <td class="td_type">Half Time ( <img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'> ) </td>
                                    <td colspan="2">{{$match_details->first_half->{"team_{$team_b_id}_goals"} }}</td>
                                <tr>
                            @endif
                            @if(isset($match_details->{$team_a_id}))
                                <tr>
                                    <td colspan="2">{{$match_details->{$team_a_id}->goals }}</td>
                                    <td class="td_type">Full Time ( <img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'> ) </td>
                                    <td colspan="2">{{$match_details->{$team_b_id}->goals }}</td>
                                <tr>
                                <tr>
                                    <td colspan="2">{{$match_details->{$team_a_id}->red_card_count }}</td>
                                    <td class='td_type'>Red Cards <button class='btn-red-card btn-card' disabled=''>&nbsp;</button>  </td>
                                    <td colspan="2">{{$match_details->{$team_b_id}->red_card_count }}</td>
                                <tr>
                                <tr>
                                    <td colspan="2">{{$match_details->{$team_a_id}->yellow_card_count }}</td>
                                    <td class='td_type'>Yellow Cards <button class='btn-yellow-card btn-card' disabled=''>&nbsp;</button>  </td>
                                    <td colspan="2">{{$match_details->{$team_b_id}->yellow_card_count }}</td>
                                <tr>
                                <tr>
                                    <td colspan="2">{{$ball_percentage_a }} %</td>
                                    <td class='td_type'>Ball Percentage  % </td>
                                    <td colspan="2">{{$ball_percentage_b }} %</td>
                                <tr>
                            @endif
                                </tbody>                           
                        </table>
                    </div>

                </div>
            </div>

            <div class="row">

                <div class="col-sm-10 col-sm-offset-1">
                    <h3 id='' class="team_fall team_title_head ">FULL MATCH DETAILS</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead">
                            <tr>
                                <th class='team_bow' colspan="9" ><center>First Half</center></th>
                            </tr>
                            </thead>
                            <tbody id="displayGoalsFirstHalf" >
                                @if(count($first_half) < 1 )
                                    <tr><td colspan="9">No Records</td></tr>
                                @else
                                    <!-- Goals Display -->
                                    @foreach($first_half->goals_details as $fh)

                                        <tr>
                                            @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                                <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'></td><td>{{$fh->current_score}}</td><td colspan="4">&nbsp;</td>
                                            @else
                                                <td colspan="4">&nbsp;</td><td>{{$fh->current_score}}</td><td><img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'></td><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <!-- Yellow Cards -->
                                    @foreach($first_half->yellow_card_details as $fh)

                                        <tr>
                                            @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                                <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><button class='btn-yellow-card btn-card' disabled="">&nbsp;</button></td><td></td><td colspan="4">&nbsp;</td>
                                            @else
                                                <td colspan="4">&nbsp;</td><td></td><td><button class='btn-yellow-card btn-card' disabled="">&nbsp;</button><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <!-- Red Cards -->
                                    @foreach($first_half->red_card_details as $fh)

                                        <tr>
                                            @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                                <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><button class='btn-red-card btn-card ' disabled="">&nbsp;</button></td><td></td><td colspan="4">&nbsp;</td>
                                            @else
                                                <td colspan="4">&nbsp;</td><td></td><td><button class='btn-red-card btn-card ' disabled="">&nbsp;</button><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                            @endif
                                        </tr>
                                    @endforeach

                                @endif

                            </tbody>

                            <thead class="thead">
                            <tr>
                                <th class='' colspan="9" ><center>Second Half</center></th>
                            </tr>
                            </thead>
                            <tbody id="displayGoalsSecondHalf" >
                            @if(count($second_half) < 1 )
                                <tr><td colspan="9">No Records</td></tr>
                            @else
                                <!-- Goals Display -->
                                @foreach($second_half->goals_details as $fh)

                                    <tr>
                                        @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                            <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'></td><td>{{$fh->current_score}}</td><td colspan="4">&nbsp;</td>
                                        @else
                                            <td colspan="4">&nbsp;</td><td>{{$fh->current_score}}</td><td><img src='/images/scorecard/hockey.png' height='20px' width='20px' style='font-size:32px'></td><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                <!-- Yellow Cards -->
                                @foreach($second_half->yellow_card_details as $fh)

                                    <tr>
                                        @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                            <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><button class='btn-yellow-card btn-card' disabled="">&nbsp;</button></td><td>&nbsp;</td><td colspan="4">&nbsp;</td>
                                        @else
                                            <td colspan="4">&nbsp;</td><td>&nbsp;</td><td><button class='btn-yellow-card btn-card' disabled="">&nbsp;</button><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                <!-- Red Cards -->
                                @foreach($second_half->red_card_details as $fh)

                                    <tr>
                                        @if(isset($fh->team_type) && $fh->team_type=='team_a')
                                            <td colspan="2">{{$fh->player_name}}</td><td>{{$fh->time}}"</td><td><button class='btn-red-card btn-card ' disabled="">&nbsp;</button></td><td></td><td colspan="4">&nbsp;</td>
                                        @else
                                            <td colspan="4">&nbsp;</td><td>&nbsp;</td><td><button class='btn-red-card btn-card ' disabled="">&nbsp;</button><td>{{$fh->time}}"</td><td colspan="2">{{$fh->player_name}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                           
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
            @if(isset($penalties['team_a']['players']) && count($penalties['team_a']['players'])>0)
                <div class="row">
                <div class='col-sm-10 col-sm-offset-1'>

                    <h3 class='team_bowl table_head'  ><center >Penalties</center><h3>

                            <div class='col-sm-6 ' >
                                <div class="table-responsive">
                                    <table class="table table-striped">

                                        @foreach($penalties['team_a']['players'] as $i=>$penalty_player)
                                            <tr>
                                                <td colspan=2>{{$penalty_player['name']}}</td>
                                                <td> 

                        0 <button class="btn-red-card btn-card btn-circle btn-penalty btn_team_a_{{$i}} {{$penalty_player['goal']=='0'?'btn-penalty-chosen':''}} " disabled > </button>

                        1 <button class="btn-green-card btn-card btn-circle btn-penalty btn_team_a_{{$i}} {{$penalty_player['goal']=='1'?'btn-penalty-chosen':''}} "  disabled=""   > </button> 

                        </td>
                                            </tr>
                                            @endforeach

                                            </tbody>
                                    </table>

                                </div>
                            </div>
                            <!-- End LineUp Team A -->
                            <!-- Start LineUp Team B -->
                            <div class='col-sm-6'>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody  >
                                        @foreach($penalties['team_b']['players'] as $i=>$penalty_player)
                                            <tr>
                                                <td colspan=2>{{$penalty_player['name']}}</td>
                                                <td> 

                        0 <button class="btn-red-card btn-card btn-circle btn-penalty btn_team_b_{{$i}} {{$penalty_player['goal']=='0'?'btn-penalty-chosen':''}} " disabled=""  > </button>

                        1 <button class="btn-green-card btn-card btn-circle btn-penalty btn_team_b_{{$i}} {{$penalty_player['goal']=='1'?'btn-penalty-chosen':''}}" disabled=""  > </button> 

                                                    
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>

                                </div>

                            </div>
                </div>
        </div></div>

    @endif


    </thead>
    </table>
    </div>

    </div>

    </div>
    <!-- Team B Goals End-->


    </div>

    <!-- Scoring End -->


    <!-- Team B Goals End-->


    </div>
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

    </div>
    <script>
        //Send Approve
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
                        data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'hockey'},
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

    
    </script>


@endsection