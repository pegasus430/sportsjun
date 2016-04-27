<div class="suggestion_box">
	<h4 class="sb_head">Suggested Players</h4>
	@if(count($result) > 0)
	<?php $i = 0; ?>
	@foreach($result as $lis)
	<div class="row">
		<div class="sb_hover">
			<div class="sb_details">
				<div class="col-md-4 col-sm-12 col-xs-3 text-center">
					{!! Helper::Images( $lis['logo'] ,config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>90,'width'=>90,'class'=>'img-circle') )!!}
				</div>
				<div class="col-md-8 col-sm-12 col-xs-9">
					<p class="sb_title"><a href="{{ url('/editsportprofile').'/'.(!empty($lis['user_id'])?$lis['user_id']:0) }}"><strong>{{ $lis['name']}}</strong></a></p>
					<ul class="sb_tags">
						<li>
							<small><span class="grey">Sports: </span>
                                <?php $sport_ids = explode(",", trim($lis['following_sports'],",")); ?>
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
							<small><span class="grey">Teams</span></small>
						</li-->
					</ul>
					<div class="sb_join_p widget_div" id="{{$lis['user_id']}}" val="TEAM_TO_PLAYER"><a href="#" class="sj_add_but"><span><i class="fa fa-plus"></i>Add To Team</span></a></div>
				</div>
			</div>
		</div>
	</div>
	<?php $i++;?>
	@endforeach
	<a href="{{url()}}/search?service=user&sport={{ !empty($sport_id)?$sport_id:'' }}&category=&search_city={{ !empty($city)?$city:'' }}&search_city_id={{ !empty($city_id)?$city_id:0 }}&search_by=" class="view_tageline"><span><i class="fa fa-arrow-down"></i> <label>View More</label></span></a>
	@else
	<div class="sj-alert sj-alert-info">No Players Found.</div>
@endif
</div>
<script type="text/javascript">
    $(".sb_join_p").click(function(){
	 	var sport_id = '{{ !empty($sport_id)?$sport_id:'' }}';
		var val = $(this).attr('val');
		var id = $(this).attr('id');
		var title = $(this).parent().find(".sb_title").html();
		var jsflag = '';
		generateteamsdiv(sport_id,val,id,title,jsflag);
    });
</script>
