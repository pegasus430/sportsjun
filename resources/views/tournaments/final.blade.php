<style>

@media (max-width: 750px){
  .jsplumb-connector{
      color: red;
      display: none;
  }
  .jsplumb-connector-outline{
     color: blue;
     display: none;
  }
}

.match_winner{
    background: #dcdccc;
}

.tour_score{
  
}

<?php $empty_rounds=[];?>

</style>

<!--  <button class="btn btn-danger" onclick="printToPdf('canvas')"> Download</button> -->

@if(count($tournamentDetails[0]['final_stage_teams']))
<!--<div>
    Add Round
</div>


<div>
    <div>Round 1</div>
    <span class="button btn-primary" onclick='addRoundMatchesSchedule({{$tournament_id}},1)'>Add Schedule</span>
</div>

<div id="round_div_1">
</div>    -->
<div class="col-sm-12">
<div class="row group-flex-content flowchart-object flowchart-action " id='canvas'>
   
@if(count($roundArray))
        @foreach($roundArray as $round)
        @if($round==1)
        <div class="col-sm-2">
            <div class="round-{{Helper::convert_number_to_words($round)}}">
                <div class="round"><p>{{Helper::getRoundStage($tournament_id, $round)}}</p></div>
                 @if(count($firstRoundBracketArray))
                 @foreach($firstRoundBracketArray as $key => $schedule)
                  <div class="match_set" style="height: 150px;">
                    @if(isset($schedule['tournament_round_number']) && $schedule['tournament_round_number']==$round)
                           <ul class="window jtk-node">
                               <div class="clearfix">
                                  <span class="tour_match_date fa fa-info" data-toggle="tooltip" data-placement="left" title="{{$schedule['match_start_date'].$sport_name.' '.$schedule['match_type']}}"></span>
                                  <span class="tour_score">

                                  @if(isset($schedule['winner_text']))
                                  <a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}">{{$schedule['winner_text']}}</a>
                                  @else
                                      @if($isOwner)
                                          <a href="javascript:void(0)" id="scheduleEdit_{{$schedule['id']}}"  onclick="editMatchSchedule({{$schedule['id']}},1,'','myModal')">Edit</a>
                                      @endif
                                  @endif
                                  </span>
                               </div>
                          <div  id="tour_{{$round}}_match_{{$schedule['tournament_match_number']}}">
                              <li title="{{isset($schedule[$scheduleTypeOne]['name'])?$schedule[$scheduleTypeOne]['name']:'Bye'}}"  data-toggle="tooltip" data-placement="top">
                                {!! Helper::Images($schedule[$scheduleTypeOne]['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>30,'width'=>30) )!!}
                                @if(isset($schedule[$scheduleTypeOne]['name']))
                                    <span>
                                        <a href="{{ url($linkUrl,[$schedule[$scheduleTypeOne]['id']]) }}">
                                            {{Helper::get_first_20_letters($schedule[$scheduleTypeOne]['name'])}}

                                        </a>
                                    </span>
                                @else
                                   <span>{{trans('message.bye')}}</span>
                                @endif
                              </li>
                              <li title="{{isset($schedule[$scheduleTypeTwo]['name'])?$schedule[$scheduleTypeTwo]['name']:'Bye'}}"  data-toggle="tooltip" data-placement="top">
                                {!! Helper::Images($schedule[$scheduleTypeTwo]['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>30,'width'=>30) )!!}
                                @if(isset($schedule[$scheduleTypeTwo]['name']))
                                   <span>
                                        <a href="{{ url($linkUrl,[$schedule[$scheduleTypeTwo]['id']]) }}">
                                            {{Helper::get_first_20_letters($schedule[$scheduleTypeTwo]['name'])}}
                                          
                                        </a>
                                   </span>
                                @else
                                   <span>{{trans('message.bye')}}</span>
                                @endif
                              </li>
                            </div>
                          </ul>
                    @else
                            <ul>
                                @if($isOwner)
                                    <div class="clearfix">
                                      <span class="tour_score">
                                        <a href="javascript:void(0)" id="scheduleEdit_{{$key}}"  onclick="addRoundMatchesSchedule({{$tournament_id}},{{$round}},{{$key}})">Schedule Match</a>
                                      </span>
                                    </div>
                                @endif
                                   <li>
                                       <span>Match {{$key}}</span>
                                   </li>
                                   <li>
                                       <span></span>
                                   </li>
                            </ul>
                    @endif
                 </div>
                 @endforeach

                 @endif
            </div>
        </div>
        @else
        <div class="col-sm-2">
            <div class="round-{{Helper::convert_number_to_words($round)}}">
                <div class="round"><p>{{$bracket_name=Helper::getRoundStage($tournament_id, $round)}}</p></div>
                <?php 
                      $new_index=0;
                      $max_matches = floor($tournamentDetails[0]['final_stage_teams']/(2 *$round)); 
                      ?>
                 @if(count($bracketTeamArray))
                    <?php
                        if(empty($minHeight)) {
                            $minHeight = 150;
                            $height=$minHeight*2;
                        }else{
                            //$minHeight = $height;
                            if($round<4) $minHeight=150 * ($round-1) * 2;
                            else {
                              if($round % 4==0)
                                $minHeight=( 150* ($round)) + 75 ;
                              else $minHeight = 150 * (($round * 2)-1);
                            }

                              
                            $height = $minHeight;
                        }
                        
                        $actualHeigh = $height.'px';
                        $has_removed=0;
                        $w=0;
                    ?>
                    @foreach($bracketTeamArray as $brk => $bracketTeam)
                      
                    <div class="match_set tourn_{{$round}}_remove_{{$brk+1}} " style="height: <?php echo $height.'px';?>">
                        <ul  id="tour_{{$round}}_match_{{($brk+1)}}">
                            @foreach($bracketTeam as $bt => $bracket)
                                @if(isset($bracket['tournament_round_number']) && $bracket['tournament_round_number']==$round)
                   <?php 
                        $new_index++;
                    ?>

                                    @if($round==($lastRoundWinner+1))
                                      <?php $w++;?>

                                        @if($w==2)
                                        <div class="clearfix">
                                          <span class="fa fa-star" style="color:#f27676;"></span>&nbsp;&nbsp;Third Position&nbsp;&nbsp;<span class="fa fa-star" style="color:#f27676;"></span>
                                        </div>
                                        @else

                                           <div class="clearfix">
                                            <span class="winner_text"><span class="fa fa-star" style="color:#f27676;"></span>&nbsp;&nbsp;Winner&nbsp;&nbsp;<span class="fa fa-star" style="color:#f27676;"></span></span>
                                        </div>


                                        @endif


                                    @else

                                        @if(isset($bracket['match_start_date']))
                                            <div class="clearfix">
                                               <span class="tour_match_date fa fa-info"  data-toggle="tooltip" data-placement="left" title="{{(isset($bracket['winner_text'])&&$bracket['winner_text']!='edit')?$bracket['match_start_date'].$sport_name.' '.$bracket['match_type']:trans('message.tournament.final.editscheduletoaddscore')}}"></span>
                                               <span class="tour_score">
                                               @if(isset($bracket['winner_text']))
                                                    @if($bracket['winner_text']=='edit')
                                                        @if(isset($bracket['id']))
                                                            <a href="javascript:void(0)" id="scheduleEdit_{{$bracket['id']}}" onclick="editMatchSchedule({{$bracket['schdule_id']}},1,{{$round}},'myModal')">Edit Schedule</a>
                                                        @endif    
                                                    @else
                                                        @if(isset($bracket['id']))
                                                            <a href="{{ url('match/scorecard/edit/'.$bracket['id']) }}">{{$bracket['winner_text']}}</a>
                                                        @endif    
                                                    @endif
                                               @else
                                                    @if($isOwner)
                                                        @if(isset($bracket['id']))
                                                            <a href="javascript:void(0)" id="scheduleEdit_{{$bracket['id']}}" onclick="editMatchSchedule({{$bracket['schdule_id']}},1,{{$round}},'myModal')">Edit Schedule</a>
                                                        @endif    
                                                    @endif
                                               @endif
                                               </span>
                                            </div>
                                        @endif
                                    @endif


                                   <li title="{{isset($bracket['name'])?$bracket['name']:''}}"  data-toggle="tooltip" data-placement="top">
                                       {!! Helper::Images($bracket['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>30,'width'=>30) )!!}
          @if(isset($bracket['name']))
                                                <span>
                                                   <a href="{{ url($linkUrl,[$bracket['team_or_player_id']]) }}">
                                                    {{Helper::get_first_20_letters($bracket['name'])}}
                                                   </a>
                                                </span>
                                        @else
                                                <span></span>
                                        @endif
                                    </li>
                                     @if($round==($lastRoundWinner+1) && $w==1)
                                        <div style="height:200px">
                                        </div>
                                 @endif

                                @else
                                 
                                  <?php 
                                    if(!in_array($round, $empty_rounds) ) array_push($empty_rounds, $round);?>
                                    @if(($new_index*2)>=$max_matches && $has_removed==0)
                                      <?php $has_removed=1;?>
                                         <div class="remove_if_empty_{{$round}}"></div>
                                    @endif
                                  @endif


                            @endforeach
                        </ul>
                        </div>
                    @endforeach
                 @endif

                 <!-- Third Place -->

              @if($bracket_name=='FINAL')

              <div class="round"><p> THIRD POSITION    </p></div>

                <?php $schedule  = Helper::getThirdPosition($tournament_id, $round);        

                                                   
                          ?>                    
                                
 <div class="match_set" style="height: 10px;">
                    @if(isset($schedule['tournament_round_number']) && $schedule['tournament_round_number']==$round)
                           <ul class="window jtk-node">
                               <div class="clearfix">
                                  <span class="tour_match_date fa fa-info" data-toggle="tooltip" data-placement="left" title="{{$schedule['match_start_date'].$sport_name.' '.$schedule['match_type']}}"></span>
                                  <span class="tour_score">

                                  @if(isset($schedule['winner_text']))
                                  <a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}">{{$schedule['winner_text']}}</a>
                                  @else
                                      @if($isOwner)
                                          <a href="javascript:void(0)" id="scheduleEdit_{{$schedule['id']}}"  onclick="editMatchSchedule({{$schedule['id']}},1,{{$round}},'myModal')">Edit Schedule</a>
                                      @endif
                                  @endif
                                  </span>
                               </div>
                          <div  id="tour_{{$round}}_match_{{$schedule['tournament_match_number']}}">
                              <li title="{{isset($schedule[$scheduleTypeOne]['name'])?$schedule[$scheduleTypeOne]['name']:'Bye'}}"  data-toggle="tooltip" data-placement="top">
                                {!! Helper::Images($schedule[$scheduleTypeOne]['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>30,'width'=>30) )!!}
                                @if(isset($schedule[$scheduleTypeOne]['name']))
                                    <span>
                                        <a href="{{ url($linkUrl,[$schedule[$scheduleTypeOne]['id']]) }}">
                                            {{Helper::get_first_20_letters($schedule[$scheduleTypeOne]['name'])}}

                                        </a>
                                    </span>
                                @else
                                   <span>{{trans('message.bye')}}</span>
                                @endif
                              </li>
                              <li title="{{isset($schedule[$scheduleTypeTwo]['name'])?$schedule[$scheduleTypeTwo]['name']:'Bye'}}"  data-toggle="tooltip" data-placement="top">
                                {!! Helper::Images($schedule[$scheduleTypeTwo]['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>30,'width'=>30) )!!}
                                @if(isset($schedule[$scheduleTypeTwo]['name']))
                                   <span>
                                        <a href="{{ url($linkUrl,[$schedule[$scheduleTypeTwo]['id']]) }}">
                                            {{Helper::get_first_20_letters($schedule[$scheduleTypeTwo]['name'])}}                                          
                                        </a>
                                   </span>
                                @else
                                   <span>{{trans('message.bye')}}</span>
                                @endif
                              </li>
                            </div>
                          </ul>
                    @else
                          
                    @endif
                 </div>
                @endif

          <!-- End of third Position -->

          <!-- Third Position Winner -->
          

          <!-- End of third Position Winner-->



            </div>
       </div>
        @endif
        @endforeach
@else
        <div class="col-sm-2">
            <div class="round-one">
                <div class="round"><p>{{Helper::getRoundStage($tournament_id, 1)}}</p></div>

                 @if(count($firstRoundBracketArray))
                 @foreach($firstRoundBracketArray as $key => $schedule)
                  <div class="match_set" style="height: 150px">
                            <ul>
                                @if($isOwner)
                                    <div class="clearfix">
                                      <span class="tour_score">
                                        <a href="javascript:void(0)" id="scheduleEdit_{{$key}}"  onclick="addRoundMatchesSchedule({{$tournament_id}},1,{{$key}})">Schedule Match</a>
                                      </span>
                                    </div>
                                @endif
                                   <li>
                                       <span>Match {{$key}}</span>
                                   </li>
                                   <li>
                                       <span></span>
                                   </li>
                            </ul>
          </div>
                 @endforeach

                 @endif
            </div>
        </div>
@endif

</div>
</div>
@else
<div class="sj-alert sj-alert-info">
@if($isOwner)
                    {{ trans('message.tournament.final.addfinalteams') }}
@else
       {{ trans('message.tournament.final.nofinalstageteams') }}
@endif     
</div>
@endif

</div>
<div class='clearfix'>

<script type="text/javascript">
  window.matches={{$tournamentDetails[0]['final_stage_teams']}};
</script>
<script type="text/javascript" src="/js/jsplumb/jsPlumb-2.1.5-min.js"></script>
<script type="text/javascript" src="/js/jsplumb/drawlines.js">  </script>
<script type="text/javascript" src="/js/jsplumb/jsPlumb-2.1.5-min.js"></script>
<script type="text/javascript" src="/js/jspdf.js">  </script>



<script type="text/javascript">


function finalStageTeams(flag) {
//    var finalStageTeams = $("#final_stage_teams").val();
    var finalStageTeams = $('select#final_stage_teams').val();
    var tournamentId = {{$tournamentDetails[0]['id']}}
    if(!tournamentId) {
        return false;
    }
    if(flag=='group' && !finalStageTeams){
        $.alert({
                title: 'Alert!',
                content: 'Select final stage teams.'
        });
        return false;
    }
    if(flag=='ko' && $(".selected-teams").length<1){
        $.alert({
                title: 'Alert!',
                content: 'Select final stage teams.'
        });
        return false;
    }
    var html='';
//    if(Math.floor(finalStageTeams) == finalStageTeams && $.isNumeric(finalStageTeams)) {
        $.ajax({
            type: 'POST',
            url: base_url + '/tournament/updatefinalstageteams',
            data: {tournamentId:tournamentId, finalStageTeams:finalStageTeams, flag:flag},
            dataType: 'json',
            beforeSend: function() {
                $.blockUI({width: '50px', message: $("#spinner").html()});
            },
            success: function(response) {
                $.unblockUI();
                window.location.reload();
//                html ='<div class="col-sm-10">';
//                html+='<div class="col-sm-8">';
//                html+='<div class="row group-flex-content">';
//                html+='<div class="col-sm-3">';
//                html+='<div class="row round-one">';
//                html+='<div class="round"><p>ROUND ONE</p></div>';
//                html+='<span class="button btn-primary" onclick="addRoundMatchesSchedule('+tournamentId+',1)">Add Schedule</span>';
//                html+='</div>';
//                html+='</div>';
//                html+='</div>';
//                html+='</div>';
//                html+='</div>';
//                $("#final_stage_div").html(html);

            }
        });
//    }
}

function addRoundMatches(tournamentId,roundNumber) {
    var finalStageTeamsCount = $("#final_stage_teams_count").val();
    var teamIds = $("#team_ids").val();
    $.ajax({
            type: 'GET',
            url: base_url + '/tournament/addRoundMatches',
            data: {tournamentId:tournamentId, finalStageTeamsCount:finalStageTeamsCount, roundNumber:roundNumber, teamIds:teamIds},
            dataType: 'html',
            beforeSend: function() {
                $.blockUI({width: '50px', message: $("#spinner").html()});
            },
            success: function(response) {
                $.unblockUI();
            }
    });
} 

function addRoundMatchesSchedule(tournamentId,roundNumber, matchNumber) {
    $.ajax({
            type: 'GET',
            url: base_url + '/tournament/getroundteams',
            data: {tournamentId:tournamentId, roundNumber:roundNumber, matchNumber:matchNumber},
            dataType: 'json',
            beforeSend: function() {
                $.blockUI({width: '50px', message: $("#spinner").html()});
            },
            success: function(response) {
                $.unblockUI();
                $('#bye').prop("selectedIndex","0");
                if(response['result'] == 'success') {
                    $("#myModal").modal();
                    $("#byeDiv").show();
                    $("#byeTextDiv").hide();
                    clearModal();
                    
                    var tournamentDetails = response['tournamentDetails'];
                    $(".modal-body #search_team_ids").val(response['searchTeamIds']);
                    $(".modal-body #tournament_round_number").val(roundNumber);
                    $(".modal-body #tournament_id").val(tournamentId);
//                    $(".modal-body #tournament_match_number").val(response['matchNumber']);
                    $(".modal-body #tournament_match_number").val(matchNumber);
                    $(".modal-body #scheduletype").val(response['scheduleType']);

                    $(".modal-body #myteam").removeAttr("readonly"); 
                    $(".modal-body #oppteam").removeAttr("readonly");
                    
                    
                   autofillsubtournamentdetails(tournamentDetails);
                }
            }
    });
}

</script>

<script type="text/javascript">
    function printToPdf(id){
        var doc= new jsPDF();

        doc.addHTML($('#'+id).get(0), 15, 15, {
          'width': 170         
        });

        doc.save('Test.pdf');
    }
</script>


<script type="text/javascript">
    $(document).ready(function(){
        var m=window.matches;
        var l={{$lastRoundWinner}}
            

    })

</script>
