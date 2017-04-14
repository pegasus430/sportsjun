@extends('layouts.pdf')

@section('content')
    <div id="header">
        @if ($logo)
            <img src="uploads/tournaments/{{$logo}}" style="float:left;" height="80px"/>
        @endif
        <h2>{{strtoupper($tournament->name)}}
            <br/>
            <span class="small">
                         <h4><b>PLAYER STANDING</b></h4>
            </span>
        </h2>
    </div>
    <style>
        table, td, tr, th {
            border: 1px solid black;
            border-collapse: collapse
        }
        .second {
            background-color: #EFEFEF
        }

    </style>

               
            @include('tournaments.player_stats.'.$sport_name)


@stop