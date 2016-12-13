@extends('admin.layouts.app')
@section('content')



 {!! Form::open(array('url' => '/admin/settings', 'method' => 'post')) !!}               
        
           


       <div class="col-sm-11">
      <div class="section">
      @foreach($settings as $set)
       <h4>{{$set->name}}</h4>
          <label class="field prepend-icon">
         
             
            {!! Form::textarea($set->name, $set->description, array('required','class'=>'gui-input','placeholder' => 'description' )) !!}
            <br>
            
     
        
           
            @endforeach
            </label>
            {!! Form::submit('Submit', array('class' => 'button btn-primary')) !!}

           </div>
        </div>

        {!! Form::close() !!}   
            
        
            
             
                
            
 

@stop