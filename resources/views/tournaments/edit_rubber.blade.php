<!-- Modal -->
<div id="editrubber" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content sportsjun-forms">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"> @lang('message.tournament.rubber.edit_schedule')</h4>
      </div>

      <div class="alert alert-success" id="div_success_rubber" style="display:none;"></div>
	  <div class="alert alert-danger" id="div_failure_rubber" style="display:none;"></div>

<!--<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.tournament.fields.heading') }}</h4></div>-->
     
   	<form action='#' id='editRubberForm'>
        <div class="modal-body">
      		<div class="sportsjun-forms sportsjun-container sportsjun-forms-modal">
		        <div class="form-body">
			        <div class="spacer-b30">
						<div class="tagline"><span>{{ trans('message.schedule.fields.scheduletype') }}</span></div>
					</div>
			     		<div class="row">
                    	<div class="col-sm-6">                
                        	<div class="section">
                             <label class="form_label">{{   trans('message.schedule.fields.myteam') }} <span  class='required'>*</span> </label>    
						<label for="myteam" class="field prepend-icon">
							<input class="gui-input" value='{{$team_a->name}}' readonly="">							
							<label for="myteam" class="field-icon"><i class="fa fa-user"></i></label>
						</label>
					</div>
                    	</div>
                        <div class="col-sm-6">
                        	<div class="section" id="oppTeamDiv">
                             <label class="form_label">{{   trans('message.schedule.fields.opponentteam') }} <span  class='required'>*</span> </label>    
						<label for="oppteam" class="field prepend-icon">							
							<input class="gui-input" value='{{$team_b->name}}' readonly="">							
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
							<div class='input-group date' id='matchStartDateRubber'>
								{!! Form::text('match_start_date',$rubber->match_start_date, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.start_date'),'id'=>'match_start_date_rubber', $readonly)) !!}
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
							<div class='input-group date' id='matchStartTimeRubber'>
								{!! Form::text('match_start_time',$rubber->match_start_time, array('class'=>'gui-input','placeholder'=>trans('message.schedule.fields.start_time'),'id'=>'match_start_time_rubber', $readonly)) !!}
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
								@if ($errors->has('match_start_time')) <p class="help-block">{{ $errors->first('match_start_time') }}</p> @endif
							</div>
						</label>
					</div>
                    	</div>
                    </div>


                   <div class="row" >
                    	<div class="col-sm-6">
                        	<div class="section">
                                              <label class="form_label">{{   trans('message.schedule.fields.playertype') }} </label>                
                            <label class="field select">
                                {!! Form::select('player_type', $player_types,$rubber->match_category,['class'=>'gui-input','placeholder'=>trans('message.schedule.fields.playertype'),'id'=>'player_type', $readonly]
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
							{!! Form::select('match_type',$match_types,$rubber->match_type,['class'=>'gui-input','placeholder'=>trans('message.schedule.fields.matchtype'),'id'=>'match_type', $readonly]
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
							{!! Form::text('venue',$rubber->match_location, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.venue'),'id'=>'venue' , $readonly)) !!}
						
							@if ($errors->has('venue')) <p class="help-block">{{ $errors->first('venue') }}</p> @endif
							<label for="venue" class="field-icon"><i class="fa fa-user"></i></label>
						</label>
					</div>

			     </div>

		 {!!Form::hidden('rubber_id', $rubber->id, ['id'=>'rubber_id', 'match_id'=>$rubber->match_id])!!}
			</div>   
		</div>				 
		
      <div class="modal-footer">
	  <button type="button" onclick="updateRubber();" class="button btn-primary">Update</button>
        <button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
      </div>	
     </form>			

				
   </div> 
 </div>
</div>



<script>
 $("#matchStartDateRubber").datetimepicker({ format: '{{ config("constants.DATE_FORMAT.JQUERY_DATE_FORMAT") }}' });
 $('#matchStartTimeRubber').datetimepicker({ format: '{{ config("constants.DATE_FORMAT.JQUERY_TIME_FORMAT") }}' });

$('#match_start_date_rubber').val({{$rubber->match_start_time_rubber}})


</script>
<script type="text/javascript">
	function updateRubber(){
		var data=$('#editRubberForm').serialize();
		var rubber_id=$('#rubber_id').val();
		var match_id=$('#rubber_id').attr('match_id');

		$.ajax({
			url:'/tournaments/rubber/'+rubber_id+'/update_schedule',
			data:data,
			type:'post',
			success:function(response){
				$('#editrubber').modal('hide');
				$('#subfield_'+match_id).html(response);
			}
		});
	}

</script>

	