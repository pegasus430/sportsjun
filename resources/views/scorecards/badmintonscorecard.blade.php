@extends('layouts.app')
@section('content')
<?php 
    $team_a_name = $user_a_name;
		$team_b_name = $user_b_name;

    $team_a_id=$match_data[0]['a_id'];
    $team_b_id=$match_data[0]['b_id'];

    $player_a_ids=$match_data[0]['player_a_ids'];
    $player_b_ids=$match_data[0]['player_b_ids'];

    $match_details=json_decode($match_data[0]['match_details']);

    isset($match_details->preferences)?$preferences=$match_details->preferences:[];
    
    if(isset($preferences->number_of_sets))$set=$preferences->number_of_sets ;
    else $set=3;

    ${'team_'.$match_data[0]['a_id'].'_score'}='0 sets';
    ${'team_'.$match_data[0]['b_id'].'_score'}='0 sets'; 

    $team_a_info='';
    $team_b_info='';

    if(isset($preferences)){
    $current_set=$match_details->current_set;

    $player_or_team_left_button_add= "<button class='btn button_add btn-circle btn-sm  btn-circle btn-sm pull-left' id='score_team_a' team_id='$preferences->left_team_id' table_score_id='{$score_a_array['id']}' onclick='return addScore(this)'> <i class='fa fa-plus'></i></button>";

    $player_or_team_left_button_remove="  <button team_id='$preferences->left_team_id' table_score_id='{$score_a_array['id']}' onclick='return removeScore(this)'class='btn button_remove btn-circle btn-sm pull-right'> <i class='fa fa-minus'></i> </button>";

    $player_or_team_right_button_add= "<button class='btn button_add btn-circle btn-sm  btn-circle btn-sm pull-left' id='score_team_b' team_id='$preferences->right_team_id' table_score_id='{$score_b_array['id']}' onclick='return addScore(this)'> <i class='fa fa-plus'></i></button>";

    $player_or_team_right_button_remove=" <button team_id='$preferences->right_team_id' table_score_id=    '{$score_b_array['id']}' onclick='return removeScore(this)'class='btn button_remove btn-circle btn-sm pull-right'> <i class='fa fa-minus'></i> </button>";

  ${'team_'.$preferences->left_team_id.'_score'}=$match_details->scores->{$preferences->left_team_id.'_score'}.' sets';
  ${'team_'.$preferences->right_team_id.'_score'}=$match_details->scores->{$preferences->right_team_id.'_score'}.' sets';
  }else{
    $player_or_team_left_button_add='';
    $player_or_team_left_button_remove='';
    $player_or_team_right_button_remove='';
    $player_or_team_right_button_add='';
    $current_set='';
  }
?>

<style>
   
    input:read-only { 
    background-color: #f4f4f4;

}
.button_add, .button_add:hover, .button_add:active{
   color:green;
  background: none;
  border: 0px #fff inset;
}
.button_remove, .button_remove:hover, .button_add:active{
  color:red;
  background: none;
  border: 0px #fff inset;
}


