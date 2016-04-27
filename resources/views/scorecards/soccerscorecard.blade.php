@extends('layouts.app')
@section('content')
    <style type="text/css">
        .alert{display: none;}
		.show_teams{display: none;}
    </style>
		@if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
        @endif
		<?php $team_a_count = 'Red Card Count:'.$team_a_red_count.','.'Yellow Card Count:'.$team_a_yellow_count;?>
		<?php $team_b_count = 'Red Card Count:'.$team_b_red_count.','.'Yellow Card Count:'.$team_b_yellow_count;?>
        
<div class="col_standard soccer_scorecard">
	<div id="team_vs" class="ss_bg">
    	<div class="container">
        	<div class="row">
                <div class="team team_one col-xs-5">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                        <div class="team_logo">
							@if(!empty($team_a_logo))
								@if($team_a_logo['url']!='')
								<!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/no_logo.png') }}';">-->
							{!! Helper::Images($team_a_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								@else
							<!--	<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">-->
						{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								@endif
								@else
							<!--	<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">	-->
						{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								@endif
                                </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="team_detail">
                            <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $team_a_name }}</a></div>
                            <div class="team_city">{{ $team_a_city }}</div>
                            <div class="team_score" id="team_a_score">{{$team_a_goals}} <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_a_count;?>"></i></span></div>
							
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <span class="vs"></span>
                </div>
                <div class="team team_two col-xs-5">
					<div class="row">
                        <div class="col-md-8 col-sm-12 visible-md visible-lg">
                        	<div class="team_detail">
                            <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
							 <div class="team_city">{{ $team_b_city }}</div>
							<div class="team_score" id="team_b_score">{{$team_b_goals}} <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_b_count;?>"></i></span></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                        <div class="team_logo">
                        	@if(!empty($team_b_logo))
								@if($team_b_logo['url']!='')
							<!--	<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/no_logo.jpg') }}';">-->
						{!! Helper::Images($team_b_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								@else
								<!--<img  class="img-responsive img-circle" height="110" width="110" src="{{ asset('/images/no_logo.jpg') }}">-->
							{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								</td>
								@endif
								@else
								<!--<img  class="img-responsive img-circle" height="110" width="110" src="{{ asset('/images/no_logo.png') }}">	-->
							{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								@endif
                                </div>
                        </div>
                         <div class="col-md-8 col-sm-12 visible-xs visible-sm">
                        	<div class="team_detail">
                            <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
							 <div class="team_city">{{ $team_b_city }}</div>
							<div class="team_score" id="team_b_score">{{$team_b_goals}} <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_b_count;?>"></i></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-xs-12">
                	<div class="match_loc">
                    	{{ date('jS F , Y',strtotime($match_data[0]['match_start_date'])).' - '.date("g:i a", strtotime($match_data[0]['match_start_time'])).(($match_data[0]['facility_name']!='')?' , '.$match_data[0]['facility_name']:'').(($match_data[0]['address']!='')?' , '.$match_data[0]['address']:'') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
	{!! Form::open(array('url' => 'match/insertSoccerScoreCard', 'method' => 'POST','id'=>'soccer')) !!}
    
    <div class="container pull-up">        
    
    <div class="row">
    	<div class="col-md-12">
        <h5 class="scoreboard_title">Soccer Scorecard</h5>
        <div class="clearfix"></div>
		<div class="form-inline">        
				@if($match_data[0]['match_status']=='completed' && $match_data[0]['winner_id']>0)

                          <div class="form-group">
                          	<label class="win_head">Winner</label> 
                                <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>
                           

                          </div>
					
				@else
				@if($match_data[0]['match_status']=='completed' && $match_data[0]['is_tied']>0)
					    <div class="form-group">
                            <label>Match Result : </label>
                            {{ 'Tie' }}

                      </div> 
				@else
					
					
                          <div class="form-group">
                            <label for="match_result">Match Result:</label>
                            <select class="form-control selectpicker" name="match_result" id="match_result" onchange="getTeam();">
                                <option value="" >Select</option>
                                <?php if(empty($match_data[0]['tournament_round_number'])) { ?>
                                <option <?php if($match_data[0]['is_tied']>0) echo " selected";?> value="tie" >Tie</option>
                                <?php } ?>
                                <option <?php if($match_data[0]['is_tied']==0 && $match_data[0]['winner_id']>0) echo " selected";?> value="win">win</option>
                            </select>
                          </div>
                          <div class="form-group" style="margin-top:15px;">
                            <label class="show_teams">Select Winner:</label>
                            <select name="winner_id" id="winner_id" class="show_teams form-control selectpicker" onchange="selectWinner();">
                                        <option <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['a_id']) echo ' selected';?> value="{{ $match_data[0]['a_id'] }}" >{{ $team_a_name }}</option>
                                        <option <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['b_id']) echo ' selected';?> value="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</option>
                                    </select>
                          </div>
				@endif	
				@endif	
                <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
				<p class="match-status">@include('scorecards.scorecardstatus')</p>
		</div>		
        </div>
        </div>
    <div class="row">
	<!-- Team A Goals Start-->
    <div class="col-sm-8 col-sm-offset-2">
    	<h3 id='team_a' class="team_bat table_head">{{ $team_a_name }}</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead">
                    <tr>
                        <th>Select Player</th>
						 <th>{{ trans('message.scorecard.soccer_fields.goal') }}</th>
                        <th>{{ trans('message.scorecard.soccer_fields.yellow_card') }}</th>
                        <th>{{ trans('message.scorecard.soccer_fields.red_card') }}</th>
						<th></th>
                    </tr>
                </thead>
                <tbody id="team_tr_a" >	
            <?php $a_count = 1;?>
                @if(count($team_a_soccer_scores_array)>0)
                @foreach($team_a_soccer_scores_array as $team_a_soccer)
                <tr id="team_a_row_{{$team_a_soccer['id']}}" class="team_a_goal_row">
                    <!--<td>{!! Form::select('a_player_'.$a_count,$team_a,null,array('class'=>'gui-input select_player_a','id'=>'a_player_'.$a_count)) !!}</td>-->
                    <td><select name="a_player_{{ $a_count }}" class="gui-input select_player_a" id="a_player_{{ $a_count }}">
                    @foreach($team_a as $key => $team)
                    <option value="{{ $key }}" <?php if (isset($team_a_soccer['user_id']) && $team_a_soccer['user_id']==$key) echo ' selected';?>>{{ $team }}</option>
                    @endforeach
                    </select></td>
					<input type="hidden" name="hid_a_player_{{ $a_count }}" value="{{$team_a_soccer['user_id']}}">
					<input type="hidden" name="hid_a_primary_id_{{ $a_count }}" value="{{$team_a_soccer['id']}}">					
					<td>{!! Form::text('a_goal_'.$a_count, (!empty($team_a_soccer['goals_scored']))?$team_a_soccer['goals_scored']:'', array('class'=>'gui-input allownumericwithdecimal team_a_goals','style'=>'width: inherit','id'=>'a_goal_'.$a_count)) !!}</td>					
					<td><select name="a_yellow_card_{{ $a_count }}" class="gui-input" id="a_yellow_card_{{ $a_count }}">
						<option value="0" <?php if (isset($team_a_soccer['yellow_cards']) && $team_a_soccer['yellow_cards']=='0') echo ' selected';?>>0</option>
						<option value="1" <?php if (isset($team_a_soccer['yellow_cards']) && $team_a_soccer['yellow_cards']=='1') echo ' selected';?>>1</option>
                    </select></td>
					
                   <!-- <td>{!! Form::text('a_red_card_'.$a_count, (!empty($team_a_soccer['red_cards']))?$team_a_soccer['red_cards']:'', array('class'=>'gui-input allownumericwithdecimal','id'=>'a_red_card_'.$a_count)) !!}</td>-->
				    <td><select name="a_red_card_{{ $a_count }}" class="gui-input" id="a_red_card_{{ $a_count }}">
						<option value="0" <?php if (isset($team_a_soccer['red_cards']) && $team_a_soccer['red_cards']=='0') echo ' selected';?>>0</option>
						<option value="1" <?php if (isset($team_a_soccer['red_cards']) && $team_a_soccer['red_cards']=='1') echo ' selected';?>>1</option>
                    </select></td>
						<td><a href="#" onclick="deleteRow('a',{{$team_a_soccer['id']}},{{$a_count}});"  class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></a></td>
                   
                </tr>
                <?php $a_count++;?>
                @endforeach
                @else
                <tr class="team_a_goal_row">
                    <td>{!! Form::select('a_player_'.$a_count,$team_a,null,array('class'=>'gui-input select_player_a','id'=>'a_player_'.$a_count)) !!}</td>
					<td>{!! Form::text('a_goal_'.$a_count, null, array('class'=>'gui-input team_a_goals allownumericwithdecimal form-control input-sm','style'=>'width: inherit','id'=>'a_goal_'.$a_count)) !!} </td>                                                  						 
					<td><select name="a_yellow_card_{{ $a_count }}" class="gui-input" id="a_yellow_card_{{ $a_count }}">
						<option value="0">0</option>
						<option value="1">1</option>
                    </select></td>
                    <td>
                        
						 
						<select name="a_red_card_{{ $a_count }}" class="gui-input" id="a_red_card_{{ $a_count }}">
							<option value="0">0</option>
							<option value="1">1</option>
						</select>
                         
                      
                     </td>
					<td></td>
                </tr>
                @endif
            </tbody>		
            </table>
    	</div>
        <div id="a_goal">
        	<a onclick="addNewRow('a',<?php echo $a_count;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>
        </div>
    </div>
	<!-- Team A Goals End-->
	
	<!-- Team B Goals Start-->
    <div class="col-sm-8 col-sm-offset-2">
    <h3 id='team_b' class="team_bowl table_head">{{ $team_b_name }}</h3>
        <div class="table-responsive">
        <table class="table table-striped">
            <thead class="thead">
                <tr>
                    <th>Select Player</th>
					 <th>{{ trans('message.scorecard.soccer_fields.goal') }}</th>	
                    <th>{{ trans('message.scorecard.soccer_fields.yellow_card') }}</th>
                    <th>{{ trans('message.scorecard.soccer_fields.red_card') }}</th>
                   	<td></td>	
                </tr>
            </thead>
            <tbody id="team_tr_b" >	
            <?php $b_count = 1; ?>
                @if(count($team_b_soccer_scores_array)>0)
                @foreach($team_b_soccer_scores_array as $team_b_soccer)
                <tr id="team_b_row_{{$team_b_soccer['id']}}" class="team_b_goal_row">
                    
                    <td><select name="b_player_{{ $b_count }}" class="gui-input select_player_b" id="a_player_{{ $b_count }}">
                    @foreach($team_b as $b_key => $b_value)
                    <option value="{{ $b_key }}" <?php if (isset($team_b_soccer['user_id']) && $team_b_soccer['user_id']==$b_key) echo ' selected';?>>{{ $b_value }}</option>
                    @endforeach
                    </select></td>
					
					<input type="hidden" name="hid_b_player_{{ $b_count }}" value="{{$team_b_soccer['user_id']}}">
					<input type="hidden" name="hid_b_primary_id_{{ $b_count }}" value="{{$team_b_soccer['id']}}">
					 <td>{!! Form::text('b_goal_'.$b_count, (!empty($team_b_soccer['goals_scored']))?$team_b_soccer['goals_scored']:'', array('class'=>'gui-input allownumericwithdecimal team_b_goals','style'=>'width: inherit','id'=>'b_goal_'.$b_count)) !!}</td>					 
					<td><select name="b_yellow_card_{{ $b_count }}" class="gui-input" id="b_yellow_card_{{ $b_count }}">
						<option value="0" <?php if (isset($team_b_soccer['yellow_cards']) && $team_b_soccer['yellow_cards']=='0') echo ' selected';?>>0</option>
						<option value="1" <?php if (isset($team_b_soccer['yellow_cards']) && $team_b_soccer['yellow_cards']=='1') echo ' selected';?>>1</option>
                    </select></td>
					
					<td>
					<select name="b_red_card_{{ $b_count }}" class="gui-input" id="b_red_card_{{ $b_count }}">
							<option value="0" <?php if (isset($team_b_soccer['red_cards']) && $team_b_soccer['red_cards']=='0') echo ' selected';?>>0</option>
							<option value="1" <?php if (isset($team_b_soccer['red_cards']) && $team_b_soccer['red_cards']=='1') echo ' selected';?>>1</option>
						</select>
					</td>
					<td><a href="#" onclick="deleteRow('b',{{$team_b_soccer['id']}},{{$b_count}});" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-remove"></i></a></td>

                </tr>
                <?php $b_count++;?>
                @endforeach
                @else
                <tr class="team_b_goal_row">
                    <td>{!! Form::select('b_player_1',$team_b,null,array('class'=>'gui-input select_player_b','id'=>'b_player_1')) !!}</td>
					                    <td>{!! Form::text('b_goal_1', null, array('class'=>'gui-input team_b_goals allownumericwithdecimal form-control input-sm','id'=>'b_goal_1')) !!}    
                        <!--{!! Form::text('b_goal_1', null, array('class'=>'gui-input allownumericwithdecimal','id'=>'b_goal_1')) !!}-->
                   </td>
                    <td><select name="b_yellow_card_1" class="gui-input" id="b_yellow_card_1">
						<option value="0">0</option>
						<option value="1">1</option>
                    </select></td>
					
					<td>
					<select name="b_red_card_1" class="gui-input" id="b_red_card_1">
							<option value="0">0</option>
							<option value="1">1</option>
						</select>
					</td>
					<td></td>
                </tr>
				@endif
            </tbody>
        </table>
        </div>
		
			<div id="b_goal">
            	<a onclick="addNewRow('b',<?php echo $b_count;?>);" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More</a>
            </div>	
    </div>
	<!-- Team B Goals End-->
	
    
    </div>
	<input type="hidden" id="soccer_form_data" value="">
	 <input type="hidden" name="team_a_count" value="{{ (count($team_a_soccer_scores_array)>0)?count($team_a_soccer_scores_array):1 }}" id="team_a_count">
	 <input type="hidden" name="team_b_count" value="{{ (count($team_b_soccer_scores_array)>0)?count($team_b_soccer_scores_array):1 }}" id="team_b_count">
	  <input type="hidden" name="tournament_id" value="{{ $match_data[0]['tournament_id'] }}">
	 <input type="hidden" name="team_a_id" value="{{ $match_data[0]['a_id'] }}" id="team_a_id">
	 <input type="hidden" name="team_b_id" value="{{ $match_data[0]['b_id'] }}" id="team_b_id">
	 <input type="hidden" name="match_id" id='match_id' value="{{ $match_data[0]['id'] }}">
	 <input type="hidden" name="team_b_name" value="{{ $team_b_name }}" id="team_b_name">
	 <input type="hidden" name="team_a_name" value="{{ $team_a_name }}" id="team_a_name">
	 <input type="hidden" name="winner_team_id" value="" id="winner_team_id">
	 <input type="hidden" name="delted_ids" value="" id="delted_ids">
     <div class="sportsjun-forms text-center scorecards-buttons">
     <center>
     		<ul class="list-inline sportsjun-forms">
			@if($isValidUser)
				<li>
					<button type="submit" class="button btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
				</li>
			@endif	
                
    @if($isValidUser && $isForApprovalExist)
		
	<button style="text-align:center;" type="button" onclick="forApproval();" class="button btn-primary">Send Score for Approval</button>

	@endif   
	
    
	{!!Form::close()!!}
	<li>
                                <!-- Adding already existing player-->
                                @include('scorecards.addplayer') 
                            </li>
                            <li>
                                <!-- Adding unknown Players-->
                                @include('scorecards.addunknownplayer')
                            </li>
                        </ul>
                        </center>
						  </div>
						  </div>
</div>
 <input type="hidden" name="i" value="{{ (count($team_a_soccer_scores_array)>0)?$a_count:2 }}" id="i">
 <input type="hidden" name="j" value="{{ (count($team_b_soccer_scores_array)>0)?$b_count:2 }}" id="j">

<script>
$(document).ready(function(){
getTeam();
});
function getTeam()
{
	var value = $( "#match_result" ).val();
	if(value=='win')
	{
		$("label.show_teams").show();
		$('#winner_id').selectpicker('show');
		selectWinner();
	}else
	{
		$("label.show_teams").hide();
		$('#winner_id').selectpicker('hide');
		$('#winner_team_id').val('');
	}
}
function selectWinner()
{
	$('#winner_team_id').val($('#winner_id').val());
        $("#winner_id").hide();
}
var team_a_count='{{ (count($team_a_soccer_scores_array)>0)?count($team_a_soccer_scores_array):1 }}';
var team_b_count='{{ (count($team_b_soccer_scores_array)>0)?count($team_b_soccer_scores_array):1 }}';
allownumericwithdecimal();
checkDuplicatePlayers('select_player_a');
checkDuplicatePlayers('select_player_b');
function addNewRow(team,i)
{

	if(team=='a')//team a
	{
		
		var get_player_countt= $('[class ^= "team_a_goal_row"]').size();
		var team_cnt = '{{ $team_a_count}}';
		if(get_player_countt >= team_cnt)
		{
			 $.alert({
				title: 'Alert!',
				content: 'All the team players are already added to the scorecard.'
			});
			//alert('Players Exceeded.');
			return false;
		}
		team_a_count++;
		$('#team_a_count').val(team_a_count);
		var i=$('#i').val();
	}
	else//team b
	{
		
		var get_player_countt= $('[class ^= "team_b_goal_row"]').size();
		var team_cnt = '{{ $team_b_count}}';
		if(get_player_countt >= team_cnt)
		{
			 $.alert({
				title: 'Alert!',
				content: 'All the team players are already added to the scorecard.'
			});
			//alert('Players Exceeded.');
			return false;
		}
		team_b_count++;
		$('#team_b_count').val(team_b_count);
		var i=$('#j').val();
	}
	var newContent = "<tr class='team_"+team+"_goal_row'><td><select  class='gui-input select_player_"+team+"' name='"+team+"_player_"+i+"' id='"+team+"_player_"+i+"'><option value=''>Select Player</option></select></td>"+
	"<td><input type='text' class='gui-input allownumericwithdecimal team_"+team+"_goals' name='"+team+"_goal_"+i+"' /></td>"+
					//"<td><input type='text' class='gui-input allownumericwithdecimal' name='"+team+"_yellow_card_"+i+"' /></td>"+
					//"<td><input type='text' class='gui-input allownumericwithdecimal'  name='"+team+"_red_card_"+i+"' /></td>"+
					"<td><select name='"+team+"_yellow_card_"+i+"' id='"+team+"_yellow_card_"+i+"'><option value='0'>0</option><option value='1'>1</option></select></td>"+
					"<td><select name='"+team+"_red_card_"+i+"' id='"+team+"_red_card_"+i+"'><option value='0'>0</option><option value='1'>1</option></select></td><td></td>"+
					
					"</tr>";
					
					$("#team_tr_"+team).append(newContent);
					if(team=='a')//team a
					{
						var player_a_ids = "{{ $match_data[0]['player_a_ids'] }}";
					}
					else//team b
					{
						var player_a_ids = "{{ $match_data[0]['player_b_ids'] }}";
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
									var val = i-1;
									$("#"+team+"_player_"+val).html(options);
								   
							}
					});
					
	if(team=='a')//team a
	{
		i++;
		$('#i').val(i);
		individual_team_score('a','team_a_goals');
		
	}else//team
	{
		i++;
		$('#j').val(i);
		individual_team_score('b','team_b_goals');
		
	}	
	
	checkDuplicatePlayers('select_player_'+team);
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
				content: 'Duplicate Player Selected.'
			});			
			$(this).val('');  
			
		}			
	 });
}

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

