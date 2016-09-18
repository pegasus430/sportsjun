@if(count($matchScheduleData))

<div class="row">
    <div class="schedule_list clearfix">
        <div id="teamone" class="col-xs-3">
            @if(isset($matchScheduleData[$matchScheduleDataTypeOne]['name']))
            <p>
                @if($matchScheduleData['schedule_type']=='team') 
                {!! Helper::Images($matchScheduleData[$matchScheduleDataTypeOne]['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}
                @else
                {!! Helper::Images($matchScheduleData[$matchScheduleDataTypeOne]['url'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>30,'width'=>30) )!!}
                @endif    
            </p>
            @endif
            <!--<p class="vs_text">{{isset($matchScheduleData[$matchScheduleDataTypeOne]['name'])?$matchScheduleData[$matchScheduleDataTypeOne]['name']:''}}</p>-->
            <p class="vs_text">
                @if($matchScheduleData['schedule_type']=='team')
                    <a href="{{ url('/team/members',[$matchScheduleData['a_id']]) }}">{{$matchScheduleData[$matchScheduleDataTypeOne]['name']}}</a>
                @else
                    <a href="{{ url('/showsportprofile',[$matchScheduleData['a_id']]) }}">{{$matchScheduleData[$matchScheduleDataTypeOne]['name']}}</a>
                @endif
            </p>
        </div>    

        <div id="center_div" class="col-xs-6"> 
            <span style="position: absolute; margin-top: 25px;">vs</span>           
        </div>

        <div id="teamtwo" class="col-xs-3">
            @if(isset($matchScheduleData[$matchScheduleDataTypeTwo]['name']))
            <p>
                @if($matchScheduleData['schedule_type']=='team') 
                {!! Helper::Images($matchScheduleData[$matchScheduleDataTypeTwo]['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}
                @else
                {!! Helper::Images($matchScheduleData[$matchScheduleDataTypeTwo]['url'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>30,'width'=>30) )!!}
                @endif
            </p>
            @endif
            <!--<p class="vs_text">{{isset($matchScheduleData[$matchScheduleDataTypeTwo]['name'])?$matchScheduleData[$matchScheduleDataTypeTwo]['name']:''}}</p>-->
            <p class="vs_text">
                @if(isset($matchScheduleData[$matchScheduleDataTypeTwo]['name']))
                    @if($matchScheduleData['schedule_type']=='team')
                        <a href="{{ url('/team/members',[$matchScheduleData['b_id']]) }}">{{$matchScheduleData[$matchScheduleDataTypeTwo]['name']}}</a>
                    @else
                        <a href="{{ url('/showsportprofile',[$matchScheduleData['b_id']]) }}">{{$matchScheduleData[$matchScheduleDataTypeTwo]['name']}}</a>
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

                           <span class=''>{{Helper::getMatchDetails($matchScheduleData['id'])->address}}</span><br>
                    Status: <span class='event_date'><b>{{ ucfirst($matchScheduleData['match_status']) }}</b></span> <br>
                    Scores: <span class='blue'><b>{{Helper::getMatchDetails($matchScheduleData['id'])->scores}}</b> </span> <br>
                    @if(!is_null(Helper::getMatchDetails($matchScheduleData['id'])->winner_id))
                            <span class='red'>Winner: {{Helper::getMatchDetails($matchScheduleData['id'])->winner}} </span>
                                
                    @endif
                    
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