</style>
<div class="col_standard table_tennis_scorcard">
    <div id="team_vs" class="tt_bg">
      <div class="container">
          <div class="row">
                <div class="team team_one col-xs-5">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                        	<div class="team_logo">
                       
						 @if($user_a_logo['url']!='')
							<!--<img class="img-responsive img-circle" width="110" height="110" src="{{ url('/uploads/'.$upload_folder.'/'.$user_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
							{!! Helper::Images($user_a_logo['url'],$upload_folder,array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
							@else
							<!--<img  class="img-responsive img-circle" width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
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
              <div class="team_score" id="team_{{$team_a_id}}_score">{{${'team_'.$team_a_id.'_score'} }} <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_a_info;?>"></i></span></div>
						  
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
              <div class="team_score" id="team_{{$team_b_id}}_score">{{${'team_'.$team_b_id.'_score'} }} <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_b_info;?>"></i></span></div>
                            </div>
                        </div>
                              <div class="col-md-4 col-sm-12">
                              	<div class="team_logo">
                                
                                
								
								 @if($user_b_logo['url']!='')
              <!--  <img class="img-responsive img-circle" width="110" height="110" src="{{ url('/uploads/'.$upload_folder.'/'.$user_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
		  	{!! Helper::Images($user_b_logo['url'],$upload_folder,array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
                @else
              <!--  <img  class="img-responsive img-circle"width="110" height="110" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
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
              <div class="team_score" id="team_{{$team_b_id}}_score">{{${'team_'.$team_b_id.'_score'} }} <span><i class="fa fa-info-circle soccer_info" data-toggle="tooltip" title="<?php echo $team_b_info;?>"></i></span></div>
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
											<span class='match_type_text'>({{ $match_data[0]['match_type']=='odi'?strtoupper($match_data[0]['match_type']):ucfirst($match_data[0]['match_type'])}} , {{ucfirst($match_data[0]['match_category'] )}})</span>
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
    @if($match_data[0]['hasSetupSquad'] && $match_data[0]['match_status']!='completed' )
          <br>
          <button class="btn btn-danger soccer_buttons_disabled" onclick="return SJ.SCORECARD.soccerSetTimes(this)"></i>End Match</button>
    @endif
 @if($isValidUser && $isForApprovalExist && ($match_data[0]['winner_id']>0 || $match_data[0]['is_tied']>0 || $match_data[0]['has_result'] == 0))    
      <button style="text-align:center;" type="button" onclick="forApproval();" class=" btn btn-primary">Send Score for Approval</button>
      @endif


	@if($match_data[0]['match_status']=='completed')
	<div class="form-group">
    	<label class="win_head">Winner</label> 
        <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$user_a_name:$user_b_name }}</h3>
    </div>
	@else

     

	@endif
    <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src=" {{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
        @include('scorecards.share')
        <p class="match-status">@include('scorecards.scorecardstatus')</p>
    </div>

    <!--<a onclick="createnewset({{ $i=1 }});" style="float:right;">(Add More Sets)</a>-->
 
<!-- Set Preferences -->
  @if(!$match_data[0]['hasSetupSquad'])
    <div class='row'>
      <div class='col-sm-10 col-sm-offset-1'>
          <div class=''>
          <form id='form_preferences' onsubmit="return savePreferences(this)">
               <h3 class=""><center>Player Preferences</center></h3>

               <div class='row'>
                  <div class='col-sm-6'>
                      <!-- Select players for the left side -->
                    <h3 class="team_bat team_title_head">Left Side</h3>
                      @if($match_data[0]['schedule_type']=='team')
                        <label>Select Team</label>
                        <select class='form-control select-picker' name='team_left' onchange='getTeamPlayers(this)' side='left' id='team_left'>
                            <option value="{{$match_data[0]['a_id']}}" selected="" >{{$user_a_name}}</option>
                            <option value="{{$match_data[0]['b_id']}}"  >{{$user_b_name}}</option>
                        </select>

                        <br>
                        <label>Select Player</label>
                        <select name='select_player_1_left' class="form-control select-picker" id='select_player_1_left'>                    
                         @foreach($a_players as $player)
                              <option value="{{$player['id']}}">{{$player['name']}}</option>
                        @endforeach
                        </select>

                        <!-- if doubles select another player -->

                        @if($match_data[0]['match_type']=='doubles')
                          <br>
                        <label>Select Player 2</label>
                        <select name='select_player_2_left' class="form-control select-picker" id='select_player_2_left'>                     
                            @foreach($a_players as $player)
                                  <option value="{{$player['id']}}">{{$player['name']}}</option>
                            @endforeach
                        </select>
                        @endif


                      @elseif($match_data[0]['schedule_type']=='player')
                            <select name='select_player_1_left' class="form-control select-picker">
                            <option value="{{$match_data[0]['a_id']}}" selected="">{{$user_a_name}}</option>
                            <option value="{{$match_data[0]['b_id']}}" >{{$user_b_name}}</option>
                            </select>
                      @endif

                  </div>
<!-- Choose right Sight -->
                  <div class='col-sm-6'>
                    <h3 class="team_fall team_title_head">Right Side</h3>
                  @if($match_data[0]['schedule_type']=='team')
                        <label>Select Team</label>
                        <select class='form-control select-picker' onchange='getTeamPlayers(this)' side='right' id='team_right' name='team_right' >
                            <option value="{{$match_data[0]['a_id']}}" >{{$user_a_name}}</option>
                            <option value="{{$match_data[0]['b_id']}}" selected="" >{{$user_b_name}}</option>
                        </select>

                        <br>

                        <label>Select Player</label>
                        <select name='select_player_1_right' class="form-control select-picker" id='select_player_1_right'>                     
                            @foreach($b_players as $player)
                                  <option value="{{$player['id']}}">{{$player['name']}}</option>
                            @endforeach
                        </select>
                        <!-- if doubles select another player -->
                   
                        @if($match_data[0]['match_type']=='doubles')
                              <br>
                            <label>Select Player 2</label>
                            <select name='select_player_2_right' class="form-control select-picker" id='select_player_2_right'>      

                                @foreach($b_players as $player)

                                      <option value="{{$player['id']}}">{{$player['name']}}</option>

                                @endforeach
                            </select>
                        @endif


                     @elseif($match_data[0]['schedule_type']=='player')
                            <select name='select_player_1_right' class="form-control select-picker">
                            <option value="{{$match_data[0]['a_id']}}">{{$user_a_name}}</option>
                            <option value="{{$match_data[0]['b_id']}}" selected="">{{$user_b_name}}</option>
                            </select>
                      @endif
                  </div>
               </div>

              <br>
              <div class='row'>
            
                  <div class='col-sm-4 col-xs-4'><label>Serving Side</label></div>
                  <div class='col-sm-4 col-xs-4'><input type='radio' name='saving_side' value='left' checked id='choose_left'> Left</div>
                  <div class='col-sm-4 col-xs-4'><input type='radio' name='saving_side' value='right' id='choose_right'> Right</div>
               
              </div>

<!-- Game Preferences -->
              <div class='row'>
                <div class="col-sm-12">
                  <h3><center>Game Preferences</center></h3>
                </div>
              </div>

              <div class='row'>
                   <div class='col-sm-6'>
                        <label>Number of Sets</label>
                        <select class='form-control select-picker' name='number_of_sets'>
                          <option value='1'>1</option>
                          <option value='2'>2</option>
                          <option value='3' selected="">3</option>
                          <option value='4'>4</option>
                          <option value='5'>5</option>
                        </select>

                        <br>
                        <input type='checkbox' name='enable_two_points' checked="" id='enable_two_points'> <label for='enable_two_points'>Enable Two points clear pattern</label>
                    </div>

                    <div class='col-sm-6'>
                        <label>Score to Win</label>
                        <input placeholder="eg. 21" type='text' name='score_to_win' class="form-control" required="">

                        <br>
                        <label>Set End Point</label>
                        <input placeholder="eg. 29" type='text' name='set_end_point' class="form-control gui-input" required="">


                    </div>
                </div>
<!-- End of Game Preference -->

              <!-- Save -->

                <div class="row">
                <div class='col-sm-12'><br>
                <input type='hidden' value="{{$match_data[0]['id']}}" name='match_id'>
                 <input type='hidden' value="{{$match_data[0]['tournament_id']}}" name='tournament_id'>
                <input type='hidden' value="{{$team_a_name}}" name='team_a_name'>
                <input type='hidden' value="{{$team_b_name}}" name='team_b_name'>
                <center><input type='submit' name='submit_preferences' value='SAVE' class="btn btn-primary"></center><br>
                </div>    
                </div>        
              </form>

          </div>
      </div>
    </div>

  @else

   {!! Form::open(array('url' => '', 'method' => 'POST','id'=>'badminton', 'onsubmit'=>'return manualScoring(this)')) !!}

   <!-- show alert for no results -->

@if(!$match_data[0]['has_result'])
    <div class='row' >
      <div class="col-sm-8 col-sm-offset-2" style="background:#ffeeee">
        <div class='col-sm-12 alert alert-danger'>
                    This match has  been saved as 'no result'. All the changes and records for this match shall be discarded after approval.
                </div>
      </div>
    </div>  

@endif

  <div class="row">
    <div class='col-sm-12'>
     <span class='pull-right'>   
        <a href='javascript:void(0)' onclick="enableManualEditing(this)" style="color:#123456;">edit <i class='fa fa-pencil'></i></a> 
        <span> &nbsp; &nbsp; </span>
        <a href='javascript:void(0)' onclick="updatePreferences(this)" style='color:#123456;'> settings <i class='fa fa-gear fa-danger'></i></a>
        <span> &nbsp; &nbsp; </span>
    </span>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
   <div class='table-responsive'>
      <table class='table table-striped table-bordered'>
        <thead>
          <tr class='team_bat team_title_head'>
             <th>PLAYERS</th>
             
            @for($set_index=1; $set_index<=$set; $set_index++)
              <th>SET {{$set_index}}</th>
            @endfor
             
          </tr>
        </thead>
        <tbody>
          <tr>

            <td>{{$score_a_array['player_name_a']}} / {{$score_a_array['player_name_b']}}</td>
            
          @for($set_index=1; $set_index<=$set; $set_index++)
            <td>
                <span class='hidden-xs pull-left remove_button_left left_button_remove_set_{{$set_index}}'></span>
                 <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new a_set{{$set_index}}" value="{{$score_a_array['set'.$set_index]}}" name='a_set{{$set_index}}'>
                <span class='hidden-xs pull-right add_button_left left_button_add_set_{{$set_index}}'></span>
            </td>
          @endfor
        </tr>

          <tr>
            <td>{{$score_b_array['player_name_a']}} / {{$score_b_array['player_name_b']}}</td>

            @for($set_index=1; $set_index<=$set; $set_index++)
              <td>
                <span class='hidden-xs pull-left remove_button_right right_button_remove_set_{{$set_index}}'></span>
                  <input  readonly class="gui-input validation allownumericwithdecimal tennis_input_new b_set{{$set_index}}" value="{{$score_b_array['set'.$set_index]}}" name='b_set{{$set_index}}'>
                <span class='hidden-xs pull-right add_button_right right_button_add_set_{{$set_index}}'></span>
              </td>
            @endfor
        </tr>

        </tbody>



      </table>
    </div>
  </div>
</div>

<input type='hidden' value='{{$set}}' name="number_of_sets">
<input type='hidden' value="{{$match_data[0]['id']}}" name='match_id'>
<input type='hidden' value="{{$score_a_array['id']}}" name='score_a_id'>
<input type='hidden' value="{{$score_b_array['id']}}" name='score_b_id'>

<div class="row" id='saveButton'>
    <div class='col-sm-12'>
       <center> <input type='submit' class="btn btn-primary" value="Save"></center>
    </div>
</div>

</form>

 {!! Form::open(array('url' => '', 'method' => 'POST','id'=>'endMatchForm', 'onsubmit'=>'return endMatch(this)')) !!}




@if($match_data[0]['match_type']!='singles' )

<div class="row" id='real_time_scoring'>
  <div class="col-sm-6 col-xs-12 table-striped ">
        <h3 class='team_bat team_title_head'>{{$score_a_array['team_name']}}</h3>
       <div class='col-xs-9'>       
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'>{{$score_a_array['player_name_a']}}</div>
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'>{{$score_a_array['player_name_b']}}</div>
            <div class='col-xs-12'>&nbsp;</div>
        </div>

       <div class='col-xs-3 visible-xs-block'>
           <br>
          <button class='btn btn-success btn-circle btn-sm  btn-circle btn-sm pull-right' id='score_team_b' team_id="{{$preferences->left_team_id}}" table_score_id="{{$score_a_array['id']}}" onclick='return addScore(this)'> + 
          </button>

              <br><br>
          <button team_id="{{$preferences->left_team_id}}" table_score_id="{{$score_a_array['id']}}" onclick='return removeScore(this)'class='btn btn-danger btn-circle btn-sm pull-right'> <i class='fa fa-minus'></i> </button>
            <br>&nbsp;
       </div>
  </div>

  <div class='col-sm-6 col-xs-12 table-striped'>
     <h3 class='team_fall team_title_head'>{{$score_a_array['team_name']}}</h3>
      <div class='col-xs-9'>       
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'><b>{{$score_b_array['player_name_a']}}</b></div>
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'><b>{{$score_b_array['player_name_b']}}</b></div>
            <div class='col-xs-12'>&nbsp;</div>


      </div>
      <div class='col-xs-3 visible-xs-block '>
              <br>
            <button class='btn btn-success btn-circle btn-sm  btn-circle btn-sm pull-right' id='score_team_b' team_id="{{$preferences->right_team_id}}" table_score_id="{{$score_b_array['id']}}" onclick='return addScore(this)'> + </button>

              <br><br>
            <button team_id="{{$preferences->right_team_id}}" table_score_id="{{$score_b_array['id']}}" onclick='return removeScore(this)' class='btn btn-danger btn-circle btn-sm pull-right'><i class='fa fa-minus'></i></button>
        <br>&nbsp;
      </div>
  </div>
</div>


 @else
<div class="row" id='real_time_scoring'>
  <div class="col-sm-6 col-xs-12">
    <h3 class='team_bat team_title_head'>&nbsp;</h3>
      
      <div class='col-xs-9'>       
            <div class='col-xs-12'>&nbsp;</div>
            <div class='col-xs-12'>{{$score_a_array['player_name_a']}}</div>
            <div class='col-xs-12'>&nbsp;</div>

      </div>
      <div class='col-xs-3 visible-xs-block'>         
               <br>
          <button class='btn btn-success btn-circle btn-sm  btn-circle btn-sm pull-right' id='score_team_b' team_id="{{$preferences->left_team_id}}" table_score_id="{{$score_a_array['id']}}" onclick='return addScore(this)'> + 
          </button>

              <br><br>
          <button  team_id="{{$preferences->left_team_id}}" table_score_id="{{$score_a_array['id']}}" onclick='return removeScore(this)'  class='btn btn-danger btn-circle btn-sm pull-right'> <i class='fa fa-minus'></i> </button>
            <br>&nbsp;
        </div>
      </div>

  <div class='col-xs-12 col-sm-6'>
    <h3 class='team_fall team_title_head'>&nbsp;</h3>
       <div class='col-xs-9'>
                <div class='col-xs-12'>&nbsp;</div>
                <div class='col-xs-12'>{{$score_b_array['player_name_a']}}</div>                    
        
      </div>
      <div class='col-xs-3 visible-xs-block' >
        <br>  
        <!-- Add score button  -->
      <button class='btn btn-success btn-circle btn-sm  btn-circle btn-sm pull-right' id='score_team_b' team_id="{{$preferences->right_team_id}}" table_score_id="{{$score_b_array['id']}}" onclick='return addScore(this)'> + </button>
        <!-- Remove score button -->
        <br><br>
      <button team_id="{{$preferences->right_team_id}}" table_score_id="{{$score_b_array['id']}}" onclick='return removeScore(this)' class='btn btn-danger btn-circle btn-sm pull-right'><i class='fa fa-minus'></i></button>
        <br>&nbsp;
    </div>
</div>


 @endif


<!-- End match modal -->
  <div id="end_match" class="modal fade">
            <div class="modal-dialog sj_modal sportsjun-forms">
              <div class="modal-content">
                <div class="alert alert-danger" id="div_failure1"></div>
                <div class="alert alert-success" id="div_success1" style="display:none;"></div>
                <div class="modal-body">

                  <div class='row'>
                 
                    <div class='col-sm-12'>
                          <div class="form-group col-sm-4 col-sm-offset-2 ">
                                  <label>Match Result</label>
                                  <select name='match_result' required="" class='form-control gui-input' onchange='SJ.SCORECARD.selectMatchType(this)'>
                                  <option value="" disabled>Select</option>
                                  <option value="win">Win</option>

                                  <option value='washout' {{$match_data[0]['has_result']==0?'selected':''}}>No Result</option>
                                  </select>                           
                          </div>
                     
                          <div class="form-group col-sm-4  " id='select_winner'>
                            <label>Select Winner:</label>
                            <select name="winner_id" id="winner_id" class="form-control gui-input" onchange="selectWinner();">
                            <option value="">Select</option>
                            <option value="{{ $match_data[0]['a_id'] }}" <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['a_id']) echo ' selected';?>>{{ $user_a_name }}</option>
                            <option value="{{ $match_data[0]['b_id'] }}" <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['b_id']) echo ' selected';?>>{{ $user_b_name }}</option>
                            </select>
                          </div>
                      </div>
                      
                      
                    </div>
                    <br>

                    </div>
                   

                  <div class="modal-footer">
                    <button class='btn btn-primary  ' onclick="" type='submit'> Save</button>
                    <button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
      

	

	<!-- end -->
		<input type="hidden" id="badminton_form_data" value="">
    	{!! Form::hidden('user_id_a',$match_data[0]['a_id'],array('class'=>'gui-input ')) !!}
    	{!! Form::hidden('user_id_b',$match_data[0]['b_id'],array('class'=>'gui-input ')) !!}
    	{!! Form::hidden('player_ids_a',$match_data[0]['player_a_ids'],array('class'=>'gui-input ')) !!}
    	{!! Form::hidden('player_ids_b',$match_data[0]['player_b_ids'],array('class'=>'gui-input ')) !!}
    	{!! Form::hidden('match_type',$match_data[0]['match_type'],array('class'=>'gui-input ')) !!}
    	{!! Form::hidden('tournament_id',$match_data[0]['tournament_id'],array('class'=>'gui-input ')) !!}
    	{!! Form::hidden('match_id',$match_data[0]['id'],array('class'=>'gui-input','id'=>'match_id')) !!}
    	{!! Form::hidden('player_name_b', $user_b_name,array('class'=>'gui-input ')) !!}
    	{!! Form::hidden('player_name_a',$user_a_name,array('class'=>'gui-input ')) !!}
    	{!! Form::hidden('is_singles',$is_singles,array('class'=>'gui-input ')) !!}
		{!! Form::hidden('schedule_type',$match_data[0]['schedule_type'],array('class'=>'gui-input')) !!}
    	  <input type="hidden" id="winner_team_id" name="winner_team_id" value="">
		  <input type="hidden" id="is_winner_inserted" name="is_winner_inserted" value="{{$match_data[0]['winner_id']}}">
          
          <div class="sportsjun-forms text-center scorecards-buttons">
		@if($isValidUser)
          
				
		@endif

		  
    {!!Form::close()!!}
	@if($isValidUser && $match_data[0]['schedule_type']=='team')
				<!-- Adding already existing player-->
				@include('scorecards.addplayer') 
				<!-- Adding unknown Players-->
				@include('scorecards.addunknownplayer')
			@endif
 </div>			
     <input type="hidden" name="i" value="2" id="i">
   </div>
   </div>
  </div>
