<p class="help-block" id="msg"></p> 
				    @if (session('status'))
                    <div class="alert alert-success" >
                        {{ session('status') }}
                    </div>
                    @endif
                        <!-- /.panel-heading -->
			@foreach($tournament as $tour)
			@foreach($tour->groups as $group)
			<div class="group_no">{{ $group->name }}<a onclick="editGroup({{ $group->id }});">Edit</a>&nbsp;<a href="{{ url('/tournament/groupedit/'.'delete'.'/'.$group->id) }}">Delete</a></div>
			<div id="edit_group_{{ $group->id }}" style="display:none;">
				<div class="form-group"><input type="text" class="form-control" id="group_name_{{ $group->id }}" value="{{ $group->name }}"></div>
				<button type="button" name="editgroup" id="editgroup" onClick="editgroupname({{ $group->id }});" class="button btn-primary">
						Edit Group
				</button>
			</div>
			<div class="panel-body">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs nav-justified">
					<li class="active"><a href="#home_{{ $group->id }}" data-toggle="tab" aria-expanded="true">Teams</a></li>
					<li class=""><a href="#profile_{{ $group->id }}" data-toggle="tab" aria-expanded="false">Matches</a></li>
				</ul>
				<!-- Tab panes -->
				{!! Form::open(array('url' => '', 'files'=> true)) !!}
				<div class="tab-content">
				
					<div class="tab-pane fade active in" id="home_{{ $group->id }}">
						<?php $table_count = 0;?>
						@if(!empty($team_details[$group->id]))
						<?php $table_count=count($team_details[$group->id]);?>
							<table class="table">
							<tr>
							<th>Name</th>
							<th>Matches</th>
							<th>Won</th>
							<th>Lost</th>
							<th>Points</th>
							</tr>
							<tbody>
							@foreach($team_details[$group->id] as $team)
							<tr>
							<td>{{ $team['name'] }}</td>
							<td>{{ $team['match_id'] }}</td>
							<td>{{ $team['won'] }}</td>
							<td>{{ $team['lost'] }}</td>
							<td>{{ $team['points'] }}</td>
							<td><a href="{{ url('/tournament/team/delete/'.$team['id']) }}">Delete Team</a></td>
							</tr>	
							@endforeach	
							</tbody>
							</table>
						@endif
						@if($table_count < $tour->groups_teams)
						
							<div class="ui-widget">
							<div class="form-group"><input type="text" id="user" class="form-control test" placeholder="Add Team"></div>
							<div class="form-group"><input id="response" name="response" class="form-control" type="hidden"></div>
							<div class="form-group"><input id="team_name" name="team_name" class="form-control" type="hidden"></div>
							
							<input type="hidden" id="sport_id" value="{{ $tour['sports_id'] }}">
							<input type="hidden" id="tournament_id" value="{{ $tour['id'] }}">
							<input type="hidden" id="team_count" value="{{ $tour->groups_teams }}">
							</div>
							<meta name="_token" content="<?php echo csrf_token(); ?>" />
							<button type="button" name="add_team" id="add_team" onClick="addTeam({{ $group->id }});" class="button btn-primary">
							Save
							</button>
						@endif
						
						
					</div>
					<div class="tab-pane fade" id="profile_{{ $group->id }}">
						<div class="button btn-primary" onclick="schedulegroupmatches({{ $group->id }})">{{ trans('message.schedule.fields.schedulematch') }}</div>
						<!--<h4>matches</h4>-->
						@if(!empty($match_details[$group->id]))
						<table class="table">
							<tbody>
							@foreach($match_details[$group->id] as $match)
							@if($match['a_id']!='' && $match['b_id'])
								@if($match['schedule_type']=='team')
							<tr>
								<td>{{ $team_name_array[$match['a_id']] }}
								@if(!empty($team_logo[$match['a_id']]))
								@if($team_logo[$match['a_id']]['url']!='')
								<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">
								@else
								<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">
								</td>
								@endif
								@endif
								<td>{{ $match['match_start_date'].','.$match['match_end_date'] }}
								</td>
								<td>{{ $team_name_array[$match['b_id']] }}
								@if(!empty($team_logo[$match['b_id']]))
								@if($team_logo[$match['b_id']]['url']!='')
								<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">
								@else
								<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">
								@endif
								</td>
								@endif
								<td>
									<div class="button btn-primary" onclick="editschedulegroupmatches({{$match['id']}},1)">{{ trans('message.tournament.fields.edit_schedule') }}</div>
								</td>
							</tr>
							@else
							<tr>
								<td>{{ $user_name[$match['a_id']] }}
								@if($user_profile[$match['a_id']]['url']!='')
								<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">
								@else
								<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">
								
								</td>
								@endif
								<td>{{ $match['match_start_date'].','.$match['match_end_date'] }}
								</td>
								<td>{{ $user_name[$match['b_id']] }}
								@if($user_profile[$match['b_id']]['url']!='')
								<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">
								@else
								<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">
								@endif
								</td>
								<td>
									<div class="button btn-primary" onclick="editschedulegroupmatches({{$match['id']}},1)">{{ trans('message.tournament.fields.edit_schedule') }}</div>
								</td>								
							</tr>
							@endif
							@else
								No Scheduled Matches.
							@endif
							@endforeach	
							</tbody>
						</table>
						@else
							No Scheduled Matches.
						@endif
						
					</div>
					
				</div>
				<input type="hidden" name="schedule_type" id="schedule_type" value="{{ $schedule_type }}">
				{!!Form::close()!!}
			</div>
			@endforeach
			@endforeach
			 <button type="button" class="button btn-primary" onclick="createGroup();">Add More Groups</button>
			<div id="create_group" style="display:none;">
				<div class="form-group"><input id="group" class="form-control" placeholder="No of Groups "></div>
				<button type="button" name="add_group" id="add_group" onClick="insertgroup({{ $tour->id }},{{ $tour->groups_number }});" class="button btn-primary">
						Create Group
				</button>
			</div>
			<!-- /.panel-body -->
