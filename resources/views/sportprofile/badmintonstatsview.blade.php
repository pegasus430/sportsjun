@if (count($sportsPlayerStatistics))
    <h4><b>{{ config('constants.BADMINTON_STATS.BADMINTON_STATISTICS')}}</b></h4>
    <div class="table-responsive stats-table">
    <table class="table">
        <thead>
            <tr>
                <th>{{ config('constants.STATISTICS.MATCH_TYPE')}}</th>  
                <th>{{ config('constants.STATISTICS.MATCHES')}}</th>
                <th>{{ config('constants.BADMINTON_STATS.WON')}}</th>
                <th>{{ config('constants.BADMINTON_STATS.LOST')}}</th>
                <!--<th>{{ config('constants.TENNIS_OR_TT_STATS.TIED')}}</th>-->
                <th>% {{trans('message.team.stats.won')}}</th>
         
                <!--<th>{{ config('constants.TENNIS_OR_TT_STATS.POINTS')}}</th>-->
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
                @if($statistic->matches!=0)
                <td>{{number_format(($statistic->won/$statistic->matches)*100,2)}}</td>
                @endif
             
                <!--<td>{{$statistic->points}}</td>-->
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