</div>


      
  <div id="updatePreferencesModal" class="modal fade">
            <div class="modal-dialog sj_modal sportsjun-forms">
              <div class="modal-content">
                <div class="alert alert-danger" id="div_failure1"></div>
                <div class="alert alert-success" id="div_success1" style="display:none;"></div>
                <div class="modal-body">

                <form onsubmit="" id='updatePreferencesForm'>
                  <div class='row'>
                     <div class="col-sm-12"><center><h3>Update Preferences</h3> </center></div>
                    <div class='col-sm-4'>
                        <label>Number of Sets</label>
                        <select class='form-control select-picker' name='number_of_sets'>
                            <option value=1  {{$preferences->number_of_sets==1?'selected':''}}>1</option>
                            <option value=2  {{$preferences->number_of_sets==2?'selected':''}}>2</option>
                            <option value=3 {{$preferences->number_of_sets==3?'selected':''}}>3</option>
                            <option value=4 {{$preferences->number_of_sets==4?'selected':''}}>4</option>
                            <option value=5 {{$preferences->number_of_sets==5?'selected':''}}>5</option>
                        </select>
                      {!!csrf_field()!!}
                      <input type='hidden' name='match_id' value="{{$match_data[0]['id']}}">
                                           
                    </div>

                    <div class='col-sm-4'>
                        <label>Score to Win</label>
                        <input type='text' name='score_to_win' class="form-control gui-input allownumericwithdecimal" required="" value="{{$preferences->score_to_win}}" readonly="">

                    </div>

                    <div class='col-sm-4'>
                        <label>Set End Point</label>
                        <input type='text' name='set_end_point' class="form-control gui-input allownumewithdecimal" required="" value="{{$preferences->end_point}}" readonly="">

                    </div>

                                                    
                    <div class='col-sm-12'>
                      <br><br>
                      <input type='checkbox' name='enable_two_points' readonly {{$preferences->enable_two_points=='on'?'checked':''}} id='enable_two_points'> 
                        <label for='enable_two_points'>Enable Two points clear pattern</label>
                      </div>
                      
                    </div>    
                  </form>
                  </div>            

                    <div class="modal-footer">
                    <button class='button btn btn-primary ' onclick="return updatePreferencesSave(this)" type='submit'> Save</button></center>
                    <button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

