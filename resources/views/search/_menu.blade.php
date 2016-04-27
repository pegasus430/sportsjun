<div class="col-sm-2 sidebar_bg">
	<div class="mp_left">
		<form action="{{ url('/search') }}">
          <h6>Sports</h6>
		  <div class="checklist filters">
		    <ul>
			   @foreach($sports_array as $key=>$val)
			   <li> <input class="checkbox2 sports_check" name="sports_arr[]" type="checkbox" value='{{$key}}' <?php if($request_params['sport'] == $key){?> checked="checked"<?php }?>>&nbsp;<span></span>{{$val}}</li>
			   @endforeach			
			</ul>
		  </div>

          </br>

        <input type="button" value="Go"  onclick="searchFilters();"  id="go" class="button btn-primary">
         <span class="clear_filter" onclick=" location.reload();">Clear</span>                   
		</button>
        </form >                    
	</div>
</div>
<script type="text/javascript"> 
function searchFilters(){
	var url = base_url + "/search";
	//Start to get checked sports in coma seperated
	var sport_values = $('input:checkbox:checked.sports_check').map(function () {
	  return this.value;
	}).get();
	//End

	var service = document.getElementById("service").value;
	var sport = sport_values;
	var search_by = document.getElementById("search_by").value;
		
	var data = "service="+service+"&sport="+sport+"&search_by="+search_by;
	//alert("suggested_"+type);
    showLoading("searchresultsDiv",500,'100%');	
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
</script>