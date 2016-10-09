<input type="hidden" name="limit" id="limit" value="{{$limit}}"/>
<input type="hidden" name="offset" id="offset" value="{{$offset}}"/>


<div class="col-sm-12 viewmoreclass">
    <div class="row">
        @if(count($matchScheduleData))
        @foreach($matchScheduleData as $schedule)
        <div id="schedule_{{$schedule['id']}}" class="schedule_list clearfix">
        	<div class="deskview hidden-xs">
                <div class="col-md-2 col-sm-12">

                    <?php
                    $schedule['match_start_date'] = trim($schedule['match_start_date']);
                    if (strpos($schedule['match_start_date'], ':') == false)
                    {
                        $schedule['match_start_date'] = DateTime::createFromFormat('d/m/Y', $schedule['match_start_date'])->format('jS F, Y');
                    }
                    else
                    {
                        $schedule['match_start_date'] = DateTime::createFromFormat('d/m/Y g:i A', $schedule['match_start_date'])->format('jS F, Y g:i A');
                    }
                    ?>

                    <p class="vs_date">
                        <span><b>{{ $schedule['match_start_date'] }}</b></span>
                    </p>
                </div>
                <div id="teamone" class="col-sm-3 col-md-2 score_view_img">
                    <p>
                        @if($schedule['schedule_type']=='team')
                        {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}
                        @else    
                        {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}
                        @endif    
                    </p>
                </div>
                <div id="center_div" class="col-sm-6 col-md-3">
