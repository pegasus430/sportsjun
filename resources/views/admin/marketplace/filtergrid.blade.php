
@extends('admin.layouts.app')
@section('content')
@include ('admin.marketplace._leftmenu')
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

<div aria-hidden="true" style="display: none;" class="modal" id="modal-gallery" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal">×</button>
          <h3 class="modal-title">Image 11</h3>
      </div>
      <div class="modal-body clearfix">
            <div class="col-sm-8 col-xs-12">
              <div id="modal-carousel" class="carousel slide carousel-fade">
                <div class='carousel-outer'>
                    <div class="carousel-inner"></div>
                    <a class="carousel-control left" href="#modal-carousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
                    <a class="carousel-control right" href="#modal-carousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
                </div>
                <!-- Indicators -->
              <!-- <ol class='carousel-indicators mCustomScrollbar'>
                    <li data-target='#carousel-custom' data-slide-to='0' class='active'><img src='http://placehold.it/100x50&text=slide1' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='1'><img src='http://placehold.it/100x50&text=slide2' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='2'><img src='http://placehold.it/100x50&text=slide3' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='3'><img src='http://placehold.it/100x50&text=slide4' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='4'><img src='http://placehold.it/100x50&text=slide5' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='5'><img src='http://placehold.it/100x50&text=slide6' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='6'><img src='http://placehold.it/100x50&text=slide7' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='7'><img src='http://placehold.it/100x50&text=slide8' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='8'><img src='http://placehold.it/100x50&text=slide9' alt='' /></li>
                </ol>-->
            </div>
        	</div>
      </div>
      <div class="clearfix"></div>
      <!--<div class="modal-footer">
          <button class="btn btn-green" data-dismiss="modal">Close</button>
      </div>-->
    </div>
  </div>
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