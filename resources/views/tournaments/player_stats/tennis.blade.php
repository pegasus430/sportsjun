@if (count($player_standing))
    <h4><b>{{ config('constants.SOCCER_STATS.SOCCER_STATISTICS')}}</b></h4>
    <div class=" stats-table" id='teamStatsDiv'>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>PLAYER NAME</th>
                <th>TEAM NAME</th>
                <th>{{ config('constants.STATISTICS.MATCHES')}}</th>                
                <th>{{ config('constants.SOCCER_STATS.YELLOW_CARDS')}}</th>
                <th>{{ config('constants.SOCCER_STATS.RED_CARDS')}}</th>
                <th>{{ config('constants.SOCCER_STATS.GOALS_SCORED')}}</th>
<!--                <th>{{ config('constants.SOCCER_STATS.GOALS_SAVED')}}</th>
                <th>{{ config('constants.SOCCER_STATS.GOALS_ASSIST')}}</th>
                <th>{{ config('constants.SOCCER_STATS.GOALS_PENALTIES')}}</th>-->
            </tr>
        </thead>
        <tbody>
            @foreach($player_standing as $statistic)  
            <tr>
                <td>{{$statistic->player_name}}</td>                
                <td><a href='/editsportprofile/{{$statistic->team_id}}' class="text-primary">{{$statistic->player_name}}</a></td>                
                <td><a href='/team/members/{{$statistic->team_id}}' class="text-primary">{{$statistic->team_name}}</a></td>                
                <td>{{$statistic->matches}}</td>
                <td>{{$statistic->yellow_cards}}</td>
                <td>{{$statistic->red_cards}}</td>
                <td>{{$statistic->goals}}</td>
<!--                <td>{{$statistic->goals_saved}}</td>
                <td>{{$statistic->goal_assist}}</td>
                <td>{{$statistic->goal_penalties}}</td>-->
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