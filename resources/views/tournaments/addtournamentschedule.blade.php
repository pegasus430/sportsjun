<style>
.ui-widget-content{z-index:9999;}
.ui-autocomplete {
    position: absolute;
}
#container_my_team,#container_opp_team {
    display: block; 
    position:relative
} 
/*.alert{display: none;}*/
</style>
<script src="http://malsup.github.com/jquery.form.js"></script>
<!-- Modal -->
{!! Form::open(['route' => 'addschedule','class'=>'form-horizontal','method' => 'POST','id' => 'frm_add_schedule']) !!} 
<div class="modal fade"  id="myModal" role="dialog">
	<div class="modal-dialog sj_modal">
	  <!-- Modal content-->
	  <div class="modal-content sportsjun-forms">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">{{ trans('message.schedule.fields.schedulematch') }}</h4>
		</div>
@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
@endif
		<div class="alert alert-success" id="div_success_tourney" style="display:none;"></div>
		<div class="alert alert-danger" id="div_failure_tourney" style="display:none;"></div>
		<div class="modal-body">
	        <div class="sportsjun-forms sportsjun-container sportsjun-forms-modal">
		        <div class="form-body">
			        <div class="spacer-b30">
						<div class="tagline"><span>{{ trans('message.schedule.fields.scheduletype') }}</span></div>
					</div>
					{!! Form::hidden('schedule_id', null , array('id' => 'schedule_id')) !!}
					{!! Form::hidden('tournament_id', $tournament_id , array('id' => 'tournament_id')) !!}
					{!! Form::hidden('tournament_group_id', null , array('id' => 'tournament_group_id')) !!}
                                        {!! Form::hidden('search_team_ids', null , array('id' => 'search_team_ids')) !!}
                                        {!! Form::hidden('tournament_round_number', null , array('id' => 'tournament_round_number')) !!}
                                        {!! Form::hidden('tournament_match_number', null , array('id' => 'tournament_match_number')) !!}
                                        {!! Form::hidden('scheduletype', $schedule_type , array('id' => 'scheduletype')) !!}
                                        {!! Form::hidden('is_tournament', 1 , array('id' => 'is_tournament')) !!}
					
                    <div class="row">
                    	<div class="col-sm-6">
                                        <div class="section" id="byeDiv" style="display:none;">
                           
						<label class="field select">
							{!! Form::select('bye', $byeArray,null,['class'=>'gui-input','placeholder'=>trans('message.schedule.fields.match'),'id'=>'bye']
							) !!}					
							<i class="arrow double"></i> 
						</label>
					</div>
                    	</div>
                        <div class="col-sm-6">
                                        <div class="section" id="byeTextDiv" style="display:none;">
                                              <label for="byetext" class="field prepend-icon">
                                                  {!! Form::text('byetext', 'Bye', array('required', 'class'=>'gui-input', 'id'=>'byetext', 'readonly'=>'true','onfocus'=>'this.blur()')) !!}
                                              </label>
                                        </div>
						</div>
                   	</div>
					<div class="row">
                    	<div class="col-sm-6">                
                        	<div class="section">
                             <label class="form_label">{{   trans('message.schedule.fields.myteam') }} <span  class='required'>*</span> </label>    
						<label for="myteam" class="field prepend-icon">
							{!! Form::text('myteam',(isset($team_name)?$team_name:''), array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.selectteam'),'id'=>'myteam','autocomplete'=>'off')) !!}
							<div id="container_my_team"></div>
							{!! Form::hidden('my_team_id', (!empty($teamId)?$teamId:''), array('id' => 'my_team_id')) !!}
							{!! Form::hidden('sports_id', (!empty($sports_id)?$sports_id:'') , array('id' => 'sports_id')) !!}
							@if ($errors->has('myteam')) <p class="help-block">{{ $errors->first('myteam') }}</p> @endif
							<label for="myteam" class="field-icon"><i class="fa fa-user"></i></label>
						</label>
					</div>
                    	</div>
                        <div class="col-sm-6">
                        	<div class="section" id="oppTeamDiv">
                             <label class="form_label">{{   trans('message.schedule.fields.opponentteam') }} <span  class='required'>*</span> </label>    
						<label for="oppteam" class="field prepend-icon">
							{!! Form::text('oppteam',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.selectteam'),'id'=>'oppteam','autocomplete'=>'off')) !!}
							<div id="container_opp_team"></div>
							{!! Form::hidden('opp_team_id', '', array('id' => 'opp_team_id')) !!}
							@if ($errors->has('oppteam')) <p class="help-block">{{ $errors->first('oppteam') }}</p> @endif
							<label for="oppteam" class="field-icon"><i class="fa fa-user"></i></label>
						</label>
					</div>
                    	</div>
                    </div>
                    <div class="row">
                    	<div class="col-sm-6">
                        	<div class="section" id="matchStartDatediv">
                                              <label class="form_label">{{   trans('message.schedule.fields.start_date') }}<span  class='required'>*</span> </label>              
						<label for="match_start_date" class="field prepend-icon">
							<div class='input-group date' id='matchStartDate'>
								{!! Form::text('match_start_date',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.start_date'),'id'=>'match_start_date')) !!}
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
								@if ($errors->has('match_start_date')) <p class="help-block">{{ $errors->first('match_start_date') }}</p> @endif
							</div>
						</label>
					</div>
                    	</div>
                        <div class="col-sm-6">
                    		<div class="section" id="matchStartTimeDiv">
                                                                          <label class="form_label">{{   trans('message.schedule.fields.start_time') }}</label>              

						<label for="match_start_time" class="field prepend-icon">
							<div class='input-group date' id='matchStartTime'>
								{!! Form::text('match_start_time',null, array('class'=>'gui-input','placeholder'=>trans('message.schedule.fields.start_time'),'id'=>'match_start_time')) !!}
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
								@if ($errors->has('match_start_time')) <p class="help-block">{{ $errors->first('match_start_time') }}</p> @endif
							</div>
						</label>
					</div>
                    	</div>
                    </div>
                                        
					<!-- <div class="section">
						<label for="end_time" class="field prepend-icon">
							<div class='input-group date' id='match_end_time'>
                                                            {{--
								{!! Form::text('end_time',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.end_time'),'id'=>'end_time')) !!}
                                                            --}}    
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
                                                            {{--
								@if ($errors->has('end_time')) <p class="help-block">{{ $errors->first('end_time') }}</p> @endif
                                                            --}}     
							</div>
						</label>
					</div>-->

					<div class="row" >
                    	<div class="col-sm-6">
                        	<div class="section">
                                              <label class="form_label">{{   trans('message.schedule.fields.playertype') }} </label>                
                            <label class="field select">
                                {!! Form::select('player_type', $player_types,null,['class'=>'gui-input','placeholder'=>trans('message.schedule.fields.playertype'),'id'=>'player_type']
                                ) !!}					
                                @if ($errors->has('player_type')) <p class="help-block">{{ $errors->first('player_type') }}</p> @endif
                                <i class="arrow double"></i> 
                            </label>
                        </div>
                    	</div>
                        <div class="col-sm-6">
                        	<div class="section">
                                              <label class="form_label">{{   trans('message.schedule.fields.matchtype') }}</label>                

						<label class="field select">
							{!! Form::select('match_type',$match_types,null,['class'=>'gui-input','placeholder'=>trans('message.schedule.fields.matchtype'),'id'=>'match_type']
							) !!}
							@if ($errors->has('match_type')) <p class="help-block">{{ $errors->first('match_type') }}</p> @endif
							<i class="arrow double"></i> 
						</label>
					</div>
                    	</div>
                    </div>
                                                            
					<div class="section">
                                  <label class="form_label">{{   trans('message.schedule.fields.venue') }}<span  class='required'>*</span> </label>               
						<label for="venue" class="field prepend-icon">
							{!! Form::text('venue',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.venue'),'id'=>'venue')) !!}
							{!! Form::hidden('facility_id', '', array('id' => 'facility_id')) !!}
							{!! Form::hidden('is_edit', '', array('id' => 'is_edit')) !!}
							@if ($errors->has('venue')) <p class="help-block">{{ $errors->first('venue') }}</p> @endif
							<label for="venue" class="field-icon"><i class="fa fa-user"></i></label>
						</label>
					</div>
					@include ('common.address',['mandatory' => ''])
                    
				</div>
	        </div>
        </div>
		<div class="modal-footer">
			<button type="button" name="save_schedule" id="save_schedule" class="button btn-primary">Schedule</button>
			<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
		</div>
	  </div>
	  
	</div>
</div>
{!! Form::close() !!}
{!! JsValidator::formRequest('App\Http\Requests\AddSchedulesRequest', '#frm_add_schedule'); !!}
<script type="text/javascript">
    $(document).ready(function() {
        $("#save_schedule").show();
    	$("#matchStartDate").datetimepicker({ format: '{{ config("constants.DATE_FORMAT.JQUERY_DATE_FORMAT") }}' });
        $('#matchStartTime').datetimepicker({ format: '{{ config("constants.DATE_FORMAT.JQUERY_TIME_FORMAT") }}' });
		/*$("#match_end_time").datetimepicker({ format: '{{ config("constants.DATE_FORMAT.JQUERY_DATE_TIME_FORMAT") }}',useCurrent: false });
		$("#match_start_time").on("dp.change", function (e) {
            $('#match_end_time').data("DateTimePicker").minDate(e.date);
        });
        $("#match_end_time").on("dp.change", function (e) {
            $('#match_start_time').data("DateTimePicker").maxDate(e.date);
        });*/ 
		//on page load
		$("#schedule_match_btn").click(
			function()
			{
				//clearing all the values on modal window load
				$("#myteam").val('');
		    	$("#my_team_id").val('');
		    	$("#oppteam").val('');
		    	$("#opp_team_id").val('');
		    	$("#start_time").val('');
		    	$("#end_time").val('');
		    	$("#venue").val('');
		    	$("#address").val('');
		    	$("#state_id").val('');
		    	$("#city_id").val('');
		    	$("#zip").val('');
		    	$("#facility_id").val('');
		    	$("#player_type").val('');
		    	$("#match_type").val('');
		    	$("#is_edit").val('');
		    	$("#schedule_id").val('');
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
			}
		);
		
		//on radio button change
		$('input[name=scheduletype]').change(
			function()
			{
				//if team is selected
				if($('input[name=scheduletype]:checked').val() === "team")
				{
					$("#my_team").html('My Team');
					$("#opp_team").html('Opponent Team');
					$("#oppteam").val('');
					$("#opp_team_id").val('');
					$("#myteam").val('');
					$("#my_team_id").val('');
				}
				else //if player is selected
				{
					$("#my_team").html('My Player');
					$("#opp_team").html('Opponent Player');
					$("#oppteam").val('');				
					$("#opp_team_id").val('');
					$("#myteam").val('');
					$("#my_team_id").val('');
				}			
			}
		);
		
		//for autocomplete my team or player		
		$("#myteam").autocomplete({
			source: function(request, response) {
				$.getJSON(base_url+"/getteamdetails", {team_id : $('#opp_team_id').val(), tournament_id : '{{ $tournament_id }}', tournament_group_id : $('#tournament_group_id').val() , scheduletype : $('#scheduletype').val(), search_team_ids : $('#search_team_ids').val(),tournament_round_number : $('#tournament_round_number').val(), term: request.term} , response);
			},
			minLength: 3,
			change: function(event,ui) { 
				if (ui.item==null || ui.item==undefined) 
				{ 
					$("#myteam").val('');
					$("#myteam").focus(); 
				} 
			},
			select: function(event, ui) {
				$('#my_team_id').val(ui.item.id);
                                if($("#bye").val()==2) {
                                    var d = new Date();
                                    var month = d.getMonth()+1;
                                    var day = d.getDate();
                                    var year = d.getFullYear();
                                    var date = day+'/'+month+'/'+year;
                                    $("#oppteam").val(1);
                                    $("#match_start_date").val(date);
                                }
			},
			appendTo: "#container_my_team"
		});

		//for autocomplete opponent team or player		
		$("#oppteam").autocomplete({
			source: function(request, response) {
				$.getJSON(base_url+"/getteamdetails", {team_id : $('#my_team_id').val(), tournament_id : '{{ $tournament_id }}', tournament_group_id : $('#tournament_group_id').val() , scheduletype : $('#scheduletype').val(), search_team_ids : $('#search_team_ids').val(), tournament_round_number : $('#tournament_round_number').val(), term: request.term} , response);
			},			
			minLength: 3,
			change: function(event,ui) { 
				if (ui.item==null || ui.item==undefined) 
				{ 
					$("#oppteam").val('');
					$("#oppteam").focus(); 
				} 
			},			
			select: function(event, ui) {
				$('#opp_team_id').val(ui.item.id);
			},
			appendTo: "#container_opp_team"
		});	
		
		//for autocomplete facilities
		/*$("#venue").autocomplete({
			source: base_url+"/facilities",
			minLength: 3,		
			select: function(event, ui) {
				$('#facility_id').val(ui.item.id);
			}
		});*/
                $("#bye").val(1);
                $("#bye").on("change", function() {
										var d = new Date();
									    var month = d.getMonth()+1;
									    var day = d.getDate();
									    var year = d.getFullYear();
                        				var date = day+'/'+month+'/'+year;

                        if($(this).val()==1) {
                            $("#oppTeamDiv").show();
                            $("#matchStartDatediv").show();
                            $("#matchStartTimeDiv").show();
                        }else{
                            $("#oppTeamDiv").hide();
                            // $("#matchStartDatediv").hide();
                            // $("#matchStartTimeDiv").hide();
                        }
                        $("#myteam").val('');
                        $("#oppteam").val('');
                        $("#match_start_date").val(date);
                });
                
    });
	
 	//save match schedule
	$("#save_schedule").click(function(){
		if($('#frm_add_schedule').valid()) //if form is valid
		{
                        $("#save_schedule").before("<div id='loader'></div>");
                        $("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
                        $("#save_schedule").hide();
			$("#frm_add_schedule").ajaxSubmit({
				url: base_url + '/addschedule', 
				type: 'get',
				dataType:'json',
				success:function(data){
					if(data.success)
					{
						$("#div_success_tourney").text(data.success);
						$("#div_success_tourney").show();
					}
					else
					{
						$("#div_failure_tourney").text(data.failure);
						$("#div_failure_tourney").show();
                                                $("#loader").remove();
                                                $("#save_schedule").show();
					}
					$('.modal .modal-body').animate({scrollTop:0},500);
					//on success reload the page
					window.setTimeout(function(){
                                            window.location.reload();
                                        },2000)

				},
				error: function ( xhr, status, error) {
					//on error get the errors and display
                                        $("#loader").remove();
                                        $("#save_schedule").show();
					var data=xhr.responseText;
					var parsed_data = JSON.parse(data);
					$.each(parsed_data, function(key, value) {
						if(key ===	'start_time')//if error thrown is for date picker
						{
							$("#"+key).parent().parent().parent().addClass('has-error');	
							$("#"+key).parent().parent().append(getErrorHtml(value, key, '_'+key));
						}
						else //if other errors
						{
							$("#"+key).parent().parent().addClass('has-error');	
							$("#"+key).parent().append(getErrorHtml(value, key, '_'+key));
						}
						
					});
				}
			}); 
		}else{
			return true;
		}
	});
	
	//function to build span on error
	function getErrorHtml(formErrors , id, flag )
	{
		var o = '<span id="'+id+'-error" class="help-block error-help-block" >';
		o += formErrors;
		o += '</span>';
		return o;
	}
        
    function editschedulegroupmatches(schedule_id,is_owner,disableflag)
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
                 $(".modal-body #country_id").val(parsed_data.scheduleData.country_id);
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