<!--                    <p class="vs_text"><span>{{$schedule['a_name']}}</span> 
                       vs 
                       <span>{{isset($schedule['b_name'])?$schedule['b_name']:trans('message.bye')}}</span>
                    </p> -->
                    <p class="vs_text">
                            <span>
                                @if($schedule['schedule_type']=='team')
                                    <a href="{{ url('/team/members',[$schedule['a_id']]) }}">{{$schedule['a_name']}}</a>
                                @else
                                    <a href="{{ url('/showsportprofile',[$schedule['a_id']]) }}">{{$schedule['a_name']}}</a>
                                @endif
                            </span>  
                            vs 
                            <span>
                                @if(isset($schedule['b_name']))
                                    @if($schedule['schedule_type']=='team')
                                        <a href="{{ url('/team/members',[$schedule['b_id']]) }}">{{$schedule['b_name']}}</a>
                                    @else
                                        <a href="{{ url('/showsportprofile',[$schedule['b_id']]) }}">{{$schedule['b_name']}}</a>
                                    @endif
                                @else  
                                    {{trans('message.bye')}}
                                @endif  
                            </span>
                     </p>
                     <p class="vs_date">
                         <span class='sports_text'>{{ isset($schedule['sport']['sports_name'])?$schedule['sport']['sports_name']:'' }}</span>
                         @if($schedule['match_type']!='other')
                             <span class='match_type_text'>({{ $schedule['match_type']=='odi'?strtoupper($schedule['match_type']):ucfirst($schedule['match_type']) }})</span>
                         @endif
                     </p>

                            <span class=''>{{Helper::getMatchDetails($schedule['id'])->address}}</span><br>
                    <?php
                    if (strpos($schedule['match_start_date'], ':') == false) {
                        $schedule['match_start_date'] .= ' 00:00 AM';
                    }
                    ?>
              
                    

                    
                    @if(isset($schedule['winner_text']))
                        @if($schedule['winner_text']=='Edit') 
                            <p><span><a href="#" id="scheduleEdit_{{$schedule['id']}}" onclick="editMatchSchedule({{$schedule['id']}}, 1, '', 'mainmatchschedule')" class="add_score_but">Edit</a></span></p>
                        @elseif($schedule['winner_text']==trans('message.schedule.pending'))
                            <p><span style="color:rgba(0,0,0,0.5)">{{ trans('message.schedule.invitationpending') }}</span></p>
                        @elseif($schedule['winner_text']==trans('message.schedule.rejected'))
                            <p><span style="color:rgba(0,0,0,0.5)">{{ trans('message.schedule.invitationrejected') }}</span></p>
                        @else    
                            <p><span><a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}" class="add_score_but">{{$schedule['winner_text']}}</a></span></p>
                        @endif
                    @elseif(strtotime($schedule['match_start_date']) < time())
                        <p><span><a href="{{ url('match/scorecard/view/'.$schedule['id']) }}" class="add_score_but">{{trans('message.schedule.viewscore')}}</a></span></p>
                    @endif
                    
                    
                </div>
    
                <div id="teamtwo" class="col-sm-3 col-md-2 score_view_img">
                    <p>
                        @if($schedule['schedule_type']=='team')
                        {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}
                        @else
                        {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}
                        @endif
                    </p>
                </div>
                <div class="col-md-2 col-sm-12">
                    Status: <span class='sports_text'>{{ ucfirst($schedule['match_status']) }}</span> <br>
                    Scores: <span class='blue'>{{Helper::getMatchDetails($schedule['id'])->scores}} </span> <br>
                    @if(!is_null(Helper::getMatchDetails($schedule['id'])->winner_id))
                        <span class='red'>Winner: {{Helper::getMatchDetails($schedule['id'])->winner}} </span>

                    @endif
                </div>
			</div>			
            <div class="mobview hidden-sm hidden-lg">
                <div class="row">
                <div id="teamone" class="col-xs-5 score_view_img">
                    <p>
                        @if($schedule['schedule_type']=='team')
                        {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}
                        @else    
                        {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border','height'=>90,'width'=>90) )!!}
                        @endif    
                    </p>
                    <!--<p class="vs_text"><span>{{$schedule['a_name']}}</span></p>-->
                    <p class="vs_text">
                        <span>
                            @if($schedule['schedule_type']=='team')
                                <a href="{{ url('/team/members',[$schedule['a_id']]) }}">{{$schedule['a_name']}}</a>
                            @else
                                <a href="{{ url('/showsportprofile',[$schedule['a_id']]) }}">{{$schedule['a_name']}}</a>
                            @endif
                        </span> 
                    </p>
                </div>    
        
                <div id="center_div" class="col-xs-2">
                     <span style="position: absolute; margin-top: 25px;">vs</span>
                </div>
        
                <div id="teamtwo" class="col-xs-5 score_view_img">
                    <p>
                        @if($schedule['schedule_type']=='team')
                        {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>'60','width'=>60) )!!}
                        @else    
                        {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border','height'=>'60','width'=>60) )!!}
                        @endif    
                    </p>
                    <!--<p class="vs_text"><span>{{isset($schedule['b_name'])?$schedule['b_name']:trans('message.bye')}}</span></p>--> 
                    <p class="vs_text">
                            <span>
                                @if(isset($schedule['b_name']))
                                    @if($schedule['schedule_type']=='team')
                                        <a href="{{ url('/team/members',[$schedule['b_id']]) }}">{{$schedule['b_name']}}</a>
                                    @else
                                        <a href="{{ url('/showsportprofile',[$schedule['b_id']]) }}">{{$schedule['b_name']}}</a>
                                    @endif
                                @else  
                                    {{trans('message.bye')}}
                                @endif  
                            </span>
                    </p>
                </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <p>
                        <span>{{ $schedule['match_start_date'] }}</span>
                        <span class='sports_text'>{{ isset($schedule['sport']['sports_name'])?$schedule['sport']['sports_name']:'' }}</span>
                        @if($schedule['match_type']!='other')
                            <span class='match_type_text'>({{ $schedule['match_type']=='odi'?strtoupper($schedule['match_type']):ucfirst($schedule['match_type']) }})</span>
                        @endif
                    </p>  

                           <span class=''>{{Helper::getMatchDetails($schedule['id'])->address}}</span><br>
                     Status: <span class='sports_text'>{{ ucfirst($schedule['match_status']) }}</span> <br>
                    Scores: <span class='blue'>{{Helper::getMatchDetails($schedule['id'])->scores}} </span> <br>
                    @if(!is_null(Helper::getMatchDetails($schedule['id'])->winner_id))
                            <span class='red'>Winner: {{Helper::getMatchDetails($schedule['id'])->winner}} </span>
                                
                    @endif
                    
                    @if(isset($schedule['winner_text']))
                        @if($schedule['winner_text']=='Edit') 
                            <p><span><a href="#" id="scheduleEdit_{{$schedule['id']}}" onclick="editMatchSchedule({{$schedule['id']}}, 1, '', 'mainmatchschedule')" class="add_score_but">Edit</a></span></p>
                        @elseif($schedule['winner_text']==trans('message.schedule.pending'))
                            <p><span style="color:rgba(0,0,0,0.5)">{{ trans('message.schedule.invitationpending') }}</span></p>
                        @elseif($schedule['winner_text']==trans('message.schedule.rejected'))
                            <p><span style="color:rgba(0,0,0,0.5)">{{ trans('message.schedule.invitationrejected') }}</span></p>
                        @else    
                            <p><span><a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}" class="add_score_but">{{$schedule['winner_text']}}</a></span></p>
                        @endif    
                    @endif
                </div>
             </div>
        </div>
        @endforeach
        @else
        <div class="col-xs-12 col-centered">
            <div class="message_new_for_team">Get Started, Create your Team(s), Search and Schedule matches with other teams.</div>
            <div class="intro_list_container">
                    <ul class="intro_list_on_empty_pages">
                            <span class="steps_to_follow">Steps to follow:</span>
                            <li>If you already own / manage teams, Click on <span class="bold">Create +</span> button on the top left side, and select <span class="bold">Match Schedule</span> to schedule a match. You can schedule a match between your own teams or with others teams.</li>
                            <li>Once scheduled, the other party will get a notification and an email, once they accept it, you will see <span class="bold">Add Score</span> link on the match day.</li>
                            <li>If you don't own any teams, please create teams using <span class="bold">Create +</span> button on the top left side, and select <span class="bold">Team</span>, give required details and you are done. You can see all your own / managed teams under <span class="bold">Managed Teams</span> tab in <span class="bold">My Teams</span> page.</li>
                            <li>You can search for Teams / Tournaments / Players (in your locality too) using Search.</li>
                    </ul>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="clearfix"></div>
@if($matchScheduleDataTotalCount>count($matchScheduleData)) 

<div id="viewmorediv">
    <a id="viewmorebutton" class="view_tageline_mkt">
        <span class="market_place"><i class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span>
    </a>
</div>
@endif





<script type="text/javascript">
            $(document).ready(function() {
    $("#offset").val({{$offset}});
            if ($("#viewmorediv").length) {
    $('#viewmorebutton').on("click", function(e) {
    var params = { limit:{{$limit}}, offset:$("#offset").val(), sportsId:$("#sportsId").val() };
            viewMore(params, '{{URL('viewmoremylist',[$userId])}}');
    });
            global_record_count = {{$matchScheduleDataTotalCount}}
    }

    });
</script>