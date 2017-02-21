@extends('layouts.organisation')

@section('content')

 <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Team (Groups)</h2>
                <div class="create-btn-link"><a href="" class="wg-cnlink" data-toggle="modal" data-target="#create_group" style="margin-right: 150px;">Create New Team Group</a> <a href="" class="wg-cnlink">List All Teams</a></div>
            </div>
        </div>
       
           
                @if($groups->count())
                    <div id="my_groups_container">
                        @include('organization.groups.partials.group_list')
                    </div> {{-- /#my_groups_container --}}
                @else
                        <div class="row">
            <div class="col-md-offset-2 col-md-8 bg-grey text-center pdtb-30">
                <div id="groups_empty">
                    <div> Team Groups for Group Competation in the Organization </div>
                    <br>
                    <h3><b>Steps to follow</b></h3>
                    <div class="text-left">
                        <br> <b>1.</b> Create Team Groups if organization want to group multi sport teams into Specific groups. (Ex. to create group competation in the organization and points need to role up from multi events.)
                        <div id="text-center text-centered">You have not created any team groups yet</div>
                        <br> <b>2. </b> Make sure to create Staff members. Staff members will need to be linked with every Team Group. (Ex: Manager, Coach, Physio etc) </div>
                </div>
            </div>
        </div>an
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
    @include('organization_2.groups.partials.create_group_modal')
@stop