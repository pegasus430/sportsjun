		@if($view_more_flag == 0)
			<div class="search_header_msg">Search results for {{$sports_array[$sport_by]}} teams @if($search_city)in {{$search_city}}  @endif @if($search_by_name)  with name "{{$search_by_name}}" @endif </div>
		@endif
		
@if(count($result) > 0)
			<?php $i = 0;?>
			@foreach($result as $lis)
			<?php if($i%2 == 0){ $alt_class = '';}else{ $alt_class = 'bg_white';}?>
			<div class="teams_search_display row main_tour <?php echo $alt_class;?>">	
				<div class="search_thumbnail right-caption">
					<div class="col-sm-2 text-center">
						{!! Helper::Images( $lis['logo'] ,'teams',array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down img-responsive') )!!}
					</div>
					<div class="col-sm-10">
                    	<div class="t_tltle">
                        	<div class="pull-left"><a href="{{ url('/team/members').'/'.$lis['id'] }}" id="{{'tname_'.$lis['id']}}">{{ $lis['name'] }}</a></div>
							<p class="search_location t_by">{{ $lis['location'] }}</p>
                        </div>
                        <ul class="t_tags">
                        	<li>Sport:
                            	<span class="green">
                            	@if(isset($sports_array[$lis['sports_id']]))
								{{$sports_array[$lis['sports_id']]}} 
								@endif</span>
                            </li>
                        </ul>
                        <div class="sj_actions_new">
	                        <?php if(!in_array($lis['id'],$exist_array) && $lis['player_available'] == 1) {?>
							<div class="sb_join_team_main" id="{{$lis['id']}}" val="PLAYER_TO_TEAM"><a href="#" class="sj_add_but"><span><i class="fa fa-check"></i>Join Team</span></a></div>
							<?php } ?>
							<div class="follow_unfollow_team" id="follow_unfollow_team_{{$lis['id']}}" uid="{{$lis['id']}}" val="TEAM" flag="{{ in_array($lis['id'],$follow_array)?0:1 }}"><a href="#" id="follow_unfollow_team_a_{{$lis['id']}}" class="{{ in_array($lis['id'],$follow_array)?'sj_unfollow':'sj_follow' }}"><span id="follow_unfollow_team_span_{{$lis['id']}}"><i class="{{ in_array($lis['id'],$follow_array)?'fa fa-remove':'fa fa-check' }}"></i>{{ in_array($lis['id'],$follow_array)?'Unfollow':'Follow' }}</span></a></div>
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