    @if(count($statsArray))
    <div id="teamStatsDiv">
        <table class="table table-hover">
        	<thead>
            <tr>
            <th>{{trans('message.team.stats.matches')}}</th>
            <th>{{trans('message.team.stats.won')}}</th>
            <th>{{trans('message.team.stats.lost')}}</th>
            <th>{{trans('message.team.stats.tied')}}</th>
           <th>{{trans('message.team.stats.washout')}}</th>
            <th>% {{trans('message.team.stats.won')}}</th>
            </tr>
            </thead>
        <tbody>
            @if(count($statsArray['othersStatsArray'])) 
            <tr>
            <td>{{ $statsArray['othersStatsArray']['totalMatches'] }}</td>
            <td>{{ $statsArray['othersStatsArray']['winCount'] }}</td>
            <td>{{ $statsArray['othersStatsArray']['looseCount'] }}</td>
            <td>{{ $statsArray['othersStatsArray']['isTied'] }}</td>
             <td>{{ $statsArray['othersStatsArray']['washout'] }}</td>
            <td>{{ $statsArray['othersStatsArray']['wonPercentage'] }}</td>
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
    
