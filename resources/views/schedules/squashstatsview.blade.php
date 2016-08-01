    @if(count($statsArray))
    <div id="teamStatsDiv">
        <table class="table table-hover">
        	<thead>
            <tr>
            <th></th>    
            <th>{{trans('message.team.stats.matches')}}</th>
            <th>{{trans('message.team.stats.won')}}</th>
            <th>{{trans('message.team.stats.lost')}}</th>
            <th>{{trans('message.team.stats.tied')}}</th>
            <th>{{trans('message.team.stats.washout')}}</th>
            <th>% {{trans('message.team.stats.won')}}</th>
            </tr>
            </thead>
        <tbody>
            @if(count($statsArray['singlesStatsArray'])) 
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.SQUASH.singles') }}</td>
            <td>{{ $statsArray['singlesStatsArray']['totalMatches'] }}</td>
            <td>{{ $statsArray['singlesStatsArray']['winCount'] }}</td>
            <td>{{ $statsArray['singlesStatsArray']['looseCount'] }}</td>
            <td>{{ $statsArray['singlesStatsArray']['isTied'] }}</td>
            <td>{{ $statsArray['singlesStatsArray']['washout'] }}</td>
            <td>{{ $statsArray['singlesStatsArray']['wonPercentage'] }}</td>
            </tr>
            @endif
            @if(count($statsArray['doublesStatsArray'])) 
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.SQUASH.doubles') }}</td>
            <td>{{ $statsArray['doublesStatsArray']['totalMatches'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['winCount'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['looseCount'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['isTied'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['washout'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['wonPercentage'] }}</td>
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
    
