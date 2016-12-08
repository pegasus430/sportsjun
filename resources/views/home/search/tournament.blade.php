@extends('home.layout')

@section('content')
    <div class="clearfix"></div>

    <?php
    $start_date = Carbon\Carbon::createFromFormat("Y-m-d", $tournament->start_date);
    $end_date = Carbon\Carbon::createFromFormat("Y-m-d", $tournament->start_date);
    ?>
    <div class="tourn_topBanar_wrapp">
        <div class="banarImg">
            <img
                    src="{{Helper::ImageFit($tournament->logoImageReal,1382,364,'center')}}">
        </div>
        <div class="registerTopWrap">
            <div class="col-lg-6 col-md-6 topLeftTT">
                <h1>{{ $tournament->name }}</h1>
                <h2>{{ $start_date->format('l, jS M Y') }}
                    @if ($end_date)
                        to {{ $end_date->format('l, jS M Y') }}
                    @endif
                </h2>
                <h3>{{ $tournament->location}}</h3>
            </div>
            <div class="col-lg-2 col-md-2 desk_flot_right">
                @if ($start_date > Carbon\Carbon::today())
                    <a href="" class="regNew">REGISTER</a>
                @endif
            </div>
        </div>
    </div>

    <?php
    $details = [
            'Tournament name' => $tournament->name,
            'Sports name' => $tournament->parent_name,
            'Sports' => $tournament->sport->sports_name,
            'Tournament Type' => $tournament->type,
            'Player Type' => $tournament->player_type,
            'Start-End Dates' => $start_date->format('d/m/Y') . ' to ' . $end_date->format('d/m/Y'),
            'Number of groups' => $tournament->groups_number,
            'Number of Teams in a Group' => $tournament->groups_teams
    ]
    ?>
    <ul class="nav nav-tabs search-tournament-tabs">
        <li class="active"><a data-toggle="tab" href="#tournament_info">Details</a></li>
        <li><a data-toggle="tab" href="#tournament_group_stage">Group Stage</a></li>
        <li><a data-toggle="tab" href="#tournament_group_matches">Matches</a></li>
    </ul>
    <div class="tab-content">
        <div id="tournament_info" class="tab-pane fade in active">
            @include('home.search.tournament.info')
        </div>
        <div id="tournament_group_stage" class="tab-pane fade">
            @include('home.search.tournament.group_stage')
        </div>
        <div id="tournament_group_matches" class="tab-pane fade">
            @include('home.search.tournament.matches')
        </div>
    </div>

@endsection