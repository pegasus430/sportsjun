@extends('layouts.app')
@section('content')
    <div class="col-lg-8 col-md-10 col-sm-12 col-md-offset-1 col-lg-offset-2 tournament_profile teamslist-pg"
         style="padding-top: 3px !important;">
        <div class="panel panel-default">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @elseif (session('error_msg'))
                <div class="alert alert-danger">
                    {{ session('error_msg') }}
                </div>
        @endif
        <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified" id="team_ul">
                    <li class="" id="org_tab"><a href="#managedorganizations" data-toggle="tab"
                                                 aria-expanded="true">{{ trans('message.users.fields.managedorganizations') }}
                            <span class="t_badge">{{ $managedOrgs->count() }}</span></a></li>
                    <li class="active" id="jnd_tab"><a href="#joinedorganizations" data-toggle="tab"
                                                       aria-expanded="false">{{ trans('message.users.fields.joinedorganizations') }}
                            <span class="t_badge">{{ $joinedOrgs->count() }}</span></a></li>
                    <li class="" id="foll_tab"><a href="#followingorganizations" data-toggle="tab"
                                                  aria-expanded="false">{{ trans('message.users.fields.followingorganizations') }}
                            <span class="t_badge">{{ $followingOrgs->count() }}</span></a></li>
                </ul>
                <div class="tab-content">

                    <div class="tab-pane fade" id="managedorganizations">
                        @if(count($managedOrgs))
                                @foreach($managedOrgs as $organization)
                                    @include('organization.el.list_row')
                                @endforeach
                        @else
                            <div class="message_new_for_team">Manage all your teams easily by
                                grouping them under an Organization.
                            </div>
                            <div class="intro_list_container">
                                <ul class="intro_list_on_empty_pages">
                                    <span class="steps_to_follow">Steps to follow:</span>
                                    <li>Click on the <span class="bold">Create New +</span> button
                                        on the top left side, select <span
                                                class="bold">Organization</span></li>
                                    <li>Fill all the details and select <span
                                                class="bold">teams</span> from drop-down (if needed)
                                        and <span class="bold">Create</span></li>
                                    <li>Manage all your Teams under one Organization umbrella.</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade active in" id="joinedorganizations">
                        @if(count($joinedOrgs))
                            @foreach($joinedOrgs as $organization)
                                @include('organization.el.list_row')
                            @endforeach
                        @else
                            <div class="message_new_for_team">Manage all your teams easily by
                                grouping them under an Organization.
                            </div>
                            <div class="intro_list_container">
                                <ul class="intro_list_on_empty_pages">
                                    <span class="steps_to_follow">Steps to follow:</span>
                                    <li>Click on the <span class="bold">Create New +</span> button
                                        on the top left side, select <span
                                                class="bold">Organization</span></li>
                                    <li>Fill all the details and select <span
                                                class="bold">teams</span> from drop-down (if needed)
                                        and <span class="bold">Create</span></li>
                                    <li>Manage all your Teams under one Organization umbrella.</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="followingorganizations">
                        @if(count($followingOrgs))
                            @foreach($followingOrgs as $organization)
                                @include('organization.el.list_row')
                            @endforeach
                        @else
                            <div class="message_new_for_team">Manage all your teams easily by
                                grouping them under an Organization.
                            </div>
                            <div class="intro_list_container">
                                <ul class="intro_list_on_empty_pages">
                                    <span class="steps_to_follow">Steps to follow:</span>
                                    <li>Click on the <span class="bold">Create New +</span> button
                                        on the top left side, select <span
                                                class="bold">Organization</span></li>
                                    <li>Fill all the details and select <span
                                                class="bold">teams</span> from drop-down (if needed)
                                        and <span class="bold">Create</span></li>
                                    <li>Manage all your Teams under one Organization umbrella.</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
            <!-- /.panel-body -->
        </div>
    </div>
@endsection
