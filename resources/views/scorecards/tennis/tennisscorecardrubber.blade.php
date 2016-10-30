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
 <ul class="nav nav-tabs nav-justified">
  <li class="active">
       <a > 
        <span class="pull-left hidden-xs">{{date('jS F , Y',strtotime($rubber['match_start_date'])).' - '.date("g:i a", strtotime($rubber['match_start_time']))}}</span>
         RUBBER {{$rubber->rubber_number}}  &nbsp; &nbsp; [ {{$rubber->match_category}} , {{$rubber->match_type}} ]
        <span class='pull-right'>{{strtoupper($rubber->rubber_number==$active_rubber?'PLAYING':$rubber->match_status)}}
        </span>
        </a>
  </li>
</ul>

<br>


   @if(!$rubber['hasSetupSquad'])
    <div class='row'>
      <div class='col-sm-12'>
          <div class=''>
          <form id='form_preferences' >
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

                        @if($rubber['match_type']=='doubles')
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
                   
                        @if($rubber['match_type']=='doubles')
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
              <div class='row' style="display:none">
                <div class="col-sm-12">
                  <h3><center>Game Preferences</center></h3>
                </div>
              </div>

              <div class='row' style="display:none">
                   <div class='col-sm-6'>
                        <label>Number of Sets</label>
                        <select class=' form-control select-picker field select' name='number_of_sets' {{$disabled}}>
                          <option value='1'>1</option>
                          <option value='2'>2</option>
                          <option value='3' selected="">3</option>
                          <option value='4'>4</option>
                          <option value='5'>5</option>
                        </select>

                        <br>
                        <input type='checkbox' name='enable_two_points' checked="" id='enable_two_points' {{$disabled}} > <label for='enable_two_points'>Enable Two points clear pattern</label>
                    </div>

                    <div class='col-sm-6'>
                      <div class="section">
                        <label class="form_label">Score to Win <span  class='required'>*</span> </label>
                        <input placeholder="eg. 21" type='number' name='score_to_win' min="0" class="form-control" required="" {{$disabled}} value="{{$match_settings->score_to_win}}">

                        <br>
                        <label class="form_label">Set End Point <span  class='required'>*</span></label>
                        <input placeholder="eg. 29" type='number' name='set_end_point' min='0' class="form-control gui-input" required="" {{$disabled}} value="{{$match_settings->end_point}}">


                    </div>
                </div>
              </div>
<!-- End of Game Preference -->

              <!-- Save -->

                <div class="row">
                <div class='col-sm-12'><br>
                <input type='hidden' value="{{$match_data[0]['id']}}" name='match_id'>
                 <input type='hidden' value="{{$match_data[0]['tournament_id']}}" name='tournament_id'>
                <input type='hidden' value="{{$team_a_name}}" name='team_a_name' id='team_a_name'>
                <input type='hidden' value="{{$team_b_name}}" name='team_b_name' id='team_b_name'>
                <center><input type='button' name='submit_preferences' value='SAVE' class="btn btn-primary" onclick="return savePreferences(this)"></center><br>
                </div>    
                </div>        
              </form>


          </div>
      </div>
    </div>


@else

{!! Form::open(array('url' => '', 'method' => 'POST','id'=>'tennis', 'onsubmit'=>'return manualScoring(this)')) !!}

<?php 
    $match_details = json_decode($rubber->match_details);
    $current_set   = $match_details->current_set;

    $set_score     = ScoreCard::tennis_active_set($rubber->match_id, $rubber->id, $current_set);
    $game          = $set_score->active_game;


