@extends('layouts.app')
@section('content')
@include ('teams._leftmenu')
    <script src="{{ asset('/js/fullcalendar.js') }}"></script>
    <script src="{{ asset('/js/jquery.qtip.min.js') }}"></script>   
    <link href="{{ asset('/css/jquery.qtip.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/fullcalendar.css') }}" rel="stylesheet">	
    <style type="text/css">
        .alert{display: none;}
    </style>
<div id="content-team" class="col-sm-10">
<div class="row">
<div class="col-sm-9">

<div id="searchFilterDiv" class="sf_div two-col-cal">
    <form class="row">
        <div class="col-md-5 col-sm-12">
            {!! Form::hidden('sportsId', $sportsId, array('id' => 'sportsId')) !!}
            {!! Form::hidden('teamId', $teamId, array('id' => 'teamId')) !!}
            {!! Form::hidden('isOwner', $isOwner, array('id' => 'isOwner')) !!}
            {!! Form::selectMonth('month',$currentMonth, ['id' => 'currentMonth','class' => 'field selectpicker']) !!}
            {!! Form::selectYear('year', config('constants.YEAR.START_YEAR'), config('constants.YEAR.END_YEAR'),$currentYear,['id' => 'currentYear','class' => 'field selectpicker']) !!}
            <span id="submitButtonDiv" class="sf_div"> 
                <input type="submit" value="GO" class="btn" />
            </span> 
        </div>
        
        <div class="col-md-7 col-xs-12 text-right">
            <div class="btn" onclick="calendarView()"><i class="fa fa-calendar"></i> </div>
           <div class="btn" onclick="listView('normal')"><i class="fa fa-list"></i> </div>
           @if($isOwner)
               <div class="btn schedule_but_new" data-toggle="modal" data-target="#myModal" id="schedule_match_btn">{{ trans('message.schedule.fields.schedulematch') }}</div>
           @endif   
        </div>    
    </form>
</div>

<div id="fc_calendar" class="pull-left full-width"></div>



<script type="text/javascript">

    var view = 'calendar';
    var tooltip = $('<div/>').qtip({
        id: 'fullcalendar',
        prerender: true,
        content: {
            text: ' ',
            title: {
                button: true
            }
        },
        position: {
            my: 'bottom center',
            at: 'top center',
            target: 'mouse',
            viewport: $('#fullcalendar'),
            adjust: {
                mouse: false,
                scroll: false
            }
        },
        show: false,
        hide: false,
        style: 'qtip-light'
    }).qtip('api');

    function displayToolTip(data, event) {
		var isOwner = $("#isOwner").val();
		var teamId = $("#teamId").val();
		$.ajax({
			url: base_url + '/gettooltipcontent',
			type: 'GET',
			data: {id: data.id, teamId: teamId,isOwner: isOwner, token: CSRF_TOKEN},
			dataType: 'html',
			success: function(response) {
				tooltip.set({'content.text': response}).reposition(event).show(event);
		    }
        });
    }
    function calendarView() {
        $("#fc_calendar").html('');
        $("#submitButtonDiv").html("<input type='submit' value='GO' class='btn'>");
        var date = new Date();
        $("#currentMonth").val(date.getMonth() + 1);
        $("#currentYear").val(date.getFullYear());
        fullCalendar();
    }
    function listView(flag) {
        if($('.qtip').length)
         $('.qtip').qtip('hide');
        if (flag === 'normal') {
            $("#submitButtonDiv").html("<input type='button' onclick='listView(\"search\")' value='GO' class='btn'>");
            var date = new Date();
            $("#currentMonth").val(date.getMonth() + 1);
            $("#currentYear").val(date.getFullYear());
        }

        var month = $("#currentMonth").val();
        var year = $("#currentYear").val();
        var sportsId = $("#sportsId").val();
        var teamId = $("#teamId").val();
        var isOwner = $("#isOwner").val();
        if (month && year && sportsId && teamId) {
            $.ajax({
                           url: base_url + '/getlistviewevents',
                               type: 'GET',
                               data: {month: month, year: year, sportsId: sportsId, teamId: teamId, isOwner: isOwner, token: CSRF_TOKEN},
                               dataType: 'html',
                success: function(response) {
                    $("#fc_calendar").html(response);
                           }
            });
        } else {
            return false;
        }
    }

    function fullCalendar() {
        var month = $("#currentMonth").val();
        var year = $("#currentYear").val();
        $('#fc_calendar').fullCalendar({
            header: {
                left: 'title',
                center: '',
                right: 'prev,next today'
            },
            editable: true,
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                    //console.log(1);
            },
            eventClick: function(data, event, view) {
                displayToolTip(data, event);
            },
            month: month - 1,
            year: year,
            events: function(start, end, callback) {
                var objectToSend = {
                    "datefrom": start.getFullYear() + '-' + (start.getMonth() + 1) + '-' + start.getDate(),
                    "dateto": end.getFullYear() + '-' + (end.getMonth() + 1) + '-' + end.getDate(),
                    "sportsId": $("#sportsId").val(),
                    "teamId": $("#teamId").val(),
                    "isOwner": $("#isOwner").val(),
                    "token": CSRF_TOKEN
                };
                $.ajax({
                    url: base_url + '/getevents',
                    type: 'GET',
                    data: objectToSend,
                    dataType: 'json',
                    cache: false
                }).done(function(data) {
                    var date = $("#fc_calendar").fullCalendar('getDate');
                    $("#currentMonth").val(date.getMonth() + 1);
                    $("#currentYear").val(date.getFullYear());
                    callback(data);
                })
            },
            eventRender: function(event, element)
            { 
                element.attr('title', event.title);
            },
        });
    }

    $(document).ready(function() {
        $('form.eventFilter').submit(function() {
            $('#fc_calendar').fullCalendar('refetchEvents');
            return false;
        });
//        fullCalendar();
        listView('normal')
        <?php $role = Helper::isTeamOwnerorcaptain((!empty(Request::segment(3))?Request::segment(3):0),Auth::user()->id);
        if($role) { ?>
        suggestedWidget('players','{{ !empty(Request::segment(3))?Request::segment(3):'' }}','{{ !empty(Request::segment(4))?Request::segment(4):'' }}','team_to_player','');
        suggestedWidget('tournaments','{{ !empty(Request::segment(3))?Request::segment(3):'' }}','{{ !empty($sport_id)?$sport_id:'' }}','team_to_tournament',''); 
        <?php } ?>       
    });
</script>  

<div id="calendar"></div>
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
@include ('schedules.addschedule',[])
@include ('widgets.teamspopup')

@endsection