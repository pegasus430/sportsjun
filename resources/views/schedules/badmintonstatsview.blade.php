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
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.BADMINTON.singles') }}</td>
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
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.BADMINTON.doubles') }}</td>
            <td>{{ $statsArray['doublesStatsArray']['totalMatches'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['winCount'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['looseCount'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['isTied'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['washout'] }}</td>
            <td>{{ $statsArray['doublesStatsArray']['wonPercentage'] }}</td>
            </tr>
            @endif
           
           {{-- @if(count($statsArray['mixedStatsArray'])) 
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.BADMINTON.mixed') }}</td>
            <td>{{ $statsArray['mixedStatsArray']['totalMatches'] }}</td>
            <td>{{ $statsArray['mixedStatsArray']['winCount'] }}</td>
            <td>{{ $statsArray['mixedStatsArray']['looseCount'] }}</td>
            <td>{{ $statsArray['mixedStatsArray']['isTied'] }}</td>
            <td>{{ $statsArray['mixedStatsArray']['wonPercentage'] }}</td>
            </tr>
            @endif
         --}}
        </tbody>
        </table>
    </div>
    @else
    
    <div class="sj-alert sj-alert-info">
                      {{ trans('message.sports.nostats')}}
</div>
    @endif
<br>
    


    @if(count($rubberStats))
    RUBBERS 
    <div id="teamStatsDiv">
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
            @if(count($rubberStats['singlesStatsArray'])) 
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.TABLE TENNIS.singles') }}</td>
            <td>{{ $rubberStats['singlesStatsArray']['totalMatches'] }}</td>
            <td>{{ $rubberStats['singlesStatsArray']['winCount'] }}</td>
            <td>{{ $rubberStats['singlesStatsArray']['looseCount'] }}</td>
            <td>{{ $rubberStats['singlesStatsArray']['isTied'] }}</td>
            <td>{{ $rubberStats['singlesStatsArray']['wonPercentage'] }}</td>
            </tr>
            @endif
            @if(count($rubberStats['doublesStatsArray'])) 
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.TABLE TENNIS.doubles') }}</td>
            <td>{{ $rubberStats['doublesStatsArray']['totalMatches'] }}</td>
            <td>{{ $rubberStats['doublesStatsArray']['winCount'] }}</td>
            <td>{{ $rubberStats['doublesStatsArray']['looseCount'] }}</td>
            <td>{{ $rubberStats['doublesStatsArray']['isTied'] }}</td>
            <td>{{ $rubberStats['doublesStatsArray']['wonPercentage'] }}</td>
            </tr>
            @endif
            @if(count($rubberStats['mixedStatsArray'])) 
            <tr>
            <td> MIXED </td>
            <td>{{ $rubberStats['mixedStatsArray']['totalMatches'] }}</td>
            <td>{{ $rubberStats['mixedStatsArray']['winCount'] }}</td>
            <td>{{ $rubberStats['mixedStatsArray']['looseCount'] }}</td>
            <td>{{ $rubberStats['mixedStatsArray']['isTied'] }}</td>
            <td>{{ $rubberStats['mixedStatsArray']['wonPercentage'] }}</td>
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
