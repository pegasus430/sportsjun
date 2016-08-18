@if (count($player_standing))

<!-- Batting -->

<div class="panel panel-default">
       <div class="panel-body">
         <ul class="nav nav-tabs nav-justified">
                                <li class="active"><a href="#batting" data-toggle="tab" aria-expanded="true"> <b>{{ config('constants.CRICKET_STATS.BATTING_STATS.BATTING_STATISTICS')}}</b></a></li>
                                <li class=""><a href="#bowling" data-toggle="tab" aria-expanded="false">   <b>{{ config('constants.CRICKET_STATS.BOWLLING_STATS.BOWLLING_STATISTICS')}}</b></a></li>
                               
        </ul>

    <div class="tab-content">

    <div class="tab-pane fade active in" id="batting">

    <div class="table-responsive stats-table teamStatsDiv" >
    <table class="table table-hover">
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
                <td><a href='/editsportprofile/{{$statistic->user_id}}' class="text-primary">
            <span class='hidden-xs hidden-sm'> 
                    @if($statistic->url!='')
                                <!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$statistic->url) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
                                
                         
                                    {!! Helper::Images($statistic->url,'user_profile',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                             
                                
                                @else
                            <!--    <img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                              
                                    {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                                
                    
                    @endif
                </span>
                    {{$statistic->player_name}}</a></td>                
                <td><a href='/team/members/{{$statistic->team_id}}' class="text-primary">{{$statistic->team_name}}</a></td>
    
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
        @endif
            @endforeach
        </tbody>
    </table>
    </div>
    </div>

    <div class="tab-pane fade" id="bowling">

    <div class=" table-responsive stats-table teamStatsDiv">
    <table class="table table-hover">
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
                <td><a href='/editsportprofile/{{$statistic->user_id}}' class="text-primary">
 

                     <span class='hidden-xs hidden-sm'> 
                    @if($statistic->url!='')
                                <!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$statistic->url) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
                                
                         
                                    {!! Helper::Images($statistic->url,'user_profile',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                             
                                
                                @else
                            <!--    <img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
                              
                                    {!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
                                
                    
                    @endif
                    </span>

                    {{$statistic->player_name}}</a></td>                
                <td><a href='/team/members/{{$statistic->team_id}}' class="text-primary">{{$statistic->team_name}}</a></td>
                
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
    @else
    
    <div class="sj-alert sj-alert-info">
                       {{ trans('message.sports.nostats')}}
</div>
    @endif