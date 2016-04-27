

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif
<!-- end .form-header section -->
         @if( $roletype=='admin')
                    {!! Form::open(['route' => 'admin.tournaments.store','class'=>'form-horizontal','method' => 'POST','id' => 'parent-tournaments']) !!}   
				<div class="form-body">
                         @include ('tournaments._parentform', ['submitButtonText' => 'Create'])
						 <input type="hidden" name="isParent" id="isParent" value="yes">
				</div>		
                 <div class="form-footer">
                  <button type="submit" class="button btn-primary">Create</button>

                </div>				
                    {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#parent-tournaments'); !!}
			      @endif
			      @if( $roletype=='user')
			      {!! Form::open(['route' => 'tournaments.store','class'=>'form-horizontal','method' => 'POST','id' => 'parent-tournaments']) !!}   
			  <div class="form-body">
                         @include ('tournaments._parentform', ['submitButtonText' => 'Create'])
						 <input type="hidden" name="isParent" id="isParent" value="yes">
					</div>	 
			  <div class="form-footer">
                  <button type="submit" class="button btn-primary">Create</button>

                </div>	
                    {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#parent-tournaments'); !!}
			     @endif	   
				 
