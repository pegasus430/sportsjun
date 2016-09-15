@extends('layouts.app')
@section('content')
    <h1>Facility List</h1>
	
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