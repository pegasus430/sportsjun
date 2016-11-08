@extends('layouts.app')
@section('content')
<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">


<div class="form-header header-primary"><h4>{{$parent_tournamet_details->name}}<br>{{$parent_tournamet_details->contact_number}}</h4></div>
<div class="form-body">
{!! Form::open(array('url' => '/tournaments/registrationdata', 'method' => 'post')) !!}

<?php $i=0;
$options=array();?>
@foreach ($all_events as $events)
<div class="row">




<div class="col-sm-7">
      <div class="section">
          <label class="field prepend-icon">
            {!! Form::text("data[$events->id][name]", $events->name, array('required','class'=>'gui-input','readonly' => 'true' )) !!}
            <label  class="form_label">{{$events->match_type}}  {{$events->end_date}}</label>  
            </label>
           </div>
        </div>

<div class="col-sm-2">
  <div class="section">
    <label class="form_label">INR {{$events->enrollment_fee}}</label>
     
    </div>
 </div>
<!-- end section -->  

<div class="col-sm-3">
    <div class="section">
    
    <label class="field select">
                   <?php 
                     if($events->match_type == 'singles') {
                       $options=array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9); 
                   } else {
                         $options=array(0=>0,1=>1);
                   } ?>
                   {!! Form::select("data[$events->id][count]",$options, null,array('class'=>'gui-input')) !!}        
                    <i class="arrow double"></i> 
    </label>

         
    
    </div>
</div>

</div><!--end row -->
<?php $i++;?>
@endforeach

{!! Form::submit('Register Now', array('class' => 'button btn-primary')) !!}


{!! Form::close() !!}
 
</div>

</div>
</div>
</div>
@endsection