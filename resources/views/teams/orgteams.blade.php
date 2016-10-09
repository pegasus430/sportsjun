 @extends('layouts.app')
 @section('content') 
 @include ('teams.orgleftmenu')
<div id="content" class="col-sm-10" style="height: 986px;">
    <div class="col-sm-9 tournament_profile">
        @if(count($teams))
            <div class="group_no clearfix">
                <h4 class="stage_head pull-left">Teams</h4>
                @can('createTeam', $organization)
                    <div class = "pull-right panel-right-btn">
                        <a href="/team/create?organization_id={{ $id }}"
                           class = "btn btn-primary"
                        >
                            Create New Team
                        </a>
                    </div> {{-- /.panel-right-btn--}}
                @endcan
            </div>
            @include('teams.orgteams_list')
        @else
            <div class="sj-alert sj-alert-info sj-alert-sm">No teams found. Please add team(s) to your organization.</div>
        @endif
    </div>
</div>
@endsection