    @if(count($statsArray))
    <div id="teamStatsDiv">
        <table class="table table-hover">
        	<thead>
            <tr>
            <th>{{trans('message.team.stats.events')}}</th>
            <th>{{trans('message.team.stats.third_position')}}</th>
            <th>{{trans('message.team.stats.second_position')}}</th>
            <th>{{trans('message.team.stats.first_position')}}</th>        
            <th>% {{trans('message.team.stats.won')}}</th>
            </tr>
            </thead>
        <tbody>
            @if(count($statsArray['othersStatsArray'])) 
            <tr>
            <td>{{ $statsArray['othersStatsArray']['events'] }}</td>
            <td>{{ $statsArray['othersStatsArray']['third'] }}</td>
            <td>{{ $statsArray['othersStatsArray']['second'] }}</td>
            <td>{{ $statsArray['othersStatsArray']['win'] }}</td>           
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
    
