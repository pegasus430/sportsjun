@extends('admin.layouts.app')
@section('content')

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

<script>
function changeuserStatus(str,id)
{

	var token = "<?php echo csrf_token();?>"; 
	//alert(base_url+'/user/updateUsers');
	//var response =  $('#response').val();
	$.ajax({
			  url: base_url+'/admin/user/updateUsers',
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