@endif
<script>
$(window).load(function(){
var limit=2;
$(".team_a_checkbox").on("ifChecked",function(e){
    var checkboxes = $(".team_a_checkbox:checkbox");
	
    var $this=$(this);
    if (checkboxes.filter(":checked").length > limit) { 
       //alert('Max limit reached');
	   	$.alert({
            title: 'Alert!',
            content: 'Can only select 2 players at once.'
        });
        setTimeout(function(){
            $this.iCheck('uncheck');
        },1);
    }
});
$(".team_b_checkbox").on("ifChecked",function(e){
    var checkboxes = $(".team_b_checkbox:checkbox");
    var $this=$(this);
    if (checkboxes.filter(":checked").length > limit) { 
       //alert('Max limit reached');
	   	$.alert({
            title: 'Alert!',
            content: 'Can only select 2 players at once.'
        });
        setTimeout(function(){
            $this.iCheck('uncheck');
        },1);
    }
});
});
function checkPlayers()
{
	var type = "{{$match_data[0]['schedule_type']}}";
	var match_type = "{{$match_data[0]['match_type']}}";
	if(type=='player' || match_type=='singles')
	{
		$('#badminton').submit();
	}else
	{
		var a_checkboxes = $(".team_a_checkbox:checkbox");
		var a_checked_count = a_checkboxes.filter(":checked").length;
		
		var b_checkboxes = $(".team_b_checkbox:checkbox");
		var b_checked_count = b_checkboxes.filter(":checked").length;
		if(a_checked_count==2 && b_checked_count==2)
		{
			$('#badminton').submit();
		}
		else
		{
			$.alert({
				title: 'Alert!',
				content: 'Select Two Players From Both Teams.'
			});
		}
	}
	
}
$('#winner_team_id').val($('#winner_id').val());
function selectWinner()
{
	$('#winner_team_id').val($('#winner_id').val());
}
/*function createnewset(i)
{

	var i=$('#i').val();
	if(i<=5)
	{

		var thContent =  "<th>SET"+i+"</th>";
						$("#sets").append(thContent);

		var td_a_content = "<td><input type='text' class='gui-input validation allownumericwithdecimal' name='set_"+i+"_a' /></td>";
							$("#set_a").append(td_a_content);

		var td_b_content = "<td><input type='text' class='gui-input validation allownumericwithdecimal' name='set_"+i+"_b' /></td>";
							$("#set_b").append(td_b_content);
			i++;
		  $('#i').val(i);
	} else{
		alert('Maximum Set Size is 5.');
	}
	allownumericwithdecimal();
}*/

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
//Send Approval
function forApproval()
{
	var winner_id = $('#match_result').val();
	var db_winner_id = "{{$match_data[0]['winner_id']}}";
	
  if(winner_id!=''){
        //return true;
      }
      else if((winner_id=='' || db_winner_id=='') )
      {
        $.alert({
          title: 'Alert!',
          content: 'Please Click on End Match Save Match Result then Send.'
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


<script>

$('#saveButton').hide();

var player_a_ids = "{{ $match_data[0]['player_a_ids'] }}";
var player_b_ids = "{{ $match_data[0]['player_b_ids'] }}";

var team_a_id={{$team_a_id}};
var team_b_id={{$team_b_id}};

var team_a_name="{{$team_a_name}}";
var team_b_name="{{$team_b_name}}";
var match_id="{{ $match_data[0]['id'] }}";

var manual=false;
     

function savePreferences(that){
      var data=$('#form_preferences').serialize();

    $.confirm({
        title:"Alert",
        content:"Do you want to save the preferences?",
        confirm:function(){
                        $.ajax({
                        url:base_url+"/match/saveBadmintonPreferences",
                        data:data,
                        type:'post',
                        success : function(){
                            
                            window.location=window.location              
                        },
                        error: function(){
                            return false;
                        }
                    })
              },
        cancel:function(){
          
        }
    })
         return false;
}

function getTeamPlayers(that){
        var selected_team_id= $(that).val();
        var selected_side   = $(that).attr('side');

        if(selected_side=='right') var other_side='left';
        else var other_side='right';

        if(selected_team_id == team_a_id) {
          var p_id=player_a_ids;
          var o_id=player_b_ids;
          var other_side_name=team_b_name;
          var other_side_id=team_b_id;
          }
        else if(selected_team_id == team_b_id){
          var p_id=player_b_ids;
          var o_id=player_a_ids;
          var other_side_name=team_a_name;
          var other_side_id=team_a_id;
        }
        

    $.ajax({
    url: "{{URL('match/getplayers')}}",
    type : 'GET',
    data : {'player_a_ids':p_id},
    dataType: 'json',
    success:function(response){
            
          var options_player_1 = "";
          $.each(response, function(key, value) {
            if(key==0) var selected='selected'; else selected ='';

            options_player_1 += "<option value='" + value['id'] + "' "+ selected+ ">" + value['name'] + "</option>";
          });

          var options_player_2=''
           $.each(response, function(key, value) {
            if(key==1) var selected='selected'; else selected ='';

            options_player_2 += "<option value='" + value['id'] + "' "+ selected+ ">" + value['name'] + "</option>";
          });

          $('#select_player_1_'+selected_side).html(options_player_1);
          $('#select_player_2_'+selected_side).html(options_player_2);

          $('#team_'+other_side).attr('readonly', true).html("<option value='"+other_side_id+"'> "+ other_side_name+ " </option>");                    

    }
  });
      

      //change players on other side

    $.ajax({
    url: "{{URL('match/getplayers')}}",
    type : 'GET',
    data : {'player_a_ids':o_id},
    dataType: 'json',
    success:function(response){           
            var options_player_1 = "";
          $.each(response, function(key, value) {
            if(key==0) var selected='selected'; else selected ='';

            options_player_1 += "<option value='" + value['id'] + "' "+ selected+ ">" + value['name'] + "</option>";
          });

          var options_player_2=''
           $.each(response, function(key, value) {
            if(key==1) var selected='selected'; else selected ='';

            options_player_2 += "<option value='" + value['id'] + "' "+ selected+ ">" + value['name'] + "</option>";
          });

          $('#select_player_1_'+other_side).html(options_player_1);
          $('#select_player_2_'+other_side).html(options_player_2);
                 

        }
    });

  }


// Allow manual scoring
    function enableManualEditing(that){

      if(!manual){
        $.confirm({
            title:"Alert",
            content:"Do you want to enter points manually?",
            confirm:function(){
                $('.tennis_input_new').removeAttr('readonly');
                $('.tennis_input_new').focus();
                $('#real_time_scoring').hide();
                $('#saveButton').show();
                manual=true;
            }, 
            cancel:function(){
               
            }
        })
        
      }
      else{
          $.confirm({
            title:"Alert",
            content:"Do you want to enter points automatically?",
            confirm:function(){    

                 $('.tennis_input_new').attr('readonly', 'readonly');
                 $('#real_time_scoring').show();
                 $('#saveButton').hide();
                 manual=false;
            }, 
            cancel:function(){
                
            }
        })

          
      }
    }


    // Add score automatically
      function addScore(that){
        var team_id=$(that).attr('team_id');
        var table_score_id=$(that).attr('table_score_id');


          data={
              team_id:team_id,
              table_score_id:table_score_id,
              match_id:match_id,
              action:'add',
              }

                    $.ajax({
                        url:'/match/badmintonAddScore',
                        data:data,
                        method:'post',
                        dataType:'json',
                        success:function(response){
                          var left_team_id=response.preferences.left_team_id;
                          var right_team_id=response.preferences.right_team_id;

                            $.each(response.match_details, function(key, value){

                                $('.a_'+key).val(value[left_team_id+'_score']);
                                $('.b_'+key).val(value[right_team_id+'_score']);
                            })

                            addButtonSet(response.current_set)

              $('#team_'+left_team_id+'_score').html(response.scores[left_team_id+"_score"] + ' sets');
              $('#team_'+right_team_id+'_score').html(response.scores[right_team_id+"_score"] + ' sets');


                        }

                       });
                  return false;
              } 

        function removeScore(that){
        var team_id=$(that).attr('team_id');
        var table_score_id=$(that).attr('table_score_id');


          data={
              team_id:team_id,
              table_score_id:table_score_id,
              match_id:match_id,
              action:'remove',
              }

                    $.ajax({
                        url:'/match/badmintonAddScore',
                        data:data,
                        method:'post',
                        dataType:'json',
                        success:function(response){
                          var left_team_id=response.preferences.left_team_id;
                          var right_team_id=response.preferences.right_team_id;

                            $.each(response.match_details, function(key, value){

                                $('.a_'+key).val(value[left_team_id+'_score']);
                                $('.b_'+key).val(value[right_team_id+'_score']);
                            })
                          addButtonSet(response.current_set)

              $('#team_'+left_team_id+'_score').html(response.scores[left_team_id+"_score"] + ' sets');
              $('#team_'+right_team_id+'_score').html(response.scores[right_team_id+"_score"]+ ' sets');

                        }

                       });
                  return false;
              } 


     
            
      


     function updatePreferences(that){
          $('#updatePreferencesModal').modal('show');
     }

     function updatePreferencesSave(){
          var data=$('#updatePreferencesForm').serialize();

          $.confirm({
            title:"Alert",
            content: "Update Preferences",
            confirm: function(){
                                  $.ajax({
                                  url:base_url+"/match/updatePreferencesBadminton",
                                  type:'post', 
                                  data:data,
                                  success:function(){
                                      window.location=window.location
                                  }         

                                 })
                             }
            })
            
          return false;
     }

     function manualScoring(that){
        var data=$('#badminton').serialize();

        $.ajax({
            url:base_url+"/match/manualScoringBadminton",
            type:'post',
            data:data,
            success:function(response){
                window.location=window.location;
            }
        })


        return false;
     }

     function endMatch(that){
        var data=$('#endMatchForm').serialize();

        $.ajax({
            url:base_url+"/match/saveMatchRecordBadminton",
            type:'post', 
            data:data,
            success:function(response){
                window.location=window.location;
            }
        })
        return false;
     }


      
</script>

<script>

//add remove and add button near current active field on current players.

var player_or_team_left_button_add="{!!$player_or_team_left_button_add!!}";

var player_or_team_left_button_remove="{!!$player_or_team_left_button_remove!!}";

var player_or_team_right_button_add="{!!$player_or_team_right_button_add!!}";

var player_or_team_right_button_remove="{!!$player_or_team_right_button_remove!!}";

function addButtonSet(set_index){

    $('.remove_button_right').html('');
    $('.remove_button_left').html('');
    $('.add_button_left').html('');
    $('.add_button_right').html('');

    $('.left_button_remove_set_'+set_index).html(player_or_team_left_button_remove);
    $('.left_button_add_set_'+set_index).html(player_or_team_left_button_add);
    $('.right_button_remove_set_'+set_index).html(player_or_team_right_button_remove);
    $('.right_button_add_set_'+set_index).html(player_or_team_right_button_add);

}

addButtonSet({{$current_set}});

</script>
@endsection



