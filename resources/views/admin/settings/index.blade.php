@extends('admin.layouts.app')
@section('content')



 {!! Form::open(array('url' => '/admin/settings', 'method' => 'post')) !!}               
        
           


       <div class="col-sm-11">
      <div class="section">
      @foreach($settings as $set)
      <div class="row">
      <div class="form-group">
      <div class="col-sm-3">
       <h4>{{$set->name}}</h4>
       </div>
      <div class="col-sm-6">
          <label class="field prepend-icon">
         
          @if($set->type=='radio')
            <input type="radio" name="{{$set->name}}" value="1" {{$set->description=='1'?'checked':''}} > Yes 
            &nbsp;&nbsp; 
            <input type="radio" name="{{$set->name}}" value="0" {{$set->description!='1'?'checked':''}}> No
          @else
            {!! Form::textarea($set->name, $set->description, array('required','class'=>'gui-input','placeholder' => 'description' )) !!}
          @endif
     
          
            </label>
        </div>
        </div>
          </div>
          @endforeach
    {!! Form::submit('Submit', array('class' => 'button btn-primary')) !!}

         
        </div>

        {!! Form::close() !!}   
            
        
            
             
                
            
 <button data-target='#add' data-toggle='modal' type="button"> Add Setting</button>

 <div class="modal fade " tabindex="-1" id='add'>

    <div class="modal-dialog sj_modal ">
                 <div class="modal-content">
                    <div class="modal-header text-center">
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                            <h4>New Setting</h4>
                                  </div>
                      <div class="alert alert-danger" id="div_failure1"></div>
                      <div class="alert alert-success" id="div_success1" style="display:none;"></div>
                    <div class="modal-body">
    <form class="form-horizontal" action="/admin/settings/add" method="post" onsubmit="get_type()">
    {!! csrf_field() !!}
      <div class="form-group">
        <div class="col-sm-3">
        <label>Name </label>
        </div>
        <div class="col-sm-6">
        <input type="text" name="name" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-3">
        <label>Type</label>
        </div>
        <div class="col-sm-6">
        <select name="type" id='type' class="form-control">
            <option>radio</option>
            <option>text</option>
            <option>textarea</option>
        </select>

        </div>
      </div>
      
      <div class="form-group">
      <div class="col-sm-3">
      <label>Description</label>
      </div>
      <div class="col-sm-6" id='description'>
        <textarea name="description" placeholder="setting details">
              
        </textarea>
      </div>
    </div>
     
 </div>
  <div class="modal-footer">
                    <button type='submit' class='btn btn-primary'  >Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
        </div>


        <script type="text/javascript">
            function get_type(){
                if($('#type').val()=='option'){
                    $('#description').val('1');
                }
            }
        </script>

@stop