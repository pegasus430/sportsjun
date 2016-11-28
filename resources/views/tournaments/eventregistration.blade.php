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

<h4 class='register_form_title'>{{$parent_tournamet_details->name}}</h4><br><br>
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


@if($errors->any())
<h4 class="error_validation">{{$errors->first()}}</h4>
@endif
<div class="form-body">
@if(Auth::user()) 
{!! Form::open(array('url' => '/tournaments/registrationdata', 'method' => 'post')) !!}
@else
{!! Form::open(array('url' => 'guest/tournaments/guestregistrationdata', 'method' => 'post')) !!}
@endif


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
@foreach ($all_events as $events)
<div class="row inner_events">




<div class="col-sm-7">
      <div class="section">
          <label class="field prepend-icon">
           <label class="field-icon"><i class="fa fa-trophy"></i></label>
            {!! Form::text("data[$events->id][name]", $events->name, array('required','class'=>'gui-input form_rows','readonly' => 'true' )) !!}
            <label  class="form_label last_date_span">Last Date:{{$events->end_date}}</label>  
            </label>
          

           </div>
        </div>

<div class="col-sm-2">
  <div class="section">
    <label class="form_label"><i class="fa fa-inr"></i> {{$events->enrollment_fee}}</label>
     
    </div>
 </div>
<!-- end section -->  

<div class="col-sm-3">
    <div class="section">
    
    


    <label class="field prepend-icon">
              <?php 
                     if($events->match_type == 'singles') {
                       $options=array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9); 
                   } else {
                         $options=array(0=>0,1=>1);
                   } ?>
             {!! Form::select("data[$events->id][count]",$options, null,array('class'=>'form-control valid')) !!}             
             <label for="Pointstolosingteam" class="field-icon"><i class="fa fa-group"></i></label>
            </label>

         
    
    </div>
</div>

</div><!--end row -->
<br>
<br>
<?php $i++;?>
@endforeach

{!! Form::submit('Register Now', array('class' => 'button btn-primary')) !!}


{!! Form::close() !!}
 
</div>

</div>
</div>
</div>
@endsection