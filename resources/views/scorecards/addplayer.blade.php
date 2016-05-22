<html lang="en">
<head>
<script type="text/javascript">
$(document).ready(function(){
	$('.launch-modaal').click(function(){
		$("#loader").remove();
		$('#savebutton').show();
		$('#myModaal').modal({
			backdrop: 'static'
		});
	}); 
});
</script>
</head>
<body>
    <!-- Button HTML (to Trigger Modal) -->
    <input type="button" class="button btn-tertiary launch-modaal" value="{{ trans('message.scorecard.fields.addplayer') }}">
    <p class="help-block" id="Response"></p> 
    <!-- Modal HTML -->
    <div id="myModaal" class="modal fade">
        <div class="modal-dialog sj_modal sportsjun-forms">
            <div class="modal-content">
                <div  class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">{{ trans('message.scorecard.fields.addplayer') }}
					</h4>
                </div>
@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
@endif
				<div class="alert alert-danger" id="div_failure1"></div>
				<div class="alert alert-success" id="div_success1" style="display:none;"></div>
                <div class="modal-body">
                {!! Form::open(array('url'=>'/match/addPlayertoTeam','class'=>'form-horizontal','files' => true,'id' => 'frm_add_player')) !!} 
				<div class="form-group">
                        <label class="col-md-4 control-label">{{ trans('message.scorecard.fields.selectteam') }}</label>
                        <div class="col-md-6">
                        	<div class="section" style="margin-bottom: 0 !important;">
                                <label class="select">
                                    <select name="select_team" id="select_team">
                                        <option  value="{{ $match_data[0]['a_id'] }}" data-status="a" >{{ $team_a_name }}</option>
                                        <option  value="{{ $match_data[0]['b_id'] }}" data-status="b">{{ $team_b_name }}</option>
                                    </select>
                                    <i class="arrow double"></i>
							 		<p class="help-block" id="nameResponse"></p> 
                                </label>
                            </div>
                        </div>
                </div>
				<div class="form-group">
					<label class="col-md-4 control-label">{{ trans('message.scorecard.fields.player') }}</label>
					<div class="col-md-6">
                    	<div class="section" style="margin-bottom: 0 !important;">
							<label class="field">
							{!! Form::text('name', null, array('required','class'=>'form-control','style'=>'resize:none','id'=>'ex_player')) !!}
							{!! Form::hidden('response', null, array('class'=>'form-control','style'=>'resize:none','id'=>'response')) !!}
							@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                            </label>
                        </div>
					</div>
				</div>
				<meta name="_token" content="<?php echo csrf_token(); ?>" />
			
				
                </div>
                <div class="modal-footer">
                    <button type="button" id="savebutton" class="button btn-primary" onclick="addPlayertoTeam();">Save</button>
                    <button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
                </div>
				{!! Form::close() !!}
				{!! JsValidator::formRequest('App\Http\Requests\ScoreCardRequest', '#frm_add_player'); !!}
            </div>
        </div>
    </div>
</body>
</html> 
<script>

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
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
var sport_id = "{{ $match_data[0]['sports_id'] }}";
var team_id = $('#select_team').val();
 $(function() {
        $("#ex_player").autocomplete({
            //source: base_url+'/source/'+sport_id+'/'+team_id,
			source: base_url+'/searchUser/'+sport_id+'/'+team_id,
            minLength: 3,
            // select: function(event, ui) {
                // $('#response').val(ui.item.id);
            // }
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
	function addPlayertoTeam()
	{
		if($('#frm_add_player').valid()) //if form is valid
		{
			//var form_id = "{{!empty($form_id)?$form_id:''}}";
			var token = "<?php echo csrf_token(); ?>";
			var response = $('#response').val();
			var team_id = $('#select_team').val();
			var match_id = "{{ $match_data[0]['id'] }}";
			var selected_team = $('#select_team option:selected').data('status');//get select box option attribute value
			
			 $("#savebutton").before("<div id='loader'></div>");
		  $("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
		   $('#savebutton').hide();
			
			$.ajax({
				url: base_url+'/match/addPlayertoTeam',
				type: "post",
				dataType: 'JSON',
				data: {'_token': token, 'response': response, 'team_id':team_id,'match_id':match_id,'selected_team':selected_team},
				success: function(data) {
					if(data.success)
					{
						$("#div_success1").text(data.success);
						$("#div_success1").show();
						 $("#loader").remove();
					}else{
						// console.log(data.success);
						$("#div_failure1").text(data.failure);
						$("#div_failure1").show();
						$("#loader").remove();
						$('#savebutton').show();
					}
					
					$('#myModal').scrollTop(0);
					//on success reload the page
					//window.setTimeout(function(){location.reload(true)},2000)
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
				
			});
		}
		else{
			return true;
		}
	}
	
</script>