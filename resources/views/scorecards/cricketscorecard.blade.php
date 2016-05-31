@extends('layouts.app')
@section('content')
<style type="text/css">
        .alert{display: none;}
</style>
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
                                                                <!--<img  class="img-responsive img-circle"width="110" height="110" src="{{ asset('/images/no_logo.png') }}">	-->
                                                                {!! Helper::Images('no_logo.png','images',array('class'=>'img-responsive img-circle','height'=>110,'width'=>110) )!!}	
                                                                @endif
                                                        </div>
                                                </div>
                                                <div class="col-md-8 col-sm-12">
                                                        <div class="team_detail">

                                                                <div class="team_name"><a href="{{ url('/team/members').'/'.$match_data[0]['a_id'] }}">{{ $team_a_name }}</a></div>
                                                                <div class="team_city">{{$team_a_city}}</div>
                                                                <div class="team_score">
                                                                        <span> @if($match_data[0]['match_type']=='test') {{'I st'}} @endif
                                                                                <div>
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="first_inning[{{ $match_data[0]['a_id'] }}][score]" placeholder="Score" value="{{ ${'team_'.$first_innings_team_variable['first'].'_fst_ing_score'} }}"/>
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="first_inning[{{ $match_data[0]['a_id'] }}][wickets]" placeholder="Wickets" value="{{ ${'team_'.$first_innings_team_variable['first'].'_fst_ing_wkt'} }}" />
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="first_inning[{{ $match_data[0]['a_id'] }}][overs]" placeholder="Overs"  value="{{ ${'team_'.$first_innings_team_variable['first'].'_fst_ing_overs'} }}"/>
                                                                                </div>
                                                                        </span>
                                                                        @if($match_data[0]['match_type']=='test')
                                                                        <span>II nd
                                                                                <div>
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="second_inning[{{ $match_data[0]['a_id'] }}][score]" placeholder="Score" value="{{ ${'team_'.$second_innings_team_variable['first'].'_scnd_ing_score'} }}" />
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="second_inning[{{ $match_data[0]['a_id'] }}][wickets]" placeholder="Wickets" value="{{ ${'team_'.$second_innings_team_variable['first'].'_scnd_ing_wkt'} }}" />
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="second_inning[{{ $match_data[0]['a_id'] }}][overs]" placeholder="Overs" value="{{ ${'team_'.$second_innings_team_variable['first'].'_scnd_ing_overs'} }}" />
                                                                                </div>
                                                                        </span>
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
                                                                <div class="team_score">
                                                                        <span>@if($match_data[0]['match_type']=='test') {{'I st'}} @endif
                                                                                <div>
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="first_inning[{{ $match_data[0]['b_id'] }}][score]" placeholder="Score" value="{{ ${'team_'.$first_innings_team_variable['second'].'_fst_ing_score'} }}"/>
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="first_inning[{{ $match_data[0]['b_id'] }}][wickets]" placeholder="Wickets" value="{{ ${'team_'.$first_innings_team_variable['second'].'_fst_ing_wkt'} }}" />
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="first_inning[{{ $match_data[0]['b_id'] }}][overs]" placeholder="Overs"  value="{{ ${'team_'.$first_innings_team_variable['second'].'_fst_ing_overs'} }}"/>
                                                                                </div>
                                                                        </span>
                                                                        @if($match_data[0]['match_type']=='test')

                                                                        <span>II nd
                                                                                <div>
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="second_inning[{{ $match_data[0]['b_id'] }}][score]" placeholder="Score" value="{{ ${'team_'.$second_innings_team_variable['second'].'_scnd_ing_score'} }}" />
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="second_inning[{{ $match_data[0]['b_id'] }}][wickets]" placeholder="Wickets" value="{{ ${'team_'.$second_innings_team_variable['second'].'_scnd_ing_wkt'} }}" />
                                                                                        <input readonly class="team_stat_readonly" type="text" data-id="second_inning[{{ $match_data[0]['b_id'] }}][overs]" placeholder="Overs" value="{{ ${'team_'.$second_innings_team_variable['second'].'_scnd_ing_overs'} }}" />
                                                                                </div>
                                                                        </span>
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
                        <div class="panel-body row">
                                <h5 class="scoreboard_title">Cricket Scorecard 
                                        @if($match_data[0]['match_type']!='other')
                                        <span class='match_type_text'>({{ $match_data[0]['match_type']=='odi'?strtoupper($match_data[0]['match_type']):ucfirst($match_data[0]['match_type']) }})</span>
                                        @endif
                                </h5>

                                <div class="form-inline">
                                    <?php if (!empty($score_status_array['toss_won_by']))
                                    { ?>
                                                <div id="matchTossNote">
                                                        <?php if ($match_data[0]['a_id'] == $score_status_array['toss_won_by'])
                                                        { ?>
                                                                {{ ucwords($team_a_name) }} 
                                                        <?php }
                                                        else
                                                        { ?>
                                                                {{ ucwords($team_b_name) }} 
                                                        <?php } ?>
                                                        won the toss 
                                                        <?php if (!empty($score_status_array['fst_ing_batting']))
                                                        { ?>
                                                                and chose to 
                                                                <?php if ($score_status_array['toss_won_by'] == $score_status_array['fst_ing_batting'])
                                                                { ?>
                                                                        Bat.
                <?php }
                else
                { ?>
                                                                        Bowl.
                <?php } ?>
        <?php } ?>
                                                </div>
<?php } ?>

                                        @if($match_data[0]['match_status']=='completed' && $match_data[0]['winner_id']>0)
                                        <div class="form-group">
                                                <label class="win_head">Winner</label> 
                                                <h3 class="win_team">{{ ($match_data[0]['a_id']==$match_data[0]['winner_id'])?$team_a_name:$team_b_name }}</h3>
                                        </div>
                                        @else
                                        @if($match_data[0]['match_status']=='completed' && $match_data[0]['is_tied']>0)
                                        <div class="form-group">
                                                <label>Match Result : {{ 'Tie' }}</label>
                                        </div>   
                                        @else
                                        <div class="form-group" style="display:none;">
                                                <label for="match_result">Match Result:</label>
                                                <select class="form-control selectpicker selectpicker_new_span" name="match_result" id="match_result" autocomplete="off">
                                                        <option value="" >Select</option>
