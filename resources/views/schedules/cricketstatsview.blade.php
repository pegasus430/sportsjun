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

    <?php
        $match_type = config('constants.ENUM.SCHEDULE.MATCH_TYPE.CRICKET');

    ?>
     
        @foreach($match_type as $mt=>$value)
            @if(count($statsArray[$mt.'StatsArray'])) 
            <tr>
            <td>{{$value}}</td>
            <td>{{ Helper::displayEmptyDash($statsArray[$mt.'StatsArray']['totalMatches']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray[$mt.'StatsArray']['winCount']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray[$mt.'StatsArray']['looseCount']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray[$mt.'StatsArray']['isTied']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray[$mt.'StatsArray']['wonPercentage']) }}</td>
            </tr>
            @endif
        @endforeach

    {{--
            @if(count($statsArray['odiStatsArray'])) 
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.CRICKET.odi') }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['odiStatsArray']['totalMatches']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['odiStatsArray']['winCount']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['odiStatsArray']['looseCount']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['odiStatsArray']['isTied']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['odiStatsArray']['wonPercentage']) }}</td>
            </tr>
            @endif
            @if(count($statsArray['tTwentyStatsArray']))
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.CRICKET.t20') }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['tTwentyStatsArray']['totalMatches']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['tTwentyStatsArray']['winCount']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['tTwentyStatsArray']['looseCount']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['tTwentyStatsArray']['isTied']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['tTwentyStatsArray']['wonPercentage']) }}</td>
            </tr>
            @endif
            @if(count($statsArray['testStatsArray']))
            <tr>
            <td>{{ config('constants.ENUM.SCHEDULE.MATCH_TYPE.CRICKET.test') }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['testStatsArray']['totalMatches']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['testStatsArray']['winCount']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['testStatsArray']['looseCount']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['testStatsArray']['isTied']) }}</td>
            <td>{{ Helper::displayEmptyDash($statsArray['testStatsArray']['wonPercentage']) }}</td>
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
    
