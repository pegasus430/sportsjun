 @extends('layouts.app')
 @section('content') 
 @include ('teams.orgleftmenu')
<div id="content" class="col-sm-10" style="height: 986px;">
    <div class="col-sm-9 tournament_profile">
       
        @if(count($parent_tournaments)) 
			<div class="group_no clearfix">
                <h4 class="stage_head">Tournaments</h4>
            </div>        
			@foreach($parent_tournaments as $parent_tournament)
                <div class="teams_search_display row main_tour">
                    <div class='col-sm-12'>	
                       <div class='t_tltle' style="padding-left:30px;"><div><h3>Tournament : {{ $parent_tournament->name }}

                       </h3>
                            <span class="pull-right"><button class="btn btn-event" href="javascript:void(0);" data-toggle="modal" data-target="#overall_standing_{{$parent_tournament->id}}">Overall Standing</span>
                       </div></div>
                    </div>

                    @foreach($parent_tournament->tournaments as $lis)

                    <div class="search_thumbnail right-caption">

                        <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                            {!! Helper::Images( $lis->logo ? $lis->logo : $parent_tournament->logo ,'tournaments',array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down img-responsive') )!!}
                        </div>
                        <div class="col-md-10 col-sm-9 col-xs-12">
                            <div class="t_tltle">
                                <div class="pull-left">Event: <a
                                        @if (!(isset($is_widget) && ($is_widget)))
                                        href="{{ url('/gettournamentdetails').'/'.$lis->id }}"
                                    @else
                                        href="#" style="pointer-events:none;text-decoration: none"
                                   @endif id="{{'touname_'.$lis->id}}"><i class="fa fa-link"></i> {{ $lis->name }}</a></div>
                                <p class="search_location t_by">{{ $lis->location }}</p>
                            </div>
                            <div class="clearfix"></div>
                            <p>Tournament Type: {{ $lis->type }}</p>
                            @if($lis->prize_money)
                                <p>Prize Money: {{ $lis->prize_money }}₹</p>
                            @endif
                            @if(isset($lis->winnerName))
                            <div class="m-y-10 red search-results-tournament-winner">Winner: <span>{{ $lis->winnerName }}</span></div>
                            @endif
                            <div class="m-y-10 {{ $lis->statusColor }}">{{ Helper::displayDate($lis->start_date,1) }} to {{ Helper::displayDate($lis->end_date,1) }}</div>
                            <ul class="t_tags">
                                <li>
                                    Status: <span class="green">{{ $lis->status }}</span>
                                </li>
                                @if(isset($sports_array[$lis->sports_id]))
                                <li>
                                    Sport: <span class="green">{{$sports_array[$lis->sports_id]}}</span>
                                </li>
                                @endif
                                @if($lis->enrollment_fee) 
                                <li>
                                    Enrollment Fee: <span class="green">{{$lis->enrollment_fee}}</span>
                                </li>
                                @endif
                                <li class="pull-right">
                                    <button class='btn schedule_but_new' href="javascript:void(0);" data-toggle="modal" data-target="#event_points_{{$lis->id}}">Event Points </button>
                                </li>
                            </ul>
                            @if($lis->description)
                            <p class="lt-grey">{{$lis->description}}</p>
                            @endif
                            <?php /*
                            <div class="sj_actions_new">
                                <?php if(!in_array($lis->id,$exist_array) && (!empty($lis->end_date && $lis->end_date!='0000-00-00')?strtotime($lis->end_date) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT'))):strtotime($lis->start_date) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT'))))) {?>
                                <div class="sb_join_tournament_main" id="{{$lis->id}}" spid="{{$lis->sports_id}}" val="{{!empty($lis->schedule_type)?(($lis->schedule_type=='individual')?'PLAYER_TO_TOURNAMENT':'TEAM_TO_TOURNAMENT'):''}}"><a href="#" class="sj_add_but"><span><i class="fa fa-check"></i>Join Tournament</span></a></div>
                                <?php }?>
                                <div class="follow_unfollow_tournament" id="follow_unfollow_tournament_{{$lis->id}}" uid="{{$lis->id}}" val="TOURNAMENT" flag="{{ in_array($lis->id,$follow_array)?0:1 }}"><a href="#" id="follow_unfollow_tournament_a_{{$lis->id}}" class="{{ in_array($lis->id,$follow_array)?'sj_unfollow':'sj_follow' }}"><span id="follow_unfollow_tournament_span_{{$lis->id}}"><i class="{{ in_array($lis->id,$follow_array)?'fa fa-remove':'fa fa-check' }}"></i>{{ in_array($lis->id,$follow_array)?'Unfollow':'Follow' }}</span></a></div> 
                            </div>	
                             * 
                             */
                            ?>			
                        </div>
                    </div>
                
                <div class=''>
                    <p>&nbsp;
                    <hr class="red">
                </div>

                @include('organization.event_points')

                
                @endforeach

                     @can('createTeam', $orgInfoObj)
                          
                          <center>   <a href='/tournaments/{{$parent_tournament->id}}/edit' class='btn btn-tiny btn-primary'> Add Event</a> </center>
                      @endcan
                         
                </div>
            @include('organization.overall_standing')

        @endforeach 
		@else
		<div class="sj-alert sj-alert-info sj-alert-sm">No tournament conducted by the organization.</div>
		@endif
    </div>
</div>
@endsection