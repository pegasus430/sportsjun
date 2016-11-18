@extends('layouts.app')
@section('content')
<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary register_form_head"><h4 class='register_form_title'>{{$parent_tournament_details->name}}<br>{{$parent_tournament_details->contact_number}}</h4></div>

<div class="form-body">

<div class="row">




<div class="col-sm-7">
      <div class="section">
        <label class="field prepend-icon head_tr">
            Tournament Events
            </label>
          

           </div>
        </div>

<div class="col-sm-2">
<label class="field prepend-icon">
  <div class="section">
    <label class="field prepend-icon head_tr">Enrollment Fee</label>
     
    </div>
 </div>
<!-- end section -->  

<div class="col-sm-3">
    <div class="section">
    
    


    <label class="field prepend-icon head_tr">
             Participant Count
            </label>

         
    
    </div>
</div>

</div><!--end row -->
<?php $i=0;
$options=array();?>


@foreach ($register_data->cartDetails as $value) 


<div class="row">
  
  <div class="col-sm-7">
   <div class="section">
      <label class="field prepend-icon">
       <label class="field-icon"><i class="fa fa-trophy"></i></label>
        <input  class="gui-input form_rows" readonly="true"  type="text" value="{{$value->tournaments->name}}">
            <label  class="form_label last_date_span">Last Date:{{$value->tournaments->end_date}}</label>
       </label>
           </div>
  </div>

  <div class="col-sm-2">
  <div class="section">
    <label class="form_label"><i class="fa fa-inr"></i> {{$value->tournaments->enrollment_fee}}</label>
     
    </div>
 </div>

 <div class="col-sm-2">
  <div class="section">
   <label class="form_label"><i class="fa fa-group"></i>  <span class="white_space"></span>{{$value->participant_count}}</label>
   </div>
 </div>

</div><!--end row -->
@endforeach



<?php $i++;?>

<div class="row">
  
  <div class="col-sm-3">
   <div class="section">
      
           </div>
  </div>

  <div class="col-sm-5">
  <div class="section">
    <label class="form_label">Total Amount</label>
     
    </div>
 </div>

 <div class="col-sm-3">
  <div class="section">
   <label class="form_label"><i class="fa fa-inr"></i> {{$amount_without_charges}}</label>
   </div>
 </div>
</div>

 @if($amount_data!='' && count($amount_data) > 0)
 @foreach($amount_data->paymentSetups as $amnt) 
          
<div class="row">
  
  <div class="col-sm-3">
   <div class="section">
      
           </div>
  </div>

  <div class="col-sm-5">
  <div class="section">
    <label class="form_label">{{$amnt->setup_name}}</label>
     
    </div>
 </div>

 <div class="col-sm-3">
  <div class="section">
   <label class="form_label"><i class="fa fa-inr"></i>{{$amount_without_charges*($amnt->setup_value/100)}}</label>
   </div>
 </div>
</div>

@endforeach
@endif

<div class="row">
  
  <div class="col-sm-3">
   <div class="section">
      
           </div>
  </div>

  <div class="col-sm-5" style="border-top:1px solid #000">
  <div class="section">
    <label class="form_label">Purchase Total</label>
     
    </div>
 </div>

 <div class="col-sm-3" style="border-top:1px solid #000">
  <div class="section">
   <label class="form_label"><i class="fa fa-inr"></i> {{$register_data->total_payment}}</label>
   </div>
 </div>
</div>


<div class="row">
  
  <div class="col-sm-8">
   <div class="section">
      
          
   
     
    </div>
 </div>

 <div class="col-sm-3">
  <div class="section">
  @if (Auth::check()) 
   <a href="/tournaments/registerstep3/{{$register_data->id}}"><input class="button btn-primary" type="submit" value="Register Now"></a>
  @else
   <a href="/guest/tournaments/guestregisterstep3/{{$register_data->id}}"><input class="button btn-primary" type="submit" value="Register Now"></a>
@endif 
   </div>
 </div>
</div>

 
</div>





</div>
</div>
</div>
@endsection