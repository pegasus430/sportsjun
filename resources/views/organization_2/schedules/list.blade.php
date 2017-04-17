@extends('layouts.organisation')

@section('content')

  <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Schedules</h2>
                    <div class="create-btn-link"> <a href="" class="wg-cnlink" data-toggle="modal" data-target="#ongoing_games">On Going Games</a></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="search-box">
                        <div class="sb-label">Filter events in your organization</div>
                        <div class="input-group col-md-12">
                            <input type="text" class="form-control input-lg" placeholder="" /> <span class="input-group-btn">
                            <i class="fa fa-search"></i>
                        </span> </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="search-reasult">
                        <div class="no-records"><i class="fa fa-frown-o" aria-hidden="true"></i> No Records Found</div>
                        <div class="tabbable-panel">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs ">
                                    <li> <a href="#page_past" data-toggle="tab">
                                PAST </a> </li>
                                    <li class="active"> <a href="#page_ongoing" data-toggle="tab">
                                ONGOING </a> </li>
                                    <li> <a href="#page_upcoming" data-toggle="tab">
                                UPCOMPING </a> </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane" id="page_past">
                                         @foreach($old_schedules as $nt)
                                        <div class="schedule_teams clearfix">
                                            <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                                                <div class="glyphicon-lg default-img"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-9 col-xs-12">
                                                <div class="t_tltle">
                                                    <h4><a href="/gettournamentdetails/{{$nt->id}}" id="touname_95">{{$nt->name}}</a></h4>
                                                    <ul class="t_tags">
                                                        <li> Matches: <span class="green">{{$nt->get_schedules('old')->count()}}</span> </li>
                                                        <li><a href="#" class="downlowd_url"><i class="fa fa-download"></i> Download Schedule</a> </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                       @endforeach
                                    </div>
                                    <div class="tab-pane active" id="page_ongoing">
                                           @foreach($current_schedules as $nt)
                                        <div class="schedule_teams clearfix">
                                            <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                                                <div class="glyphicon-lg default-img"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-9 col-xs-12">
                                                <div class="t_tltle">
                                                    <h4><a href="/gettournamentdetails/{{$nt->id}}" id="touname_{{$nt->id}}">{{$nt->name}}</a></h4>
                                                    <ul class="t_tags">
                                                        <li> Matches: <span class="green">{{$nt->get_schedules('current')->count()}}</span> </li>
                                                        <li><a href="#" class="downlowd_url"><i class="fa fa-download"></i> Download Schedule</a> </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                       @endforeach
                                    </div>
                                    <div class="tab-pane" id="page_upcoming">
                                         @foreach($next_schedules as $nt)
                                        <div class="schedule_teams clearfix">
                                            <div class="col-md-2 col-sm-3 col-xs-12 text-center">
                                                <div class="glyphicon-lg default-img"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-9 col-xs-12">
                                                <div class="t_tltle">
                                                    <h4><a href="/gettournamentdetails/{{$nt->id}}" id="touname_{{$nt->id}}">{{$nt->name}}</a></h4>
                                                    <ul class="t_tags">
                                                        <li> Matches: <span class="green">{!!$nt->get_schedules('next')->count()!!}</span> </li>
                                                        <li><a href="#" class="downlowd_url"><i class="fa fa-download"></i> Download Schedule</a> </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                       @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="modal fade" id="ongoing_games" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Schedules</h3> </div>
                <div class="modal-body">
                    <div class="content row">
                        <div class="tabbable-panel">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs ">
                                    <li> <a href="#popup_past" data-toggle="tab">
                                PAST </a> </li>
                                    <li class="active"> <a href="#popup_ongoing" data-toggle="tab">
                                ONGOING </a> </li>
                                    <li> <a href="#popup_upcoming" data-toggle="tab">
                                UPCOMPING </a> </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane" id="popup_past">
                                            @foreach($old_schedules as $nt)
                                            <center>  <h4><a href="/gettournamentdetails/{{$nt->id}}">{{strtoupper($nt->name)}}</a></h4> </center>
                                            @foreach($nt->get_schedules('old') as $sc)
                                        <div class="row fixture-team-inner clearfix">
                                            <div class="col-xs-4 col-sm-4 text-center"><img width="40" src="{{$sc->getSideALogoAttribute()}}" alt="{{count($sc->getSideAAttribute())?$sc->getSideAAttribute()->name:''}}">
                                                <h4>{{count($sc->getSideAAttribute())?$sc->getSideAAttribute()->name:''}}</h4></div>
                                            <div class="col-xs-4 col-sm-4 status text-center">
                                                <p class="time">12:00 PM</p>
                                                <p><strong>{{date('jS M Y', strtotime($sc->match_start_date))}}</strong></p>
                                                <p><strong>{{$sc->sport->sports_name}}</strong></p>
                                                <p>{{$sc->address}}</p>
                                                <p>Scores: 12 - 20</p>
                                                <p>Winners: The Hurricanes</p>
                                            </div>
                                            <div class="col-xs-4 col-sm-4 text-center"><img width="40" src="images/nations-flags/atletico-sm.png" alt="{{count($sc->getSideBAttribute())?$sc->getSideBAttribute()->name:''}}">
                                                <h4>{{count($sc->getSideBAttribute())?$sc->getSideBAttribute()->name:''}}</h4></div>
                                        </div>
                                            @endforeach
                                       @endforeach
                                    </div>
                                    <div class="tab-pane active" id="popup_ongoing">
                                            @foreach($current_schedules as $nt)
                                            <center>  <h4><a href="/gettournamentdetails/{{$nt->id}}">{{strtoupper($nt->name)}}</a></h4> </center>
                                            @foreach($nt->get_schedules('current') as $sc)
                                        <div class="row fixture-team-inner clearfix">
                                            <div class="col-xs-4 col-sm-4 text-center"><img width="40" src="{{$sc->getSideALogoAttribute()}}" alt="{{count($sc->getSideAAttribute())?$sc->getSideAAttribute()->name:''}}">
                                                <h4>{{count($sc->getSideAAttribute())?$sc->getSideAAttribute()->name:''}}</h4></div>
                                            <div class="col-xs-4 col-sm-4 status text-center">
                                                <p class="time">12:00 PM</p>
                                                <p><strong>{{date('jS M Y', strtotime($sc->match_start_date))}}</strong></p>
                                                <p><strong>{{$sc->sport->sports_name}}</strong></p>
                                                <p>{{$sc->address}}</p>
                                                <p>Scores: 12 - 20</p>
                                                <p>Winners: The Hurricanes</p>
                                            </div>
                                            <div class="col-xs-4 col-sm-4 text-center"><img width="40" src="images/nations-flags/atletico-sm.png" alt="{{count($sc->getSideBAttribute())?$sc->getSideBAttribute()->name:''}}">
                                                <h4>{{count($sc->getSideBAttribute())?$sc->getSideBAttribute()->name:''}}</h4></div>
                                        </div>
                                            @endforeach
                                       @endforeach
                                    </div>
                                    <div class="tab-pane" id="popup_upcoming">

                                        @foreach($next_schedules as $nt)
                                            <center>  <h4><a href="/gettournamentdetails/{{$nt->id}}">{{strtoupper($nt->name)}}</a></h4> </center>
                                            @foreach($nt->get_schedules('next') as $sc)
                                        <div class="row fixture-team-inner clearfix">
                                            <div class="col-xs-4 col-sm-4 text-center"><img width="40" src="{{$sc->getSideALogoAttribute()}}" alt="{{count($sc->getSideAAttribute())?$sc->getSideAAttribute()->name:''}}">
                                                <h4>{{count($sc->getSideAAttribute())?$sc->getSideAAttribute()->name:''}}</h4></div>
                                            <div class="col-xs-4 col-sm-4 status text-center">
                                                <p class="time">12:00 PM</p>
                                                <p><strong>{{date('jS M Y', strtotime($sc->match_start_date))}}</strong></p>
                                                <p><strong>{{$sc->sport->sports_name}}</strong></p>
                                                <p>{{$sc->address}}</p>
                                                <p>Scores: 12 - 20</p>
                                                <p>Winners: The Hurricanes</p>
                                            </div>
                                            <div class="col-xs-4 col-sm-4 text-center"><img width="40" src="images/nations-flags/atletico-sm.png" alt="{{count($sc->getSideBAttribute())?$sc->getSideBAttribute()->name:''}}">
                                                <h4>{{count($sc->getSideBAttribute())?$sc->getSideBAttribute()->name:''}}</h4></div>
                                        </div>
                                            @endforeach
                                       @endforeach
                                     
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

          

@stop