<?php if (empty($match_data[0]['tournament_round_number']))
{ ?>
                                                                <option <?php if ($match_data[0]['is_tied'] > 0) echo " selected"; ?> value="tie" >Tie</option>
<?php } ?>    
                                                        <option <?php if ($match_data[0]['is_tied'] == 0 && $match_data[0]['winner_id'] > 0) echo " selected"; ?> value="win">Win</option>
                                                </select>
                                        </div>
                                        <div class="form-group" style="margin-top:15px;display:none;">
                                                <label class="show_teams">Select Winner:</label>
                                                <select name="winner_id" id="winner_id" class="show_teams form-control selectpicker selectpicker_new_span" autocomplete="off">
                                                        <option <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id'] == $match_data[0]['a_id']) echo ' selected'; ?> value="{{ $match_data[0]['a_id'] }}" >{{ $team_a_name }}</option>
                                                        <option <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id'] == $match_data[0]['b_id']) echo ' selected'; ?> value="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</option>
                                                </select>
                                        </div>
                                        @endif	
                                        @endif	  
                                        <div class="form-group" id="tossWonBy" style="display:none;">
                                                <!--<label for="team">Ist Ing Batting:</label>-->
                                                <label for="team">Toss Won By</label>
                                                <select class="form-control selectpicker selectpicker_new_span" name="toss_won" id="toss_won" onchange="tosswonby();">
                                                        <option value="{{ $match_data[0]['a_id'] }}" <?php if (!empty($score_status_array['toss_won_by']) && $match_data[0]['a_id'] == $score_status_array['toss_won_by']) echo 'selected'; ?>>{{ $team_a_name }}</option>
                                                        <option value="{{ $match_data[0]['b_id'] }}" <?php if (!empty($score_status_array['toss_won_by']) && $match_data[0]['b_id'] == $score_status_array['toss_won_by']) echo 'selected'; ?>>{{ $team_b_name }}</option>
                                                </select>
                                        </div>

