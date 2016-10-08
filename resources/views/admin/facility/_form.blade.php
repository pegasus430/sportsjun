                  
 @if (session('status'))
<div class="alert alert-success">
	{{ session('status') }}
</div>
@endif

 <div class="row">                     
<div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.name') }} <span  class='required'>*</span></label>
	<label class="field prepend-icon">
	{!! Form::text('name', null, array('required','class'=>'gui-input','placeholder'=>  trans('message.facility.fields.name') ))!!} 
		@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        <label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>  
	</label>

</div>
	
<div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.email') }}</label>
	<label for="useremail" class="field prepend-icon">
			
		{!! Form::text('email', null, array('class'=>'gui-input','placeholder'=> trans('message.facility.fields.email'))) !!}
		@if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
	  <label for="useremail" class="field-icon"><i class="fa fa-envelope"></i></label>  
	</label>
</div>		  
</div>	

@include ('common.upload')
@include ('common.uploadfield', ['uploadLimit' => '1','field'=>'photos','fieldname'=>'Choose Facility Logo'])	
@if(isset($facility))
@include('common.editphoto',['photos'=>$facility->photos,'type'=>'facility'])
@endif
<label class="form_label">{{  trans('message.facility.fields.gallery') }} </label>
@include ('common.uploadfield', ['uploadLimit' => 'null','field'=>'gallery']) 
@if(isset($facility))
@include('common.editphoto',['photos'=>$facility->photo,'type'=>'form_gallery_facility'])
@endif               
 <div class="row">  										
<div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.contactpersonname') }} </label>
	<label class="field prepend-icon">
	{!! Form::text('contact_name', null, array('required','class'=>'gui-input','placeholder'=> trans('message.facility.fields.contactpersonname') )) !!}
	@if ($errors->has('contact_name')) <p class="help-block">{{ $errors->first('contact_name') }}</p> @endif
	<label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>  
	</label>

</div>					 
<div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.contactnumber') }} <span  class='required'>*</span></label>
		<label for="mobile_phone" class="field prepend-icon">
		  
					{!! Form::text('contact_number', null, array('class'=>'gui-input','placeholder'=>  trans('message.facility.fields.contactnumber'))) !!}
                    @if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
		<label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
		</label>
</div>						
</div>		
 <div class="row">  											
<div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.altcontactnumber') }} <span  class='required'>*</span></label>
  <label for="mobile_phone" class="field prepend-icon">
        
{!! Form::text('alternate_contact_number', null, array('class'=>'gui-input','placeholder'=>trans('message.facility.fields.altcontactnumber'))) !!}
@if ($errors->has('alternate_contact_number')) <p class="help-block">{{ $errors->first('alternate_contact_number') }}</p> @endif
    <label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
  </label>
</div>			
				
						
<div class="section col-sm-6">
    	<label class="form_label">{{   trans('message.facility.fields.altcontactpersonname') }} </label>
	<label class="field prepend-icon">
	{!! Form::text('alternate_personcontact_name', null, array('class'=>'gui-input','placeholder'=> trans('message.facility.fields.altcontactpersonname') )) !!}
	@if ($errors->has('alternate_personcontact_name')) <p class="help-block">{{ $errors->first('alternate_personcontact_name') }}</p> @endif
	<label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>  
	</label>

</div>		
</div>		
 <div class="row">  				
<div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.altcontactpersonnumber') }} </label>
  <label for="mobile_phone" class="field prepend-icon">
        
{!! Form::text('alternate_personcontact_number', null, array('class'=>'gui-input','placeholder'=>trans('message.facility.fields.altcontactpersonnumber'))) !!}
@if ($errors->has('alternate_personcontact_number')) <p class="help-block">{{ $errors->first('alternate_personcontact_number') }}</p> @endif
    <label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
  </label>
</div>			
		
</div>	


						 
<div class="spacer-t40 spacer-b40">
	<div class="tagline"><span> {{ trans('message.facility.fields.facilitytype') }}</span></div><!-- .tagline -->
</div> 
			   
			    
<div class="section colm colm6 pad-l40">
<div class="option-group field">
	<label class="option">
	
		{!!  Form::checkbox('facility_type[]','1',null, array('class'=>'gui-input','class'=>'checkbox1','id'=>'indoor')) !!}{{ trans('message.facility.fields.facilitytypeindoor') }}       
		<span class="checkbox"></span>
	</label>

	<label class="option">
		
		{!! Form::checkbox('facility_type[]','2',null, array('class'=>'gui-input','class'=>'checkbox1','id'=>'outdoor'))!!}{{ trans('message.facility.fields.facilitytypeoutdoor') }}           
		<span class="checkbox"></span>	
	</label>
	<label class="option">
		
		{!! Form::checkbox('facility_type[]','3',null, array('class'=>'gui-input','id'=>'selecctall'))!!}{{ trans('message.facility.fields.facilitytypeboth') }}           
		<span class="checkbox"></span>	
	</label>
         @if ($errors->has('facility_type')) <p class="help-block">{{ $errors->first('facility_type') }}</p> @endif                       	   
	</div> 
