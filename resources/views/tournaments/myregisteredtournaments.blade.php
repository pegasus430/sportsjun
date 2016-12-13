@extends('layouts.app')
@section('content')

<div class="col-lg-8 col-md-10 col-sm-14 col-md-offset-1 col-lg-offset-2 tournament_profile teamslist-pg" style="padding-top: 3px !important;">
 <div class="panel panel-default">
   <div class="panel-body">
 
<div class="transcations_head">    
<h4 class="left_head">Registrations of Your Tournaments</h4>
<h4 class="right_head"><a href="/mytransactions/{{$u_id}}">Your Payments</a></h4>
<div class="clear"></div>
<br>
</div>
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


<!-- <div class="col-sm-2">
    <div class="section">
    
    


    <label class="field prepend-icon head_tr">
             Payment phone
            </label>

         
    
    </div>
</div> -->

<div class="col-sm-2">
    <div class="section">
    
    


    <label class="field prepend-icon head_tr">
             Date
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



<br><br>







<?php $i=0;
$options=array();?>
@foreach ($details as $details)
<div class="row inner_events">




<div class="col-sm-3">
      <div class="section">
          <label class="field prepend-icon">
           
            
            
            <label class="form_label">{{$details['tournament']}}</label>  
            </label>
          

           </div>
        </div>

<div class="col-sm-2">
  <div class="section">
    <label class="form_label"><i class="fa fa-user"></i> {{$details['name']}}</label>
     
    </div>
 </div>
<!-- end section -->  

<div class="col-sm-3">
    <div class="section">
    
    


   
                        
             <label class="form_label"><i class="fa fa-envelope"></i> {{$details['email']}}</label>
            

         
    
    </div>
</div>
<!-- <div class="col-sm-2">
  <div class="section">
    <label class="form_label"><i class="fa fa-phone"></i> {{$details['phone']}}</label>
     
    </div>
 </div>
 -->
<div class="col-sm-2">
  <div class="section">
    <label class="form_label"><i class="fa fa-phone"></i> {{$details['date']}}</label>
     
    </div>
 </div>


 <div class="col-sm-2">
  <div class="section">
    <label class="form_label"></label>
     
    </div>
 </div>
 <div class="col-sm-2">
  <div class="section">
    <label class="form_label"><i class="fa fa-inr"></i> {{$details['price']}}</label>
     
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


@endsection