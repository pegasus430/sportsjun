@if(count($userResult) > 0)
	<?php $i = 0;?>
	@foreach($userResult as $lis)
	<?php if($i%2 == 0){ $alt_class = '';}else{ $alt_class = 'bg_white';}?>
<div class="teams_search_display <?php echo $alt_class;?>">	
		<div class="search_thumbnail right-caption">
        	
            	<div class="col-sm-2 text-center">
                	{!! Helper::Images('A04AXZOM6f6pTMh32NPT.png','user_profile',array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down img-responsive') )!!}
					</div>
					<div class="col-sm-10">
                    	<div class="t_tltle">
                        	<div class="pull-left">{{ $lis['name'] }}</a></div>
							<p class="search_location t_by">{{ $lis['address'] }}</p>
                        </div>
                        <ul class="t_tags">
                        	<li>Sports:
                            	<span class="green">Cricket</span>
                            </li>
                        	<li>Members:
                            	<span class="green">1</span>
                            </li>
                        </ul>
                        <p class="lt-grey">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500</p>
					</div>
		</div>
	</div>
	<?php $i++;?>
	@endforeach
	@else
	<div class="sj-alert sj-alert-info">No Records</div>
@endif

