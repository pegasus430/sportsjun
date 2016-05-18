
{!! Form::open(array('url' => 'match/insertCricketScoreCard', 'method' => 'POST')) !!}
<!--********* TEAM A Start**************!-->
<h3 id='second_team_a_batting' class="team_bat table_head">{{ $team_a_name }} Innings</h3>
<div class="table-responsive">
	<table class="table table-striped">
	<thead class="thead">
		<tr>
			<th>Players</th>
			<th></th>
			<th></th>
			<th></th>
			<th>R</th>
			<th>B</th>
			<th>4s</th>
			<th>6s</th>
			<th>SR</th>
			
		</tr>
		</thead>
		<tbody id="two_player_tr_a" >
			@if(!empty($team_a_secnd_ing_array) && count($team_a_secnd_ing_array)>0)
			@foreach($team_a_secnd_ing_array as $a_batng_second_ing)
			<tr>
				
				<td>
					<a href="{{ url('/showsportprofile',[$a_batng_second_ing['user_id']]) }}">{{(!empty($player_name_array[$a_batng_second_ing['user_id']]))?$player_name_array[$a_batng_second_ing['user_id']]:''}}
					</a>
				</td>
				
				<td colspan="3">
                                        {!! (!empty($enum_shortcuts[$a_batng_second_ing['out_as']]))?'<strong>' . strtoupper($enum_shortcuts[$a_batng_second_ing['out_as']]) . '</strong>':'' !!}
                                        {{(!empty($player_name_array[$a_batng_second_ing['fielder_id']]))?$player_name_array[$a_batng_second_ing['fielder_id']]:''}}
                                        {!! (!empty($player_name_array[$a_batng_second_ing['bowled_id']]))?(($enum_shortcuts[$a_batng_second_ing['out_as']] != 'b') ? '<strong>B</strong> ':'').$player_name_array[$a_batng_second_ing['bowled_id']]:'' !!}
				</td>
			
				
				<td>{{(!empty($a_batng_second_ing['totalruns']))?$a_batng_second_ing['totalruns']:''}}</td>
				
				<td>{{(!empty($a_batng_second_ing['balls_played']))?$a_batng_second_ing['balls_played']:''}}</td>
				
				<td>{{(!empty($a_batng_second_ing['fours']))?$a_batng_second_ing['fours']:''}}</td>
				
				<td>{{(!empty($a_batng_second_ing['sixes']))?$a_batng_second_ing['sixes']:''}}</td>
				
				<td>{{(!empty($a_batng_second_ing['strikerate']))?number_format($a_batng_second_ing['strikerate'],2):''}}</td>
				
			</tr>
			@endforeach
			<td colspan="9">Extras(W {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['wide']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['wide']:''}} ,NB {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['noball']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['noball']:''}}, Lb {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['legbye']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['legbye']:''}}, b {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['bye']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['bye']:''}} others {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['others']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['others']:''}} ) {{((!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['wide']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['wide']:'')+((!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['noball']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['noball']:'')+((!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['legbye']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['legbye']:'')+((!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['bye']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['bye']:'')+((!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['others']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['others']:'')}}</td>
			@else
			<tr>
			<td colspan="11">{{ 'No Records. '}}</td>
			</tr>
			
			@endif
		</tbody>
	</table>
	</div>

	
	<div class="clearfix"></div>
    
	 <h3 id='second_team_b_bowling' class="team_bowl table_head">{{ $team_b_name }} Bowling</h3>
	 <div class="table-responsive">
	<table class="table table-striped">
	<thead class="thead">
		<tr>
			<th>Players</th>
			<th>O</th>
			<th>M</th>
			<th>R</th>
			<th>WK</th>
			<th>Econ</th>
			<th>Wd</th>	
			<th>Nb</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		</thead>
		<tbody id="two_bowler_tr_b" >
		@if(!empty($team_b_scnd_ing_bowling_array) && count($team_b_scnd_ing_bowling_array)>0)
			@foreach($team_b_scnd_ing_bowling_array as $team_b_bowl_scnd_ing)
			<tr>
				<!--<td>{!! Form::select('b_bowler_1',$team_b,null,array('class'=>'gui-input b_bowler','id'=>'b_bowlers_second_ing_1')) !!}</td>-->
				
				<td><a href="{{ url('/showsportprofile',[$team_b_bowl_scnd_ing['user_id']]) }}">
				{{(!empty($player_name_array[$team_b_bowl_scnd_ing['user_id']]))?$player_name_array[$team_b_bowl_scnd_ing['user_id']]:''}}
				</a>
				</td>
				
				<td>{{(!empty($team_b_bowl_scnd_ing['overs_bowled']))?$team_b_bowl_scnd_ing['overs_bowled']:''}}</td>
				<td>{{(!empty($team_b_bowl_scnd_ing['overs_maiden']))?$team_b_bowl_scnd_ing['overs_maiden']:''}}</td>
				<td>{{(!empty($team_b_bowl_scnd_ing['runs_conceded']))?$team_b_bowl_scnd_ing['runs_conceded']:''}}</td>
				<td>{{(!empty($team_b_bowl_scnd_ing['wickets']))?$team_b_bowl_scnd_ing['wickets']:''}}</td>
				<td>{{(!empty($team_b_bowl_scnd_ing['ecomony']))?number_format($team_b_bowl_scnd_ing['ecomony'],2):''}}</td>
				<td>{{(!empty($team_b_bowl_scnd_ing['wides_bowl']))?$team_b_bowl_scnd_ing['wides_bowl']:''}}</td>
					<td>{{(!empty($team_b_bowl_scnd_ing['noballs_bowl']))?$team_b_bowl_scnd_ing['noballs_bowl']:''}}</td>
                <td></td>
                <td></td>
                <td></td>	
			</tr>
			@endforeach
		@else
			<tr>
			<td colspan="11">{{ 'No Records. '}}</td>
			</tr>	
			
		@endif
		</tbody>
	</table>
	</div>

	<!-- *********Fall Of Wickets team a START ************* -->
	  <div class="clearfix"></div>
	  <h3 class="team_fall table_head">Fall Of Wickets</h3>
	   <div class="table-responsive">
	<table  class="table table-striped">
	<thead class="thead">
		<tr> 
			<th>WK</th>
			<th>Players</th>
			<th>R</th>
			<th>O</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>	
		<tbody id="fall_of_wkt_fst_a">
		@if(!empty($team_wise_match_details[$match_data[0]['a_id']]['second']) && count($team_wise_match_details[$match_data[0]['a_id']]['second'])>0 && $a_keycount_scnd_ing>0)
			@foreach($team_wise_match_details[$match_data[0]['a_id']]['second'] as $a_scnd_key => $team_a_wkts_ing)
		@if(is_numeric($a_scnd_key))
			<tr>
				<td>{{(!empty($team_a_wkts_ing['wicket']))?$team_a_wkts_ing['wicket']:''}}</td>
				<!--<td>{!! Form::select('a_wkt_player_1',$team_a,null,array('class'=>'gui-input','id'=>'a_wkt_player_fst_ing_1')) !!}</td>-->
				<td><a href="{{ url('/showsportprofile',[$team_a_wkts_ing['batsman']]) }}">{{(!empty($player_name_array[$team_a_wkts_ing['batsman']]))?$player_name_array[$team_a_wkts_ing['batsman']]:''}}</a>
				</td>
				<td>{{(!empty($team_a_wkts_ing['score']))?$team_a_wkts_ing['score']:''}}</td>
				<td>{{(!empty($team_a_wkts_ing['over']))?$team_a_wkts_ing['over']:''}}</td>
				<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
			@endif
			@endforeach
		@else
		<td colspan="11">{{ 'No Records. '}}</td>	
		@endif	

		</tbody>
	</table> 
	</div>

	
	<!-- *********Fall Of Wickets END************* -->
	
<!--********* TEAM A  End **************!-->

<hr>
<!--********* TEAM B Start **************!-->
	<div class="clearfix"></div>
	<h3 id='second_team_b_batting' class="team_bat table_head">{{ $team_b_name }} Innings</h3>
	<div class="table-responsive">
		<table class="table table-striped">
		 <thead class="thead">
		<tr>
			<th>Players</th>
			<th></th>
			<th></th>
			<th></th>
			<th>R</th>
			<th>B</th>
			<th>4s</th>
			<th>6s</th>
			<th>SR</th>		
			
		</tr>
		</thead>
		<tbody id="two_player_tr_b" >
			@if(!empty($team_b_secnd_ing_array) && count($team_b_secnd_ing_array)>0)
				@foreach($team_b_secnd_ing_array as $team_b_bat_scnd)
			<tr>
				
				<td><a href="{{ url('/showsportprofile',[$team_b_bat_scnd['user_id']]) }}">{{(!empty($player_name_array[$team_b_bat_scnd['user_id']]))?$player_name_array[$team_b_bat_scnd['user_id']]:''}}</a>
				</td>
				
				<td colspan="3">
                                {!! (!empty($enum_shortcuts[$team_b_bat_scnd['out_as']]))?'<strong>' . strtoupper($enum_shortcuts[$team_b_bat_scnd['out_as']]) . '</strong>':'' !!}
                                {{(!empty($player_name_array[$team_b_bat_scnd['fielder_id']]))?$player_name_array[$team_b_bat_scnd['fielder_id']]:''}}
                                {!! (!empty($player_name_array[$team_b_bat_scnd['bowled_id']]))?(($enum_shortcuts[$team_b_bat_scnd['out_as']] != 'b') ? '<strong>B</strong> ':'').$player_name_array[$team_b_bat_scnd['bowled_id']]:'' !!}
				</td>
		
				
				<td>{{(!empty($team_b_bat_scnd['totalruns']))?$team_b_bat_scnd['totalruns']:''}}</td>
				<td>{{(!empty($team_b_bat_scnd['balls_played']))?$team_b_bat_scnd['balls_played']:''}}</td>
				<td>{{(!empty($team_b_bat_scnd['fours']))?$team_b_bat_scnd['fours']:''}}</td>
				<td>{{(!empty($team_b_bat_scnd['sixes']))?$team_b_bat_scnd['sixes']:''}}</td>
				<td>{{(!empty($team_b_bat_scnd['strikerate']))?number_format($team_b_bat_scnd['strikerate'],2):''}}</td>
				
			</tr>
			@endforeach
			<td colspan="9">Extras(W {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['wide']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['wide']:''}} , NB {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['noball']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['noball']:''}}, Lb {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['legbye']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['legbye']:''}}, b {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['bye']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['bye']:''}} others {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['others']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['others']:''}} ) {{((!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['wide']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['wide']:'')+((!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['noball']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['noball']:'')+((!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['legbye']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['legbye']:'')+((!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['bye']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['bye']:'')+((!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['others']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['others']:'')}}</td>
			@else
			<tr>
			<td colspan="11">{{ 'No Records. '}}</td>
					
			</tr>	
			
			@endif

		</tbody>

	</table>
	</div>

	
	 <h3 id='second_team_a_bowling' class="team_bowl table_head">{{ $team_a_name }} Bowling</h3>
	<div class="table-responsive">
	<table class="table table-striped">
    	<thead class="thead">
		<tr>
			<th>Players</th>
			<th>O</th>
			<th>M</th>
			<th>R</th>
			<th>WK</th>
			<th>Econ</th>
			<th>Wd</th>	
			<th>Nb</th>	
			<th></th>	
			<th></th>	
			<th></th>	
			
		</tr>
		</thead>
		<tbody id="two_bowler_tr_a" >
		@if(!empty($team_a_scnd_ing_bowling_array) && count($team_a_scnd_ing_bowling_array)>0)	
			@foreach($team_a_scnd_ing_bowling_array as $team_a_secnd_bat)
			<tr>
				<!--<td>{!! Form::select('a_bowler_1',$team_a,null,array('class'=>'gui-input a_bowler','id'=>'a_bowlers_second_ing_1')) !!}</td>-->
				<td>
				<a href="{{ url('/showsportprofile',[$team_a_secnd_bat['user_id']]) }}">{{(!empty($player_name_array[$team_a_secnd_bat['user_id']]))?$player_name_array[$team_a_secnd_bat['user_id']]:''}}</a>
				</td>
				<td>{{(!empty($team_a_secnd_bat['overs_bowled']))?$team_a_secnd_bat['overs_bowled']:''}}</td>
				<td>{{(!empty($team_a_secnd_bat['overs_maiden']))?$team_a_secnd_bat['overs_maiden']:''}}</td>
				<td>{{(!empty($team_a_secnd_bat['runs_conceded']))?$team_a_secnd_bat['runs_conceded']:''}}</td>
				<td>{{(!empty($team_a_secnd_bat['wickets']))?$team_a_secnd_bat['wickets']:''}}</td>
				<td>{{(!empty($team_a_secnd_bat['ecomony']))?number_format($team_a_secnd_bat['ecomony'],2):''}}</td>
				<td>{{(!empty($team_a_secnd_bat['wides_bowl']))?$team_a_secnd_bat['wides_bowl']:''}}</td>
					<td>{{(!empty($team_a_secnd_bat['noballs_bowl']))?$team_a_secnd_bat['noballs_bowl']:''}}</td>
					<td></td>
					<td></td>
					<td></td>	
					
			</tr>
			@endforeach
		@else
			<tr>
			<td colspan="11">{{ 'No Records. '}}</td>
				
			</tr>
		@endif
		</tbody>

	</table>
	</div>

		<!-- *********Fall Of Wickets ************* -->
		
	<h3 class="team_fall table_head">Fall Of Wickets</h3>
	 <div class="table-responsive">
	<table class="table table-striped">
    	<thead class="thead">
		<tr> 
			<th>WK</th>
			<th>Players</th>
			<th>R</th>
			<th>O</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		</thead>
		<tbody id="fall_of_wkt_second_b">
		@if(!empty($team_wise_match_details[$match_data[0]['b_id']]['second']) && count($team_wise_match_details[$match_data[0]['b_id']]['second'])>0 && $b_keycount_scnd_ing>0)
			@foreach($team_wise_match_details[$match_data[0]['b_id']]['second'] as $b_key => $team_b_fall_ing)
		@if(is_numeric($b_key))
			<tr>
				<td>{{(!empty($team_b_fall_ing['wicket']))?$team_b_fall_ing['wicket']:''}}</td>
				<!--<td>{!! Form::select('b_wkt_player_1',$team_b,null,array('class'=>'gui-input','id'=>'b_wkt_player_second_ing_1')) !!}</td>-->
				<td><a href="{{ url('/showsportprofile',[$team_b_fall_ing['batsman']]) }}">{{(!empty($player_name_array[$team_b_fall_ing['batsman']]))?$player_name_array[$team_b_fall_ing['batsman']]:''}}</a>
				</td>
				<td>{{(!empty($team_b_fall_ing['score']))?$team_b_fall_ing['score']:''}}</td>
				<td>{{(!empty($team_b_fall_ing['over']))?$team_b_fall_ing['over']:''}}</td>
				 <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
			@endif
			@endforeach
		@else
			<tr>
			<td colspan="11">{{ 'No Records. '}}</td>
			</tr>
		@endif

		</tbody>

	</table>
	</div>
	<!-- *********Fall Of Wickets ************* -->
	
<!--********* TEAM B  End **************!-->

{!!Form::close()!!}
