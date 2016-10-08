
	 @if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif
                  
 <!--<div class="section">
	<label class="field prepend-icon">

			{!! Form::text('user_id', null, array('required','class'=>'gui-input','placeholder'=> 'User Id')) !!}
			@if ($errors->has('user_id')) <p class="help-block">{{ $errors->first(user_id) }}</p> @endif
	</label>
</div>-->
                

@include ('common.upload')
@include ('common.uploadfield', ['uploadLimit' => '1','field'=>'photos','fieldname'=>'Choose Organization Logo'])
	
@if(isset($organization))
@include('common.editphoto',['photos'=>$organization->photos,'type'=>'organization'])
@endif
<label class="form_label">{{  trans('message.organization.fields.gallery') }} </label>
@include ('common.uploadfield', ['uploadLimit' => 'null','field'=>'gallery'])      
	
@if(isset($organization))
@include('common.editphoto',['photos'=>$organization->photo,'type'=>'form_gallery_organization'])
@endif          
						
<div class="row">	
<div class="col-sm-6">					
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.name') }}<span  class='required'>*</span> </label>
	<label class="field prepend-icon">
		{!! Form::text('name', null, array('required','class'=>'gui-input','placeholder'=> trans('message.organization.fields.name'))) !!}
		@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        <label for="name" class="field-icon"><i class="fa fa-user"></i></label>  
	</label>
</div>
</div>					
	
<div class="col-sm-12">
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.team') }} </label>
	<label class="field select-multiple">

	{!! Form::select('team[]',$teams,$selectedTeams, array('multiple'=>true,'class'=>'gui-input','id'=>'team')) !!}
	@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
     <i class="arrow double"></i>      
	</label>
</div>
</div>
</div>

<div class="row">	
<div class="col-sm-6">
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.contactnumber') }}<span  class='required'>*</span> </label>
		<label for="mobile_phone" class="field prepend-icon">
		{!! Form::text('contact_number', null, array('required','class'=>'gui-input','placeholder'=>  trans('message.organization.fields.contactnumber'))) !!}
		@if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
	<label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
		</label>
</div>
</div>
<div class="col-sm-6">
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.altcontactnumber') }}</label>
		<label for="mobile_phone" class="field prepend-icon">
		  
				{!! Form::text('alternate_contact_number', null, array('class'=>'gui-input phone-group', 'placeholder'=>trans('message.organization.fields.altcontactnumber'))) !!}
				@if ($errors->has('alternate_contact_number')) <p class="help-block">{{ $errors->first('alternate_contact_number') }}</p> @endif
		<label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
		</label>
</div>
</div>
</div>

<div class="row">	
<div class="col-sm-6">			
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.contactpersonname') }}<span  class='required'>*</span> </label>
	<label class="field prepend-icon">
	{!! Form::text('contact_name', null, array('required','class'=>'gui-input','placeholder'=> trans('message.organization.fields.contactpersonname') )) !!}
	@if ($errors->has('contact_name')) <p class="help-block">{{ $errors->first('contact_name') }}</p> @endif
	<label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>  
	</label>

</div>
</div>				
<div class="col-sm-6">					
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.email') }}<span  class='required'>*</span> </label>
	<label for="useremail" class="field prepend-icon">
			
		{!! Form::text('email', null, array('required','class'=>'gui-input','placeholder'=>  trans('message.organization.fields.email'))) !!}
		@if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
	  <label for="useremail" class="field-icon"><i class="fa fa-envelope"></i></label>  
	</label>
</div>
</div>				
</div>							
<div class="row">	
<div class="col-sm-6">					
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.organizationtype') }}<span  class='required'>*</span> </label>
	<label class="field select">

	{!! Form::select('organization_type',$type,null, array('required','class'=>'gui-input','placeholder'=>  trans('message.organization.fields.organizationtype'))) !!}  
	@if ($errors->has('organization_type')) <p class="help-block">{{ $errors->first('organization_type') }}</p> @endif
	 <i class="arrow double"></i>      
	</label>
</div>
</div>
<div class="col-sm-6">
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.facebook') }}</label>
	<label class="field prepend-icon">

{!! Form::text('social_facebook', null, array('class'=>'gui-input','placeholder'=>trans('message.organization.fields.facebook'))) !!}
@if ($errors->has('social_facebook')) <p class="help-block">{{ $errors->first('social_facebook') }}</p> @endif
    <label for="facebook" class="field-icon"><i class="fa fa-facebook"></i></label>
	</label>

</div>
</div>
</div>
<div class="row">
<div class="col-sm-6">
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.twitter') }}</label>
	<label class="field prepend-icon">

{!! Form::text('social_twitter', null, array('class'=>'gui-input','placeholder'=>trans('message.organization.fields.twitter'))) !!}
@if ($errors->has('social_twitter')) <p class="help-block">{{ $errors->first('social_twitter') }}</p> @endif
    <label for="twitter" class="field-icon"><i class="fa fa-twitter"></i></label>


	</label>

</div>
</div>
<div class="col-sm-6">
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.linkedin') }} </label>
	<label class="field prepend-icon">

{!! Form::text('social_linkedin', null, array('class'=>'gui-input','placeholder'=>trans('message.organization.fields.linkedin'))) !!}
@if ($errors->has('social_linkedin')) <p class="help-block">{{ $errors->first('social_linkedin') }}</p> @endif
    <label for="linkedin" class="field-icon"><i class="fa fa-linkedin"></i></label>

	</label>

</div>
</div>
</div>
<div class="row">
<div class="col-sm-6">
<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.googleplus') }} </label>
	<label class="field prepend-icon">
{!! Form::text('social_googleplus', null, array('class'=>'gui-input','placeholder'=>trans('message.organization.fields.googleplus'))) !!}
@if ($errors->has('social_googleplus')) <p class="help-block">{{ $errors->first('social_googleplus') }}</p> @endif
    <label for="googleplus" class="field-icon"><i class="fa fa-google-plus"></i></label>

	</label>

</div>
</div>
<div class="col-sm-6">					   
	<div class="section">
    	<label class="form_label">{{   trans('message.organization.fields.websiteurl') }}</label>
  <label for="website" class="field prepend-icon">
 
	 {!! Form::text('website_url', null, array('class'=>'gui-input','placeholder'=>trans('message.organization.fields.websiteurl'))) !!}
	@if ($errors->has('website_url')) <p class="help-block">{{ $errors->first('website_url') }}</p> @endif
<label for="website" class="field-icon"><i class="fa fa-globe"></i></label>  
</label>
</div>
</div>
<!-- end section -->  				  

 </div>
 
<div class="section">
	<label class="form_label">{{   trans('message.organization.fields.about') }} </label>
	<label for="comment" class="field prepend-icon">
	
		   {!! Form::textarea('about', null, array('class'=>'gui-textarea','style'=>'resize:none','rows'=>3)) !!}
		   @if ($errors->has('about')) <p class="help-block">{{ $errors->first('about') }}</p> @endif
	<label for="comment" class="field-icon"><i class="fa fa-comments"></i></label>
	</label>
</div>		
						

						 
      @include ('common.address',['mandatory' => ''])
				

			
     