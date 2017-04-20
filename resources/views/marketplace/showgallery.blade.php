@foreach($photos as $photo)	
<div class="item" id="image-1">
  <!--<img class="img-responsive" title="{{ $item->item}}" src="{{url('/uploads/marketplace/'. $photo)}}">-->
   {!! Helper::Images( $photo,'marketplace',array('class'=>'img-responsive','title'=> $item->item) )
!!}	
</div>
 @endforeach
   <div id="itemname"  style="display: none;" > <center><b> {{$itemname }} </b></center></div>
 <!-- <div class="item" id="image-1-desc" onClick ='showDetails();' style="z-index: 3000;">show Details</div>-->
 <div id="details" class="col-sm-4 col-xs-12 details1" >
<!--     <div id="itemname" ><strong>Item: {{$itemname }}</strong></div>-->
     <div id="base_price" >Buy for <span class="fa fa-inr"> {{$base_price }} </span></div>
     <div id="actual_price">Selling Price: <span class="strike-through fa fa-inr">{{$actual_price}}</span></div>
<!--	  <div id="actual_price">Selling Price: <span class="strike-through">{{$actual_price}}</span></div>
-->     <div id="itemdescription" ><strong>Description</strong> <p>{{$itemdescription }}</p></div>
	  <div id="location" ><strong>Location</strong> <p>{{$location }}</p></div>
      <div class="contact1" style="display: none;"  ><strong>Contact Number</strong> <p>{{$contact_number }}</p></div>
	  <div class="contactnumber" style="display: none;" > {{$contact_number}}</div>
	  <div  id="marketid"  style="display: none;"  >{{ $id}}</div>



    @if($item->organization_id)
          <a href="javascript:void(0)" class="add-to-cart" onclick="add_to_cart({{$item->id}},'{{$item->item}}')"> <em>Add to Cart</em>
                                            <svg x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32">
                                                <path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"></path>
                                            </svg>
                                        </a>
    @endif


</div>





<script>
$(document).ready(function() {
    $(".mCustomScrollbar").mCustomScrollbar({axis:"x"});
});
</script>