@extends('admin.layouts.app')
@section('content')
<h1>User Bank Account</h1>
<p><b>Name</b>: {{$bankDetails->user->name}}</p>
<p><b>Account Holder Name</b>: {{$bankDetails->account_holder_name}}</p>
<p><b>Account Number</b>: {{$bankDetails->account_number}}</p>
<p><b>Bank Name</b>: {{$bankDetails->bank_name}}</p>
<p><b>Branch</b>: {{$bankDetails->branch}}</p>
<p><b>IFSC</b>: {{$bankDetails->ifsc}}</p>
<p><b>DOCS</b>: </p>
@stop