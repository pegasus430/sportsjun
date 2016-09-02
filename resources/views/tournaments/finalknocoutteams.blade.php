<div id="final_stage_div">
        <div class="sportsjun-forms">
            <div class="action-panel">
                @if($maxRoundNumber>1)
                <div class="sj-alert sj-alert-info">
                    {{ trans('message.tournament.final.knockoutstarted') }}
                </div>
                  <div class="sportsjun-forms">
            <div class="action-panel">
                @if(count($tournamentDetails[0]['final_stage_teams']))
                <table class="table table-striped">
                <thead>
                    <tr></tr>
                </thead>
                <tbody id="addedTeamsTable">
                @if(count($tournamentTeams))
                    @foreach($tournamentTeams as $requestedTeam)
                            <tr class="selected-teams">
                            <td>
                                @if($schedule_type=='team')
                                    {!! Helper::Images($requestedTeam['logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border ','height'=>30,'width'=>30) )!!}
                                @else
                                    {!! Helper::Images($requestedTeam['logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border ','height'=>30,'width'=>30) )!!}
                                @endif
                            </td>
                            <td>{{ $requestedTeam['name'] }}</td>
                            
                        </tr>
                    @endforeach
                 @endif 
                </tbody>
                </table>
            
            
            @else   
                     <div class="sj-alert sj-alert-info">
                    {{ trans('message.tournament.final.nofinalstageteams') }}
                </div>
                  
              @endif
              </div>
        </div>
                @else

                    <div class="action-btns">
                         @if($tournamentDetails[0]['schedule_type']=='team')
                          	<label>
                            	<input type="radio" name="team_selection_radio" class="selectTeamClass"  checked="true" value="reqested_teams"/>{{ trans('message.tournament.addteam.requestedteam') }}</label>
                            <label>
                            	<input type="radio" name="team_selection_radio" class="selectTeamClass"  value="all_teams"/>{{ trans('message.tournament.addteam.allteam') }}
                            </label>
                         @else
                            <label>
                                <input type="radio" name="team_selection_radio" class="selectTeamClass"  checked="true" value="reqested_teams"/>{{ trans('message.tournament.addteam.requestedteam') }}</label>
                            <label>
                                <input type="radio" name="team_selection_radio" class="selectTeamClass"  value="all_teams"/>Add Player
                             </label>
                             <label>
                                <input type="radio" name="team_selection_radio" class="selectTeamClass"  value="invite_player"/>Invite Player
                            </label>
                        @endif
						</div>



                    <div class="ui-widget" id="auto_teams_div" style="display:none;">
                           @if($tournamentDetails[0]['schedule_type']!='team')
                                 <span class="tab_new_label_txt" style="font-size:13px"><b>Registered User</b></span>
                           @endif
                        <div class="form-group"><input type="text" id="auto_user" class="gui-input" placeholder="Add Team"></div>
                        <input id="auto_response" name="auto_response" class="form-control" type="hidden">
                        <button type="button" name="add_team_button" id="add_team_button" onClick="addFinalStageTeam('auto');" class="button btn-primary">Save</button>
                    </div>

                    <div id="requested_teams_div">
                                <span style="width: 200px; margin-right: 15px;">
                                    {!! Form::select('requested_teams[]', $requestedFinalTeams, null,array('multiple'=>true,'class'=>'multiselect','id'=>'requested_teams')) !!}
                                </span>
                        <button type="button" name="requested_teams_button" id="requested_teams_button" onClick="addFinalStageTeam('select');" class="button btn-primary">Save</button>
                    </div>

                @if($tournamentDetails[0]['schedule_type']!='team')
                     <div id="invite_player_div" style="display:none;" >
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="tab_new_label_txt"><b>Non-Registered User</b></label>
                                <div class="form-group">
                                    <input type="text" id="player_name" class="gui-input" placeholder="Player name">
                                </div>    
                        
                                <div class="form-group" >
                                    <input type="text" id="player_email" class="gui-input" placeholder="Email">
                                </div>
                            </div>

                            <div class='col-sm-3'>
                            <br><br>
                            <button type="button" name="invite_team_button" id="invite_team_button" onClick="addFinalStageTeam('invite');" class="button btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                @endif

<br>

<p>
<div id="displayNotification">

