@extends('layouts.app')
@section('content')
<?php //echo '<pre>';print_r($joinTeamArray);die();?>
<div class="col_middle tournament_profile">
    <div class="col-lg-9 leftsidebar">
        <div class="panel panel-default">
            <div class="panel-heading">
                Teams
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#home" data-toggle="tab" aria-expanded="true">{{ trans('message.users.fields.managedteams') }}<span class="t_badge">{{ count($manageTeamArray) }}</span></a>
                    </li>
                    <li class=""><a href="#profile" data-toggle="tab" aria-expanded="false">{{ trans('message.users.fields.joinedteams') }}<span class="t_badge">{{ count($joinTeamArray) }}</span></a>
                    </li>
                    <li class=""><a href="#messages" data-toggle="tab" aria-expanded="false">{{ trans('message.users.fields.followingteam') }}<span class="t_badge">{{ count($followingTeamArray) }}</span></a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                    <div class="tab-pane fade active in" id="home">
                        <table class="table">
                            <tbody>
                                @if(count($manageTeamArray))    
                                @foreach($manageTeamArray as $managed_team)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-xs-2 text-center">
                                                @if(count($managed_team['photos']))
                                                @foreach($managed_team['photos'] as $p)
                                                    <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.(!empty($p['url'])?$p['url']:'')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">
                                                @endforeach
                                                @else
                                                    <img class="img-circle img-border" src="{{ asset('/images/default-profile-pic.jpg') }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">
                                                @endif                                               
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="t_details">
                                                    <p class="t_tltle">
                                                        <strong><a href="{{ url('/team/members').'/'.(!empty($managed_team['id'])?$managed_team['id']:0) }}">{{ !empty($managed_team['name'])?$managed_team['name']:'' }}</a></strong>  
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <strong><a href="{{ url('/team/edit/'.(!empty($managed_team['id'])?$managed_team['id']:0))}}">Edit</a></strong>
                                                        &nbsp;&nbsp;
                                                        <strong><a href="{{ url('/team/delete/'.(!empty($managed_team['id'])?$managed_team['id']:0))}}">Delete</a></strong>
                                                    </p>
                                                    <ul class="t_tags">
                                                        <li>
                                                            <span class="deep-blue">By</span>
                                                            <span class="green">{{ !empty($managed_team['user']['name'])?$managed_team['user']['name']:'' }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Sports</span>
                                                            <span class="green">{{ !empty($managed_team['sports']['sports_name'])?$managed_team['sports']['sports_name']:'' }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Teams</span>
                                                            <span class="green">{{ !empty($managed_team['teamplayers'])?count($managed_team['teamplayers']):0 }}</span>
                                                        </li>
                                                    </ul>
                                                    <p class="lt-grey">{{ !empty($managed_team['description'])?$managed_team['description']:'' }}</p>
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
                               @foreach($joinTeamArray as $joined_team)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-xs-2 text-center">
                                                @if(count($joined_team['photos']))
                                                @foreach($joined_team['photos'] as $p)
                                                    <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.(!empty($p['url'])?$p['url']:'')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">
                                                @endforeach
                                                @else
                                                    <img class="img-circle img-border" src="{{ asset('/images/default-profile-pic.jpg') }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">
                                                @endif
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="t_details">
                                                    <p class="t_tltle">
                                                        <strong><a href="{{ url('/team/members').'/'.(!empty($joined_team['id'])?$joined_team['id']:0) }}">{{ !empty($joined_team['name'])?$joined_team['name']:'' }}</a></strong>                                               
                                                    </p>
                                                    <ul class="t_tags">
                                                        <li>
                                                            <span class="deep-blue">By</span>
                                                            <span class="green">{{ !empty($joined_team['user']['name'])?$joined_team['user']['name']:'' }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Sports</span>
                                                            <span class="green">{{ !empty($joined_team['sports']['sports_name'])?$joined_team['sports']['sports_name']:'' }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Players</span>
                                                            <span class="green">{{ !empty($joined_team['teamplayers'])?count($joined_team['teamplayers']):0 }}</span>
                                                        </li>
                                                    </ul>
                                                    <p class="lt-grey">{{ !empty($joined_team['description'])?$joined_team['description']:'' }}</p>
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
                               @foreach($followingTeamArray as $following_team)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-xs-2 text-center">
                                                @if(count($following_team['photos']))
                                                @foreach($following_team['photos'] as $p)
                                                    <img class="img-circle img-border" src="{{ asset('/uploads/'.config('constants.PHOTO_PATH.TEAMS_FOLDER_PATH').'/'.(!empty($p['url'])?$p['url']:'')) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">
                                                @endforeach
                                                @else
                                                    <img class="img-circle img-border" src="{{ asset('/images/default-profile-pic.jpg') }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" style="width: 90%;height:90%;">
                                                @endif
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="t_details">
                                                    <p class="t_tltle">
                                                        <strong><a href="{{ url('/team/members').'/'.(!empty($following_team['id'])?$following_team['id']:0) }}">{{ !empty($following_team['name'])?$following_team['name']:'' }}</a></strong>                                        
                                                    </p>
                                                    <ul class="t_tags">
                                                        <li>
                                                            <span class="deep-blue">By</span>
                                                            <span class="green">{{ !empty($following_team['user']['name'])?$following_team['user']['name']:'' }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Sports</span>
                                                            <span class="green">{{ !empty($following_team['sports']['sports_name'])?$following_team['sports']['sports_name']:'' }}</span>
                                                        </li>
                                                        <li>
                                                            <span class="deep-blue">Players</span>
                                                            <span class="green">{{ !empty($following_team['teamplayers'])?count($following_team['teamplayers']):0 }}</span>
                                                        </li>
                                                    </ul>
                                                    <p class="lt-grey">{{ !empty($following_team['description'])?$following_team['description']:'' }}</p>
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
            <!-- /.panel-body -->
        </div>
    </div>
</div>
@endsection