<script type="text/javascript">
    $(function() {
		var sport_id = $('#sport_id').val();
		var tournament_id = $('#tournament_id').val();
		var schedule_type = $('#schedule_type').val();
        $(".test").autocomplete({
			source: base_url+'/tournaments/getSportTeams/'+sport_id+'/'+tournament_id+'/'+schedule_type,
            minLength: 3,
            select: function(event, ui) {
                $('#response').val(ui.item.id);
                $('#team_name').val(ui.item.value);
            }
        });
    });
	function addTeam(group_id)
    {
		var team_count = $('#team_count').val();
        var token = "<?php echo csrf_token(); ?>";
        var response = $('#response').val();
        var team_name = $('#team_name').val();
        $.ajax({
            url: '/tournaments/addteamtotournament',
            type: "post",
            dataType: 'JSON',
            data: {'_token': token, 'response': response,'group_id':group_id,'team_name':team_name,'team_count':team_count},
            success: function(data) {
				$( "#msg" ).append( data.success );
				$('#response').val('');
				$('.test').val('');
				location.reload();
            }
        });
    }
	//function to create group
	function createGroup()
	{
		$('#create_group').show();
		$('#create_group').css('display','inline-block');
	}
	function editGroup(group_id)
	{
		$('#edit_group_'+group_id).show();
	}
	function insertgroup(tournament_id,group_numbers)//inset group
	{
		var group = $('#group').val();
		if($.isNumeric(group)==true && group>0)
		{
			var token = "<?php echo csrf_token(); ?>";
			$.ajax({
				url: '/tournaments/insertTournamentGroup',
				type: "post",
				dataType: 'JSON',
				data: {'_token': token, 'tournament_id': tournament_id,'group_numbers':group_numbers,'group':group},
				success: function(data) {
					// alert(data.success);
					// console.log(data);
					$( "#msg" ).append( data.success );
					$('#group').val('');
					location.reload();
				}
			});
		}else{
			alert('Enter Only Numbers.');
			$('#group').val('');
			location.reload();
			$( "#msg" ).append( 'Enter Only Numbers.' );
		}
			
		
	}
	function editgroupname(group_id)
	{
		var group = $('#group_name_'+group_id).val();
		var token = "<?php echo csrf_token(); ?>";
		$.ajax({
            url: '/tournament/groupedit/'+'edit'+'/'+group_id,
            type: "get",
            dataType: 'JSON',
            data: {'_token': token,'group':group},
            success: function(data) {
                $( "#msg" ).append( data.success );
				location.reload();
            }
        });
	}
	//modal popup call
	function schedulegroupmatches(tournament_group_id)
	{
		$("#myModal").modal();
		//clearing all the values on modal window load
		clearModal();
    	//populating radio button based on selected radio button and default is team
		var $radios = $('input:radio[name=scheduletype]');
		$radios.filter('[value=Team]').prop('checked', true);
		if($('input[name=scheduletype]:checked').val() === "team")
		{
			$("#my_team").html('My Team');
			$("#opp_team").html('Opponent Team');
		}
		else
		{
			$("#my_team").html('My Player');
			$("#opp_team").html('Opponent Player');
		}		
		$(".modal-body #tournament_group_id").val(tournament_group_id);
	}

    function editschedulegroupmatches(schedule_id,is_owner)
    {
        $.get(base_url+'/editteamschedule',{'scheduleId':schedule_id,'isOwner':is_owner},function(response,status,xhr){
            $("#myModal").modal();
            if(status == 'success')
            {
                var data=xhr.responseText;
                var parsed_data = JSON.parse(data);
                var options = "<option value=''>Select City</option>";
                $.each(parsed_data.cities, function(key, value) {
                    options += "<option value='" + key + "'>" + value + "</option>";
                });
                $(".modal-body #city_id").html(options);
                $(".modal-body #schedule_id").val(parsed_data.scheduleData.id);
                $(".modal-body #myteam").val(parsed_data.team_a_name);
                $(".modal-body #my_team_id").val(parsed_data.scheduleData.a_id);
                $(".modal-body #sports_id").val(parsed_data.scheduleData.sports_id);
                $(".modal-body #oppteam").val(parsed_data.team_b_name);
                $(".modal-body #opp_team_id").val(parsed_data.scheduleData.b_id);
                $(".modal-body #start_time").val(parsed_data.scheduleData.match_start_time);
                $(".modal-body #end_time").val(parsed_data.scheduleData.match_end_time);
                $(".modal-body #venue").val(parsed_data.scheduleData.facility_name);
                $(".modal-body #facility_id").val(parsed_data.scheduleData.facility_id);
                $(".modal-body #player_type").val(parsed_data.scheduleData.match_category);
                $(".modal-body #match_type").val(parsed_data.scheduleData.match_type);
                $(".modal-body #address").val(parsed_data.scheduleData.address);
                $(".modal-body #state_id").val(parsed_data.scheduleData.state_id);
                $(".modal-body #city_id").val(parsed_data.scheduleData.city_id);
                $(".modal-body #zip").val(parsed_data.scheduleData.zip); 
                $(".modal-body #is_edit").val(1);
                $(".modal-body #tournament_id").val(parsed_data.scheduleData.tournament_id);
                $(".modal-body #tournament_group_id").val(parsed_data.scheduleData.tournament_group_id);
                $(".modal-body #tournament_round_number").val(parsed_data.scheduleData.tournament_round_number);
                $(".modal-body #tournament_match_number").val(parsed_data.scheduleData.tournament_match_number);
                
            }
        });    	
    }
</script>