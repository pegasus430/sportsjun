@foreach($tournaments as $tournament)
    <div class="t_details">
        <div class="row main_tour">
            <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                {!! Helper::Images($tournament['logo'] ? $tournament['logo'] : $tournament->tournamentParent['logo'],config('constants.PHOTO_PATH.TOURNAMENT'),array('height'=>90,'width'=>90,'class'=>'img-circle img-border img-scale-down') )!!}
            </div>
            <div class="col-md-10 col-sm-9 col-xs-12">
                <div class="t_tltle clearfix schedule-tournament-info">
                    <div class="pull-left">
                        {{ $tournament['name'] }}
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
            <table class="table sportsjun-datatable">
                <thead class="sportsjun-datatable-head">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Event</th>
                    <th>Location</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($tournament->matches as $item)
                    <tr>
                        <td>
                            <span>{{$item->match_start_date}}</span><br/>
                            <span class="schedule-time">{{$item->match_start_time}}</span>
                        </td>
                        <td class="text-capitalize">{{$item->match_type}}</td>
                        <td><p>{{$tournament->name}}<br/>
                            <span class="schedule-team-name">{{$item->scheduleA ? $item->scheduleA->name : '' }}</span> <b>vs</b> <span class="schedule-team-name">{{ $item->scheduleB ? $item->scheduleB->name : '' }}</span></p>
                        </td>
                        <td>
                            @if ($item->address)
                                {{$item->address}}<br/>
                            @endif
                           <span class="schedule-address-global-info">{{$item->country ? $item->country.',': ''}} {{$item->city ? $item->city.',': ''}} {{$item->state}}</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
@if ($tournaments->hasMorePages())
    <div id="viewmorediv">
        <a id="viewmorebutton" class="view_tageline_mkt" data-replace="#viewmorediv"
           data-url="{{route('organization.schedules.list',['id'=>$id,'page'=>$tournaments->currentPage()+1,'filter-event'=>$filter_event])}}"
           onclick="return DataTableLoadMore(this);"
        >
                    <span class="market_place"><i
                                class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span>
        </a>
    </div>
@endif
