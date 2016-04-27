@if(count($matchScheduleData))

<div class="row">
    <div class="schedule_list clearfix">
        <div id="teamone" class="col-xs-3">
            @if(isset($matchScheduleData[$scheduleTypeOne]['name']))
            <p>
                @if($matchScheduleData['schedule_type']=='team') 
                {!! Helper::Images($matchScheduleData[$scheduleTypeOne]['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}
                @else
                {!! Helper::Images($matchScheduleData[$scheduleTypeOne]['url'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>30,'width'=>30) )!!}
                @endif    
            </p>
            @endif
            <!--<p class="vs_text">{{isset($matchScheduleData[$scheduleTypeOne]['name'])?$matchScheduleData[$scheduleTypeOne]['name']:''}}</p>-->
            <p class="vs_text">
                @if($matchScheduleData['schedule_type']=='team')
                    <a href="{{ url('/team/members',[$matchScheduleData['a_id']]) }}">{{$matchScheduleData[$scheduleTypeOne]['name']}}</a>
                @else
                    <a href="{{ url('/showsportprofile',[$matchScheduleData['a_id']]) }}">{{$matchScheduleData[$scheduleTypeOne]['name']}}</a>
                @endif
            </p>
        </div>    

        <div id="center_div" class="col-xs-6"> 
            <span style="position: absolute; margin-top: 25px;">vs</span>           
        </div>

        <div id="teamtwo" class="col-xs-3">
            @if(isset($matchScheduleData[$scheduleTypeTwo]['name']))
            <p>
                @if($matchScheduleData['schedule_type']=='team') 
                {!! Helper::Images($matchScheduleData[$scheduleTypeTwo]['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}
                @else
                {!! Helper::Images($matchScheduleData[$scheduleTypeTwo]['url'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>30,'width'=>30) )!!}
                @endif
            </p>
            @endif
            <!--<p class="vs_text">{{isset($matchScheduleData[$scheduleTypeTwo]['name'])?$matchScheduleData[$scheduleTypeTwo]['name']:''}}</p>-->
            <p class="vs_text">
                @if(isset($matchScheduleData[$scheduleTypeTwo]['name']))
                    @if($matchScheduleData['schedule_type']=='team')
                        <a href="{{ url('/team/members',[$matchScheduleData['b_id']]) }}">{{$matchScheduleData[$scheduleTypeTwo]['name']}}</a>
                    @else
                        <a href="{{ url('/showsportprofile',[$matchScheduleData['b_id']]) }}">{{$matchScheduleData[$scheduleTypeTwo]['name']}}</a>
                    @endif
                @else  
                    {{trans('message.bye')}}
                @endif  
            </p>
        </div>
        <div class="clearfix"></div>
                <div class="col-xs-12">
                    <p>
                        <span>{{ $matchScheduleData['match_start_date'] }}</span>
                        <span class='sports_text'>{{ isset($matchScheduleData['sport']['sports_name'])?$matchScheduleData['sport']['sports_name']:'' }}</span>
                        @if($matchScheduleData['match_type']!='other')
                            <span class='match_type_text'>({{ $matchScheduleData['match_type']=='odi'?strtoupper($matchScheduleData['match_type']):ucfirst($matchScheduleData['match_type']) }})</span>
                        @endif
                    </p>  
                    @if(isset($matchScheduleData['winner_text']))
                        @if($matchScheduleData['winner_text']=='Edit') 
                            <p><span><a href="#" id="scheduleEdit_{{$matchScheduleData['id']}}" onclick="editMatchSchedule({{$matchScheduleData['id']}}, 1, '','mainmatchschedule')" class="add_score_but">Edit</a></span></p>
                        @elseif($matchScheduleData['winner_text']==trans('message.schedule.pending'))  
                            <p><span style="color:rgba(0,0,0,0.5)">{{ trans('message.schedule.invitationpending') }}</span></p>
                        @elseif($matchScheduleData['winner_text']==trans('message.schedule.rejected'))  
                            <p><span style="color:rgba(0,0,0,0.5)">{{ trans('message.schedule.invitationrejected') }}</span></p>
                        @else
                            <p><span><a href="{{ url('match/scorecard/edit/'.$matchScheduleData['id']) }}">{{$matchScheduleData['winner_text']}}</a></span></p>
                        @endif    
                    @endif
                </div>

    </div>    
</div>    

@endif
