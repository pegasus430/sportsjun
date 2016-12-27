    @if(count($statsArray))
    <div id="teamStatsDiv">
        <table class="table table-hover">
        	<thead>
            <tr>
            <th>{{trans('message.team.stats.events')}}</th>
            <th>{{trans('message.team.stats.third_position')}}</th>
            <th>{{trans('message.team.stats.second_position')}}</th>
            <th>{{trans('message.team.stats.first_position')}}</th>        
      
            </tr>
            </thead>
        <tbody>
            @if(count($statsArray)) 
            <tr>
            <td><center>{{ $statsArray['events'] }}</center></td>
            <td>{{ $statsArray['third'] }}</td>
            <td>{{ $statsArray['second'] }}</td>
            <td>{{ $statsArray['first'] }}</td>           
  
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
    
