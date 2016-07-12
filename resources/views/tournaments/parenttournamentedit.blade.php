



                  				
				@if( $roletype=='admin')
                 {!! Form::model( $tournament ,(array('route' => array('admin.tournaments.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'my-tournaments'))) !!}   
			     <div class="form-body">
                        @include ('tournaments._parentform', ['submitButtonText' => 'Update'])
						<input type="hidden" name="isParent" id="isParent" value="yes">
						</div>	
				 <div class="form-footer">
                  <button type="submit" class="button btn-primary">Update</button>

                </div>	
                {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#my-tournaments'); !!}
			   @endif
					
			   @if( $roletype=='user')
				 {!! Form::model( $tournament,(array('route' => array('tournaments.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'my-tournaments'))) !!}   
			  <div class="form-body">
                       @include ('tournaments._parentform', ['submitButtonText' => 'Update'])
					   <input type="hidden" name="isParent" id="isParent" value="yes">
			    </div>	
            <div class="form-footer">
                  <button type="submit" class="button btn-primary">Update</button>

                </div>					
                {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#my-tournaments'); !!}
				@endif