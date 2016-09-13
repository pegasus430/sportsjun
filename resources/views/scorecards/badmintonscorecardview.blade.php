@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')

<?php 
    $team_a_name = $user_a_name;
    $team_b_name = $user_b_name;

    $team_a_id=$match_data[0]['a_id'];
    $team_b_id=$match_data[0]['b_id'];

    $player_a_ids=$match_data[0]['player_a_ids'];
    $player_b_ids=$match_data[0]['player_b_ids'];

    $match_details=json_decode($match_data[0]['match_details']);
    $match_settings   =   Helper::getMatchSettings($match_data[0]['tournament_id'],$match_data[0]['sports_id']);

    isset($match_details->preferences)?$preferences=$match_details->preferences:[];
    
    // if(isset($preferences->number_of_sets))$set=$preferences->number_of_sets ;
    // else $set=3;

     $set=$match_settings->number_of_sets;

    ${'team_'.$match_data[0]['a_id'].'_score'}='0 sets';
    ${'team_'.$match_data[0]['b_id'].'_score'}='0 sets'; 

    $team_a_info='';
    $team_b_info='';

    if(isset($preferences)){
    $current_set=$match_details->current_set;

  ${'team_'.$preferences->left_team_id.'_score'}=$match_details->scores->{$preferences->left_team_id.'_score'} .' sets';
  ${'team_'.$preferences->right_team_id.'_score'}=$match_details->scores->{$preferences->right_team_id.'_score'} .' sets';
} else {
  $current_set=0;
}

if($match_data[0]['game_type']=='rubber'){
  ${'team_'.$team_a_id.'_score'}=$match_data[0]['a_score'];
  ${'team_'.$team_b_id.'_score'}=$match_data[0]['b_score'];
  }
  
?>



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
                <div class="team_score" id="team_{{$team_a_id}}_score">{{${'team_'.$team_a_id.'_score'} }} </div>
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
                <div class="team_score" id="team_{{$team_b_id}}_score">{{${'team_'.$team_b_id.'_score'} }} </div>
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
                                          <div class="team_score" id="team_{{$team_b_id}}_score">{{${'team_'.$team_b_id.'_score'} }} </div>
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
			<h5 class="scoreboard_title">Badminton Scorecard @if($match_data[0]['match_type']!='other')
											<span class='match_type_text'>({{ $match_data[0]['match_type']=='odi'?strtoupper($match_data[0]['match_type']):ucfirst($match_data[0]['match_type'])}}, {{ucfirst($match_data[0]['match_category']) }})</span>
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

      @elseif($match_data[0]['match_result'] == "washout")
                                                     <div class="form-group">
                                                         <label>MATCH ENDED DUE TO</label>
                                                         <h3 class="win_team">Washout</h3>
                                                     </div>

	@else

      <div class="form-group">
        <label>Winner is not updated</label>

      </div>

	@endif
    <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
	@include('scorecards.share')
        <p class="match-status">@include('scorecards.scorecardstatusview')</p>
    </div>

       <!-- Match has no results -->

@if($match_data[0]['game_type']!='rubber')
<!-- Start of normal match -->

  <div class="row">
    <div class="col-sm-12">
   <div class='table-responsive'>
      <table class='table table-striped table-bordered'>
        <thead>
          <tr class='team_fall team_title_head'>
           @if(!is_null($score_a_array['team_id']))  <th><b>TEAMS</b></th> @endif
             <th>PLAYERS</th>

             @for($set_index=1; $set_index<=$set; $set_index++)
              <th>SET {{$set_index}}</th>
             @endfor

          </tr>
        </thead>
        <tbody>
             <tr>
             <!-- Show teams if schedule type is team -->
            @if(!is_null($score_a_array['team_id']))<td><b>{{$score_a_array['team_name']}}</b></td>@endif

            <td><b>{{$score_a_array['player_name_a']}} / {{$score_a_array['player_name_b']}}</b></td>

          @for($set_index=1; $set_index<=$set; $set_index++)
                 
               <td class='a_set{{$set_index}} ' >
                  <span class='remove_button_left button_set_{{$set_index}}'></span>
                      {{$score_a_array['set'.$set_index]}}
                  <span class='add_button_left button_set_{{$set_index}}'></span>
               </td>
          @endfor
          
          </tr>

          <tr>
          <!-- Show teams if schedule type is team -->
          @if(!is_null($score_b_array['team_id']))<td><b>{{$score_b_array['team_name']}}</b></td>@endif

            <td><b>{{$score_b_array['player_name_a']}} / {{$score_b_array['player_name_b']}}</b></td>
            @for($set_index=1; $set_index<=$set; $set_index++)
                  
               <td class='b_set{{$set_index}}  '>
                   <span class='remove_button_right button_set_{{$set_index}}'></span>
                      {{$score_b_array['set'.$set_index]}}
                   <span class='add_button_right button_set_{{$set_index}}'></span>
                </td>
          @endfor
        </tr>

        </tbody>
        </tbody>
      </table>
    </div>
  <!-- End of normal match -->

