@extends('home.layout')

@section('content')
    <div class="tourn_topBanar_wrapp">
        <div class="banarImg"><img src="{{ $tournament->logoImageReal }}"></div>
        <div class="registerTopWrap">
            <div class="col-lg-6 col-md-6 topLeftTT">
                <h1>{{ $tournament->name }}</h1>
                <h2>Sunday, 27th Oct 2016 to Wednesday 30 Oct 2016 </h2>
                <h3>{{ $tournament->location}}</h3>
            </div>
            <div class="col-lg-2 col-md-2 desk_flot_right">
                <a href="" class="regNew">REGISTER</a>
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
            'Start-End Dates' => '',
            'Number of groups' => $tournament->groups_number,
            'Number of Teams in a Group' =>$tournament->groups_teams
    ]
    ?>

    <div class="tournamentDetailWrapp">
        <h1>TOURNAMENT DETAILS</h1>
        <div class="col-lg-8 col-md-8 TudetailLeft">
            @foreach ($details as $key=>$value)
                <div class="tounamentNameList">
                    <div class="tourLeftRight"><h2>{{$key}}</h2></div>
                    <div class="tourLeftRight"><h3>{{$value}}</h3></div>
                </div>
            @endforeach
        </div>
        <div class="col-lg-4 col-md-4 ">
            @if (false)
                <div class="TudetailLeft regiRight">
                    <h1>Registration Fee</h1>
                    <div class="feeBox">
                        <h2>2000 <span>Rs</span></h2>
                    </div>
                    <h3>Last Date: 10-10-2016</h3>
                    <h4>INVITE FRIENDS</h4>
                    <div class="socilaWrap">
                        <a href=""><img src="images/socilal1.png"></a>
                        <a href=""><img src="images/socilal2.png"></a>
                        <a href=""><img src="images/socilal3.png"></a>
                        <a href=""><img src="images/socilal4.png"></a>
                        <a href=""><img src="images/socilal5.png"></a>
                    </div>
                </div>
            @endif
        </div>

        <div class="clear"></div>

        <div class="TudetailLeft discription">
            <h4>DESCRIPTION</h4>
            <p>{{$tournament->description}} </p>

        </div>


    </div>

@endsection