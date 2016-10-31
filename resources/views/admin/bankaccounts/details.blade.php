@extends('admin.layouts.app')
@section('content')
<h1>User Bank Account</h1>
<p><b>Name</b>: {{$bankDetails->user->name}}</p>
<p><b>Account Holder Name</b>: {{$bankDetails->account_holder_name}}</p>
<p><b>Account Number</b>: {{$bankDetails->account_number}}</p>
<p><b>Bank Name</b>: {{$bankDetails->bank_name}}</p>
<p><b>Branch</b>: {{$bankDetails->branch}}</p>
<p><b>IFSC</b>: {{$bankDetails->ifsc}}</p>
<p><b>DOCS</b>:<br>
@foreach ($img_array as $img)
<span><a href="{{$img}}">{{$img}}</a></span><br>
@endforeach
</p>
<br>
<form  action='admin/bankaccounts/details' method="post">
        <div class="col-md-4">
          <input type="hidden" name='id' value={{$bankDetails->id}}>
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="checkbox" name="verified" @if ($bankDetails->varified === 1) checked  @endif value="1">Verified<br>
        </div>
        <div class="form-group">
        
        <div class="col-md-4">
          <input type="submit" class="btn btn-primary" value="Save">
        </div>

</form>

@stop