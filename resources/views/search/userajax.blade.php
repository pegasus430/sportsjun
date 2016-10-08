	@if($view_more_flag == 0)
		<div class="search_header_msg">Search results for {{$sports_array[$sport_by]}} players @if($search_city)in {{$search_city}}  @endif @if($search_by_name)  with name "{{$search_by_name}}" @endif </div>
	@endif
	@if(count($result) > 0)
			<?php $i = 0;?>
			@foreach($result as $lis)
			<?php if($i%2 == 0){ $alt_class = '';}else{ $alt_class = 'bg_white';}?>
			<div class="teams_search_display row main_tour <?php echo $alt_class;?>">	
				<div class="search_thumbnail right-caption">
                	
                    <div class="col-sm-2 text-center">
                    	{!! Helper::Images( $lis['logo'] ,config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down img-responsive') )!!}
					</div>
					<div class="col-sm-10">
                    	<div class="t_tltle">
                        	<div class="pull-left"><a href="{{ url('/editsportprofile').'/'.$lis['user_id'] }}" id="{{'uname_'.$lis['user_id']}}"> {{ $lis['name'] }}</a></div>
							<p class="search_location t_by">{{ $lis['location'] }}</p>
                        </div>
                        <ul class="t_tags">
                        	<li>Sports:
                            	<?php $sport_ids = explode(",", trim($lis['following_sports'],","));
							?>													   
                                <span class="green">
                                    <?php $sport_names = ''; ?>
                                    @foreach($sport_ids as $key=>$val)
                                    <?php 
                                    if(isset($sports_array[$val]))							
                                        $sport_names .= ", ".$sports_array[$val];					   
                                    ?>
                                    @endforeach													   
                                    <?php $sport_names = trim($sport_names,",");?>
                                    {{$sport_names}}
                                </span>
                            </li>
                        </ul>
                        <div class="sj_actions_new">
                            <?php if (strpos($lis['allowed_sports'], ','.$sport_by.',') !== false) {?>
                            <div class="sb_join_p_main" id="{{$lis['user_id']}}" val="TEAM_TO_PLAYER"><a href="#" class="sj_add_but"><span><i class="fa fa-plus"></i>Add To Team</span></a></div>
                            <?php } ?>
                            <div class="follow_unfollow_player" id="follow_unfollow_player_{{$lis['user_id']}}" uid="{{$lis['user_id']}}" val="PLAYER" flag="{{ in_array($lis['user_id'],$follow_array)?0:1 }}"><a href="#" id="follow_unfollow_player_a_{{$lis['user_id']}}" class="{{ in_array($lis['user_id'],$follow_array)?'sj_unfollow':'sj_follow' }}"><span id="follow_unfollow_player_span_{{$lis['user_id']}}"><i class="{{ in_array($lis['user_id'],$follow_array)?'fa fa-remove':'fa fa-check' }}"></i>{{ in_array($lis['user_id'],$follow_array)?'Unfollow':'Follow' }}</span></a></div>
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