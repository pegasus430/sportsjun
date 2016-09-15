

@if(count($tournamentDetails[0]['final_stage_teams']))
<!--<div>
    Add Round
</div>    

<div>
    <div>Round 1</div>
    <span class="button btn-primary" onclick='addRoundMatchesSchedule({{$tournament_id}},1)'>Add Schedule</span>
</div>

<div id="round_div_1">
</div>    -->
<div class="col-sm-10">
<div class="col-sm-8">
<div class="row group-flex-content">
@if(count($roundArray))
        @foreach($roundArray as $round)
        @if($round==1)
        <div class="col-sm-3 ">
            <div class="row round-{{Helper::convert_number_to_words($round)}}">
                <div class="round"><p>ROUND {{Helper::convert_number_to_words($round)}}</p></div>
                <span class="button btn-primary" onclick='addRoundMatchesSchedule({{$tournament_id}},{{$round}})'>Add Schedule</span>
                 @if(count($matchScheduleData))   
                 @foreach($matchScheduleData as $schedule) 
                 @if($schedule['tournament_round_number']==$round)
                 <ul>
                     <div>
                        <span class="tour_match_date">{{$schedule['match_start_date']}}</span>
                        <span class="tour_score">
                        @if(isset($schedule['winner_text']))
                        <a href="{{ url('match/scorecard/'.$schedule['id']) }}" target="_blank">{{$schedule['winner_text']}}</a>
                        @else
                        <a href="javascript:void(0)" id="scheduleEdit_{{$schedule['id']}}" onclick="editschedulegroupmatches({{$schedule['id']}},1,'')">Edit</a>
                        @endif
                        </span>
                     </div>
                    <li><img src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.$schedule[$scheduleTypeOne]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="30" width="30">
                            <span>{{isset($schedule[$scheduleTypeOne]['name'])?$schedule[$scheduleTypeOne]['name']:'Bye'}}</span>
                    </li>
                    <li><img src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.$schedule[$scheduleTypeTwo]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="30" width="30">
                        <span>{{isset($schedule[$scheduleTypeTwo]['name'])?$schedule[$scheduleTypeTwo]['name']:'Bye'}}</span>
                    </li>
                </ul>
                 @endif
                 @endforeach
                 @endif
            </div>    
        </div>
        @else
        <div class="col-sm-3 ">
            <div class="row round-{{Helper::convert_number_to_words($round)}}">
                <div class="round"><p>ROUND {{Helper::convert_number_to_words($round)}}</p></div>
                
                 @if(count($bracketTeamArray))   
                    @foreach($bracketTeamArray as $brk => $bracketTeam) 
                        <ul>
                            @foreach($bracketTeam as $bt => $bracket)
                                @if(isset($bracket['tournament_round_number']) && $bracket['tournament_round_number']==$round)
                                    @if(isset($bracket['match_start_date']))
                                        <div>
                                           <span class="tour_match_date">{{$bracket['match_start_date']}}</span>
                                           <span class="tour_score">
                                           @if(isset($bracket['winner_text']))
                                           <a href="{{ url('match/scorecard/'.$bracket['id']) }}" target="_blank">{{$bracket['winner_text']}}</a>
                                           @else
                                           <a href="javascript:void(0)" id="scheduleEdit_{{$schedule['id']}}" onclick="editschedulegroupmatches({{$schedule['id']}},1,1)"Edit</a>
                                           @endif
                                           </span>
                                        </div>
                                    @endif
                                   <li><img src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.$bracket['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="30" width="30">
                                           <span>{{isset($bracket['name'])?$bracket['name']:''}}</span>
                                   </li>
                                @endif
                            @endforeach  
                        </ul>
                        
                    @endforeach
                 @endif
            </div>    
       </div>
        @endif
        @endforeach
@else
        <div class="col-sm-3 ">
            <div class="row round-one">
                <div class="round"><p>ROUND ONE</p></div>
                <span class="button btn-primary" onclick='addRoundMatchesSchedule({{$tournament_id}},1)'>Add Schedule</span>
            </div>
         </div>     
@endif
</div>
</div>
</div>
@else
<div class="container-fluid" id="final_stage_div">
    <div class="sportsjun-wrap">
        <div class="sportsjun-forms sportsjun-container wrap-2">
            <div class="form-body">
                <div class="section">
                    <label for="firstname" class="field prepend-icon">
                            <!--<input type="text" placeholder="Number Of Teams Qualifying For The Final Stage" value="" id="final_stage_teams">-->
                            {!! Form::select('final_stage_teams[]',$tournamentTeams,null, array('multiple'=>true,'class'=>'gui-inpu','id'=>'final_stage_teams','placeholder'=>"Number Of Teams Qualifying For The Final Stage")) !!}
                    </label>
                </div>
                <div class="form-footer">
                    <button type="button" class="button btn-primary" onclick="finalStageTeams()">Submit </button>
                </div>
            </div>    
        </div>
    </div>
</div>
@endif


<script type="text/javascript">
function finalStageTeams() {
//    var finalStageTeams = $("#final_stage_teams").val();
    var finalStageTeams = $('select#final_stage_teams').val();
    var tournamentId = {{$tournamentDetails[0]['id']}}
    if(!finalStageTeams || !tournamentId) {
        return false;
    }
    var html='';
//    if(Math.floor(finalStageTeams) == finalStageTeams && $.isNumeric(finalStageTeams)) {
        $.ajax({
            type: 'POST',
            url: base_url + '/tournament/updatefinalstageteams',
            data: {tournamentId:tournamentId, finalStageTeams:finalStageTeams},
            dataType: 'json',
            beforeSend: function() {
                $.blockUI({width: '50px', message: $("#spinner").html()});
            },
            success: function(response) {
                $.unblockUI();
                html ='<div class="col-sm-10">';
                html+='<div class="col-sm-8">';
                html+='<div class="row group-flex-content">';
                html+='<div class="col-sm-3">';
                html+='<div class="row round-one">';
                html+='<div class="round"><p>ROUND ONE</p></div>';
                html+='<span class="button btn-primary" onclick="addRoundMatchesSchedule('+tournamentId+',1)">Add Schedule</span>';
                html+='</div>';
                html+='</div>';
                html+='</div>';
                html+='</div>';
                html+='</div>';
                $("#final_stage_div").html(html);
                   
            }   
        });
//    }
}

function addRoundMatches(tournamentId,roundNumber) {
    var finalStageTeamsCount = $("#final_stage_teams_count").val();
    var teamIds = $("#team_ids").val();
    $.ajax({
            type: 'GET',
            url: base_url + '/tournament/addRoundMatches',
            data: {tournamentId:tournamentId, finalStageTeamsCount:finalStageTeamsCount, roundNumber:roundNumber, teamIds:teamIds},
            dataType: 'html',
            beforeSend: function() {
                $.blockUI({width: '50px', message: $("#spinner").html()});
            },
            success: function(response) {
                $.unblockUI();
            }   
    });
}

function addRoundMatchesSchedule(tournamentId,roundNumber) {
    $.ajax({
            type: 'GET',
            url: base_url + '/tournament/getroundteams',
            data: {tournamentId:tournamentId, roundNumber:roundNumber},
            dataType: 'json',
            beforeSend: function() {
                $.blockUI({width: '50px', message: $("#spinner").html()});
            },
            success: function(response) {
                $.unblockUI();
                if(response['result'] == 'success') {
                    $("#myModal").modal();
                    clearModal();
                    $(".modal-body #search_team_ids").val(response['searchTeamIds']);
                    $(".modal-body #tournament_round_number").val(roundNumber);
                    $(".modal-body #tournament_match_number").val(response['matchNumber']);
                    $(".modal-body #schedule_type").val(response['scheduleType']);
                }
            }   
    });
}

</script>
