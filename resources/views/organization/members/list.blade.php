@extends('layouts.app')
@section('content')
    @include ('teams.orgleftmenu')
    <div class="col-lg-10 col-md-10 col-sm-12 groups">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-top-container clearfix">
                    <div class="pull-left"><h4 class="panel-heading">Players</h4></div>
                    <div class="pull-right panel-right-btn">
                    </div> {{-- /.panel-right-btn--}}
                </div> {{-- /.panel-top-container --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if($members->count())
                    <div class="sportsjun-datafilter">
                        <form method="GET">
                            <div class="form-group">
                                <label>Find people in your club</label>
                                <div class="input-group">
                                    <input class="form-control" name="filter-team" value="{{ $filter_team }}"/>
                                    <span class="input-group-btn"><button class="btn btn-tiny btn-primary "
                                                                          type="button">Find</button></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="my_players_container">
                        <table class="table sportsjun-datatable">
                            <thead class="sportsjun-datatable-head">
                            <tr>
                                <th style="width:30%">Name</th>
                                <th>Teams</th>
                                <th>Sports</th>
                                <th>Stats/Notes</th>
                            </tr>
                            </thead>
                            <tbody>
                                @include('organization.members.partials.member_list')
                            </tbody>
                        </table>
                    </div> {{-- /#my_groups_container --}}
                @else
                    <div id="players_empty text-left">

                    </div>
                @endif

            </div> {{-- /.panel --}}
        </div> {{-- /.col-md-12 --}}
    </div> {{-- /.col-lg-10 --}}
    @include('organization.groups.partials.create_group_modal')
@endsection
