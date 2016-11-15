@extends('layouts.app')
@section('content')
@include ('tournaments._leftmenu')

<div id="content" class="col-sm-10 tournament_profile">
	<div class="col-md-6">
	<div class="form-header header-primary"><h4>Register Form<!-- (No of Participants {{ $register_data->participant_count }}   Total Amount <i class="fa fa-inr"></i> {{$register_data->participant_count*$register_data->enrollment_fee}}) --></h4></div>
    	
       
	</div> 


<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container">





<div class="form-body">
{!! Form::open(array('url' => '/tournaments/registrationstep5', 'method' => 'post')) !!}

{!! Form::hidden("match_type", $register_data->match_type) !!}
{!! Form::hidden("event_id", $register_data->event_id) !!}
{!! Form::hidden("cart_id", $register_data->cart_id) !!}

@if ($register_data->match_type === 'singles')

<?php
$count=$register_data->participant_count;
?>

@for ($i = 0; $i < $register_data->participant_count; $i++)

<h4>{{$sport_name}}({{$register_data->match_type}})</h4>

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            <label class="field-icon"><i class="fa fa-user"></i></label>
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
            <label class="field-icon"><i class="fa fa-envelope"></i></label>
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
             <label class="field-icon"><i class="fa fa-group"></i></label>
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
             <label class="field-icon"><i class="fa fa-mobile"></i></label>
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


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
             <label class="field-icon"><i class="fa fa-map-marker"></i></label>
             <?php
            if($i==0 && isset($user_data->location)) {
            	$loc=$user_data->location;
            } else {
            	$loc='';
            } ?>
            {!! Form::textarea("single[$i][location]", $loc, array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
            </label>
           </div>
        </div>


<!-- end section -->  



</div><!--end row -->
@endfor

@elseif ($register_data->match_type === 'doubles')

<h4>{{$sport_name}}({{$register_data->match_type}})</h4>

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
               <label class="field-icon"><i class="fa fa-group"></i></label>
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
           <label class="field-icon"><i class="fa fa-user"></i></label>
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
    <label class="field-icon"><i class="fa fa-envelope"></i></label>
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
          <label class="field-icon"><i class="fa fa-group"></i></label>
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
    <label class="field-icon"><i class="fa fa-mobile"></i></label>
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


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
          <label class="field-icon"><i class="fa fa-map-marker"></i></label>
           <?php
            if($i==0 && isset($user_data->location)) {
            	$loc=$user_data->location;
            } else {
            	$loc='';
            } ?>
            {!! Form::textarea("doubles[$i][location]", $loc, array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
            </label>
           </div>
        </div>


<!-- end section -->  



</div><!--end row -->
<?php $j++; ?>
@endfor



@else


<h4>{{$sport_name}}({{$register_data->match_type}})</h4>
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
              <label class="field-icon"><i class="fa fa-group"></i></label>
           
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
          <label class="field-icon"><i class="fa fa-user"></i></label>
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
    <label class="field-icon"><i class="fa fa-envelope"></i></label>
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
          <label class="field-icon"><i class="fa fa-group"></i></label>
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
    <label class="field-icon"><i class="fa fa-mobile"></i></label>
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


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
          <label class="field-icon"><i class="fa fa-map-marker"></i></label>
           <?php
            if(isset($user_data->location)) {
            	$loc=$user_data->location;
            } else {
            	$loc='';
            } ?>
            {!! Form::textarea("team_owner[location]", $loc, array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
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
