@extends('layouts.app')
@section('content')
@include ('tournaments._leftmenu')

<div id="content" class="col-sm-10 tournament_profile">
	<div class="col-md-6">
    	<div class="group_no clearfix">
        	<h4 class="stage_head">No of Registrations {{ $register_data->participant_count }}   Total Amount INR {{$register_data->participant_count*$register_data->enrollment_fee}}</h4>
        </div>
       
	</div> 
<br>
<h4>Registration Details</h4>
<br>

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
            {!! Form::text("single[$i][name]", '', array('required','class'=>'gui-input','placeholder' => 'Full Name' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("single[$i][email]", '', array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("single[$i][club]", '', array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("single[$i][number]", '', array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::textarea("single[$i][location]", '', array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
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
    
    <label class="field select">
                  <?php $options=array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);    ?>
            {!! Form::select("data[count]",$options, null,array('class'=>'gui-input')) !!}
            <i class="arrow double"></i>   
    </label>

         
    
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
            {!! Form::text("data[name]", '', array('required','class'=>'gui-input','placeholder' => 'Player 1' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("data[email]", '', array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("data[club]", '', array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("data[number]", '', array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::textarea("data[location]", '', array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
            </label>
           </div>
        </div>


<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("data[name]", '', array('required','class'=>'gui-input','placeholder' => 'Player 2' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("data[email]", '', array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("data[club]", '', array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("data[number]", '', array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::textarea("data[location]", '', array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
            </label>
           </div>
        </div>


<!-- end section -->  



</div><!--end row -->

@else


<h4>{{$sport_name}}({{$register_data->match_type}})</h4>
<div class="row">
<div class="col-sm-5">
    <div class="section">
    
    <label class="field select">
                  <?php $options=array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);    ?>
            {!! Form::select("data[count]",$options, null,array('class'=>'gui-input')) !!}
            <i class="arrow double"></i>   
    </label>

         
    
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
            {!! Form::text("data[name]", '', array('required','class'=>'gui-input','placeholder' => 'owner Nmae' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("data[email]", '', array('required','class'=>'gui-input','placeholder' => 'Owner Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("data[club]", '', array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("data[number]", '', array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::textarea("data[location]", '', array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
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
