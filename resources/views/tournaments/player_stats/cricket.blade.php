@if (count($player_standing))
     <h4><b>{{ config('constants.CRICKET_STATS.BATTING_STATS.BATTING_STATISTICS')}}</b></h4>
    <div class=" stats-table" id='teamStatsDiv'>
    <table class="table table-hover">
         <thead>
            <tr>

                <th>PLAYER NAME</th>
                <th>TEAM NAME</th>
                <th>{{ config('constants.STATISTICS.MATCH_TYPE')}}</th>  
                <th>{{ config('constants.STATISTICS.MATCHES')}}</th>
                <th>{{ config('constants.STATISTICS.INNINGS')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.NOT_OUTS')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.TOTAL_RUNS')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.50s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.100s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.4s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.6s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.AVERAGE')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.HIGH_SCORE')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.STRIKE_RATE')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sportsPlayerStatistics as $statistic)  

            <tr>
                <td><a href='/editsportprofile/{{$statistic->team_id}}' class="text-primary">
 
                    @if($statistic->url!='')
                                <!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$statistic->url) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
                                
                         
                                    {!! Helper::Images($statistic->url,'user_profile',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                             
                                
                                @else
                            <!--    <img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                              
                                    {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                                
                    
                    @endif

                    {{$statistic->player_name}}</a></td>                
                <td><a href='/team/members/{{$statistic->team_id}}' class="text-primary">{{$statistic->team_name}}</a></td>
                <td>{{$statistic->match_type}}</td>  
                <td>{{$statistic->matches}}</td>
                <td>{{$statistic->innings_bat}}</td>
                <td>{{$statistic->notouts}}</td>
                <td>{{$statistic->totalruns}}</td>
                <td>{{$statistic->fifties}}</td>
                <td>{{$statistic->hundreds}}</td>
                <td>{{$statistic->fours}}</td>
                <td>{{$statistic->sixes}}</td>
                <td>{{$statistic->average_bat}}</td>
                <td>{{$statistic->highscore}}</td>
                <td>{{$statistic->strikerate}}</td>
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