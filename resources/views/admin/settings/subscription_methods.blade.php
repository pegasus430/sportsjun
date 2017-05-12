@extends('admin.layouts.app')
@section('content')

<div class="table-responsive">
	<table class="table">
	<thead>
		<tr>
			<th>Title</th>
			<th>Type</th>
			<th>Duration</th>
			<th>Duration Type</th>
		</tr>
	</thead>
	<tbody>
		@foreach($s_methods as $sm)
			<tr>
				<td>{{$sm->title}}</td>
				<td>{{$sm->type}}</td>
				<td>{{$sm->duration}}</td>
				<td>{{$sm->duration_type}}</td>
			</tr>

@endforeach


	</tbody>
	</table>
</div>



            
            
 <button data-target='#add' data-toggle='modal' type="button"> Add Method</button>

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
    <form class="form-horizontal" action="/admin/subscription_methods/add" method="post" >
    {!! csrf_field() !!}
      <div class="form-group">
        <div class="col-sm-3">
        <label>Name </label>
        </div>
        <div class="col-sm-9">
        <input type="text" name="title" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-3">
        <label>Type</label>
        </div>
        <div class="col-sm-9">
        <select name="type" id='type' class="form-control">

           @foreach($types as $key=>$type)
           		<option value="{{$key}}">{{$type}}</option>
           @endforeach	
        </select>

        </div>
      </div>

       <div class="form-group">
        <div class="col-sm-3">
        <label>Duration /number of installments </label>
        </div>

        <div class="col-sm-9">
        <input type="text" name="duration" class="form-control">
        </div>
      </div>


      
      <div class="form-group">
      <div class="col-sm-3">
      <label>Description</label>
      </div>
      <div class="col-sm-9" id='description'>
        <textarea name="details" placeholder="setting details">
              
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



@stop