<?php
$first_inning_editable  = !(!empty($team_a_fst_ing_array) || !empty($team_b_fst_ing_array) || !empty($team_a_fst_ing_bowling_array) || !empty($team_b_fst_ing_bowling_array)) ? 1 : 0;
$second_inning_editable = 0;
if ($match_data[0]['match_type'] == 'test')
{
        $second_inning_editable = !(!empty($team_a_secnd_ing_array) || !empty($team_b_secnd_ing_array) || !empty($team_a_scnd_ing_bowling_array) || !empty($team_b_scnd_ing_bowling_array)) ? 1 : 0;
}
?>
                                        @if($first_inning_editable!=0)
                                        <!-- Toss won by modal start -->
                                        <div class="modal fade in tossDetail" tabindex="-1" role="modal" aria-labelledby="tossDetail" id="tossModal" style="display: block;">
                                                <div class="vertical-alignment-helper">
                                                        <div class="modal-dialog modal-lg vertical-align-center">
                                                                <div class="modal-content create-team-model create-album-popup model-align">
                                                                        <div class="modal-header text-center">
                                                                                <button type="button" class="close" data-dismiss="modal">×</button>
                                                                                <h4>TOSS DETAILS</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                                <form class="content">
                                                                                        <div id="tossWonByRadio" class="form-group">
                                                                                                <div class="toss-detail">
                                                                                                        <span class="head">TOSS WON BY</span>
                                                                                                        <div class="radio-box">
                                                                                                                <div class="radio">
                                                                                                                        <input name="team" type="radio" value="toss" id="{{ $match_data[0]['a_id'] }}" checked="">
                                                                                                                        <label for="{{ $match_data[0]['a_id'] }}">{{ $team_a_name }}</label>
                                                                                                                </div>
                                                                                                                <div class="radio">
                                                                                                                        <input name="team" type="radio" value="toss" id="{{ $match_data[0]['b_id'] }}">
                                                                                                                        <label for="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</label>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <div id="batRadio" class="form-group">
                                                                                                <div class="toss-detail">
                                                                                                        <span class="head">ELECTED</span>
                                                                                                        <div class="radio-box">
                                                                                                                <div class="radio">
                                                                                                                        <input name="elected" type="radio" value="bat" id="bat" checked="">
                                                                                                                        <label for="bat">BAT</label>
                                                                                                                </div>
                                                                                                                <div class="radio">
                                                                                                                        <input name="elected" type="radio" value="bowl" id="bowl">
                                                                                                                        <label for="bowl">BOWL</label>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                                <button type="button" class="button btn-primary" onclick="SJ.SCORECARD.doneTossModal();">Done</button>
                                                                                <button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                        <!-- Toss won by modal end -->
                                        @endif
                                        @if($match_data[0]['match_type']=='test' && $second_inning_editable != 0)
                                        <div class="modal fade in tossDetail" tabindex="-1" id="secondInningsBatModal" role="modal" aria-labelledby="secondInningsBatModal">
                                                <div class="vertical-alignment-helper">
                                                        <div class="modal-dialog modal-lg vertical-align-center">
                                                                <div class="modal-content create-team-model create-album-popup model-align">
                                                                        <div class="modal-header text-center">
                                                                                <button type="button" class="close" data-dismiss="modal">×</button>
                                                                                <h4>SECOND INNING BATTING</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                                <form class="content">
                                                                                        <div id="bat2ndInningBatting" class="form-group">
                                                                                                <div class="toss-detail">
                                                                                                        <span class="head">Select Team to Start Second Innings Batting</span>
                                                                                                        <div class="radio-box">
                                                                                                                <div class="radio">
                                                                                                                        <input name="team" type="radio" value="toss" id="{{ $match_data[0]['a_id'] }}" checked="">
                                                                                                                        <label for="{{ $match_data[0]['a_id'] }}">{{ $team_a_name }}</label>
                                                                                                                </div>
                                                                                                                <div class="radio">
                                                                                                                        <input name="team" type="radio" value="toss" id="{{ $match_data[0]['b_id'] }}">
                                                                                                                        <label for="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</label>
                                                                                                                </div>
                                                                                                        </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                                <button type="button" class="button btn-primary" onclick="SJ.SCORECARD.done2ndInningModal();">Done</button>
                                                                                <button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                        @endif


                                        <div class="form-group" id="bat1stInning" style="display:none;">
                                                <label for="team">Ist Ing Batting:</label>
                                                <select class="form-control selectpicker selectpicker_new_span" name="team" id="team" onchange="getTeamName();">
                                                        <option value="{{ $match_data[0]['player_a_ids'] }}" <?php if (!empty($score_status_array['fst_ing_batting']) && $match_data[0]['a_id'] == $score_status_array['fst_ing_batting']) echo 'selected'; ?> data-status="{{ $match_data[0]['a_id'] }}" >{{ $team_a_name }}</option>
                                                        <option value="{{ $match_data[0]['player_b_ids'] }}" <?php if (!empty($score_status_array['fst_ing_batting']) && $match_data[0]['b_id'] == $score_status_array['fst_ing_batting']) echo 'selected'; ?> data-status="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</option>
                                                </select>
                                        </div>
                                        @if($match_data[0]['match_type']=='test')
                                        <div class="form-group" id="bat2ndInning" style="display:none;">
                                                <label for="team">II Ing Batting:</label>
                                                <select class="form-control selectpicker selectpicker_new_span" name="team" id="teams" onchange="getTeamNames();">
                                                        <option value="{{ $match_data[0]['player_a_ids'] }}" <?php if (!empty($score_status_array['scnd_ing_batting']) && $match_data[0]['a_id'] == $score_status_array['scnd_ing_batting']) echo 'selected'; ?> data-status="{{ $match_data[0]['a_id'] }}" >{{ $team_a_name }}</option>
                                                        <option value="{{ $match_data[0]['player_b_ids'] }}" <?php if (!empty($score_status_array['scnd_ing_batting']) && $match_data[0]['b_id'] == $score_status_array['scnd_ing_batting']) echo 'selected'; ?> data-status="{{ $match_data[0]['b_id'] }}" >{{ $team_b_name }}</option>
                                                </select>
                                        </div>
                                        @endif
                                        <p class="match-status mg"><a href="{{ url('user/album/show').'/match'.'/0'.'/'.$action_id }}"><span class="fa" style="float: left; margin-left: 8px;"><img src="{{ asset('/images/sc-gallery.png') }}" height="18" width="22"></span> <b>Media Gallery</b></a></p>
                                        @include('scorecards.share')
                                        <p class="match-status">@include('scorecards.scorecardstatus')</p>
                                </div>



                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-justified">
                                        <li class="active">
                                                @if($first_inning_editable!=0)
                                                <span id="edit_first_innings" onclick="SJ.SCORECARD.firstInningsModal();">Change</span>
                                                @endif
                                                <a href="#first_innings" data-toggle="tab" aria-expanded="true">Ist Innings</a>
                                        </li>
                                        @if($match_data[0]['match_type']=='test')
                                        <li class="">
                                                @if($second_inning_editable != 0)
                                                <span id="edit_second_innings" onclick="SJ.SCORECARD.secondInningBattingOrderModal();">Change</span>
                                                @endif
                                                <a href="#second_innings" data-toggle="tab" aria-expanded="false" onclick="SJ.SCORECARD.secondInningBattingOrderModal();">2nd Innings</a>
                                        </li>
                                        @endif
                                </ul>
                                <div  class="tab-content">
                                        <div id="first_innings" class="tab-pane fade active in">

                                                <!-- /.panel-heading -->
                                                @include('scorecards.cricketinnings')
                                                @if($isValidUser && $isForApprovalExist)

                                                <li><button style="text-align:center;" type="button" onclick="forApproval();" class="btn btn-green">Send Score for Approval</button></li>

                                                @endif

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
                                        @if($match_data[0]['match_type']=='test')
                                        <div id="second_innings" class="tab-pane fade" >

                                                @include('scorecards.cricketsecondinnings')

                                                @if($isValidUser && $isForApprovalExist)

                                                <li><button style="text-align:center;" type="button" onclick="forApproval();" class="btn btn-green">For Approval</button></li>

                                                @endif

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

                                        @endif
                                </div>	
                                <!-- /.panel-body -->
                        </div>
                </div>        
        </div>
