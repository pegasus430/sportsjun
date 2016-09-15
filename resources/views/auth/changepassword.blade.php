@extends('layouts.app')

@section('content')
<div class="container-fluid">

<div class="sportsjun-wrap">
    	<div class="sportsjun-forms sportsjun-container wrap-2">
                
 <div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.auth.fields.changepassword') }}</h4></div><!-- end .form-header section -->   
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{-- @if (count($errors) > 0) --}}
                    <!--						<div class="alert alert-danger">
                                                                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                                                            <ul>
                                                                                    @foreach ($errors->all() as $error)
                                                                                            <li>{{ $error }}</li>
                                                                                    @endforeach
                                                                            </ul>
                                                                    </div>-->
                    {{-- @endif --}}


                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/updatepassword') }}">
					<div class="form-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

					    <div class="row">						
								<div class="section col-sm-6">
									<label class="form_label">{{ trans('message.auth.fields.oldpassword') }}&nbsp;<span  class='required'>*</span></label>
									<label class="field prepend-icon">
									{!! Form::password('old_password',array('class'=>'gui-input','placeholder'=> trans('message.auth.fields.oldpassword'))) !!}
									@if ($errors->has('old_password')) <p class="help-block help-red">{{ $errors->first('old_password') }}</p> @endif
                                    <label for="oldpassword" class="field-icon"><i class="fa fa-lock"></i></label>
									</label>
								</div>	
								<div class="section col-sm-6">
									<label class="form_label">{{ trans('message.auth.fields.password') }}&nbsp;<span  class='required'>*</span></label>
									<label class="field prepend-icon">
										{!! Form::password('password',array('class'=>'gui-input','placeholder'=>trans('message.auth.fields.password'))) !!}
										@if ($errors->has('password')) <p class="help-block help-red">{{ $errors->first('password') }}</p> @endif
                                        <label for="password" class="field-icon"><i class="fa fa-unlock"></i></label>
									</label>
								</div>			   
						</div>	  

                        <div class="row">	
							<div class="section col-sm-6">
								<label class="form_label">{{ trans('message.auth.fields.confirmpassword') }}&nbsp;<span  class='required'>*</span></label>
								<label class="field prepend-icon">
										{!! Form::password('password_confirmation',array('class'=>'gui-input','placeholder'=>trans('message.auth.fields.confirmpassword'))) !!}
										@if ($errors->has('password_confirmation')) <p class="help-block help-red">{{ $errors->first('password_confirmation') }}</p> @endif
                                    	<label for="confirmpassword" class="field-icon"><i class="fa fa-key"></i></label>
								</label>
							</div>			   
						</div>		
									               
					</div>
						 <div class="form-footer">
                	<button type="submit" class="button btn-primary">  Change Password </button>
                </div><!-- end .form-footer section -->
                    </form>
        </div>
    </div>
</div>
@endsection