</div>
<br>
                <table class="table table-striped">
                <thead>
                    <tr></tr>
                </thead>
                <tbody id="addedTeamsTable">
                @if(count($tournamentTeams))
                    @foreach($tournamentTeams as $requestedTeam)
							<tr id='selected_team_{{$tournamentDetails[0]['id']}}_{{$requestedTeam['id']}}' class="selected-teams">
                            <td>
                                @if($schedule_type=='team')
                                    {!! Helper::Images($requestedTeam['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border ','height'=>30,'width'=>30) )!!}
                                @else
                                    {!! Helper::Images($requestedTeam['url'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border ','height'=>30,'width'=>30) )!!}
                                @endif
                            </td>
                            <td>{{ $requestedTeam['name'] }}</td>
                            <td>
                            	<a href="#" class="btn btn-danger btn-circle btn-sm" onclick="deleteFinalStageTeam({{$requestedTeam['id']}},'{{ $requestedTeam['name'] }}')"><i class="fa fa-remove"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
		</table>
    <br>
                <button type="button" class="button btn-primary" onclick="finalStageTeams('ko')">Submit </button>
              @endif
            </div>
        </div>
</div>

<script type="text/javascript">
    var tournamentId = {{$tournamentDetails[0]['id']}};
    var scheduleType = '{{$schedule_type}}';
    $(function() {
        $("#auto_user").autocomplete({
			source: base_url+'/tournaments/getfinalstageteams/'+{{$tournamentDetails[0]['id']}},
            minLength: 3,
            select: function(event, ui) {
                $('#auto_response').val(ui.item.id);
            }
        });
    });

    function deleteFinalStageTeam(teamId, teamName) {

        $.confirm({
                    title: 'Confirm',
                    content: "Do you want to delete "+teamName+"?",
                    confirm: function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + '/tournament/deletefinalstageteams',
                        data: {tournamentId:tournamentId, teamId:teamId, scheduleType:scheduleType},
                        dataType: 'json',
                        beforeSend: function() {
                            $.blockUI({width: '50px', message: $("#spinner").html()});
                        },
                        success: function(response) {
                            $.unblockUI();
                            if(response['result']=='error') {
                                return false;
                            }

                            $("#selected_team_"+tournamentId+"_"+teamId).remove();
                            var options = "<option value=''>Select "+scheduleType+"</option>";
                            $.each(response['requestedTeams'], function(key, value) {
                                options += "<option value='" + key + "'>" + value + "</option>";
                            });
//                            $(".req_team_class").html(options);
                            $("#requested_teams").html(options);
                            $('#requested_teams').selectMultiple('refresh');
                        }
                    });
                    },
                    cancel: function() {

                    }
		});

    }

    function addFinalStageTeam(label) {
        var response='';
        var content ='';
        var email='';
        var name='';
        if(label=='auto')
        {
            response = $('#auto_response').val();
            content = 'Selec a team.'
        }
        else if(label=='invite'){
             
             var content ='Selet a player';
             var email=$('#player_email').val();
             var name=$('#player_name').val();
             var response=email;

             if(name=='')
                {
                        $.alert({
                                        title: 'Alert!',
                                        content: "Enter the Player's name"
                                });
                        $('#player_name').focus();
                        return false;
                }
            if(email=='')
                {
                        $.alert({
                                        title: 'Alert!',
                                        content: "Enter Player's email"
                                });
                        $('#player_email').focus();
                        return false;
                }
        }
        else
        {
            response = $('select#requested_teams').val();
            content = 'All requested teams have been added.'
        }

        if(response=='')
        {
                $.alert({
                                title: 'Alert!',
                                content: 'Enter Team.'
                        });
                return false;
        }

        $.ajax({
            type: 'POST',
            url: base_url + '/tournament/addfinalstageteams',
            data: {tournamentId:tournamentId, teamId:response, scheduleType:scheduleType, flag:label, name:name, email:email},
            dataType: 'json',
            beforeSend: function() {
                $.blockUI({width: '50px', message: $("#spinner").html()});
            },
            success: function(response) {
                $.unblockUI();
                if(response['result']=='error') {

        if(typeof(response['message'])!=='undefined'){
            $('#displayNotification').html("<div class='alert alert-danger'>"+response['message']+"</div>");
            setTimeout(function(){ $('#displayNotification').html('') }, 2000);
            }
                    return false;
                }

                var options = "<option value=''>Select "+scheduleType+"</option>";
                $.each(response['requestedTeams'], function(key, value) {
                    options += "<option value='" + key + "'>" + value + "</option>";
                });
                
//                $(".req_team_class").html(options);
                
                $("#requested_teams").html(options);
                $("#addedTeamsTable").html(response['tournamentTeams']);
                $('#auto_response').val('');
                $('#auto_user').val('');
                $('#requested_teams').selectMultiple('refresh');

                $('#player_email').val('');
                $('#player_name').val('')

            }
        });

    }




</script>
