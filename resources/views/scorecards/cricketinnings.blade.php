<!--Batting:<select name="team" id="team" onchange="getTeamName();">
		<option value="{{ $match_data[0]['player_a_ids'] }}" data-status="{{ $fstIngFstBatId }}" >{{ $team_a_name }}</option>
		<option value="{{ $match_data[0]['player_b_ids'] }}" data-status="{{ $fstIngsecondBatId }}">{{ $team_b_name }}</option>
		</select>
		-->
{!! Form::open(array('url' => 'match/insertCricketScoreCard', 'method' => 'POST','id'=>'firsting')) !!}
<!--********* TEAM A Start**************!-->
<h3 id='team_a_batting' class="team_bat table_head">{{ $team_a_name }} Innings</h3>
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
		<tbody id="player_tr_a">
		<?php $a_bat_fst_ing = 1; ?>
		@if(!empty($team_a_fst_ing_array) && count($team_a_fst_ing_array)>0)

			@foreach($team_a_fst_ing_array as $a_fst_inning)
			<tr id="team_a_bat_{{$a_fst_inning['id']}}" class="team_a_bat_open_row">
				<!--<td>{!! Form::select('a_player_'.$a_bat_fst_ing,$team_a,null,array('class'=>'gui-input a_player','id'=>'a_player_'.$a_bat_fst_ing)) !!}</td>
				<td>{!! Form::select('a_outas_'.$a_bat_fst_ing,$enum,null,array('class'=>'gui-input','id'=>'a_outas_'.$a_bat_fst_ing)) !!}</td>
				<td>{!! Form::select('a_bowled_'.$a_bat_fst_ing ,$team_b,null,array('class'=>'gui-input','id'=>'a_bowled_'.$a_bat_fst_ing)) !!}</td>
				<td>{!! Form::select('a_fielder_'.$a_bat_fst_ing,$team_b,null,array('class'=>'gui-input','id'=>'a_fielder_'.$a_bat_fst_ing)) !!}</td>-->

				<td><select name='a_player_{{ $a_bat_fst_ing }}' class='gui-input a_player' id='a_player_{{$a_bat_fst_ing}}'>
					@foreach($team_a as $a_key => $a_val)
					<option value="{{$a_key}}" <?php if (isset($a_fst_inning['user_id']) && $a_fst_inning['user_id']==$a_key) echo ' selected';?>>{{ $a_val }}</option>
					@endforeach
				</select>
				 <div id="demoBasic">
                                </div>
				</td>

				<td><select name='a_outas_{{ $a_bat_fst_ing }}' class='gui-input team_a_wkt' id='a_outas_{{$a_bat_fst_ing}}'>
					@foreach($enum as $enum_key => $enum_val)
					<option value="{{$enum_key}}" <?php if (isset($a_fst_inning['out_as']) && $a_fst_inning['out_as']==$enum_key) echo ' selected';?>>{{ $enum_val }}</option>
					@endforeach
				</select>
				</td>
                                
                                <td><select name='a_fielder_{{ $a_bat_fst_ing }}' class='gui-input' id='a_fielder_{{$a_bat_fst_ing}}'>
					@foreach($team_b as $b_player_key => $b_player_val)
					<option value="{{$b_player_key}}" <?php if (isset($a_fst_inning['fielder_id']) && $a_fst_inning['fielder_id']==$b_player_key) echo ' selected';?>>{{ $b_player_val }}</option>
					@endforeach
				</select>
				<span id="a_fildershow_{{ $a_bat_fst_ing }}" style="display:none;">{{'--'}}</span>
				</td>

				<td><select name='a_bowled_{{ $a_bat_fst_ing }}' class='gui-input' id='a_bowled_{{$a_bat_fst_ing}}'>
					@foreach($team_b as $b_key => $b_val)
					<option value="{{$b_key}}" <?php if (isset($a_fst_inning['bowled_id']) && $a_fst_inning['bowled_id']==$b_key) echo ' selected';?>>{{ $b_val }}</option>
					@endforeach
				</select>
				<span id="a_bowlershow_{{ $a_bat_fst_ing }}" style="display:none;">{{'--'}}</span>
				</td>

				<td>{!! Form::text('a_runs_'.$a_bat_fst_ing , (!empty($a_fst_inning['totalruns']))?$a_fst_inning['totalruns']:'', array('class'=>'gui-input allownumericwithdecimal team_a_score runs_new','id'=>'a_runs_'.$a_bat_fst_ing,'onkeyup'=>"strikeratecalculator('a_runs_$a_bat_fst_ing','a_balls_$a_bat_fst_ing','a_strik_rate_$a_bat_fst_ing');")) !!}</td>
				<td>{!! Form::text('a_balls_'.$a_bat_fst_ing, (!empty($a_fst_inning['balls_played']))?$a_fst_inning['balls_played']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_balls_'.$a_bat_fst_ing ,'onkeyup'=>"strikeratecalculator('a_runs_$a_bat_fst_ing','a_balls_$a_bat_fst_ing','a_strik_rate_$a_bat_fst_ing');")) !!}</td>
				<td>{!! Form::text('a_fours_'.$a_bat_fst_ing, (!empty($a_fst_inning['fours']))?$a_fst_inning['fours']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_fours_'.$a_bat_fst_ing)) !!}</td>
				<td>{!! Form::text('a_sixes_'.$a_bat_fst_ing, (!empty($a_fst_inning['sixes']))?$a_fst_inning['sixes']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_sixes_'.$a_bat_fst_ing)) !!}</td>
				<td>{!! Form::text('a_strik_rate_'.$a_bat_fst_ing, (!empty($a_fst_inning['strikerate']))?number_format($a_fst_inning['strikerate'],2):'', array('class'=>'gui-input allownumericwithdecimal strike_new runs_new','id'=>'a_strik_rate_'.$a_bat_fst_ing,'readonly')) !!}</td>
				<td><a href="#" onclick="delete_row('a','bat',{{$a_fst_inning['id']}},{{$a_bat_fst_ing}});" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></td>
			</tr>
			<?php $a_bat_fst_ing++; ?>
			@endforeach
		@else
			<tr class="team_a_bat_open_row">
				<td>{!! Form::select('a_player_1',$team_a,null,array('class'=>'gui-input a_player','id'=>'a_player_1')) !!}</td>
				<!--<td>{!! Form::text('a_outas_1', null, array('class'=>'gui-input','id'=>'a_outas_1')) !!}</td>-->
				<td>{!! Form::select('a_outas_1',$enum,null,array('class'=>'gui-input team_a_wkt','id'=>'a_outas_1')) !!}</td>
                                <td>{!! Form::select('a_fielder_1',$team_b,null,array('class'=>'gui-input','id'=>'a_fielder_1')) !!}
				<span id="a_fildershow_{{ $a_bat_fst_ing }}" style="display:none;">{{'--'}}</span>
				</td>
				<td>{!! Form::select('a_bowled_1',$team_b,null,array('class'=>'gui-input','id'=>'a_bowled_1')) !!}
				<span id="a_bowlershow_{{ $a_bat_fst_ing }}" style="display:none;">{{'--'}}</span>
				</td>
				<td>{!! Form::text('a_runs_1', null, array('class'=>'gui-input allownumericwithdecimal team_a_score runs_new','id'=>'a_runs_1','onkeyup'=>"strikeratecalculator('a_runs_$a_bat_fst_ing','a_balls_$a_bat_fst_ing','a_strik_rate_$a_bat_fst_ing');")) !!}</td>
				<td>{!! Form::text('a_balls_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_balls_1','onkeyup'=>"strikeratecalculator('a_runs_$a_bat_fst_ing','a_balls_$a_bat_fst_ing','a_strik_rate_$a_bat_fst_ing');")) !!}</td>
				<td>{!! Form::text('a_fours_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_fours_1')) !!}</td>
				<td>{!! Form::text('a_sixes_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_sixes_1')) !!}</td>
				<td>{!! Form::text('a_strik_rate_1', null, array('class'=>'gui-input allownumericwithdecimal strike_new runs_new','id'=>'a_strik_rate_1','readonly','style'=>'width:120%')) !!}</td>
				<td></td>
			</tr>
		@endif
		</tbody>

	</table>
	</div>
	  <a id="a_bat" onclick="getPlayerTr(<?php echo $i=$a_bat_fst_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>

	<div class="clearfix"></div>
	 <h3 id='team_b_bowling' class="team_bowl table_head">{{ $team_b_name }} Bowling</h3>
	 <div class="table-responsive">
	<table class="table table-striped">
	<thead class="thead">
		<tr>
			<th>Bowlers</th>
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
			<th></th>

		</tr>
	</thead>
		<tbody id="bowler_tr_b" >
		<?php $b_bowl_fst_ing=1;?>
			@if(!empty($team_b_fst_ing_bowling_array) && count($team_b_fst_ing_bowling_array)>0)
				@foreach($team_b_fst_ing_bowling_array as $team_b_bowl)
			<tr id="team_b_bowl_{{$team_b_bowl['id']}}" class="team_b_bowl_open_row">
				<!--<td>{!! Form::select('b_bowler_1',$team_b,null,array('class'=>'gui-input b_bowler','id'=>'b_bowler_1')) !!}</td>-->
				<td><select name='b_bowler_{{$b_bowl_fst_ing}}' id="b_bowler_{{$b_bowl_fst_ing}}" class='gui-input b_bowler'>
				@foreach($team_b as $b_bowl_key => $b_bowl_val)
				<option value="{{$b_bowl_key}}" <?php if (isset($team_b_bowl['user_id']) && $team_b_bowl['user_id']==$b_bowl_key) echo ' selected';?>>{{$b_bowl_val}}</option>
				@endforeach
				</select></td>
				<td>{!! Form::text('b_bowler_overs_'.$b_bowl_fst_ing, (!empty($team_b_bowl['overs_bowled']))?$team_b_bowl['overs_bowled']:'', array('class'=>'gui-input allownumericwithdecimal team_b_overs runs_new','id'=>'b_bowler_overs_'.$b_bowl_fst_ing,'onkeyup'=>"economycalculator('b_bowler_runs_$b_bowl_fst_ing','b_bowler_overs_$b_bowl_fst_ing','b_ecomony_$b_bowl_fst_ing');")) !!}</td>
				
				<td>{!! Form::text('b_bowler_maidens_'.$b_bowl_fst_ing, (!empty($team_b_bowl['overs_maiden']))?$team_b_bowl['overs_maiden']:'', array('class'=>'gui-input allownumericwithdecimal team_b_maidens runs_new','id'=>'b_bowler_maidens_'.$b_bowl_fst_ing)) !!}</td>
				
				<td>{!! Form::text('b_bowler_runs_'.$b_bowl_fst_ing, (!empty($team_b_bowl['runs_conceded']))?$team_b_bowl['runs_conceded']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_bowler_runs_'.$b_bowl_fst_ing,'onkeyup'=>"economycalculator('b_bowler_runs_$b_bowl_fst_ing','b_bowler_overs_$b_bowl_fst_ing','b_ecomony_$b_bowl_fst_ing');")) !!}</td>
				<td>{!! Form::text('b_bowler_wkts_'.$b_bowl_fst_ing, (!empty($team_b_bowl['wickets']))?$team_b_bowl['wickets']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_bowler_wkts_'.$b_bowl_fst_ing)) !!}</td>
				<td>{!! Form::text('b_ecomony_'.$b_bowl_fst_ing, (!empty($team_b_bowl['ecomony']))?number_format($team_b_bowl['ecomony'],2):'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_ecomony_'.$b_bowl_fst_ing,'readonly')) !!}</td>

				<td>{!! Form::text('b_bowler_wide_'.$b_bowl_fst_ing, (!empty($team_b_bowl['wides_bowl']))?$team_b_bowl['wides_bowl']:'', array('class'=>'gui-input allownumericwithdecimal b_wides runs_new','id'=>'b_bowler_wide_'.$b_bowl_fst_ing)) !!}</td>
                <td>{!! Form::text('b_bowler_noball_'.$b_bowl_fst_ing, (!empty($team_b_bowl['noballs_bowl']))?$team_b_bowl['noballs_bowl']:'', array('class'=>'gui-input allownumericwithdecimal b_no_balls runs_new','id'=>'b_bowler_noball_'.$b_bowl_fst_ing)) !!}</td>
				<td><a href="#" onclick="delete_row('b','bowl',{{$team_b_bowl['id']}},{{$b_bowl_fst_ing}});" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></a></td>
                <td></td>
                <td></td>
                <td></td>

			</tr>
			<?php $b_bowl_fst_ing++;?>
			@endforeach
			@else
			<tr class="team_b_bowl_open_row">
				<td>{!! Form::select('b_bowler_1',$team_b,null,array('class'=>'gui-input b_bowler','id'=>'b_bowler_1')) !!}</td>
				<td>{!! Form::text('b_bowler_overs_1', null, array('class'=>'gui-input allownumericwithdecimal team_b_overs runs_new','id'=>'b_bowler_overs_1','onkeyup'=>"economycalculator('b_bowler_runs_$b_bowl_fst_ing','b_bowler_overs_$b_bowl_fst_ing','b_ecomony_$b_bowl_fst_ing');")) !!}</td>
				<td>{!! Form::text('b_bowler_maidens_'.$b_bowl_fst_ing, (!empty($team_b_bowl['overs_maiden']))?$team_b_bowl['overs_maiden']:'', array('class'=>'gui-input allownumericwithdecimal team_b_maidens runs_new','id'=>'b_bowler_maidens_'.$b_bowl_fst_ing)) !!}</td>
				<td>{!! Form::text('b_bowler_runs_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_bowler_runs_1','onkeyup'=>"economycalculator('b_bowler_runs_$b_bowl_fst_ing','b_bowler_overs_$b_bowl_fst_ing','b_ecomony_$b_bowl_fst_ing');")) !!}</td>
				<td>{!! Form::text('b_bowler_wkts_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_bowler_wkts_1')) !!}</td>
				<td>{!! Form::text('b_ecomony_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_ecomony_1','readonly')) !!}</td>
				<td>{!! Form::text('b_bowler_wide_1', null, array('class'=>'gui-input allownumericwithdecimal b_wides runs_new','id'=>'b_bowler_wide_1')) !!}</td>
				<td>{!! Form::text('b_bowler_noball_1', null, array('class'=>'gui-input allownumericwithdecimal b_no_balls runs_new','id'=>'b_bowler_noball_1')) !!}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
			@endif
		</tbody>

	</table>
	</div>
	 <a id="b_bowl" onclick="getTeambBowlerTr(<?php echo $i=$b_bowl_fst_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>


	<!-- Team A Extras------>
	<div class="clearfix"></div>
	<h3 id='team_a_extras' class="team_extra table_head">{{ $team_a_name }} Extras</h3>
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
		<td>{!! Form::text('team_a_wide', (!empty($team_wise_match_details[$fstIngFstBatId]['first']['wide']))?$team_wise_match_details[$fstIngFstBatId]['first']['wide']:'', array('class'=>'gui-input allownumericwithdecimal a_extras runs_new','id'=>'team_a_wide','readonly')) !!}</td>
		<td>{!! Form::text('team_a_noball', (!empty($team_wise_match_details[$fstIngFstBatId]['first']['noball']))?$team_wise_match_details[$fstIngFstBatId]['first']['noball']:'', array('class'=>'gui-input allownumericwithdecimal a_extras runs_new','id'=>'team_a_noball','readonly')) !!}</td>
		<td>{!! Form::text('team_a_legbye', (!empty($team_wise_match_details[$fstIngFstBatId]['first']['legbye']))?$team_wise_match_details[$fstIngFstBatId]['first']['legbye']:'', array('class'=>'gui-input allownumericwithdecimal a_extras runs_new','id'=>'team_a_legbye')) !!}</td>
		<td>{!! Form::text('team_a_bye', (!empty($team_wise_match_details[$fstIngFstBatId]['first']['bye']))?$team_wise_match_details[$fstIngFstBatId]['first']['bye']:'', array('class'=>'gui-input allownumericwithdecimal a_extras runs_new','id'=>'team_a_bye')) !!}</td>
		<td>{!! Form::text('team_a_others', (!empty($team_wise_match_details[$fstIngFstBatId]['first']['others']))?$team_wise_match_details[$fstIngFstBatId]['first']['others']:'', array('class'=>'gui-input allownumericwithdecimal a_extras runs_new','id'=>'team_a_others')) !!}</td>
		<td>{!! Form::text('team_a_tot_extras', '', array('class'=>'gui-input allownumericwithdecimal runs_new','readonly','id'=>'team_a_tot_extras')) !!}</td>
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
		<tbody id="fall_of_wkt_a">
		<?php $team_a_fall_wkts=1;?>
		@if(!empty($team_wise_match_details[$fstIngFstBatId]['first']) && count($team_wise_match_details[$fstIngFstBatId]['first'])>0 && $a_keyCount>0)
			@foreach($team_wise_match_details[$fstIngFstBatId]['first'] as $a_key => $team_a_wkts)
			@if(is_numeric($a_key))
			<tr class="team_a_fall_row">
				<td>{!! Form::text('a_wicket_'.$team_a_fall_wkts, (!empty($team_a_wkts['wicket']))?$team_a_wkts['wicket']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_wicket_'.$team_a_fall_wkts)) !!}</td>
				<!--<td>{!! Form::select('a_wkt_player_'.$team_a_fall_wkts,$team_a,null,array('class'=>'gui-input','id'=>'a_wkt_player_'.$team_a_fall_wkts)) !!}</td>-->
				<td><select name="a_wkt_player_{{$team_a_fall_wkts}}" id="a_wkt_player_{{$team_a_fall_wkts}}" class="gui-input a_fal_wkt">
				@foreach($team_a as $a_fall_key => $a_fall_val)
				<option value="{{$a_fall_key}}" <?php if (isset($team_a_wkts['batsman']) && $team_a_wkts['batsman']==$a_fall_key) echo ' selected';?>>{{$a_fall_val}}</option>
				@endforeach
				</select>
				</td>
				<td>{!! Form::text('a_at_runs_'.$team_a_fall_wkts, (!empty($team_a_wkts['score']))?$team_a_wkts['score']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_at_runs_'.$team_a_fall_wkts)) !!}</td>
				<td>{!! Form::text('a_at_over_'.$team_a_fall_wkts, (!empty($team_a_wkts['over']))?$team_a_wkts['over']:'', array('class'=>'gui-input allownumericwithdecimal out_at_over','id'=>'a_at_over_'.$team_a_fall_wkts)) !!}</td>
				<td></td>
				 <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

			</tr>
			<?php $team_a_fall_wkts++;?>
			@endif
			@endforeach
		@else
			<tr class="team_a_fall_row">
				<td>{!! Form::text('a_wicket_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_wicket_1')) !!}</td>
				<td>{!! Form::select('a_wkt_player_1',$team_a,null,array('class'=>'gui-input a_fal_wkt','id'=>'a_wkt_player_1')) !!}</td>
				<td>{!! Form::text('a_at_runs_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_at_runs_1')) !!}</td>
				<td>{!! Form::text('a_at_over_1', null, array('class'=>'gui-input allownumericwithdecimal out_at_over','id'=>'a_at_over_1')) !!}</td>
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
	 <a  id="a_fall_wkt" onclick="fall_of_wkt('a',<?php echo $i=$team_a_fall_wkts;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>
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
		<tbody id="player_tr_b" >
		<?php $team_b_bat_fst_ing=1;?>
			@if(!empty($team_b_fst_ing_array) && count($team_b_fst_ing_array)>0)
				@foreach($team_b_fst_ing_array as $team_b_bat)
			<tr id="team_b_bat_{{$team_b_bat['id']}}" class="team_b_bat_open_row">
				<!--<td>{!! Form::select('b_player_1',$team_b,null,array('class'=>'gui-input b_player','id'=>'b_player_1')) !!}</td>
				<td>{!! Form::select('b_outas_1',$enum,null,array('class'=>'gui-input','id'=>'b_outas_1')) !!}</td>
				<td>{!! Form::select('b_bowled_1',$team_a,null,array('class'=>'gui-input','id'=>'b_bowled_1')) !!}</td>
				<td>{!! Form::select('b_fielder_1',$team_a,null,array('class'=>'gui-input','id'=>'b_fielder_1')) !!}</td>-->

				<td><select name='b_player_{{ $team_b_bat_fst_ing }}' class='gui-input b_player' id='b_player_{{$team_b_bat_fst_ing}}'>
					@foreach($team_b as $b__bat_key => $b_bowl_val)
					<option value="{{$b__bat_key}}" <?php if (isset($team_b_bat['user_id']) && $team_b_bat['user_id']==$b__bat_key) echo ' selected';?>>{{ $b_bowl_val }}</option>
					@endforeach
				</select>
				</td>

				<td><select name='b_outas_{{ $team_b_bat_fst_ing }}' class='gui-input team_b_wkt' id='b_outas_{{$team_b_bat_fst_ing}}'>
					@foreach($enum as $enum_b_key => $enum_b_val)
					<option value="{{$enum_b_key}}" <?php if (isset($team_b_bat['out_as']) && $team_b_bat['out_as']==$enum_b_key) echo ' selected';?>>{{ $enum_b_val }}</option>
					@endforeach
				</select>
				</td>
                                
                                <td><select name='b_fielder_{{ $team_b_bat_fst_ing }}' class='gui-input' id='b_fielder_{{$team_b_bat_fst_ing}}'>
					@foreach($team_a as $b_bating_key => $b_bating_val)
					<option value="{{$b_bating_key}}" <?php if (isset($team_b_bat['fielder_id']) && $team_b_bat['fielder_id']==$b_bating_key) echo ' selected';?>>{{ $b_bating_val }}</option>
					@endforeach
				</select>
				<span id="b_fildershow_{{ $team_b_bat_fst_ing }}" style="display:none;">{{'--'}}</span>
				</td>
                                
				<td><select name='b_bowled_{{ $team_b_bat_fst_ing }}' class='gui-input' id='b_bowled_{{$team_b_bat_fst_ing}}'>
					@foreach($team_a as $b_bowled_key => $b_bowled_val)
					<option value="{{$b_bowled_key}}" <?php if (isset($team_b_bat['bowled_id']) && $team_b_bat['bowled_id']==$b_bowled_key) echo ' selected';?>>{{ $b_bowled_val }}</option>
					@endforeach
				</select>
				<span id="b_bowlershow_{{ $team_b_bat_fst_ing }}" style="display:none;">{{'--'}}</span>
				</td>

				<td>{!! Form::text('b_runs_'.$team_b_bat_fst_ing, (!empty($team_b_bat['totalruns']))?$team_b_bat['totalruns']:'', array('class'=>'gui-input allownumericwithdecimal team_b_score runs_new','id'=>'b_runs_'.$team_b_bat_fst_ing,'onkeyup'=>"strikeratecalculator('b_runs_$team_b_bat_fst_ing','b_balls_$team_b_bat_fst_ing','b_strik_rate_$team_b_bat_fst_ing');")) !!}</td>
				<td>{!! Form::text('b_balls_'.$team_b_bat_fst_ing, (!empty($team_b_bat['balls_played']))?$team_b_bat['balls_played']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_balls_'.$team_b_bat_fst_ing,'onkeyup'=>"strikeratecalculator('b_runs_$team_b_bat_fst_ing','b_balls_$team_b_bat_fst_ing','b_strik_rate_$team_b_bat_fst_ing');")) !!}</td>
				<td>{!! Form::text('b_fours_'.$team_b_bat_fst_ing, (!empty($team_b_bat['fours']))?$team_b_bat['fours']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_fours_'.$team_b_bat_fst_ing)) !!}</td>
				<td>{!! Form::text('b_sixes_'.$team_b_bat_fst_ing, (!empty($team_b_bat['sixes']))?$team_b_bat['sixes']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_sixes_'.$team_b_bat_fst_ing)) !!}</td>
				<td>{!! Form::text('b_strik_rate_'.$team_b_bat_fst_ing, (!empty($team_b_bat['strikerate']))?number_format($team_b_bat['strikerate'],2):'', array('class'=>'gui-input allownumericwithdecimal strike_new runs_new','id'=>'b_strik_rate_'.$team_b_bat_fst_ing,'readonly')) !!}</td>
				<td><a href="#" onclick="delete_row('b','bat',{{$team_b_bat['id']}},{{$team_b_bat_fst_ing}});" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></td>
				<?php $team_b_bat_fst_ing++;?>
				@endforeach
			</tr>
			@else
			<tr class="team_b_bat_open_row">
				<td>{!! Form::select('b_player_1',$team_b,null,array('class'=>'gui-input b_player','id'=>'b_player_1')) !!}</td>
				<!--<td>{!! Form::text('b_outas_1', null, array('class'=>'gui-input','id'=>'b_outas_1')) !!}</td>-->
				<td>{!! Form::select('b_outas_1',$enum,null,array('class'=>'gui-input team_b_wkt','id'=>'b_outas_1')) !!}</td>
				<td>{!! Form::select('b_fielder_1',$team_a,null,array('class'=>'gui-input','id'=>'b_fielder_1')) !!}
				<span id="b_fildershow_{{ $team_b_bat_fst_ing }}" style="display:none;">{{'--'}}</span>
				</td>
                                <td>{!! Form::select('b_bowled_1',$team_a,null,array('class'=>'gui-input','id'=>'b_bowled_1')) !!}
				<span id="b_bowlershow_{{ $team_b_bat_fst_ing }}" style="display:none;">{{'--'}}</span>
				</td>
				<td>{!! Form::text('b_runs_1', null, array('class'=>'gui-input allownumericwithdecimal team_b_score runs_new','id'=>'b_runs_1','onkeyup'=>"strikeratecalculator('b_runs_$team_b_bat_fst_ing','b_balls_$team_b_bat_fst_ing','b_strik_rate_$team_b_bat_fst_ing');")) !!}</td>
				<td>{!! Form::text('b_balls_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_balls_1','onkeyup'=>"strikeratecalculator('b_runs_$team_b_bat_fst_ing','b_balls_$team_b_bat_fst_ing','b_strik_rate_$team_b_bat_fst_ing');")) !!}</td>
				<td>{!! Form::text('b_fours_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_fours_1')) !!}</td>
				<td>{!! Form::text('b_sixes_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_sixes_1')) !!}</td>
				<td>{!! Form::text('b_strik_rate_1', null, array('class'=>'gui-input allownumericwithdecimal strike_new runs_new','id'=>'b_strik_rate_1','readonly')) !!}</td>
				<td></td>
			</tr>
			@endif
		</tbody>

	</table>
	</div>
	 <a id="b_bat" onclick="getteamBPlayerTr(<?php echo $i=$team_b_bat_fst_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>

	 <h3 id='team_a_bowling' class="team_bowl table_head">{{ $team_a_name }} Bowling</h3>
	  <div class="table-responsive">
	<table class="table table-striped">
	 <thead class="thead">
		<tr>
			<th>Bowlers</th>
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
			<th></th>

		</tr>
	</thead>
		<tbody id="bowler_tr_a" >
		<?php $team_a_bowl_fst_ing=1;?>
		@if(!empty($team_a_fst_ing_bowling_array) && count($team_a_fst_ing_bowling_array)>0)
			@foreach($team_a_fst_ing_bowling_array as $team_a_bowl)
			<tr id="team_a_bowl_{{$team_a_bowl['id']}}" class="team_a_bowl_open_row">
				<!--<td>{!! Form::select('a_bowler_1',$team_a,null,array('class'=>'gui-input a_bowler','id'=>'a_bowler_1')) !!}</td>-->
				<td><select name='a_bowler_{{$team_a_bowl_fst_ing}}' id='a_bowler_{{$team_a_bowl_fst_ing}}' class='gui-input a_bowler'>
				@foreach($team_a as $a_bowl_key => $a_bowl_val)
				<option value="{{$a_bowl_key}}" <?php if (isset($team_a_bowl['user_id']) && $team_a_bowl['user_id']==$a_bowl_key) echo ' selected';?>>{{$a_bowl_val}}</option>
				@endforeach
				</select></td>
				<td>{!! Form::text('a_bowler_overs_'.$team_a_bowl_fst_ing, (!empty($team_a_bowl['overs_bowled']))?$team_a_bowl['overs_bowled']:'', array('class'=>'gui-input allownumericwithdecimal team_a_overs runs_new','id'=>'a_bowler_overs_'.$team_a_bowl_fst_ing,'onkeyup'=>"economycalculator('a_bowler_runs_$team_a_bowl_fst_ing','a_bowler_overs_$team_a_bowl_fst_ing','a_ecomony_$team_a_bowl_fst_ing');")) !!}</td>
				
				<td>{!! Form::text('a_bowler_maidens_'.$team_a_bowl_fst_ing, (!empty($team_a_bowl['overs_maiden']))?$team_a_bowl['overs_maiden']:'', array('class'=>'gui-input allownumericwithdecimal team_a_maidens runs_new','id'=>'a_bowler_maidens_'.$team_a_bowl_fst_ing)) !!}</td>
				
				<td>{!! Form::text('a_bowler_runs_'.$team_a_bowl_fst_ing, (!empty($team_a_bowl['runs_conceded']))?$team_a_bowl['runs_conceded']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_bowler_runs_'.$team_a_bowl_fst_ing,'onkeyup'=>"economycalculator('a_bowler_runs_$team_a_bowl_fst_ing','a_bowler_overs_$team_a_bowl_fst_ing','a_ecomony_$team_a_bowl_fst_ing');")) !!}</td>
				<td>{!! Form::text('a_bowler_wkts_'.$team_a_bowl_fst_ing, (!empty($team_a_bowl['wickets']))?$team_a_bowl['wickets']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_bowler_wkts_'.$team_a_bowl_fst_ing)) !!}</td>
				<td>{!! Form::text('a_ecomony_'.$team_a_bowl_fst_ing, (!empty($team_a_bowl['ecomony']))?number_format($team_a_bowl['ecomony'],2):'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_ecomony_'.$team_a_bowl_fst_ing,'readonly')) !!}</td>
				<td>{!! Form::text('a_bowler_wide_'.$team_a_bowl_fst_ing, (!empty($team_a_bowl['wides_bowl']))?$team_a_bowl['wides_bowl']:'', array('class'=>'gui-input allownumericwithdecimal a_wides runs_new','id'=>'a_bowler_wide_'.$team_a_bowl_fst_ing)) !!}</td>
                <td>{!! Form::text('a_bowler_noball_'.$team_a_bowl_fst_ing, (!empty($team_a_bowl['noballs_bowl']))?$team_a_bowl['noballs_bowl']:'', array('class'=>'gui-input allownumericwithdecimal a_no_balls runs_new','id'=>'a_bowler_noball_'.$team_a_bowl_fst_ing)) !!}</td>
				<td><a href="#" onclick="delete_row('a','bowl',{{$team_a_bowl['id']}},{{$team_a_bowl_fst_ing}})" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></td>

                <td></td>
                <td></td>
                <td></td>
			</tr>
			<?php $team_a_bowl_fst_ing++;?>
			@endforeach
			@else
			<tr class="team_a_bowl_open_row">
				<td>{!! Form::select('a_bowler_1',$team_a,null,array('class'=>'gui-input a_bowler','id'=>'a_bowler_1')) !!}</td>
				<td>{!! Form::text('a_bowler_overs_1', null, array('class'=>'gui-input allownumericwithdecimal team_a_overs runs_new','id'=>'a_bowler_overs_1','onkeyup'=>"economycalculator('a_bowler_runs_$team_a_bowl_fst_ing','a_bowler_overs_$team_a_bowl_fst_ing','a_ecomony_$team_a_bowl_fst_ing');")) !!}</td>
				
				<td>{!! Form::text('a_bowler_maidens_'.$team_a_bowl_fst_ing, (!empty($team_a_bowl['overs_maiden']))?$team_a_bowl['overs_maiden']:'', array('class'=>'gui-input allownumericwithdecimal team_a_maidens runs_new','id'=>'a_bowler_maidens_'.$team_a_bowl_fst_ing)) !!}</td>
				
				<td>{!! Form::text('a_bowler_runs_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_bowler_runs_1','onkeyup'=>"economycalculator('a_bowler_runs_$team_a_bowl_fst_ing','a_bowler_overs_$team_a_bowl_fst_ing','a_ecomony_$team_a_bowl_fst_ing');")) !!}</td>
				<td>{!! Form::text('a_bowler_wkts_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_bowler_wkts_1')) !!}</td>
				<td>{!! Form::text('a_ecomony_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'a_ecomony_1','readonly')) !!}</td>
				<td>{!! Form::text('a_bowler_wide_1', null, array('class'=>'gui-input allownumericwithdecimal a_wides runs_new','id'=>'a_bowler_wide_1')) !!}</td>
                <td>{!! Form::text('a_bowler_noball_1', null, array('class'=>'gui-input allownumericwithdecimal a_no_balls runs_new','id'=>'a_bowler_noball_1')) !!}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
			</tr>
		@endif
		</tbody>
	</table>
	</div>
	 <a id="a_bowl" onclick="getBowlerTr(<?php echo $i=$team_a_bowl_fst_ing;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>


	<!-- Team B Extras------>
	<div class="clearfix"></div>
	<h3 id='team_b_extras' class="team_extra table_head">{{ $team_b_name }} Extras</h3>
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
		<td>{!! Form::text('team_b_wide', (!empty($team_wise_match_details[$fstIngsecondBatId]['first']['wide']))?$team_wise_match_details[$fstIngsecondBatId]['first']['wide']:'', array('class'=>'gui-input allownumericwithdecimal b_extras runs_new','id'=>'team_b_wide','readonly')) !!}</td>
		<td>{!! Form::text('team_b_noball', (!empty($team_wise_match_details[$fstIngsecondBatId]['first']['noball']))?$team_wise_match_details[$fstIngsecondBatId]['first']['noball']:'', array('class'=>'gui-input allownumericwithdecimal b_extras runs_new','id'=>'team_b_noball','readonly')) !!}</td>
		<td>{!! Form::text('team_b_legbye', (!empty($team_wise_match_details[$fstIngsecondBatId]['first']['legbye']))?$team_wise_match_details[$fstIngsecondBatId]['first']['legbye']:'', array('class'=>'gui-input allownumericwithdecimal b_extras runs_new','id'=>'team_b_legbye')) !!}</td>
		<td>{!! Form::text('team_b_bye', (!empty($team_wise_match_details[$fstIngsecondBatId]['first']['bye']))?$team_wise_match_details[$fstIngsecondBatId]['first']['bye']:'', array('class'=>'gui-input allownumericwithdecimal b_extras runs_new','id'=>'team_b_bye')) !!}</td>
		<td>{!! Form::text('team_b_others', (!empty($team_wise_match_details[$fstIngsecondBatId]['first']['others']))?$team_wise_match_details[$fstIngsecondBatId]['first']['others']:'', array('class'=>'gui-input allownumericwithdecimal b_extras runs_new','id'=>'team_b_others')) !!}</td>
		<td>{!! Form::text('team_b_tot_extras', '', array('class'=>'gui-input allownumericwithdecimal runs_new','readonly','id'=>'team_b_tot_extras')) !!}</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
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
			<th></th>
		</tr>
		</thead>
		<tbody id="fall_of_wkt_b">
		<?php $team_b_fall_wkt = 1;?>
		@if(!empty($team_wise_match_details[$fstIngsecondBatId]['first']) && count($team_wise_match_details[$fstIngsecondBatId]['first'])>0 && $b_keyCount>0)

			@foreach($team_wise_match_details[$fstIngsecondBatId]['first'] as $b_key => $team_b_fall)
			@if(is_numeric($b_key))
			<tr class="team_b_fall_row">
				<td>{!! Form::text('b_wicket_'.$team_b_fall_wkt, (!empty($team_b_fall['wicket']))?$team_b_fall['wicket']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_wicket_'.$team_b_fall_wkt)) !!}</td>
				<!--<td>{!! Form::select('b_wkt_player_1',$team_b,null,array('class'=>'gui-input','id'=>'b_wkt_player_1')) !!}</td>-->
				<td><select name="b_wkt_player_{{$team_b_fall_wkt}}" id="b_wkt_player_{{$team_b_fall_wkt}}" class="gui-input b_fal_wkt">
				@foreach($team_b as $b_fall_key => $b_fall_val)
				<option value="{{$b_fall_key}}" <?php if (isset($team_b_fall['batsman']) && $team_b_fall['batsman']==$b_fall_key) echo ' selected';?>>{{$b_fall_val}}</option>
				@endforeach
				</select>
				</td>
				<td>{!! Form::text('b_at_runs_'.$team_b_fall_wkt, (!empty($team_b_fall['score']))?$team_b_fall['score']:'', array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_at_runs_'.$team_b_fall_wkt)) !!}</td>
				<td>{!! Form::text('b_at_over_'.$team_b_fall_wkt, (!empty($team_b_fall['over']))?$team_b_fall['over']:'', array('class'=>'gui-input allownumericwithdecimal out_at_over','id'=>'b_at_over_'.$team_b_fall_wkt)) !!}</td>
				<td></td>
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
			<tr class="team_b_fall_row">
				<td>{!! Form::text('b_wicket_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_wicket_1')) !!}</td>
				<td>{!! Form::select('b_wkt_player_1',$team_b,null,array('class'=>'gui-input b_fal_wkt','id'=>'b_wkt_player_1')) !!}</td>
				<td>{!! Form::text('b_at_runs_1', null, array('class'=>'gui-input allownumericwithdecimal runs_new','id'=>'b_at_runs_1')) !!}</td>
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
	 <a id="b_fall_wkt" onclick="fall_of_wkt('b',<?php echo $i=$team_b_fall_wkt;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>
	<!-- *********Fall Of Wickets ************* -->

<!--********* TEAM B  End **************!-->

<div class="clearfix"></div>
<!--********* MATCH REPORT Start **************!-->
<div class="summernote_wrapper form-group">
        <h3 class="brown1 table_head">Match Report</h3>
        <textarea id="match_report" class="summernote" name="match_report" title="Match Report">{{ $match_data[0]['match_report'] }}</textarea>
</div>
<!--********* MATCH REPORT End **************!-->
<div class="clearfix"></div>

@if($isValidUser)
@include('scorecards.cricket_end_match')
@endif

     <center>
     	<ul class="list-inline sportsjun-forms">
            <li>
                @if($isValidUser)
                <button type="submit" id="save_first_inning" class="button btn-primary" onclick="save('fst_ing_click');"><i class="fa fa-floppy-o"></i> Save</button>
                @endif
            </li>
	<input type="hidden" id="cricketfirsting_form_data" value="">		
			
	 <input type="hidden" name="a_player_count" value="{{ (count($team_a_fst_ing_array)>0)?count($team_a_fst_ing_array):1 }}" id="a_player_count">
	 <input type="hidden" name="a_bowler_count" value="{{ (count($team_a_fst_ing_bowling_array)>0)?count($team_a_fst_ing_bowling_array):1 }}" id="a_bowler_count">
	 <input type="hidden" name="b_player_count" value="{{ (count($team_b_fst_ing_array)>0)?count($team_b_fst_ing_array):1 }}" id="b_player_count">
	 <input type="hidden" name="b_bowler_count" value="{{ (count($team_b_fst_ing_bowling_array)>0)?count($team_b_fst_ing_bowling_array):1 }}" id="b_bowler_count">
	 <input type="hidden" name="a_fall_of_count" value="{{ $team_a_fall_wkts }}" id="a_fall_of_count">
	 <input type="hidden" name="b_fall_of_count" value="{{ $team_b_fall_wkt }}" id="b_fall_of_count">
	 <input type="hidden" name="tournament_id" value="{{ $match_data[0]['tournament_id'] }}" id="player_count">
	 <input type="hidden" name="team_a_id" value="{{ $fstIngFstBatId }}" id="team_a_id">
	 <input type="hidden" name="team_b_id" value="{{ $fstIngsecondBatId }}" id="team_b_id">
	 <input type="hidden" name="match_id" value="{{ $match_data[0]['id'] }}" id="player_count">
	 <input type="hidden" name="match_type" value="{{ $match_data[0]['match_type'] }}" id="player_count">
	 <input type="hidden" name="team_b_name" value="{{ $team_b_name }}" id="team_b_name">
	 <input type="hidden" name="team_a_name" value="{{ $team_a_name }}" id="team_a_name">
	 <input type="hidden" name="inning" value="first" id="inning">
	 <input type="hidden" name="toss_won_by" value="{{ $toss_won_by }}" id="toss_won_by">
	 <input type="hidden" name="toss_won_team_name" value="{{ $team_a_name }}" id="toss_won_team_name">
	 <input type="hidden" id="winner_team_id" name="winner_team_id" class="winner_team_id" value="{{ !empty($match_data[0]['winner_id'])?$match_data[0]['winner_id']:''}}">
        
        <?php 
                foreach ([$fstIngFstBatId,$fstIngsecondBatId] as $teamStat_team_id)
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
        
        <input type="hidden" id="deleted_ids" name="deleted_ids" value="">
        <input type="hidden" id="hid_match_result" name="hid_match_result" value="">

{!!Form::close()!!}
 <input type="hidden" name="i" value="{{ (!empty($team_a_fst_ing_array))?$a_bat_fst_ing:2 }}" id="i">
 <input type="hidden" name="j" value="{{ (!empty($team_a_fst_ing_bowling_array))?$team_a_bowl_fst_ing:2 }}" id="j">
 <input type="hidden" name="k" value="{{ (!empty($team_b_fst_ing_array))?$team_b_bat_fst_ing:2 }}" id="k">
 <input type="hidden" name="l" value="{{ (!empty($team_b_fst_ing_bowling_array))?$b_bowl_fst_ing:2 }}" id="l">
 <input type="hidden" name="x" value="{{ (!empty($team_wise_match_details[$fstIngFstBatId]['first']) && $a_keyCount>0)?$team_a_fall_wkts:2 }}" id="x">
 <input type="hidden" name="z" value="{{ (!empty($team_wise_match_details[$fstIngsecondBatId]['first']) && $b_keyCount)?$team_b_fall_wkt:2 }}" id="z">
<script>
checkDuplicatePlayers('a_player');
checkDuplicatePlayers('b_bowler');
checkDuplicatePlayers('b_player');
checkDuplicatePlayers('a_bowler');
checkDuplicatePlayers('b_fal_wkt');
checkDuplicatePlayers('a_fal_wkt');
function strikeratecalculator(runs,balls,strikerate)
{
	var txtFirstNumberValue = $('#'+runs).val();
	var txtSecondNumberValue = $('#'+balls).val();
	var result = (parseInt(txtFirstNumberValue) * 100) / parseInt(txtSecondNumberValue);//strike rate calculation
	//alert(runs);
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
function economycalculator(runs_conceded,overs,economy)
{
	var given_runs = $('#'+runs_conceded).val();
	var overs_bowled = $('#'+overs).val();
    if (overs_bowled.indexOf('.') !== -1)
    {
        var oversArr = overs_bowled.split('.');
        var balls = parseInt(oversArr[1]);
        if (balls > 0)
        {
            overs_bowled = parseInt(oversArr[0]) + (balls / 6);
            if (overs_bowled.toString().indexOf('.') === -1)
            {
                $('#'+overs).val(overs_bowled);
            }
        }
    }
	var eco = parseInt(given_runs) / parseFloat(overs_bowled);//strike rate calculation
	if(parseFloat(overs_bowled)>0)
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

var teamId = $('#team option:selected').data('status');
var bating_team = $( "#team option:selected" ).text();
var bowling_team = $('#team option:not(:selected)').text();
$("#team_a_batting").text(bating_team+' Innings');
$("#team_b_bowling").text(bowling_team+' Bowling');
$("#team_b_batting").text(bowling_team+' Innings');
$("#team_a_bowling").text(bating_team+' Bowling');
$("#team_a_extras").text(bating_team+' Extras');
$("#team_b_extras").text(bowling_team+' Extras');

var is_valid_for_change = '{{ !(!empty($team_a_fst_ing_array) || !empty($team_b_fst_ing_array) || !empty($team_a_fst_ing_bowling_array) || !empty($team_b_fst_ing_bowling_array))?1:0 }}';
if(is_valid_for_change==0)
{
	$("#team").prop('disabled',true);
}
function tosswonby()
{
	var tosswonby = $("#toss_won").val();
	$("#toss_won_by").val(tosswonby);
	var toss_won_team_name = $( "#toss_won option:selected" ).text();
	$('#toss_won_team_name').val(toss_won_team_name);
}
function getTeamName()
{
	if(is_valid_for_change==0)
	{
		$("#team").prop('disabled',true);
		 $.alert({
            title: 'Alert!',
            content: 'Already Score Entered.'
        });
		//alert('Already Score Entered.');
		return false;
	}
	var team_a_Id = $('#team option:selected').data('status');//get select box option attribute value
	var team_b_Id = $('#team option:not(:selected)').data('status');//get select box option attribute value

	$("#team_a_id").val(team_a_Id);
	$("#team_b_id").val(team_b_Id);


	var batting_team = $( "#team option:selected" ).text();
	var bowlng_team = $('#team option:not(:selected)').text();

	$("#team_a_name").val(batting_team);
	$("#team_b_name").val(bowlng_team);

	$("#team_a_batting").text(batting_team+' Innings');
	$("#team_b_bowling").text(bowlng_team+' Bowling');
	$("#team_b_batting").text(bowlng_team+' Innings');
	$("#team_a_bowling").text(batting_team+' Bowling');
	var player_a_id = $( "#team option:selected" ).val();
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
							$("#a_player_1").html(options);
							$("#a_bowler_1").html(options);
                                                        $("#b_fielder_1").html(options);
							$("#b_bowled_1").html(options);
							$("#a_wkt_player_1").html(options);


					}
		});
var player_b_id = $( "#team option:not(:selected)" ).val();
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
							$("#b_player_1").html(options);
							$("#b_bowler_1").html(options);
                                                        $("#a_fielder_1").html(options);
							$("#a_bowled_1").html(options);
							$("#b_wkt_player_1").html(options);

					}
		});
}
var get_player_count='{{ (!empty($team_a_fst_ing_array))?count($team_a_fst_ing_array):1}}';
var get_b_bowler_count='{{ (!empty($team_b_fst_ing_bowling_array))?count($team_b_fst_ing_bowling_array):1 }}';
var get_b_bating_count='{{ (!empty($team_b_fst_ing_array))?count($team_b_fst_ing_array):1 }}';
var get_a_bowler_count = '{{ (!empty($team_a_fst_ing_bowling_array))?count($team_a_fst_ing_bowling_array):1}}';
var get_player_counts = get_player_count;
function getPlayerTr(i)
{
	var get_player_countt= $('[class ^= "team_a_bat_open_row"]').size();
	var team_cnt = '{{ $team_a_count}}';
	if(get_player_countt >= team_cnt)
	{
		 $.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
        });
		//alert('Players Exceeded.');
		return false;
	}

	get_player_counts++;
	$('#a_player_count').val(get_player_counts);
	var i=$('#i').val();


	var newContent = "<tr class='team_a_bat_open_row'><td><select  class='gui-input a_player' name='a_player_"+i+"' id='a_player_"+i+"'><option value=''>Select Player</option></select></td>"+
					"<td><select  class='gui-input team_a_wkt' name='a_outas_"+i+"' id='a_outas_"+i+"'><option value=''>Select Out As</option></select></td>"+
					"<td><select  class='gui-input' name='a_fielder_"+i+"' id='a_fielder_"+i+"'><option value=''>Select Fielder</option></select><span  id='a_fildershow_"+i+"' style='display:none;'>--</span></td>"+
                                        "<td><select  class='gui-input' name='a_bowled_"+i+"' id='a_bowled_"+i+"'><option value=''>Select Bowler</option></select><span  id='a_bowlershow_"+i+"' style='display:none;'>--</span></td>"+
					<!--"<td><input type='text' class='gui-input' name='a_outas_"+i+"' /></td>"+-->
					"<td><input type='text' class='gui-input allownumericwithdecimal team_a_score runs_new' id='a_runs_"+i+"' name='a_runs_"+i+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal runs_new' id='a_balls_"+i+"' name='a_balls_"+i+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal runs_new' name='a_fours_"+i+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal runs_new' name='a_sixes_"+i+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal strike_new runs_new' readonly id='a_strik_rate_"+i+"' name='a_strik_rate_"+i+"' /></td><td></td>"+
					"</tr>";

					$("#player_tr_a").append(newContent);


					//get bowling player ids
					//var player_a_ids = "{{ $match_data[0]['player_a_ids'] }}";
					var player_a_ids = $( "#team option:selected" ).val();
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
									var val = i-1;
									$("#a_player_"+val).html(options);

							}
					});

					//get bowler ids
					var opposiet_team_ids = $( "#team option:not(:selected)" ).val();
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
									var val = i-1;
                                                                        $("#a_fielder_"+val).html(options);
									$("#a_bowled_"+val).html(options);
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
									var val = i-1;
									$("#a_outas_"+val).html(options);

							}
					});
					//strike rate
					$("#a_runs_"+i).on("keyup", function() {
						strikeratecalculator("a_runs_"+(i-1),"a_balls_"+(i-1),"a_strik_rate_"+(i-1));
					});
					$("#a_balls_"+i).on("keyup", function() {
						strikeratecalculator("a_runs_"+(i-1),"a_balls_"+(i-1),"a_strik_rate_"+(i-1));
					});

	checkDuplicatePlayers('a_player');
	team_score_calculator('team_a_score','a','score');
	teamWickets('team_a_wkt','a');
	allownumericwithdecimal();
	i++;
	$('#i').val(i);
}

function getBowlerTr(j)
{
	var get_bowler_countt= $('[class ^= "team_a_bowl_open_row"]').size();
	var team_cnt = '{{ $team_a_count}}';
	if(get_bowler_countt >= team_cnt)
	{
		//alert('Players Exceeded.');
		$.alert({
                        title: 'Alert!',
                        content: 'Players Exceeded.'
                });
		return false;
	}
	get_a_bowler_count++;
	$('#a_bowler_count').val(get_a_bowler_count);
	var j=$('#j').val();

	var bowlerContent = "<tr class='team_a_bowl_open_row'>"+
				"<td><select class='a_bowler' name='a_bowler_"+j+"' id='a_bowler_"+j+"'><option value=''>Select Player</option></select></td>"+
				"<td><input type='text' class='allownumericwithdecimal team_a_overs runs_new' id='a_bowler_overs_"+j+"' name='a_bowler_overs_"+j+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal team_a_maidens runs_new' id='a_bowler_maidens_"+j+"' name='a_bowler_maidens_"+j+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal runs_new'  id='a_bowler_runs_"+j+"' name='a_bowler_runs_"+j+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal runs_new' name='a_bowler_wkts_"+j+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal runs_new' readonly id='a_ecomony_"+j+"' name='a_ecomony_"+j+"' /></td>"+"<td><input type='text' class='allownumericwithdecimal a_wides' id='a_bowler_wide_"+j+"' name='a_bowler_wide_"+j+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal a_no_balls runs_new' id='a_bowler_noball_"+j+"' name='a_bowler_noball_"+j+"' /></td>"+
				"<td></td><td></td><td></td><td></td>"+
			"</tr>";
			$("#bowler_tr_a").append(bowlerContent);
			//var player_a_ids = "{{ $match_data[0]['player_a_ids']}}";
			var player_a_ids = $( "#team option:selected" ).val();
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
									var val = j-1;
									$("#a_bowler_"+val).html(options);

							}
					});
			//economy rate
			$("#a_bowler_overs_"+j).on("keyup", function() {
				economycalculator("a_bowler_runs_"+(j-1),"a_bowler_overs_"+(j-1),"a_ecomony_"+(j-1));
			});
			$("#a_bowler_runs_"+j).on("keyup", function() {
				economycalculator("a_bowler_runs_"+(j-1),"a_bowler_overs_"+(j-1),"a_ecomony_"+(j-1));
			});

	j++;
	$('#j').val(j);
	checkDuplicatePlayers('a_bowler');
	team_score_calculator('team_a_overs','b','over');
	wides('a_wides','b');
	noballs('a_no_balls','b');
	allownumericwithdecimal();

}
function getteamBPlayerTr(k)
{
	var get_player_countt= $('[class ^= "team_b_bat_open_row"]').size();
	var team_cnt = '{{ $team_b_count}}';
	if(get_player_countt >= team_cnt)
	{
		//alert('Players Exceeded.');
		$.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
        });
		return false;
	}
	get_b_bating_count++;
	$('#b_player_count').val(get_b_bating_count);
	var k=$('#k').val();

	var newContent = "<tr class='team_b_bat_open_row'><td><select class='b_player' name='b_player_"+k+"' id='b_player_"+k+"'><option value=''>Select Player</option></select></td>"+
					<!--"<td><input type='text' class='gui-input' name='b_outas_"+k+"' /></td>"+-->
					"<td><select  class='gui-input team_b_wkt' name='b_outas_"+k+"' id='b_outas_"+k+"'><option value=''>Select Out As</option></select></td>"+
                                        "<td><select  class='gui-input' name='b_fielder_"+k+"' id='b_fielder_"+k+"'><option value=''>Select Fielder</option></select><span  id='b_fildershow_"+k+"' style='display:none;'>--</span></td>"+
					"<td><select  class='gui-input' name='b_bowled_"+k+"' id='b_bowled_"+k+"'><option value=''>Select Bowler</option></select><span  id='b_bowlershow_"+k+"' style='display:none;'>--</span></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal team_b_score runs_new' id='b_runs_"+k+"' name='b_runs_"+k+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal runs_new' id='b_balls_"+k+"'  name='b_balls_"+k+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal runs_new' name='b_fours_"+k+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal runs_new' name='b_sixes_"+k+"' /></td>"+
					"<td><input type='text' class='gui-input allownumericwithdecimal strike_new runs_new' readonly id='b_strik_rate_"+k+"' name='b_strik_rate_"+k+"' /></td><td></td>"+
					"</tr>";
					$("#player_tr_b").append(newContent);
				//var player_a_ids = "{{ $match_data[0]['player_b_ids']}}";
				var player_a_ids = $( "#team option:not(:selected)" ).val();
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
									var val = k-1;
									$("#b_player_"+val).html(options);

							}
					});

					//get bowler ids
					var opposiet_team_ids = $(  "#team option:selected"  ).val();
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
									var val = k-1;
                                                                        $("#b_fielder_"+val).html(options);
									$("#b_bowled_"+val).html(options);

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
									var val = k-1;
									$("#b_outas_"+val).html(options);

							}
					});

					//strike rate
					$("#b_runs_"+k).on("keyup", function() {
						strikeratecalculator("b_runs_"+(k-1),"b_balls_"+(k-1),"b_strik_rate_"+(k-1));
					});
					$("#b_balls_"+k).on("keyup", function() {
						strikeratecalculator("b_runs_"+(k-1),"b_balls_"+(k-1),"b_strik_rate_"+(k-1));
					});
	k++;
	$('#k').val(k);
	team_score_calculator('team_b_score','b','score');
	teamWickets('team_b_wkt','b');
	checkDuplicatePlayers('b_player');
	allownumericwithdecimal();

}
function getTeambBowlerTr(l)
{
	var get_bowler_countt= $('[class ^= "team_b_bowl_open_row"]').size();
	var team_cnt = '{{ $team_b_count}}';
	if(get_bowler_countt >= team_cnt)
	{
		//alert('Players Exceeded.');
		$.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
        });
		return false;
	}
	get_b_bowler_count++;
	$('#b_bowler_count').val(get_b_bowler_count);
	var l=$('#l').val();

	var bowlerContent = "<tr class='team_b_bowl_open_row'>"+
				"<td><select class='b_bowler' name='b_bowler_"+l+"' id='b_bowler_"+l+"'><option value=''>Select Player</option></select></td>"+
				"<td><input type='text' class='allownumericwithdecimal team_b_overs runs_new' id='b_bowler_overs_"+l+"' name='b_bowler_overs_"+l+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal team_b_maidens runs_new' id='b_bowler_maidens_"+l+"' name='b_bowler_maidens_"+l+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal runs_new' id='b_bowler_runs_"+l+"' name='b_bowler_runs_"+l+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal runs_new' name='b_bowler_wkts_"+l+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal runs_new' id='b_ecomony_"+l+"' readonly name='b_ecomony_"+l+"' /></td>"+"<td><input type='text' class='allownumericwithdecimal b_wides' id='b_bowler_wide_"+l+"' name='b_bowler_wide_"+l+"'</td>"+
				"<td><input type='text' class='allownumericwithdecimal b_no_balls runs_new' id='b_bowler_noball_"+l+"' name='b_bowler_noball_"+l+"'</td>"+
				"<td></td><td></td><td></td><td></td>"+
			"</tr>";
			$("#bowler_tr_b").append(bowlerContent);
			//var player_a_ids = "{{ $match_data[0]['player_b_ids']}}";
			var player_a_ids = $( "#team option:not(:selected)" ).val();
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
									var val = l-1;
									$("#b_bowler_"+val).html(options);

							}
					});
			//economy rate
			$("#b_bowler_overs_"+l).on("keyup", function() {
				economycalculator("b_bowler_runs_"+(l-1),"b_bowler_overs_"+(l-1),"b_ecomony_"+(l-1));
			});
			$("#b_bowler_runs_"+l).on("keyup", function() {
				economycalculator("b_bowler_runs_"+(l-1),"b_bowler_overs_"+(l-1),"b_ecomony_"+(l-1));
			});

	l++;
	$('#l').val(l);
	team_score_calculator('team_b_overs','a','over');
	checkDuplicatePlayers('b_bowler');
	wides('b_wides','a');
	noballs('b_no_balls','a');
	allownumericwithdecimal();

}
//check duplicate players selected
function checkDuplicatePlayers(select_class)
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
            content: "Duplicate Player Selected."
        });
			$(this).val('');

		}
	 });
}
var team_a_fall_wkts = "{{ ($a_keyCount>0)?$a_keyCount:1 }}";
var team_b_fall_wkts = "{{ ($b_keyCount>0)?$b_keyCount:1 }}";
//fall of wikects
function fall_of_wkt(team,x)
{
	if(team=='a')
	{
		var get_a_fall_countt= $('[class ^= "team_a_fall_row"]').size();

		var team_cnt = '{{ $team_a_count}}';
				//alert(get_a_fall_countt);
		//alert(team_cnt);
		if(get_a_fall_countt >= team_cnt)
		{
			//alert('Players Exceeded.');
			$.alert({
            title: 'Alert!',
            content: 'Players Exceeded.'
        });
			return false;
		}
		team_a_fall_wkts++;
		$('#a_fall_of_count').val(team_a_fall_wkts);
		var x=$('#x').val();
	}
	else
	{
		var get_b_fall_countt= $('[class ^= "team_b_fall_row"]').size();
		var team_cnt = '{{ $team_b_count}}';
		if(get_b_fall_countt >= team_cnt)
		{
			//alert('Players Exceeded.');
			$.alert({
				title: 'Alert!',
				content: 'Players Exceeded.'
			});
			return false;
		}
		team_b_fall_wkts++;
		$('#b_fall_of_count').val(team_b_fall_wkts);
		var x=$('#z').val();
	}

	var fall_of_cnt = "<tr class='team_"+team+"_fall_row'>"+
				"<td><input type='text' class='allownumericwithdecimal runs_new'  name='"+team+"_wicket_"+x+"' /></td>"+
				"<td><select  name='"+team+"_wkt_player_"+x+"' class='"+team+"_fal_wkt' id='"+team+"_wkt_player_"+x+"'><option value=''>Select Player</option></select></td>"+
				"<td><input type='text' class='allownumericwithdecimal runs_new' name='"+team+"_at_runs_"+x+"' /></td>"+
				"<td><input type='text' class='allownumericwithdecimal runs_new' name='"+team+"_at_over_"+x+"' /></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>"+
			"</tr>";
			$("#fall_of_wkt_"+team).append(fall_of_cnt);
			//var player_a_ids = "{{ $match_data[0]['player_b_ids']}}";
			if(team=='a')
			{
				var player_a_ids = $( "#team option:selected" ).val();
			}else
			{
				var player_a_ids = $( "#team option:not(:selected)" ).val();
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
									var val = x-1;
									$("#"+team+"_wkt_player_"+val).html(options);

							}
					});

	x++;
	if(team=='a')
	{
		$('#x').val(x);
		checkDuplicatePlayers('a_fal_wkt');
	}else{
		$('#z').val(x);
		checkDuplicatePlayers('b_fal_wkt');
	}
	allownumericwithdecimal();

}
//delete players
var deleted_ids=',';
function delete_row(team,status,id,value)
{
			$.confirm({
			title: 'Confirmation',
			content: "Are you sure you want to delete this Player?",
			confirm: function() {
	var row_count = $('[class ^= "team_'+team+'_'+status+'_open_row"]').size();
	if(team=='a' && status=='bat')
	{
		row_count--;
		team_a_del_score = $('#a_runs_'+value).val();
		SJ.SCORECARD.adjustScore('first','a',team_a_del_score,'sub');
		//delete wickets
		team_a_out_as = $('#a_outas_'+value).val();
		if (team_a_out_as!='' && team_a_out_as !='not_out')
		{
			SJ.SCORECARD.adjustWicket('first','a',1,'sub');
		}

	}else if(team=='a' && status=='bowl')
	{
		row_count--;
		//$('#a_bowler_count').val(row_count);
		team_a_noballs = $('#a_bowler_noball_'+value).val();
		team_a_wides = $('#a_bowler_wide_'+value).val();
		team_b_wide = $('#team_b_wide').val();
		team_b_noball = $('#team_b_noball').val();
		$('#team_b_wide').val(team_b_wide - team_a_wides);
		$('#team_b_noball').val(team_b_noball - team_a_noballs);

		var a_extra = 0;
		$('.b_extras').each(function() {
				a_extra += Number($(this).val());
			});
		$('#team_b_tot_extras').val(a_extra);

		team_a_del_overs = $('#a_bowler_overs_'+value).val();
                SJ.SCORECARD.adjustOver('first','b',team_a_del_overs,'sub');
                
	}else if(team=='b' && status=='bat')
	{
		row_count--;
		team_b_del_score = $('#b_runs_'+value).val();
                SJ.SCORECARD.adjustScore('first','b',team_b_del_score,'sub');
		//delete wickets
		team_b_out_as = $('#b_outas_'+value).val();
		if (team_b_out_as!='' && team_b_out_as !='not_out')
		{
			SJ.SCORECARD.adjustWicket('first','b',1,'sub');
		}
	}else if(team=='b' && status=='bowl')
	{
		row_count--;
		team_b_noballs = $('#b_bowler_noball_'+value).val();
		team_b_wides = $('#b_bowler_wide_'+value).val();
		team_a_wide = $('#team_a_wide').val();
		team_a_noball = $('#team_a_noball').val();
		$('#team_a_wide').val(team_a_wide - team_b_wides);
		$('#team_a_noball').val(team_a_noball - team_b_noballs);

		var b_extra = 0;
		$('.a_extras').each(function() {
				b_extra += Number($(this).val());
			});
		$('#team_a_tot_extras').val(b_extra);

		team_b_del_overs = $('#b_bowler_overs_'+value).val();
                SJ.SCORECARD.adjustOver('first','a',team_b_del_overs,'sub');
	}

	deleted_ids = deleted_ids+id+',';
	$('#deleted_ids').val(deleted_ids);
	$('#team_'+team+'_'+status+'_'+id).remove();
	},
			cancel: function() {
				// nothing to do
			}
		});
}

