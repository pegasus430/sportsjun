    @if(count($statsArray))
    <div id="teamStatsDiv" class="table-responsive">
        <table class="table table-hover">
        	<thead>
            <tr>
            <th></th>    
            <th>{{trans('message.team.stats.matches')}}</th>
            <th>{{trans('message.team.stats.won')}}</th>
            <th>{{trans('message.team.stats.lost')}}</th>
            <th>{{trans('message.team.stats.tied')}}</th>
            <th>% {{trans('message.team.stats.won')}}</th>
            </tr>
            </thead>
        <tbody>

     
        @for($i=5; $i<=45; $i=$i+5)
            @if(count($statsArray[$i.'StatsArray'])) 
            <tr>
            <td>{{$i}}</td>
            <td>{{ $statsArray[$i.'StatsArray']['totalMatches'] }}</td>
            <td>{{ $statsArray[$i.'StatsArray']['winCount'] }}</td>
            <td>{{ $statsArray[$i.'StatsArray']['looseCount'] }}</td>
            <td>{{ $statsArray[$i.'StatsArray']['isTied'] }}</td>
            <td>{{ $statsArray[$i.'StatsArray']['wonPercentage'] }}</td>
            </tr>
            @endif
        @endfor
            @if(count($statsArray['odiStatsArray'])) 
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.CRICKET.odi') }}</td>
            <td>{{ $statsArray['odiStatsArray']['totalMatches'] }}</td>
            <td>{{ $statsArray['odiStatsArray']['winCount'] }}</td>
            <td>{{ $statsArray['odiStatsArray']['looseCount'] }}</td>
            <td>{{ $statsArray['odiStatsArray']['isTied'] }}</td>
            <td>{{ $statsArray['odiStatsArray']['wonPercentage'] }}</td>
            </tr>
            @endif
            @if(count($statsArray['tTwentyStatsArray'])) 
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.CRICKET.t20') }}</td>
            <td>{{ $statsArray['tTwentyStatsArray']['totalMatches'] }}</td>
            <td>{{ $statsArray['tTwentyStatsArray']['winCount'] }}</td>
            <td>{{ $statsArray['tTwentyStatsArray']['looseCount'] }}</td>
            <td>{{ $statsArray['tTwentyStatsArray']['isTied'] }}</td>
            <td>{{ $statsArray['tTwentyStatsArray']['wonPercentage'] }}</td>
            </tr>
            @endif
            @if(count($statsArray['testStatsArray'])) 
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.CRICKET.test') }}</td>
            <td>{{ $statsArray['testStatsArray']['totalMatches'] }}</td>
            <td>{{ $statsArray['testStatsArray']['winCount'] }}</td>
            <td>{{ $statsArray['testStatsArray']['looseCount'] }}</td>
            <td>{{ $statsArray['testStatsArray']['isTied'] }}</td>
            <td>{{ $statsArray['testStatsArray']['wonPercentage'] }}</td>
            </tr>
            @endif
        </tbody>
        </table>
    </div>
    @else
   
    
    <div class="sj-alert sj-alert-info">
                        {{ trans('message.sports.nostats')}}
                    </div>
    
    @endif
<br>
    
