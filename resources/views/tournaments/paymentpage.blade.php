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
{!! Form::open(array('url' => 'tournaments/paymentform', 'method' => 'post')) !!}


{!! Form::hidden("key", $key) !!}
{!! Form::hidden("txnid", $txnid) !!}
{!! Form::hidden("amount", $amount) !!}
{!! Form::hidden("productinfo", $productinfo) !!}





<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("firstname", '', array('required','class'=>'gui-input','placeholder' => 'First Name' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
             {!! Form::text("lastname", '', array('class'=>'gui-input','placeholder' => 'Last Name' )) !!}
            
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->



<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("address1", '', array('class'=>'gui-input','placeholder' => 'Address1' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
             {!! Form::text("address2", '', array('class'=>'gui-input','placeholder' => 'Address2' )) !!}
            
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->



<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("city", '', array('class'=>'gui-input','placeholder' => 'City' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
             {!! Form::text("State", '', array('class'=>'gui-input','placeholder' => 'State' )) !!}
            
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("country", '', array('class'=>'gui-input','placeholder' => 'Country' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
             {!! Form::text("zipcode", '', array('class'=>'gui-input','placeholder' => 'Zipcode' )) !!}
            
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->




<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::email("email", '', array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("phone", '', array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
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