//team a score
$( "#player_tr_a" ).on( "keyup", ".team_a_score", function() {
	team_a_score = 0;
	extras = 0;
	$('.team_a_score').each(function() {
		team_a_score += Number($(this).val());
	});
	$('.a_extras').each(function() {
                extras += Number($(this).val());
        });
        SJ.SCORECARD.adjustScore('first','a',extras,'add',team_a_score);
});

//team b score
$( "#player_tr_b" ).on( "keyup", ".team_b_score", function() {
	team_b_score = 0;
	extras = 0;
	$('.team_b_score').each(function() {
                team_b_score += Number($(this).val());
        });
        $('.b_extras').each(function() {
                extras += Number($(this).val());
        });
        SJ.SCORECARD.adjustScore('first','b',extras,'add',team_b_score);
});

//team b bowler overs
$( "#bowler_tr_b" ).on( "keyup", ".team_b_overs", function() {
	team_b_overs = 0;
	before_decimal = 0;
	after_decimal = 0;
        $('.team_b_overs').each(function() {
                //team_b_overs += Number($(this).val());
                before_decimal += Number(String($(this).val()).split('.')[0] || 0);
                after_decimal += Number(String($(this).val()).split('.')[1] || 0);
        });
        beforeDecimal = parseInt(((before_decimal*6) + after_decimal)/6);
        afterDecimal = ((before_decimal*6) + after_decimal)%6;
        team_b_overs = beforeDecimal+'.'+afterDecimal;
        SJ.SCORECARD.adjustOver('first','a',0,'add',team_b_overs);
});

