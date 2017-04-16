@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
@include ('album._leftmenu')
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
            <div id="searchFilterDiv" class="sf_div">
                <form class="row">
                    <div class="col-sm-5 col-xs-12">
                        {!! Form::select('sportsId',$sportArray,$selectedSport,['id' => 'sportsId','class' => 'field selectpicker']) !!}
                        <span id="submitButtonDiv">
                        <input type="submit" value="GO" class="btn" />
                        </span>
                    </div>

                    <div class="col-sm-7 col-xs-12 text-right">
                        <div class="btn" onclick="calendarView()"><i class="fa fa-calendar"></i> </div>
                        <div class="btn" onclick="listView('normal')"><i class="fa fa-list"></i> </div>
                         @if($userId==Auth::user()->id)
                            <div class="btn schedule_but_new" href="#" data-toggle="modal" data-target="#mainmatchschedule">
                            {{ trans('message.schedule.fields.schedulematch') }}
                            </div>
                         @endif
                    </div>
                </form>
            </div>
			<div class="clearfix"></div>
            <div id="fc_calendar"></div>

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
                $.ajax({
                url: base_url + '/getmytooltipcontent/'+{{$userId}},
                type: 'GET',
                data: {id: data.id},
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
                if ($('.qtip').length)
                $('.qtip').qtip('hide');
                if (flag === 'normal') {
                $("#submitButtonDiv").html("<input type='button' onclick='listView(\"search\")' value='GO' class='btn'>");
                }

                var sportsId = $("#sportsId").val();

                $.ajax({
                           url: base_url + '/getmylistviewevents/'+{{$userId}},
                               type: 'GET',
                               data: {sportsId: sportsId},
                               dataType: 'html',
                success: function(response) {
                $("#fc_calendar").html(response);
                           }
                });

                }

                function fullCalendar() {
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

                },
                eventClick: function(data, event, view) {
                displayToolTip(data, event);
                },
                //            month: month - 1,
                //            year: year,
                events: function(start, end, callback) {
                var objectToSend = {
                "datefrom": start.getFullYear() + '-' + (start.getMonth() + 1) + '-' + start.getDate(),
                "dateto": end.getFullYear() + '-' + (end.getMonth() + 1) + '-' + end.getDate(),
                "sportsId": $("#sportsId").val(),
                //                  "token": CSRF_TOKEN
                };
                $.ajax({
                url: base_url + '/getmyevents/'+{{$userId}},
                type: 'GET',
                data: objectToSend,
                dataType: 'json',
                cache: false
                }).done(function(data) {
                var date = $("#fc_calendar").fullCalendar('getDate');
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
//                fullCalendar();
                listView('normal');
                });
            </script>
            <div id="calendar"></div>
        </div>
        <div class="col-sm-3 col-xs-12" id="sidebar-right">
            <div id="suggested_teams"> </div>
            <div id="suggested_tournaments"> </div>
        </div>
    </div>
</div>
@include ('widgets.teamspopup')
<script type="text/javascript">
$(window).ready(function(){
        suggestedWidget("teams", '{{ Auth::user()->id }}', '1', "player_to_team",'');
        suggestedWidget("tournaments", '{{ Auth::user()->id }}', '1', "team_to_tournament",'');
});
$("#submitButtonDiv").click(function(){
    if($("#sportsId").val() != ''){

        suggestedWidget("teams", '{{ Auth::user()->id }}', $("#sportsId").val(), "player_to_team",'');
        suggestedWidget("tournaments", '{{ Auth::user()->id }}', $("#sportsId").val(), ($("#sportsId").val() == 1 || $("#sportsId").val() == 4)?"team_to_tournament":"player_to_tournament",'');
    }else{
        suggestedWidget("teams", '{{ Auth::user()->id }}', '1', "player_to_team",'');
        suggestedWidget("tournaments", '{{ Auth::user()->id }}', '1', "team_to_tournament",'');
    }
});
</script>
@endsection