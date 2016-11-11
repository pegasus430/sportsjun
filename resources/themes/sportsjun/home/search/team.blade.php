@extends('home.layout')

@section('content')
    <div class="profileWrapp">
        <div class="container">
            <h1>Teams</h1>
            <div class="profileDetaiWrap">
                <div class="profile_flex">
                    <div class="profiledetailWrapp">
                        <div class="profileBox"><img src="{{ $team->logoImage }}"></div>
                        <h2>{{ $team->name }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection