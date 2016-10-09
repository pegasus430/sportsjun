@extends('layouts.app')
@section('content')
@include ('teams._leftmenu')

<div id="content-team" class="col-sm-10">
	<div class="row">
<div class="col-sm-9">
	<div class="team_managers_row clearfix">
		<h4>{{ trans('message.team.stats.stats') }}</h4>    
        @include($statsview)
    </div>
    
    <input type="hidden" name="limit" id="limit" value="{{$limit}}"/>
    <input type="hidden" name="offset" id="offset" value="{{$offset}}"/>
    
    <div id="searchStatsFilterDiv" class="row players_row">
    <form class="form-inline">
        {!! Form::hidden('sportsId', $sportsId, array('id' => 'sportsId')) !!}
        {!! Form::hidden('teamId', $teamId, array('id' => 'teamId')) !!}
        <div class="col-lg-12">
        		<div class="players_row_left"><h4 style="margin-top: 20px;">{{ trans('message.team.stats.gamestats') }}</h4></div>
                <div class="shorting_cal players_row_right">
                <label for="fromDate" class="field prepend-icon">
                	{{ trans('message.team.stats.from') }}
                        <div class='input-group date' id='from_Date'>
                                {!! Form::text('fromDate',date('m/d/Y', strtotime($fromDate)), array('required','class'=>'gui-input','placeholder'=>'From','id'=>'fromDate')) !!}
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                </label>
                <label for="toDate" class="field prepend-icon">
                	{{ trans('message.team.stats.to') }}
                        <div class='input-group date' id='to_Date'>
                                {!! Form::text('toDate',date('m/d/Y', strtotime($toDate)), array('required','class'=>'gui-input','placeholder'=>'To','id'=>'toDate')) !!}
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                        </div>
                </label>
                <span id="submitButtonDiv" class="sf_div" style="width: auto; float: right; margin: 8px 0;"> 
                    <input type="submit" value="{{ trans('message.team.stats.go') }}" class="btn" />
                </span> 
                </div>
        </div>  
    </form>
    </div>
   <br>

        <div class="clearfix"></div>
   	<div class="row">
    <div class="viewmoreclass col-xs-12">
            @if(count($matchScheduleData))
            @foreach($matchScheduleData as $schedule)
            <div id="schedule_{{$schedule['id']}}" class="schedule_list clearfix">
            	<div class="deskview hidden-xs">
                    <div id="teamone_{{$schedule['scheduleteamone']['id']}}" class="col-sm-3 score_view_img">
                        <p><!--<img src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.$schedule['scheduleteamone']['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="30" width="30">-->
                        {!! Helper::Images($schedule['scheduleteamone']['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}</p>
                        @if(isset($schedule['scheduleteamone']['result']))
                            <span>{{ $schedule['scheduleteamone']['result'] }}</span>
                        @endif
                    </div>    
    
                    <div id="center_div" class="col-sm-6">
                        <p class="vs_text">
                            <span>
                                <a href="{{ url('/team/members',[$schedule['a_id']]) }}">
                                {{$schedule['scheduleteamone']['name']}}
                                </a>
                            </span>  
                           vs 
                            <span>@if(isset($schedule['scheduleteamtwo']['name']))
                                    <a href="{{ url('/team/members',[$schedule['b_id']]) }}">{{$schedule['scheduleteamtwo']['name']}}
                                    </a>
                                  @else  
                                    {{trans('message.bye')}}
                                  @endif  
                            </span>
                         </p> 
                       <?php 
                        $schedule['match_start_date'] = trim($schedule['match_start_date']);
                        if (strpos($schedule['match_start_date'], ':') == false)
                        {
                                $schedule['match_start_date'] = DateTime::createFromFormat('d/m/Y', $schedule['match_start_date'])->format('jS F, Y');
                        }
                        else
                        {
                                $schedule['match_start_date'] = DateTime::createFromFormat('d/m/Y g:i A', $schedule['match_start_date'])->format('jS F, Y g:i A');
                        }
                    ?>
                     
                    <p class="vs_date">                       
                        <span><b>{{ $schedule['match_start_date'] }}</b></span>
                        <span class='sports_text'>{{ isset($schedule['sport']['sports_name'])?$schedule['sport']['sports_name']:'' }}</span>
                        @if($schedule['match_type']!='other')
                            <span class='match_type_text'>({{ $schedule['match_type']=='odi'?strtoupper($schedule['match_type']):ucfirst($schedule['match_type']) }})</span>
                        @endif

                    </p>
                            <span class=''>{{Helper::getMatchDetails($schedule['id'])->address}}</span><br>
                    Status: <span class='sports_text'>{{ ucfirst($schedule['match_status']) }}</span> <br>
                    Scores: <span class=''>{{Helper::getMatchDetails($schedule['id'])->scores}} </span> <br>
                    @if(!is_null(Helper::getMatchDetails($schedule['id'])->winner_id))
                            <span class='red'>Winner: {{Helper::getMatchDetails($schedule['id'])->winner}} </span>
                                
                    @endif
                        
                        <p><a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}" class="add_score_but">{{$schedule['winner_text']}}</a></p>
                    </div>  
    
                    <div id="teamtwo_{{$schedule['scheduleteamtwo']['id']}}" class="col-sm-3 score_view_img">
                    <p><!--<img src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.$schedule['scheduleteamtwo']['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="30" width="30">-->
					 {!! Helper::Images($schedule['scheduleteamtwo']['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}</p>
                    @if(isset($schedule['scheduleteamone']['result']))
                        <span>{{ isset($schedule['scheduleteamtwo']['result'])?$schedule['scheduleteamtwo']['result']:'BYE' }}</span>
                    @endif
                </div>
                </div>
                <div class="mobview hidden-sm hidden-lg">
                <div class="row">
                <div id="teamone" class="col-xs-5 score_view_img">
                    <p>
                       {!! Helper::Images($schedule['scheduleteamone']['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}</p>
                    <p>
                    	@if(isset($schedule['scheduleteamone']['result']))
                            <span>{{ $schedule['scheduleteamone']['result'] }}</span>
                        @endif                           
                    </p>
                    <p class="vs_text">
                            <span>
                                <a href="{{ url('/team/members',[$schedule['a_id']]) }}">
                                {{$schedule['scheduleteamone']['name']}}
                                </a>
                            </span>  
                           
                    </p> 
                </div>    
        
                <div id="center_div" class="col-xs-2">
                     <span style="position: absolute; margin-top: 25px;">vs</span>
                </div>
        
                <div id="teamtwo" class="col-xs-5 score_view_img">
                    <p>
                        {!! Helper::Images($schedule['scheduleteamtwo']['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}    
                    </p>
                    <p>
                    	@if(isset($schedule['scheduleteamone']['result']))
                            <span>{{ isset($schedule['scheduleteamtwo']['result'])?$schedule['scheduleteamtwo']['result']:'BYE' }}</span>
                        @endif
                    </p>
                    <p class="vs_text">
                            <span>@if(isset($schedule['scheduleteamtwo']['name']))
                                    <a href="{{ url('/team/members',[$schedule['b_id']]) }}">{{$schedule['scheduleteamtwo']['name']}}
                                    </a>
                                  @else  
                                    {{trans('message.bye')}}
                                  @endif  
                            </span>
                    </p> 
                </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <p>
                        <span>{{ $schedule['match_start_date'] }}</span>
                        <span class='sports_text'>{{ isset($schedule['sport']['sports_name'])?$schedule['sport']['sports_name']:'' }}</span>
                        @if($schedule['match_type']!='other')
                            <span class='match_type_text'>({{ $schedule['match_type']=='odi'?strtoupper($schedule['match_type']):ucfirst($schedule['match_type']) }})</span>
                        @endif
                    </p>  
                    <a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}" class="add_score_but">{{$schedule['winner_text']}}</a>
                </div>
             </div>
            </div>
            @endforeach
            @else
            <div class="col-xs-6 col-centered">
                
                <div class="sj-alert sj-alert-info">
                       {{ trans('message.schedule.noschedule')}}
</div>
            </div>
            @endif
        </div>
        <div class="clearfix"></div>
		@if($matchScheduleDataTotalCount>count($matchScheduleData)) 
<!--            <div id="viewmorediv">
                <div id="viewmorebutton" class="btn btn-view"></div>
            </div>-->
            
            <div class="col-md-12" id="viewmorediv">

  <a  id="viewmorebutton" class="view_tageline_mkt"><span class="market_place"><i class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span></a>
  
 </div>
            
        @endif
        <br>
	
    </div>
</div>
<?php $role = Helper::isTeamOwnerorcaptain((!empty(Request::segment(3))?Request::segment(3):0),Auth::user()->id);
        if($role) { ?>
<div class="col-sm-3 col-xs-12" id="sidebar-right">
	<div class="suggestion_box tournament_profile">
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#addplayer" data-toggle="tab" aria-expanded="true">Add Player</a></li>
                    <li class=""><a href="#inviteplayer" data-toggle="tab" aria-expanded="false">Invite Player</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade active in" id="addplayer">
					@include('widgets.teamplayer')
                </div>
                <div class="tab-pane fade" id="inviteplayer">
					@include('widgets.inviteplayer')
                </div>                 
            </div>
        </div>
        </div>    
		<div id="suggested_players"> </div>
		<br/>
		<div id="suggested_tournaments"></div>	
 </div>
<?php } ?>

	</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {    	
    	$("#from_Date").datepicker({ format: 'mm/dd/yyyy', endDate: new Date() });
        $('#to_Date').datepicker({ format: 'mm/dd/yyyy', endDate: new Date() });
        $("#offset").val({{$offset}});
        
        if($("#viewmorediv").length) {
            $('#viewmorebutton').on("click", function(e) {
                var params = { limit:{{$limit}}, offset:$("#offset").val(), fromDate:$("#fromDate").val(), toDate:$("#toDate").val(), teamId:{{$teamId}}, sportsId:{{$sportsId}}  };
                viewMore(params,'{{URL('team/viewmorestats')}}'); 
            });
			global_record_count = {{$matchScheduleDataTotalCount}}
        }
        <?php $role = Helper::isTeamOwnerorcaptain((!empty(Request::segment(3))?Request::segment(3):0),Auth::user()->id);
        if($role) { ?>
		suggestedWidget('players','{{ !empty(Request::segment(3))?Request::segment(3):'' }}','{{ !empty(Request::segment(4))?Request::segment(4):'' }}','team_to_player','');
        suggestedWidget('tournaments','{{ !empty(Request::segment(3))?Request::segment(3):'' }}','{{ !empty($sport_id)?$sport_id:'' }}','team_to_tournament',''); 
        <?php } ?>       
    });
</script>  
@include ('widgets.teamspopup')
@endsection