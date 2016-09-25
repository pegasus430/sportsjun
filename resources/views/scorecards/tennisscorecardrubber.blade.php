 <ul class="nav nav-tabs nav-justified">
  <li class="active">
      <a>
     <span class="pull-left hidden-xs">{{date('jS F , Y',strtotime($rubber['match_start_date'])).' - '.date("g:i a", strtotime($rubber['match_start_time']))}}</span>
        RUBBER {{$rubber->rubber_number}}   &nbsp; &nbsp; <span style='color:white'> [ {{$rubber->match_category}} , {{$rubber->match_type}} ]</span>
        <span class='pull-right'>{{strtoupper($rubber->rubber_number==$active_rubber?'PLAYING':$rubber->match_status)}}
        </span>
        </a>
</ul>

<br>

{!! Form::open(array('url' => 'match/insertTennisScoreCard', 'method' => 'POST','id'=>'tennis')) !!}
  <div class="table-responsive simplebar">
    <table class="table table-striped">
      <thead class="thead">
      <tr id="sets">
  
        <th>{{ trans('message.scorecard.tennis_fields.team') }}</th>
          <th>{{ trans('message.scorecard.tennis_fields.set1') }}</th>
          <th class="append_after">{{ trans('message.scorecard.tennis_fields.set1tie') }}</th>
        @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set2']!='' || $rubber_b_array['set2']!='' || $rubber_a_array['set3']!='' || $rubber_b_array['set3']!='' || $rubber_a_array['set4']!='' || $rubber_b_array['set4']!='' || $rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set2_tie_breaker']!='' || $rubber_b_array['set2_tie_breaker']!='' || $rubber_a_array['set3_tie_breaker']!='' || $rubber_b_array['set3_tie_breaker']!='' || $rubber_a_array['set4_tie_breaker']!='' || $rubber_b_array['set4_tie_breaker']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
          <th >{{ trans('message.scorecard.tennis_fields.set2') }}</th>
          <th class="append_after">{{ trans('message.scorecard.tennis_fields.set2tie') }}</th>
        @endif
        @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set3']!='' || $rubber_b_array['set3']!='' || $rubber_a_array['set4']!='' || $rubber_b_array['set4']!='' || $rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set3_tie_breaker']!='' || $rubber_b_array['set3_tie_breaker']!='' || $rubber_a_array['set4_tie_breaker']!='' || $rubber_b_array['set4_tie_breaker']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
          <th>{{ trans('message.scorecard.tennis_fields.set3') }}</th>
          <th class="append_after">{{ trans('message.scorecard.tennis_fields.set3tie') }}</th>
        @endif
        @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set4']!='' || $rubber_b_array['set4']!='' || $rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set4_tie_breaker']!='' || $rubber_b_array['set4_tie_breaker']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
          <th>{{ trans('message.scorecard.tennis_fields.set4') }}</th>
          <th class="append_after">{{ trans('message.scorecard.tennis_fields.set4tie') }}</th>
        @endif
        @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
          <th>{{ trans('message.scorecard.tennis_fields.set5') }}</th>
          <th class="append_after">{{ trans('message.scorecard.tennis_fields.set5tie') }}</th>
        @endif
      
          <th>{{ trans('message.scorecard.tennis_fields.aces') }}</th>
          <th>{{ trans('message.scorecard.tennis_fields.double_faults') }}</th>
      </tr>
    </thead>
      <tbody>
        <tr id="set_a">
          <td>
            @if($user_a_logo['url']!='')
              <!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/'.$upload_folder.'/'.$user_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
         {!! Helper::Images($user_a_logo['url'],$upload_folder,array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!} 
              @else
             <!-- <img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
         {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!} 
            @endif
              {{ $user_a_name }}
          </td>

          <td>{!! Form::text('set_1_a',(!(empty($rubber_a_array['set1'])))?$rubber_a_array['set1']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
          <td class="append_after_a">{!! Form::text('set_1_tiebreaker_a',(!(empty($rubber_a_array['set1_tie_breaker'])))?$rubber_a_array['set1_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
        
        @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set2']!='' || $rubber_b_array['set2']!='' || $rubber_a_array['set3']!='' || $rubber_b_array['set3']!='' || $rubber_a_array['set4']!='' || $rubber_b_array['set4']!='' || $rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set2_tie_breaker']!='' || $rubber_b_array['set2_tie_breaker']!='' || $rubber_a_array['set3_tie_breaker']!='' || $rubber_b_array['set3_tie_breaker']!='' || $rubber_a_array['set4_tie_breaker']!='' || $rubber_b_array['set4_tie_breaker']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
          <td>{!! Form::text('set_2_a',(!(empty($rubber_a_array['set2'])))?$rubber_a_array['set2']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
          <td class="append_after_a">{!! Form::text('set_2_tiebreaker_a',(!(empty($rubber_a_array['set2_tie_breaker'])))?$rubber_a_array['set2_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
        @endif
        
        @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set3']!='' || $rubber_b_array['set3']!='' || $rubber_a_array['set4']!='' || $rubber_b_array['set4']!='' || $rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set3_tie_breaker']!='' || $rubber_b_array['set3_tie_breaker']!='' || $rubber_a_array['set4_tie_breaker']!='' || $rubber_b_array['set4_tie_breaker']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))

          <td>{!! Form::text('set_3_a',(!(empty($rubber_a_array['set3'])))?$rubber_a_array['set3']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
          <td class="append_after_a">{!! Form::text('set_3_tiebreaker_a',(!(empty($rubber_a_array['set3_tie_breaker'])))?$rubber_a_array['set3_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
        @endif
        
        @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set4']!='' || $rubber_b_array['set4']!='' || $rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set4_tie_breaker']!='' || $rubber_b_array['set4_tie_breaker']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
          <td>{!! Form::text('set_4_a',(!(empty($rubber_a_array['set4'])))?$rubber_a_array['set4']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
          <td class="append_after_a">{!! Form::text('set_4_tiebreaker_a',(!(empty($rubber_a_array['set4_tie_breaker'])))?$rubber_a_array['set4_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
        @endif
        
        @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set5']!='' || $rubber_b_array['set5']!=''  || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
          <td>{!! Form::text('set_5_a',(!(empty($rubber_a_array['set5'])))?$rubber_a_array['set5']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
          <td class="append_after_a">{!! Form::text('set_5_tiebreaker_a',(!(empty($rubber_a_array['set5_tie_breaker'])))?$rubber_a_array['set5_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_a_count tennis_input_new')) !!}</td>
        @endif
        
        <td>{!! Form::text('aces_a',(!(empty($rubber_a_array['aces'])))?$rubber_a_array['aces']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
        
        <td>{!! Form::text('double_faults_a',(!(empty($rubber_a_array['double_faults'])))?$rubber_a_array['double_faults']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>


        </tr>
        <tr id="set_b">
          <td>
              @if($user_b_logo['url']!='')
              <!--  <img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/'.$upload_folder.'/'.$user_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
           {!! Helper::Images($user_b_logo['url'],$upload_folder,array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!} 
                @else
                <!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
             {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!} 
              @endif
              {{ $user_b_name }}
          </td>
            <td>{!! Form::text('set_1_b',(!(empty($rubber_b_array['set1'])))?$rubber_b_array['set1']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
            <td class="append_after_b">{!! Form::text('set_1_tiebreaker_b',(!(empty($rubber_b_array['set1_tie_breaker'])))?$rubber_b_array['set1_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
          
          @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set2']!='' || $rubber_b_array['set2']!='' || $rubber_a_array['set3']!='' || $rubber_b_array['set3']!='' || $rubber_a_array['set4']!='' || $rubber_b_array['set4']!='' || $rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set2_tie_breaker']!='' || $rubber_b_array['set2_tie_breaker']!='' || $rubber_a_array['set3_tie_breaker']!='' || $rubber_b_array['set3_tie_breaker']!='' || $rubber_a_array['set4_tie_breaker']!='' || $rubber_b_array['set4_tie_breaker']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
            <td>{!! Form::text('set_2_b',(!(empty($rubber_b_array['set2'])))?$rubber_b_array['set2']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
            <td class="append_after_b">{!! Form::text('set_2_tiebreaker_b',(!(empty($rubber_b_array['set2_tie_breaker'])))?$rubber_b_array['set2_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
          @endif
          
          
          @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set3']!='' || $rubber_b_array['set3']!='' || $rubber_a_array['set4']!='' || $rubber_b_array['set4']!='' || $rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set3_tie_breaker']!='' || $rubber_b_array['set3_tie_breaker']!='' || $rubber_a_array['set4_tie_breaker']!='' || $rubber_b_array['set4_tie_breaker']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
            <td>{!! Form::text('set_3_b',(!(empty($rubber_b_array['set3'])))?$rubber_b_array['set3']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
            <td class="append_after_b">{!! Form::text('set_3_tiebreaker_b',(!(empty($rubber_b_array['set3_tie_breaker'])))?$rubber_b_array['set3_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
          @endif
          
          @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set4']!='' || $rubber_b_array['set4']!='' || $rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set4_tie_breaker']!='' || $rubber_b_array['set4_tie_breaker']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
            <td>{!! Form::text('set_4_b',(!(empty($rubber_b_array['set4'])))?$rubber_b_array['set4']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
            <td class="append_after_b">{!! Form::text('set_4_tiebreaker_b',(!(empty($rubber_b_array['set4_tie_breaker'])))?$rubber_b_array['set4_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
          @endif
          
          @if((!empty($rubber_a_array) || !empty($rubber_b_array)) && ($rubber_a_array['set5']!='' || $rubber_b_array['set5']!='' || $rubber_a_array['set5_tie_breaker']!='' || $rubber_b_array['set5_tie_breaker']!=''))
            <td>{!! Form::text('set_5_b',(!(empty($rubber_b_array['set5'])))?$rubber_b_array['set5']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
            <td class="append_after_b">{!! Form::text('set_5_tiebreaker_b',(!(empty($rubber_b_array['set5_tie_breaker'])))?$rubber_b_array['set5_tie_breaker']:'',array('class'=>'gui-input validation allownumericwithdecimal set_b_count tennis_input_new')) !!}</td>
          @endif
          
          <td>{!! Form::text('aces_b',(!(empty($rubber_b_array['aces'])))?$rubber_b_array['aces']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
          
          <td>{!! Form::text('double_faults_b',(!(empty($rubber_b_array['double_faults'])))?$rubber_b_array['double_faults']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
        </tr>
      </tbody>
    </table>
  
  
  </div>
  <a onclick="createnewset({{ $i=1 }});" class="addmore"><span class="glyphicon glyphicon-plus-sign"></span> Add More Sets</a>
  
  <!-- if match schedule type is team -->
  @if($rubber['schedule_type']!='player')
  
  <div class="row">
      <div class="col-md-6">      
          <h4 class="team_title_head">{{ $user_a_name }} Players</h4>
      <table class="table table-striped table-bordered team_players_check">
      <tbody>
      <?php $i=1;?>
      @foreach($a_players as $a_player)
        <?php if(!empty($decoded_match_details[$rubber['a_id']])) {
          
          $checed='';
          $radio='';
        
         } else if($i==1 || $i==2){
           $checed="checked='checked'";
         } else {
           $checed='';
         }?>
         <?php if($i==1){$radio="checked='checked'";}else{$radio='';}?>
      
      <tr>
      @if($rubber['match_type']=='singles')
      <td><input type="radio" name="a_player_ids[]" <?php echo $radio;?> <?php if(!empty($decoded_match_details[$rubber['a_id']]) && in_array($a_player['id'],$decoded_match_details[$rubber['a_id']])){echo "checked='checked'";}?> value="{{$a_player['id']}}"/></td>
      @else
      @if($rubber['match_type']=='doubles' || $rubber['match_type']=='mixed')
      <td><input type="checkbox" name="a_player_ids[]" <?php echo $checed;?> <?php if(!empty($decoded_match_details[$rubber['a_id']]) && in_array($a_player['id'],$decoded_match_details[$rubber['a_id']])){echo "checked='checked'";}?> class="team_a_checkbox" onclick="test();" value="{{$a_player['id']}}"/></td>
      @endif
      @endif
      <td>
      @if($team_a_player_images[$a_player['id']]['url']!='')
              
      {!! Helper::Images($team_a_player_images[$a_player['id']]['url'],'user_profile',array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!}  
        @else
                
      {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!}  
        @endif
      {{$a_player['name']}}
      </td>
      </tr>
      <?php $i++;?>
      @endforeach
        
      </tbody>
    </table>
        </div>
        <div class="col-md-6">
            <h4 class="team_title_head">{{ $user_b_name }} Players</h4>
      <table class="table table-striped table-bordered team_players_check">
      <tbody>
      <?php $j=1;?>
      @foreach($b_players as $b_player)
        <?php if(!empty($decoded_match_details[$rubber['b_id']])) {
          
          $checed='';
          $radio='';
         } else if($j==1 || $j==2){
           $checed="checked='checked'";
         } else {
           $checed='';
         }?>
          <?php if($j==1){$radio="checked='checked'";}else{$radio='';}?>
        <tr>
          @if($rubber['match_type']=='singles')
          <td><input type="radio" name="b_player_ids[]" <?php echo $radio;?> <?php if(!empty($decoded_match_details[$rubber['b_id']]) && in_array($b_player['id'],$decoded_match_details[$rubber['b_id']])){echo "checked='checked'";}?> value="{{$b_player['id']}}"/></td>
          @else
          @if($rubber['match_type']=='doubles' || $rubber['match_type']=='mixed')
          <td><input type="checkbox" name="b_player_ids[]" <?php echo $checed;?> <?php if(!empty($decoded_match_details[$rubber['b_id']]) && in_array($b_player['id'],$decoded_match_details[$rubber['b_id']])){echo "checked='checked'";}?>  id="b_player_{{$b_player['id']}}" class="team_b_checkbox" value="{{$b_player['id']}}"/></td>
          @endif
          @endif
        <td>
        @if($team_b_player_images[$b_player['id']]['url']!='')
              
        {!! Helper::Images($team_b_player_images[$b_player['id']]['url'],'user_profile',array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!}  
        @else
                
        {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!}  
        @endif
        {{$b_player['name']}}
        </td>
        </tr>
        <?php $j++;?>
      @endforeach
      </tbody>
    </table>
        </div>
  </div>
  


  @endif

   <input type="hidden" id="tennis_form_data" value="">
  {!! Form::hidden('user_id_a',$rubber['a_id'],array('class'=>'gui-input validation')) !!}
  {!! Form::hidden('player_ids_a',$rubber['player_a_ids'],array('class'=>'gui-input validation')) !!}
  {!! Form::hidden('player_ids_b',$rubber['player_b_ids'],array('class'=>'gui-input validation')) !!}
  {!! Form::hidden('user_id_b',$rubber['b_id'],array('class'=>'gui-input validation')) !!}
  {!! Form::hidden('match_type',$rubber['match_type'],array('class'=>'gui-input validation', 'id'=>'match_type_test')) !!}
  {!! Form::hidden('tournament_id',$rubber['tournament_id'],array('class'=>'gui-input validation')) !!}
  {!! Form::hidden('match_id',$rubber['match_id'],array('class'=>'gui-input validation','id'=>'match_id')) !!}
  {!! Form::hidden('player_name_b', $user_b_name,array('class'=>'gui-input validation')) !!}
  {!! Form::hidden('player_name_a',$user_a_name,array('class'=>'gui-input validation')) !!}
  {!! Form::hidden('schedule_type',$rubber['schedule_type'],array('class'=>'gui-input', 'id'=>'player_type_test')) !!}
  {!! Form::hidden('is_singles',$is_singles,array('class'=>'gui-input validation')) !!}
    
  <input type="hidden" id="winner_team_id" name="winner_team_id" value="">
  <input type="hidden" id="is_winner_inserted" name="is_winner_inserted" value="{{$rubber['winner_id']}}">
    
  <div class="sportsjun-forms text-center scorecards-buttons">
  <center>
  <ul class="list-inline">
  <li>
  @if($isValidUser)
    <li>
      <button style="text-align:center;" type="button" onclick="checkPlayers();" class="button btn-primary">Save</button>
    </li>
  @endif  
        @if($isValidUser && $isForApprovalExist)    
      <button style="text-align:center;" type="button" onclick="forApproval();" class="button green">Send Score for Approval</button>    
        @endif
  
   

{!!Form::close()!!}
<br>
