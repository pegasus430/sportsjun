@extends('layouts.app')
@section('content')
@include ('search._tournamentmenu')
<?php //echo '<pre>';print_r($result->toArray()); exit;?>
<div id="content" class="col-sm-10">
		<div class="col-sm-9 viewmoreclass tournament_profile" id="searchresultsDiv">
			
			<div class="search_header_msg">Search results for {{$sports_array[$sport_by]}} tournaments @if($search_city)in {{$search_city}}  @endif @if($search_by_name)  with name "{{$search_by_name}}" @endif </div>
			
			@if(count($result) > 0)
			<?php $i = 0;?>
			@foreach($result as $lis)
			<?php 
			    $lis_object=$lis;
                $lis = $lis->toArray();
                if ($i%2 == 0) { $alt_class = ''; }
                else { $alt_class = 'bg_white'; }
            ?>
			<div class="teams_search_display row main_tour <?php echo $alt_class;?>">	       	            
				<div class="search_thumbnail right-caption">
                
                	<div class="col-md-2 col-sm-3 col-xs-12 text-center">
	                    {!! Helper::Images( $lis['logo']['url'] ,'tournaments',array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down img-responsive') )!!}
					</div>
					<div class="col-md-10 col-sm-9 col-xs-12">
                    	<div class="t_tltle">
                        	<div class="pull-left"><a href="{{ url('/gettournamentdetails').'/'.$lis['id'] }}" id="{{'touname_'.$lis['id']}}">{{ $lis['name'] }}</a></div>
                            <p class="search_location t_by">{{ $lis['location'] }}</p>
                        </div>
                        <div class="clearfix"></div>
                        @if(isset($lis['winnerName']))
                        <div class="m-y-10 red search-results-tournament-winner">Winner: <span>{{ $lis['winnerName'] }}</span></div>
                        @endif
                        <div class="m-y-10 {{ $lis['statusColor'] }}">{{ Helper::displayDate($lis['start_date'],1) }} to {{ Helper::displayDate($lis['end_date'],1) }}</div>
                        <ul class="t_tags">
                            <li>
                                Status: <span class="green">{{ $lis['status'] }}</span>
                            </li>
                        	@if(isset($sports_array[$lis['sports_id']]))
                            <li>
                                Sport: <span class="green">{{$sports_array[$lis['sports_id']]}}</span>
                            </li>
                            @endif
                            @if($lis['enrollment_fee']) 
                            <li>
								Enrollment Fee: <span class="green">{{$lis['enrollment_fee']}}</span>
                            </li>
                            @endif
                        </ul>
                        @if($lis['description'])
                        <p class="lt-grey">{{$lis['description']}}</p>
                        @endif


                        <div class="sj_actions_new">
	                        <?php if(!in_array($lis['id'],$exist_array) && (!empty($lis['end_date'] && $lis['end_date']!='0000-00-00')?strtotime($lis['end_date']) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT'))):strtotime($lis['start_date']) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT'))))) {?>
							<div class="sb_join_tournament_main" id="{{$lis['id']}}" spid="{{$lis['sports_id']}}" val="{{!empty($lis['schedule_type'])?(($lis['schedule_type']=='individual')?'PLAYER_TO_TOURNAMENT':'TEAM_TO_TOURNAMENT'):''}}"><a href="#" class="sj_add_but"><span><i class="fa fa-check"></i>
							     
                                 @if($lis['enrollment_type'] == 'online' && $lis_object->bankaccount !== null && $lis_object->bankaccount->varified == 1)Event Registration (Online Payment)
							@else
                             Event Registration (Offline Payment)
                            @endif
							</span></a></div>

							
							
							<?php }?>
							<div class="follow_unfollow_tournament" id="follow_unfollow_tournament_{{$lis['id']}}" uid="{{$lis['id']}}" val="TOURNAMENT" flag="{{ in_array($lis['id'],$follow_array)?0:1 }}"><a href="#" id="follow_unfollow_tournament_a_{{$lis['id']}}" class="{{ in_array($lis['id'],$follow_array)?'sj_unfollow':'sj_follow' }}"><span id="follow_unfollow_tournament_span_{{$lis['id']}}"><i class="{{ in_array($lis['id'],$follow_array)?'fa fa-remove':'fa fa-check' }}"></i>{{ in_array($lis['id'],$follow_array)?'Unfollow':'Follow' }}</span></a></div> 
						</div>				
					</div>
				</div>
			</div>
			<?php $i++;?>
			@endforeach
			@else
			<div class="sj-alert sj-alert-info">No Records</div>
			@endif
            
            	@if($totalcount>count($result)) 
	<div  id="viewmorediv" style="text-align: center" class="col-md-12">
