@extends('admin.layouts.app')
@section('content')
    <h1>Team List</h1>
	
					 @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
					@elseif (session('error_msg'))
				<div class="alert alert-danger">
					{{ session('error_msg') }}
				</div>
				@endif
	
    <p>
	   <?php echo $filter;?>  
       <?php echo $grid;?>  
    </p>
@stop