//team a bowler overs
$( "#bowler_tr_a" ).on( "keyup", ".team_a_overs", function() {
	team_a_overs = 0;
	before_decimal = 0;
	after_decimal = 0;
        $('.team_a_overs').each(function() {
                //team_b_overs += Number($(this).val());
                before_decimal += Number(String($(this).val()).split('.')[0] || 0);
                after_decimal += Number(String($(this).val()).split('.')[1] || 0);
        });
        beforeDecimal = parseInt(((before_decimal*6) + after_decimal)/6);
        afterDecimal = ((before_decimal*6) + after_decimal)%6;
        team_a_overs = beforeDecimal+'.'+afterDecimal;
        SJ.SCORECARD.adjustOver('first','b',0,'add',team_a_overs);
});


isFielderDisplay=1;
$('.team_a_wkt').each(function() {
	BowlerFielderDisplay('a',isFielderDisplay);
	isFielderDisplay++;
});


//team a wkt
$( "#player_tr_a" ).on( "change", ".team_a_wkt", function() {
	team_a_wkt = 0;
	isFielderDisplay = 1;
        $('.team_a_wkt').each(function() {
                if($(this).val()!='' && $(this).val()!='not_out')
                {
                        team_a_wkt ++;
                }
                BowlerFielderDisplay('a',isFielderDisplay);
                isFielderDisplay++;
        });
        SJ.SCORECARD.adjustWicket('first','a',0,'add',team_a_wkt);
});