<!--	    <div id="viewmorebutton" class="btn btn-green">{{ trans('message.view_more') }}</div>
-->        <a  id="viewmorebutton" class="view_tageline_mkt"><span class="market_place"><i class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span></a>
	</div>
	@endif
            
		</div>
		<div class="col-sm-3 search_filters_div col-xs-12" id="sidebar-right">
			<div id="suggested_players"> </div>
			<div id="suggested_teams"> </div>
		</div>
</div>
<input type="hidden" name="limit" id="limit" value="{{$limit}}"/>
<input type="hidden" name="offset" id="offset" value="{{$offset}}"/>
<input type="hidden" name="sport_by" id="sport_by" value="{{$sport_by}}"/>
<input type="hidden" name="city_by" id="city_by" value="{{$city_by}}"/>
<input type="hidden" name="category_by" id="category_by" value="{{$category_by}}"/>
<input type="hidden" name="search_by_name" id="search_by_name" value="{{$search_by_name}}"/>
<input type="hidden" name="search_city" id="search_city" value="{{$search_city}}"/>

@include ('widgets.teamspopup')
<script type="text/javascript">
$(window).ready(function(){
	suggestedWidget("players", '', $("#sport").val(), 'player_to_tournament',$("#search_city_id").val());
	suggestedWidget("teams", '', $("#sport").val(), 'team_to_tournament',$("#search_city_id").val());
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $("#offset").val({{$offset}});
            if ($("#viewmorediv").length) {
    $('#viewmorebutton').on("click", function(e) {
		var page='tournament';
		var sport_by=$("#sport_by").val();
		var city_by=$("#city_by").val();
		var category_by=$("#category_by").val();
		var search_by_name=$("#search_by_name").val();
		var search_city = $("#search_city").val();
		var urls =	'{{URL('search/viewMore')}}';
    var params = { limit:{{$limit}}, offset:$("#offset").val(), page:page,sport:sport_by,search_city_id:city_by,category:category_by,search_by:search_by_name,search_city:search_city };
            viewMore(params,urls);
    });
            global_record_count = {{$totalcount}}
    }

});
	$(document.body).on('click', '.sb_join_tournament_main' ,function(){ 		
		var sport_id = $(this).attr('spid');
		var val = $(this).attr('val');
		var id = $(this).attr('id');
		var title = $("#touname_"+id).html();
		var jsflag = 'Tournaments';
		if(val === 'PLAYER_TO_TOURNAMENT')
		{
			id = [$(this).attr('id')];
			var user_id = '{{ Auth::user()->id }}';
			$.confirm({
				title: 'Confirm',
				content: "Do you want to join "+title+"?",
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
								$("#hid_flag").val('');
								$("#hid_val").val('');
		        			}
		        			else if(response.status == 'exist')
		        			{
		        				$.alert({
					                title: "Alert!",
					                content: 'Request already sent.'
					            });
								$("#hid_flag").val('');
								$("#hid_val").val('');				
		        			}
		        			else
		        			{
		        				$.alert({
					                title: "Alert!",
					                content: 'Failed to send the request.'
					            });
								$("#hid_flag").val('');
								$("#hid_val").val('');	        				
		        			}
		        		}
		    		    else
		    			{
	        				$.alert({
				                title: "Alert!",
				                content: 'Failed to send the request.'
				            });
							$("#hid_flag").val('');
							$("#hid_val").val('');
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
@endsection
