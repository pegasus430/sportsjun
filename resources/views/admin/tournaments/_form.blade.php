 <meta charset="utf-8">
  <title>jQuery UI Autocomplete - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	
	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif
			
			

<div class="row">
    <div class="section col-sm-6">
    	<label class="form_label">{{ trans('message.tournament.fields.name') }}<span  class='required'>*</span></label>
	<label class="field prepend-icon">
	{!! Form::text('name', null, array('required','class'=>'gui-input','placeholder'=> trans('message.tournament.fields.name') )) !!}
	@if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
	<label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>  
	</label>
   </div>
</div>

	
                


@include ('common.upload')
@include ('common.uploadfield', ['uploadLimit' => '1','field'=>'photos','fieldname'=>'Choose Tournament Logo'])
@if(isset($tournament))
@include('common.editphoto',['photos'=>$tournament->photos,'type'=>'tournaments'])
@endif
<label class="form_label">{{  trans('message.tournament.fields.gallery') }} </label>
@include ('common.uploadfield', ['uploadLimit' => 'null','field'=>'gallery']) 
@if(isset($tournament))
@include('common.editphoto',['photos'=>$tournament->photo,'type'=>'form_gallery_tournaments'])
@endif               
						
<div class="row">
    <div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.tournament.fields.type') }} <span  class='required'>*</span></label>
	<label class="field select">

	{!! Form::select('type', $enum, null,array('class'=>'gui-input' )) !!}
	@if ($errors->has('type')) <p class="help-block">{{ $errors->first('sports') }}</p> @endif
	<i class="arrow double"></i>      
	</label>
</div>						     
  <div class="section col-sm-6">
    	<label class="form_label">{{  trans('message.tournament.fields.sports') }} <span  class='required'>*</span></label>
	<label class="field select">
	  
	 {!! Form::select('sports_id', $sports, null,array('id'=>'sports_id','class'=>'gui-input','onchange'=>'sportsChange(this.value)')) !!}
	@if ($errors->has('sports_id')) <p class="help-block">{{ $errors->first('sports') }}</p> @endif
	 <i class="arrow double"></i>      
	</label>
</div>
</div>

<div class="row">
<div class="section col-sm-6" id="show_hide_div" style="display:none;">
	<label class="form_label">{{  trans('message.tournament.fields.schedule_type') }} <span  class='required'>*</span></label>
	<label class="field select">
		 {!! Form::select('schedule_type', $schedule_type_enum, null,array('id'=>'schedule_type','class'=>'gui-input')) !!}

		 <i class="arrow double"></i>
	</label>
