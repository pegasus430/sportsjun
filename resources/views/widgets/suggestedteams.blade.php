
	<h4 class="sb_head">Suggested Teams</h4>
    <div class="suggestion_box">
	<?php //echo var_dump($result); die();?>
	@if(count($result) > 0)
	<?php $i = 0;?>
	@foreach($result as $lis)
	<div class="row">
		<div class="sb_hover">
			<div class="sb_details">
				<div class="col-md-4 col-sm-12 col-xs-3 text-center">
					{!! Helper::Images( $lis['logo'] ,'teams',array('height'=>90,'width'=>90,'class'=>'img-circle') )!!}
				</div>
				<div class="col-md-8 col-sm-12 col-xs-9">
					<p class="sb_title"><a href="{{ url('/team/members').'/'.$lis['id'] }}"><strong>{{ $lis['name'] }}</strong></a></p>
					<ul class="sb_tags">
						<li>
							<small><span class="grey">Sport: </span>
								<?php $sport_ids = explode(",", trim($lis['sports_id'],","));
								   ?>													   
								   <span>
								   <?php $sport_names = ''; ?>
								   @foreach($sport_ids as $key=>$val)
								   <?php
									if(isset($sports_array[$val])){
										 $sport_names .= ", ".$sports_array[$val];					   
									}						  
								   ?>
								   @endforeach													   
								   <?php $sport_names = trim($sport_names,",");?>
								   {{$sport_names}}
								   </span>
							</small>
						</li>
						<!--li>
							<small><span class="grey">Teams</span>
								<span class="black">10</span>
							</small>
						</li-->
					</ul>
					<div class="sb_join_team widget_div" id="{{$lis['id']}}" val="PLAYER_TO_TEAM"><a href="#" class="sj_add_but"><span><i class="fa fa-check"></i>Join Team</span></a></div>
				</div>
			</div>
		</div>
	</div> 
	<?php $i++;?>
	@endforeach
	<a href="{{url()}}/search?service=team&sport={{ !empty($sport_id)?$sport_id:'' }}&category=&search_city={{ !empty($city)?$city:'' }}&search_city_id={{ !empty($city_id)?$city_id:0 }}&search_by=" class="view_tageline"><span><i class="fa fa-arrow-down"></i> <label>View More</label></span></a>
	@else
      <div class="sj-alert sj-alert-info">No Teams Found.</div>
@endif	
</div>
<script type="text/javascript">
    $(".sb_join_team").click(function(){
    	var id = [$(this).attr('id')];
    	var val = $(this).attr('val');
    	var user_id = '{{ Auth::user()->id }}';
		$.confirm({
			title: 'Confirm',
			content: "Do you want to join "+$(this).parent().find(".sb_title").html()+"?",
			confirm: function() {
		        $.post(base_url+'/team/saverequest',{flag:val,player_tournament_id:user_id,team_ids:id},function(response,status){
		        		if(status == 'success')
		        		{
		        			if(response.status == 'success')
		        			{
		        				 $.alert({
					                title: "Alert!",
					                content: 'Request sent successfully.'
					            });
								// $("#div_success").text("Request sent successfully.");
								// $("#div_success").show();
								$("#hid_flag").val('');
								$("#hid_val").val('');
		    					//window.setTimeout(function(){location.reload()},1000)
		        			}
		        			else if(response.status == 'exist')
		        			{
		        				$.alert({
					                title: "Alert!",
					                content: 'You have already sent a request.'
					            });
								// $("#div_failure").text("Failed to send the request.");
								// $("#div_failure").show();
								$("#hid_flag").val('');
								$("#hid_val").val('');		
		    					//window.setTimeout(function(){location.reload()},1000)						
		        			}
		        			else
		        			{
		        				$.alert({
					                title: "Alert!",
					                content: 'Failed to send the request.'
					            });
								// $("#div_failure").text("Failed to send the request.");
								// $("#div_failure").show();
								$("#hid_flag").val('');
								$("#hid_val").val('');		
		    					//window.setTimeout(function(){location.reload()},1000)			        				
		        			}
		        		}
		    		    else
		    			{
	        				$.alert({
				                title: "Alert!",
				                content: 'Failed to send the request.'
				            });		    				
							// $("#div_failure").text("Failed to send the request.");
							// $("#div_failure").show();
							$("#hid_flag").val('');
							$("#hid_val").val('');					
							//window.setTimeout(function(){location.reload()},1000)
		    			}
		        })			    
			},
			cancel: function() {
			    // nothing to do
			}
		});   	
    });
</script> 