</div>

</div>
<input type="hidden" id="match_id" value="{{$match_data[0]['id']}}">
<script>
        $('#hid_match_result').val($('#match_result').val());
        $(document).ready(function () {
                SJ.SCORECARD.initTeamStats();
                $(".team_stat_readonly").keyup(function () {
                        SJ.SCORECARD.initTeamStats();
                });
        })
        
//Send Approval
        function forApproval()
        {
                var db_winner_id = "{{$match_data[0]['winner_id']}}";
                var is_tied = "{{$match_data[0]['is_tied']}}";
                var winner_id = $('#match_result').val();
                if (winner_id == '' || (db_winner_id == '' && is_tied == 0))
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
                        confirm: function () {
                                match_id = $('#match_id').val();
                                $.ajax({
                                        url: base_url + '/match/scoreCardStatus',
                                        type: "post",
                                        data: {'scorecard_status': 'approval_pending', 'match_id': match_id},
                                        success: function (data) {
                                                if (data.status == 'success') {
                                                        // $.alert({
                                                        // title: 'Alert!',
                                                        // content: data.msg
                                                        // });
                                                        window.location.href = base_url + '/match/scorecard/edit/' + match_id;
                                                }
                                        }
                                });
                        },
                        cancel: function () {
                                // nothing to do
                        }
                });
        }
</script>
@endsection