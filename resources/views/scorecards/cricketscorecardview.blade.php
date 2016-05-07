@extends('layouts.app')
@section('content')
<div class="col_standard cricket_scorcard">
	
    <div id="team_vs" class="cs_bg">
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
								<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">	-->
							{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								@endif
                                </div>
                        </div>
                       <div class="col-md-8 col-sm-12">
                        	<div class="team_detail">
                               <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $team_a_name }}</a></div>
								<div class="team_city">{{$team_a_city}}</div>
                                <div class="team_score"> @if($match_data[0]['match_type']=='test') {{'I st'}} @endif {{($team_a_fst_ing_score>0)?$team_a_fst_ing_score:0}}/{{($team_a_fst_ing_wkt>0)?$team_a_fst_ing_wkt:0}} <span>({{(($team_a_fst_ing_overs>0)?$team_a_fst_ing_overs:0).' ovs'}})</span>
                                
                                @if($match_data[0]['match_type']=='test')
                                II nd {{($team_a_scnd_ing_score>0)?$team_a_scnd_ing_score:0}}/{{($team_a_scnd_ing_wkt>0)?$team_a_scnd_ing_wkt:0}} <span>({{(($team_a_scnd_ing_overs>0)?$team_a_scnd_ing_overs:0).' ovs'}})</span>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <span class="vs"></span>
                </div>
                <div class="team team_two col-xs-5">
					<div class="row">
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
								<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/no_logo.png') }}">	-->
							{!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
								@endif
                               </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                        	<div class="team_detail">
                                <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $team_b_name }}</a></div>
								<div class="team_city">{{$team_b_city}}</div>
                                <div class="team_score"> @if($match_data[0]['match_type']=='test') {{'I st'}} @endif {{($team_b_fst_ing_score>0)?$team_b_fst_ing_score:0}}/{{($team_b_fst_ing_wkt>0)?$team_b_fst_ing_wkt:0}} <span>({{(($team_b_fst_ing_overs>0)?$team_b_fst_ing_overs:0).' ovs'}})</span>
                                
                                @if($match_data[0]['match_type']=='test')
                                II nd {{($team_b_scnd_ing_score>0)?$team_b_scnd_ing_score:0}}/{{($team_b_scnd_ing_wkt>0)?$team_b_scnd_ing_wkt:0}} <span>({{(($team_b_scnd_ing_overs>0)?$team_b_scnd_ing_overs:0).' ovs'}})</span>
                                @endif
                                </div>
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


		
        <div class="container">	
	<div class="panel panel-default">	
            <div class="panel-body">
            	<h5 class="scoreboard_title">Cricket Scorecard @if($match_data[0]['match_type']!='other')
											<span class='match_type_text'>({{ $match_data[0]['match_type']=='odi'?strtoupper($match_data[0]['match_type']):ucfirst($match_data[0]['match_type']) }})</span>
									@endif</h5>
                
                <div class="form-inline">
                         @if($match_data[0]['winner_id']>0)

							  <div class="form-group">
								<label class="win_head">Winner</label>
                                <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>

							  </div>
						
					@else
					@if($match_data[0]['is_tied']>0)
							<div class="form-group">
								<label>Match Result</label>
                                <h3 class="win_team">{{ 'Tie' }}</h3>

						  </div>   
					@else
                          <div class="form-group">
					  <label>Winner is not updated.</label>
							</div>
					@endif	
					@endif	
                        <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
                        @include('scorecards.share')
                        <p class="match-status">@include('scorecards.scorecardstatus')</p>
                        </div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#first_innings" data-toggle="tab" aria-expanded="true">Ist Innings</a></li>
                    @if($match_data[0]['match_type']=='test')
                    <li class=""><a href="#second_innings" data-toggle="tab" aria-expanded="false">2nd Innings </a></li>
                    @endif
                </ul>
                <div  class="tab-content">
                    <div id="first_innings" class="tab-pane fade active in">
                    
                    <!-- /.panel-heading -->
                        @include('scorecards.cricketfirstinningsview')
                           
                        </ul>
                        </center>
                    </div>
                    @if($match_data[0]['match_type']=='test')
                    <div id="second_innings" class="tab-pane fade" >
                        
                        @include('scorecards.cricketsecondinningsview')
                        
                        </ul>
                        </center>
                        </div>
                    </div>
                    @endif
                    <!-- /.panel-body -->
                </div>
	<input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
	@if($isValidUser && $isApproveRejectExist)	
		
        <div class="sportsjun-forms text-center">
           <button type="button" onclick="scoreCardStatus('approved');" class="button btn-primary">Approve</button>
            <button type="button" onclick="scoreCardStatus('rejected');" class="button btn-secondary">Reject</button><br/>
			 <textarea name="rej_note" id="rej_note" rows="4" cols="50" placeholder="Reject Note" style="margin:20px 0 10px 0;"></textarea>
        </div>
	@endif		
        </div>
		</div> 
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
            data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'Cricket'},
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