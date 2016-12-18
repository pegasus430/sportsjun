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

      



<?php $i=0;
$options=array();?>
@foreach ($details as $det)

<div class="row head_reg">

        <div class="col-sm-3">
            <div class="section">
                <label class="field prepend-icon head_tr">
                  Tournament Name : {{$det[0]['t_name']}}
                </label>
            </div>
        </div>

        <div class="col-sm-2">
           <div class="section">
           <label class="field prepend-icon head_tr">Total enrollment : {{$det[0]['tot_enrollmet']}}</label>
     
          </div>
        </div>
 

      <div class="col-sm-3">
          <div class="section">
            <label class="field prepend-icon head_tr">
             Currently Registerd : {{$det[0]['current_enrollmet']}}
            </label>
         </div>
      </div>




      <div class="col-sm-3">
         <div class="section">
            <label class="field prepend-icon head_tr">
            Remaining Enrollment: {{$det[0]['remaining_enrollmet']}}
            </label>
         </div>
      </div>

  </div><!--end row -->
 <br>
@foreach ($det as $de)
<div class="row inner_events inner_reg">




     <div class="col-sm-2">
       <div class="section">
         <label class="form_label"><i class="fa fa-user"></i> {{$de['data']['name']}}</label>  
        </div>
     </div>
     <div class="col-sm-2">
       <div class="section">
       @if($de['data']['team'] != '')
         <label class="form_label"><i class="fa fa-group"></i> {{$de['data']['team']}}</label>  
          @endif 
        </div>
     </div>
     <div class="col-sm-3">
       <div class="section">
       
         <label class="form_label"><i class="fa fa-envelope"></i> {{$de['data']['email']}}</label>  
       
        </div>
     </div>
     <div class="col-sm-3">
       <div class="section">
         <label class="form_label">{{$de['data']['date']}}</label>  
        </div>
     </div>
     <div class="col-sm-2">
       <div class="section">
         <label class="form_label"><i class="fa fa-inr"></i> {{$de['data']['price']}}</label>  
        </div>
     </div>



</div><!--end row -->
<br>

<?php $i++;?>
@endforeach
@endforeach


 
</div>


   </div>
  </div>
 </div>


@endsection