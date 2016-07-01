@extends('layouts.app')
@section('content')
    @include ('teams.orgleftmenu')
    <div class = "col-lg-10 col-md-10 col-sm-12 groups">
        <div class = "col-md-12">
            <div class = "panel">
                <div class = "panel-top-container clearfix">
                    <div class = "pull-left"><h4 class = "panel-heading">Groups</h4></div>
                    @can('createGroup', $organization)
                        <div class = "pull-right panel-right-btn">
                            <button type = "button"
                                    class = "btn btn-primary"
                                    data-toggle = "modal"
                                    data-target = "#create_group">Create New Group
                            </button>
                        </div> {{-- /.panel-right-btn--}}
                    @endcan
                </div> {{-- /.panel-top-container --}}
                @if (session('status'))
                    <div class = "alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if($groups->count())
                    <div id = "my_groups_container">
                        @include('organization.groups.partials.group_list')
                    </div> {{-- /#my_groups_container --}}
                @else
                    <div id = "groups_empty">
                        @can('createGroup', $organization)
                            <div id = "groups_empty_info">You have not created any groups yet</div>
                            <div id = "create_first_group">
                                <button type = "button"
                                        class = "btn btn-primary"
                                        data-toggle = "modal"
                                        data-target = "#create_group">Create Your First Group
                                </button>
                            </div>
                        @else
                            <div id = "groups_empty_info">There are no groups yet.</div>
                        @endcan
                    </div>
                @endif

            </div> {{-- /.panel --}}
        </div> {{-- /.col-md-12 --}}
    </div> {{-- /.col-lg-10 --}}
    @include('organization.groups.partials.create_group_modal')
@endsection
