<div class="modal fade"  id="myTeamsPopUpModal" role="dialog">
	<div class="modal-dialog sj_modal">
	  <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Select Teams {{ !empty($head_text)?$head_text:'' }}</h4>
			</div>
			<div class="alert alert-success" id="div_success_teams_popup" style="display:none;"></div>
			<div class="alert alert-danger" id="div_failure_teams_popup" style="display:none;"></div>			
			<div class="modal-body">
		        <div class="sportsjun-forms sportsjun-container wrap-2 sportsjun-forms-modal">
			        <div class="form-body">	
			        	{!! Form::hidden('hid_flag', '', array('id' => 'hid_flag')) !!}
			        	{!! Form::hidden('hid_val', '', array('id' => 'hid_val')) !!}
			        	{!! Form::hidden('hid_spid', '', array('id' => 'hid_spid')) !!}
			        	<div id="jsdiv" class="clearfix"></div>
			        </div>
			    </div>
			</div>
			<div class="modal-footer">
				<div id="footer_div" class="clearfix"></div>
			</div>		
		</div>
	</div>
</div>
<script type="text/javascript">
// $("#save_data").click(function(){
// $("#save_data").on("click", function(){	
$(document).on('click','#save_data',function(){
	$("#save_data").before("<div id='loader'></div>");
	$("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
	$("#save_data").hide();
    var team_ids = [];
    $(':checkbox:checked').each(function(i){
    	if($.isNumeric($(this).val()))
    	{
    		team_ids[i] = $(this).val();
    	}
    });
    $.post(base_url+'/team/saverequest',{flag:$("#hid_flag").val(),player_tournament_id:$("#hid_val").val(),team_ids:team_ids},function(response,status){
    	
		if(status == 'success')
		{
			if(response.status == 'success')
			{
				
				$("#div_success_teams_popup").show();
				$("#div_success_teams_popup").html("Request sent successfully.");
				$("#div_failure_teams_popup").hide();
				$("#hid_flag").val('');
				$("#hid_val").val('');
				$('.modal .modal-body').animate({scrollTop:0},500);

				if (window.location.href.indexOf("team/") > -1) {
		            var href = window.location.href;
		            var team_select = href.substr(href.lastIndexOf('/') + 1).match(/\d+/);
		            suggestedWidget('players', team_select[0], $("#hid_spid").val(),'team_to_player','');
		            $.post(base_url+'/team/getTeamPlayersDiv',{team_id:team_select[0]},function(response,status){
		            	if(status == 'success' && response.status == 'success')
		            	{
		            		$("#team_players_div").html(response.html);
		            		$(".players_row_left").html("<h4>Players ("+($('.player_inactive').length+$('.player_active').length)+")</h4>");
		            	}
		            });
		            window.setTimeout(function(){$('#myTeamsPopUpModal').modal('toggle')},1000)
		        }
		        else
		        {
		        	window.setTimeout(function(){$('#myTeamsPopUpModal').modal('toggle')},1000)	
		        }
				
			}
			else if(response.status == 'exist')
			{
				$("#div_failure_teams_popup").show();
				if($("#hid_flag").val() == 'TEAM_TO_TOURNAMENT')
				{
					$("#div_failure_teams_popup").html("Join request already sent.");
				}
				else
				{
					$("#div_failure_teams_popup").html("Request already sent.");
				}
				$("#div_success_teams_popup").hide();
				$("#hid_flag").val('');
				$("#hid_val").val('');						
				$('.modal .modal-body').animate({scrollTop:0},500);
				window.setTimeout(function(){$('#myTeamsPopUpModal').modal('toggle')},1000)					
			}
			else
			{
				$("#div_failure_teams_popup").show();
				$("#div_failure_teams_popup").text("Failed to send the request.");
				$("#div_success_teams_popup").hide();
				$("#hid_flag").val('');
				$("#hid_val").val('');						
				$('.modal .modal-body').animate({scrollTop:0},500);
				window.setTimeout(function(){$('#myTeamsPopUpModal').modal('toggle')},1000)					
			}
		}
	    else
		{
			$("#div_failure_teams_popup").show();
			$("#div_failure_teams_popup").text("Failed to send the request.");
			$("#div_success_teams_popup").hide();
			$("#hid_flag").val('');
			$("#hid_val").val('');					
			$('.modal .modal-body').animate({scrollTop:0},500);
			window.setTimeout(function(){$('#myTeamsPopUpModal').modal('toggle')},1000)
		}
    })
});
//match type drop down on change
$(document).on('change',"#match_type", function(){
    if($("#match_type").val() == 'player_to_tournament')
	{
		$("#div_teams").css("display", "none");
	}
	else if($("#match_type").val() == 'team_to_tournament')
	{
		$("#div_teams").css("display", "block");
	}	
});

</script>