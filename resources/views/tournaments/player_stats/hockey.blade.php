@if (count($player_standing))
    <h4><b>{{ config('constants.SOCCER_STATS.HOCKEY_STATISTICS')}}</b></h4>
    <div class="table-responsive stats-table teamStatsDiv" >
    <table class="table table-hover">
        <thead>
            <tr>
                <th>PLAYER</th>
                <th>TEAM </th>
                <th>MAT</th>                
                <th>Y CARDS</th>
                <th>R RARDS</th>
                <th>GOALS</th>
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
                   
                         
 {!! Helper::Images($statistic->logo,'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52), isset($to_print)?true:false )!!}
                             
                                
                             
                        </span>
                {{$statistic->player_name}}</a></td>                
                <td><a href='/team/members/{{$statistic->team_id}}' class="text-primary">{{$statistic->team_name}}</a></td>                
                <td>{{$statistic->matches}}</td>
                <td>{{Helper::displayEmptyDash($statistic->yellow_cards)}}</td>
                <td>{{Helper::displayEmptyDash($statistic->red_cards)}}</td>
                <td>{{Helper::displayEmptyDash($statistic->goals)}}</td>
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