?>

  <div class="row ">
    <div class="col-sm-12">
   <div class='table-responsive'>
      <table class='table table-striped table-bordered'>
        <thead>
          <tr class='team_bat team_title_head'>
     {{--   @if(!is_null($rubber_a_array['team_id']))  <th><b>TEAMS</b></th> @endif --}}
             <th>PLAYERS</th>
             
            @for($set_index=1; $set_index<=$set; $set_index++)
              <th>SET {{$set_index}}</th>
              <th class="tb-hide tb tb-{{$set_index}}">TB {{$set_index}}</th>
            @endfor
              <th>ACES </th>
              <th>D FAULTS </th>
             
          </tr>
        </thead>
        <tbody>
          <tr>
           
            <td>
            @if(!is_null($rubber_a_array['team_id']))<b>{{$rubber_a_array['team_name']}}</b><br>@endif {{$rubber_a_array['player_name_a']}} / {{$rubber_a_array['player_name_b']}}</td>
            
          @for($set_index=1; $set_index<=$set; $set_index++)
            <td>
                <span class='hidden-xs pull-left remove_button_left left_button_remove_set_{{$set_index}}'></span>
                 <input  readonly class="gui-input validation allownumericwithdecimal runs_new a_set{{$set_index}}" value="{{$rubber_a_array['set'.$set_index]}}" name='a_set{{$set_index}}'>
                <span class='hidden-xs pull-right add_button_left left_button_add_set_{{$set_index}}'></span>
            </td>
               <td class="tb-hide tb tb-{{$set_index}}">
                   <span class='hidden-xs pull-left remove_button_left left_button_remove_set_tb_{{$set_index}}'></span>
                 <input  readonly class="tgui-input validation allownumericwithdecimal runs_new a_set{{$set_index}}_tie_breaker" value="{{$rubber_a_array['set'.$set_index.'_tie_breaker']}}" name='a_set_tie{{$set_index}}'>
                  <span class='hidden-xs pull-right add_button_left left_button_add_set_tb_{{$set_index}}'></span>

            </td>
          @endfor
            <td><input class="gui-input validation allownumericwithdecimal runs_new a_aces" type="" name="a_aces" value="{{$rubber_a_array['aces']}}" readonly=""> </td>
            <td><input class="gui-input validation allownumericwithdecimal runs_new a_double_faults" type="" name="a_double_faults" value="{{$rubber_a_array['double_faults']}}" readonly="">  </td>
        </tr>

          <tr>
    
            <td>
             @if(!is_null($rubber_b_array['team_id']))<b>{{$rubber_b_array['team_name']}}</b><br>@endif 
             {{$rubber_b_array['player_name_a']}} / {{$rubber_b_array['player_name_b']}}</td>

            @for($set_index=1; $set_index<=$set; $set_index++)
              <td>
                <span class='hidden-xs pull-left remove_button_right right_button_remove_set_{{$set_index}}'></span>
                  <input  readonly class="gui-input validation allownumericwithdecimal runs_new b_set{{$set_index}}" value="{{$rubber_b_array['set'.$set_index]}}" name='b_set{{$set_index}}'>
                <span class='hidden-xs pull-right add_button_right right_button_add_set_{{$set_index}}'></span>
              </td>
                 <td class="tb-hide tb tb-{{$set_index}}">

                 <input  readonly class="  gui-input validation allownumericwithdecimal runs_new b_set{{$set_index}}_tie_breaker" value="{{$rubber_b_array['set'.$set_index.'_tie_breaker']}}" name='b_set_tie{{$set_index}}'>
            </td>
            @endfor
             <td><input type="" class="gui-input validation allownumericwithdecimal runs_new b_aces" name="b_aces" value="{{$rubber_b_array['aces']}}" readonly=""> </td>
            <td><input class="gui-input validation allownumericwithdecimal runs_new b_double_faults" type="" name="b_double_faults" value="{{$rubber_b_array['double_faults']}}" readonly="">  </td>
        </tr>

        </tbody>



      </table>
    </div>
  </div>

  @if($game)
      <div class="col-sm-12" id='real_time_scoring_a'>
          <div class="row">
            <div class="col-xs-4">
             <center> <h3 id='team_a_set_score'>  {{$rubber_a_array['set'.$current_set]}} </h3>   </center>
            </div>
            <div class="col-xs-4">
               <center>   <h3> SET <span id='set_number_display'> {{$current_set}}</span> </h3> </center>
            </div>
            <div class="col-xs-4">
              <center><h3 id='team_b_set_score'>  {{$rubber_b_array['set'.$current_set]}} </h3>   </center>
            </div>
      </div>
      <div class="col-sm-12" style="border:inset 1px green;">

           <div class="col-sm-8" style="background:#fdfffd">
            <div class="row">
              <center>  Game <span id='game_number_display'>{{$game->game_number}} </span> </center>

              </div>
                <div class="row">
                <div class="col-sm-6 pull-left" >
                    <center>
                      @if(!is_null($rubber_a_array['team_id']))<b>{{$rubber_a_array['team_name']}}</b><br>@endif 
                       <a style="font-size:30px;text-align:center;" id='team_a_game_score'> {{$game->team_a_score}} </a> 

                       <button style="position:absolute; margin-left:25%;" class="btn" id='end_set'> End Set </button>
                       </center>
                    <center>
                    <button class='btn btn-danger btn-circle' type='team_a' value="{{$rubber_a_array['team_id']}}" score_id="{{$rubber_a_array['id']}}">15</button>
                    <button class='btn btn-danger btn-circle' type='team_a' value="{{$rubber_a_array['team_id']}}" score_id="{{$rubber_a_array['id']}}">30</button>
                    <button class='btn btn-danger btn-circle' type='team_a' value="{{$rubber_a_array['team_id']}}" score_id="{{$rubber_a_array['id']}}">40</button>
                    <button class='btn btn-danger btn-circle' type='team_a' value="{{$rubber_a_array['team_id']}}" score_id="{{$rubber_a_array['id']}}">Ace</button>
                    <button class='btn btn-danger btn-circle' type='team_a' value="{{$rubber_a_array['team_id']}}" score_id="{{$rubber_a_array['id']}}">DB</button>
                    <button class='btn btn-danger btn-circle' type='team_a' value="{{$rubber_a_array['team_id']}}" score_id="{{$rubber_a_array['id']}}">Win</button>
                    </center>
                </div>
                <div class="col-sm-6 pull-left">
                    <center>
                          @if(!is_null($rubber_b_array['team_id']))<b>{{$rubber_b_array['team_name']}}</b><br>@endif  <a style="font-size:30px; text-align:center" id='team_b_game_score'> {{$game->team_b_score}} </a> 
                    </center>
                    <center>
                    <button class='btn btn-danger btn-circle' type='team_b' value="{{$rubber_b_array['team_id']}}" score_id="{{$rubber_b_array['id']}}">15</button>
                    <button class='btn btn-danger btn-circle' type='team_b'  value="{{$rubber_b_array['team_id']}}" score_id="{{$rubber_b_array['id']}}">30</button>
                    <button class='btn btn-danger btn-circle' type='team_b' value="{{$rubber_b_array['team_id']}}" score_id="{{$rubber_b_array['id']}}">40</button>
                    <button class='btn btn-danger btn-circle' type='team_b' value="{{$rubber_b_array['team_id']}}" score_id="{{$rubber_b_array['id']}}">Ace</button>
                    <button class='btn btn-danger btn-circle' type='team_b' value="{{$rubber_b_array['team_id']}}" score_id="{{$rubber_b_array['id']}}">DB</button>
                    <button class='btn btn-danger btn-circle' type='team_b' value="{{$rubber_b_array['team_id']}}" score_id="{{$rubber_b_array['id']}}">Win</button>
                    </center>
                </div>

                 <a title="Start Tie Breaker" href="javascript:void(0)" style="color:green; position:absolute; margin-left:-50px; margin-top:-20px; font-size:30px" class="show-tie-breaker show_tb" onclick='show_tb()' >
                           <i class="fa fa-caret-right"></i>
                  </a>
              </div>
          </div>

          <div class="col-sm-4 hide-tie-breaker hide_tb" style="" id='show_tie_breaker'>

            <div class="col-sm-12">
              <div class=''> <center>Tie Breaker  </center></div>     
              <br>
              <br>
              </div>
            <div class="row">
                <div  class="col-sm-6 col-xs-6" style="">
                    @if(!is_null($rubber_a_array['team_id']))<b>{{$rubber_a_array['team_name']}}</b><br>@endif 
                      <div class="col-xs-4">
                          <a href="javascript:void(0)" class="tb_scoring"  style="color:red; font-size:30px" type="team_a" action='remove' value="{{$rubber_a_array['team_id']}}">
                              <i class="fa fa-caret-left"> </i>
                          </a>
                      </div>
                      <div class="col-xs-4" style="font-size:30px" id='team_a_tb'>
                            {{$rubber_a_array['set'.$current_set.'_tie_breaker']}}
                      </div>
                      <div class="col-xs-4">
                           <a href="javascript:void(0)" class="tb_scoring"  style="color:green; font-size:30px" type="team_a" action='add' value="{{$rubber_a_array['team_id']}}">
                              <i class="fa fa-caret-right"> </i>
                          </a>
                      </div>

                </div>
                <div class="col-sm-6 col-xs-6">
                    @if(!is_null($rubber_b_array['team_id']))<b>{{$rubber_b_array['team_name']}}</b><br>@endif 

                    <div class="col-sm-4 col-xs-4">
                          <a href="javascript:void(0)" class="tb_scoring"  style="color:red; font-size:30px" type="team_b" action='remove' value="{{$rubber_b_array['team_id']}}">
                              <i class="fa fa-caret-left"> </i>
                          </a>
                      </div>
                      <div class="col-sm-4 col-xs-4" style="font-size:30px" id='team_b_tb'>
                         {{$rubber_b_array['set'.$current_set.'_tie_breaker']}}
                      </div>
                      <div class="col-sm-4 col-xs-4">
                           <a href="javascript:void(0)" class="tb_scoring" style="color:green; font-size:30px" type="team_b" action='add' value="{{$rubber_b_array['team_id']}}">
                              <i class="fa fa-caret-right"> </i>
                          </a>
                      </div>
                </div>

             
                      <a title="End Tie Breaker" href="javascript:void(0)" style="color:red; font-size:30px" style="position:absolute; margin-left:-50px; margin-top:20px;" class="show-tie-breaker" >
                              <i class="fa fa-caret-left" onclick='hide_tb()'> </i>
                      </a>
        
              </div>


          </div>
      </div>
      <div class="col-sm-12"> &nbsp; 
          <input type='hidden' id='set_number' value="{{$current_set}}">
          <input type='hidden' id='game_number' value='{{$game->game_number}}'>
          <input type='hidden' id='game_id'>
      </div>
</div>

@endif


<input type='hidden' value='{{$set}}' name="number_of_sets">
<input type='hidden' value="{{$match_data[0]['id']}}" name='match_id'>
<input type='hidden' value="{{$rubber->id}}" name='rubber_id' id='rubber_id'>
<input type='hidden' value="{{$rubber_a_array['id']}}" name='score_a_id' class='arm_a_val' id=''>
<input type='hidden' value="{{$rubber_b_array['id']}}" name='score_b_id' class='arm_b_val'>
                <input type='hidden' value="{{$team_a_name}}" name='team_a_name' id='team_a_name'>
                <input type='hidden' value="{{$team_b_name}}" name='team_b_name' id='team_b_name'>

<div class="row" id='saveButton'>
    <div class='col-sm-12'>
       <center> <input type='submit' class="btn btn-primary" value="Save"></center>
    </div>
</div>

</form>


@endif


 <!-- End of Normal Match -->

