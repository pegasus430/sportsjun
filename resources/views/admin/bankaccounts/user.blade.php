@extends('admin.layouts.app')
@section('content')
<h1>Bank Details of <b>{{$user->name}}</b></h1>

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif

<p>
  <?php echo $filter;?>  
  <?php echo $grid;?>  
</p>  
@stop