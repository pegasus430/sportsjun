@foreach($tournaments as $tournament)
    <div class="t_details">
        <div class="row main_tour">
            <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                {!! Helper::Images($tournament['logo'] ? $tournament['logo'] : $tournament->tournamentParent['logo'],config('constants.PHOTO_PATH.TOURNAMENT'),array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down') )!!}
            </div>
            <div class="col-md-10 col-sm-9 col-xs-12">
                <div class="t_tltle clearfix schedule-tournament-info">
                    <div class="pull-left">
                        <b>{{ $tournament['name'] }}</b>
                    </div>
                    <div class="pull-right">
                        <a href="/download/schedules?tournament_id={{ $tournament['id'] }}" class="btn-danger btn"
                           name="match_schedule_tournament_{{ $tournament['id'] }}"

                        ><i class="fa fa-download"></i>
                            Download Schedule </a>
                    </div>
                </div>

                @if($tournament->matches && $tournament->matches->count() )
                    }<a href="#matches_{{$tournament->id}}" class="show_sub_tournament" data-toggle="collapse"
                        aria-expanded="false"
                        aria-controls="matches_{{$tournament->id}}">Matches: {{$tournament->matches->count()}}</a>
                @endif
            </div>
        </div>
        <div id="matches_{{$tournament->id}}" class="collapse schedulle-matches-info">
            <div class="sportsjun-datatable">
                <div class="row sportsjun-datatable-head hidden-sm hidden-xs">
                    <div class="col-md-2">Date</div>
                    <div class="col-md-1 ">Sport Name</div>
                    <div class="col-md-3">Event</div>
                    <div class="col-md-4">Match Details</div>
                    <div class="col-md-2">Status/Score</div>
                </div>
                <div class="sportsjun-datatable-body">
                    @foreach ($tournament->matches as $match)
                        <div class="row sportsjun-datatable-item">
                            <div class="col-md-2">
                                <span class="match-detail-score">
                                    @if ($match['match_start_date'])
                                        {!!  Helper::displayDateFormat($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 'jS F, Y <\b\\r>g:i A' )  !!}</span>
                                    @else
                                        TBD
                                    @endif
                            </div>
                            <div class="col-md-1 text-capitalize">
                                <span class="hidden-md hidden-lg"><b>Sport Name:</b></span>
                                {{$match->sport->sports_name}}
                            </div>
                            <div class="col-md-3">
                                <b>{{$tournament->name}}</b>
                            </div>
                            <div class="col-md-4">
                                <div class="schedule-datatable-img">
                                    <div class="team_player_sj_img">
                                        {!! Helper::makeImageHtml($match->sideALogo,['class'=>'img-circle img-border ','height'=>52,'width'=>52]) !!}
                                    </div>
                                    VS
                                    <div class="team_player_sj_img">
                                        {!! Helper::makeImageHtml($match->sideBLogo,['class'=>'img-circle img-border ','height'=>52,'width'=>52]) !!}
                                    </div>
                                </div>

                                @if ($match->a_id != null)
                                    <span class="schedule-team-name">{{  $match->schedule_type == 'team' ? array_get($teamNames,
                                        $match->a_id) : array_get($userNames, $match->a_id) }}</span>
                                @endif
                                @if ($match->a_id !=null && $match->b_id != null)
                                    <b>vs</b>
                                @endif
                                @if ($match->b_id != null)
                                    <span class="schedule-team-name">
                                        {{ $match->schedule_type =='team' ? array_get($teamNames,$match->b_id) : array_get($userNames,$match->b_id) }}</span>
                                @endif
                                <br/>
                                @if ($match->address)
                                    {{$match->address}}<br/>
                                @endif
                                <span class="schedule-address-global-info">{{$match->country ? $match->country.',': ''}} {{$match->city ? $match->city.',': ''}} {{$match->state}}</span><br/>

                            </div>
                            <div class="col-md-2">
                                Status: <span class='event_date sports_text'>{{ucfirst($match['match_status'])}}</span>
                                <br>

                                <span class="match-detail-score">Scores: <span
                                            class='blue'>{{ $match->scores }} </span></span><br>
                                @if(!is_null($match['winner_id']))
                                    <span class="match-detail-winner red">Winner: {{ $match->winner }} </span>
                                @endif
                                <br>
                                @if($match->scoreMore)
                                    @if($match->scoreMore==trans('message.schedule.viewscore'))
                                        <span class="tournament_score pull-left"><a
                                                    href="{{ url('match/scorecard/view/'.$match['id']) }}"
                                                    class="btn-primary "
                                                    style="padding: .3em 1em;">{{$match->scoreMore}}</a></span>
                                    @else
                                        <span class="tournament_score pull-left"><a
                                                    href="{{ url('match/scorecard/edit/'.$match['id']) }}"
                                                    class="btn-primary "
                                                    style="padding: .3em 1em;">{{$match->scoreMore}}</a></span>
                                    @endif

                                @else
                                @endif


                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach
@if ($tournaments->hasMorePages())
    <div id="viewmorediv">
        <a id="viewmorebutton" class="view_tageline_mkt" data-replace="#viewmorediv"
           @if (isset($is_widget) && $is_widget)
           data-url="{{route('widget.organization.schedule',['id'=>$id,'page'=>$tournaments->currentPage()+1,'filter-event'=>$filter_event])}}"
           @else
           data-url="{{route('organization.schedules.list',['id'=>$id,'page'=>$tournaments->currentPage()+1,'filter-event'=>$filter_event])}}"
           @endif
           onclick="return DataTableLoadMore(this);"
        >
                    <span class="market_place"><i
                                class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span>
        </a>
    </div>
@endif
