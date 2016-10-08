@extends('admin.layouts.app')
@section('content')
@include ('admin.sports._leftmenu')
<div id="page-wrapper">
    <h1><?php echo $feild_name;?></h1>
	
					                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
	
    <p>
	 {!! $filter !!}
       <?php echo $grid;?>  
    </p>
</div>
<script>
$( document ).ready(function() {
$(".test").datepicker({});
 });
function changestatus(str,id)
{

	var token = "<?php echo csrf_token();?>"; 
	//var response =  $('#response').val();
	$.ajax({
			  url: base_url+'/usermarketplace/update',
			  type: "PATCH",
			  type: "get",
			  dataType: 'JSON',
			  data: {'_token':token,'id':id,'status':str},
			  success: function(data){
				  //alert(data.success);
				 //console.log(data);
				 location.reload();
				// window.location = window.location;
			  }
		}); 
}

</script>
@stop