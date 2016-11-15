@extends('layouts.app')
@section('content')
<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">


<div class="form-header header-primary"><h4>Register Form</h4></div>
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
            {!! Form::text("guest[name]", '', array('required','class'=>'gui-input','placeholder' => 'Full Name' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
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
            {!! Form::text("guest[club]", '', array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
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
            {!! Form::textarea("guest[location]", '', array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
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