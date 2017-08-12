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
        'Sports name' => $tournament->sport->sports_name,
        'Tournament Type' => $tournament->type,
        'Player Type' => $tournament->player_type,
        'Start-End Dates' => $start_date->format('d/m/Y') . ' to ' . $end_date->format('d/m/Y'),
        'Number of groups' => $tournament->groups_number,
        'Number of Teams in a Group' => $tournament->groups_teams
    ]
    ?>
    <ul class="nav nav-tabs search-tournament-tabs hidden-xs">
        <li class="active"><a data-toggle="tab" href="#tournament_info">Details</a></li>
        @if($tournament->type=='league' || $tournament->type=='multistage' || $tournament->type=='doublemultistage' )
            <li><a data-toggle="tab" href="#tournament_group_stage">Group Stage</a></li>
        @endif
        @if($tournament->type=='knockout' || $tournament->type=='multistage' || $tournament->type=='doubleknockout' || $tournament->type=='doublemultistage')
            <li><a data-toggle="tab" href="#tournament_final_stage">Final Stage</a></li>
        @endif
        <li><a data-toggle="tab" href="#tournament_group_matches">Matches</a></li>
        <li><a data-toggle="tab" href="#tournament_gallery">Gallery</a></li>
        <li><a data-toggle="tab" href="#player_standing">Player Standing</a></li>
    </ul>
    <div class="nav nav-tabs search-tournament-tabs hidden-sm hidden-md hidden-lg">
        <li class="active">
            <select id="select_tournament_tabs"
                    onchange="return tournamentTabSelect(this)">
                <option value="#tournament_info" selected>Details</option>
                @if($tournament->type=='league' || $tournament->type=='multistage' || $tournament->type=='doublemultistage' )
                    <option value="#tournament_group_stage">Group Stage</option>
                @endif
                @if($tournament->type=='knockout' || $tournament->type=='multistage' || $tournament->type=='doubleknockout' || $tournament->type=='doublemultistage')
                    <option value="#tournament_final_stage">Final Stage</option>
                @endif
                <option value="#tournament_group_matches">Matches</option>
                <option value="#tournament_gallery">Gallery</option>
                <?php
                    $player_standing = $tournament->playerStanding();
                ?>
                @if ($player_standing && count($player_standing)):
                <option value="#player_standing">Player Standing</option>
                @endif
            </select>
        </li>
    </div>
    <div class="tab-content">
        <div id="tournament_info" class="tab-pane fade in active">
            @include('home.search.tournament.info')
        </div>
        <div id="tournament_group_stage" class="tab-pane fade">
            @include('home.search.tournament.group_stage')
        </div>

        <div id="tournament_final_stage" class="tab-pane fade">
            @include('home.search.tournament.final_stage')
        </div>
        <div id="tournament_group_matches" class="tab-pane fade">
            @include('home.search.tournament.matches')
        </div>
        <div id="tournament_gallery" class="tab-pane fade">
            @include('home.search.tournament.gallery')
        </div>
        <div id="player_standing" class="tab-pane fade">
            @include('home.search.tournament.player_standing')
        </div>
    </div>

    <script>
        function tournamentTabSelect(el) {
            $('ul.search-tournament-tabs a[href="' + $(':selected', el).val() + '"]').click();
        }

    </script>


@endsection