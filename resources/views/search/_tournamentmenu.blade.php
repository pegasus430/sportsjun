<div class="col-sm-2 sidebar_bg">
<form action="{{ url('/search') }}">
<input type="hidden" name="max" id="max" value="<?php echo $max; ?>">				
<input type="hidden" name="min" id="min" value="<?php echo $min; ?>">				
	<div class="mp_left sportsjun-forms">		
          <h6>Sports</h6>
		  <div class="checklist filters">
		    <ul>
			   @foreach($sports_array as $key=>$val)
			   <li><input class="checkbox2 sports_check" name="sports_arr[]" type="radio" value='{{$key}}' <?php if($request_params['sport'] == $key){?> checked="checked"<?php }?> autocomplete="off" onclick="fndisplay();">&nbsp;<span></span>{{$val}}</li>
			   @endforeach			
			</ul>
		  </div>
		  
		
		  <?php /* <h6>Gender</h6>
		  <div class="checklist filters">
		    <ul>
			   <li> <input class="checkbox2 gender_check" name="gender_arr[]" type="radio" value='male' autocomplete="off">&nbsp;<span></span>Male</li>
			   
			    <li> <input class="checkbox2 gender_check" name="gender_arr[]" type="radio" value='female' autocomplete="off">&nbsp;<span></span>Female</li>
				
				 <li> <input class="checkbox2 gender_check" name="gender_arr[]" type="radio" value='other' autocomplete="off">&nbsp;<span></span>Mixed</li>	
			</ul>
		  </div> */ ?>
		
		  
			<span id="displa_div" >
			 		  
			  <div class="form-group">
	          <h3 for="amount">Fee range:</h3>
	          <input type="text" id="amount" readonly  class="gui-input">
         </div>
		
        <div class="form-group slide">            
			<div id="slider-range"></div>
		</div>
			  
			</span>
		  
		
		
		
		
        </br>
        
		<input type="button" value="Go"  onclick="searchFilters();"  id="go" class="button btn-primary" />
        <span class="clear_filter btn-link btn-secondary-link" onclick=" location.reload();" style="display: inline-block; padding-top: 8px;">Clear</span> 
		
	</div>

	
</form >  
</div>
<script type="text/javascript"> 
function searchFilters(){
	var url = base_url + "/search";
	//Start to get checked sports in coma seperated
	var sport_values = $('input:radio:checked.sports_check').map(function () {
	  return this.value;
	}).get();
	//End
	
	//Start to get checked gender in coma seperated
	var gender_values = $('input:radio:checked.gender_check').map(function () {
	  return this.value;
	}).get();
	//End
	
	//Start to get checked availability in coma seperated
	var avialable_values = $('input:radio:checked.avialable_check').map(function () {
	  return this.value;
	}).get();
	//End
	
	//Start to get checked availability to join in coma seperated
	var joinavialable_values = $('input:radio:checked.joinavailable_check').map(function () {
	  return this.value;
	}).get();
	//End

	var service = document.getElementById("service").value;
	var sport = sport_values;
	var gender = gender_values;
	var avialability = avialable_values;
	var joinavialability = joinavialable_values;
	var search_by = document.getElementById("search_by").value;
	var amount = document.getElementById("amount").value;
	
	
	var sport_by = document.getElementById("sport_by").value;
	var city_by = document.getElementById("city_by").value;
	var category_by = document.getElementById("category_by").value;
	var search_by_name = document.getElementById("search_by_name").value;
	var search_city = document.getElementById("search_city").value;
	
	//alert(amount);
		
	var data = "service="+service+"&sport="+sport+"&search_by="+search_by+"&gender="+gender+"&avialability="+avialability+"&joinavialability="+joinavialability+"&amount="+amount+"&search_city_id="+city_by+"&category="+category_by+"&search_city="+search_city;
	//alert("suggested_"+type);
    showLoading("searchresultsDiv",'','');	
	$.ajax({
		type: "GET",
		data: data,
		url: url,
		global: false,
		success: function(result)
		{
			$("#searchresultsDiv").html(result);
	        hideLoading("searchresultsDiv");			
		},
		error: function(){
		  hideLoading("searchresultsDiv");
		}
	});
}

//Start slider
$(function() {
	var max=$('#max').val();
	var min=$('#min').val();
	// var min=$('#min').val();
	$( "#slider-range" ).slider({
		range: true,
		min: min,
		max: max,
		values: [ min, max ],
		slide: function( event, ui ) {
			$( "#amount" ).val(ui.values[ 0 ] + "-" + ui.values[ 1 ] );
		}
	});
	$( "#amount" ).val($( "#slider-range" ).slider( "values", 0 ) +
		"-" + $( "#slider-range" ).slider( "values", 1 ) );

});
//End

</script>
