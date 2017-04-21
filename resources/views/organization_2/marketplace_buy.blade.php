@extends('layouts.organisation')

@section('content')
<div class="container">
           
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Marketplace</h2> 

               <!-- 
                     <div class="create-btn-link">
                     <a href="/organization/{{$organisation->id}}/orders" class="wg-cnlink" style="margin-right: 150px;" >Orders</a>
                     <a href="/marketplace/create" class="wg-cnlink" >New Item</a>
                     </div> -->
                    </div>
            </div>
            <div class="row">

@include('marketplace._menu')


<div id="content" class="col-md-8 col-sm-8" style="background:#fff;">    

    <div class="sportsjun-forms sportsjun-container ">
        

            @if(count($items))
                
                @foreach($items as $item)
                           <div class="col-lg-4 col-sm-6">
                            <div class="shop-item">
                                <div class="shop-thumbnail"> <span class="shop-label text-danger">Sale</span>
                                    <a href="javascript:void(0)" data-pid="{{ $item['id'] }}"  data-page='{{ $marketplace }}' data-toggle="tooltip" data-placement="top" title="View Detail" class="view_gallery  item-link"></a> <img src="/org/marketplace/mp_img_1.png" alt="Shop item">
                                    <div class="shop-item-tools">
                                        <a href="#" class="add-to-whishlist" data-toggle="tooltip" data-placement="top" title="" data-original-title="Wishlist"> <i class="fa fa-heart-o"></i> </a>
                                        <a href="#" class="add-to-cart" onclick="add_to_cart({{$item->id}},'{{$item->item}}')"> <em>Add to Cart</em>
                                            <svg x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32">
                                                <path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="shop-item-details">
                                    <h3 class="shop-item-title"><a href="javascript:void(0)" data-pid="{{ $item['id'] }}"  data-page='{{ $marketplace }}' data-toggle="tooltip" data-placement="top" title="View Detail" class="view_gallery icon-info mp_info">Storage Box</a></h3> <span class="shop-item-price">
                            <span class="old-price">$49.00</span> $38.00 </span>
                                </div>
                            </div>
                            <!-- .shop-item -->
                        </div>

                @endforeach

             @else

                    <div class="sj-alert sj-alert-info sj-alert-sm message_new_for_team">
                        Post your Old/New Sports Equipment, Buying and Selling made easy.
                        </div>
                        <div class="intro_list_container">
                                <ul class="intro_list_on_empty_pages">
                                        <span class="steps_to_follow">Steps to follow:</span>
                                        <li>Click on the <span class="bold">Create New +</span> button on the top left side, select <span class="bold">Market Place.</span></li>
                                        <li><span class="bold">Upload</span> images and <span class="bold">fill</span> all the details related to item</li>
                                        <li>Your item will be posted, after approval by the Admin</li>
                                </ul>
                    </div>

                @endif
                        <!-- .col-lg-4.col-sm-6 -->
                 
                        <!-- .col-lg-4.col-sm-6 -->
                    </div>
                </div>
            </div>
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

         <div id="more" style="display:none; text-align: center" class="col-md-12">
<!--  <input type="button" value="View More" id="loadmore" onclick="marketplace(this);"  class="btn btn-green">
-->  
  <a class="view_tageline_mkt" id="loadmore" onclick="marketplace(this);"><span class="market_place"><i class="fa fa-arrow-down"></i> <label>View More</label></span></a>
  
 </div>
    <!--<button onclick="loadmore()" value="loadmore" />-->
  <div id="market" style="display:none; text-align: center" class="col-md-12">
       
        <input type="hidden" name="limit" id="limit" value="{{config('constants.LIMIT')}}" autocomplete="off"/>
        <input type="hidden" name="offset" id="offset" value="0" autocomplete="off"/>
        <input type="hidden" name="list_coun" id="list_coun" value="0"/>
        <input type="hidden" name="marketplace" id="marketplace" value="{{$page}}"/>
    </div>


@stop


@section('end_scripts')


    <script language="JavaScript">

      function marketplace(msg)
        {

             $("#market").show();
             var checkbox_value = [];
             var checkbox2_value=[];
              var checkbox3_value=[];
             var state= $( "#state_id" ).val();
             var city=  $( "#city_id" ).val();
             var offset= $('#offset').val();
             var limit=$('#limit').val();
             var id = $(msg).attr('id');
             var query=$( "#marketplace" ).val();
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

            var amount= $( "#amount" ).val();
            var token = "<?php echo csrf_token(); ?>";
            $.ajax({
                url: "{{ url('/marketplaceSearch') }}",
                type: "POST",
             //  dataType: 'JSON',
                data: {'_token': token,categorytype:checkbox_value,itemtype:checkbox2_value,itemstatus:checkbox3_value,amount:amount,state:state,city:city,offset:offset,limit:limit,id:id,query:query,searchType:searchType},
                success: function(data) {
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

@stop