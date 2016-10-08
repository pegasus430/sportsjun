 $(window).load(function() {
	//setInterval("suggestedWidget('players')", 2000);
	//suggestedWidget('players');
	// alert(widzet_type);
	var widzets_arr = widzet_type.split(',');


	for(var i = 0; i < widzets_arr.length; i++) {

		if(widzets_arr[i] == 'players'){
			var type_id = widzet_team_id_param;
			// var type_id = widzet_player_id_param;
		}else if(widzets_arr[i] == 'teams'){
			var type_id = widzet_team_id_param;

		}else if(widzets_arr[i] == 'tournaments'){
			var type_id = widzet_tournament_id_param;

		}else if(widzets_arr[i] == 'facility'){
			var type_id = widzet_facility_id_param;

		}
		var sport_id = widzet_sport_param;

	   suggestedWidget(widzets_arr[i],type_id,sport_id);
	}
	//suggestedWidget(widzet_type);
});

function suggestedWidget(type,type_id,sport_id,flag_type){	
	var url = base_url + "/search/suggestedWidget";

	var data = "type="+type+'&type_id='+type_id+'&sport_id='+sport_id+'&flag_type='+flag_type;;
	//alert("suggested_"+type);
    showLoading("suggested_"+type,500,'100%');	
	$.ajax({
		type: "POST",
		data: data,
		url: url,
		global: false,
		success: function(result)
		{
			$("#suggested_"+type).html(result);
	        hideLoading("suggested_"+type);	
	        setHeight();		
		},
		error: function(){
		  hideLoading("suggested_"+type);
		  setHeight();
		}
	}); 
}