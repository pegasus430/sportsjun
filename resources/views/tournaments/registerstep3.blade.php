@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
<div class="">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">


<div class="form-header header-primary"><h4>jjjjj<br>jhhhhh</h4></div>
<div class="form-body">
{!! Form::open(array('url' => '/tournaments/registrationdata', 'method' => 'post')) !!}

<?php $i=0;
$options=array();?>
@foreach ($register_data->cart_details as $value) 
<div class="row">




<div class="col-sm-7">
  <div class="section">
     <label class="field prepend-icon">
        {!! Form::text("name", '', array('required','class'=>'gui-input','readonly' => 'true' )) !!}
      <label  class="form_label">dxjhedbxhjbedj  dxjjxjx</label>  
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