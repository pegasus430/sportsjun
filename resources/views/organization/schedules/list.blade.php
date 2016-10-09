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
                <div class="sportsjun-datafilter">
                    <form method="GET">
                        <div class="form-group">
                            <label>Find events in your club</label>
                            <div class="input-group">
                                <input class="form-control" name="filter-event" value="{{ $filter_event }}"/>
                                <span class="input-group-btn"><button class="btn btn-tiny btn-primary "
                                                                      type="button">Find</button></span>
                            </div>
                        </div>
                    </form>
                </div>
                @if($tournaments->count())

                    <div>
                        @include('organization.schedules.partials.schedule_list')
                    </div>
                @else
                    <div id="schedule_empty text-left">
                        <p>No Records</p>
                    </div>
                @endif

            </div> {{-- /.panel --}}
        </div> {{-- /.col-md-12 --}}
    </div> {{-- /.col-lg-10 --}}
    @include('organization.groups.partials.create_group_modal')
@endsection
