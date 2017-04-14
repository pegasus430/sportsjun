@if (count($player_standing))

<!-- Batting -->

<div class="panel panel-default">
       <div class="panel-body">
         <ul class="nav nav-tabs nav-justified">
                                <li class="active"><a href="#batting" data-toggle="tab" aria-expanded="true"> <b>{{ config('constants.CRICKET_STATS.BATTING_STATS.BATTING_STATISTICS')}}</b></a></li>
                                <li class=""><a href="#bowling" data-toggle="tab" aria-expanded="false">   <b>{{ config('constants.CRICKET_STATS.BOWLLING_STATS.BOWLLING_STATISTICS')}}</b></a></li>
                                <li class=""><a href="#fielding" data-toggle='tab' aria-expanded='false'><b>FIELDING</b></a></li>

        </ul>

    <div class="tab-content">



    <div class="tab-pane fade active in" id="batting">

    <div class="table-responsive stats-table teamStatsDiv" >
    <table class="table table-bordered table-hover">
         <thead>
            <tr>
                <th>PLAYER</th>
                <th>TEAM</th>
                <th>MAT</th>
                <th>INN</th>
                <th>NO</th>
                <th>RUNS</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.50s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.100s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.4s')}}</th>
                <th>{{ config('constants.CRICKET_STATS.BATTING_STATS.6s')}}</th>
                <th>AVE</th>
                <th>HS</th>
                <th>S/R</th>
            </tr>
        </thead>
        <tbody>
            @foreach($player_standing['batting'] as $statistic)

        @if(!empty($statistic->totalruns))
            <tr>
                <td>
                    @if (!Auth::guest())
                    <a href='/editsportprofile/{{$statistic->user_id}}' class="text-primary">
                    @endif
            <span class='hidden-xs hidden-sm'>
                   
                         
 {!! Helper::Images($statistic->logo,'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52), isset($to_print)?true:false )!!}
                             
                                
                             
                </span>
                    {{$statistic->player_name}}
                        @if (!Auth::guest())
                    </a>
                        @endif
                </td>
                <td>
                    @if (Auth::guest())
                        <a href="{{route('public.search.view',['teams',$statistic->team_id])}}" class="text-primary">{{$statistic->team_name}}</a>
                    @else
                    <a href='/team/members/{{$statistic->team_id}}' class="text-primary">{{$statistic->team_name}}</a>
                      @endif
                      </td>
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
                <td>{{$statistic->strikerates}}</td>
            </tr>
        @endif
            @endforeach
        </tbody>
    </table>
    </div>
    </div>

    <div class="tab-pane fade" id="bowling">

    <div class=" table-responsive stats-table teamStatsDiv">
    <table class="table table-bordered table-hover">
         <thead>
            <tr>

                <th>PLAYER</th>
                <th>TEAM</th>

                <th>MAT</th>
                <th>INN</th>
                <th>W</th>
                <th>RUNS</th>
                <th>AVE</th>
                <th>ECN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($player_standing['bowling'] as $statistic)

       @if(!empty($statistic->ecomony))
            <tr>
                <td>
                    @if (!Auth::guest())
                        <a href='/editsportprofile/{{$statistic->user_id}}' class="text-primary">
                    @endif

                     <span class='hidden-xs hidden-sm'> 
                   
                         
 {!! Helper::Images($statistic->logo,'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52), isset($to_print)?true:false )!!}
                             
                                
                             
                    </span>

                    {{$statistic->player_name}}
                            @if (!Auth::guest())
                        </a>
                            @endif

                </td>
                <td>
                    @if (!Auth::guest())
                    <a href='/team/members/{{$statistic->team_id}}' class="text-primary">{{$statistic->team_name}}</a>
                    @else
                        {{$statistic->team_name}}
                    @endif

                </td>

                 <td>{{$statistic->matches}}</td>
                <td>{{$statistic->innings_bowl}}</td>
                <td>{{$statistic->wickets}}</td>
                <td>{{$statistic->runs_conceded}}</td>
                <td>{{$statistic->average_bowl}}</td>
                <td>{{$statistic->ecomony}}</td>
            </tr>
         @endif
            @endforeach
        </tbody>
    </table>
    </div>
</div>
      <div class="tab-pane fade active in" id="fielding">

    <div class="table-responsive stats-table teamStatsDiv" >
     <table class="table table-bordered table-hover">
         <thead>
            <tr>

                <th>PLAYER</th>
                <th>TEAM</th>
<!-- 
                <th>MAT</th>
                <th>INN</th> -->
                <th>CT</th>
                <th>RO</th>
                <th>ST</th>
                
            </tr>
        </thead>
        <tbody>
                  @foreach($player_standing['fielding'] as $statistic)
            <tr>
                <td>
                    @if (!Auth::guest())
                        <a href='/editsportprofile/{{$statistic->fielder_id}}' class="text-primary">
                    @endif

                     <span class='hidden-xs hidden-sm'> 
                   
                         
 {!! Helper::Images($statistic->logo,'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52), isset($to_print)?true:false )!!}
                             
                                
                             
                    </span>

                    {{$statistic->name}}
                            @if (!Auth::guest())
                        </a>
                            @endif

                </td>
                <td>
                    @if (!Auth::guest())
                    <a href='/team/members/{{$statistic->fielder_team_id}}' class="text-primary">{{$statistic->fielder_team_name}}</a>
                    @else
                        {{$statistic->fielder_team_name}}
                    @endif

                </td>
<!-- 
                 <td>{{$statistic->matches}}</td>
                <td>{{$statistic->innings_bowled}}</td> -->
                <td>{{$statistic->caught}}</td>
                <td>{{$statistic->run_out}}</td>
                <td>{{$statistic->stumped}}</td>
            
            </tr>
      
            @endforeach
        </tbody>

        </table>

    </div>
    </div>
    </div>

    @else

    <div class="sj-alert sj-alert-info">
                       {{ trans('message.sports.nostats')}}
</div>
    @endif