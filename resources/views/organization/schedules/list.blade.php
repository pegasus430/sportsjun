@extends('layouts.app')
@section('content')
    @include ('teams.orgleftmenu')
    <div class="col-lg-10 col-md-10 col-sm-12 groups">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-top-container clearfix">
                    <div class="pull-left"><h4 class="panel-heading">Schedule</h4></div>
                    <div class="pull-right panel-right-btn">
                    </div> {{-- /.panel-right-btn--}}
                </div> {{-- /.panel-top-container --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if($schedules))
                    <div id="my_schedule_container">
                        @include('organization.schedules.partials.schedule_list')
                    </div> {{-- /#my_groups_container --}}
                @else
                    <div id="schedule_empty text-left">

                    </div>
                @endif

            </div> {{-- /.panel --}}
        </div> {{-- /.col-md-12 --}}
    </div> {{-- /.col-lg-10 --}}
    @include('organization.groups.partials.create_group_modal')
@endsection