@else 
  <!-- Start of Rubber -->

 @foreach($rubbers as $rubber)
    <?php
         $rubber_players = ScoreCard::getRubberPlayers($rubber->id);
         $rubber_a_array = $rubber_players['a'];
         $rubber_b_array = $rubber_players['b'];
    ?>
         @include('scorecards.badmintonrubberview')
 @endforeach
 
 @endif

 <!-- End of Rubber -->

    @if($match_data[0]['match_status']=='completed' && isset($display_users))


@if($match_data[0]['match_type']!='singles' )

<div class="row" id='real_time_scoring'>

  <div class="col-sm-6 col-xs-12 table-striped ">
        <h3 class='team_bat team_title_head'>{{$score_a_array['team_name']}}</h3>
            
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'> <a href="/editsportprofile/{{$score_a_array['user_id_a']}}" class="text-primary"> {{$score_a_array['player_name_a']}}</a></div>
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'><a href="/editsportprofile/{{$score_a_array['user_id_b']}}" class="text-primary"> {{$score_a_array['player_name_b']}}</a></div>
            <div class='col-xs-12'>&nbsp;</div>
        
      </div>
  

  <div class='col-sm-6 col-xs-12 table-striped'>
     <h3 class='team_fall team_title_head'>{{$score_a_array['team_name']}}</h3>
         
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'><a href="/editsportprofile/{{$score_b_array['user_id_a']}}" class="text-primary"> {{$score_b_array['player_name_a']}}</a></div>
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'><a href="/editsportprofile/{{$score_b_array['user_id_b']}}" class="text-primary"> {{$score_b_array['player_name_b']}}</a></div>
            <div class='col-xs-12'>&nbsp;</div>
      </div>
      
</div> 

 @else
<div class="row" id='real_time_scoring'>
  <div class="col-sm-6 col-xs-12">
    <h3 class='team_bat team_title_head'>&nbsp;</h3> 
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'><a href="/editsportprofile/{{$score_a_array['user_id_a']}}" class="text-primary"> {{$score_a_array['player_name_a']}}</a></div>
            <div class='col-xs-12'>&nbsp;</div>    
  </div>

  <div class='col-xs-12 col-sm-6'>
    <h3 class='team_fall team_title_head'>&nbsp;</h3>      
                <div class='col-xs-12'>&nbsp;</div>
                <div class='col-xs-12'><a href="/editsportprofile/{{$score_b_array['user_id_a']}}" class="text-primary"> {{$score_b_array['player_name_a']}}</a></div>    
    
  </div>
</div>


    @endif
@endif

	
	<!-- if match schedule type is team -->

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
            data: {'scorecard_status': status,'match_id':match_id,'rej_note':rej_note,'sport_name':'Badminton'},
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


function getMatchDetails(){

  var data={
    match_id:{{$match_data[0]['id']}}
  }

  var base_url=base_url || secure_url;
        $.ajax({
            url:  base_url+'/viewpublic/match/getBadmintonDetails', 
            type:'get', 
            dataType:'json',
            data:data,
            success:function(response){
                var left_team_id=response.preferences.left_team_id;
                var right_team_id=response.preferences.right_team_id;

                  $.each(response.match_details, function(key, value){
                      $('.a_'+key).html(value[left_team_id+'_score']);
                      $('.b_'+key).html(value[right_team_id+'_score']);
                  })
            }
        })
}

@if($match_data[0]['match_status']!='completed')
//window.setInterval(getMatchDetails, 10000);
@endif

</script>


<!-- Put plus and minus buttons on left and rights of sets -->

@endsection
