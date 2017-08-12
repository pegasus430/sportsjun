
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

<!-- Display rubber modal -->
<!-- 
<div id='displayrubber'> </div> -->


<div class="col-sm-12">
<div class="row group-flex-content">



<?php $i=0;?>
@if(count($roundArray))

	<br>
	<div class="col-sm-12">
    <div class="pull-left col-xs-12 col-sm-6 "> <center><input  type='text' class=' form-control dark-border' placeholder="filter match e.g team name, date" onkeyup="filterDiv(this)"></center>
    </div>
{{--@if($isOwner)--}}
<div class="pull-right">
<a href='/download/schedules?tournament_id={{$tournament_id}}' class="btn-danger btn" name='match_schedule_tournament_{{$tournament_id}}'><i class="fa fa-download"></i> Download Schedule </a>
</div>
{{--@endif--}}
   </div>

        @foreach($roundArray as $round)
        	 <?php $bracket_name = Helper::getRoundStage($tournament_id, $round);
                $round_name = $bracket_name=='WINNER'?'THIRD POSITION' : $bracket_name; 
          ?>
                          

          <div class="col-sm-12">
                <div class="round-{{Helper::convert_number_to_words($round)}}">
                    <div class="round"><p>    {{$round_name}} </p></div>
                       @if($round==1)             
                                 @include('tournaments.sub_match_schedules')               
                         @else 

                         @if(count($bracketTeamArray))
                             @foreach($bracketTeamArray as $brk => $bracketTeam)  
                                      <?php $i++;?>
                                <?php $firstRoundBracketArray=$bracketTeam;?>
                                @include('tournaments.sub_match_schedules')
                             @endforeach                          
                         @endif   
                     @endif
                </div>
            </div>
@endforeach
</div>
</div>


@endif

@else
<div class="col-sm-12">
<div class="row group-flex-content">
 <div class="sj-alert sj-alert-info">
 {{ trans('message.tournament.final.nofinalstageteams') }}
 </div>
 </div>
 </div>
@endif

<script type="text/javascript">
// editable group final

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
				$.ajax({
						url: base_url + '/matchScheduleExistCheck/'+tournament_id+'/'+1, //  match check in group stage
						type: "get", 
						success: function(response) {
							if( response.match_count * 1 > 0 )
							{
								$.confirm({
									title: 'Confirmation',
									content: "Schedule is already created. Do you want to delete and recreate again?",
									confirm: function () {
										$("#is_knockout").val( 1 );
										$("#generateScheduleLeagueModal").modal();
									}
								});
							} else {
								$("#is_knockout").val( 1 );
								$("#generateScheduleLeagueModal").modal();
							}

						}
				});
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

                    $("#myteam").focus("");
				    $("#my_team_id").val("");
				    $("#oppteam").focus("");
				    $("#opp_team_id").val("");
				    $("#match_start_date").val("");
                    
                    var tournamentDetails = response['tournamentDetails'];
                    $(".modal-body #search_team_ids").val(response['searchTeamIds']);
                    $(".modal-body #tournament_round_number").val(roundNumber);
                    $(".modal-body #tournament_id").val(tournamentId);
//                    $(".modal-body #tournament_match_number").val(response['matchNumber']);
                    $(".modal-body #tournament_match_number").val(matchNumber);
                    $(".modal-body #scheduletype").val(response['scheduleType']);

                    $(".modal-body #myteam").attr("readonly", false); 
                    $(".modal-body #oppteam").attr("readonly", false);

                    $(".modal-body #myteam").focus(function(){
                        $('#myteam').focus();
                    });
                    $(".modal-body #oppteam").focus(function(){
                        $('#oppteam').focus();
                    });
                    
                   autofillsubtournamentdetails(tournamentDetails);
                }
            }
    });
}

</script>


				
	
<div id='response_to_download' style="display:none">

</div>			
<div id='by_pass'>
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



function downloadPdf(that){
	var src =$(that); 
	var link =src.attr('link');
	var d = new Date();	
	var name = src.attr('name') + '_'+d.getTime();

	$.ajax({
		url:link,
		success:function(response){
		
		$('#response_to_download').html(response);
		var specialElementHandlers = {
				'#by_pass': function(element, renderer) {
				return false;
				}
			}

        var printDoc = new jsPDF('p', 'pt', 'a4');
        printDoc.fromHTML($('#response_to_download').html(), 15, 15, {
        	'width': 800,
        	'elementHandlers': specialElementHandlers
           
        }, function(){      
        	
         printDoc.save(name+'.pdf');    
            
         }); 
   
		}
	})
}
	

</script>
