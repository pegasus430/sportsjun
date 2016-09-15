@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')

<div class="col_standard table_tennis_scorcard">
    <div id="team_vs" class="tt_bg">
      <div class="container">
          <div class="row">
                <div class="team team_one col-xs-5">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                        	<div class="team_logo">
                       
						 @if($user_a_logo['url']!='')
							<!--<img class="img-responsive img-circle" width="270" height="204" src="{{ url('/uploads/'.$upload_folder.'/'.$user_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
						  {!! Helper::Images($user_a_logo['url'],$upload_folder,array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
							@else
							<!--<img  class="img-responsive img-circle" width="270" height="204" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
							 {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
						@endif
                        	</div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                        	<div class="team_detail">
	                    @if($match_data[0]['schedule_type']=='player')
                          <div class="team_name"><a href="{{ url('/editsportprofile/'.$match_data[0]['a_id'])}}">{{ $user_a_name }}</a></div>
						@else
							<div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $user_a_name }}</a></div>
						@endif
							  <div class="team_city">{!! $team_a_city !!}</div>
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
                        @if($match_data[0]['schedule_type']=='player')
                          <div class="team_name"><a href="{{ url('/editsportprofile/'.$match_data[0]['b_id'])}}">{{ $user_b_name }}</a></div>
						@else
							<div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $user_b_name }}</a></div>
						@endif
							 <div class="team_city">{!! $team_b_city !!}</div>
                            </div>
                        </div>
                              <div class="col-md-4 col-sm-12">
                              	<div class="team_logo">
                                
								
								 @if($user_b_logo['url']!='')
                <!--<img class="img-responsive img-circle" width="270" height="204" src="{{ url('/uploads/'.$upload_folder.'/'.$user_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
			 {!! Helper::Images($user_b_logo['url'],$upload_folder,array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
                @else
               <!-- <img  class="img-responsive img-circle"width="270" height="204" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
		   	 {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
              @endif
              </div>
                              </div>
                              <div class="col-md-8 col-sm-12 visible-xs visible-sm">
                        		<div class="team_detail">
                            		@if($match_data[0]['schedule_type']=='player')
                                      <div class="team_name"><a href="{{ url('/editsportprofile/'.$match_data[0]['b_id'])}}">{{ $user_b_name }}</a></div>
                                    @else
                                        <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['b_id'] }}">{{ $user_b_name }}</a></div>
                                    @endif
                                         <div class="team_city">{!! $team_b_city !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             @if(!is_null($match_data[0]['tournament_id']))
                <div class='row'>
                    <div class='col-xs-12'>
                        <div class='match_loc'>
                            <a href="/tournaments/groups/{{$tournamentDetails['id']}}">
                            {{$tournamentDetails['name']}} Tournament
                          </a>  
                        </div>
                    </div>
                </div>
                @endif
                
            <div class="row">
              <div class="col-xs-12">
                  <div class="match_loc">
                      {{ date('jS F , Y',strtotime($match_data[0]['match_start_date'])).' - '.date("g:i a", strtotime($match_data[0]['match_start_time'])).(($match_data[0]['facility_name']!='')?' , '.$match_data[0]['facility_name']:'').(($match_data[0]['address']!='')?' , '.$match_data[0]['address']:'') }}
                    </div>
                </div>
            </div>
			<h5 class="scoreboard_title">TableTennis Scorecard @if($match_data[0]['match_type']!='other')
											<span class='match_type_text'>({{ $match_data[0]['match_type']=='odi'?strtoupper($match_data[0]['match_type']):ucfirst($match_data[0]['match_type']) }}, {{ucfirst($match_data[0]['match_category']) }})</span>
									@endif</h5>
        </div>
          @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
          @endif
    </div>

  <div class="container">
    <div class="row">
      <div class="col-md-12">
    <div class="form-inline">    
	@if($match_data[0]['winner_id']>0)
	<div class="form-group">
        <label class="win_head">Winner</label>
        <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$user_a_name:$user_b_name }}</h3>
    </div>
	@else

      <div class="form-group">
        <label>Winner is not updated</label>

      </div>

	@endif
    <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
	@include('scorecards.share')
        <p class="match-status">@include('scorecards.scorecardstatus')</p>
    </div>

    <div class="table-responsive">
    	<table class="table table-striped">
        <thead class="thead">
    		<tr id="sets">
    			  <th>{{ trans('message.scorecard.tennis_fields.team') }}</th>
				  @if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set1']!='' || $score_b_array[0]['set1']!='' ))
    				<th>{{ trans('message.scorecard.tennis_fields.set1') }}</th>
				@endif
				@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set2']!='' || $score_b_array[0]['set2']!=''))
    				<th>{{ trans('message.scorecard.tennis_fields.set2') }}</th>
				@endif
				@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set3']!='' || $score_b_array[0]['set3']!=''))
    				<th>{{ trans('message.scorecard.tennis_fields.set3') }}</th>
				@endif
				@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set4']!='' || $score_b_array[0]['set4']!=''))
    				<th>{{ trans('message.scorecard.tennis_fields.set4') }}</th>
				@endif
				@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set5']!='' || $score_b_array[0]['set5']!=''))
    				<th>{{ trans('message.scorecard.tennis_fields.set5') }}</th>
				@endif
    		</tr>
      </thead>
    		<tbody>
    			<tr id="set_a">
    				<td>
              @if($user_a_logo['url']!='')
             <!--   <img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/'.$upload_folder.'/'.$user_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
		  {!! Helper::Images($user_a_logo['url'],$upload_folder,array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
                @else
                <!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
			 {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user  fa-2x','height'=>36,'width'=>36) )!!}	
              @endif
              {{ $user_a_name }}
    				</td>
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set1']!='' || $score_b_array[0]['set1']!=''))
    				<td>{{(!(empty($score_a_array[0]['set1'])))?$score_a_array[0]['set1']:''}}</td>
					@endif
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set2']!='' || $score_b_array[0]['set2']!=''))
    				<td>{{(!(empty($score_a_array[0]['set2'])))?$score_a_array[0]['set2']:''}}</td>
					@endif
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set3']!='' || $score_b_array[0]['set3']!=''))
    				<td>{{(!(empty($score_a_array[0]['set3'])))?$score_a_array[0]['set3']:''}}</td>
					@endif
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set4']!='' || $score_b_array[0]['set4']!=''))
    				<td>{{(!(empty($score_a_array[0]['set4'])))?$score_a_array[0]['set4']:''}}</td>
					@endif
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set5']!='' || $score_b_array[0]['set5']!=''))
    				<td>{{(!(empty($score_a_array[0]['set5'])))?$score_a_array[0]['set5']:''}}</td>    
					@endif
					</tr>
    			<tr id="set_b">
    				<td>
              @if($user_b_logo['url']!='')
                <!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/'.$upload_folder.'/'.$user_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
			 {!! Helper::Images($user_b_logo['url'],$upload_folder,array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
                @else
                <!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
			 {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
              @endif
              {{ $user_b_name }}
    				</td>
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set1']!='' || $score_b_array[0]['set1']!=''))
    					<td>{{(!(empty($score_b_array[0]['set1'])))?$score_b_array[0]['set1']:''}}</td>
					@endif
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set2']!='' || $score_b_array[0]['set2']!=''))
    					<td>{{(!(empty($score_b_array[0]['set2'])))?$score_b_array[0]['set2']:''}}</td>
					@endif
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set3']!='' || $score_b_array[0]['set3']!=''))
    					<td>{{(!(empty($score_b_array[0]['set3'])))?$score_b_array[0]['set3']:''}}</td>
					@endif
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set4']!='' || $score_b_array[0]['set4']!=''))
    					<td>{{(!(empty($score_b_array[0]['set4'])))?$score_b_array[0]['set4']:''}}</td>
					@endif
					@if((!empty($score_a_array[0]) || !empty($score_b_array[0])) && ($score_a_array[0]['set5']!='' || $score_b_array[0]['set5']!=''))
    					<td>{{(!(empty($score_b_array[0]['set5'])))?$score_b_array[0]['set5']:''}}</td>
					@endif
            </tr>
    		</tbody>
    	</table>
    </div>
	
	<!-- if match schedule type is team -->
	@if($match_data[0]['schedule_type']!='player')
	
	<div class="row">
    	<div class="col-md-6">			
        	<h4 class="team_title_head">{{ $user_a_name }} Players</h4>
			<table class="table table-striped team_players_check">
			<tbody>
			
			@foreach($a_players as $a_player)
			
			<tr>
			@if($match_data[0]['match_type']=='singles')
			<td></td>
			@else
			@if($match_data[0]['match_type']=='doubles')
			<td></td>
			@endif
			@endif
			<td>
			@if($team_a_player_images[$a_player['id']]['url']!='')
  						
			{!! Helper::Images($team_a_player_images[$a_player['id']]['url'],'user_profile',array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
  			@else
  							
			{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
  			@endif
			
			<a href="{{ url('/showsportprofile',$a_player['id']) }}">{{$a_player['name']}}</a>
			</td>
			</tr>
			
			@endforeach
				
			</tbody>
		</table>
        </div>
        <div class="col-md-6">
        	<h4 class="team_title_head">{{ $user_b_name }} Players</h4>
			<table class="table table-striped team_players_check">
			<tbody>
			@foreach($b_players as $b_player)

				<tr>
					@if($match_data[0]['match_type']=='singles')
					<td></td>
					@else
					@if($match_data[0]['match_type']=='doubles')
					<td></td>
					@endif
					@endif
				<td>
				@if($team_b_player_images[$b_player['id']]['url']!='')
							
				{!! Helper::Images($team_b_player_images[$b_player['id']]['url'],'user_profile',array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
				@else
								
				{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-2x','height'=>36,'width'=>36) )!!}	
				@endif
				<a href="{{ url('/showsportprofile',$b_player['id']) }}">{{$b_player['name']}}</a>
				</td>
				</tr>
			@endforeach
			</tbody>
		</table>
       	</div>
	</div>
	
	@endif
	<!-- end -->
	
    <div class="sportsjun-forms text-center scorecards-buttons">
	<input type="hidden" name="match_id" id="match_id" value="{{$match_data[0]['id']}}">
	@if($isValidUser && $isApproveRejectExist)
		
	<button style="text-align:center;" type="button" onclick="scoreCardStatus('approved');" class="button green">Approve</button>
	<button style="text-align:center;" type="button" onclick="scoreCardStatus('rejected');" class="button black">Reject</button><br />	
	
	<textarea name="rej_note" id="rej_note" rows="4" cols="50" placeholder="Reject Note" style="margin:20px 0 10px 0;"></textarea>
    @endif
    </div>
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
            data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'Table Tennis'},
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