</div><!-- end section -->


						 
<div class="spacer-t40 spacer-b40">
	<div class="tagline"><span>Facility Service</span></div><!-- .tagline -->
</div> 
		

<div class="section colm colm6 pad-l40">
<div class="option-group field">

	<label class="option">
		
		{!! Form::checkbox('facility_service[]','1',null, array('class'=>'gui-input','class'=>'checkbox2','id'=>'academy')) !!}
		{{ trans('message.facility.fields.academy') }}     
		<span class="checkbox"></span>
	</label>

	<label class="option">
			{!! Form::checkbox('facility_service[]','2',null,array('class'=>'gui-input','class'=>'checkbox2','id'=>'coach')) !!}{{ trans('message.facility.fields.coach') }}  
		<span class="checkbox"></span>   
	</label>
	<label class="option">
		
		{!! Form::checkbox('facility_service[]','3',null, array('class'=>'gui-input','id'=>'selectall'))!!}{{ trans('message.facility.fields.facilitytypeboth') }}           
		<span class="checkbox"></span>	
	</label>
       @if ($errors->has('facility_service[]')) <p class="help-block">{{ $errors->first('facility_service') }}</p> @endif                         	   
 </div>                        
</div><!-- end section -->
						 

<div class="spacer-t40 spacer-b40">
<div class="tagline"><span>  {{ trans('message.facility.fields.amenities') }}</span></div><!-- .tagline -->
</div> 


<div class="section colm colm6 pad-l40">
		<div class="option-group field">
		
			<label class="option">
				
				{!! Form::checkbox('amenities_flood_lights','1',null,array('class'=>'gui-input','class'=>'checkbox3')) !!}{{ trans('message.facility.fields.flood_lights') }}   
                <span class="checkbox"></span>				
			</label>
	  
			<label class="option ">
			
					{!! Form::checkbox('amenities_refrigerators', '1',null,array('class'=>'gui-input','class'=>'checkbox3')) !!}{{ trans('message.facility.fields.refrigerators') }}   
                <span class="checkbox"></span>				
			</label>
			
			<label class="option">
			
				{!! Form::checkbox('amenities_refreshments','1',null,array('class'=>'gui-input','class'=>'checkbox3')) !!}{{ trans('message.facility.fields.refreshments') }}     
				<span class="checkbox"></span>
			</label>      
   
		   <label class="option">
			
				{!!  Form::checkbox('amenities_dressing_room','1',null,array('class'=>'gui-input','class'=>'checkbox3')) !!}{{ trans('message.facility.fields.dressroom') }} 
				<span class="checkbox"></span>
			</label>    
		  <label class="option">
				
				{!!  Form::checkbox('amenities_bathroom', '1',null,array('class'=>'gui-input','class'=>'checkbox3')) !!}{{ trans('message.facility.fields.bathroom') }}    
				<span class="checkbox"></span>
			</label>  

		     <label class="option">
				
			{!!  Form::checkbox('amenities_water', '1',null,array('class'=>'gui-input','class'=>'checkbox3')) !!}{{ trans('message.facility.fields.water') }}   
               <span class="checkbox"></span>			
			</label>    
                     <label class="option">
				
				{!!  Form::checkbox('amenities_pavilion', '1',null,array('class'=>'gui-input','class'=>'checkbox3')) !!}{{ trans('message.facility.fields.pavilion') }}    
				<span class="checkbox"></span>
			</label>  						
			   
		</div>                        
</div><!-- end section -->
<div class="row">
  <div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.social_facebook') }} </label>
	<label class="field prepend-icon">

{!! Form::text('social_facebook', null, array('class'=>'gui-input','placeholder'=>  trans('message.facility.fields.social_facebook'))) !!}
@if ($errors->has('social_facebook')) <p class="help-block">{{ $errors->first('social_facebook') }}</p> @endif
	</label>

</div>


  <div class="section col-sm-6">
    	<label class="form_label">{{   trans('message.facility.fields.twitter') }} </label>
	<label class="field prepend-icon">

{!! Form::text('social_twitter', null, array('class'=>'gui-input','placeholder'=> trans('message.facility.fields.twitter'))) !!}
@if ($errors->has('social_twitter')) <p class="help-block">{{ $errors->first('social_twitter') }}</p> @endif

	</label>

</div>
</div>
<div class="row">
 <div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.linkedin') }} </label>
	<label class="field prepend-icon">

{!! Form::text('social_linkedin', null, array('class'=>'gui-input','placeholder'=>trans('message.facility.fields.linkedin'))) !!}
@if ($errors->has('social_linkedin')) <p class="help-block">{{ $errors->first('social_linkedin') }}</p> @endif
	</label>

