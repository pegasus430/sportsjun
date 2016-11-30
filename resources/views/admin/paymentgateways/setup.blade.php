@extends('admin.layouts.app')
@section('content')
<h1>Payment Gate Way Setups</h1>

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