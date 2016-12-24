<div class="" style="border:3px inset  #fdd;border-radius:5px;">
<h4 class="team_extra table_head"> {{$player->player_name }} {{$round->distance}}mts round score </h4>
<br>

	<center>

	
	</center>

	<br>


	@if($isValidUser)

	<center>
		@for($i=1; $i<=10;$i++)
			<button class="btn-small btn btn-circle btn-pink" value='{{$i}}' onclick="btn_pink_click(this)"> {{$i}} </button> 
		@endfor
	</center>

	@endif


</div>