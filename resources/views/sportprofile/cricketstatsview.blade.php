@if (count($sportsPlayerStatistics))
    <h4><b>{{ config('constants.CRICKET_STATS.BATTING_STATS.BATTING_STATISTICS')}}</b></h4>
    <div class="table-responsive stats-table">
    <table class="table">
        <thead>
            <tr>
                <th>{{ config('constants.STATISTICS.MATCH_TYPE')}}</th>  
                <th>{{ config('constants.STATISTICS.MATCHES')}}</th>
                <th>{{ config('constants.STATISTICS.INNINGS')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.NOT_OUTS')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.TOTAL_RUNS')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.50s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.100s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.4s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.6s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.AVERAGE')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.HIGH_SCORE')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.STRIKE_RATE')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sportsPlayerStatistics as $statistic)  
            <tr>
                <td>{{$statistic->match_type}}</td>  
                <td>{{$statistic->matches}}</td>
                <td>{{$statistic->innings_bat}}</td>
                <td>{{$statistic->notouts}}</td>
                <td>{{$statistic->totalruns}}</td>
                <td>{{$statistic->fifties}}</td>
                <td>{{$statistic->hundreds}}</td>
                <td>{{$statistic->fours}}</td>
                <td>{{$statistic->sixes}}</td>
                <td>{{$statistic->average_bat}}</td>
                <td>{{$statistic->highscore}}</td>
                <td>{{$statistic->strikerate}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <h4><b>{{ config('constants.CRICKET_STATS.BOWLLING_STATS.BOWLLING_STATISTICS')}}</b></h4>
    <div class="table-responsive">         
    <table class="table">
        <thead>
            <tr>
                <th>{{ config('constants.STATISTICS.MATCH_TYPE')}}</th>
                <th>{{ config('constants.STATISTICS.MATCHES')}}</th>
                <th>{{ config('constants.STATISTICS.INNINGS')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BOWLLING_STATS.WICKETS')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BOWLLING_STATS.RUNS CONCEDED')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BOWLLING_STATS.AVERAGE')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BOWLLING_STATS.ECONOMY')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sportsPlayerStatistics as $statistic)  
            <tr>
                <td>{{$statistic->match_type}}</td>  
                 <td>{{$statistic->matches}}</td>
                <td>{{$statistic->innings_bowl}}</td>
                <td>{{$statistic->wickets}}</td>
                <td>{{$statistic->runs_conceded}}</td>
                <td>{{$statistic->average_bowl}}</td>
                <td>{{$statistic->ecomony}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @else
   
    
    <div class="sj-alert sj-alert-info sj-alert-sm">{{ trans('message.sports.nostats')}}</div>
    
    @endif