<div  style="margin-bottom: 12px;">
@if(count($matchScheduleData))
@foreach($matchScheduleData as $schedule)
      <div class="row">
    <div class="schedule_list clearfix">
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
    </div>
@endforeach
@endif
</div>

<script type="text/javascript">
$(document).ready(function() {
				
    var offset = {{$offset}};
            $("#offset").val(offset);
            if (offset >= global_record_count)
    {
    $("#viewmorediv").remove();
    }

    });
</script>  