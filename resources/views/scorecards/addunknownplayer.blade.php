<html lang="en">
<head>
<script type="text/javascript">
$(document).ready(function(){
	$('.launch-modals').click(function(){
		$('#unknown_player').val("");
		$('#user_email').val("");
		$("#loaderbtn").remove();
		$('#savebtn').show();
		$('#myModals').modal({
			backdrop: 'static'
		});
	}); 
});
</script>
</head>
<body>
    <!-- Button HTML (to Trigger Modal) -->
    <input type="button" class="button btn-primary launch-modals" value="{{ trans('message.scorecard.fields.addunkownplayer') }}">
    <p class="help-block" id="Response"></p> 
    <!-- Modal HTML -->
    <div id="myModals" class="modal fade">
        <div class="modal-dialog sj_modal sportsjun-forms">
            <div class="modal-content">
                <div  class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">{{ trans('message.scorecard.fields.addunkownplayer') }}
					</h4>
                </div>
@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
@endif
				<div class="alert alert-danger" id="div_failures"></div>
				<div class="alert alert-success" id="div_successss" style="display:none;"></div>
                <div class="modal-body">
                {!! Form::open(array('class'=>'form-horizontal','files' => true,'id'=>'frm_add_new_player')) !!} 
				<div class="form-group">
                        <label class="col-md-4 control-label">{{ trans('message.scorecard.fields.selectteam') }}</label>
                        <div class="col-md-6">
                        	<div class="section" style="margin-bottom: 0 !important;">
                                <label class="select">
                                    <select name="selected_team" id="selected_team" class="gui-select">
                                    <option  value="{{ $match_data[0]['a_id'] }}" data-status="a" >{{ $team_a_name }}</option>
                                    <option  value="{{ $match_data[0]['b_id'] }}" data-status="b">{{ $team_b_name }}</option>
                                    </select>
                                    <i class="arrow double"></i>
                                </label>
                            </div>
                        </div>
                </div>
				<div class="form-group">
					<label class="col-md-4 control-label">{{ trans('message.scorecard.fields.player') }}</label>
					<div class="col-md-6">
                    	<div class="section" style="margin-bottom: 0 !important;">
							<label class="field">
                            {!! Form::text('name', null, array('class'=>'gui-input','style'=>'resize:none','id'=>'unknown_player')) !!}
                            <span class="help-block" id="nameResponse"></span>
                           	</label>
                       	</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">{{ trans('message.scorecard.fields.email') }}</label>
					<div class="col-md-6">
                    	<div class="section" style="margin-bottom: 0 !important;">
                        	<label class="field">
                            	{!! Form::text('email', null, array('class'=>'gui-input','style'=>'resize:none','id'=>'user_email')) !!}
                                <span class="help-block" id="emailResponse"></span>	
                            </label>
                        </div>
					</div>
				</div>
				<meta name="_token" content="<?php echo csrf_token(); ?>" />
			
				
                </div>
                <div class="modal-footer">
                    <button type="button" id="savebtn" class="button btn-primary" onclick="Inviteunknownplayer();">Save</button>
                    <button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
                </div>
				{!! Form::close() !!}
				{!! JsValidator::formRequest('App\Http\Requests\ScoreCardRequest', '#frm_add_new_player'); !!}
            </div>
        </div>
    </div>
</body>
</html> 
<script>
var form_id = "{{!empty($form_id)?$form_id:''}}";
var sports_id = "{{$match_data[0]['sports_id']}}";
var matchType = "{{$match_data[0]['match_type']}}";

	$(window).load(function(){
		if(sports_id==1)
		{
			if(matchType=='test')
			{
				$('#cricketsecond_form_data').val($('#secondting').serialize());
			}	
			$('#cricketfirsting_form_data').val($('#firsting').serialize());
		}else
		{
			$('#'+form_id+'_form_data').val($('#'+form_id).serialize());
		}
	});
	
	function Inviteunknownplayer()
	{
		if($('#frm_add_new_player').valid()) //if form is valid
		{
			var token = "<?php echo csrf_token();?>"; 
			 name = $('#unknown_player').val();
			 var teamid = $('#selected_team').val();
			 email = $('#user_email').val();
			  $("#nameResponse").html('');
			   $("#emailResponse").html('');
			   $("#savebtn").before("<div id='loaderbtn'></div>");
		  $("#loaderbtn").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
		   $('#savebtn').hide();
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
					   
					   $("#div_successss").text("{{trans('message.sports.teamplayer')}}");
						$("#div_successss").show();
						$('#myModals').scrollTop(0);
						 $("#loaderbtn").remove();
						//on success reload the page
						//window.setTimeout(function(){location.reload(true);},2000)
						//$("#winner_team_id").val("");
						
						if(sports_id==1) //if cricket
						{
							var orgFormData = $('#cricketfirsting_form_data').val();
							var newFormData = $('#firsting').serialize();
							
								SJ.SCORECARD.initTeamStats();
								$('#hid_match_result').val($('#match_result').val());
							
							
							var second_ing = 1;
							if(matchType=='test')
							{
								var orgFormData2 = $('#cricketsecond_form_data').val();
								var newFormData2 = $('#secondting').serialize();
								if(orgFormData2 != newFormData2)
								{
									second_ing = 0;
								}
							}
							
							if(orgFormData != newFormData || second_ing==0){
								$.confirm({
									title: 'Confirmation',
									content: 'Do you want to save scores?',
									confirm: function() {
										var $form1 = $("#firsting");
										$.post($form1.attr("action"), $form1.serialize(), function () {
											
											if(matchType=='test')
											{
												$form2 = $("#secondting");
												$.post($form2.attr("action"), $form2.serialize(), function () {
													window.setTimeout(function(){location.reload(true);},2000)
												});
											}
											window.setTimeout(function(){location.reload(true);},2000)	
										});
									},
									cancel: function() {
											window.setTimeout(function(){location.reload(true);},2000)
										}
								});
							
							}else{
								window.setTimeout(function(){location.reload(true);},2000)
							}
						}else
						{
							var orgFormData = $('#'+form_id+'_form_data').val();
							var newFormData = $('#'+form_id).serialize();
							
							if(orgFormData != newFormData){
								$.confirm({
									title: 'Confirmation',
									content: 'Do you want to save scores?',
									confirm: function() {
										$('#'+form_id).submit();
									},
									cancel: function() {
											window.setTimeout(function(){location.reload(true);},2000)
										}
									});
							
							}else{
								window.setTimeout(function(){location.reload(true);},2000)
							}
						}
						
						
						
						
						// if(form_id!='')
							// window.setTimeout(function(){$('#'+form_id).submit();},2000)
						// else
							// window.setTimeout(function(){location.reload(true);},2000)
					
				   }
				  if(data.status == 'fail') {
				  // console.log(data.success);
					$("#div_failures").text(data.failure);
					$("#div_failures").show();
					$("#loaderbtn").remove();
						$('#savebtn').show();
						   
					  }

				}
			});
		}
		else{
			return true;
		}
	}
</script>