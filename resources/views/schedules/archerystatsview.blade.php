    @if(count($statsArray))
    <div id="teamStatsDiv">
        <h3>Event Stats</h3>
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
            @if(count($statsArray['event_level'])) 
            <tr>
            <td><center>{{ $statsArray['event_level']['events'] }}</center></td>
            <td>{{ $statsArray['event_level']['third'] }}</td>
            <td>{{ $statsArray['event_level']['second'] }}</td>
            <td>{{ $statsArray['event_level']['first'] }}</td>           
  
            </tr>
            @else
                       <tr> <td colspan="4">   {{ trans('message.sports.nostats')}} </td> </tr>
            @endif
        </tbody>
        </table>

        <br><br>
        <h3>Match Stats</h3>
       <table class="table table-hover">
            <thead>
            <tr>
            <th>Mat</th>
         @for($i=10; $i>=5; $i--)
            <th>{{$i}} Pts</th>
        @endfor
            <th>Points </th>  
           
      
            </tr>
            </thead>
        <tbody>
        
            <tr>
            <td><center>{!! $statsArray['match_level']->count() !!}</center></td>
                  @for($i=10; $i>=5; $i--)
                    <td>{{Helper::displayEmptyDash($statsArray['pt_'.$i], '-')}}</td>
                @endfor        
                    <td>{{$statsArray['match_level']->sum('total')}}</td>
  
            </tr>
    
        </tbody>
        </table>
    </div>
    @else
    
    <div class="sj-alert sj-alert-info">
                       {{ trans('message.sports.nostats')}}
</div>
    @endif
<br>
    
