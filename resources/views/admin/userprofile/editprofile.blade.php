@extends('admin.layouts.app')
<?php  
$disabled = "readonly";
$required = '';
$route = array('admin.update',Auth::user()->id);
if(isset($is_from_admin) && $is_from_admin>0)
{
	$disabled = "";
	$required = 'required';
	$route = array('admin.update',$edit_user_id);
}
?>
@section('content')


<div class="container-fluid">

<div class="sportsjun-wrap">
  <div class="sportsjun-forms sportsjun-container wrap-2">
       <div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.users.fields.editheading') }}</h4></div><!-- end .form-header section -->  
		@if (session('status'))
		<div class="alert alert-success">
			{{ session('status') }}
		</div>
		@endif	   
		 {!! Form::model($userDetails,(array('route' => $route,'class'=>'form-horizontal','method' => 'put', 'files' => true,'id'=>'edit-form'))) !!}
		<div class="form-body">
		<div class="spacer-b30">
			<div class="tagline"><span>Personal Details</span></div><!-- .tagline -->
		</div>
		 <input type="hidden" name="id" value="{{ $edit_user_id }}">
		 <div class="row">  
		  <div class="section col-sm-6">
    	 <label class="form_label">{{  trans('message.users.fields.firstname') }} <span  class='required'>*</span></label>
			<label for="firstname" class="field prepend-icon">
				{!! Form::text('firstname', null, array('required', 'class'=>'form-control','placeholder'=>trans('message.users.fields.firstname'))) !!}
			
                @if ($errors->has('firstname')) <p class="help-block">{{ $errors->first('firstname') }}</p> @endif
				<label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>  
			</label>
		 </div><!-- end section -->
		
		<div class="section col-sm-6">
    	  <label class="form_label">{{  trans('message.users.fields.lastname') }} <span  class='required'>*</span></label>
			<label for="lastname" class="field prepend-icon">
				{!! Form::text('lastname', null, array('required', 'class'=>'form-control','placeholder'=>trans('message.users.fields.lastname'))) !!}
                @if ($errors->has('lastname')) <p class="help-block">{{ $errors->first('lastname') }}</p> @endif
				<label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>  
			</label>
		  </div><!-- end section -->
	   </div>
	    <div class="row">  
		<div class="section col-sm-6">
    	  <label class="form_label">{{  trans('message.users.fields.email') }} <span  class='required'>*</span></label>
			<label for="useremail" class="field prepend-icon">
				 {!! Form::email('email', null, array('class'=>'gui-input',$disabled,$required,'email'=>'unique:users,email,'.$edit_user_id,'placeholder'=>trans('message.users.fields.email'))) !!}
				
                            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
				<label for="useremail" class="field-icon"><i class="fa fa-envelope"></i></label>  
			</label>
		</div><!-- end section -->
		<div class="section col-sm-6">
    	  <label class="form_label">{{  trans('message.users.fields.role') }} <span  class='required'>*</span></label>
			<label class="field select">
				 {!! Form::select('role', $enum,null,array('class'=>'gui-input','placeholder'=>trans('message.users.fields.role')),Input::old('role')) !!}
				
				<i class="arrow double"></i>          
			
			</label>  
		</div><!-- end section -->  
		</div>
					@if(!Session::has('socialuser'))
					@include ('common.upload')
					@include ('common.uploadfield', ['uploadLimit' => '1','field'=>'profilepic','fieldname'=>'Choose Profile Pic'])
					@if(isset($user_photo))
						@include('common.editphoto',['photos'=>$user_photo->photos,'type'=>'user_profile'])
					@endif
                    @endif
		<div class="section">
			<div class="option-group field">
				<label for="female" class="option">
					
					 {!! Form::radio('gender', 'female', null,array('class'=>'gui-input','id'=>'female')) !!}<span class="radio"></span>&nbsp;{{ trans('message.users.fields.female') }}
					 
				</label>
				
				<label for="male" class="option">
					
					 {!! Form::radio('gender', 'male', null,array('class'=>'gui-input','id'=>'male')) !!}<span class="radio"></span>&nbsp;{{ trans('message.users.fields.male') }}
										 
				</label>                            
				
				<label for="other" class="option">
					
					 {!! Form::radio('gender', 'other', null,array('class'=>'gui-input','id'=>'other')) !!}<span class="radio"></span>&nbsp;{{ trans('message.users.fields.other') }}    
									 
				</label>
				
				 @if ($errors->has('gender')) <p class="help-block">{{ $errors->first('gender') }}</p> @endif
			</div>	                         
        </div><!-- end .section section --> 
		
     <div class="row">  
		<div class="section col-sm-6">
    	  <label class="form_label">{{  trans('message.users.fields.dob') }} </label>
			<label for="dob" class="field prepend-icon">
			<div class='input-group date' id='dob'>
			{!! Form::text('dob', null, array('class'=>'gui-input','placeholder'=>trans('message.users.fields.dob'))) !!}
			
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
				@if ($errors->has('dob')) <p class="help-block">{{ $errors->first('dob') }}</p> @endif
				</div>
			</label>	
        </div>
	 </div><!-- end section -->  
		<div class="spacer-t40 spacer-b40">
            <div class="tagline"><span>Address Details</span></div><!-- .tagline -->
        </div>
		@include ('common.address',['mandatory' => ''])	
		<div class="spacer-t20 spacer-b40">
			<div class="tagline"><span> Personal Information </span></div><!-- .tagline -->
		</div> 
		 <div class="row">  
		<div class="section col-sm-6">
    	  <label class="form_label">{{  trans('message.users.fields.contactnumber') }} <span  class='required'>*</span> </label>
			<label for="mobile_phone" class="field prepend-icon">
				{!! Form::text('contact_number', null, array('class'=>'gui-input','placeholder'=>trans('message.users.fields.contactnumber'))) !!}
                            @if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
				<label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
			</label>
		  </div>
		</div><!-- end section -->
		<div class="section">
		  <label class="form_label">{{  trans('message.users.fields.about') }}</label>
			<label for="comment" class="field prepend-icon">
				{!! Form::textarea('about', null, array('class'=>'gui-textarea','style'=>'resize:none','rows'=>3,'placeholder'=>trans('message.users.fields.about'))) !!}
                            @if ($errors->has('about')) <p class="help-block">{{ $errors->first('about') }}</p> @endif
				<label for="comment" class="field-icon"><i class="fa fa-comments"></i></label>
				
			</label>
		</div><!-- end section --> 
		<div class="section">
			<label class="option block">
				{!! Form::hidden('newsletter', 0) !!}
				{!! Form::checkbox('newsletter', 1,null, array('class'=>'gui-input')) !!}
				@if ($errors->has('newsletter')) <p class="help-block">{{ $errors->first('newsletter') }}</p> @endif
				<span class="checkbox"></span> {{ trans('message.users.fields.bulletinalert') }}           
			</label>
		</div>
		<!--<div class="section colm colm6 pad-l40">
		    <div class="option-group field">
		        <label class="option block">
					{!! Form::hidden('is_available', 0) !!}		        	
		            {!! Form::checkbox('is_available', 1, null, ['id' => 'is_available', 'class'=>'gui-input']) !!}
		            <span class="checkbox"></span> {{ trans('message.users.fields.isavailable') }}
		            @if ($errors->has('is_available')) <p class="help-block">{{ $errors->first('is_available') }}</p> @endif
		        </label>
		    </div>
		</div>	-->	
		</div>
		<div class="form-footer">
                	<button type="submit" class="button btn-primary">Update </button>
        </div><!-- end .form-footer section -->		
		{!! Form::close() !!}
		               
		{!! JsValidator::formRequest('App\Http\Requests\UpdateUserProfileRequest', '#edit-form'); !!}
			
</div>
</div>
</div>



<script type="text/javascript">
    $(function () {
    $("#dob").datepicker({startDate: '-120y'});
            @if (!empty(old('state_id')))
            displayStates({{old('state_id')}});
            @endif

    });

</script>
@endsection
