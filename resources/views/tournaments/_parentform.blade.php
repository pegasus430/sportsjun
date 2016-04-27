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
    <div class="col-sm-12">
        <div class="section">
            <label class="form_label">{{ trans('message.tournament.fields.name') }}<span  class='required'>*</span></label>
        <label class="field prepend-icon">
        {!! Form::text('name', null, array('required','class'=>'gui-input','placeholder'=> trans('message.tournament.fields.name') )) !!}
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        <label for="firstname" class="field-icon"><i class="fa fa-trophy"></i></label>  
        </label>
       </div>
    </div>
</div>

@include ('common.upload')
@include ('common.uploadfield', ['uploadLimit' => '1','field'=>'photos','fieldname'=>'Choose Tournament Logo'])
@if(isset($tournament))
@include('common.editphoto',['photos'=>$tournament->photos,'type'=>'tournaments'])
@endif


  
<div class="row">	
<div class="col-sm-6">		 
    <div class="section">
        <label class="form_label">{{  trans('message.tournament.fields.contactnumber') }}  <span  class='required'>*</span></label>		
            <label for="mobile_phone" class="field prepend-icon">
              
                    {!! Form::text('contact_number', null, array('class'=>'gui-input phone-group', 'placeholder'=>trans('message.tournament.fields.contactnumber'))) !!}
                    @if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
            <label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
            </label>
    </div>
</div>

<div class="col-sm-6">						 
<div class="section">
    <label class="form_label">{{  trans('message.tournament.fields.altcontactnumber') }} </label>		
		<label for="mobile_phone" class="field prepend-icon">
		  
				{!! Form::text('alternate_contact_number', null, array('class'=>'gui-input phone-group', 'placeholder'=>trans('message.tournament.fields.altcontactnumber'))) !!}
				@if ($errors->has('alternate_contact_number')) <p class="help-block">{{ $errors->first('alternate_contact_number') }}</p> @endif
		<label for="mobile_phone" class="field-icon"><i class="fa fa-mobile-phone"></i></label>  
		</label>
</div>
</div>

</div>

<div class="row">	

<div class="col-sm-6">						 
<div class="section">
    <label class="form_label">{{  trans('message.tournament.fields.manager_name') }} <span  class='required'>*</span></label>		
		<label for="managername" class="field prepend-icon">
			@if(!empty($parent_manager_name))
				{!! Form::text('manager_id', $parent_manager_name, array('class'=>'gui-input phone-group', 'placeholder'=>trans('message.tournament.fields.manager_name'),'id'=>'manager_name')) !!}
			@else
				{!! Form::text('manager_id', null, array('class'=>'gui-input phone-group', 'placeholder'=>trans('message.tournament.fields.manager_name'),'id'=>'manager_name')) !!}
			@endif
				@if ($errors->has('manager_id')) <p class="help-block">{{ $errors->first('manager_id') }}</p> @endif
		<label for="firstname" class="field-icon"><i class="fa fa-user"></i></label>  
		</label>
		<input type="hidden" id="managerId" name="managerId" value="">
</div>
</div>

<div class="col-sm-6">						 
<div class="section">
    <label class="form_label">{{  trans('message.tournament.fields.email') }}  <span  class='required'>*</span></label>	
	<label for="useremail" class="field prepend-icon">
		 
		{!! Form::text('email', null, array('class'=>'gui-input','placeholder'=>trans('message.tournament.fields.email'))) !!}
		@if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
	<label for="useremail" class="field-icon"><i class="fa fa-envelope"></i></label>  
	</label>
</div>
</div>



</div>

 
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
		   $("#manager_name").autocomplete({			 
                source: "{{ url('tournaments/getUsers') }}",
                minLength: 3,
				response: function(event, ui) {
					if (!ui.content.length) {
						var noResult = { value:"",label:"No results found" };
						ui.content.push(noResult);
						$("[name='manager_id']").val('');
					} else {
					   // $("#response").empty();
					}
				},
                select: function( event, ui ) {
                    $('#managerId').val(ui.item.id);
                }
            });
    });


</script>
  