</div>
</div>						 
<div class="row">
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.startdate') }} <span  class='required'>*</span></label>         
	<div class='input-group date' id='startdate'>
		{!! Form::text('start_date', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.startdate'))) !!}
		<span class="input-group-addon">
		<span class="glyphicon glyphicon-calendar"></span>
		</span>
		@if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
	</div>

</div>
   
 
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.enddate') }}  <span  class='required'>*</span></label>		
		<div class='input-group date' id='enddate'>
		{!! Form::text('end_date', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.enddate'))) !!}
		<span class="input-group-addon">
		<span class="glyphicon glyphicon-calendar"></span>
		</span>
		@if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
		</div>
		
</div>
						 					 
</div>		


<div class="row">			 
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.contactnumber') }}  <span  class='required'>*</span></label>		
		<label for="mobile_phone" class="field prepend-icon">
		  
				{!! Form::text('contact_number', null, array('class'=>'gui-input phone-group', 'placeholder'=>trans('message.tournament.fields.contactnumber'))) !!}
				@if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
		<label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
		</label>
</div>

						 
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.altcontactnumber') }}  <span  class='required'>*</span></label>		
		<label for="mobile_phone" class="field prepend-icon">
		  
				{!! Form::text('alternate_contact_number', null, array('class'=>'gui-input phone-group', 'placeholder'=>trans('message.tournament.fields.altcontactnumber'))) !!}
				@if ($errors->has('alternate_contact_number')) <p class="help-block">{{ $errors->first('alternate_contact_number') }}</p> @endif
		<label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
		</label>
</div>
</div>

<div class="row">		
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.contactpersonname') }}<span  class='required'>*</span></label>	
	<label class="field prepend-icon">
	{!! Form::text('contact_name', null, array('required','class'=>'gui-input','placeholder'=> trans('message.tournament.fields.contactpersonname') )) !!}
	@if ($errors->has('contact_name')) <p class="help-block">{{ $errors->first('contact_name') }}</p> @endif
	<label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>  
	</label>

</div>
								 
						 
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.email') }}  <span  class='required'>*</span></label>	
	<label for="useremail" class="field prepend-icon">
		 
		{!! Form::text('email', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.email'))) !!}
		@if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
	<label for="useremail" class="field-icon"><i class="fa fa-envelope"></i></label>  
	</label>
</div>
</div><!-- end section -->	 
						     

	 @include ('common.address', ['mandatory' => 'mandatory'])			
<div class="row">							
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.groups') }}  <span  class='required'>*</span></label>	
		<label class="field prepend-icon">

		@if($type=='create')

		{!! Form::text('groups_number', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.groups'))) !!}

		@endif
		@if($type=='edit')
		{!! Form::text('groups_number', null, array('class'=>'gui-input','placeholder'=>'groups_number','readonly' => 'true')) !!}

		@endif
		@if ($errors->has('groups_number')) <p class="help-block">{{ $errors->first('groups_number') }}</p> @endif

		</label>
</div>		
										
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.noofteams') }} <span  class='required'>*</span></label>	
	<label class="field prepend-icon">
		
		 {!! Form::text('groups_teams', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.noofteams'))) !!}
			@if ($errors->has('groups_teams')) <p class="help-block">{{ $errors->first('groups_teams') }}</p> @endif
	 </label>
</div>
</div>		

<!--<div class="row">	
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.facility') }} </label>	
	<label class="field prepend-icon">
	{!! Form::text('facility_name', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.facility'),'id'=>'facility')) !!}
	{!! Form::hidden('facility_response', null, array('class'=>'gui-input','placeholder'=>'Group','id'=>'response')) !!}
	{!! Form::hidden('facility_response_name', null, array('class'=>'gui-input','placeholder'=>'Group','id'=>'response_name')) !!}
	@if ($errors->has('facility_id')) <p class="help-block">{{ $errors->first('facility_id') }}</p> @endif
	</label>
</div>-->
<div class="row">	
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.pricemoney') }} </label>	
	<label class="field prepend-icon">
	 {!! Form::text('prize_money', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.pricemoney'))) !!}
		@if ($errors->has('prize_money')) <p class="help-block">{{ $errors->first('prize_money') }}</p> @endif
	</label>
</div>


		
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.enrollmentfee') }} </label>	
	<label class="field prepend-icon">
	 {!! Form::text('enrollment_fee', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.enrollmentfee'))) !!}
		@if ($errors->has('enrollment_fee')) <p class="help-block">{{ $errors->first('enrollment_fee') }}</p> @endif
	  </label>
</div>
</div>
<div class="row">					
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.pointstowinningteam') }} </label>	
	<label class="field prepend-icon">
	{!! Form::text('points_win', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.pointstowinningteam'))) !!}
	@if ($errors->has('points_win')) <p class="help-block">{{ $errors->first('points_win') }}</p> @endif
	</label>
</div>
<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.Pointstolosingteam') }} </label>	
	<label class="field prepend-icon">
	{!! Form::text('points_loose', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.Pointstolosingteam'))) !!}
	@if ($errors->has('points_loose')) <p class="help-block">{{ $errors->first('points_loose') }}</p> @endif
	</label>
</div>
	</div>		
<!--<div class="section col-sm-6">
    <label class="form_label">{{  trans('message.tournament.fields.status') }} <span  class='required'>*</span> </label>	
	<label class="field prepend-icon">
	{!! Form::text('status', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.status'))) !!}
	@if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
	</label>
</div>-->


<div class="section">
   <label class="form_label">{{  trans('message.tournament.fields.description') }} </label>	
		<label for="comment" class="field prepend-icon">
		{!! Form::textarea('description', null, array('class'=>'gui-textarea','placeholder'=>trans('message.tournament.fields.description'),'style'=>'resize:none','rows'=>3)) !!}
		@if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
<label for="comment" class="field-icon"><i class="fa fa-comments"></i></label>
		</label>
</div>			

			
						
					
 
<script type="text/javascript">
	
    $(function () {
		   $("#facility").autocomplete({			 
                source: "{{ url('/getfacilitieslist') }}",
                minLength: 3,
                select: function( event, ui ) {
                    $('#response').val(ui.item.id);
					$('#response_name').val(ui.item.value);
                }
            });
    $("#startdate").datepicker({});
    $("#enddate").datepicker({});      

    });
	function sportsChange(sport_id)
	{
		var sport_name = $( "#sports_id option:selected" ).text();
		if(sport_name.toLowerCase()=='tennis' || sport_name.toLowerCase()=='table tennis')
		{

			$('#show_hide_div').show();
		}
		else
		{
			$('#show_hide_div').hide();
			
		}
	}

</script>    
