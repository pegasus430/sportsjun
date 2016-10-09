{!! Form::open(array('url' => '', 'files'=> true)) !!}

                    <div class="sportsjun-forms">
					<p style="color:#a94442;" class="help-block" id="nameResponse" style="display:none"></p>
						
						 <p class="alert alert-success" id="Response" style="display:none"></p>
                    	<label class="tab_new_label_txt">Registered User</label>
						
                        <div class="form-group"><input id="user" class="gui-input" placeholder="Name"></div>
                        <div class="form-group"><input id="response" name="response" class="form-control" type="hidden"></div>
                        <input type="hidden" id="sport_id" name="sport_id" value='{{ !empty($sport_id)?$sport_id:"null" }}'>
                        <input type="hidden" name="team_id" id="team_id" value="{{ $team_id }}">
                        <meta name="_token" content="<?php echo csrf_token(); ?>" />
                        <button type="button" name="add_player" id="add_player" onClick="addPlayer();" class="button btn-primary">
                            Add Player
                        </button>
                       
                    </div>
               
{!!Form::close()!!}
<script>

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
    $(function() {
                $('#user').val();
		 var sport_id = $('#sport_id').val();
		 var team_id = $('#team_id').val();
        $("#user").autocomplete({
            source: base_url+'/searchUser/'+sport_id+'/'+team_id,
            minLength: 3,
			response: function(event, ui) {
				if (!ui.content.length) {
					var noResult = { value:"",label:"No results found" };
					ui.content.push(noResult);
				} else {
				   // $("#response").empty();
				}
			},
            select: function(event, ui) {
                $('#response').val(ui.item.id);
            }
        });
    });
    function addPlayer()
    {
        var response = $('#response').val();
        var team_id = $('#team_id').val();
        $("#nameResponse").html('');
		  $("#nameResponse").hide();
		$("#Response").hide();
		$("#inviteResponse").html('');
        if(!response) {
            $("#nameResponse").html("{{trans('message.sports.emptyplayername')}}");
			$("#nameResponse").show();
						setTimeout(function() { $("#nameResponse").hide(); }, 3000);
            return false;
        }
        $.ajax({
            url: base_url+'/addplayer',
            type: "post",
            dataType: 'JSON',
            data: {'response': response,'team_id':team_id},
            success: function(data) {
                if(data.status == 'success') {
						$("#Response").html('');
							
                        $("#Response").append(data.msg);
						$("#Response").show();
						setTimeout(function() { $("#Response").hide(); }, 3000);
						// $('.addTeamPlayer').append(data.html);
                        suggestedWidget('players', team_id, $('#sport_id').val(),'team_to_player','');
                        $("#team_players_div").html(data.html);
                        $(".players_row_left").html("<h4>Players ("+($('.player_inactive').length+$('.player_active').length)+")</h4>");
						$('#user').val('');
                        //location.reload();
                }else
                {
                        $("#nameResponse").append(data.msg);
						$("#nameResponse").show();
						setTimeout(function() { $("#nameResponse").hide(); }, 3000);
                }
            }
        });
    }

</script>