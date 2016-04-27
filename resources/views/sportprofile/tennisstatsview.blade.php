@if (count($sportsPlayerStatistics))
    <h4><b>{{ config('constants.TENNIS_OR_TT_STATS.TENNIS_STATISTICS')}}</b></h4>
    <div class="table-responsive stats-table">
    <table class="table">
        <thead>
            <tr>
                <th>{{ config('constants.STATISTICS.MATCH_TYPE')}}</th>  
                <th>{{ config('constants.STATISTICS.MATCHES')}}</th>
                <th>{{ config('constants.TENNIS_OR_TT_STATS.WON')}}</th>
                <th>{{ config('constants.TENNIS_OR_TT_STATS.LOST')}}</th>
                <!--<th>{{ config('constants.TENNIS_OR_TT_STATS.TIED')}}</th>-->
                <th>% {{trans('message.team.stats.won')}}</th>
                <th>{{ config('constants.TENNIS_OR_TT_STATS.ACES')}}</th>
				 <th>{{ config('constants.TENNIS_OR_TT_STATS.DOUBLE_FAULTS')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sportsPlayerStatistics as $statistic)  
            <tr>
                <td>{{ucfirst($statistic->match_type)}}</td>  
                <td>{{$statistic->matches}}</td>
                <td>{{$statistic->won}}</td>
                <td>{{$statistic->lost}}</td>
                <!--<td>{{$statistic->tied}}</td>-->
                <td>{{number_format(($statistic->won/$statistic->matches)*100,2)}}</td>
                <td>{{$statistic->double_faults}}</td>
                <td>{{$statistic->aces}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @else
    
    <div class="sj-alert sj-alert-info">
                      {{ trans('message.sports.nostats')}}
</div>
    @endif