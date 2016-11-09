@extends('layouts.app')
@section('content')
<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary"><h4>{{$parent_tournament_details->name}}<br>{{$parent_tournament_details->contact_number}}</h4></div>

<div class="form-body">


<?php $i=0;
$options=array();?>


@foreach ($register_data->cart_details as $value) 


<div class="row">
  
  <div class="col-sm-7">
   <div class="section">
      <label class="field prepend-icon">
        <input  class="gui-input" readonly="true"  type="text" value="{{$value->tournaments->name}}">
            <label class="form_label">{{$value->tournaments->match_type}} {{$value->tournaments->end_date}}</label>  
       </label>
           </div>
  </div>

  <div class="col-sm-2">
  <div class="section">
    <label class="form_label">{{$value->tournaments->enrollment_fee}}</label>
     
    </div>
 </div>

 <div class="col-sm-2">
  <div class="section">
   <label class="form_label">{{$value->participant_count}}</label>
   </div>
 </div>


@endforeach


</div><!--end row -->
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
   <label class="form_label">{{$register_data->total_payment}}</label>
   </div>
 </div>
</div>

<div class="row">
  
  <div class="col-sm-3">
   <div class="section">
      
           </div>
  </div>

  <div class="col-sm-5">
  <div class="section">
    <label class="form_label">Service Charge</label>
     
    </div>
 </div>

 <div class="col-sm-3">
  <div class="section">
   <label class="form_label">100.00</label>
   </div>
 </div>
</div>

<div class="row">
  
  <div class="col-sm-3">
   <div class="section">
      
           </div>
  </div>

  <div class="col-sm-5">
  <div class="section">
    <label class="form_label">Payment Gateway Fee</label>
     
    </div>
 </div>

 <div class="col-sm-3">
  <div class="section">
   <label class="form_label">200.00</label>
   </div>
 </div>
</div>

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
<?php $tot=0;
$tot=$register_data->total_payment + 200.00 + 100.00;
$tot=number_format((float)$tot, 2, '.', '');
 ?>
 <div class="col-sm-3" style="border-top:1px solid #000">
  <div class="section">
   <label class="form_label">{{$tot}}</label>
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
   <a href="/tournaments/registerstep3/{{$register_data->id}}"><input class="button btn-primary" type="submit" value="Register Now"></a>
   </div>
 </div>
</div>

 
</div>





</div>
</div>
</div>
@endsection