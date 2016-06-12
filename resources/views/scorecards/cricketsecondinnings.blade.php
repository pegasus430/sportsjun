 <!--Batting:<select name="team" id="teams" onchange="getTeamNames();">
		 <option value="{{ $match_data[0]['player_a_ids'] }}" data-status="{{ $match_data[0]['a_id'] }}" >{{ $team_a_name }}</option>
		 <option value="{{ $match_data[0]['player_b_ids'] }}" data-status="{{ $match_data[0]['b_id'] }}" >{{ $team_b_name }}</option>
		 </select>-->
<?php $team_a = $team_a_scnd_ing; //team a players
$team_b = $team_b_scnd_ing; //team b players
$team_a_count = $team_a_scnd_ing_count; // team player count
$team_b_count = $team_b_scnd_ing_count; //team b player count

?>
{!! Form::open(array('url' => 'match/insertCricketScoreCard', 'method' => 'POST','id'=>'secondting')) !!}

<!--********* TEAM A Start**************!-->
<h3 id='second_team_a_batting' class="team_bat table_head">{{ $team_a_name }} Innings</h3>
<div class="table-responsive">
	<table class="table table-striped">
	<thead class="thead">
		<tr>
			<th>Batsman</th>
			<th>How Out</th>
                        <th>Fielder</th>
			<th>Bowler</th>
			<th>R</th>
			<th>B</th>
			<th>4s</th>
			<th>6s</th>
			<th>SR</th>
			<th></th>

		</tr>
		</thead>
		<tbody id="two_player_tr_a" >
		<?php $a_bat_second_ing = 1;?>
			@if(!empty($team_a_secnd_ing_array) && count($team_a_secnd_ing_array)>0)
			@foreach($team_a_secnd_ing_array as $a_batng_second_ing)
			<tr id="team_a_batting_{{$a_batng_second_ing['id']}}" class="team_a_batting_open_row">
				<!--<td>{!! Form::select('a_player_1',$team_a,null,array('class'=>'gui-input a_player','id'=>'a_players_second_ing_1')) !!}</td>
				<td>{!! Form::select('a_outas_1',$enum,null,array('class'=>'gui-input','id'=>'a_outas_second_ing_1')) !!}</td>
				<td>{!! Form::select('a_bowled_1',$team_b,null,array('class'=>'gui-input','id'=>'a_bowled_second_ing_1')) !!}</td>
				<td>{!! Form::select('a_fielder_1',$team_b,null,array('class'=>'gui-input','id'=>'a_fielder_second_ing_1')) !!}</td>-->

				<td><select name='a_player_{{ $a_bat_second_ing }}' class='gui-input a_player_ing' id='a_players_second_ing_{{$a_bat_second_ing}}'>
					@foreach($team_a as $a_key => $a_val)
					<option value="{{$a_key}}" <?php if (isset($a_batng_second_ing['user_id']) && $a_batng_second_ing['user_id']==$a_key) echo ' selected';?>>{{ $a_val }}</option>
					@endforeach
				</select>
				</td>

				<td><select name='a_outas_{{ $a_bat_second_ing }}' class='gui-input team_a_scnd_ing_wkt' id='a_outas_second_ing_{{$a_bat_second_ing}}'>
					@foreach($enum as $enum_key => $enum_val)
					<option value="{{$enum_key}}" <?php if (isset($a_batng_second_ing['out_as']) && $a_batng_second_ing['out_as']==$enum_key) echo ' selected';?>>{{ $enum_val }}</option>
					@endforeach
				</select>
				</td>
                                
                                <td><select name='a_fielder_{{ $a_bat_second_ing }}' class='gui-input' id='a_fielder_second_ing_{{$a_bat_second_ing}}'>
					@foreach($team_b as $b_player_key => $b_player_val)
					<option value="{{$b_player_key}}" <?php if (isset($a_batng_second_ing['fielder_id']) && $a_batng_second_ing['fielder_id']==$b_player_key) echo ' selected';?>>{{ $b_player_val }}</option>
					@endforeach
				</select>
				<span id="a_ingfieldershow_{{ $a_bat_second_ing }}" style="display:none;">{{'--'}}</span>
				</td>
                                
				<td><select name='a_bowled_{{ $a_bat_second_ing }}' class='gui-input' id='a_bowled_second_ing_{{$a_bat_second_ing}}'>
					@foreach($team_b as $b_key => $b_val)
					<option value="{{$b_key}}" <?php if (isset($a_batng_second_ing['bowled_id']) && $a_batng_second_ing['bowled_id']==$b_key) echo ' selected';?>>{{ $b_val }}</option>
					@endforeach
				</select>
				<span id="a_ingbowlershow_{{ $a_bat_second_ing }}" style="display:none;">{{'--'}}</span>
				</td>

				<td>{!! Form::text('a_runs_'.$a_bat_second_ing, (!empty($a_batng_second_ing['totalruns']))?$a_batng_second_ing['totalruns']:'', array('class'=>'gui-input allownumericwithdecimal team_a_scnd_ing_score runs_new','id'=>'a_runs_ing_'.$a_bat_second_ing,'onkeyup'=>"batsman_strikeratecalculator('a_runs_ing_$a_bat_second_ing','a_balls_ing_$a_bat_second_ing','a_strik_rate_ing_$a_bat_second_ing');")) !!}</td>

				<td>{!! Form::text('a_balls_'.$a_bat_second_ing, (!empty($a_batng_second_ing['balls_played']))?$a_batng_second_ing['balls_played']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_balls_ing_'.$a_bat_second_ing,'onkeyup'=>"batsman_strikeratecalculator('a_runs_ing_$a_bat_second_ing','a_balls_ing_$a_bat_second_ing','a_strik_rate_ing_$a_bat_second_ing');")) !!}</td>

				<td>{!! Form::text('a_fours_'.$a_bat_second_ing, (!empty($a_batng_second_ing['fours']))?$a_batng_second_ing['fours']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_fours_'.$a_bat_second_ing)) !!}</td>

				<td>{!! Form::text('a_sixes_'.$a_bat_second_ing, (!empty($a_batng_second_ing['sixes']))?$a_batng_second_ing['sixes']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_sixes_'.$a_bat_second_ing)) !!}</td>

				<td>{!! Form::text('a_strik_rate_'.$a_bat_second_ing, (!empty($a_batng_second_ing['strikerate']))?number_format($a_batng_second_ing['strikerate'],2):'', array('class'=>'gui-input allownumericwithdecimal strike_new','id'=>'a_strik_rate_ing_'.$a_bat_second_ing,'readonly')) !!}</td>

				<td><a href="#" onclick="deleteRow('a','batting',{{$a_batng_second_ing['id']}},{{$a_bat_second_ing}})" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></td>
			</tr>
			<?php $a_bat_second_ing++;?>
			@endforeach

			@else
			<tr class="team_a_batting_open_row">
				<td>{!! Form::select('a_player_1',$team_a,null,array('class'=>'gui-input a_player_ing','id'=>'a_players_second_ing_1')) !!}</td>
				<!--<td>{!! Form::text('a_outas_1', null, array('class'=>'gui-input','id'=>'a_outas_1')) !!}</td>-->
				<td>{!! Form::select('a_outas_1',$enum,null,array('class'=>'gui-input team_a_scnd_ing_wkt','id'=>'a_outas_second_ing_1')) !!}</td>
				<td>{!! Form::select('a_bowled_1',$team_b,null,array('class'=>'gui-input','id'=>'a_bowled_second_ing_1')) !!}<span id="a_ingbowlershow_1" style="display:none;">{{'--'}}</span></td>
				<td>{!! Form::select('a_fielder_1',$team_b,null,array('class'=>'gui-input','id'=>'a_fielder_second_ing_1')) !!}<span id="a_ingfieldershow_1" style="display:none;">{{'--'}}</span></td>
				<td>{!! Form::text('a_runs_1', null, array('class'=>'gui-input allownumericwithdecimal team_a_scnd_ing_score runs_new','id'=>'a_runs_ing_1','onkeyup'=>"batsman_strikeratecalculator('a_runs_ing_$a_bat_second_ing','a_balls_ing_$a_bat_second_ing','a_strik_rate_ing_$a_bat_second_ing');")) !!}</td>
				<td>{!! Form::text('a_balls_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'a_balls_ing_1')) !!}</td>
				<td>{!! Form::text('a_fours_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'a_fours_1')) !!}</td>
				<td>{!! Form::text('a_sixes_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'a_sixes_1')) !!}</td>
				<td>{!! Form::text('a_strik_rate_1', null, array('class'=>'gui-input allownumericwithdecimal strike_new','id'=>'a_strik_rate_ing_1','readonly')) !!}</td>
				<td></td>
			</tr>

			@endif
		</tbody>
	</table>
	</div>
	 <a id="a_bat_ing" onclick="second_innings_getPlayerTr(<?php echo $i=$a_bat_second_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>

	<div class="clearfix"></div>
	 <h3 id='second_team_b_bowling' class="team_bowl table_head">{{ $team_b_name }} Bowling</h3>
	 <div class="table-responsive">
	<table class="table table-striped">
	<thead class="thead">
		<tr>
			<th>Bowlers</th>
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
			<th></th>
		</tr>
		</thead>
		<tbody id="two_bowler_tr_b" >
		<?php $b_bowl_second_ing=1;?>
		@if(!empty($team_b_scnd_ing_bowling_array) && count($team_b_scnd_ing_bowling_array)>0)
			@foreach($team_b_scnd_ing_bowling_array as $team_b_bowl_scnd_ing)
			<tr id="team_b_bowling_{{$team_b_bowl_scnd_ing['id']}}" class="team_b_bowling_open_row">
				<!--<td>{!! Form::select('b_bowler_1',$team_b,null,array('class'=>'gui-input b_bowler','id'=>'b_bowlers_second_ing_1')) !!}</td>-->

				<td><select name='b_bowler_{{$b_bowl_second_ing}}' id="b_bowlers_second_ing_{{$b_bowl_second_ing}}" class='gui-input b_bowler_ing'>
				@foreach($team_b as $b_bowl_key => $b_bowl_val)
				<option value="{{$b_bowl_key}}" <?php if (isset($team_b_bowl_scnd_ing['user_id']) && $team_b_bowl_scnd_ing['user_id']==$b_bowl_key) echo ' selected';?>>{{$b_bowl_val}}</option>
				@endforeach
				</select></td>

				<td>{!! Form::text('b_bowler_overs_'.$b_bowl_second_ing, (!empty($team_b_bowl_scnd_ing['overs_bowled']))?$team_b_bowl_scnd_ing['overs_bowled']:'', array('class'=>'gui-input allownumericwithdecimal team_b_scnd_ing_overs','id'=>'b_bowler_overs_ing_'.$b_bowl_second_ing,'onkeyup'=>"bowler_economycalculator('b_bowler_runs_ing_$b_bowl_second_ing','b_bowler_overs_ing_$b_bowl_second_ing','b_ecomony_ing_$b_bowl_second_ing');")) !!}</td>
				
				
				<td>{!! Form::text('b_bowler_maidens_'.$b_bowl_second_ing, (!empty($team_b_bowl_scnd_ing['overs_maiden']))?$team_b_bowl_scnd_ing['overs_maiden']:'', array('class'=>'gui-input allownumericwithdecimal team_b_scnd_ing_maidens','id'=>'b_bowler_maidens_ing_'.$b_bowl_second_ing)) !!}</td>
				
				<td>{!! Form::text('b_bowler_runs_'.$b_bowl_second_ing, (!empty($team_b_bowl_scnd_ing['runs_conceded']))?$team_b_bowl_scnd_ing['runs_conceded']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'b_bowler_runs_ing_'.$b_bowl_second_ing,'onkeyup'=>"bowler_economycalculator('b_bowler_runs_ing_$b_bowl_second_ing','b_bowler_overs_ing_$b_bowl_second_ing','b_ecomony_ing_$b_bowl_second_ing');")) !!}</td>
				<td>{!! Form::text('b_bowler_wkts_'.$b_bowl_second_ing, (!empty($team_b_bowl_scnd_ing['wickets']))?$team_b_bowl_scnd_ing['wickets']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'b_bowler_wkts_'.$b_bowl_second_ing)) !!}</td>
				<td>{!! Form::text('b_ecomony_'.$b_bowl_second_ing, (!empty($team_b_bowl_scnd_ing['ecomony']))?number_format($team_b_bowl_scnd_ing['ecomony'],2):'', array('class'=>'gui-input allownumericwithdecimal','id'=>'b_ecomony_ing_'.$b_bowl_second_ing,'readonly')) !!}</td>
				<td>{!! Form::text('b_bowler_wide_'.$b_bowl_second_ing, (!empty($team_b_bowl_scnd_ing['wides_bowl']))?$team_b_bowl_scnd_ing['wides_bowl']:'', array('class'=>'gui-input allownumericwithdecimal b_wides_ing','id'=>'b_bowler_wide_ing_'.$b_bowl_second_ing)) !!}</td>
                <td>{!! Form::text('b_bowler_noball_'.$b_bowl_second_ing, (!empty($team_b_bowl_scnd_ing['noballs_bowl']))?$team_b_bowl_scnd_ing['noballs_bowl']:'', array('class'=>'gui-input allownumericwithdecimal b_noballs_ing','id'=>'b_bowler_noball_ing_'.$b_bowl_second_ing)) !!}</td>
                <td></td>
                <td></td>
                <td></td>
               <td><a href="#" onclick="deleteRow('b','bowling',{{$team_b_bowl_scnd_ing['id']}},{{$b_bowl_second_ing}})" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></a></td>
			</tr>
			<?php $b_bowl_second_ing++;?>
			@endforeach
		@else
			<tr class="team_b_bowling_open_row">
				<td>{!! Form::select('b_bowler_1',$team_b,null,array('class'=>'gui-input b_bowler_ing','id'=>'b_bowlers_second_ing_1')) !!}</td>
				<td>{!! Form::text('b_bowler_overs_1', null, array('class'=>'gui-input allownumericwithdecimal team_b_scnd_ing_overs','id'=>'b_bowler_overs_ing_1','onkeyup'=>"bowler_economycalculator('b_bowler_runs_ing_$b_bowl_second_ing','b_bowler_overs_ing_$b_bowl_second_ing','b_ecomony_ing_$b_bowl_second_ing');")) !!}</td>
				
				<td>{!! Form::text('b_bowler_maidens_'.$b_bowl_second_ing, (!empty($team_b_bowl_scnd_ing['overs_maiden']))?$team_b_bowl_scnd_ing['overs_maiden']:'', array('class'=>'gui-input allownumericwithdecimal team_b_scnd_ing_maidens','id'=>'b_bowler_maidens_ing_'.$b_bowl_second_ing)) !!}</td>
				
				<td>{!! Form::text('b_bowler_runs_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'b_bowler_runs_ing_1','onkeyup'=>"bowler_economycalculator('b_bowler_runs_ing_$b_bowl_second_ing','b_bowler_overs_ing_$b_bowl_second_ing','b_ecomony_ing_$b_bowl_second_ing');")) !!}</td>
				<td>{!! Form::text('b_bowler_wkts_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'b_bowler_wkts_1')) !!}</td>
				<td>{!! Form::text('b_ecomony_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'b_ecomony_ing_1','readonly')) !!}</td>
				<td>{!! Form::text('b_bowler_wide_1', null, array('class'=>'gui-input allownumericwithdecimal b_wides_ing','id'=>'b_bowler_wide_ing_1')) !!}</td>
                <td>{!! Form::text('b_bowler_noball_1', null, array('class'=>'gui-input allownumericwithdecimal b_noballs_ing','id'=>'b_bowler_noball_ing_1')) !!}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>

		@endif
		</tbody>
	</table>
	</div>
    <a id="b_bowl_ing" onclick="second_innings_getTeambBowlerTr(<?php echo $i=$b_bowl_second_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>
		<!-- Team A Extras------>
	<div class="clearfix"></div>
	<h3 id='team_a_scnd_extras' class="team_extra table_head">{{ $team_a_name }} Extras</h3>
	<div class="table-responsive">
	<table class="table table-striped">
	<thead class="thead">
		<tr>
			<th>Wd</th>
			<th>Nb</th>
			<th>Lb</th>
			<th>B</th>
			<th>Others</th>
			<th>Total</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>

		</tr>
	</thead>
	<tbody>
		<td>{!! Form::text('team_a_wide', (!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['wide']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['wide']:'', array('class'=>'gui-input allownumericwithdecimal a_scnd_ing_extras','id'=>'team_a_ing_wide','readonly')) !!}</td>
		<td>{!! Form::text('team_a_noball', (!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['noball']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['noball']:'', array('class'=>'gui-input allownumericwithdecimal a_scnd_ing_extras','id'=>'team_a_ing_noball','readonly')) !!}</td>
		<td>{!! Form::text('team_a_legbye', (!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['legbye']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['legbye']:'', array('class'=>'gui-input allownumericwithdecimal a_scnd_ing_extras','id'=>'team_a_ing_legbye')) !!}</td>
		<td>{!! Form::text('team_a_bye', (!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['bye']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['bye']:'', array('class'=>'gui-input allownumericwithdecimal a_scnd_ing_extras','id'=>'team_a_ing_bye')) !!}</td>
		<td>{!! Form::text('team_a_others', (!empty($team_wise_match_details[$match_data[0]['a_id']]['second']['others']))?$team_wise_match_details[$match_data[0]['a_id']]['second']['others']:'', array('class'=>'gui-input allownumericwithdecimal a_scnd_ing_extras','id'=>'team_a_ing_others')) !!}</td>
		<td>{!! Form::text('team_a_ing_tot_extras', '', array('class'=>'gui-input allownumericwithdecimal','readonly','id'=>'team_a_ing_tot_extras')) !!}</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
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
			<th></th>
		</tr>
	</thead>
		<tbody id="fall_of_wkt_fst_a">
		<?php $fall_of_a_second_ing=1;?>
		@if(!empty($team_wise_match_details[$match_data[0]['a_id']]['second']) && count($team_wise_match_details[$match_data[0]['a_id']]['second'])>0 && $a_keycount_scnd_ing>0)
			@foreach($team_wise_match_details[$match_data[0]['a_id']]['second'] as $a_scnd_key => $team_a_wkts_ing)
			@if(is_numeric($a_scnd_key))
			<tr class="team_a_fall_ing_row">
				<td>{!! Form::text('a_wicket_'.$fall_of_a_second_ing, (!empty($team_a_wkts_ing['wicket']))?$team_a_wkts_ing['wicket']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_wicket_fst_ing_'.$fall_of_a_second_ing)) !!}</td>
				<!--<td>{!! Form::select('a_wkt_player_1',$team_a,null,array('class'=>'gui-input','id'=>'a_wkt_player_fst_ing_1')) !!}</td>-->
				<td><select name="a_wkt_player_{{$fall_of_a_second_ing}}" id="a_wkt_player_fst_ing_{{$fall_of_a_second_ing}}" class="gui-input a_scnd_fal_wkt">
				@foreach($team_a as $a_fall_key => $a_fall_val)
				<option value="{{$a_fall_key}}" <?php if (isset($team_a_wkts_ing['batsman']) && $team_a_wkts_ing['batsman']==$a_fall_key) echo ' selected';?>>{{$a_fall_val}}</option>
				@endforeach
				</select>
				</td>
				<td>{!! Form::text('a_at_runs_'.$fall_of_a_second_ing, (!empty($team_a_wkts_ing['score']))?$team_a_wkts_ing['score']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_at_runs_fst_ing_'.$fall_of_a_second_ing)) !!}</td>
				<td>{!! Form::text('a_at_over_'.$fall_of_a_second_ing, (!empty($team_a_wkts_ing['over']))?$team_a_wkts_ing['over']:'', array('class'=>'gui-input allownumericwithdecimal out_at_over','id'=>'a_at_over_fst_ing_'.$fall_of_a_second_ing)) !!}</td>
				<td></td>
				<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
			<?php $fall_of_a_second_ing++;?>
			@endif
			@endforeach
		@else
			<tr class="team_a_fall_ing_row">
				<td>{!! Form::text('a_wicket_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'a_wicket_fst_ing_1')) !!}</td>
				<td>{!! Form::select('a_wkt_player_1',$team_a,null,array('class'=>'gui-input a_scnd_fal_wkt','id'=>'a_wkt_player_fst_ing_1')) !!}</td>
				<td>{!! Form::text('a_at_runs_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'a_at_runs_fst_ing_1')) !!}</td>
				<td>{!! Form::text('a_at_over_1', null, array('class'=>'gui-input allownumericwithdecimal out_at_over','id'=>'a_at_over_fst_ing_1')) !!}</td>
				 <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
		@endif

		</tbody>
	</table>
	</div>
    <a  id="a_fall_wkt_ing" onclick="fall_of_wkts('a','fst',<?php echo $i=$fall_of_a_second_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>

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
			<th>Batsman</th>
			<th>How Out</th>
                        <th>Fielder</th>
			<th>Bowler</th>
			<th>R</th>
			<th>B</th>
			<th>4s</th>
			<th>6s</th>
			<th>SR</th>
			<th></th>

		</tr>
		</thead>
		<tbody id="two_player_tr_b" >
		<?php $team_b_bat_scnd_ing = 1;?>
			@if(!empty($team_b_secnd_ing_array) && count($team_b_secnd_ing_array)>0)
				@foreach($team_b_secnd_ing_array as $team_b_bat_scnd)
			<tr id="team_b_batting_{{$team_b_bat_scnd['id']}}" class="team_b_batting_open_row">
				<!--<td>{!! Form::select('b_player_1',$team_b,null,array('class'=>'gui-input b_player','id'=>'b_players_second_ing_1')) !!}</td>
				<td>{!! Form::select('b_outas_1',$enum,null,array('class'=>'gui-input','id'=>'b_outas_second_ing_1')) !!}</td>
				<td>{!! Form::select('b_bowled_1',$team_a,null,array('class'=>'gui-input','id'=>'b_bowled_second_ing_1')) !!}</td>
				<td>{!! Form::select('b_fielder_1',$team_a,null,array('class'=>'gui-input','id'=>'b_fielder_second_ing_1')) !!}</td>-->

				<td><select name='b_player_{{ $team_b_bat_scnd_ing }}' class='gui-input b_player_ing' id='b_players_second_ing_{{$team_b_bat_scnd_ing}}'>
					@foreach($team_b as $b__bat_key => $b_bowl_val)
					<option value="{{$b__bat_key}}" <?php if (isset($team_b_bat_scnd['user_id']) && $team_b_bat_scnd['user_id']==$b__bat_key) echo ' selected';?>>{{ $b_bowl_val }}</option>
					@endforeach
				</select>
				</td>

				<td><select name='b_outas_{{ $team_b_bat_scnd_ing }}' class='gui-input team_b_scnd_ing_wkt' id='b_outas_second_ing_{{$team_b_bat_scnd_ing}}'>
					@foreach($enum as $enum_b_key => $enum_b_val)
					<option value="{{$enum_b_key}}" <?php if (isset($team_b_bat_scnd['out_as']) && $team_b_bat_scnd['out_as']==$enum_b_key) echo ' selected';?>>{{ $enum_b_val }}</option>
					@endforeach
				</select>
				</td>
                                
                                <td><select name='b_fielder_{{ $team_b_bat_scnd_ing }}' class='gui-input' id='b_fielder_second_ing_{{$team_b_bat_scnd_ing}}'>
					@foreach($team_a as $b_bating_key => $b_bating_val)
					<option value="{{$b_bating_key}}" <?php if (isset($team_b_bat_scnd['fielder_id']) && $team_b_bat_scnd['fielder_id']==$b_bating_key) echo ' selected';?>>{{ $b_bating_val }}</option>
					@endforeach
				</select>
				<span id="b_ingfieldershow_{{$b_bowled_key}}" style="display:none;">{{'--'}}</span>
				</td>
                                
				<td><select name='b_bowled_{{ $team_b_bat_scnd_ing }}' class='gui-input' id='b_bowled_second_ing_{{$team_b_bat_scnd_ing}}'>
					@foreach($team_a as $b_bowled_key => $b_bowled_val)
					<option value="{{$b_bowled_key}}" <?php if (isset($team_b_bat_scnd['bowled_id']) && $team_b_bat_scnd['bowled_id']==$b_bowled_key) echo ' selected';?>>{{ $b_bowled_val }}</option>
					@endforeach
				</select>
				<span id="b_ingbowlershow_{{$b_bowled_key}}" style="display:none;">{{'--'}}</span>
				</td>

				<td>{!! Form::text('b_runs_'.$team_b_bat_scnd_ing, (!empty($team_b_bat_scnd['totalruns']))?$team_b_bat_scnd['totalruns']:'', array('class'=>'gui-input allownumericwithdecimal team_b_scnd_ing_score runs_new','id'=>'b_runs_ing_'.$team_b_bat_scnd_ing,'onkeyup'=>"batsman_strikeratecalculator('b_runs_ing_$team_b_bat_scnd_ing','b_balls_ing_$team_b_bat_scnd_ing','b_strik_rate_ing_$team_b_bat_scnd_ing');")) !!}</td>
				<td>{!! Form::text('b_balls_'.$team_b_bat_scnd_ing, (!empty($team_b_bat_scnd['balls_played']))?$team_b_bat_scnd['balls_played']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'b_balls_ing_'.$team_b_bat_scnd_ing,'onkeyup'=>"batsman_strikeratecalculator('b_runs_ing_$team_b_bat_scnd_ing','b_balls_ing_$team_b_bat_scnd_ing','b_strik_rate_ing_$team_b_bat_scnd_ing');")) !!}</td>
				<td>{!! Form::text('b_fours_'.$team_b_bat_scnd_ing, (!empty($team_b_bat_scnd['fours']))?$team_b_bat_scnd['fours']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'b_fours_'.$team_b_bat_scnd_ing)) !!}</td>
				<td>{!! Form::text('b_sixes_'.$team_b_bat_scnd_ing, (!empty($team_b_bat_scnd['sixes']))?$team_b_bat_scnd['sixes']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'b_sixes_'.$team_b_bat_scnd_ing)) !!}</td>
				<td>{!! Form::text('b_strik_rate_'.$team_b_bat_scnd_ing, (!empty($team_b_bat_scnd['strikerate']))?number_format($team_b_bat_scnd['strikerate'],2):'', array('class'=>'gui-input allownumericwithdecimal strike_new','id'=>'b_strik_rate_ing_'.$team_b_bat_scnd_ing,'readonly')) !!}</td>
				 <td><a href="#" onclick="deleteRow('b','batting',{{$team_b_bat_scnd['id']}},{{$team_b_bat_scnd_ing}})" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></td>
			</tr>
			<?php $team_b_bat_scnd_ing++; ?>
			@endforeach
			@else
			<tr class="team_b_batting_open_row">
				<td>{!! Form::select('b_player_1',$team_b,null,array('class'=>'gui-input b_player_ing','id'=>'b_players_second_ing_1')) !!}</td>
				<!--<td>{!! Form::text('b_outas_1', null, array('class'=>'gui-input','id'=>'b_outas_1')) !!}</td>-->
				<td>{!! Form::select('b_outas_1',$enum,null,array('class'=>'gui-input team_b_scnd_ing_wkt','id'=>'b_outas_second_ing_1')) !!}</td>
                                <td>{!! Form::select('b_fielder_1',$team_a,null,array('class'=>'gui-input','id'=>'b_fielder_second_ing_1')) !!}<span id="b_ingfieldershow_1" style="display:none;">{{'--'}}</span></td>
				<td>{!! Form::select('b_bowled_1',$team_a,null,array('class'=>'gui-input','id'=>'b_bowled_second_ing_1')) !!}<span id="b_ingbowlershow_1" style="display:none;">{{'--'}}</span></td>
				<td>{!! Form::text('b_runs_1', null, array('class'=>'gui-input allownumericwithdecimal team_b_scnd_ing_score runs_new','id'=>'b_runs_ing_1','onkeyup'=>"batsman_strikeratecalculator('b_runs_ing_$team_b_bat_scnd_ing','b_balls_ing_$team_b_bat_scnd_ing','b_strik_rate_ing_$team_b_bat_scnd_ing');")) !!}</td>
				<td>{!! Form::text('b_balls_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'b_balls_ing_1','onkeyup'=>"batsman_strikeratecalculator('b_runs_ing_$team_b_bat_scnd_ing','b_balls_ing_$team_b_bat_scnd_ing','b_strik_rate_ing_$team_b_bat_scnd_ing');")) !!}</td>
				<td>{!! Form::text('b_fours_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'b_fours_1')) !!}</td>
				<td>{!! Form::text('b_sixes_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'b_sixes_1')) !!}</td>
				<td>{!! Form::text('b_strik_rate_1', null, array('class'=>'gui-input allownumericwithdecimal strike_new','id'=>'b_strik_rate_ing_1','readonly')) !!}</td>
				<td></td>

			</tr>

			@endif

		</tbody>

	</table>
	</div>
		<a  id="b_bat_ing" onclick="second_innings_getteamBPlayerTr(<?php echo $i=$team_b_bat_scnd_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>

	 <h3 id='second_team_a_bowling' class="team_bowl table_head">{{ $team_a_name }} Bowling</h3>
	<div class="table-responsive">
	<table class="table table-striped">
    	<thead class="thead">
		<tr>
			<th>Bowlers</th>
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
			<th></th>

		</tr>
		</thead>
		<tbody id="two_bowler_tr_a" >
		<?php $team_a_secnd_bat_ing = 1;?>
		@if(!empty($team_a_scnd_ing_bowling_array) && count($team_a_scnd_ing_bowling_array)>0)
			@foreach($team_a_scnd_ing_bowling_array as $team_a_secnd_bat)
			<tr id="team_a_bowling_{{$team_a_secnd_bat['id']}}" class="team_a_bowling_open_row">
				<!--<td>{!! Form::select('a_bowler_1',$team_a,null,array('class'=>'gui-input a_bowler','id'=>'a_bowlers_second_ing_1')) !!}</td>-->
				<td><select name='a_bowler_{{$team_a_secnd_bat_ing}}' id='a_bowlers_second_ing_{{$team_a_secnd_bat_ing}}' class='gui-input a_bowler_ing'>
				@foreach($team_a as $a_bowl_key => $a_bowl_val)
				<option value="{{$a_bowl_key}}" <?php if (isset($team_a_secnd_bat['user_id']) && $team_a_secnd_bat['user_id']==$a_bowl_key) echo ' selected';?>>{{$a_bowl_val}}</option>
				@endforeach
				</select></td>
				<td>{!! Form::text('a_bowler_overs_'.$team_a_secnd_bat_ing, (!empty($team_a_secnd_bat['overs_bowled']))?$team_a_secnd_bat['overs_bowled']:'', array('class'=>'gui-input allownumericwithdecimal team_a_scnd_ing_overs','id'=>'a_bowler_overs_ing_'.$team_a_secnd_bat_ing,'onkeyup'=>"bowler_economycalculator('a_bowler_runs_ing_$team_a_secnd_bat_ing','a_bowler_overs_ing_$team_a_secnd_bat_ing','a_ecomony_ing_$team_a_secnd_bat_ing');")) !!}</td>
				
				<td>{!! Form::text('a_bowler_maidens_'.$team_a_secnd_bat_ing, (!empty($team_a_secnd_bat['overs_maiden']))?$team_a_secnd_bat['overs_maiden']:'', array('class'=>'gui-input allownumericwithdecimal team_a_scnd_ing_maidens','id'=>'a_bowler_maidens_ing_'.$team_a_secnd_bat_ing)) !!}</td>
				
				<td>{!! Form::text('a_bowler_runs_'.$team_a_secnd_bat_ing, (!empty($team_a_secnd_bat['runs_conceded']))?$team_a_secnd_bat['runs_conceded']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_bowler_runs_ing_'.$team_a_secnd_bat_ing,'onkeyup'=>"bowler_economycalculator('a_bowler_runs_ing_$team_a_secnd_bat_ing','a_bowler_overs_ing_$team_a_secnd_bat_ing','a_ecomony_ing_$team_a_secnd_bat_ing');")) !!}</td>
				<td>{!! Form::text('a_bowler_wkts_'.$team_a_secnd_bat_ing, (!empty($team_a_secnd_bat['wickets']))?$team_a_secnd_bat['wickets']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_bowler_wkts_'.$team_a_secnd_bat_ing)) !!}</td>
				<td>{!! Form::text('a_ecomony_'.$team_a_secnd_bat_ing, (!empty($team_a_secnd_bat['ecomony']))?number_format($team_a_secnd_bat['ecomony'],2):'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_ecomony_ing_'.$team_a_secnd_bat_ing,'readonly')) !!}</td>

				<td>{!! Form::text('a_bowler_wide_'.$team_a_secnd_bat_ing, (!empty($team_a_secnd_bat['wides_bowl']))?$team_a_secnd_bat['wides_bowl']:'', array('class'=>'gui-input allownumericwithdecimal a_wides_ing','id'=>'a_bowler_wide_ing_'.$team_a_secnd_bat_ing)) !!}</td>
				<td>{!! Form::text('a_bowler_noball_'.$team_a_secnd_bat_ing, (!empty($team_a_secnd_bat['noballs_bowl']))?$team_a_secnd_bat['noballs_bowl']:'', array('class'=>'gui-input allownumericwithdecimal a_noballs_ing','id'=>'a_bowler_noball_ing_'.$team_a_secnd_bat_ing)) !!}</td>
				<td><a href="#" onclick="deleteRow('a','bowling',{{$team_a_secnd_bat['id']}},{{$team_a_secnd_bat_ing}})" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></td>
				<td></td>
				<td></td>
				<td></td>


			</tr>
			<?php $team_a_secnd_bat_ing++;?>
			@endforeach
		@else
			<tr class="team_a_bowling_open_row">
				<td>{!! Form::select('a_bowler_1',$team_a,null,array('class'=>'gui-input a_bowler_ing','id'=>'a_bowlers_second_ing_1')) !!}</td>
				<td>{!! Form::text('a_bowler_overs_1', null, array('class'=>'gui-input allownumericwithdecimal team_a_scnd_ing_overs','id'=>'a_bowler_overs_ing_1','onkeyup'=>"bowler_economycalculator('a_bowler_runs_ing_$team_a_secnd_bat_ing','a_bowler_overs_ing_$team_a_secnd_bat_ing','a_ecomony_ing_$team_a_secnd_bat_ing');")) !!}</td>
				<td>{!! Form::text('a_bowler_maidens_'.$team_a_secnd_bat_ing, (!empty($team_a_secnd_bat['overs_maiden']))?$team_a_secnd_bat['overs_maiden']:'', array('class'=>'gui-input allownumericwithdecimal team_a_scnd_ing_maidens','id'=>'a_bowler_maidens_ing_'.$team_a_secnd_bat_ing)) !!}</td>
				<td>{!! Form::text('a_bowler_runs_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'a_bowler_runs_ing_1','onkeyup'=>"bowler_economycalculator('a_bowler_runs_ing_$team_a_secnd_bat_ing','a_bowler_overs_ing_$team_a_secnd_bat_ing','a_ecomony_ing_$team_a_secnd_bat_ing');")) !!}</td>
				<td>{!! Form::text('a_bowler_wkts_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'a_bowler_wkts_1')) !!}</td>
				<td>{!! Form::text('a_ecomony_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'a_ecomony_ing_1','readonly')) !!}</td>
				<td>{!! Form::text('a_bowler_wide_1', null, array('class'=>'gui-input allownumericwithdecimal a_wides_ing','id'=>'a_bowler_wide_ing_1')) !!}</td>
					<td>{!! Form::text('a_bowler_noball_1', null, array('class'=>'gui-input allownumericwithdecimal a_noballs_ing','id'=>'a_bowler_noball_ing_1')) !!}</td>
					<td></td>
					<td></td>
					<td></td>

			</tr>
		@endif
		</tbody>

	</table>
	</div>
	 <a  id="a_bowl_ing" onclick="second_innings_getBowlerTr(<?php echo $i=$team_a_secnd_bat_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>


	<!-- Team B Extras------>
	<div class="clearfix"></div>
	<h3 id='team_b_scnd_extras' class="team_extra table_head">{{ $team_b_name }} Extras</h3>
	<div class="table-responsive">
	<table class="table table-striped">
	<thead class="thead">
		<tr>
			<th>Wd</th>
			<th>Nb</th>
			<th>Lb</th>
			<th>B</th>
			<th>Others</th>
			<th>Total</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>

		</tr>
	</thead>
	<tbody>
		<td>{!! Form::text('team_b_wide', (!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['wide']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['wide']:'', array('class'=>'gui-input allownumericwithdecimal b_scnd_ing_extras','id'=>'team_b_ing_wide','readonly')) !!}</td>
		<td>{!! Form::text('team_b_noball', (!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['noball']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['noball']:'', array('class'=>'gui-input allownumericwithdecimal b_scnd_ing_extras','id'=>'team_b_ing_noball','readonly')) !!}</td>
		<td>{!! Form::text('team_b_legbye', (!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['legbye']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['legbye']:'', array('class'=>'gui-input allownumericwithdecimal b_scnd_ing_extras','id'=>'team_b_ing_legbye')) !!}</td>
		<td>{!! Form::text('team_b_bye', (!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['bye']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['bye']:'', array('class'=>'gui-input allownumericwithdecimal b_scnd_ing_extras','id'=>'team_b_ing_bye')) !!}</td>
		<td>{!! Form::text('team_b_others', (!empty($team_wise_match_details[$match_data[0]['b_id']]['second']['others']))?$team_wise_match_details[$match_data[0]['b_id']]['second']['others']:'', array('class'=>'gui-input allownumericwithdecimal b_scnd_ing_extras','id'=>'team_b_ing_others')) !!}</td>
		<td>{!! Form::text('team_b_ing_extras', '', array('class'=>'gui-input allownumericwithdecimal','id'=>'team_b_ing_extras')) !!}</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tbody>

	</table>
	</div>

		<!-- *********Fall Of Wickets ************* -->

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
			<th></th>
		</tr>
		</thead>
		<tbody id="fall_of_wkt_second_b" >
		<?php $team_b_fall_wkt_ing = 1;?>
		@if(!empty($team_wise_match_details[$match_data[0]['b_id']]['second']) && count($team_wise_match_details[$match_data[0]['b_id']]['second'])>0 && $b_keycount_scnd_ing>0)
			@foreach($team_wise_match_details[$match_data[0]['b_id']]['second'] as $b_key => $team_b_fall_ing)
			@if(is_numeric($b_key))
			<tr class="team_b_fall_ing_row">
				<td>{!! Form::text('b_wicket_'.$team_b_fall_wkt_ing, (!empty($team_b_fall_ing['wicket']))?$team_b_fall_ing['wicket']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'b_wicket_'.$team_b_fall_wkt_ing)) !!}</td>
				<!--<td>{!! Form::select('b_wkt_player_1',$team_b,null,array('class'=>'gui-input','id'=>'b_wkt_player_second_ing_1')) !!}</td>-->
				<td><select name="b_wkt_player_{{$team_b_fall_wkt_ing}}" id="b_wkt_player_second_ing_{{$team_b_fall_wkt_ing}}" class="gui-input b_scnd_fal_wkt">
				@foreach($team_b as $b_fall_key => $b_fall_val)
				<option value="{{$b_fall_key}}" <?php if (isset($team_b_fall_ing['batsman']) && $team_b_fall_ing['batsman']==$b_fall_key) echo ' selected';?>>{{$b_fall_val}}</option>
				@endforeach
				</select>
				</td>
				<td>{!! Form::text('b_at_runs_'.$team_b_fall_wkt_ing, (!empty($team_b_fall_ing['score']))?$team_b_fall_ing['score']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'b_at_runs_'.$team_b_fall_wkt_ing)) !!}</td>
				<td>{!! Form::text('b_at_over_'.$team_b_fall_wkt_ing, (!empty($team_b_fall_ing['over']))?$team_b_fall_ing['over']:'', array('class'=>'gui-input allownumericwithdecimal out_at_over','id'=>'b_at_over_'.$team_b_fall_wkt_ing)) !!}</td>
				<td></td>
				<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
			<?php $team_b_fall_wkt_ing++;?>
			@endif
			@endforeach
		@else
			<tr class="team_b_fall_ing_row">
				<td>{!! Form::text('b_wicket_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'b_wicket_1')) !!}</td>
				<td>{!! Form::select('b_wkt_player_1',$team_b,null,array('class'=>'gui-input b_scnd_fal_wkt','id'=>'b_wkt_player_second_ing_1')) !!}</td>
				<td>{!! Form::text('b_at_runs_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'b_at_runs_1')) !!}</td>
				<td>{!! Form::text('b_at_over_1', null, array('class'=>'gui-input allownumericwithdecimal out_at_over','id'=>'b_at_over_1')) !!}</td>
				<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
		@endif

		</tbody>

	</table>
	</div>
	 <a id="b_fall_wkt_ing" onclick="fall_of_wkts('b','second',<?php echo $i=$team_b_fall_wkt_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>
	<!-- *********Fall Of Wickets ************* -->

<!--********* TEAM B  End **************!-->



	      <center>
     	<ul class="list-inline sportsjun-forms">
            <li>
			@if($isValidUser)
                <button type="submit" class="button btn-primary" onclick="saveIng('scnd_ing_click');"><i class="fa fa-floppy-o"></i> Save</button>
			@endif
            </li>
	<input type="hidden" id="cricketsecond_form_data" value="">		
			
	 <input type="hidden" name="a_player_count" value="{{ (count($team_a_secnd_ing_array)>0)?count($team_a_secnd_ing_array):1 }}" id="a_player_count_ing">
	 <input type="hidden" name="a_bowler_count" value="{{ (count($team_a_scnd_ing_bowling_array)>0)?count($team_a_scnd_ing_bowling_array):1 }}" id="a_bowler_count_ing">
	 <input type="hidden" name="b_player_count" value="{{ (count($team_b_secnd_ing_array)>0)?count($team_b_secnd_ing_array):1 }}" id="b_player_count_ing">
	 <input type="hidden" name="b_bowler_count" value="{{ (count($team_b_scnd_ing_bowling_array)>0)?count($team_b_scnd_ing_bowling_array):1 }}" id="b_bowler_count_ing">
	 <input type="hidden" name="tournament_id" value="{{ $match_data[0]['tournament_id'] }}" id="player_count">
	 <input type="hidden" name="team_a_id" value="{{ $secondIngFstBatId }}" id="team_a_ids">
	 <input type="hidden" name="team_b_id" value="{{ $secondIngsecondBatId }}" id="team_b_ids">
	 <input type="hidden" name="match_id" value="{{ $match_data[0]['id'] }}" id="player_count">
	 <input type="hidden" name="match_type" value="{{ $match_data[0]['match_type'] }}" id="player_count">
	 <input type="hidden" name="team_b_name" value="{{ $team_b_name }}" id="team_b_names">
	 <input type="hidden" name="team_a_name" value="{{ $team_a_name }}" id="team_a_names">
	 <input type="hidden" name="inning" value="second" id="inning">
	 <input type="hidden" name="a_fall_of_count" value="{{ $a_bat_second_ing }}" id="a_fall_of_count_ing">
	 <input type="hidden" name="b_fall_of_count" value="{{ $team_b_fall_wkt_ing }}" id="b_fall_of_count_ing">
	 <input type="hidden" id="winner_team_id" name="winner_team_id" class="winner_team_id" value="">

        <?php 
                foreach ([$secondIngFstBatId,$secondIngsecondBatId] as $teamStat_team_id)
                {
                        foreach (['first','second'] as $teamStat_innings_name)
                        {
                                foreach (['score','wickets','overs'] as $teamStat_inning_stat_name)
                                {
        ?>
        <input type="hidden" class="form_team_stat_readonly" name="<?php echo $teamStat_innings_name ?>_inning[<?php echo $teamStat_team_id ?>][<?php echo $teamStat_inning_stat_name ?>]">
        <?php                   }
                        }
                }
        ?>
                
	 <input type="hidden" id="str_deleted_ids" name="deleted_ids" value="">
	 <input type="hidden" id="hidden_match_result" name="hid_match_result" value="">
{!!Form::close()!!}
 <input type="hidden" name="m" value="{{ (!empty($team_a_secnd_ing_array))?$a_bat_second_ing:2 }}" id="m">
 <input type="hidden" name="n" value="{{ (!empty($team_a_scnd_ing_bowling_array))?$team_a_secnd_bat_ing:2 }}" id="n">
 <input type="hidden" name="o" value="{{ (!empty($team_b_secnd_ing_array))?$team_b_bat_scnd_ing:2 }}" id="o">
 <input type="hidden" name="p" value="{{ (!empty($team_b_scnd_ing_bowling_array))?$b_bowl_second_ing:2 }}" id="p">
 <input type="hidden" name="y" value="{{ (!empty($team_wise_match_details[$match_data[0]['a_id']]['second']) && $a_keycount_scnd_ing>0 )?$fall_of_a_second_ing:2 }}" id="y">
 <input type="hidden" name="t" value="{{ (!empty($team_wise_match_details[$match_data[0]['b_id']]['second']) && $b_keycount_scnd_ing>0 )?$team_b_fall_wkt_ing:2 }}" id="t">
<script>
checkDuplicatePlayer('a_player_ing');
checkDuplicatePlayer('b_player_ing');
checkDuplicatePlayer('b_bowler_ing');
checkDuplicatePlayer('a_bowler_ing');
checkDuplicatePlayer('b_scnd_fal_wkt');
checkDuplicatePlayer('a_scnd_fal_wkt');
function batsman_strikeratecalculator(runs,balls,strikerate)
{
	var txtFirstNumberValue = $('#'+runs).val();
	var txtSecondNumberValue = $('#'+balls).val();
	var result = (parseInt(txtFirstNumberValue) * 100) / parseInt(txtSecondNumberValue);//strike rate calculation
	if(parseInt(txtSecondNumberValue)>0)
	{
		if (!isNaN(result)) {
			$('#'+strikerate).val(result.toFixed(2));
		}
	}
	else{
		$('#'+strikerate).val('0.00');
	}
}
function bowler_economycalculator(runs_conceded,overs,economy)
{
	var given_runs = $('#'+runs_conceded).val();
	var overs_bowled = $('#'+overs).val();
	var eco = parseInt(given_runs) / parseInt(overs_bowled);//strike rate calculation
	if(parseInt(overs_bowled)>0)
	{
		if (!isNaN(eco)) {
			$('#'+economy).val(eco.toFixed(2));
		}
	}
	else{
		$('#'+economy).val('0.00');
	}
}
allownumericwithdecimal();
function allownumericwithdecimal()
{
	 $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if (event.which != 08 && (event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
	});
}
var bating_team = $( "#teams option:selected" ).text();
var bowling_team = $('#teams option:not(:selected)').text();
$("#second_team_a_batting").text(bating_team+' Bating');
$("#second_team_b_bowling").text(bowling_team+' Bowling');
$("#second_team_b_batting").text(bowling_team+' Bating');
$("#second_team_a_bowling").text(bating_team+' Bowling');
$("#team_a_scnd_extras").text(bating_team+' Extras');
$("#team_b_scnd_extras").text(bowling_team+' Extras');

var is_valid_for_changes= '{{ !(!empty($team_a_secnd_ing_array) || !empty($team_b_secnd_ing_array) || !empty($team_a_scnd_ing_bowling_array) || !empty($team_b_scnd_ing_bowling_array))?1:0 }}';
if(is_valid_for_change==0)
{
	$("#teams").prop('disabled',true);
}
function getTeamNames()
{
	if(is_valid_for_changes==0)
	{
		//alert('Already Score Entered.');
		 $.alert({
            title: 'Alert!',
            content: 'Already Score Entered.'
        });
		return false;
	}
	var team_a_Id = $('#teams option:selected').data('status');//get select box option attribute value
	var team_b_Id = $('#teams option:not(:selected)').data('status');//get select box option attribute value

	$("#team_a_ids").val(team_a_Id);
	$("#team_b_ids").val(team_b_Id);

	var batting_team = $( "#teams option:selected" ).text();
	var bowlng_team = $('#teams option:not(:selected)').text();

	$("#team_a_names").val(batting_team);
	$("#team_b_names").val(bowlng_team);

	$("#second_team_a_batting").text(batting_team+' Innings');
	$("#second_team_b_bowling").text(bowlng_team+' Bowling');
	$("#second_team_b_batting").text(bowlng_team+' Innings');
	$("#second_team_a_bowling").text(batting_team+' Bowling');
	var player_a_id = $( "#teams option:selected" ).val();
		$.ajax({
				url: "{{URL('match/getplayers')}}",
					type : 'GET',
					data : {'player_a_ids':player_a_id},
					dataType: 'json',
					success : function(response){
							var options = "<option value=''>Select Player</option>";
							$.each(response, function(key, value) {
							options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
							});
							$("#a_players_second_ing_1").html(options);
							$("#a_bowlers_second_ing_1").html(options);
                                                        $("#b_fielder_second_ing_1").html(options);
							$("#b_bowled_second_ing_1").html(options);
							$("#a_wkt_player_fst_ing_1").html(options);

					}
		});
var player_b_id = $( "#teams option:not(:selected)" ).val();
		$.ajax({
				url: "{{URL('match/getplayers')}}",
					type : 'GET',
					data : {'player_a_ids':player_b_id},
					dataType: 'json',
					success : function(response){
							var options = "<option value=''>Select Player</option>";
							$.each(response, function(key, value) {
							options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
							});
							$("#b_players_second_ing_1").html(options);
							$("#b_bowlers_second_ing_1").html(options);
							$("#b_wkt_player_second_ing_1").html(options);
							$("#a_bowled_second_ing_1").html(options);
							$("#a_fielder_second_ing_1").html(options);

					}
		});
}
var count=1
var get_player_count='{{ (!empty($team_a_secnd_ing_array))?count($team_a_secnd_ing_array):1}}';
var get_b_bowler_count='{{ (!empty($team_b_scnd_ing_bowling_array))?count($team_b_scnd_ing_bowling_array):1 }}';
var get_b_bating_count='{{ (!empty($team_b_secnd_ing_array))?count($team_b_secnd_ing_array):1 }}';
var get_a_bowler_count = '{{ (!empty($team_a_scnd_ing_bowling_array))?count($team_a_scnd_ing_bowling_array):1}}';
function second_innings_getPlayerTr(m)
{
	var get_bat_countt= $('[class ^= "team_a_batting_open_row"]').size();
	var team_cnt = '{{ $team_a_count}}';
	if(get_bat_countt >= team_cnt)
	{
		//alert('Players Exceeded.');
		$.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
        });
		return false;
	}
	get_player_count++;
	$('#a_player_count_ing').val(get_player_count);
	var m=$('#m').val();


	var newContent = "<tr class='team_a_batting_open_row'><td><select  class='gui-input a_player_ing' name='a_player_"+m+"' id='a_players_second_ing_"+m+"'><option value=''>Select Player</option></select></td>"+
					<!--"<td><input type='text' class='gui-input' name='a_outas_"+m+"' /></td>"+-->
					"<td><select  class='gui-input team_a_scnd_ing_wkt' name='a_outas_"+m+"' id='a_outas_second_ing_"+m+"'><option value=''>Select Out As</option></select></td>"+
					"<td><select  class='gui-input' name='a_bowled_"+m+"' id='a_bowled_second_ing_"+m+"'><option value=''>Select Bowler</option></select><span id='a_ingbowlershow_"+m+"' style='display:none;'>--</span></td>"+
					"<td><select  class='gui-input' name='a_fielder_"+m+"' id='a_fielder_second_ing_"+m+"'><option value=''>Select Fielder</option></select><span id='a_ingfieldershow_"+m+"' style='display:none;'>--</span></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal team_a_scnd_ing_score runs_new' id='a_runs_ing_"+m+"' name='a_runs_"+m+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal'  id='a_balls_ing_"+m+"' name='a_balls_"+m+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal' name='a_fours_"+m+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal' name='a_sixes_"+m+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal strike_new' readonly id='a_strik_rate_ing_"+m+"' name='a_strik_rate_"+m+"' /></td><td></td>"+
					"</tr>";

					$("#two_player_tr_a").append(newContent);
					//var player_a_ids = "{{ $match_data[0]['player_a_ids']}}";
					var player_a_ids = $( "#teams option:selected" ).val();
					$.ajax({
						url: "{{URL('match/getplayers')}}",
							type : 'GET',
							data : {'player_a_ids':player_a_ids},
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Player</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
									});
									var val = m-1;
									$("#a_players_second_ing_"+val).html(options);

							}
					});

					//get bowler ids
					var opposiet_team_ids = $( "#teams option:not(:selected)" ).val();
					$.ajax({
						url: "{{URL('match/getplayers')}}",
							type : 'GET',
							data : {'player_a_ids':opposiet_team_ids},
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Player</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
									});
									var val = m-1;
									$("#a_bowled_second_ing_"+val).html(options);
									$("#a_fielder_second_ing_"+val).html(options);

							}
					});

					//get our
					$.ajax({
						url: "{{URL('match/get_outas_enum')}}",
							type : 'GET',
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Out As</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + key + "'>" + value + "</option>";
									});
									var val = m-1;
									$("#a_outas_second_ing_"+val).html(options);

							}
					});

					//strike rate
					$("#a_runs_ing_"+m).on("keyup", function() {
						batsman_strikeratecalculator("a_runs_ing_"+(m-1),"a_balls_ing_"+(m-1),"a_strik_rate_ing_"+(m-1));
					});
					$("#a_balls_ing_"+m).on("keyup", function() {
						batsman_strikeratecalculator("a_runs_ing_"+(m-1),"a_balls_ing_"+(m-1),"a_strik_rate_ing_"+(m-1));
					});


	m++;
	$('#m').val(m);
			checkDuplicatePlayer('a_player_ing');
			team_ing_score_calculator('team_a_scnd_ing_score','a','score');
			allownumericwithdecimal();
			teamSecondWickets('team_a_scnd_ing_wkt','a');


}
function second_innings_getBowlerTr(n)
{

		var get_a_bowl_countt= $('[class ^= "team_a_bowling_open_row"]').size();
		var team_cnt = '{{ $team_a_count}}';
		if(get_a_bowl_countt >= team_cnt)
		{
			//alert('Players Exceeded.');
			$.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
			});
			return false;
		}
	get_a_bowler_count++;
	$('#a_bowler_count_ing').val(get_a_bowler_count);
	var n=$('#n').val();

	var bowlerContent = "<tr class='team_a_bowling_open_row'>"+
				"<td><select class='a_bowler_ing' name='a_bowler_"+n+"' id='a_bowlers_second_ing_"+n+"'><option value=''>Select Player</option></select></td>"+
				"<td><input type='text' class='allownumericwithdecimal team_a_scnd_ing_overs' id='a_bowler_overs_ing_"+n+"' name='a_bowler_overs_"+n+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal team_a_scnd_ing_maidens' id='a_bowler_maidens_ing_"+n+"' name='a_bowler_maidens_"+n+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal'  id='a_bowler_runs_ing_"+n+"' name='a_bowler_runs_"+n+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal' name='a_bowler_wkts_"+n+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal' readonly id='a_ecomony_ing_"+n+"' name='a_ecomony_"+n+"' /></td>"+"<td><input type='text' class='allownumericwithdecimal a_wides_ing'  id='a_bowler_wide_ing_"+n+"' name='a_bowler_wide_"+n+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal a_noballs_ing' id='a_bowler_noball_ing_"+n+"' name='a_bowler_noball_"+n+"' /></td>"+
				"<td></td><td></td><td></td><td></td>"+
			"</tr>";
			$("#two_bowler_tr_a").append(bowlerContent);
			//var player_a_ids = "{{ $match_data[0]['player_a_ids']}}";
			var player_a_ids = $( "#teams option:selected" ).val();
					$.ajax({
						url: "{{URL('match/getplayers')}}",
							type : 'GET',
							data : {'player_a_ids':player_a_ids},
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Player</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
									});
									var val = n-1;
									$("#a_bowlers_second_ing_"+val).html(options);

							}
					});

			//economy rate
			$("#a_bowler_overs_ing_"+n).on("keyup", function() {
				bowler_economycalculator("a_bowler_runs_ing_"+(n-1),"a_bowler_overs_ing_"+(n-1),"a_ecomony_ing_"+(n-1));
			});
			$("#a_bowler_runs_ing_"+n).on("keyup", function() {
				bowler_economycalculator("a_bowler_runs_ing_"+(n-1),"a_bowler_overs_ing_"+(n-1),"a_ecomony_ing_"+(n-1));
			});

	n++;
	$('#n').val(n);
		checkDuplicatePlayer('a_bowler_ing');
		team_ing_score_calculator('team_a_scnd_ing_overs','b','over');
		ing_wides('a_wides_ing','b');
	ing_noballs('a_noballs_ing','b');
		allownumericwithdecimal();
	var team_cnt = '{{ $team_a_count }}';

}
function second_innings_getteamBPlayerTr(o)
{
	var get_b_bat_countt= $('[class ^= "team_b_batting_open_row"]').size();
	var team_cnt = '{{ $team_b_count}}';
	if(get_b_bat_countt >= team_cnt)
	{
		$.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
			});
		return false;
	}
	get_b_bating_count++;
	$('#b_player_count_ing').val(get_b_bating_count);
	var o=$('#o').val();

	var newContent = "<tr class='team_b_batting_open_row'><td><select class='b_player_ing' name='b_player_"+o+"' id='b_players_second_ing_"+o+"'><option value=''>Select Player</option></select></td>"+
					<!--"<td><input type='text' class='gui-input' name='b_outas_"+o+"' /></td>"+-->
					"<td><select  class='gui-input team_b_scnd_ing_wkt' name='b_outas_"+o+"' id='b_outas_second_ing_"+o+"'><option value=''>Select Out As</option></select></td>"+
                                        "<td><select  class='gui-input' name='b_fielder_"+o+"' id='b_fielder_second_ing_"+o+"'><option value=''>Select Fielder</option></select><span id='b_ingfieldershow_"+o+"' style='display:none;'>--</span></td>"+
					"<td><select  class='gui-input' name='b_bowled_"+o+"' id='b_bowled_second_ing_"+o+"'><option value=''>Select Bowler</option></select><span id='b_ingbowlershow_"+o+"' style='display:none;'>--</span></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal team_b_scnd_ing_score runs_new' id='b_runs_ing_"+o+"' name='b_runs_"+o+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal' id='b_balls_ing_"+o+"'  name='b_balls_"+o+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal' name='b_fours_"+o+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal' name='b_sixes_"+o+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal strike_new' readonly id='b_strik_rate_ing_"+o+"' name='b_strik_rate_"+o+"' /></td><td></td>"+
					"</tr>";
					$("#two_player_tr_b").append(newContent);
//var player_a_ids = "{{ $match_data[0]['player_b_ids']}}";
var player_a_ids = $( "#teams option:not(:selected)" ).val();
					$.ajax({
						url: "{{URL('match/getplayers')}}",
							type : 'GET',
							data : {'player_a_ids':player_a_ids},
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Player</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
									});
									var val = o-1;
									$("#b_players_second_ing_"+val).html(options);

							}
					});
			//get bowler ids
					var opposiet_team_ids = $(  "#teams option:selected"  ).val();
					$.ajax({
						url: "{{URL('match/getplayers')}}",
							type : 'GET',
							data : {'player_a_ids':opposiet_team_ids},
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Player</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
									});
									var val = o-1;
                                                                        $("#b_fielder_second_ing_"+val).html(options);
									$("#b_bowled_second_ing_"+val).html(options);

							}
					});

					//get our
					$.ajax({
						url: "{{URL('match/get_outas_enum')}}",
							type : 'GET',
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Out As</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + key + "'>" + value + "</option>";
									});
									var val = o-1;
									$("#b_outas_second_ing_"+val).html(options);

							}
					});
					//strike rate
					$("#b_runs_ing_"+o).on("keyup", function() {
						batsman_strikeratecalculator("b_runs_ing_"+(o-1),"b_balls_ing_"+(o-1),"b_strik_rate_ing_"+(o-1));
					});
					$("#b_balls_ing_"+o).on("keyup", function() {
						batsman_strikeratecalculator("b_runs_ing_"+(o-1),"b_balls_ing_"+(o-1),"b_strik_rate_ing_"+(o-1));
					});
	o++;
	$('#o').val(o);
	checkDuplicatePlayer('b_player_ing');
	teamSecondWickets('team_b_scnd_ing_wkt','b');
	team_ing_score_calculator('team_b_scnd_ing_score','b','score');
	allownumericwithdecimal();

}
function second_innings_getTeambBowlerTr(p)
{
	var get_bowler_countt= $('[class ^= "team_b_bowling_open_row"]').size();
	var team_cnt = '{{ $team_b_count}}';
	if(get_bowler_countt >= team_cnt)
	{
				$.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
			});
		return false;
	}
	get_b_bowler_count++;
	$('#b_bowler_count_ing').val(get_b_bowler_count);
	var p=$('#p').val();

	var bowlerContent = "<tr class='team_b_bowling_open_row'>"+
				"<td><select class='b_bowler_ing' name='b_bowler_"+p+"' id='b_bowlers_second_ing_"+p+"'><option value=''>Select Player</option></select></td>"+
				"<td><input type='text' class='allownumericwithdecimal team_b_scnd_ing_overs'  id='b_bowler_overs_ing_"+p+"' name='b_bowler_overs_"+p+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal team_b_scnd_ing_maidens'  id='b_bowler_maidens_ing_"+p+"' name='b_bowler_maidens_"+p+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal' id='b_bowler_runs_ing_"+p+"'  name='b_bowler_runs_"+p+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal' name='b_bowler_wkts_"+p+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal' readonly id='b_ecomony_ing_"+p+"' name='b_ecomony_"+p+"' /></td>"+"<td><input type='text' class='allownumericwithdecimal b_wides_ing' id='b_bowler_wide_ing_"+p+"' name='b_bowler_wide_"+p+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal b_noballs_ing' id='b_bowler_noball_ing_"+p+"' name='b_bowler_noball_"+p+"' /></td>"+
				"<td></td><td></td><td></td><td></td>"+
			"</tr>";
			$("#two_bowler_tr_b").append(bowlerContent);
			//var player_a_ids = "{{ $match_data[0]['player_b_ids']}}";
			var player_a_ids = $( "#teams option:not(:selected)" ).val();
					$.ajax({
						url: "{{URL('match/getplayers')}}",
							type : 'GET',
							data : {'player_a_ids':player_a_ids},
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Player</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
									});
									var val = p-1;
									$("#b_bowlers_second_ing_"+val).html(options);

							}
					});
			//economy rate
			$("#b_bowler_overs_ing_"+p).on("keyup", function() {
				bowler_economycalculator("b_bowler_runs_ing_"+(p-1),"b_bowler_overs_ing_"+(p-1),"b_ecomony_ing_"+(p-1));
			});
			$("#b_bowler_runs_ing_"+p).on("keyup", function() {
				bowler_economycalculator("b_bowler_runs_ing_"+(p-1),"b_bowler_overs_ing_"+(p-1),"b_ecomony_ing_"+(p-1));
			});

	p++;
	$('#p').val(p);
	checkDuplicatePlayer('b_bowler_ing');
	ing_wides('b_wides_ing','a');
	ing_noballs('b_noballs_ing','a');
	allownumericwithdecimal();
	team_ing_score_calculator('team_b_scnd_ing_overs','a','over');

}
//check duplicate players selected
function checkDuplicatePlayer(select_class)
{
	 $('.'+select_class).on('change',function(){
	// Checking Duplicate players
		var pid=[];
		$('.'+select_class).each(function(){
            if(this.value != ''){
            pid.push(this.value);
		}

        });
        b = {};
		for (var i = 0; i < pid.length; i++) {
			b[pid[i]] = pid[i];
		}
		c = [];
		for (var key in b) {
			c.push(key);
		}
		if(pid.length!=c.length){

			//alert("Duplicate Player Selected.");
			$.alert({
            title: 'Alert!',
            content: 'Duplicate Player Selected.'
			});
			$(this).val('');

		}
	 });
}
var team_a_fall_wkts_ing = "{{ ($a_keycount_scnd_ing>0)?$a_keycount_scnd_ing:1 }}";
var team_b_fall_wkts_ing = "{{ ($b_keycount_scnd_ing>0)?$b_keycount_scnd_ing:1 }}";
//fall of wikects
function fall_of_wkts(team,ing,y)
{
	if(team=='a')
	{
		var get_a_fall_countt= $('[class ^= "team_a_fall_ing_row"]').size();

		var team_cnt = '{{ $team_a_count}}';

		if(get_a_fall_countt >= team_cnt)
		{
			//alert('Players Exceeded.');
						$.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
			});
			return false;
		}
		team_a_fall_wkts_ing++;
		$('#a_fall_of_count_ing').val(team_a_fall_wkts_ing);
		var y=$('#y').val();
	}
	else
	{
		var get_b_fall_countt= $('[class ^= "team_b_fall_ing_row"]').size();
		var team_cnt = '{{ $team_b_count}}';

		if(get_b_fall_countt >= team_cnt)
		{
			$.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
			});
			return false;
		}
		team_b_fall_wkts_ing++;
		$('#b_fall_of_count_ing').val(team_b_fall_wkts_ing);
		var y=$('#t').val();
	}


	var fall_of_cnt = "<tr class='team_"+team+"_fall_ing_row'>"+

				"<td><input type='text' class='allownumericwithdecimal'  name='"+team+"_wicket_"+y+"' /></td>"+
				"<td><select  class='"+team+"_scnd_fal_wkt' name='"+team+"_wkt_player_"+y+"' id='"+team+"_wkt_player_"+ing+"_ing_"+y+"'><option value=''>Select Player</option></select></td>"+
				"<td><input type='text' class='allownumericwithdecimal' name='"+team+"_at_runs_"+y+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal' name='"+team+"_at_over_"+y+"' /></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>"+
			"</tr>";
			$("#fall_of_wkt_"+ing+"_"+team).append(fall_of_cnt);
			//var player_a_ids = "{{ $match_data[0]['player_b_ids']}}";
			if(team=='a')
			{
				var player_a_ids = $( "#teams option:selected" ).val();
			}else
			{
				var player_a_ids = $( "#teams option:not(:selected)" ).val();
			}

					$.ajax({
						url: "{{URL('match/getplayers')}}",
							type : 'GET',
							data : {'player_a_ids':player_a_ids},
							dataType: 'json',
							success : function(response){
									var options = "<option value=''>Select Player</option>";
									$.each(response, function(key, value) {
									options += "<option value='" + value['id'] + "'>" + value['name'] + "</option>";
									});
									var val = y-1;
									$("#"+team+"_wkt_player_"+ing+"_ing_"+val).html(options);

							}
					});

	y++;
	if(team=='a')
	{
		$('#y').val(y);
		
		checkDuplicatePlayer('a_scnd_fal_wkt');

	}
	else{
		$('#t').val(y);
		checkDuplicatePlayer('b_scnd_fal_wkt');
	}
	allownumericwithdecimal();
}
//delete row
var deleted_ids=',';
function deleteRow(team,status,id,value)
{
				$.confirm({
			title: 'Confirmation',
			content: "Are you sure you want to delete this Player?",
			confirm: function() {
	var row_count_val = $('[id ^= "team_'+team+'_'+status+'_open_row"]').size();
	if(team=='a' && status=='batting')
	{
		row_count_val--;
		//$('#a_player_count_ing').val(row_count_val);

		team_a_del_score = $('#a_runs_ing_'+value).val();
		fst_ing_score = $('#scnd_ing_a_score').val();
		$('#scnd_ing_a_score').val(fst_ing_score - team_a_del_score);//deletes row run remove from total score runs

		//delete wickets
		team_a_out_as = $('#a_outas_second_ing_'+value).val();
		out_count = $('#scnd_ing_a_wkts').val();
		if(team_a_out_as!='' && team_a_out_as!='not_out')
		{
			out_count--;
		}
		$('#scnd_ing_a_wkts').val(out_count);

	}else if(team=='a' && status=='bowling')
	{
		row_count_val--;
		//$('#a_bowler_count_ing').val(row_count_val);

		team_a_noballs = $('#a_bowler_noball_ing_'+value).val();
		team_a_wides = $('#a_bowler_wide_ing_'+value).val();
		team_b_wide = $('#team_b_ing_wide').val();
		team_b_noball = $('#team_b_ing_noball').val();
		$('#team_b_ing_wide').val(team_b_wide - team_a_wides);
		$('#team_b_ing_noball').val(team_b_noball - team_a_noballs);

		var a_extra = 0;
		$('.b_scnd_ing_extras').each(function() {
				a_extra += Number($(this).val());
			});
		$('#team_b_ing_extras').val(a_extra);

		a_bowler_overs_ing_ = $('#a_bowler_overs_ing_'+value).val();
		scnd_ing_b_over = $('#scnd_ing_b_over').val();
		$('#scnd_ing_b_over').val(scnd_ing_b_over - a_bowler_overs_ing_);//delete overs

	}else if(team=='b' && status=='batting')
	{
		row_count_val--;
		//$('#b_player_count_ing').val(row_count_val);

		team_b_del_score = $('#b_runs_ing_'+value).val();
		fst_ing_b_score = $('#scnd_ing_b_score').val();
		$('#scnd_ing_b_score').val(fst_ing_b_score - team_b_del_score);//deletes row run remove from total score runs

		//delete wickets
		team_b_out_as = $('#b_outas_second_ing_'+value).val();
		out_count = $('#scnd_ing_b_wkts').val();
		if(team_b_out_as!='' && team_b_out_as!='not_out')
		{
			out_count--;
		}
		//alert(out_count);
		$('#scnd_ing_b_wkts').val(out_count);

	}else if(team=='b' && status=='bowling')
	{
		row_count_val--;
		//$('#b_bowler_count_ing').val(row_count_val);


		team_b_noballs = $('#b_bowler_noball_ing_'+value).val();
		team_b_wides = $('#b_bowler_wide_ing_'+value).val();
		team_a_wide = $('#team_a_ing_wide').val();
		team_a_noball = $('#team_a_ing_noball').val();
		$('#team_a_ing_wide').val(team_a_wide - team_b_wides);
		$('#team_a_ing_noball').val(team_a_noball - team_b_noballs);

				var a_extra = 0;
		$('.a_scnd_ing_extras').each(function() {
				a_extra += Number($(this).val());
			});
		$('#team_a_ing_tot_extras').val(a_extra);

		team_b_del_overs = $('#b_bowler_overs_ing_'+value).val();
		scnd_ing_a_over = $('#scnd_ing_a_over').val();
		$('#scnd_ing_a_over').val(scnd_ing_a_over - team_b_del_overs);//delete overs
	}
	deleted_ids = deleted_ids+id+',';
	$('#str_deleted_ids').val(deleted_ids);

	$('#team_'+team+'_'+status+'_'+id).remove();
		},
			cancel: function() {
				// nothing to do
			}
		});
}

//team a score
$('.team_a_scnd_ing_score').keyup(function () {
	team_a_scnd_ing_score = 0;
	extras = 0;
$('.team_a_scnd_ing_score').each(function() {
			team_a_scnd_ing_score += Number($(this).val());
		});
			$('.a_scnd_ing_extras').each(function() {
					extras += Number($(this).val());
				});
		$('#scnd_ing_a_score').val(team_a_scnd_ing_score + extras);
});
//team b score
$('.team_b_scnd_ing_score').keyup(function () {
	team_b_scnd_ing_score = 0;
		extras = 0;
$('.team_b_scnd_ing_score').each(function() {
			team_b_scnd_ing_score += Number($(this).val());
		});
					$('.b_scnd_ing_extras').each(function() {
					extras += Number($(this).val());
				});
		$('#scnd_ing_b_score').val(team_b_scnd_ing_score + extras);
});

//team b bowler overs
$('.team_b_scnd_ing_overs').keyup(function () {
	team_b_scnd_ing_overs = 0;
	before_decimal = 0;
	after_decimal = 0;
$('.team_b_scnd_ing_overs').each(function() {
			//team_b_scnd_ing_overs += Number($(this).val());
			before_decimal += Number(String($(this).val()).split('.')[0] || 0);
			after_decimal += Number(String($(this).val()).split('.')[1] || 0);
		});
		beforeDecimal = parseInt(((before_decimal*6) + after_decimal)/6);
		afterDecimal = ((before_decimal*6) + after_decimal)%6;
		team_b_scnd_ing_overs = beforeDecimal+'.'+afterDecimal;
		$('#scnd_ing_a_over').val(team_b_scnd_ing_overs);
});
//team a bowler overs
$('.team_a_scnd_ing_overs').keyup(function () {
	team_a_scnd_ing_overs = 0;
	before_decimal = 0;
	after_decimal = 0;
$('.team_a_scnd_ing_overs').each(function() {
			//team_a_scnd_ing_overs += Number($(this).val());
			before_decimal += Number(String($(this).val()).split('.')[0] || 0);
			after_decimal += Number(String($(this).val()).split('.')[1] || 0);
		});
		beforeDecimal = parseInt(((before_decimal*6) + after_decimal)/6);
		afterDecimal = ((before_decimal*6) + after_decimal)%6;
		team_a_scnd_ing_overs = beforeDecimal+'.'+afterDecimal;
		$('#scnd_ing_b_over').val(team_a_scnd_ing_overs);
});
//team a wkt
isFielderDisplay=1;
$('.team_a_scnd_ing_wkt').each(function() {
			BowlerFielderDisplayScndIng('a',isFielderDisplay);
			isFielderDisplay++;
		});
$('.team_a_scnd_ing_wkt').on('change',function(){
	team_a_scnd_ing_wkt = 0;
	isFielderDisplay = 1;
$('.team_a_scnd_ing_wkt').each(function() {
			if($(this).val()!='' && $(this).val()!='not_out')
			{
				team_a_scnd_ing_wkt ++;
			}
			BowlerFielderDisplayScndIng('a',isFielderDisplay);
			isFielderDisplay++;
		});
		$('#scnd_ing_a_wkts').val(team_a_scnd_ing_wkt);
});
isFielderDisplay=1;
$('.team_b_scnd_ing_wkt').each(function() {
			BowlerFielderDisplayScndIng('b',isFielderDisplay);
			isFielderDisplay++;
});
//team b wkt
$('.team_b_scnd_ing_wkt').on('change',function(){
	team_b_scnd_ing_wkt = 0;
	isFielderDisplay = 1;
$('.team_b_scnd_ing_wkt').each(function() {
			if($(this).val()!='' && $(this).val()!='not_out')
			{
				team_b_scnd_ing_wkt ++;
			}
			BowlerFielderDisplayScndIng('b',isFielderDisplay);
			isFielderDisplay++;
		});
		$('#scnd_ing_b_wkts').val(team_b_scnd_ing_wkt);
});
//team score and over calucation
function team_ing_score_calculator(classname,team,status)
{
	$('.'+classname).keyup(function () {
	team_score = 0;
	extras=0;
	before_decimal = 0;
	after_decimal = 0;
	$('.'+classname).each(function() {
				team_score += Number($(this).val());
				if(status=='over')
				{
					before_decimal += Number(String($(this).val()).split('.')[0] || 0);
					after_decimal += Number(String($(this).val()).split('.')[1] || 0);
				}
			});
			if(status=='over')
			{
				beforeDecimal = parseInt(((before_decimal*6) + after_decimal)/6);
				afterDecimal = ((before_decimal*6) + after_decimal)%6;
				team_score = beforeDecimal+'.'+afterDecimal;
			}
			if(status=='score')
			{
				$('.'+team+'_scnd_ing_extras').each(function() {
					extras += Number($(this).val());
				});
				team_score = team_score + extras;
			}
				$('#scnd_ing_'+team+'_'+status).val(team_score);


	});
}
//team wickets
function teamSecondWickets(name,team)
{
	$('.'+name).on('change',function(){
	team_a_wkts = 0;
	isFielderDisplay=1;
	$('.'+name).each(function() {
				if($(this).val()!='')
				{
					team_a_wkts ++;
				}
				BowlerFielderDisplayScndIng(team,isFielderDisplay);
				isFielderDisplay++;
			});
			$('#scnd_ing_'+team+'_wkts').val(team_a_wkts);
	});
}
function saveIng(status)
{
	SJ.SCORECARD.initTeamStats();
	$('#hidden_match_result').val($('#match_result').val());
	
	if(status=='scnd_ing_click')
	{
		$("#secondting").ajaxForm({
			url: base_url+'/match/insertCricketScoreCard', 
			type: 'post',
			success: function(res) {
				save('');
				$("#firsting").ajaxSubmit({
					url: base_url+'/match/insertCricketScoreCard', 
					type: 'post',
                                        success: function(res2) {
                                                SJ.GLOBAL.reload();
                                        }
				});
			}
		});
	}
}
var a_extra = 0;
$('.a_scnd_ing_extras').each(function() {
					a_extra += Number($(this).val());
	});
$('#team_a_ing_tot_extras').val(a_extra);
//team extras
	$('.a_scnd_ing_extras').keyup(function () {
	extras = 0;
	total = 0;
	$('.a_scnd_ing_extras').each(function() {
					extras += Number($(this).val());
			});
		$('.team_a_scnd_ing_score').each(function() {
					total += Number($(this).val());
			});

			$('#team_a_ing_tot_extras').val(extras);
			$('#scnd_ing_a_score').val(extras + total);
	});
	var b_extra = 0;
$('.b_scnd_ing_extras').each(function() {
					b_extra += Number($(this).val());
	});
$('#team_b_ing_extras').val(b_extra);
//team extras
	$('.b_scnd_ing_extras').keyup(function () {
	extras = 0;
	total = 0;
	$('.b_scnd_ing_extras').each(function() {
					extras += Number($(this).val());
			});
		$('.team_b_scnd_ing_score').each(function() {
					total += Number($(this).val());
			});

			$('#team_b_ing_extras').val(extras);
			$('#scnd_ing_b_score').val(extras + total);
	});
//team a wides
$('.b_wides_ing').keyup(function () {
	b_wides = 0;
$('.b_wides_ing').each(function() {
			b_wides += Number($(this).val());
		});
		$('#team_a_ing_wide').val(b_wides);

	var a_extra = 0;
		$('.a_scnd_ing_extras').each(function() {
			a_extra += Number($(this).val());
		});
$('#team_a_ing_tot_extras').val(a_extra);
total_runs_scored('team_a_ing_tot_extras','team_a_scnd_ing_score','a');
});

//team b wides
$('.a_wides_ing').keyup(function () {
	a_wides = 0;
$('.a_wides_ing').each(function() {
			a_wides += Number($(this).val());
		});
		$('#team_b_ing_wide').val(a_wides);
					var a_extra = 0;
		$('.b_scnd_ing_extras').each(function() {
			a_extra += Number($(this).val());
		});
$('#team_b_ing_extras').val(a_extra);
total_runs_scored('team_b_ing_extras','team_b_scnd_ing_score','b');
});
//team a no balls
$('.b_noballs_ing').keyup(function () {
	b_no_balls = 0;
$('.b_noballs_ing').each(function() {
			b_no_balls += Number($(this).val());
		});
		$('#team_a_ing_noball').val(b_no_balls);
			var a_extra = 0;
		$('.a_scnd_ing_extras').each(function() {
			a_extra += Number($(this).val());
		});
$('#team_a_ing_tot_extras').val(a_extra);
total_runs_scored('team_a_ing_tot_extras','team_a_scnd_ing_score','a');
});

//team b no balls
$('.a_noballs_ing').keyup(function () {
	a_no_balls = 0;
$('.a_noballs_ing').each(function() {
			a_no_balls += Number($(this).val());
		});
		$('#team_b_ing_noball').val(a_no_balls);
							var a_extra = 0;
		$('.b_scnd_ing_extras').each(function() {
			a_extra += Number($(this).val());
		});
$('#team_b_ing_extras').val(a_extra);
total_runs_scored('team_b_ing_extras','team_b_scnd_ing_score','b');
});
function ing_wides(classname,team)
{
	$('.'+classname).keyup(function () {
		wide_noball = 0;
		$('.'+classname).each(function() {
			wide_noball += Number($(this).val());
		});
		$('#team_'+team+'_ing_wide').val(wide_noball);
		var b_extra = 0;
		$('.'+team+'_scnd_ing_extras').each(function() {
				b_extra += Number($(this).val());
		});
		if(team=='a')
		{
			$('#team_'+team+'_ing_tot_extras').val(b_extra);
			total_runs_scored('team_'+team+'_ing_tot_extras','team_'+a+'_scnd_ing_score',team);
		}else
		{
			$('#team_'+team+'_ing_extras').val(b_extra);
			total_runs_scored('team_'+team+'_ing_extras','team_'+a+'_scnd_ing_score',team);
		}

	});
}
function ing_noballs(classname,team)
{
	$('.'+classname).keyup(function () {
		noball = 0;
		$('.'+classname).each(function() {
			noball += Number($(this).val());
		});
		$('#team_'+team+'_ing_noball').val(noball);
				var b_extra = 0;
		$('.'+team+'_scnd_ing_extras').each(function() {
				b_extra += Number($(this).val());
		});

		if(team=='a')
		{
			$('#team_'+team+'_ing_tot_extras').val(b_extra);
		}else
		{
			$('#team_'+team+'_ing_extras').val(b_extra);
		}
	});
}
function total_runs_scored(idname,classname,team)
{
	var extras = parseInt($('#'+idname).val());
	score=0;
	$('.'+classname).each(function() {
			score += Number($(this).val());
		});

	$('#scnd_ing_'+team+'_score').val(extras+parseInt(score));
}
function BowlerFielderDisplayScndIng(team,isFielderDisplay)
{
		out_as_value = $('#'+team+'_outas_second_ing_'+isFielderDisplay).val();
			if(out_as_value=='' || out_as_value=='caught' || out_as_value=='run_out' || out_as_value=='stumped')
			{
				$('#'+team+'_fielder_second_ing_'+isFielderDisplay).show();
				$('#'+team+'_ingfieldershow_'+isFielderDisplay).hide();
			}else
			{
				$('#'+team+'_fielder_second_ing_'+isFielderDisplay).hide();
				$('#'+team+'_ingfieldershow_'+isFielderDisplay).show();
			}
			if(out_as_value=='not_out' || out_as_value=='handled_ball' || out_as_value=='obstructing_the_field' || out_as_value=='retired' || out_as_value=='run_out' || out_as_value=='timed_out')
			{
				$('#'+team+'_bowled_second_ing_'+isFielderDisplay).hide();
				$('#'+team+'_ingbowlershow_'+isFielderDisplay).show();
			}
			else
			{
				$('#'+team+'_bowled_second_ing_'+isFielderDisplay).show();
				$('#'+team+'_ingbowlershow_'+isFielderDisplay).hide();
			}
			
}
</script>
