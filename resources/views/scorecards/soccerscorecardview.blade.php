@extends('layouts.app')
@section('content')
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
								<!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
							{!! Helper::Images($team_a_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								@else
								<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
								{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
							
								</td>
								@endif
								@else
								<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">-->	
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
								<!--<img  class="img-responsive img-circle" alt="" width="110" height="110" src="{{ url('/uploads/teams/'.$team_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
								{!! Helper::Images($team_b_logo['url'],'teams',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								@else
								<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
										{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								</td>
								@endif
								@else
							<!--	<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">	-->
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
	{!! Form::open(array('url' => 'match/insertSoccerScoreCard', 'method' => 'POST')) !!}
    
    <div class="container pull-up">        
    
    <div class="panel panel-default">
    	<div class="col-md-12">
        <h5 class="scoreboard_title">Soccer Scorecard</h5>
		
        <div class="clearfix"></div>
		<div class="form-inline">
				@if($match_data[0]['winner_id']>0)

                          <div class="form-group">
                            <label class="win_head">Winner</label>
                            <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>

                          </div>
					
				@else
				@if($match_data[0]['is_tied']>0)
					
					    <div class="form-group">
                            <label>Match Result : </label>
                            <h3 class="win_team">{{ 'Tie' }}</h3>

                      </div>  
				@else
					

                          <div class="form-group">
                            <label>Winner is Not Updated</label>
                           
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
                        <th style="width: 25%;">Players</th>
						<th>{{ trans('message.scorecard.soccer_fields.goal') }}</th>
                        <th>{{ trans('message.scorecard.soccer_fields.yellow_card') }}</th>
                        <th>{{ trans('message.scorecard.soccer_fields.red_card') }}</th>
                       
                    </tr>
                </thead>
                <tbody id="team_tr_a" >	
            <?php $a_count = 1;?>
                @if(count($team_a_soccer_scores_array)>0)
                @foreach($team_a_soccer_scores_array as $team_a_soccer)
                <tr>
                    <td style="width: 25%; white-space: pre-line;"><a href="{{ url('/showsportprofile',[$team_a_soccer['user_id']]) }}">{{(!empty($player_name_array[$team_a_soccer['user_id']]))?$player_name_array[$team_a_soccer['user_id']]:''}}</a></td>
					<td>{{ (!empty($team_a_soccer['goals_scored']))?$team_a_soccer['goals_scored']:'' }}</td>
					
                 
					
					 <td>{{ (!empty($team_a_soccer['yellow_cards']))?$team_a_soccer['yellow_cards']:'' }}</td>
					
                 
				    <td>{{ (!empty($team_a_soccer['red_cards']))?$team_a_soccer['red_cards']:'' }}</td>
                    
                </tr>
                @endforeach
                @else
                <tr>
				<td colspan="5">{{ 'No Records.' }}</td>

                </tr>
                @endif
            </tbody>		
            </table>
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
                    <th style="width: 25%;">Players</th>
					 <th>{{ trans('message.scorecard.soccer_fields.goal') }}</th>	
                    <th>{{ trans('message.scorecard.soccer_fields.yellow_card') }}</th>
                    <th>{{ trans('message.scorecard.soccer_fields.red_card') }}</th>
                   		
                </tr>
            </thead>
            <tbody id="team_tr_b" >	
            <?php $b_count = 1; ?>
                @if(count($team_b_soccer_scores_array)>0)
                @foreach($team_b_soccer_scores_array as $team_b_soccer)
                <tr>
                     <td style="width: 25%; white-space: pre-line;"><a href="{{ url('/showsportprofile',[$team_b_soccer['user_id']]) }}">{{(!empty($player_name_array[$team_b_soccer['user_id']]))?$player_name_array[$team_b_soccer['user_id']]:''}}</a></td>
					
					 <td>{{ (!empty($team_b_soccer['goals_scored']))?$team_b_soccer['goals_scored']:'' }}</td>
					 
				
					
					<td>{{ (!empty($team_b_soccer['yellow_cards']))?$team_b_soccer['yellow_cards']:'' }}</td>
					
					<td>
					{{(!empty($team_b_soccer['red_cards']))?$team_b_soccer['red_cards']:''}}
					</td>
					
                   
                </tr>
                @endforeach
                @else
                <tr>
				<td colspan="4">{{'No Records.'}}</td>

                </tr>
				@endif
            </tbody>
        </table>
        </div>
		
    </div>
	<!-- Team B Goals End-->
	
    
    </div>
	<div class="sportsjun-forms text-center scorecards-buttons">
	<input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
	@if($isValidUser && $isApproveRejectExist)
		

	
	
	 <button style="text-align:center;" type="button" onclick="scoreCardStatus('approved');" class="button green">Approve</button>
        <button style="text-align:center;" type="button" onclick="scoreCardStatus('rejected');" class="button black">Reject</button><br />	
        <textarea name="rej_note" id="rej_note" rows="4" cols="50" placeholder="Reject Note" style="margin:20px 0 10px 0;"></textarea>
	 @endif
</div>

	 
</div>


<script>
//Send Approve
function scoreCardStatus(status)
{
		var msg = ' Reject ';
	if(status=='approved')
		var msg = ' Approve ';
	$.confirm({
	title: 'Confirmation',
	content: 'Are You Sure You Want To '+msg+' This ScoreCard?',
	confirm: function() {
		match_id = $('#match_id').val();
		rej_note = $('#rej_note').val();
		$.ajax({
            url: base_url+'/match/scoreCardStatus',
            type: "post",
            data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'Soccer'},
            success: function(data) {
                if(data.status == 'success') {
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
