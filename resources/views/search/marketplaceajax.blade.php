@if($view_more_flag == 0)
			<div class="search_header_msg">Search results for items @if($search_city) in {{$search_city}}  @endif @if($search_by_name)  with name "{{$search_by_name}}" @endif </div>
		@endif

@if(count($result) > 0)
	<?php $i = 0;?>
	@foreach($result as $lis)
	<?php if($i%2 == 0){ $alt_class = '';}else{ $alt_class = 'bg_white';}?>
<div class="teams_search_display <?php echo $alt_class;?>">	
		<div class="search_thumbnail right-caption">
			  <div class="search_image">
				<!-- <img width="90" height="90" src="http://localhost/sportsjun/public/uploads/user_profile/A04AXZOM6f6pTMh32NPT.png" class="img-circle">-->
				  {!! Helper::Images( $lis['logo'] ,'tournaments',array('height'=>90,'width'=>90,'class'=>'img-circle') )!!}
			   </div>
			  <div class="search_caption">
				<h3><a href="{{ url('/tournaments/groups').'/'.$lis['id'] }}">{{ $lis['name'] }}</a></h3>
				<span class="search_location">{{ $lis['location'] }}</span>
				<div class="list_display">
						 <p>
						   <label>Sports</label>
						   <span> @if(isset($sports_array[$lis['sports_id']]))
						{{$sports_array[$lis['sports_id']]}} 
						@endif
						</span>
						   
						  @if($lis['enrollment_fee']) 
						  <label>Enrollment fee</label><span>{{$lis['enrollment_fee']}}</span>
					      @endif

						 <?php /* @if($lis['prize_money']) 
						  <label>Prize</label><span>{{$lis['prize_money']}}</span>
					      @endif */ ?>
					  
						 </p>
						 @if($lis['description'])
						 <p>{{$lis['description']}}</p>
						@endif
			   </div>
			  </div>
		</div>
	</div>
	<?php $i++;?>
	@endforeach
	@else
	No Records
@endif