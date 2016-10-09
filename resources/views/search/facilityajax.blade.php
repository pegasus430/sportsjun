                       
@if(count($result) > 0)
	<?php $i = 0;?>
	@foreach($result as $lis)
	<?php if($i%2 == 0){ $alt_class = '';}else{ $alt_class = 'bg_white';}?>
<div class="teams_search_display <?php echo $alt_class;?>">	
		<div class="search_thumbnail right-caption">
			  <div class="search_image">
				<!-- <img width="90" height="90" src="http://localhost/sportsjun/public/uploads/user_profile/A04AXZOM6f6pTMh32NPT.png" class="img-circle">-->
					  {!! Helper::Images('A04AXZOM6f6pTMh32NPT.png','user_profile',array('height'=>90,'width'=>90,'class'=>'img-circle') )!!}
			   </div>
			  <div class="search_caption">
				<h3>{{ $lis['name'] }}</h3>
				<span class="search_location">{{ $lis['address'] }}</span>
				<div class="list_display">
						 <p>
						   <label>By</label><span>Dipika Guptha</span>
						   <label>Sports</label><span>Cricket</span>
						   <label>Members</label><span>1</span>
						   <!--<label>Last Activity</label><span>About 2 minutes ago</span>-->
						 </p>
						 <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500</p>
			   </div>
			  </div>
		</div>
	</div>
	<?php $i++;?>
	@endforeach
	@else
	No Records
@endif
	