isFielderDisplay=1;
$('.team_b_wkt').each(function() {
        BowlerFielderDisplay('b',isFielderDisplay);
        isFielderDisplay++;
});

//team b wkt
$( "#player_tr_b" ).on( "change", ".team_b_wkt", function() {
	team_b_wkt = 0;
	isFielderDisplay = 1;
        $('.team_b_wkt').each(function() {
                if ($(this).val()!='' && $(this).val()!='not_out')
                {
                        team_b_wkt ++;
                }
                BowlerFielderDisplay('b',isFielderDisplay);
                isFielderDisplay++;
        });
        SJ.SCORECARD.adjustWicket('first','b',0,'add',team_b_wkt);
});

function BowlerFielderDisplay(team,isFielderDisplay)
{
		out_as_value = $('#'+team+'_outas_'+isFielderDisplay).val();
			if(out_as_value=='' || out_as_value=='caught' || out_as_value=='run_out' || out_as_value=='stumped')
			{
				$('#'+team+'_fielder_'+isFielderDisplay).show();
				$('#'+team+'_fildershow_'+isFielderDisplay).hide();
			}else
			{
				$('#'+team+'_fielder_'+isFielderDisplay).hide();
				$('#'+team+'_fildershow_'+isFielderDisplay).show();
			}
			if(out_as_value=='not_out' || out_as_value=='handled_ball' || out_as_value=='obstructing_the_field' || out_as_value=='retired' || out_as_value=='run_out' || out_as_value=='timed_out')
			{
				$('#'+team+'_bowled_'+isFielderDisplay).hide();
				$('#'+team+'_bowlershow_'+isFielderDisplay).show();
			}
			else
			{
				$('#'+team+'_bowled_'+isFielderDisplay).show();
				$('#'+team+'_bowlershow_'+isFielderDisplay).hide();
			}
			
}

