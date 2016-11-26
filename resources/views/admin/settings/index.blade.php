@extends('admin.layouts.app')
@section('content')
<h1>Disclaimer text</h1>


 {!! Form::open(array('url' => '/admin/settings', 'method' => 'post')) !!}               
        
           


       <div class="col-sm-11">
      <div class="section">
          <label class="field prepend-icon">
         
          
            {!! Form::textarea("description", $settings->description, array('required','class'=>'gui-input','placeholder' => 'description' )) !!}
            <br>
     
        {!! Form::submit('Submit', array('class' => 'button btn-primary')) !!}
            </label>
           </div>
        </div>

        {!! Form::close() !!}   
            
        
            
             
                
            
 

@stop