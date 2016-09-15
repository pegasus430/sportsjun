@extends('admin.layouts.app')
@section('content')
    <h1>Tournaments List</h1>
	
					 @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
	
    <p>
	   <?php echo $filter;?>  
       <?php echo $grid;?>  
    </p>
	 
<script type="text/javascript">
           
  $( document ).ready(function() {
$(".test").datepicker({});
});

</script>    
@stop