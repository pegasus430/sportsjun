			@if($view_more_flag == 0)
				<div class="search_header_msg">Search results for {{$sports_array[$sport_by]}} tournaments @if($search_city)in {{$search_city}}  @endif @if($search_by_name)  with name "{{$search_by_name}}" @endif </div>
			@endif
				
				
					@if(count($result) > 0)
			<?php $i = 0;?>
			@foreach($result as $lis)
			<?php 
                $lis = $lis->toArray();
                if ($i%2 == 0) { $alt_class = ''; }
                else { $alt_class = 'bg_white'; }
            ?>
			<div class="teams_search_display row main_tour <?php echo $alt_class;?>">	       	            
				<div class="search_thumbnail right-caption">
                
                	<div class="col-sm-2 text-center">
	                    {!! Helper::Images( $lis['logo']['url'] ,'tournaments',array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down img-responsive') )!!}
					</div>
					<div class="col-sm-10">
                    	<div class="t_tltle">
							<div class="pull-left"><a href="{{ url('/gettournamentdetails').'/'.$lis['id'] }}" id="{{'touname_'.$lis['id']}}">{{ $lis['name'] }}</a></div>
							<p class="search_location t_by">{{ $lis['location'] }}</p>
                        </div>
                        <ul class="t_tags">
                        	<li>Sport:
                            	<span class="green">
                            	@if(isset($sports_array[$lis['sports_id']]))
									{{$sports_array[$lis['sports_id']]}} 
								@endif</span>
                            </li>
                            @if($lis['enrollment_fee']) 
                            <li>
								Enrollment fee <span class="green">{{$lis['enrollment_fee']}}</span>
                            </li>
                            @endif
                        </ul>
                        @if($lis['description'])
                        <p class="lt-grey">{{$lis['description']}}</p>
                        @endif
                        <div class="sj_actions_new">
    						<?php if(!in_array($lis['id'],$exist_array) && (!empty($lis['end_date'] && $lis['end_date']!='0000-00-00')?strtotime($lis['end_date']) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT'))):strtotime($lis['start_date']) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT'))))) { ?>
							<div class="sb_join_tournament_main" id="{{$lis['id']}}" spid="{{$lis['sports_id']}}" val="{{!empty($lis['schedule_type'])?(($lis['schedule_type']=='individual')?'PLAYER_TO_TOURNAMENT':'TEAM_TO_TOURNAMENT'):''}}"><a href="#" class="sj_add_but"><span><i class="fa fa-check"></i>Join Tournament</span></a></div>
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
<script type="text/javascript">
            $(document).ready(function() {
	global_record_count = {{$totalcount}}			
    var offset = {{$offset}};
            $("#offset").val(offset);
            if (offset >= global_record_count)
    {
    $("#viewmorediv").remove();
    }

    });
</script>  