$('.team_a_goals').keyup(function () {
	team_a_goal = 0;
$('.team_a_goals').each(function() {
			team_a_goal += Number($(this).val());
		});
		$('#team_a_score').html(team_a_goal);
});	
$('.team_b_goals').keyup(function () {
	team_b_goal = 0;
$('.team_b_goals').each(function() {
			team_b_goal += Number($(this).val());
		});
		$('#team_b_score').html(team_b_goal);
});			

function individual_team_score(team,selected_class)
{
	//individual total team calculattion with player goals_scored
	  $('.'+selected_class).keyup(function () {
		// initialize the sum (total price) to zero
		var sum = 0;
		 
		// we use jQuery each() to loop through all the textbox with 'price' class
		// and compute the sum for each loop
		$('.'+selected_class).each(function() {
			sum += Number($(this).val());
		});
		// set the computed value to 'totalPrice' textbox
		$('#team_'+team+'_score').html(sum);
		 
	});
}

//delete row 
var deleted_ids = ',';
function deleteRow(team,id,value)
{
	$.confirm({
		title: 'Confirmation',
		content: "Are you sure you want to delete this Player?",
		confirm: function() {
				var row_count = $('[id ^= "team_'+team+'_row_"]').size();
				if(team=='a')//team a
				{
					row_count--;
					$('#team_a_count').val(row_count);
					
					player_a_goals = $('#a_goal_'+value).val();
					team_tot_goals = $('#team_a_score').html();
					$('#team_a_score').html(team_tot_goals - player_a_goals);//deletes row run remove from total score runs
				}
				else//team b
				{
					row_count--;
					$('#team_b_count').val(row_count);
					
					player_b_goals = $('#b_goal_'+value).val();
					team_tot_goals = $('#team_b_score').html();
					$('#team_b_score').html(team_tot_goals - player_b_goals);//deletes row run remove from total score runs
				}
				deleted_ids = deleted_ids+id+',';
				$('#delted_ids').val(deleted_ids);
				
				$('#team_'+team+'_row_'+id).remove();
		},
			cancel: function() {
				// nothing to do
			}
	});
}

//Send Approval
function forApproval()
{
	var winner_id = $('#match_result').val();
	var db_winner_id = "{{$match_data[0]['winner_id']}}";
	var is_tied = "{{$match_data[0]['is_tied']}}";
	if(winner_id=='' || (db_winner_id=='' && is_tied==0))
	{
		$.alert({
            title: 'Alert!',
            content: 'Select Match Result & Save.'
        });
		return false;
	}
		$.confirm({
	title: 'Confirmation',
	content: 'Are You Sure You want to Send Score for Approval ?',
	confirm: function() {
		match_id = $('#match_id').val();
		$.ajax({
            url: base_url+'/match/scoreCardStatus',
            type: "post",
            data: {'scorecard_status': 'approval_pending','match_id':match_id},
            success: function(data) {
                if(data.status == 'success') {
						// $.alert({
							// title: 'Alert!',
							// content: data.msg
						// });
					window.location.href = base_url+'/match/scorecard/edit/'+match_id;
                }
            }
		});
	},
	cancel: function() {
			// nothing to do
		}
	});
}

</script>


@endsection