function team_score_calculator(classname,team,status)
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
				$('.'+team+'_extras').each(function() {
					extras += Number($(this).val());
				});
				team_score = team_score + extras;
			}
				$('#fst_ing_'+team+'_'+status).val(team_score);


	});
}
function teamWickets(name,team)
{
	$('.'+name).on('change',function(){
	team_a_wkts = 0;
	isFielderDisplay = 1;
	$('.'+name).each(function() {
				if($(this).val()!='' && $(this).val()!='not_out')
				{
					team_a_wkts ++;
				}
				BowlerFielderDisplay(team,isFielderDisplay);
				isFielderDisplay++;
			});
			$('#fst_ing_'+team+'_wkts').val(team_a_wkts);
	});
}
function save(status)
{
	var matchType = "{{$match_data[0]['match_type']}}";
        
	SJ.SCORECARD.initTeamStats();
        
	$('#hid_match_result').val($('#match_result').val());
	
	if(status=='fst_ing_click')
	{
		$("#firsting").ajaxForm({
			url: base_url+'/match/insertCricketScoreCard', 
			type: 'post',
			success: function(res) {
				if (matchType=='test')
				{
					saveIng('');
					$("#secondting").ajaxSubmit({
						url: base_url+'/match/insertCricketScoreCard', 
						type: 'post'
					});
				}
                                else
				{
					SJ.GLOBAL.reload();
				}
				
			}
		});
	}
}
var a_extra = 0;
$('.a_extras').each(function() {
					a_extra += Number($(this).val());
	});
