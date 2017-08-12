<!-- Modal -->
{!! Form::open(['route' => 'generateScheduleLeague','class'=>'form-horizontal','method' => 'POST','id' => 'frm_generate']) !!} 
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
                            <div class="col-sm-6">
                                <div class="section" id="matchStartTimeDiv">
                                    <label class="form_label">{{   trans('message.schedule.fields.start_time') }}</label>

                                    <label for="auto_start_time" class="field prepend-icon">
                                        <div class='input-group date' id='matchStartTime'>
											{!! Form::hidden('tournament_id_save', $tournament_id, array('id'=>'tournament_id_save')) !!}
											{!! Form::hidden('is_knockout', '', array('id'=>'is_knockout')) !!}
											{!! Form::hidden('generate_bracket_type', $tournament_type, array('id'=>'generate_bracket_type')) !!}
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
						<div class="col-sm-6">
							<div class="section">
									<label class="form_label">{{  trans('message.tournament.fields.noofplaces') }} <span  class='required'>*</span></label>
									<label class="field prepend-icon">
											{!! Form::text('noofplaces', null, array( 'id'=>'noofplaces' , 'class'=>'gui-input','placeholder'=>trans('message.tournament.fields.noofplaces'))) !!}
													@if ($errors->has('noofplaces')) <p class="help-block">{{ $errors->first('noofplaces') }}</p> @endif
																			<label for="noofplaces" class="field-icon"><i class="fa fa-group"></i></label>

									</label>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="section">
													<label class="form_label">{{   trans('message.schedule.fields.matchperday') }}<span
																			class='required'>*</span> </label>
													<label for="matchperday" class="field prepend-icon">
															{!! Form::text('matchperday',null, array( 'id'=>'matchperday' ,'required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.matchperday'))) !!} 
															<label for="matchperday" class="field-icon"><i class="fa fa-user"></i></label>
													</label>
										</div>
									</div>
						</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="section">
									<label class="form_label">{{   trans('message.schedule.fields.totalmatchperday') }}<span
															class='required'>*</span> </label>
									<label for="totalmatchperday" class="field prepend-icon">
											{!! Form::text('totalmatchperday',null, array( 'readonly' , 'id'=>'totalmatchperday' , 'class'=>'gui-input','placeholder'=>trans('message.schedule.fields.totalmatchperday'))) !!} 
											<label for="totalmatchperday" class="field-icon"><i class="fa fa-user"></i></label>
									</label>
							</div>
						</div>
					</div>
					<div class="row">
						 <div class="col-sm-6">
							<div class="section">
													<label class="form_label">{{   trans('message.schedule.fields.minutespermatch') }}<span
																			class='required'>*</span> </label>
													<label for="minutespermatch" class="field prepend-icon">
															{!! Form::text('minutespermatch',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.minutespermatch'),'id'=>'minutespermatch')) !!} 
															<label for="minutespermatch" class="field-icon"><i class="fa fa-user"></i></label>
													</label>
										</div>
									</div>

						<div class="col-sm-6">
							<div class="section">
													<label class="form_label">{{   trans('message.schedule.fields.breakeachmatch') }}<span
																			class='required'>*</span> </label>
													<label for="breakeachmatch" class="field prepend-icon">
															{!! Form::text('breakeachmatch',null, array('required','class'=>'gui-input','placeholder'=>trans('message.schedule.fields.breakeachmatch'),'id'=>'breakeachmatch')) !!}
															{!! Form::hidden('facility_id', '', array('id' => 'facility_id')) !!}
															{!! Form::hidden('is_edit', '', array('id' => 'is_edit')) !!}
															<label for="breakeachmatch" class="field-icon"><i class="fa fa-user"></i></label>
													</label>
										</div>
									</div>
					</div>
 			
			@if ($tournament_type=='knockout' || $tournament_type='doubleknockout')
			<div class="row" >
				<div class="col-sm-12">
					<div class="section">
							<label class="form_label">{{  trans('message.tournament.fields.roundofplay') }} <span  class='required'>*</span></label>
							<label class="field prepend-icon">
									{!! Form::text('roundofplay', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.roundofplay'))) !!}
											@if ($errors->has('roundofplay')) <p class="help-block">{{ $errors->first('roundofplay') }}</p> @endif
																	<label for="roundofplay" class="field-icon"><i class="fa fa-group"></i></label>

							</label>
					</div>
				</div>
		    </div>
			@endif
			</div>
		<div class="modal-footer">
			<button type="button" name="generate_schedule" id="generate_schedule" class="button btn-primary">Generate</button>
			<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
		</div>
	  </div>
	  
	</div>
</div>
{!! Form::close() !!} 
{!! JsValidator::formRequest('App\Http\Requests\GenerateMatchRequest', '#frm_generate'); !!}

<script type="text/javascript">
    $(document).ready(function() {
			$('#auto_start_time').datetimepicker({format: '{{ config("constants.DATE_FORMAT.JQUERY_TIME_FORMAT") }}'});    
			$('#auto_end_time').datetimepicker({format: '{{ config("constants.DATE_FORMAT.JQUERY_TIME_FORMAT") }}'});

			$('#matchperday').change( function(){
				//if( $.isNumeric( $('#matchperday').val() ) && $.isNumeric( $('#noofplaces').val()  ) ) 
					$('#totalmatchperday').val( $('#matchperday').val() * $('#noofplaces').val() );
			});
			$('#noofplaces').change( function(){
			//	if( $.isNumeric( $('#matchperday').val() ) && $.isNumeric( $('#noofplaces').val()  ) )  
					$('#totalmatchperday').val( $('#matchperday').val() * $('#noofplaces').val() );
				
			});

			$('#generate_schedule').click( function(){
				var tm_type = '/generateScheduleLeague'; 
				if( $('#dispViewFlag').val() == 'final' )
				{
					switch( $('#generate_bracket_type').val() )
					{
						case 'league':
							tm_type = '/generateScheduleLeague';
							break;
						case 'knockout':
						case 'multistage':
							tm_type = '/generateScheduleKnockout';
							break;
						case 'doubleknockout':
						case 'doublemultistage':
							tm_type = '/generateScheduleKnockoutDouble';
							break;
					}
				}
				else
				{
						tm_type = '/generateScheduleLeague';
				} 

				$("#frm_generate").ajaxSubmit({
					url: base_url + tm_type, 
					type: 'post',
					dataType:'json',
					success:function(data){
						location.reload();
					}
				});

				//$("#frm_generate").attr("action" , base_url + tm_type );
			});
			
    });
</script>