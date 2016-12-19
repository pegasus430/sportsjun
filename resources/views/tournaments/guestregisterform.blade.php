@extends('layouts.app')
@section('content')
<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">


<div class="form-header header-primary register_form_head"><h4 class='register_form_title'>Register Form</h4></div>
@if($errors->any())
<h4 class="error_validation">{{$errors->first()}}</h4>
@endif
<div class="form-body">
{!! Form::open(array('url' => 'guest/tournaments/guestregisterstep3', 'method' => 'post')) !!}

{!! Form::hidden("id", $id) !!}
{!! Form::hidden("event_id", $event_id) !!}






<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
          <label class="field-icon"><span  class='required validation'>*</span></label>
            {!! Form::text("guest[name]", '', array('required','class'=>'gui-input','placeholder' => 'Full Name' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
    <label class="field-icon"><span  class='required validation'>*</span></label>
            {!! Form::email("guest[email]", '', array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
          <label class="field-icon"><span  class='required validation'>*</span></label>
            {!! Form::text("guest[club]", '', array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
    <label class="field-icon"><span  class='required validation'>*</span></label>
            {!! Form::text("guest[number]", '', array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
          <label class="field-icon"><span  class='required validation'>*</span></label>
            {!! Form::textarea("guest[location]", '', array('required','class'=>'gui-input','placeholder' => 'Location' ,'rows' => 2, 'cols' => 40)) !!}
            
            </label>
           </div>
        </div>


<!-- end section -->  



</div><!--end row -->

{!! Form::submit('Register Now', array('class' => 'button btn-primary')) !!}


{!! Form::close() !!}
 
</div>

</div>
</div>
</div>
@endsection