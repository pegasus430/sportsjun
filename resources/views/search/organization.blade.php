@extends('layouts.app')
@section('content')
@include ('search._teammenu')
<?php //echo '<pre>';print_r($follow_array); exit;?>
<div id="content" class="col-sm-10 " >
		<div class="col-sm-9 viewmoreclass tournament_profile" id="searchresultsDiv">
		<div class="search_header_msg">Search results for organizations @if($search_city)in {{$search_city}}  @endif @if($search_by_name)  with name "{{$search_by_name}}" @endif </div>
		@if(count($result) > 0)
			<?php $i = 0;?>
			@foreach($result as $lis)
			<?php if($i%2 == 0){ $alt_class = '';}else{ $alt_class = 'bg_white';}?>
			<div class="teams_search_display row main_tour <?php echo $alt_class;?>">	
				<div class="search_thumbnail right-caption">
					<div class="col-md-2 col-sm-3 col-xs-12 text-center">
						{!! Helper::Images( $lis['logo'] ,'organization',array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down') )!!}
					</div>
                                        <div class="col-md-10 col-sm-9 col-xs-12">
                                                <div class="t_tltle">
                                                        <div class="pull-left"><a href="{{ url('/getorgteamdetails').'/'.$lis['id'] }}" id="{{'tname_'.$lis['id']}}">{{ $lis['name'] }}</a></div>
                                                                                <p class="search_location t_by">{{ $lis['location'] }}</p>
                                                </div>
                                                   <ul class="t_tags">
                                                @if(isset($lis['sports_id']) && !empty($lis['sports_id']))
                                             
                                                        <li>Sport:
                                                                <span class="green">
                                                                        {{$sports_array[$lis['sports_id']]}} 
                                                                </span>
                                                        </li>

                                                
                                                @endif
                                                <?php 
                                                /*
                                                @if(isset($lis['sports_ids']) && !empty($lis['sports_ids']))
                                                <?php $lis['sports_ids'] = explode(',', $lis['sports_ids']); ?>
                                                <ul class="t_tags">
                                                        <li>Sport:
                                                                <span class="green">
                                                                        @foreach($lis['sports_ids'] as $idx => $sports_id)
                                                                                @if(isset($sports_array[$sports_id]))
                                                                                        {{$sports_array[$sports_id]}}
                                                                                        @if(isset($lis['sports_ids'][$idx+1])),@endif
                                                                                @endif
                                                                        @endforeach
                                                                </span>
                                                        </li>
                                                </ul>
                                                @endif
                                                 * 
                                                 */
                                                ?>
                                                
                                              	

                                           
                                                        <li>Teams:
                                                                <span class="green">
                                                                        {{count($lis['teamplayers'])}}
                                                                </span>
                                                        </li>
                                                </ul>

                                              	<p>
                                                <div class="follow_unfollow_organization" id="follow_unfollow_organization_{{$lis['id']}}" uid="{{$lis['id']}}" val="ORGANIZATION" flag="{{ in_array($lis['id'],$follow_array)?0:1 }}"><a href="#" id="follow_unfollow_organization_a_{{$lis['id']}}" class="{{ in_array($lis['id'],$follow_array)?'sj_unfollow':'sj_follow' }}"><span id="follow_unfollow_organization_span_{{$lis['id']}}"><i class="{{ in_array($lis['id'],$follow_array)?'fa fa-remove':'fa fa-check' }}"></i>{{ in_array($lis['id'],$follow_array)?'Unfollow':'Follow' }}</span></a></div> 
                                                
                                        </div>
                                </div>
			</div>
			<?php $i++;?>
			@endforeach
		@else
			<div class="sj-alert sj-alert-info">No Records</div>
		@endif
            
            @if($totalcount>count($result)) 
<div  id="viewmorediv" style="text-align:center; margin-bottom:10px;" class="col-md-12">
<!--    <div id="viewmorebutton" class="btn btn-green">{{ trans('message.view_more') }}</div>
-->    <a  id="viewmorebutton" class="view_tageline_mkt"><span class="market_place"><i class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span></a>
</div>
@endif
            
		</div>
		
		<div class="col-sm-3 search_filters_div col-xs-12" id="sidebar-right">
			<div id="suggested_players"> </div>
			<div id="suggested_tournaments"></div>
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
	suggestedWidget('players','',$("#sport").val(),'team_to_player',$("#search_city_id").val());
	suggestedWidget('tournaments','',$("#sport").val(),'team_to_tournament',$("#search_city_id").val());
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $("#offset").val({{$offset}});
            if ($("#viewmorediv").length) {
    $('#viewmorebutton').on("click", function(e) {
		var page='team';
		var urls =	'{{URL('search/viewMore')}}';
		var sport_by=$("#sport_by").val();
		var city_by=$("#city_by").val();
		var category_by=$("#category_by").val();
		var search_by_name=$("#search_by_name").val();
		var search_city = $("#search_city").val();
    var params = { limit:{{$limit}}, offset:$("#offset").val(), page:page,sport:sport_by,search_city_id:city_by,category:category_by,search_by:search_by_name,search_city:search_city};
            viewMore(params,urls);
    });
            global_record_count = {{$totalcount}}
    }
});
	$(document.body).on('click', '.sb_join_team_main' ,function(){    	
    	var id = [$(this).attr('id')];
    	var val = $(this).attr('val');
    	var user_id = '{{ Auth::user()->id }}';
		$.confirm({
			title: 'Confirm',
			content: "Do you want to join "+$("#tname_"+id).html()+"?",
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
    });    
</script>
@endsection
