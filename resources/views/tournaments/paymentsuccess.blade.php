@extends('layouts.app')
@section('content')

<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">


<div class="form-header header-primary register_form_head"><h4 class='register_form_title'>Payment Result</h4></div>

<h3>Thank You. Your order status is {{$data['status']}}.</h3>
<h4>Your Transaction ID for this transaction is {{$data['txnid']}}</h4>
<h4>We have received a payment of INR. {{$data['amount']}} .</h4>



<div class="form-body">



<div class="row">




<div class="col-sm-3">
      <div class="section">
        <label class="field prepend-icon head_tr">
            Tournament Events
            </label>
          

           </div>
        </div>

<div class="col-sm-2">
<label class="field prepend-icon">
  <div class="section">
    <label class="field prepend-icon head_tr">Payment name</label>
     
    </div>
 </div>
<!-- end section -->  

<div class="col-sm-3">
    <div class="section">
    
    


    <label class="field prepend-icon head_tr">
             Payment email
            </label>

         
    
    </div>
</div>


<div class="col-sm-2">
    <div class="section">
    
    


    <label class="field prepend-icon head_tr">
             Payment phone
            </label>

         
    
    </div>
</div>






<div class="col-sm-2">
      <div class="section">
        <label class="field prepend-icon head_tr">
            Amount
            </label>
          

           </div>
        </div>

</div><!--end row -->











<?php $i=0;
$options=array();?>
@foreach ($details as $details)
<div class="row inner_events">




<div class="col-sm-3">
      <div class="section">
          <label class="field prepend-icon">
           
            
            <label for="Pointstolosingteam" class="field-icon">{{$details['tournament']}}</label>  
            </label>
          

           </div>
        </div>

<div class="col-sm-2">
  <div class="section">
    <label class="form_label">{{$details['name']}}</label>
     
    </div>
 </div>
<!-- end section -->  

<div class="col-sm-3">
    <div class="section">
    
    


   
                        
             <label for="Pointstolosingteam" class="field-icon">{{$details['email']}}</label>
            

         
    
    </div>
</div>
<div class="col-sm-2">
  <div class="section">
    <label class="form_label">{{$details['phone']}}</label>
     
    </div>
 </div>
 <div class="col-sm-2">
  <div class="section">
    <label class="form_label"></label>
     
    </div>
 </div>
 <div class="col-sm-2">
  <div class="section">
    <label class="form_label">{{$details['price']}}</label>
     
    </div>
 </div>

</div><!--end row -->
<br>
<br>
<?php $i++;?>
@endforeach


 
</div>







</div>

</div>
</div>
</div>
@endsection