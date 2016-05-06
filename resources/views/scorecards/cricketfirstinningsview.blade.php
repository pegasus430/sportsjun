
{!! Form::open(array('url' => 'match/insertCricketScoreCard', 'method' => 'POST')) !!}

<!--********* TEAM A Start**************!-->
<h3 id='team_a_batting' class="team_bat table_head">{{ $team_a_name }} Innings</h3>
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
		<tbody id="player_tr_a" >	
		@if(!empty($team_a_fst_ing_array) && count($team_a_fst_ing_array)>0)
			
			@foreach($team_a_fst_ing_array as $a_fst_inning)
			<tr >
				<td><a href="{{ url('/showsportprofile',[$a_fst_inning['user_id']]) }}" class="score_link">{{(!empty($player_name_array[$a_fst_inning['user_id']]))?$player_name_array[$a_fst_inning['user_id']]:''}}
				</a></td>
				<td colspan="3">
				{{ (!empty($enum_shortcuts[$a_fst_inning['out_as']]) && $enum_shortcuts[$a_fst_inning['out_as']]!='Select Out As')?$enum_shortcuts[$a_fst_inning['out_as']]:'' }}
				{{(!empty($player_name_array[$a_fst_inning['bowled_id']]))?$player_name_array[$a_fst_inning['bowled_id']]:''}}
					
				{{ (!empty($player_name_array[$a_fst_inning['fielder_id']]))?'b'.$player_name_array[$a_fst_inning['fielder_id']]:'' }}
				</td>
			
				<td>{{(!empty($a_fst_inning['totalruns']))?$a_fst_inning['totalruns']:''}}</td>
				<td>{{ (!empty($a_fst_inning['balls_played']))?$a_fst_inning['balls_played']:'' }}</td>
				<td>{{ (!empty($a_fst_inning['fours']))?$a_fst_inning['fours']:'' }}</td>
				<td>{{ (!empty($a_fst_inning['sixes']))?$a_fst_inning['sixes']:'' }}</td>
				<td>{{ (!empty($a_fst_inning['strikerate']))?number_format($a_fst_inning['strikerate'],2):'' }}</td>
				
			</tr>
			@endforeach
			<td colspan="9">Extras(W {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['wide']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['wide']:''}} ,NB {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['noball']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['noball']:''}}, Lb {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['legbye']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['legbye']:''}}, b {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['bye']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['bye']:''}} others {{(!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['others']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['others']:''}} ) {{((!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['wide']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['wide']:'')+((!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['noball']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['noball']:'')+((!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['legbye']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['legbye']:'')+((!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['bye']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['bye']:'')+((!empty($team_wise_match_details[$match_data[0]['a_id']]['first']['others']))?$team_wise_match_details[$match_data[0]['a_id']]['first']['others']:'')}}</td>
		@else
			<tr>
			<td colspan="11">{{ 'No Records. '}}</td>
			</tr>	
		@endif	
		</tbody>

	</table>
	</div>
	
	
	<div class="clearfix"></div>
	 <h3 id='team_b_bowling' class="team_bowl table_head">{{ $team_b_name }} Bowling</h3>
	 <div class="table-responsive">
	<table class="table table-striped">
	<thead class="thead">
		<tr>
			<th>Players</th>
			<th>O</th>
			<th>M</th>
			<th>R</th>
			<th>Wk</th>
			<th>Econ</th>
			<th>Wd</th>	
			<th>Nb</th>
			<th></th>
			<th></th>
			<th></th>
			
		</tr>
	</thead>	
		<tbody id="bowler_tr_b" >
			@if(!empty($team_b_fst_ing_bowling_array) && count($team_b_fst_ing_bowling_array)>0)
				@foreach($team_b_fst_ing_bowling_array as $team_b_bowl)
			<tr>
				<!--<td>{!! Form::select('b_bowler_1',$team_b,null,array('class'=>'gui-input b_bowler','id'=>'b_bowler_1')) !!}</td>-->
				<td>
				<a href="{{ url('/showsportprofile',[$team_b_bowl['user_id']]) }}" class="score_link">{{ (!empty($player_name_array[$team_b_bowl['user_id']]))?$player_name_array[$team_b_bowl['user_id']]:'' }}</a>
				</td>
				<td>{{ (!empty($team_b_bowl['overs_bowled']))?$team_b_bowl['overs_bowled']:'' }}</td>
				<td>{{ (!empty($team_b_bowl['overs_maiden']))?$team_b_bowl['overs_maiden']:'' }}</td>
				<td>{{ (!empty($team_b_bowl['runs_conceded']))?$team_b_bowl['runs_conceded']:'' }}</td>
				<td>{{ (!empty($team_b_bowl['wickets']))?$team_b_bowl['wickets']:'' }}</td>
				<td>{{ (!empty($team_b_bowl['ecomony']))?number_format($team_b_bowl['ecomony'],2):'' }}</td>
				<td>{{(!empty($team_b_bowl['wides_bowl']))?$team_b_bowl['wides_bowl']:''}}</td>
                <td> {{(!empty($team_b_bowl['noballs_bowl']))?$team_b_bowl['noballs_bowl']:''}}</td>
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
	<table class="table table-striped">
	<thead class="thead">
		<tr> 
			<th>Wk</th>
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
		<tbody id="fall_of_wkt_a">
		@if(!empty($team_wise_match_details[$match_data[0]['a_id']]['first']) && count($team_wise_match_details[$match_data[0]['a_id']]['first'])>0 && $a_keyCount>0)
			@foreach($team_wise_match_details[$match_data[0]['a_id']]['first'] as $a_key => $team_a_wkts)
		@if(is_numeric($a_key))
			<tr>
				<td>{{ (!empty($team_a_wkts['wicket']))?$team_a_wkts['wicket']:'' }}</td>

				<td><a href="{{ url('/showsportprofile',[$team_a_wkts['batsman']]) }}" class="score_link">{{ (!empty($player_name_array[$team_a_wkts['batsman']]))?$player_name_array[$team_a_wkts['batsman']]:'' }}
					</a>
				</td>
				<td>{{ (!empty($team_a_wkts['score']))?$team_a_wkts['score']:'' }}</td>
				<td>{{ (!empty($team_a_wkts['over']))?$team_a_wkts['over']:'' }}</td>
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

	<!-- *********Fall Of Wickets END************* -->
	
<!--********* TEAM A  End **************!-->
	<hr />
	<div class="clearfix"></div>
<!--********* TEAM B Start **************!-->
 <h3 id='team_b_batting' class="team_bat table_head">{{ $team_b_name }} Innings</h3>
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
		<tbody id="player_tr_b" >
			@if(!empty($team_b_fst_ing_array) && count($team_b_fst_ing_array)>0)
				@foreach($team_b_fst_ing_array as $team_b_bat)
			<tr>
				
				<td><a href="{{ url('/showsportprofile',[$team_b_bat['user_id']]) }}" class="score_link">{{(!empty($player_name_array[$team_b_bat['user_id']]))?$player_name_array[$team_b_bat['user_id']]:''}}
				</a>
				</td>
				
				<td colspan="3">
				{{(!empty($enum_shortcuts[$team_b_bat['out_as']]) && $enum_shortcuts[$team_b_bat['out_as']]!='Select Out As')?$enum_shortcuts[$team_b_bat['out_as']]:''}}
				{{(!empty($player_name_array[$team_b_bat['bowled_id']]))?$player_name_array[$team_b_bat['bowled_id']]:''}}
				
				{{(!empty($player_name_array[$team_b_bat['fielder_id']]))?'b'.$player_name_array[$team_b_bat['fielder_id']]:''}}
				</td>

				
				<td>{{ (!empty($team_b_bat['totalruns']))?$team_b_bat['totalruns']:'' }}</td>
				<td>{{ (!empty($team_b_bat['balls_played']))?$team_b_bat['balls_played']:'' }}</td>
				<td>{{ (!empty($team_b_bat['fours']))?$team_b_bat['fours']:'' }}</td>
				<td>{{ (!empty($team_b_bat['sixes']))?$team_b_bat['sixes']:'' }}</td>
				<td>{{ (!empty($team_b_bat['strikerate']))?number_format($team_b_bat['strikerate'],2):'' }}</td>
				
				@endforeach
			</tr>
			<td colspan="9">Extras(W {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['wide']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['wide']:''}} ,NB {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['noball']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['noball']:''}}, Lb {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['legbye']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['legbye']:''}}, b {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['bye']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['bye']:''}} others {{(!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['others']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['others']:''}} ) {{((!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['wide']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['wide']:'')+((!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['noball']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['noball']:'')+((!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['legbye']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['legbye']:'')+((!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['bye']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['bye']:'')+((!empty($team_wise_match_details[$match_data[0]['b_id']]['first']['others']))?$team_wise_match_details[$match_data[0]['b_id']]['first']['others']:'')}}</td>
			@else
			<tr>
			<td colspan="11">{{ 'No Records. '}}</td>
			</tr>
			@endif
		</tbody>

	</table>
	</div>

	
	 <h3 id='team_a_bowling' class="team_bowl table_head">{{ $team_a_name }} Bowling</h3>
	  <div class="table-responsive">
	<table class="table table-striped">
	 <thead class="thead">
		<tr>
			<th>Players</th>
			<th>O</th>
			<th>M</th>
			<th>R</th>
			<th>Wk</th>
			<th>Econ</th>
			<th>Wd</th>	
			<th>Nb</th>		
			<th></th>		
			<th></th>		
			<th></th>	
			
		</tr>
	</thead>	
		<tbody id="bowler_tr_a" >
		<?php $team_a_bowl_fst_ing=1;?>		
		@if(!empty($team_a_fst_ing_bowling_array) && count($team_a_fst_ing_bowling_array)>0)
			@foreach($team_a_fst_ing_bowling_array as $team_a_bowl)
			<tr>
				<!--<td>{!! Form::select('a_bowler_1',$team_a,null,array('class'=>'gui-input a_bowler','id'=>'a_bowler_1')) !!}</td>-->
				<td><a href="{{ url('/showsportprofile',[$team_a_bowl['user_id']]) }}" class="score_link">{{ (!empty($player_name_array[$team_a_bowl['user_id']]))?$player_name_array[$team_a_bowl['user_id']]:'' }}
				</a>
				</td>
				<td>{{ (!empty($team_a_bowl['overs_bowled']))?$team_a_bowl['overs_bowled']:'' }}</td>
				<td>{{ (!empty($team_a_bowl['overs_maiden']))?$team_a_bowl['overs_maiden']:'' }}</td>
				<td>{{ (!empty($team_a_bowl['runs_conceded']))?$team_a_bowl['runs_conceded']:''}}</td>
				<td>{{ (!empty($team_a_bowl['wickets']))?$team_a_bowl['wickets']:'' }}</td>
				<td>{{ (!empty($team_a_bowl['ecomony']))?number_format($team_a_bowl['ecomony'],2):'' }}</td>
				<td>{{(!empty($team_a_bowl['wides_bowl']))?$team_a_bowl['wides_bowl']:''}}</td>
                <td>{{ (!empty($team_a_bowl['noballs_bowl']))?$team_a_bowl['noballs_bowl']:''}}</td>
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
	<div class="clearfix"></div>
	 <h3 class="team_fall table_head">Fall Of Wickets</h3>
	 <div class="table-responsive">
	<table class="table table-striped">
	<thead class="thead">
		<tr> 
			<th>Wk</th>
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
		<tbody id="fall_of_wkt_b">
		<?php $team_b_fall_wkt = 1;?>
		@if(!empty($team_wise_match_details[$match_data[0]['b_id']]['first']) && count($team_wise_match_details[$match_data[0]['b_id']]['first'])>0 && $b_keyCount>0)

			@foreach($team_wise_match_details[$match_data[0]['b_id']]['first'] as $b_key => $team_b_fall)
			@if(is_numeric($b_key))
			<tr>
				<td>{{ (!empty($team_b_fall['wicket']))?$team_b_fall['wicket']:'' }}</td>
				<!--<td>{!! Form::select('b_wkt_player_1',$team_b,null,array('class'=>'gui-input','id'=>'b_wkt_player_1')) !!}</td>-->
				<td><a href="{{ url('/showsportprofile',[$team_b_fall['batsman']]) }}" class="score_link"> {{ (!empty($player_name_array[$team_b_fall['batsman']]))?$player_name_array[$team_b_fall['batsman']]:'' }}</a>
				</td>
				<td>{{ (!empty($team_b_fall['score']))?$team_b_fall['score']:'' }}</td>
				<td>{{ (!empty($team_b_fall['over']))?$team_b_fall['over']:'' }}</td>
				<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
			<?php $team_b_fall_wkt++;?>
			@endif
			@endforeach
			
		@else
			<tr>
			<td colspan="11">{{ 'No Records.' }}</td>
			</tr>	
		@endif
		</tbody>
	</table>
	</div>

	<!-- *********Fall Of Wickets ************* -->
	
<!--********* TEAM B  End **************!-->

	
	 	 
	 
{!!Form::close()!!}