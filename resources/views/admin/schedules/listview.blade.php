<input type="hidden" name="limit" id="limit" value="{{$limit}}"/>
<input type="hidden" name="offset" id="offset" value="{{$offset}}"/>


    <div class="col-sm-12 viewmoreclass">
       <div class="row">
            @if(count($matchScheduleData))
            @foreach($matchScheduleData as $schedule)
            <div id="schedule_{{$schedule['id']}}" class="schedule_list clearfix">
            
                <div class="deskview hidden-xs">
                
                    <div id="teamone" class="col-sm-3 score_view_img">
                    <p>
                        @if($schedule['schedule_type']=='team')
                        {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}
                        @else    
                        {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>30,'width'=>30) )!!}
                        @endif    
                    </p>
    
                </div>    
        
                    <div id="center_div" class="col-sm-6">
                
                 
                
                    <p class="vs_text">
                    
                    @if($schedule['schedule_type']=='team')
                                        <span><a href="{{ url('/team/members',[$schedule['a_id']]) }}">{{$schedule['a_name']}}</a></span>
                                    @else
                                        <span><a href="{{ url('/showsportprofile',[$schedule['a_id']]) }}">{{$schedule['a_name']}}</a></span>
                                    @endif
                    
                    
                    vs 
                    @if($schedule['schedule_type']=='team')
                                        <span><a href="{{ url('/team/members',[$schedule['b_id']]) }}">{{$schedule['b_name']}}</a></span>
                                    @else
                                        <span><a href="{{ url('/showsportprofile',[$schedule['b_id']]) }}">{{$schedule['b_name']}}</a></span>
                                    @endif
                    
                    
                    </p> 
                    <p class="vs_date">
                        <span>{{ $schedule['match_start_date'] }}</span> 								
                        <span class='sports_text'>
                            {{ isset($schedule['sport']['sports_name'])?$schedule['sport']['sports_name']:'' }}
                            @if($schedule['match_type']!='other')
                                ({{ $schedule['match_type']=='odi'?strtoupper($schedule['match_type']):ucfirst($schedule['match_type']) }})
                            @endif
                        </span>
                    </p> 	
                    
                    @if(isset($schedule['winner_text']))
                    <p><span><a href="{{ url('admin/match/scorecard/edit/'.$schedule['id']) }}" class="add_score_but">{{$schedule['winner_text']}}</a></span></p>
                    @else
                    <p><span><a href="#" id="scheduleEdit_{{$schedule['id']}}" >Invited Status Pending</a></span></p>
                    @endif
                </div>
    
                    <div id="teamtwo" class="col-sm-3 score_view_img">
                    <p>
                        @if($schedule['schedule_type']=='team')
                        {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}
                        @else    
                        {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>30,'width'=>30) )!!}
                        @endif    
                    </p>
                </div>
                    
                </div>
                
                <div class="mobview hidden-sm hidden-lg">
                    <div class="row">
                
                    <div id="teamone" class="col-xs-5 score_view_img">
                    <p>
                        @if($schedule['schedule_type']=='team')
                        {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}
                        @else    
                        {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>30,'width'=>30) )!!}
                        @endif    
                    </p>
                     <p class="vs_text">
                    
                        @if($schedule['schedule_type']=='team')
                                        <span><a href="{{ url('/team/members',[$schedule['a_id']]) }}">{{$schedule['a_name']}}</a></span>
                                    @else
                                        <span><a href="{{ url('/showsportprofile',[$schedule['a_id']]) }}">{{$schedule['a_name']}}</a></span>
                                    @endif
                     </p>
    
                </div>    
        
                    <div id="center_div" class="col-xs-2">			
                        <span style="position: absolute; margin-top: 25px;">vs</span>                     
                    </div>
    
                    <div id="teamtwo" class="col-xs-5 score_view_img">
                        <p>
                            @if($schedule['schedule_type']=='team')
                            {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('height'=>30,'width'=>30) )!!}
                            @else    
                            {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('height'=>30,'width'=>30) )!!}
                            @endif    
                        </p>
                        <p class="vs_text">
                            @if($schedule['schedule_type']=='team')
                                <span><a href="{{ url('/team/members',[$schedule['b_id']]) }}">{{$schedule['b_name']}}</a></span>
                            @else
                            <span><a href="{{ url('/showsportprofile',[$schedule['b_id']]) }}">{{$schedule['b_name']}}</a></span>
                            @endif								
                        </p> 
                    </div>
                    
                </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <p>
                            <span>{{ $schedule['match_start_date'] }}</span> 								
                            <span class='sports_text'>
                                {{ isset($schedule['sport']['sports_name'])?$schedule['sport']['sports_name']:'' }}
                                @if($schedule['match_type']!='other')
                                    ({{ $schedule['match_type']=='odi'?strtoupper($schedule['match_type']):ucfirst($schedule['match_type']) }})
                                @endif
                            </span>
                        </p> 	
                        
                        @if(isset($schedule['winner_text']))
                        <p><span><a href="{{ url('admin/match/scorecard/edit/'.$schedule['id']) }}" class="add_score_but">{{$schedule['winner_text']}}</a></span></p>
                        @else
                        <p><span><a href="#" id="scheduleEdit_{{$schedule['id']}}" >Invited Status Pending</a></span></p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="col-xs-6 col-centered">
                {{ trans('message.schedule.noschedule')}}
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
    var params = { limit:{{$limit}}, offset:$("#offset").val(), month:$("#currentMonth").val(),
           sportsId:{{!empty($sportsId)?$sportsId:'null'}}, year:$("#currentYear").val()   };
            viewMore(params, '{{URL('admin/Schedules/viewmorelist')}}');
    });
            global_record_count = {{$matchScheduleDataTotalCount}}
    }

    });

</script>