$('#team_a_tot_extras').val(a_extra);

//team extras
$('.a_extras').keyup(function () {
	extras = 0;
	total = 0;
	$('.a_extras').each(function() {
		extras += Number($(this).val());
	});
        $('.team_a_score').each(function() {
                total += Number($(this).val());
        });

        $('#team_a_tot_extras').val(extras);
        SJ.SCORECARD.adjustScore('first','a',extras,'add',total);
});
var b_extra = 0;
$('.b_extras').each(function() {
        b_extra += Number($(this).val());
});
$('#team_b_tot_extras').val(b_extra);
//team extras
$('.b_extras').keyup(function () {
	extras = 0;
	total = 0;
	$('.b_extras').each(function() {
                extras += Number($(this).val());
        });
        $('.team_b_score').each(function() {
                total += Number($(this).val());
        });

        $('#team_b_tot_extras').val(extras);
        SJ.SCORECARD.adjustScore('first','b',extras,'add',total);
});

//team a wides
$('.b_wides').keyup(function () {
	b_wides = 0;
        $('.b_wides').each(function() {
                b_wides += Number($(this).val());
        });
        $('#team_a_wide').val(b_wides);

        var a_extra = 0;
        $('.a_extras').each(function() {
                a_extra += Number($(this).val());
        });
        $('#team_a_tot_extras').val(a_extra);
        total_score('team_a_tot_extras','team_a_score','a');
});

