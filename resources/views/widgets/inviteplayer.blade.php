
					<div class="sportsjun-forms">
					<p  style="color:#a94442;" class="help-block" id="inviteResponse"  style="display:none"></p>
					   <p class="alert alert-success" id="inviteResponse1" style="display:none" ></p>
						<label class="tab_new_label_txt">Non-Registered User</label>
			           
					
						<input type="hidden" name="team_id" value="{{ $team_id }}" >
                        
						<div class="form-group">{!! Form::text('name', null, array('class'=>'gui-input','id'=>'id1','placeholder'=>'Name')) !!}</div>
                        <div class="form-group">{!! Form::text('email', null, array('class'=>'gui-input','id'=>'id2','placeholder'=>'Email (optional)')) !!}</div>
					  	
						<button type="button" onClick ='Inviteplayer();' id='button' class="button btn-primary">Invite</button>
					</div>					
               
<script type="text/javascript">
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
	function Inviteplayer()
	{
		var name = $('#id1').val();
		var email = $('#id2').val();
		var teamid = $('#team_id').val();
		// $("#invitenameResponse").html('');
		// $("#inviteemailResponse").html('');
		 $("#nameResponse").html('');
		$("#inviteResponse").html('');
		$("#inviteResponse").hide();
		$("#inviteResponse1").html('');
		$("#inviteResponse1").hide();
		$("#Response").hide();
                
                if(!name) {
					
                    $("#inviteResponse").html("{{trans('message.sports.emptyname')}}");
					$("#inviteResponse").show();
							setTimeout(function() { $("#inviteResponse").hide(); }, 3000);
                    return false;
                }
		$.ajax({
			url: "{{URL('getplayers')}}",
			type : 'POST',
			data : {name:name,email:email,teamid:teamid},
			dataType: 'json',
			beforeSend: function () {
				//$.blockUI({ width:'50px', message: $("#spinner").html() });
			},
			success : function(data) {
				if(data.status == 'success') {
					
						$('#id1').val('');
						$('#id2').val('');
						suggestedWidget('players', team_id, $('#sport_id').val(),'team_to_player','');
						$("#team_players_div").html(data.html);
						$(".players_row_left").html("<h4>Players ("+($('.player_inactive').length+$('.player_active').length)+")</h4>");
					$("#inviteResponse1").append(data.msg);
					$("#inviteResponse1").show();
					setTimeout(function() { $("#inviteResponse1").hide(); }, 3000);
				}
				if(data.status == 'fail') {
					$.each(data.msg, function(key, value){
							
                                        	$("#inviteResponse").html(value);
											$("#inviteResponse").show();
							setTimeout(function() { $("#inviteResponse").hide(); }, 3000);
					});
				}
			}
		});
	}
        
        $(function() {
            $("#id1").val('');
            $("#id2").val('');
        });
</script>   
