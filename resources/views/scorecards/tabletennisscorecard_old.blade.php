@extends('layouts.app')
@section('content')
<?php $team_a_name = $user_a_name;
		$team_b_name = $user_b_name;
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
                           <h4>   {{$tournamentDetails['name']}} Tournament </h4>
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
	@if($match_data[0]['match_status']=='completed')
	<div class="form-group">
    	<label class="win_head">Winner</label> 
        <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$user_a_name:$user_b_name }}</h3>
    </div>
	@else

      <div class="form-group form-select">
        <label>Select Winner:</label>
        <select name="winner_id" id="winner_id" class="form-control selectpicker" onchange="selectWinner();">
			<option value="">Select</option>
          <option value="{{ $match_data[0]['a_id'] }}" <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['a_id']) echo ' selected';?>>{{ $user_a_name }}</option>
          <option value="{{ $match_data[0]['b_id'] }}" <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id']==$match_data[0]['b_id']) echo ' selected';?>>{{ $user_b_name }}</option>
        </select>
      </div>

	@endif
    <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src=" {{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
        @include('scorecards.share')
        <p class="match-status">@include('scorecards.scorecardstatus')</p>
    </div>

@if($match_data[0]['game_type']=='normal')
    <!--<a onclick="createnewset({{ $i=1 }});" style="float:right;">(Add More Sets)</a>-->
    {!! Form::open(array('url' => 'match/insertTableTennisScoreCard', 'method' => 'POST','id'=>'tabletennis')) !!}
    <div class="table-responsive">
    	<table class="table table-striped">
        <thead class="thead">
    		<tr id="sets">
    			  <th>{{ trans('message.scorecard.tennis_fields.team') }}</th>
    				<th>{{ trans('message.scorecard.tennis_fields.set1') }}</th>
    				<th>{{ trans('message.scorecard.tennis_fields.set2') }}</th>
    				<th>{{ trans('message.scorecard.tennis_fields.set3') }}</th>
    				<th>{{ trans('message.scorecard.tennis_fields.set4') }}</th>
    				<th>{{ trans('message.scorecard.tennis_fields.set5') }}</th>
    		</tr>
      </thead>
    		<tbody>
    			<tr id="set_a">
    				<td>
              @if($user_a_logo['url']!='')
              <!--  <img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/'.$upload_folder.'/'.$user_a_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
    {!! Helper::Images($user_a_logo['url'],$upload_folder,array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!}	
                @else
               <!-- <img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
		    {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!}	
              @endif
              {{ $user_a_name }}
    				</td>
    				<td>{!! Form::text('set_1_a',(!(empty($score_a_array[0]['set1'])))?$score_a_array[0]['set1']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
    				<td>{!! Form::text('set_2_a',(!(empty($score_a_array[0]['set2'])))?$score_a_array[0]['set2']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
    				<td>{!! Form::text('set_3_a',(!(empty($score_a_array[0]['set3'])))?$score_a_array[0]['set3']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
    				<td>{!! Form::text('set_4_a',(!(empty($score_a_array[0]['set4'])))?$score_a_array[0]['set4']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
    				<td>{!! Form::text('set_5_a',(!(empty($score_a_array[0]['set5'])))?$score_a_array[0]['set5']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
					</tr>
    			<tr id="set_b">
    				<td>
              @if($user_b_logo['url']!='')
               <!-- <img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/'.$upload_folder.'/'.$user_b_logo['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
		     {!! Helper::Images($user_b_logo['url'],$upload_folder,array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!}	
                @else
               <!-- <img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
		    {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'fa fa-user fa-fw fa-2x','height'=>42,'width'=>42) )!!}	
              @endif
              {{ $user_b_name }}
    				</td>
    					<td>{!! Form::text('set_1_b',(!(empty($score_b_array[0]['set1'])))?$score_b_array[0]['set1']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
    					<td>{!! Form::text('set_2_b',(!(empty($score_b_array[0]['set2'])))?$score_b_array[0]['set2']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
    					<td>{!! Form::text('set_3_b',(!(empty($score_b_array[0]['set3'])))?$score_b_array[0]['set3']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
    					<td>{!! Form::text('set_4_b',(!(empty($score_b_array[0]['set4'])))?$score_b_array[0]['set4']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
    					<td>{!! Form::text('set_5_b',(!(empty($score_b_array[0]['set5'])))?$score_b_array[0]['set5']:'',array('class'=>'gui-input validation allownumericwithdecimal tennis_input_new')) !!}</td>
            </tr>
    		</tbody>
    	</table>
    </div>
	
	
	<!-- if match schedule type is team -->
	@if($match_data[0]['schedule_type']!='player')
	
	<div class="row">
    	<div class="col-md-6">			
        	<h4 class="team_title_head">{{ $user_a_name }} Players</h4>
			<table class="table table-striped table-bordered team_players_check">
			<tbody>
				<?php $i=1;?>
			@foreach($a_players as $a_player)
				<?php if(!empty($decoded_match_details[$match_data[0]['a_id']])) {
					
					$checed='';
					$radio='';
				
				 } else if($i==1 || $i==2){
					 $checed="checked='checked'";
				 } else {
					 $checed='';
				 }?>
				 <?php if($i==1){$radio="checked='checked'";}else{$radio='';}?>
			
			<tr>
			@if($match_data[0]['match_type']=='singles')
			<td><input type="radio" name="a_player_ids[]" <?php echo $radio;?> <?php if(!empty($decoded_match_details[$match_data[0]['a_id']]) && in_array($a_player['id'],$decoded_match_details[$match_data[0]['a_id']])){echo "checked='checked'";}?> value="{{$a_player['id']}}"/></td>
			@else
			@if($match_data[0]['match_type']=='doubles' || $match_data[0]['match_type']=='mixed')
			<td><input type="checkbox" name="a_player_ids[]" <?php echo $checed;?> <?php if(!empty($decoded_match_details[$match_data[0]['a_id']]) && in_array($a_player['id'],$decoded_match_details[$match_data[0]['a_id']])){echo "checked='checked'";}?> class="team_a_checkbox" onclick="test();" value="{{$a_player['id']}}"/></td>
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
				<?php if(!empty($decoded_match_details[$match_data[0]['b_id']])) {
					
					$checed='';
					$radio='';
				 } else if($j==1 || $j==2){
					 $checed="checked='checked'";
				 } else {
					 $checed='';
				 }?>
				  <?php if($j==1){$radio="checked='checked'";}else{$radio='';}?>
				<tr>
					@if($match_data[0]['match_type']=='singles')
					<td><input type="radio" name="b_player_ids[]"  <?php echo $radio;?> <?php if(!empty($decoded_match_details[$match_data[0]['b_id']]) && in_array($b_player['id'],$decoded_match_details[$match_data[0]['b_id']])){echo "checked='checked'";}?> value="{{$b_player['id']}}"/></td>
					@else
					@if($match_data[0]['match_type']=='doubles' || $match_data[0]['match_type']=='mixed')
					<td><input type="checkbox" name="b_player_ids[]" <?php echo $checed;?> <?php if(!empty($decoded_match_details[$match_data[0]['b_id']]) && in_array($b_player['id'],$decoded_match_details[$match_data[0]['b_id']])){echo "checked='checked'";}?> id="b_player_{{$b_player['id']}}" class="team_b_checkbox" value="{{$b_player['id']}}"/></td>
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
	<!-- end -->
		<input type="hidden" id="tabletennis_form_data" value="">
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
          	<button style="text-align:center;" type="button" onclick="checkPlayers();" class="button btn-primary">Save</button>
				
		@endif
            @if($isValidUser && $isForApprovalExist)		
			<button style="text-align:center;" type="button" onclick="forApproval();" class="button green">Send Score for Approval</button>
            @endif
	    
		  
    {!!Form::close()!!}


  @else

     @foreach($rubbers as $rubber)
    <?php
         $rubber_players = ScoreCard::getRubberPlayers($rubber->id,3);
         $rubber_a_array = $rubber_players['a'];
         $rubber_b_array = $rubber_players['b'];
    ?>

    <?php 
    if($rubber->rubber_number==$active_rubber){
        $score_a_array=$rubber_a_array;
        $score_b_array=$rubber_b_array;
      }

  ?>

      @if($rubber->rubber_number==$active_rubber)
         @include('scorecards.tabletennisscorecardrubber')
      @else
         @include('scorecards.tabletennisscorecardrubberview')
      @endif
    
 @endforeach


  @endif

  <!-- End Rubber -->

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
		$('#tabletennis').submit();
	}else
	{
		var a_checkboxes = $(".team_a_checkbox:checkbox");
		var a_checked_count = a_checkboxes.filter(":checked").length;
		
		var b_checkboxes = $(".team_b_checkbox:checkbox");
		var b_checked_count = b_checkboxes.filter(":checked").length;
		if(a_checked_count==2 && b_checked_count==2)
		{
			$('#tabletennis').submit();
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
	var winner_id = $('#winner_id').val();
	var db_winner_id = "{{$match_data[0]['winner_id']}}";
	if(winner_id=='' || db_winner_id=='')
	{
		$.alert({
            title: 'Alert!',
            content: 'Select Winner & Save.'
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
