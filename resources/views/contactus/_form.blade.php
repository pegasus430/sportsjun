	 @if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif
                  
					
	

                       <div class="row">						
								<div class="section col-sm-6">
									<label class="form_label">{{trans('message.contactus.fields.email') }}</label>
									<label class="field prepend-icon">
									{!! Form::text('email', null, array('class'=>'gui-input','placeholder'=> trans('message.contactus.fields.email'))) !!}
									@if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
									</label>
								</div>	
								<div class="section col-sm-6">
									<label class="form_label">{{trans('message.contactus.fields.subject') }}</label>
									<label class="field prepend-icon">
									{!! Form::text('subject', null, array('class'=>'gui-input','placeholder'=>trans('message.contactus.fields.subject'))) !!}
									@if ($errors->has('subject')) <p class="help-block">{{ $errors->first('subject') }}</p> @endif
									</label>
								</div>	
	                      </div>					
						

				
		                <div class="row">						
								<div class="section col-sm-6">
									<label class="form_label">{{ 'message' }}</label>
									<label class="field prepend-icon">
									{!! Form::text('message', null, array('class'=>'gui-textarea','rows'=>3,'placeholder'=> 'message')) !!}
									@if ($errors->has('message')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
									</label>
								</div>	
                        </div>								

			


						
					
						