

<div class="col-sm-12" style="background:#eee;border:#eee inset 1px; ">
		<div class="col-sm-8 col-sm-offset-2 sidebar-right ">
				

          
                <div class="panel-body" style="background:white">

                 <div class="table-responsive">
                 		<table class="table">
                 		<thead>
                 		  <tr>
                 			<th> Match Referees </th>
                 			<th>  </th>
                 		</tr>
                 		</thead>
                 		<tbody>
                 			<tr class="record">
                 				<td>Yossa michel </td>
                 				<td><button class="btn btn-circle btn-danger"><i class="fa fa-remove"></i></button></td>
                 			</tr>

                 		</tbody>
                  </table>

                 </div>
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active"><a href="#addplayer" data-toggle="tab" aria-expanded="false">Add Referee</a></li>
                        <li class=""><a href="#inviteplayer" data-toggle="tab" aria-expanded="true">Invite Referee</a></li>
                    </ul>
                    <div class="tab-content">
                    <br>
                        <div class="tab-pane fade active in" id="addplayer">


                    <div class="sportsjun-forms">
					<p style="color:#a94442;" class="help-block" id="nameResponse"></p>
						
						 <p class="alert alert-success" id="Response" style="display:none"></p>
                    	<label class="tab_new_label_txt">Registered User</label>
						
                        <div class="form-group"><input id="user" class="gui-input ui-autocomplete-input" placeholder="Name" autocomplete="off"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span></div>
                        <div class="form-group"><input id="response" name="response" class="form-control" type="hidden"></div>                   
                      
                        <button type="button" name="add_player" id="add_player" onclick="addPlayer();" class="button btn-primary">
                            Add Referee
                        </button>                       
                    </div>
		         
		           </div>
		          
                        <div class="tab-pane fade " id="inviteplayer">
				
                            <div class="sportsjun-forms">
					<p style="color:#a94442;" class="help-block" id="inviteResponse"></p>
					   <p class="alert alert-success" id="inviteResponse1" style="display:none"></p>
						<label class="tab_new_label_txt">Non-Registered User</label>
	
                        
						<div class="form-group"><input class="gui-input" id="id1" placeholder="Name" name="name" type="text"></div>
                        <div class="form-group"><input class="gui-input" id="id2" placeholder="Email (optional)" name="email" type="text"></div>
					  	
						<button type="button" onclick="Inviteplayer();" id="button" class="button btn-primary">Invite</button>
					</div>	
		</div>




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
	
        $("#user").autocomplete({
            source: base_url+'/searchUser',
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
        var match_id =  {{$match_data[0]['id']}}
        $("#nameResponse").html('');
		  $("#nameResponse").hide();
		$("#Response").hide();
		$("#inviteResponse").html('');
        if(!response) {
            $("#nameResponse").html("Enter Player Name");
			$("#nameResponse").show();
						setTimeout(function() { $("#nameResponse").hide(); }, 3000);
            return false;
        }
        $.ajax({
            url: base_url+'/add_referee',
            type: "post",
            dataType: 'JSON',
            data: {'response': response,'match_id':match_id},
            success: function(data) {
                if(data.status == 'success') {
						$("#Response").html('');
							
                        $("#Response").append(data.msg);
						$("#Response").show();
						setTimeout(function() { $("#Response").hide(); }, 3000);					
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
		var match_id = {{$match_data[0]['id']}}
		// $("#invitenameResponse").html('');
		// $("#inviteemailResponse").html('');
		 $("#nameResponse").html('');
		$("#inviteResponse").html('');
		$("#inviteResponse").hide();
		$("#inviteResponse1").html('');
		$("#inviteResponse1").hide();
		$("#Response").hide();
                
                if(!name) {
					
                    $("#inviteResponse").html("Enter Name");
					$("#inviteResponse").show();
							setTimeout(function() { $("#inviteResponse").hide(); }, 3000);
                    return false;
                }
		$.ajax({
			url: "http://localhost:8000/invite_referee",
			type : 'POST',
			data : {name:name,email:email, match_id:match_id},
			dataType: 'json',
			beforeSend: function () {
				//$.blockUI({ width:'50px', message: $("#spinner").html() });
			},
			success : function(data) {
				if(data.status == 'success') {
					
						$('#id1').val('');
						$('#id2').val('');
					
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
                        </div>
                    </div>
                </div>
            </div>
            </div>