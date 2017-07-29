<!-- Modal -->
{!! Form::open(['route' => 'generateScheduleLeague','class'=>'form-horizontal','method' => 'get','id' => 'frm_generate']) !!} 
<div class="modal fade"  id="generateScheduleLeagueModal" role="dialog">
	<div class="modal-dialog sj_modal sportsjun-forms">
	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">{{ trans('message.schedule.fields.generatematch') }}</h4>
		</div>
		<div class="alert alert-success" id="div_success"></div>
		<div class="alert alert-danger" id="div_failure"></div>
		<div class="modal-body">
	        <div class="sportsjun-forms sportsjun-container wrap-2 sportsjun-forms-modal">
		        <div class="form-body">
			        <div class="spacer-b30">
						<div class="tagline"><span>{{ trans('message.schedule.fields.generatematch') }}</span></div>
					</div> 
				</div>
					</div>
					<div class="row">
						 <div class="col-sm-12">
							<div class="section">
													<label class="form_label">{{   trans('message.schedule.fields.matchperday') }}<span
																			class='required'>*</span> </label>
													<label for="venue" class="field prepend-icon">
															{!! Form::text('venue',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.matchperday'),'id'=>'matchperday')) !!} 
															<label for="venue" class="field-icon"><i class="fa fa-user"></i></label>
													</label>
										</div>
									</div>
					</div>
					<div class="row">
                            <div class="col-sm-6">
                                <div class="section" id="matchStartTimeDiv">
                                    <label class="form_label">{{   trans('message.schedule.fields.start_time') }}</label>

                                    <label for="auto_start_time" class="field prepend-icon">
                                        <div class='input-group date' id='matchStartTime'>
											{!! Form::hidden('generate_bracket_type',null, array('id'=>'generate_bracket_type')) !!}
                                            {!! Form::text('auto_start_time',null, array('class'=>'gui-input','placeholder'=>trans('message.schedule.fields.start_time'),'id'=>'auto_start_time')) !!}
                                            <span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="section" id="matchEndTimeDiv">
                                    <label class="form_label">{{   trans('message.schedule.fields.end_time') }}</label>

                                    <label for="auto_end_time" class="field prepend-icon">
                                        <div class='input-group date' id='matchStartTime'>
                                            {!! Form::text('auto_end_time',null, array('class'=>'gui-input','placeholder'=>trans('message.schedule.fields.end_time'),'id'=>'auto_end_time')) !!}
                                            <span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span> 
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
					<div class="row">
						 <div class="col-sm-12">
							<div class="section">
													<label class="form_label">{{   trans('message.schedule.fields.hourpermatch') }}<span
																			class='required'>*</span> </label>
													<label for="venue" class="field prepend-icon">
															{!! Form::text('venue',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.hourpermatch'),'id'=>'hourpermatch')) !!} 
															<label for="venue" class="field-icon"><i class="fa fa-user"></i></label>
													</label>
										</div>
									</div>
					</div>
					
			
			<div class="row">
						 <div class="col-sm-12">
							<div class="section">
													<label class="form_label">{{   trans('message.schedule.fields.breakeachmatch') }}<span
																			class='required'>*</span> </label>
													<label for="venue" class="field prepend-icon">
															{!! Form::text('venue',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.breakeachmatch'),'id'=>'venue')) !!}
															{!! Form::hidden('facility_id', '', array('id' => 'facility_id')) !!}
															{!! Form::hidden('is_edit', '', array('id' => 'is_edit')) !!}
															@if ($errors->has('venue')) <p
																			class="help-block">{{ $errors->first('venue') }}</p> @endif
															<label for="venue" class="field-icon"><i class="fa fa-user"></i></label>
													</label>
										</div>
									</div>
					</div>
			</div>
			<div class="row">
			<br>
			</div>
			<div class="row">
			<br>
			</div>
		<div class="modal-footer">
			<button type="button" name="generate_schedule" id="generate_schedule" class="button btn-primary">Generate</button>
			<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
		</div>
	  </div>
	  
	</div>
</div>
{!! Form::close() !!}

<script type="text/javascript">
    $(document).ready(function() {
			$('#auto_start_time').datetimepicker({format: '{{ config("constants.DATE_FORMAT.JQUERY_TIME_FORMAT") }}'});    
			$('#auto_end_time').datetimepicker({format: '{{ config("constants.DATE_FORMAT.JQUERY_TIME_FORMAT") }}'});

			$('#generate_schedule').click(function(){

					$.confirm({
						title: 'Confirmation',
						content: "Schedule is already created. Do you want to delete and recreate again?",
						confirm: function() {
							var tournament_id = "{{$tournament_id}}";
							$.ajax({
								url: base_url+( $('#generate_bracket_type').val() == 'league' ? '/generateScheduleLeague/' : '/generateScheduleKnockout/' )+tournament_id,
								type: "Get" ,
								success: function(response) {
									location.reload();
								}
							});
					}
				});
			});
			
    });
</script>