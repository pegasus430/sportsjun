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
            {!! Form::email("single[$i][email]", '', array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
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
    
    
            @if (count($teams_array) > 0)
            <label class="field select">
            {!! Form::select("team_id",$teams_array, null,array('class'=>'gui-input')) !!}
             <i class="arrow double"></i>
             </label>   
            @else
              <label class="field prepend-icon">
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
            {!! Form::text("doubles[$i][name]", '', array('required','class'=>'gui-input','placeholder' => 'Player'.$j )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::email("doubles[$i][email]", '', array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("doubles[$i][club]", '', array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("doubles[$i][number]", '', array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::textarea("doubles[$i][location]", '', array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
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
            {!! Form::text("team_owner[name]", '', array('required','class'=>'gui-input','placeholder' => 'Full Name' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::email("team_owner[email]", '', array('required','class'=>'gui-input','placeholder' => 'Email' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->

<div class="row">




<div class="col-sm-5">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("team_owner[club]", '', array('required','class'=>'gui-input','placeholder' => 'Club' )) !!}
            
            </label>
           </div>
        </div>

<div class="col-sm-6">
  <div class="section">
    <label class="field prepend-icon">
            {!! Form::text("team_owner[number]", '', array('required','class'=>'gui-input','placeholder' => 'Contact Number' )) !!}
           
            </label>
     
    </div>
 </div>
<!-- end section -->  



</div><!--end row -->


<div class="row">




<div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::textarea("team_owner[location]", '', array('required','class'=>'gui-input','placeholder' => 'Location' )) !!}
            
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
