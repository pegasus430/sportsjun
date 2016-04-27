@extends('layouts.app')
@section('content')
@include ('tournaments._leftmenu')

<div class="col_middle tournament_profile">
    <div class="col-lg-9 leftsidebar">
        <div class="panel panel-default">
            <div class="panel-heading">
                Tournament Profile
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#home" data-toggle="tab" aria-expanded="true">Managed Tournaments <span class="t_badge"> {{ count($manageTeamArray) }} </span></a>
                    </li>
                    <li class=""><a href="#profile" data-toggle="tab" aria-expanded="false">Joined Tournaments <span class="t_badge"> {{ count($joinTeamArray) }} </span></a>
                    </li>
                    <li class=""><a href="#messages" data-toggle="tab" aria-expanded="false">Following Tournaments  <span class="t_badge"> {{ count($followingTeamArray) }} </span></a>
                    </li>

                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="home">
                        <table class="table">
                            <tbody>
                                @if(count($manageTeamArray))    
                                @foreach($manageTeamArray as $mangkey => $managedTeam)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-xs-2 text-center">
                                              <!--<img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">-->
                                                <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TOURNAMENT').'/'.$managedTeam['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="t_details">
                                                    <p class="t_tltle">
                                                        <strong><a href="{{ url('tournaments/groups').'/'.$managedTeam['id'] }}">{{ $managedTeam['name'] }}</a></strong>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <strong><a href="{{ route('tournaments.edit',[$managedTeam['id']])}}">Edit</a></strong>
                                                    </p>
                                                    <ul class="t_tags">
                                                        <li>
                                                            <span class="deep-blue">By</span>
                                                            <span class="green">{{ $managedTeam['user_name'] }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Sports</span>
                                                            <span class="green">{{ $managedTeam['sports_name'] }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Teams</span>
                                                            <span class="green">{{ $managedTeam['team_count'] }}</span>
                                                        </li>
                                                    </ul>
                                                    <p class="lt-grey">{{ $managedTeam['description'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>        
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="profile">
                        <table class="table">
                            <tbody>
                                @if(count($joinTeamArray))    
                                @foreach($joinTeamArray as $joinkey => $joinedTeam)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-xs-2 text-center">
                                              <!--<img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">-->
                                                <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TOURNAMENT').'/'.$joinedTeam['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="t_details">
                                                    <p class="t_tltle">
                                                        <strong><a href="{{ url('tournaments/groups').'/'.$joinedTeam['id'] }}">{{ $joinedTeam['name'] }}</a></strong>
                                                    </p>
                                                    <ul class="t_tags">
                                                        <li>
                                                            <span class="deep-blue">By</span>
                                                            <span class="green">{{ $joinedTeam['user_name'] }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Sports</span>
                                                            <span class="green">{{ $joinedTeam['sports_name'] }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Teams</span>
                                                            <span class="green">{{ $joinedTeam['team_count'] }}</span>
                                                        </li>
                                                    </ul>
                                                    <p class="lt-grey">{{ $joinedTeam['description'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>        
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="messages">
                        <table class="table">
                            <tbody>
                                @if(count($followingTeamArray))    
                                @foreach($followingTeamArray as $followkey => $followedTeam)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-xs-2 text-center">
                                              <!--<img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">-->
                                                <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TOURNAMENT').'/'.$followedTeam['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="t_details">
                                                    <p class="t_tltle">
                                                        <strong><a href="{{ url('tournaments/groups').'/'.$followedTeam['id'] }}">{{ $followedTeam['name'] }}</a></strong>
                                                    </p>
                                                    <ul class="t_tags">
                                                        <li>
                                                            <span class="deep-blue">By</span>
                                                            <span class="green">{{ $followedTeam['user_name'] }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Sports</span>
                                                            <span class="green">{{ $followedTeam['sports_name'] }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Teams</span>
                                                            <span class="green">{{ $followedTeam['team_count'] }}</span>
                                                        </li>
                                                    </ul>
                                                    <p class="lt-grey">{{ $followedTeam['description'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>        
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- /.panel-body -->
        </div>
    </div>

    <div class="col-lg-3 rightsidebar">
        <div class="cn_tournament_box">
            <h4 class="cntb_head">Create New Tournaments</h4>
            <p>Create and mange your sports team with no more paper scoring. Socre all your macthes live and reach all your team's followers.</p>
            <a href="#" class="btn btn-black">Create Team</a>
        </div>
        <div class="suggestion_box">
            <h4 class="sb_head">Suggested Tournaments</h4>
            <div class="row">
                <div class="sb_hover">

                    <div class="sb_details">
                        <div class="col-md-4 col-sm-2 col-xs-2 text-center">
                            <img class="img-circle img-border" src="http://localhost/sportsjun/public/images/sunrisers_hyd.png" style="width: 90%;height:90%;">
                        </div>
                        <div class="col-md-8 col-sm-10 col-xs-10">
                            <p class="sb_title"><strong>Indian Premier League</strong></p>
                            <ul class="sb_tags">
                                <li>
                                    <small><span class="grey">Sports</span>
                                        <span class="black">Cricket</span></small>
                                </li>
                                <li>
                                    <small><span class="grey">Teams</span>
                                        <span class="black">10</span></small>
                                </li>
                            </ul>
                            <div class="sb_join"><a href="#">Join</a></div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>
@endsection
