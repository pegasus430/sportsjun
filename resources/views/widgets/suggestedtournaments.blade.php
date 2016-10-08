
	<h4 class="sb_head">Suggested Tournaments</h4>
    <div class="suggestion_box">
	<?php 
		$flag_value = !empty($result['flag_type'])?$result['flag_type']:'';
		unset($result['flag_type']);
	?>
	@if(count($result) > 0)
	<?php $i = 0; ?>
	@foreach($result as $lis)
	<div class="row">
		<div class="sb_hover">
			<div class="sb_details">
				<div class="col-md-4 col-sm-12 col-xs-3 text-center">
					{!! Helper::Images( $lis['logo'] ,'teams',array('height'=>90,'width'=>90,'class'=>'img-circle') )!!}
				</div>
				<div class="col-md-8 col-sm-12 col-xs-9">
					<p class="sb_title"><a href="{{ url('/tournaments/groups').'/'.$lis['id'] }}"><strong>{{ $lis['name'] }}</strong></a></p>
					<ul class="sb_tags">
						<li>
							<small><span class="grey">Sport: </span>
							   <span>
							   <?php $sport_names = ''; ?>

							   <?php
								if(isset($sports_array[$lis['sports_id']])){
									 $sport_names .= ", ".$sports_array[$lis['sports_id']];
								}
							   ?>
							   <?php $sport_names = trim($sport_names,",");?>
							   {{$sport_names}}
							   </span>
							</small>
						</li>
					</ul>
					<?php if(!empty($lis['end_date'] && $lis['end_date']!='0000-00-00')?strtotime($lis['end_date']) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT'))):strtotime($lis['start_date']) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT')))) {?>
					<div class="sb_join_tournament widget_div" id="{{$lis['id']}}" spid="{{$lis['sports_id']}}" val="{{!empty($lis['schedule_type'])?(($lis['schedule_type']=='individual')?'PLAYER_TO_TOURNAMENT':'TEAM_TO_TOURNAMENT'):''}}"					
					><a href="#" class="sj_add_but"><span><i class="fa fa-check"></i>Join Tournament</span></a></div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php $i++;?>
	@endforeach
	<a href="{{url()}}/search?service=tournament&sport={{ !empty($sport_id)?$sport_id:'' }}&category=&search_city={{ !empty($city)?$city:'' }}&search_city_id={{ !empty($city_id)?$city_id:0 }}&search_by=" class="view_tageline"><span><i class="fa fa-arrow-down"></i><label>view more</label></span></a>
	@else
	
      <div class="sj-alert sj-alert-info">No Tournaments Found.</div>
@endif
</div>
<script type="text/javascript">
$(".sb_join_tournament").click(function(){
	var sport_id = $(this).attr('spid');
	var val = $(this).attr('val');
	var id = $(this).attr('id');
	var title = $(this).parent().find(".sb_title").html();
	var jsflag = 'Tournaments';
	if(val === 'PLAYER_TO_TOURNAMENT')
	{
		id = [$(this).attr('id')];
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
					                content: 'Request already sent.'
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
	}
	else
	{
		generateteamsdiv(sport_id,val,id,title,jsflag);	
	}
});
</script>
