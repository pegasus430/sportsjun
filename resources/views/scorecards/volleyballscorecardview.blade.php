@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
    <?php $team_a_count = 'Red Card Count:'.$team_a_red_count.','.'Yellow Card Count:'.$team_a_yellow_count;?>
    <?php $team_b_count = 'Red Card Count:'.$team_b_red_count.','.'Yellow Card Count:'.$team_b_yellow_count;
    $team_a_id = $match_data[0]['a_id']; $team_b_id= $match_data[0]['b_id'] ;
    $player_of_the_match=$player_of_the_match==NULL? 0 : $player_of_the_match;?>

   <?php $team_a_id = $match_data[0]['a_id']; $team_b_id= $match_data[0]['b_id'] ; $match_id=$match_data[0]['id'];
    ?>
    <?php
    $match_details=json_decode($match_data[0]['match_details']);
    $preferences=isset($match_details->preferences)?$match_details->preferences:[];
    $a_points=0;
    $b_points=0;
    $a_fouls=0;
    $b_fouls=0;

    if(isset($preferences->number_of_quarters)){
        $number_of_quarters=$preferences->number_of_quarters;
        $quarter_time=$preferences->quarter_time;
        $max_fouls=$preferences->max_fouls;

        $a_points=$match_details->{$team_a_id}->total_points;
        $b_points=$match_details->{$team_b_id}->total_points;

        $a_fouls=$match_details->{$team_a_id}->fouls;
        $b_fouls=$match_details->{$team_b_id}->fouls;       
    }
    
    ?>

    <style type="text/css">
        .alert{display: none;}
        .show_teams{display: none;}
        .player_selected{
            background: #111111;
            background-color: red;
        }
        .fouls{
            color:red;
            font: 23px;
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
                                        <!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/no_logo.png') }}';">-->
                                        {!! Helper::Images($team_a_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                    @else
                                        <!--    <img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">-->
                                        {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                    @endif
                                @else
                                    <!--    <img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">  -->
                                        {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="team_detail">
                                    <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $team_a_name }}</a></div>
                                    <div class="team_city">{{ $team_a_city }}</div>
                                    <div class="team_score" id="team_a_score">{{$a_points}} <span id='team_a_fouls'  class='fouls'>{{$a_fouls}}</span></div>
                                    
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
                                    <div class="team_score" id="team_b_score">{{$b_points}} <span id='team_b_count' class='fouls' >{{$b_fouls}}</span></div>
                                    
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="team_logo">
                                @if(!empty($team_b_logo))
                                    @if($team_b_logo['url']!='')
                                        <!--    <img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/no_logo.jpg') }}';">-->
                                        {!! Helper::Images($team_b_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                    @else
                                        <!--<img  class="img-responsive img-circle" height="110" width="110" src="{{ asset('/images/no_logo.jpg') }}">-->
                                            {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                            </td>
                                    @endif
                                @else
                                    <!--<img  class="img-responsive img-circle" height="110" width="110" src="{{ asset('/images/no_logo.png') }}">  -->
                                        {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12 visible-xs visible-sm">
                                <div class="team_detail">
                                    <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
                                    <div class="team_city">{{ $team_b_city }}</div>
                                    <div class="team_score" id="team_b_score">{{$b_points}} <span id='team_b_count' class='fouls' >{{$b_fouls}}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                 @if(!is_null($match_data[0]['tournament_id']))
                <div class='row'>
                    <div class='col-xs-12'>
                        <div class='match_loc'>
                            {{$tournamentDetails['name']}} Tournament
                                
                        </div>
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

            <div class="row">
                <div class="col-md-12">
                    <h5 class="scoreboard_title">Basketball Scorecard
                    @if(!empty($match_data[0]['match_category']))
                             <span class='match_type_text'>
                             ({{ucfirst($match_data[0]['match_category']) }})
                             </span>
                                @endif</h5>

                    <div class="clearfix"></div>
                    <div class="form-inline">
                        @if($match_data[0]['match_status']=='completed' && $match_data[0]['winner_id']>0)

                            <div class="form-group">
                                <label class="win_head">Winner</label>
                                <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>


                            </div>

                        @else
                            @if($match_data[0]['match_status']=='completed' && $match_data[0]['is_tied']>0)
                                <div class="form-group">
                                    <label>Match Result : </label>
                                    {{ 'Tie' }}

                                </div>
                            @else
                                <p></p>
                                <br>
                                @if($match_data[0]['hasSetupSquad'])
                                    <button class="btn btn-danger " onclick="return SJ.SCORECARD.soccerSetTimes(this)"></i>End Match</button>
                                @endif
                 @if($isValidUser && $isForApprovalExist && ($match_data[0]['winner_id']>0 || $match_data[0]['is_tied']>0 || $match_data[0]['has_result'] == 0))  

                    <button style="text-align:center;" type="button" onclick="forApproval();" class="btn btn-primary">Send Score for Approval</button>

                @endif

                            @endif
                        @endif
                        <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
                        @include('scorecards.share')
                        <p class="match-status">@include('scorecards.scorecardstatus')</p>
                    </div>
                </div>
            </div>

    <!-- Match has no results -->
    <div class="row">
                    <!-- Team A Goals Start-->
                    <div class="col-sm-10 col-lg-10 col-sm-offset-1">
 

                        <div class='row'>
                            <div class='col-sm-12'>
                                <h3 id='team_a' class="team_bat team_title_head">{{$team_a_name}}</h3>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="thead ">
                                            <tr>
                                                <th  >Player</th>
                                                <th >Qtr Halves</th>
                                                <th >Fouls</th>
                                                
                                        @for($index=1; $index<=$number_of_quarters; $index++)
                                                <th>Quarter {{$index}}</th>
                                        @endfor
                                                

                                            </tr>
                                        </thead>
                                        <tbody id="team_tr_a" >
                                            @foreach($team_a_basketball_scores_array as $player)

                                        <tr id="team_a_row_{{$player['id']}}">

                                                <td>
                                                        {{$player['player_name']}}                              
                                                </td>
                                                 <td>
                                                        {{$player['quarters_played']}}
                                                    </td>
                                              
                                                <td class="{{$player['id']}}_faults">
                                                        {{$player['fouls']}}
                                                </td>

                                              @for($index=1; $index<=$number_of_quarters; $index++)
                                                <td>{{$player['quarter_'.$index]}}</td>
                                              @endfor
                                         </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <!-- End LineUp Team A -->
                            <!-- Start LineUp Team B -->
                            <div class='col-sm-12'>
                                <h3 id='team_a' class="team_fall team_title_head">{{$team_b_name}}</h3>
                                <div class="table-responsive">
                                    <table class="table table-striped"> 
                                        <thead class="thead ">
                                            <tr>
                                                <th >Player</th>
                                                <th >Qtr Halves</th>
                                                <th >Fouls</th>                                         
                                                
                                        @for($index=1; $index<=$number_of_quarters; $index++)
                                                <th>Quarter {{$index}}</th>
                                        @endfor


                                            </tr>
                                        </thead>                                    
                                        <tbody id="team_tr_b" >
                                    
                                            @foreach($team_b_basketball_scores_array as $player)

                                        <tr>

                                                <td  id="player_lineup_{{$player['id']}}">
                                                        {{$player['player_name']}}                              
                                                </td>

                                                <td>
                                                   {{$player['quarters_played']}}</td>
                                           
                                                <td class="{{$player['id']}}_faults">
                                                         {{$player['fouls']}}
                                                </td>

                                              @for($index=1; $index<=$number_of_quarters; $index++)
                                                <td>{{$player['quarter_'.$index]}}</td>
                                              @endfor
                                         </tr>
                                    

                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>

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