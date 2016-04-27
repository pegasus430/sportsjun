
@if(count($matchScheduleData))
<div class="players_row clearfix" style="margin-bottom: 8px; padding-left: 12px;"><h5>RECENT MATCHES</h5></div>
@foreach($matchScheduleData as $schedule)
<div id="schedule_{{$schedule['id']}}" class="schedule_list clearfix" style="border-bottom: 1px solid #f0f0f0">
	<div class="deskview hidden-xs">
        <div id="teamone" class="col-sm-3">
            <p>
                @if($schedule['schedule_type']=='team')
                {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>'90','width'=>90) )!!}
                @else    
                {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border','height'=>'90','width'=>90) )!!}
                @endif    
            </p>
        </div>    

        <div id="center_div" class="col-sm-6">
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
            <p>
                <span>{{ $schedule['match_start_date'] }}</span>
                <span class='sports_text'>{{ isset($schedule['sport']['sports_name'])?$schedule['sport']['sports_name']:'' }}</span>
                @if($schedule['match_type']!='other')
                    <span class='match_type_text'>({{ $schedule['match_type']=='odi'?strtoupper($schedule['match_type']):ucfirst($schedule['match_type']) }})</span>
                @endif
            </p>  
            @if(isset($schedule['winner_text']))
            @if($schedule['winner_text']=='Edit') 
            <p><span><a href="#" id="scheduleEdit_{{$schedule['id']}}" onclick="editMatchSchedule({{$schedule['id']}}, 1, '')" class="sj_add_but">Edit</a></span></p>
            @else    
            <p><span><a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}" class="sj_add_but">{{$schedule['winner_text']}}</a></span></p>
            @endif    
            @endif
        </div>

        <div id="teamtwo" class="col-sm-3">
            <p>
                @if($schedule['schedule_type']=='team')
                {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>'90','width'=>90) )!!}
                @else    
                {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border','height'=>'90','width'=>90) )!!}
                @endif    
            </p>
        </div>
    </div>
    <div class="mobview hidden-sm hidden-lg">
    	<div class="row">
        <div id="teamone" class="col-xs-5">
            <p>
                @if($schedule['schedule_type']=='team')
                {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>'60','width'=>60) )!!}
                @else    
                {!! Helper::Images($schedule['a_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border','height'=>'90','width'=>60) )!!}
                @endif    
            </p>
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

        <div id="teamtwo" class="col-xs-5">
            <p>
                @if($schedule['schedule_type']=='team')
                {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH'),array('class'=>'img-circle img-border','height'=>'60','width'=>60) )!!}
                @else    
                {!! Helper::Images($schedule['b_logo'],config('constants.PHOTO_PATH.USERS_PROFILE'),array('class'=>'img-circle img-border','height'=>'60','width'=>60) )!!}
                @endif    
            </p>
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
        	<p><span>{{ $schedule['match_start_date'] }}</span></p>  
            @if(isset($schedule['winner_text']))
            @if($schedule['winner_text']=='Edit') 
            <p><span><a href="#" id="scheduleEdit_{{$schedule['id']}}" onclick="editMatchSchedule({{$schedule['id']}}, 1, '')" class="sj_add_but">Edit</a></span></p>
            @else    
            <p><span><a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}" class="sj_add_but">{{$schedule['winner_text']}}</a></span></p>
            @endif    
            @endif
        </div>
     </div>

    </div>
@endforeach

<a href="{{ url('/myschedule',[$userId]) }}" class="view_tageline_mkt"><span class="market_place"><i class="fa fa-arrow-down"></i> View All Schedule</span></a>

@endif
