@if(count($matchScheduleData))

    <div class="row">
         <div class="schedule_list clearfix">
            <div id="teamone" class="col-xs-3">
                @if(isset($matchScheduleData['scheduleteamone']['name']))
                <p><!--<img src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.$matchScheduleData['scheduleteamone']['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="30" width="30">-->
			     {!! Helper::Images($matchScheduleData['scheduleteamone']['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}</p>
                @endif
                <p class="vs_text">
                    <a href="{{ url('/team/members',[$matchScheduleData['a_id']]) }}">
                        {{$matchScheduleData['scheduleteamone']['name']}}
                    </a>
                 </p>
            </div>    
             
            <div id="center_div" class="col-xs-6"> 
                 <span style="position: absolute; margin-top: 25px;">vs</span>
            </div>
             
            <div id="teamtwo" class="col-xs-3">
                @if(isset($matchScheduleData['scheduleteamtwo']['name']))
                <p><!--<img src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.$matchScheduleData['scheduleteamtwo']['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="30" width="30">-->
			     {!! Helper::Images($matchScheduleData['scheduleteamtwo']['url'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}</p>
                @endif
                <p class="vs_text">
                    @if(isset($matchScheduleData['scheduleteamtwo']['name']))
                        <a href="{{ url('/team/members',[$matchScheduleData['b_id']]) }}">
                            {{$matchScheduleData['scheduleteamtwo']['name']}}
                        </a>    
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
                    <p>
                        <span style="color:rgba(0,0,0,0.5)">
                            @if($matchScheduleData['winner_text']==trans('message.schedule.pending'))
                                {{ trans('message.schedule.invitationpending') }}
                            @elseif($matchScheduleData['winner_text']==trans('message.schedule.rejected'))
                                {{ trans('message.schedule.invitationrejected') }}
                            @else    
                                <a href="{{ url('match/scorecard/edit/'.$scheduleId) }}">{{$matchScheduleData['winner_text']}}</a>
                            @endif    
                        </span>
                    </p> 
                    @elseif($isOwner)
                      <p><span><a href="#" id="scheduleEdit_{{$scheduleId}}" onclick="editMatchSchedule({{$scheduleId}},{{$isOwner}},'','myModal')" class="add_score_but">Edit</a></span></p> 
                    @endif
                </div>
             
        </div>    
    </div>    
  
@endif
