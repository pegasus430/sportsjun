<div class="row">	
    <div class="col-sm-12">
    <div class="section">
     @if(isset($mandatory) && $mandatory=='mandatory')
    <label class="form_label">{{ trans('message.common.fields.address') }} <span  class='required'>*</span> </label>
    
        @else
    <label class="form_label">{{ trans('message.common.fields.address') }}</label>
        @endif
        <label for="comment" class="field prepend-icon">
            @if(isset($address))
             {!! Form::textarea('address', $address, array('class'=>'gui-textarea sj-address','style'=>'resize:none','rows'=>3,'id'=>'address','placeholder'=>trans('message.common.fields.address'))) !!}
            @else
              {!! Form::textarea('address', null, array('class'=>'gui-textarea sj-address','style'=>'resize:none','rows'=>3,'id'=>'address','placeholder'=>trans('message.common.fields.address'))) !!}
              @endif
            @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
            <label for="comment" class="field-icon"><i class="fa fa-comments"></i></label>
            
        </label>
    </label>
    </div>
    </div>
</div>
<!-- end section --> 

<div class="row">
<div class="col-sm-4">
    <div class="section">
        <label class="form_label">{{ trans('message.common.fields.country') }}	<span  class='required'>*</span></label>
        <label class="field select">

            {!! Form::select('country_id',$countries, null, array('required','id'=>'country_id','class'=>'form-control','onchange'=>'displayCountries(this.value)','autocomplete'=>'off','placeholder'=>trans('message.common.fields.country'))) !!}
            @if ($errors->has('country_id')) <p class="help-block">{{ $errors->first('country_id') }}</p> @endif
            <i class="arrow double"></i>
        </label>
        </label>
    </div>
</div>

<div class="col-sm-4">
    <div class="section">
    <label class="form_label">{{ trans('message.common.fields.state') }}	<span  class='required'>*</span></label>
        <label class="field select">
    
            {!! Form::select('state_id',$states, null, array('required','id'=>'state_id','class'=>'form-control states','onchange'=>'displayStates(this.value)','autocomplete'=>'off','placeholder'=>trans('message.common.fields.state'))) !!}
               @if ($errors->has('state_id')) <p class="help-block">{{ $errors->first('state_id') }}</p> @endif
            <i class="arrow double"></i>                    
        </label>  
    </label>
    </div>
</div>
<!-- end section -->  
<div class="col-sm-4">
    <div class="section">
    <label class="form_label">{{ trans('message.common.fields.city') }}	<span  class='required'>*</span> </label>
        <label class="field select">
             {!! Form::select('city_id',$cities, null, array('required','id'=>'city_id','class'=>'form-control cities','id'=>'city_id','placeholder'=>trans('message.common.fields.city'))) !!}		 	
            @if ($errors->has('city_id')) <p class="help-block">{{ $errors->first('city_id') }}</p> @endif
            <i class="arrow double"></i>                    
        </label>  
    </label>
    </div>
</div>
</div><!-- end section --> 
<div class="row">
<div class="col-sm-12">
<div class="section">
<label class="form_label">{{ trans('message.common.fields.zip') }}	<span  class='required'>*</span></label>
	<label for="zip" class="field prepend-icon">
	@if(isset($zip))
		{!! Form::text('zip',$zip, array('required','class'=>'gui-input','id'=>'zip','placeholder'=>trans('message.common.fields.zip'))) !!}
	@else
		
		{!! Form::text('zip',null, array('required','class'=>'gui-input','id'=>'zip','placeholder'=>trans('message.common.fields.zip'))) !!}
		@endif
		@if ($errors->has('zip')) <p class="help-block">{{ $errors->first('zip') }}</p> @endif
		<label for="zip" class="field-icon"><i class="fa  fa-location-arrow"></i></label>  

	</label>
</label>
</div>
</div>
<!-- end section -->

</div>

<script type="text/javascript">
// Function to display city for states               
$(document).ready(function(){
    @if (!empty(old('state_id')))
        $("#state_id").val({{old('state_id')}}); ;
    @endif

    @if (!empty(old('city_id')))
        $("#city_id").val({{old('city_id')}}); ;
    @endif
});
</script>

