var currentSearchAnchor = null;
//console.log(currentSearchAnchor);
$(document).ready(function(){
	setInterval("checkBrowserHash()", 300);
	//var searchtypes = Array('swap','iso','gift','Personsearch','Groupsearch');
	if(document.location.hash == ''){
		var searchtypes = Array('user');//To display swap items as default
		for(var i=0;i<searchtypes.length;i++){
			//var page = '<?php /*echo $currentPage;?>';
			//var search = '<?php echo $searchstr;*/?>';
			changeHash(searchtypes[i],search,page);
		}
	}
	//change href link to ajax call event

	$(".searchresults #tnt_pagination a").on("click", function() {
		type = $(this).parents('.searchresults ').attr('id');
		//getResultsByType($(this).parents('.searchresults ').attr('id'),'','',$(this).attr('href'));
		var getParams = ($(this).attr('href')).split('/?');
		var URLVariables = getParams[1].split('&');
		
		//var URLParameters =  URLVariables[1].split('=');
		
		type = URLVariables[0].split('=')[1];
		search = URLVariables[2].split('=')[1];
		page = URLVariables[1].split('=')[1];
		//document.location.hash = type+'/'+search+'/'+page;
		changeHash(type,search,page);
		//console.log($(this).attr('href'));
		return false;
	});
	//$(".searchresults #tnt_pagination a").livequery(function(){
	//	$(this).attr("onclick","");
	//});
});

function checkBrowserHash()
{
	//Check if it has changes
	if(currentSearchAnchor != document.location.hash)
	{
		currentSearchAnchor = document.location.hash;
		checkSearchHash();
	}

	if(document.location.hash == '')
		changeHash('user',searchstr,1);
}

function globalajaxcall(){
	
}

function getResultsByType(type,search,page,url){
	if(page == undefined)
		page = 1;
	if(url == undefined){
		url = base_url + "/search/searchResultsByType";
		data = "id="+type+"&page="+page+"&search="+search;
	}else{
		data = '';
	}
  //showLoading('searchresults_id',500,'40%');
	$.ajax({
		type: "POST",
		data: data,
		url: url,
		success: function(result)
		{
			//alert(result);
			$(".searchresults").html('');
			$("[id$='bold_link']").css('font-weight','');
			$("[id$='bold_link']").css('color','');
			$('#'+type+"_bold_link").css('font-weight','bold');
			$('#'+type+"_bold_link").css('color','#1284B8');
			$(".searchresults").html(result);
			$(".paginationtwo").css("display","none");
			var maxHeight = Math.max.apply(null, $("div.grpNameDiv").map(function ()
					{
					    return $(this).outerHeight(true);
					}).get());
			$(".grpNameDiv").css('height',maxHeight);			
			var maxHeight = Math.max.apply(null, $("div.typename").map(function ()
					{
					    return $(this).outerHeight();
					}).get());
			$(".typename").css('height',maxHeight);				
			var maxHeight = Math.max.apply(null, $("div.searchResultsByType").map(function ()
					{
					    return $(this).outerHeight();
					}).get());
			$(".searchResultsByType").css('height',maxHeight);					
	    //hideLoading('searchresults_id');
  	},
    error: function(){
      //hideLoading('searchresults_id');
    }
	}); 
}

function checkSearchHash(){
	var hash = document.location.hash;
	hash = hash.substring(1,hash.length);

	var tmpHash = hash.split("/");
	type = tmpHash[0];
	search = tmpHash[1];
	page = tmpHash[2];
	getResultsByType(type,search,page);
}
function searchtypecall(type,search,page){
	//$(".searchresults").html('');
	if(page == undefined)
		page = 1;
	changeHash(type,search,page);
}

function changeHash(type,search,page){
	document.location.hash = type+'/'+search+'/'+page;
}
