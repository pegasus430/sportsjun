@extends('admin.layouts.app')
@section('content')
<h1>User Bank Account</h1>
<div class="row">
	<div class="col-md-6">
		<p><b>Name</b>: {{$bankDetails->user->name}}</p>
		<p><b>Account Holder Name</b>: {{$bankDetails->account_holder_name}}</p>
		<p><b>Account Number</b>: {{$bankDetails->account_number}}</p>
		<p><b>Bank Name</b>: {{$bankDetails->bank_name}}</p>
		<p><b>Branch</b>: {{$bankDetails->branch}}</p>
		<p><b>IFSC</b>: {{$bankDetails->ifsc}}</p>
		<p><b>DOCS</b>:<br>
		@foreach ($img_array as $img)
		<span><a target="_blank" href="{{URL::to('uploads/'.$img)}}">{{$img}}</a></span><br>
		@endforeach
		</p>
	</div>
	<div class="col-md-6">
		<form  action='{{URL::to("admin/bankaccounts/details")}}' method="post">
			<div class="form-group">
		        
					<input type="hidden" name='id' value={{$bankDetails->id}}>
					<input type="hidden" name='user_id' value={{$bankDetails->user_id}}>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="radio" name="verified" @if ($bankDetails->varified === 1) checked  @endif value="1">Verified<br>
				

		   	</div>
		    <div class="form-group">
		    	
		    		<input type="radio" name="verified" @if ($bankDetails->varified === 2) checked  @endif value="2">Rejected<br>
		    	
		    </div>
		    <div class="form-group">
		    
			    
			      <input type="submit" class="btn btn-primary" value="Save">
			    <a href="{{URL::to('admin/bankaccounts/user/'.$bankDetails->user_id)}}" class="btn btn-danger">Cancel</a>
		    </div>

		</form>
	</div>
</div>
@stop