@if (count($player_standing))
    <h4><b>{{ config('constants.SOCCER_STATS.SOCCER_STATISTICS')}}</b></h4>

    <div class="table-responsive stats-table print_table teamStatsDiv" >
    <table class="table table-hover table-bordered">

        <thead>
            <tr>
                <th>PLAYER </th>
                <th>TEAM </th>
                <th>MAT</th>   

            @for($i=10; $i>=5; $i--)             
                <th>{{$i}} Pts</th>
            @endfor
                <th>Points</th>
<!--                <th>{{ config('constants.SOCCER_STATS.GOALS_SAVED')}}</th>
                <th>{{ config('constants.SOCCER_STATS.GOALS_ASSIST')}}</th>
                <th>{{ config('constants.SOCCER_STATS.GOALS_PENALTIES')}}</th>-->
            </tr>
        </thead>
        <tbody>
            @foreach($player_standing as $statistic)  
            <tr>
                
              
                <td><a href='/editsportprofile/{{$statistic->user_id}}' class="text-primary"> 
                   <span class='hidden-xs hidden-sm'> 
                   
                         
 {!! Helper::Images($statistic->logo,'user_profile',array('class'=>'img-circle img-border','height'=>52,'width'=>52), isset($to_print)?true:false )!!}
                             
                                
                             
                    </span>
                {{$statistic->player_name}}</a></td>                
                <td><a href='/team/members/{{$statistic->team_id}}' class="text-primary">{{$statistic->name}}</a></td>                
                <td>{{$statistic->matches}}</td>
            @for($i=10; $i>=5; $i--)             
                <td>{{Helper::displayEmptyDash(ScoreCard::get_archery_tournament_points($statistic, $statistic->user_id, $i, true), '-')}}</th>
            @endfor
                <td>{{Helper::displayEmptyDash($statistic->total_points)}}</td>
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