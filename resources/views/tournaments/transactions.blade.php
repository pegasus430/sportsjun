@extends('layouts.app')
@section('content')

<div class="col-lg-8 col-md-10 col-sm-12 col-md-offset-1 col-lg-offset-2 tournament_profile teamslist-pg" style="padding-top: 3px !important;">
 <div class="panel panel-default">
   <div class="panel-body">
     


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
<div class="col-sm-2">
  <div class="section">
    <label class="form_label"><i class="fa fa-phone"></i> {{$details['phone']}}</label>
     
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