</div>


<div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.googleplus') }} </label>
	<label class="field prepend-icon">
{!! Form::text('social_googleplus', null, array('class'=>'gui-input','placeholder'=>trans('message.facility.fields.googleplus'))) !!}
@if ($errors->has('social_googleplus')) <p class="help-block">{{ $errors->first('social_googleplus') }}</p> @endif
	</label>

</div>
</div>

                       
					   
<div class="row">					  
<div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.social_facebook') }} </label>
  <label for="website" class="field prepend-icon">
 
	 {!! Form::text('website_url', null, array('class'=>'gui-input','placeholder'=> trans('message.facility.fields.social_facebook'))) !!}
	@if ($errors->has('website_url')) <p class="help-block">{{ $errors->first('website_url') }}</p> @endif
<label for="website" class="field-icon"><i class="fa fa-globe"></i></label>  
</label>
</div><!-- end section -->               						
</div>					
					
			

<div class="section">
		<label for="comment" class="field prepend-icon">
		{!! Form::textarea('description', null, array('class'=>'gui-textarea','placeholder'=> trans('message.facility.fields.description'),'style'=>'resize:none','rows'=>3)) !!}
		@if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
<label for="comment" class="field-icon"><i class="fa fa-comments"></i></label>
		</label>
</div>			
					
<div class="spacer-t40 spacer-b40">
<div class="tagline"><span>  {{ trans('message.facility.fields.verified') }}</span></div><!-- .tagline -->
</div> 

<div class="row">							
<div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.facility.fields.verified') }} </label>
<div class="option-group field">
	<label for="verified" class="option">

	{!! Form::radio('verified','1',null,array('class'=>'gui-input','id'=>'verified')) !!}{{ trans('message.facility.fields.yes') }}
		<span class="radio"></span> 
	</label>

	<label for="verified1" class="option">
	
	{!! Form::radio('verified','0',null,array('class'=>'gui-input','id'=>'verified1')) !!}{{ trans('message.facility.fields.no') }}   
<span class="radio"></span> 	
	</label>                    																									
				
@if ($errors->has('verified')) <p class="help-block">{{ $errors->first('verified') }}</p> @endif
</div>			 
</div><!-- end .section section --> 			
	</div>		
						
					
						
 @include ('common.address', ['mandatory' => ''])
					  


					
<script type="text/javascript">	
$(document).ready(function() {
	
	@if(isset($facility))
	var facility_type= "{{$facility->facility_type}}";
	var facility_service= "{{$facility->facility_service}}";
if(facility_type==1)
{
	  $('#indoor').prop('checked',true);
	  $('#outdoor').prop('checked',false);
	  $('#selecctall').prop('checked',false);
	   
}
if(facility_type==2)
{
	 $('#indoor').prop('checked',false);
	  $('#selecctall').prop('checked',false);
	  $('#outdoor').prop('checked',true);
}	
if(facility_type==3)
{
	    $('#indoor').prop('checked',true);
		 $('#outdoor').prop('checked',true);
		  $('#selecctall').prop('checked',true);
}	
if(facility_service==1)
{
	  $('#academy').prop('checked',true);
	  $('#coach').prop('checked',false);
	   $('#selecctall').prop('checked',false);
}
if(facility_service==2)
{
	 $('#academy').prop('checked',false);
	  $('#selecctall').prop('checked',false);
	  $('#coach').prop('checked',true);
}	
if(facility_service==3)
{
	    $('#academy').prop('checked',true);
		  $('#coach').prop('checked',true);
		   $('#selecctall').prop('checked',true);
}	
if(facility_service==3)
{
	    $('#all').prop('checked',true);
		  $('#coach').prop('checked',true);
		   $('#selecctall').prop('checked',true);
}	
   
@endif
 $('#selecctall').click(function(event) {  
        if(this.checked) { 
            $('.checkbox1').each(function() { 
                this.checked = true; 				
            });
        }else{
            $('.checkbox1').each(function() {
                this.checked = false;          
				
            });        
        }
    });
	
	 $('.checkbox1').on('click',function(){
        if($('.checkbox1:checked').length == $('.checkbox1').length){
            $('#selecctall').prop('checked',true);
        }else{
            $('#selecctall').prop('checked',false);
        }
    });

$('#selectall').click(function(event) {  
        if(this.checked) { 
            $('.checkbox2').each(function() { 
                this.checked = true; 				
            });
        }else{
            $('.checkbox2').each(function() {
                this.checked = false;          
				
            });        
        }
    });
	
	 $('.checkbox2').on('click',function(){
        if($('.checkbox2:checked').length == $('.checkbox2').length){
            $('#selectall').prop('checked',true);
        }else{
            $('#selectall').prop('checked',false);
        }
    });

});	
</script>	
