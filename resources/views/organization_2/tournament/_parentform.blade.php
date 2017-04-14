
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

{!!Form::hidden('tournament_id', null) !!}

   <input type="hidden" name="organization_id" value="{{$organisation->id}}">
<input type="hidden" name="isParent" value="yes">
<input type="hidden" name="from_organization" value="yes">


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
		<label for="managername" class="field prepend-icon select">

				{!! Form::select('manager_id', $organisation->staff->lists('name','id'), array('class'=>'gui-input select', 'placeholder'=>trans('message.tournament.fields.manager_name'),'id'=>'manager_name')) !!}
			
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
 <div class="row">

<?php $groupsList = $organisation->groups->lists('name','id');?>
    <div class = "col-sm-12">
        <div class = "section " id="organization-groups" @if(!isset($groupsList)) style="display: none" @endif>
            <label class = "form_label">{{   trans('message.team.fields.organization_groups') }} </label>
            <p class = "help-block">Select groups only if it is an internal tournament.</p>
            <label class = "field select-multiple">
                {!! Form::select('organization_group_id[]',isset($groupsList) ? $groupsList : [], isset($groupId) ? $groupId : null, array('class'=>'gui-input org-groups','id'=>'organization_group_id','placeholder'=>'Select Group', 'multiple')) !!}
                @if ($errors->has('organization_group_id'))
                    <p class = "help-block">{{ $errors->first('organization_group_id') }}</p>
                @endif
                <i class = "arrow double"></i>
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


  