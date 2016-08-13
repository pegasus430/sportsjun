
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
<div class="col-sm-12">
<div class="row group-flex-content">
@if(count($roundArray))
        @foreach($roundArray as $key=>$round)
          <?php
            $round_name=[];
                switch (count($roundArray)) {
                  case 1:
                      $round_name[1]='FINAL';
                    break;
                  case 2:
                      $round_name[1]='SEMI FINAL';
                      $round_name[2]='FINAL';
                     
                      break;
                  case 3:  
                      $round_name[1]='QUARTER FINAL';
                      $round_name[2]='SEMI FINAL';
                      $round_name[3]='FINAL';
                      break;               
                  default:
                    # code...
                    break;
                }


              ?>  
                 @if($round==1)
        <div class="col-sm-12">
            <div class="round-{{Helper::convert_number_to_words($round)}}">
                <div class="round"><p>      {{$round_name[$key]}} </p></div>
                 @if(count($firstRoundBracketArray))
                 @foreach($firstRoundBracketArray as $key => $schedule)
                 	<div class="col-sm-12 match_set" style="">                 	
                    @if(isset($schedule['tournament_round_number']) && $schedule['tournament_round_number']==$round)
                       <?php $match=Helper::getMatchDetails($schedule['id']); ?>


                       	@if(($match['a_id']!='' && $match['b_id']) ) 
								@if($match['schedule_type']=='team' )
							<div class="row">
						
								<div class="col-md-3 schedule_new_team_img">
								@if(!empty($team_logo[$match['a_id']]))
									@if($team_logo[$match['a_id']]['url']!='')
									<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
									<div class="team_player_sj_img">
										{!! Helper::Images($team_logo[$match['a_id']]['url'],'teams',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
									</div>						
									@else
									<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
									<div class="team_player_sj_img">
										{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
									</div>
									@endif
								@else
								<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
								<div class="team_player_sj_img">
                                	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                                </div>
								@endif
								{{ 'VS' }}
								@if(!empty($team_logo[$match['b_id']]))
									@if($team_logo[$match['b_id']]['url']!='')
									<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
									<div class="team_player_sj_img">
										{!! Helper::Images($team_logo[$match['b_id']]['url'],'teams',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
									</div>                    
									@else
								<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
									<div class="team_player_sj_img">
										{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}		
									</div>
									@endif
								@else
								<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
								<div class="team_player_sj_img">
                                	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                                </div>
								@endif	
								</div>
								<div class="col-md-6 schedule_new_team_txt">
                                	<h4 class="tour-title">
                                    	{{ $team_name_array[$match['a_id']] }}
                                        {{ 'VS' }}                                        
                                        {{ $team_name_array[$match['b_id']] }}
                                    </h4>
									
									<span class="event-date">{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}</span>
									<span class='sports_text'>{{ isset($sport_name)?$sport_name:'' }}</span>
									@if($match['match_type']!='other')
											<span class='match_type_text'>({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }}, {{ucfirst($match['match_category'])}})</span>
									@endif
									<br/>
									<!-- match_details -->
									
									<span class=''>{{$match['address']}}</span><br>
									Status: <span class='event_date'>{{ ucfirst($match['match_status']) }}</span> <br>
									Scores: <span class='blue'>{{Helper::getMatchDetails($match['id'])->scores}} </span> <br>
									@if(!is_null($match['winner_id']))
								<span class='red'>Winner: {{Helper::getMatchDetails($match['id'])->winner}} </span>
								
									@endif

									<br>
								
                  <a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}">View Score</a>


										
								</div>
								
								

							</div>
							@else
							<div class="row">
								<div class="col-md-3 schedule_new_team_img">
								
								@if($user_profile[$match['a_id']]['url']!='')
								<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
								
                                <div class="team_player_sj_img">
                                	{!! Helper::Images($user_profile[$match['a_id']]['url'],'user_profile',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                                </div>	
                                
								@else
							<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                            	<div class="team_player_sj_img">
                                	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                                </div>	
					
					@endif
					{{'VS'}}
					
					@if($user_profile[$match['b_id']]['url']!='')
								<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
								<div class="team_player_sj_img">
                                	{!! Helper::Images($user_profile[$match['b_id']]['url'],'user_profile',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                                </div>		
								@else
							<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                            <div class="team_player_sj_img">
                            	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                                </div>
                                		
								@endif
					
								</div>
                                
                                <div class="col-md-6 schedule_new_team_txt">
                                	<h4 class="tour-title">
                                    	{{ $user_name[$match['a_id']] }}
                                        {{ 'VS' }}                                        
                                        {{ $user_name[$match['b_id']] }}
                                    </h4>
									
									<span class="event-date">{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}</span>
									<span class='sports_text'>{{ isset($sport_name)?$sport_name:'' }}</span>
									@if($match['match_type']!='other')
											<span class='match_type_text'>({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }})</span>
									@endif
									<br/>

								
                <a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}">View Score</a>                             
						
							@endif


                    @else
                            <ul>
                                @if($isOwner)
                                    <div class="clearfix">
                                      <span class="tour_score">
                                        <a href="javascript:void(0)" id="scheduleEdit_{{$key}}"  onclick="addRoundMatchesSchedule({{$tournament_id}},{{$round}},{{$key}})">Schedule Match</a>
                                      </span>
                                    </div>
                                @endif
                                   <li>
                                       <span>Match {{$key}}</span>
                                   </li>
                                   <li>
                                       <span></span>
                                   </li>
                            </ul>
                    @endif
                 </div>

                
                 @endif
                 <p><p>&nbsp;</p>
                 @endforeach

                 @endif
            </div>
        </div>
     @endif
@endforeach

@endif
@else
  
    <div class='col-sm-12'>
      <div class='col-sm-12'>
      <p>&nbsp; <p>
 <center>{{ trans('message.tournament.final.nofinalstageteams') }}
 </center>
 </div>
    </div>
    </div>
 
@endif

<script type="text/javascript">
function finalStageTeams(flag) {
//    var finalStageTeams = $("#final_stage_teams").val();
    var finalStageTeams = $('select#final_stage_teams').val();
    var tournamentId = {{$tournamentDetails[0]['id']}}
    if(!tournamentId) {
        return false;
    }
    if(flag=='group' && !finalStageTeams){
        $.alert({
                title: 'Alert!',
                content: 'Select final stage teams.'
        });
        return false;
    }
    if(flag=='ko' && $(".selected-teams").length<1){
        $.alert({
                title: 'Alert!',
                content: 'Select final stage teams.'
        });
        return false;
    }
    var html='';
//    if(Math.floor(finalStageTeams) == finalStageTeams && $.isNumeric(finalStageTeams)) {
        $.ajax({
            type: 'POST',
            url: base_url + '/tournament/updatefinalstageteams',
            data: {tournamentId:tournamentId, finalStageTeams:finalStageTeams, flag:flag},
            dataType: 'json',
            beforeSend: function() {
                $.blockUI({width: '50px', message: $("#spinner").html()});
            },
            success: function(response) {
                $.unblockUI();
                window.location.reload();
//                html ='<div class="col-sm-10">';
//                html+='<div class="col-sm-8">';
//                html+='<div class="row group-flex-content">';
//                html+='<div class="col-sm-3">';
//                html+='<div class="row round-one">';
//                html+='<div class="round"><p>ROUND ONE</p></div>';
//                html+='<span class="button btn-primary" onclick="addRoundMatchesSchedule('+tournamentId+',1)">Add Schedule</span>';
//                html+='</div>';
//                html+='</div>';
//                html+='</div>';
//                html+='</div>';
//                html+='</div>';
//                $("#final_stage_div").html(html);

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

function addRoundMatchesSchedule(tournamentId,roundNumber, matchNumber) {
    $.ajax({
            type: 'GET',
            url: base_url + '/tournament/getroundteams',
            data: {tournamentId:tournamentId, roundNumber:roundNumber, matchNumber:matchNumber},
            dataType: 'json',
            beforeSend: function() {
                $.blockUI({width: '50px', message: $("#spinner").html()});
            },
            success: function(response) {
                $.unblockUI();
                $('#bye').prop("selectedIndex","0");
                if(response['result'] == 'success') {
                    $("#myModal").modal();
                    $("#byeDiv").show();
                    $("#byeTextDiv").hide();
                    clearModal();
                    
                    var tournamentDetails = response['tournamentDetails'];
                    $(".modal-body #search_team_ids").val(response['searchTeamIds']);
                    $(".modal-body #tournament_round_number").val(roundNumber);
                    $(".modal-body #tournament_id").val(tournamentId);
//                    $(".modal-body #tournament_match_number").val(response['matchNumber']);
                    $(".modal-body #tournament_match_number").val(matchNumber);
                    $(".modal-body #scheduletype").val(response['scheduleType']);
                    
                   autofillsubtournamentdetails(tournamentDetails);
                }
            }
    });
}

</script>


				
					
							

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
	function addTeam(group_id,label,prev_team_coount)
    {
		var team_count = $('#team_count').val();
		var row_count = $('[class ^= "group_row_'+group_id+'"]').size();
        var token = "<?php echo csrf_token(); ?>";
        var tournament_id = "{{$tournament_id}}";
		var schedule_type = $('#schedule_type').val();
		response='';
		team_name='';
		if(label=='auto')
		{
			str_val = $('#response').val();
			response = $.makeArray( str_val );
			team_name = $('#team_name').val();
		}
		else
		{
			response = $('#'+group_id+'_req_team').val();
			team_name = $('#'+group_id+'_req_team option:selected').text();
			
			var selected_team_count = $("#"+group_id+"_req_team option[value!='']:selected").length;
			if((selected_team_count+row_count) > team_count)
			{
				alert("{{trans('message.tournament.team_count_exceeded') }}");
				$('#'+group_id+'_req_team option').attr('selected', false);
				return false;
			}
			
		}
		if(response=='' || response==null)
		{
			$.alert({
						title: 'Alert!',
						content: "{{trans('message.tournament.select_team') }}"
					});
			return false;		
		}
			
       
        $.ajax({
            url: base_url+'/tournaments/addteamtotournament',
            type: "post",
            dataType: 'JSON',
            data: {'_token': token, 'response': response,'group_id':group_id,'team_name':team_name,'team_count':team_count,'tournament_id':tournament_id},
            success: function(response) {
				if(response.length>0)
				{
					 var trHTML = '';
					 $.each(response, function (i, item) {
						 trHTML = '<tr id="row_'+item.id+'">'+
                                 '<td>' + item.name + '</td>'+
                                 '<td>' + item.match_id + '</td>'+
                                 '<td>' + item.won + '</td>'+
                                 '<td>' + item.lost + '</td>'+
                                 '<td>' + item.points + '</td>'+
                                 '<td></td>'+
                                 '<td><a href="#" class="btn btn-danger btn-circle btn-sm" onclick="deleteTeam('+tournament_id+','+item.tournament_group_id+','+item.id+','+item.team_id+');"><i class="fa fa-remove"></i></a></td>'+
                                 '</tr>';
						 $('#records_table_'+group_id).append(trHTML);
					});
					//$('#records_table_'+group_id).append(trHTML);
					$('#no_teams_'+group_id).hide();
					 $('#response').val('');
					 $('.test').val('');
					 
					 
					 
					//replace requested team select box options
					$.ajax({
						url: "{{URL('tournaments/getRequestedTeams')}}",
							type : 'GET',
							data : {'tournament_id':tournament_id,'schedule_type':schedule_type},
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Team</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + key + "'>" + value + "</option>";
									});
									$(".req_team_class").html(options);
									$('.req_team_class').selectMultiple('refresh');

							}
					});
					 
				}else
				{
					//$( "#msg" ).append( data.success );
					$.alert({
						title: 'Alert!',
						content: "Maximum count in this Group is ["+team_count+"]"
					});
					 $('#response').val('');
					 $('.test').val('');
					 location.reload();
				}
				
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
				url: base_url+'/tournaments/insertTournamentGroup',
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
            url:  base_url+'/tournament/groupedit/'+'edit'+'/'+group_id,
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
                var scheduletype = '{{$schedule_type}}';
		$("#myModal").modal();
                $('#bye').prop("selectedIndex","0");
                $("#match_start_date").val("");
                $("#match_start_time").val("");
		//clearing all the values on modal window load
//		clearModal();
                //populating radio button based on selected radio button and default is team
                $.ajax({
                    type: 'GET',
                    url: base_url + '/tournament/getsubtournamentdetails/'+{{$tournament_id}},
//                    data: {tournamentId:tournamentId, roundNumber:roundNumber, matchNumber:matchNumber},
                    dataType: 'json',
                    beforeSend: function() {
                        $.blockUI({width: '50px', message: $("#spinner").html()});
                    },
                    success: function(response) {
                        $.unblockUI();
                        var tournamentDetails = response['tournamentDetails'];
                        $(".modal-body #tournament_group_id").val(tournament_group_id);
                        $(".modal-body #tournament_id").val({{$tournament_id}});
                        $(".modal-body #scheduletype").val('{{$schedule_type}}');
                        //$(".modal-body #main_venue").val(tournamentDetails['facility_name']);
                        //$(".modal-body #main_facility_id").val(tournamentDetails['facility_id']);
                        autofillsubtournamentdetails(tournamentDetails);
                    }
                });
	}
	
	//delete team
	function deleteTeam(tournament_id,tournament_group_id,id,team_id)
	{
		$.confirm({
			title: 'Confirmation',
			content: "Are you sure you want to delete this Team?",
			confirm: function() {
				
				$.ajax({
					url:  base_url+'/tournament/schedule/delete',
					type: "get",
					data:{'tournament_id': tournament_id,'tournament_group_id':tournament_group_id,'team_id':team_id},
					dataType: 'JSON',
					success: function(data) {
						if(data.msg=='true')
						{
							$.ajax({
								url:  base_url+'/tournament/team/delete/'+id,
								type: "get",
								dataType: 'JSON',
								success: function(data) {
									if(data.success!='')
									{
										$.alert({
											title: 'Alert!',
											content: data.success
										});
										$('#row_'+id).remove();
										 //replace requested team select box options
										var schedule_type = $('#schedule_type').val();
										 $.ajax({
											url: "{{URL('tournaments/getRequestedTeams')}}",
												type : 'GET',
												data : {'tournament_id':tournament_id,'schedule_type':schedule_type},
												dataType: 'json',
												success : function(response){
														var options = "<option value=''>Select Team</option>";
														$.each(response, function(key, value) {
														options += "<option value='" + key + "'>" + value + "</option>";
														});
														$(".req_team_class").html(options);
														$('.req_team_class').selectMultiple('refresh');

												}
										});
										
									}
								}
							});
						}
						else
						{
							$.alert({
									title: 'Alert!',
									content: data.msg
								});
						}
					}
				});
				
			},
			cancel: function() {
				// nothing to do
			}
		});

	}
	//delete Group
	function deleteGroup(tour_id,tournament_grp_id)
	{
		
		$.confirm({
			title: 'Confirmation',
			content: "Are you sure you want to delete this Group?",
			confirm: function() {
				
				$.ajax({
					url:  base_url+'/tournament/deleteGroupTeams',
					type: "get",
					dataType: 'JSON',
					data: {'tournament_id': tour_id,'tournament_group_id':tournament_grp_id},
					success: function(data) {
						if(data.status=='true')
						{
							$.ajax({
								url:  base_url+'/tournament/groupedit/'+'delete'+'/'+tournament_grp_id,
								type: "get",
								dataType: 'JSON',
								success: function(response) {
										$.alert({
										title: 'Alert!',
										content:  response.success
									});
									//location.reload();
									$('#group_'+tournament_grp_id).remove();
									
										 //replace requested team select box options
										var schedule_type = $('#schedule_type').val();
										 $.ajax({
											url: "{{URL('tournaments/getRequestedTeams')}}",
												type : 'GET',
												data : {'tournament_id':tour_id,'schedule_type':schedule_type},
												dataType: 'json',
												success : function(response){
														var options = "<option value=''>Select Team</option>";
														$.each(response, function(key, value) {
														options += "<option value='" + key + "'>" + value + "</option>";
														});
														$(".req_team_class").html(options);

												}
										});
									
								}
							});
						}else
						{
							$.alert({
									title: 'Alert!',
									content:  "{{trans('message.tournament.schedule_delete_fail')}}"
								});
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