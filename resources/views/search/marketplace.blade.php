@extends('layouts.app')

@section('content')

@include('marketplace._menu')

<div id="content" class="col-sm-10">    
<div class="search_header_msg">Search results for items @if($search_city) in {{$search_city}}  @endif @if($search_by_name)  with name "{{$search_by_name}}" @endif </div>

<div id="marketplaceid"></div>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<div aria-hidden="true" style="display: none;" class="modal" id="modal-gallery" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal">×</button>
          <h3 class="modal-title">Image 11</h3>
      </div>
      <div class="modal-body clearfix">
            <div class="col-xs-8">
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
 <div id="more" style="display:none; text-align: center" class="col-md-12">
<!--  <input type="button" value="View More" id="loadmore" onclick="marketplace(this);"  class="btn btn-green">
-->   <a  id="loadmore" onclick="marketplace(this);" class="view_tageline_mkt"><span class="market_place"><i class="fa fa-arrow-down"></i> <label>View More</label></span></a>
 </div>
	<!--<button onclick="loadmore()" value="loadmore" />-->
  <div id="market" style="display:none; text-align: center" class="col-md-12">
       
        <input type="hidden" name="limit" id="limit" value="{{config('constants.LIMIT')}}" autocomplete="off"/>
        <input type="hidden" name="offset" id="offset" value="0" autocomplete="off"/>
        <input type="hidden" name="list_coun" id="list_coun" value="0"/>
        <input type="hidden" name="marketplace" id="marketplace" value="{{$page}}"/>
		
		 <input type="hidden" name="city" id="city" value="{{$city}}"/>
        <input type="hidden" name="name1" id="name1" value="{{$name}}"/>
		 <input type="hidden" name="category" id="category" value="{{$category}}"/>
        <input type="hidden" name="modeltype" id="modeltype" value="{{$modeltype}}"/>
		
    </div>
</div>


        <!--   </div>
        </div> -->
<script language="JavaScript">
 function marketplace(msg)
        {

			 $("#market").show();
			 var checkbox_value = [];
			 var checkbox2_value=[];
			  var checkbox3_value=[];
			 var state=	$( "#state_id" ).val();
			 var city=	$( "#city" ).val();
			 var offset= $('#offset').val();
	         var limit=$('#limit').val();
			 var id = $(msg).attr('id');
			 var query=$( "#marketplace" ).val();
			  var name=$( "#name1" ).val();
			 var searchType;
			 if(id=="go")
			 {
				 offset = 0;
				 $('#marketplaceid').html('');
			 }
			$(".checkbox1").each(function () {
				var ischecked = $(this).is(":checked");
				if (ischecked) {
					checkbox_value.push($(this).val());
				}
			});
				$(".checkbox2").each(function () {
				var ischecked = $(this).is(":checked");
				if (ischecked) {
					checkbox2_value.push($(this).val());
				}
			});
          $(".checkbox3").each(function () {
				var ischecked = $(this).is(":checked");
				if (ischecked) {
					checkbox3_value.push($(this).val());
				}
			});

				$(".radio1").each(function () {
				var ischecked = $(this).is(":checked");
			if (ischecked) {
					searchType=$(this).val();
				}
			});

			var amount=	$( "#amount" ).val();
            var token = "<?php echo csrf_token(); ?>";
            $.ajax({
                url: "{{ url('/marketplaceSearch') }}",
                type: "POST",
             //  dataType: 'JSON',
                data: {'_token': token,categorytype:checkbox_value,itemtype:checkbox2_value,itemstatus:checkbox3_value,amount:amount,state:state,city:city,offset:offset,limit:limit,id:id,query:query,searchType:searchType,name:name},
                success: function(data) {
                     // var count= data.list_count;
					 // if(data.offset<data.list_count)
					 // {
						
					  // $('#marketplaceid').append(data.html);
					    	// $("#more").show();
					  // $('#offset').val(data.offset);
                      // $('#limit').val(data.limit);
			          // $('#list_count').val(data.list_count);

				     // }
					 // else{
						 // $('#marketplaceid').append(data.html);
					  	// $("#market").hide();
						// $("#more").hide();
				   // }
				   
				   
				    var count= data.list_count;
					 if(data.offset<data.list_count)
					 {
					if(offset == 0){
					  $('#marketplaceid').append(data.html);
                    }else{
                        $('#marketplaceid .market_gallery').append(data.html);
                    }
					    	$("#more").show();
					  $('#offset').val(data.offset);
                      $('#limit').val(data.limit);
			          $('#list_count').val(data.list_count);

				     }
					 else{
						 if(offset == 0){
                      $('#marketplaceid').append(data.html);
                    }else{
                        $('#marketplaceid .market_gallery').append(data.html);
                    }
					  	$("#market").hide();
						$("#more").hide();
				   }
				   
				   
				   
				   

				}


            });

        }




$(document).ready(function() {


		marketplace(this);

    $('#selecctall').click(function(event) {

        if(this.checked) {
            $('.checkbox1').each(function() {
                this.checked = true;
            });
        }else{
            $('.checkbox1').each(function() {
                this.checked = false;

            });
        }
    });

	 $('.checkbox1').on('click',function(){
        if($('.checkbox1:checked').length == $('.checkbox1').length){
            $('#selecctall').prop('checked',true);
        }else{
            $('#selecctall').prop('checked',false);
        }
    });


});

 </script>
 <script>

	$(function() {
		 var max=$('#max').val();
		// var min=$('#min').val();
		$( "#slider-range" ).slider({
			range: true,
			min: 0,
			max: max,
			values: [ 0, max ],
			slide: function( event, ui ) {
				$( "#amount" ).val(ui.values[ 0 ] + "-" + ui.values[ 1 ] );
			}
		});
		$( "#amount" ).val($( "#slider-range" ).slider( "values", 0 ) +
			"-" + $( "#slider-range" ).slider( "values", 1 ) );

	});

	</script>


<script type="text/javascript">
// Function to display city for states
    function displayStates(stateid) {
    if (!stateid) {
    $("#city_id").html("<option value=''>Select City</option>");
            return false;
    }
    $.ajax({
    url: "{{URL('getcities')}}",
            type : 'GET',
            data : {id:stateid},
            dataType: 'json',
            beforeSend: function () {
            $.blockUI({ width:'50px', message: $("#spinner").html() });
            },
            success : function(response){
            $.unblockUI();
                    var options = "<option value=''>Select City</option>";
                    $.each(response, function(key, value) {
                    options += "<option value='" + value['id'] + "'>" + value['city_name'] + "</option>";
                    });
                    $("#city_id").html(options);
                    @if (!empty(old('city_id')))
                    $("#city_id").val({{old('city_id')}}); ;
                    @endif
            }
    });
    }

</script>
@endsection
<!--<link href="{{ asset('/css/marketplace.css') }}" rel="stylesheet">-->
