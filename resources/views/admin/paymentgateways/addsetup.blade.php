@extends('admin.layouts.app')
@section('content')
<h1>Tournaments</h1>

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

<p>
  

  <div class="rpd-dataform inline">
     
     {!! Form::open(array('url' => 'admin/paymentgateways/newsetup', 'method' => 'post','class' => 'form-inline' )) !!}

     {!! Form::hidden("gateway_id", $id) !!}
                
        
            
            <div class="form-group" id="fg_name">

        <label for="name" class="sr-only">Service Name</label>
        <span id="div_name">

            

{!! Form::text("setup_name", '', array('required','class'=>'gui-input','placeholder' => 'Service Name', 'class' => "form-control form-control" )) !!}




            
        </span>

    </div>
<div class="form-group" id="fg_value">

        <label for="value" class="sr-only">Service charge</label>
        <span id="div_value">

            
{!! Form::text("setup_value", '', array('required','class'=>'gui-input','placeholder' => 'Service Charge(In percentage)', 'class' => "form-control form-control" )) !!}






            
        </span>

    </div>
    
            
     {!! Form::submit('Add', array('class' => 'btn btn-primary')) !!}

@if($errors->first('setup_value')!='')
<span class="error_msg_admin">{{$errors->first('setup_value')}}</span>
@endif
{!! Form::close() !!}   
            
             
    </div>




  <?php echo $grid;?>  
</p>  
@stop