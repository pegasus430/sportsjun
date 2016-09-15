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
                        	<div class="pull-left"><a href="{{ url('/team/members').'/'.$lis['id'] }}">{{ $lis['name'] }}</a></div>
							<p class="search_location t_by">{{ $lis['location'] }}</p>
                        </div>
                        <ul class="t_tags">
                        	<li>Sports:
                            	<span class="green">
                            	@if(isset($sports_array[$lis['sports_id']]))
								{{$sports_array[$lis['sports_id']]}} 
								@endif</span>
                            </li>
                        </ul>
						<div class="sb_join_team" id="{{$lis['id']}}" val="PLAYER_TO_TEAM"><a href="#" class="sj_add_but"><span><i class="fa fa-check"></i>Join</span></a></div>
					</div>
				</div>
			</div>
			<?php $i++;?>
			@endforeach
			@else
			<div class="sj-alert sj-alert-info">No Records</div>
			@endif