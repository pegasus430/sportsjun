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
// $open_timestamp = strtotime($tournament_data->reg_opening_date);
// $open_day = date('D', $open_timestamp);
// $newopen = date("d-m-Y", strtotime($tournament_data->reg_opening_date));
// $opentime=date("h:i A", strtotime($tournament_data->reg_opening_time));
// $close_timestamp = strtotime($tournament_data->reg_closing_date);
// $close_day = date('D', $close_timestamp);
// $newclose = date("d-m-Y", strtotime($tournament_data->reg_closing_date));
// $closetime=date("h:i A", strtotime($tournament_data->reg_closing_time));


 $open_timestamp = strtotime($tournament_data->start_date);
  $open_day = date('D', $open_timestamp);
  $newopen = date("d-m-Y", strtotime($tournament_data->start_date));

  $close_timestamp = strtotime($tournament_data->end_date);
  $close_day = date('D', $close_timestamp);
  $newclose = date("d-m-Y", strtotime($tournament_data->end_date));
?>
<h4><i class="fa fa-calendar-o"></i>{{$open_day}} {{$newopen}} - {{$open_day}} {{$newclose}}</h4><br>
<h4><i class="fa fa-map-marker"></i>{{preg_replace('/(?<!\d),|,(?!\d{3})/', ', ',$tournament_data->location)}}</h4>
</div>

@if($errors->any())
<h4 class="error_validation">{{$errors->first()}}</h4>
@endif

<div class="form-body">
{!! Form::open(array('url' => '/tournaments/registrationstep5', 'method' => 'post')) !!}

{!! Form::hidden("match_type", $register_data->match_type) !!}
{!! Form::hidden("event_id", $register_data->event_id) !!}
{!! Form::hidden("cart_id", $register_data->cart_id) !!}


{!! Form::hidden("refresh", 'no',array('id'=>'refreshed')) !!}

@if ($register_data->match_type === 'singles')

<?php
$count=$register_data->participant_count;
?>

@for ($i = 0; $i < $register_data->participant_count; $i++)

<h4 class="form_titles">{{$sport_name}}({{$register_data->match_type}})</h4>

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-user"></i></label>
            <?php
            if($i==0 && isset($user_data->name)) {
            	$nm=$user_data->name;
            } else {
            	$nm='';
            } ?>

            {!! Form::text("single[$i][name]",$nm, array('required','class'=>'gui-input','placeholder' => 'Full Name' )) !!}
            
            </label>


              


           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-envelope"></i></label>
            <?php
            if($i==0 && isset($user_data->email)) {
            	$mail=$user_data->email;
            } else {
            	$mail='';
            } ?>
            {!! Form::email("single[$i][email]", $mail, array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
             <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-group"></i></label>
              <?php
            if($i==0 && isset($user_data->club)) {
            	$clb=$user_data->club;
            } else {
            	$clb='';
            } ?>
            {!! Form::text("single[$i][club]",$clb, array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
             <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-mobile"></i></label>
              <?php
            if($i==0 && isset($user_data->contact_number)) {
            	$c_no=$user_data->contact_number;
            } else {
            	$c_no='';
            } ?>
            {!! Form::text("single[$i][number]", $c_no, array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->



@endfor

@elseif ($register_data->match_type === 'doubles')

<h4 class="form_titles">{{$sport_name}}({{$register_data->match_type}})</h4>

<div class="row">




<div class="col-sm-5">
    <div class="section">
    
    
            @if (count($teams_array) > 0)
            <label class="field select">
            {!! Form::select("team_id",$teams_array, null,array('class'=>'gui-input')) !!}
             <i class="arrow double"></i>
             </label>   
            @else
              <label class="field prepend-icon">
               <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-group"></i></label>
              {!! Form::text("team_name", '', array('required','class'=>'gui-input','placeholder' => 'Team Name' )) !!}
              </label>
            @endif
           
    

         
    
    </div>
</div>

<div class="col-sm-6">
  
 </div>
<!-- end section -->  



</div><!--end row -->

<?php $j=1;?>
@for ($i = 0; $i < 2; $i++)


<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
           <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-user"></i></label>
            <?php
            if($i==0 && isset($user_data->name)) {
            	$nm=$user_data->name;
            } else {
            	$nm='';
            } ?>

            {!! Form::text("doubles[$i][name]", $nm, array('required','class'=>'gui-input','placeholder' => 'Player'.$j )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
    <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-envelope"></i></label>
     <?php
            if($i==0 && isset($user_data->email)) {
            	$mail=$user_data->email;
            } else {
            	$mail='';
            } ?>
            {!! Form::email("doubles[$i][email]",$mail, array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
          <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-group"></i></label>
          <?php
            if($i==0 && isset($user_data->club)) {
            	$clb=$user_data->club;
            } else {
            	$clb='';
            } ?>
            {!! Form::text("doubles[$i][club]", $clb, array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
    <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-mobile"></i></label>
     <?php
            if($i==0 && isset($user_data->contact_number)) {
            	$c_no=$user_data->contact_number;
            } else {
            	$c_no='';
            } ?>
            {!! Form::text("doubles[$i][number]", $c_no, array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->








<?php $j++; ?>
@endfor



@else


<h4 class="form_titles">{{$sport_name}}({{$register_data->match_type}})</h4>
<div class="row">




<div class="col-sm-5">
    <div class="section">
            
    
            @if (count($teams_array) > 0)

            <label class="field select">
            
            {!! Form::select("team_id",$teams_array, null,array('class'=>'gui-input')) !!}
             <i class="arrow double"></i>
             
             </label>   
            @else
              <label class="field prepend-icon">
              <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-group"></i></label>
           
              {!! Form::text("team_name", '', array('required','class'=>'gui-input','placeholder' => 'Team Name' )) !!}
              </label>
            @endif
           
    

         
    
    </div>
</div>

<div class="col-sm-6">
  
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
      
          <label class="field prepend-icon">
          <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-user"></i></label>
           <?php
            if(isset($user_data->name)) {
            	$nm=$user_data->contact_number;
            } else {
            	$nm='';
            } ?>
            {!! Form::text("team_owner[name]", $nm, array('required','class'=>'gui-input','placeholder' => 'Full Name' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
  
    <label class="field prepend-icon">
    <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-envelope"></i></label>
           <?php
            if(isset($user_data->email)) {
            	$mail=$user_data->email;
            } else {
            	$mail='';
            } ?>
            {!! Form::email("team_owner[email]", $mail, array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
      
          <label class="field prepend-icon">
          <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-group"></i></label>
           <?php
            if(isset($user_data->club)) {
            	$clb=$user_data->club;
            } else {
            	$clb='';
            } ?>
            {!! Form::text("team_owner[club]", $clb, array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
 
    <label class="field prepend-icon">
    
    <label class="field-icon"><span  class='required validation'>*</span><i class="fa fa-mobile"></i></label>
           <?php
            if(isset($user_data->contact_number)) {
            	$c_no=$user_data->contact_number;
            } else {
            	$c_no='';
            } ?>
            {!! Form::text("team_owner[number]", $c_no, array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->





@endif





{!! Form::submit('Register Now', array('class' => 'button btn-primary')) !!}


{!! Form::close() !!}
 
</div>


</div>
</div>

</div>
@endsection
