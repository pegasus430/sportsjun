@extends('layouts.app')
@section('content')
    @include ('teams.orgleftmenu')
    <div class="col-lg-10 col-md-10 col-sm-12 groups">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-top-container clearfix">
                    <div class="pull-left"><h4 class="panel-heading">Team Groups</h4></div>
                    <div class="pull-right panel-right-btn">
                        @can('createGroup', $organization)
                            <button type="button"
                                    class="btn btn-primary"
                                    data-toggle="modal"
                                    data-target="#create_group">Create New Team Group
                            </button>
                        @endcan
                        <a href="{{route('organizationTeamlist',['id'=>$id])}}"
                                class="btn btn-primary"
                            >List All Teams
                        </a>
                    </div> {{-- /.panel-right-btn--}}
                </div> {{-- /.panel-top-container --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if($groups->count())
                    <div id="my_groups_container">
                        @include('organization.groups.partials.group_list')
                    </div> {{-- /#my_groups_container --}}
                @else
                    <div id="groups_empty text-left">
                        @can('createGroup', $organization)
                            <div>
                                Team Groups for Group Competation in the Organization
                            </div>

                            <br><h3><b>Steps to follow</b></h3>

                            <br>

                            <b>1.</b> Create Team Groups if organization want to group multi sport teams into Specific
                            groups. (Ex. to create group competation in the organization and points need to role up from
                            multi events.)
                            <i>
                                <center>
                                    <div id="text-center text-centered">You have not created any team groups yet</div>
                            </i></center>
                            <div id="create_first_group">
                                <!-- <button type = "button"
                                        class = "btn btn-primary"
                                        data-toggle = "modal"
                                        data-target = "#create_group">Create Your First Team Group
                                </button> -->
                            </div>

                            <br>
                            <b>2. </b> Make sure to create Staff members. Staff members will need to be linked with
                            every Team Group. (Ex: Manager, Coach, Physio etc)
                        @else
                            <div id="groups_empty_info">There are no team groups yet.</div>
                        @endcan
                    </div>
                @endif
            </div> {{-- /.panel --}}
            <div class="panel">
                @if ($teams && count($teams))
                    <div class="panel-top-container clearfix">
                        <div class="pull-left"><h4 class="panel-heading">Teams without Groups</h4></div>
                    </div>
                    <div id="my_teams_container">
                        @include('teams.orgteams_list')
                    </div>
                @endif
            </div>
        </div> {{-- /.col-md-12 --}}
    </div> {{-- /.col-lg-10 --}}
    @include('organization.groups.partials.create_group_modal')
@endsection
