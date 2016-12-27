<div class="" style="border:3px inset  #fdd;border-radius:5px;">
<h4 class="team_extra table_head"> {{$player->player_name }} {{$round->distance}}mts round score </h4>
<br>

	<center>
			@for($i=1; $i<=$round->number_of_arrows;$i++)
		<?php $arrow_stats = ScoreCard::get_arrow_stats($match_obj->id,$player->user_id,$round->id,$round->round_number);


		?>

		<button class="btn-large btn btn-arrow" arrow_number='{{$i}}' type='button' onclick='btn_arrow_click(this)' id='arrow_{{$i}}'> {{$arrow_stats->{'arrow_'.$i}? $arrow_stats->{'arrow_'.$i} : "Arrow $i"}}</button>
	@endfor
	
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