//team b wides
$('.a_wides').keyup(function () {
	a_wides = 0;
        $('.a_wides').each(function() {
                a_wides += Number($(this).val());
        });
        $('#team_b_wide').val(a_wides);
                var b_extra = 0;
        $('.b_extras').each(function() {
                        b_extra += Number($(this).val());
        });
        $('#team_b_tot_extras').val(b_extra);
        total_score('team_b_tot_extras','team_b_score','b');
});


//team a no balls
$('.b_no_balls').keyup(function () {
	b_no_balls = 0;
        $('.b_no_balls').each(function() {
                b_no_balls += Number($(this).val());
        });
        $('#team_a_noball').val(b_no_balls);

                        var a_extra = 0;
        $('.a_extras').each(function() {
                a_extra += Number($(this).val());
        });
        $('#team_a_tot_extras').val(a_extra);
        total_score('team_a_tot_extras','team_a_score','a');
});

//team b no balls
$('.a_no_balls').keyup(function () {
	a_no_balls = 0;
        $('.a_no_balls').each(function() {
                a_no_balls += Number($(this).val());
        });
        $('#team_b_noball').val(a_no_balls);
                                var b_extra = 0;
        $('.b_extras').each(function() {
                        b_extra += Number($(this).val());
        });
        $('#team_b_tot_extras').val(b_extra);
        total_score('team_b_tot_extras','team_b_score','b');
});
function wides(classname,team)
{
	$('.'+classname).keyup(function () {
		wide_noball = 0;
		$('.'+classname).each(function() {
			wide_noball += Number($(this).val());
		});
		$('#team_'+team+'_wide').val(wide_noball);
		var b_extra = 0;
		$('.'+team+'_extras').each(function() {
				b_extra += Number($(this).val());
		});
		$('#team_'+team+'_tot_extras').val(b_extra);
		total_score('team_'+team+'_tot_extras','team_'+team+'_score',team);
	});
}
function noballs(classname,team)
{
	$('.'+classname).keyup(function () {
		noball = 0;
		$('.'+classname).each(function() {
			noball += Number($(this).val());
		});
		$('#team_'+team+'_noball').val(noball);
		var b_extra = 0;
		$('.'+team+'_extras').each(function() {
				b_extra += Number($(this).val());
		});

		$('#team_'+team+'_tot_extras').val(b_extra);
		total_score('team_'+team+'_tot_extras','team_'+team+'_score',team);
	});

}
function total_score(idname,classname,team)
{
	var extras = parseInt($('#'+idname).val());
	score=0;
	$('.'+classname).each(function() {
			score += Number($(this).val());
		});
    SJ.SCORECARD.adjustScore('first',team,extras,'add',score);
}
</script>
