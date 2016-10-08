@extends('layouts.app')

@section('content')
@include ('search._menu')

<div id="content" class="col-sm-10">
<div class="row">
                    
                    	<div class="col-lg-9">
						
						<script type="text/javascript">
						var searchstr = '{{$search}}';
						var type = '';
						var page = 1;
						var search = '{{$search}}';
						</script>
						<script src="{{ asset('/js/search.js') }}"></script>

						<div class="searchResultsByType">

						</div>

						<div style="width: 770px; position: relative;" id="searchresults_id" class="search_results_box">

							<div class="searchfilters" id="userfilters"> </div>
							<div class="searchfilters" id="facilityfilters"> </div>
							<div class="searchfilters" id="teamfilters"> </div>
							<div class="searchfilters" id="tournamentfilters"> </div>
							
							<div class="searchresults" id="searchresults"></div>
							
						</div>
                        
                        	<!--<div class="teams_search_display">	
                                <div class="search_thumbnail right-caption">
                                      <div class="search_image">
                                                     <img width="90" height="90" src="http://localhost/sportsjun/public/uploads/user_profile/A04AXZOM6f6pTMh32NPT.png" class="img-circle">
                                       </div>
                                      <div class="search_caption">
                                        <h3>Player Name</h3>
										<span class="search_location">Sunrisers Hyderabad</span>
                                        <div class="list_display">
                                                 <p>
                                                   <label>By</label><span>Dipika Guptha</span>
                                                   <label>Sports</label><span>Cricket</span>
                                                   <label>Members</label><span>1</span>
                                                   <label>Last Activity</label><span>About 2 minutes ago</span>
                                                 </p>
                                                 <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500</p>
                                       </div>
                                      </div>
                                </div>
                            </div>
                            
                            <div class="teams_search_display bg_white">	
                                <div class="search_thumbnail right-caption">
                                      <div class="search_image">
                                                     <img width="90" height="90" src="http://localhost/sportsjun/public/uploads/user_profile/A04AXZOM6f6pTMh32NPT.png" class="img-circle">
                                       </div>
                                      <div class="search_caption">
                                        <h3>Player Name</h3>
										<span class="search_location">Sunrisers Hyderabad</span>
                                        <div class="list_display">
                                                 <p>
                                                   <label>By</label><span>Dipika Guptha</span>
                                                   <label>Sports</label><span>Cricket</span>
                                                   <label>Members</label><span>1</span>
                                                   <label>Last Activity</label><span>About 2 minutes ago</span>
                                                 </p>
                                                 <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500</p>
                                       </div>
                                      </div>
                                </div>
                            </div>
                            
                            <div class="teams_search_display">	
                                <div class="search_thumbnail right-caption">
                                      <div class="search_image">
                                                     <img width="90" height="90" src="http://localhost/sportsjun/public/uploads/user_profile/A04AXZOM6f6pTMh32NPT.png" class="img-circle">
                                       </div>
                                      <div class="search_caption">
                                        <h3>Player Name</h3>
										<span class="search_location">Sunrisers Hyderabad</span>
                                        <div class="list_display">
                                                 <p>
                                                   <label>By</label><span>Dipika Guptha</span>
                                                   <label>Sports</label><span>Cricket</span>
                                                   <label>Members</label><span>1</span>
                                                   <label>Last Activity</label><span>About 2 minutes ago</span>
                                                 </p>
                                                 <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500</p>
                                       </div>
                                      </div>
                                </div>
                            </div>-->
                                
                        
                        </div>
                        
                        <div class="col-lg-3 search_filters_div">
                        
                        	<form action=""> 
							 
							 
							 <div class="form-group">
								<label>State</label>
								
							   </div>

							<div class="form-group">
								<label>City</label>
								
							</div>



										<input type="button" value="Go"  onclick="marketplace(this);"  id="go" class="button btn-primary">
														
												 </button>
									 </form > 
                        
                        </div>
                    
                    </div>
              
</div>

<!--<div class="col_middle bg_white_new">
	<div class="col-lg-9 leftsidebar">
	<div class="container-fluid">
		<div class="row">
<script type="text/javascript">
var searchstr = '{{$search}}';
var type = '';
var page = 1;
var search = '{{$search}}';
</script>
<script src="{{ asset('/js/search.js') }}"></script>

<div class="searchResultsByType">

</div>

<div style="width: 770px; position: relative;" id="searchresults_id" class="search_results_box">

	<div class="searchfilters" id="userfilters"> </div>
	<div class="searchfilters" id="facilityfilters"> </div>
	<div class="searchfilters" id="teamfilters"> </div>
	<div class="searchfilters" id="tournamentfilters"> </div>
	
    <div class="searchresults" id="searchresults"></div>
	
</div>
</div>
</div>
</div>
</div>-->
@endsection
<!--<link href="{{ asset('/css/marketplace.css') }}" rel="stylesheet">
-->