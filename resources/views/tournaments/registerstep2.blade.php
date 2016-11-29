@extends('layouts.app')
@section('content')
<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">
<?php 
$lis = $tournament_data->toArray();
?>
<div class="payment_header_logo">
{!! Helper::Images( $lis['logo']['url'] ,'tournaments')!!}
</div>
<br>

<div class="form-header header-primary register_form_head">

<h4 class='register_form_title'>{{$parent_tournament_details->name}}</h4><br><br>
<?php
$open_timestamp = strtotime($tournament_data->reg_opening_date);
$open_day = date('D', $open_timestamp);
$newopen = date("d-m-Y", strtotime($tournament_data->reg_opening_date));
$opentime=date("h:i a", strtotime($tournament_data->reg_opening_time));
$close_timestamp = strtotime($tournament_data->reg_closing_date);
$close_day = date('D', $close_timestamp);
$newclose = date("d-m-Y", strtotime($tournament_data->reg_closing_date));
$closetime=date("h:i a", strtotime($tournament_data->reg_closing_time));
?>
<h4><i class="fa fa-calendar-o"></i>{{$open_day}} {{$newopen}} - {{$open_day}} {{$newclose}} | {{$opentime}} - {{$closetime}}</h4><br>
<h4><i class="fa fa-map-marker"></i>{{$tournament_data->location}}</h4>
</div>

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
        <div class="col-sm-12">
            <div class="section">
                
                    <label for="comment" class="field prepend-icon" id='terms_agree_label'>
                    <input type="checkbox" class="'gui-checkbox" name="agree" id="terms_agree" value="yes">{{$terms_and_conditions}}<br>
                   <p class="help-block" id="agree_conditions-val">Please agree our Terms and Conditions</p> 
                    
                </label>
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
   <a href="/tournaments/registerstep3/{{$register_data->id}}"><input class="button btn-primary" id="payment_sub" type="submit" value="Pay Now"></a>
  @else
   <a href="/guest/tournaments/guestregisterstep3/{{$register_data->id}}"><input class="button btn-primary" id="guest_payment_sub" type="submit" value="Register Now"></a>
@endif 
   </div>
 </div>
</div>

 
</div>





</div>
</div>
</div>
@endsection


