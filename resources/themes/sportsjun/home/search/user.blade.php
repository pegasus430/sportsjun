@extends('home.layout')

@section('content')
    <div class="profileWrapp">
        <div class="container">
            <h1>MY PROFILE</h1>
            <div class="profileDetaiWrap">
                <div class="profile_flex">
                    <div class="followingBox">
                        <h2>Following</h2>
                        <h3>{{ $user->following }}</h3>
                    </div>
                    <div class="profiledetailWrapp">
                        <div class="profileBox"><img src="{{ $user->logoImage }}"></div>
                        <h2>{{ $user->name }}</h2>
                        <h4>{{$user->city}}, {{ $user->state }}, {{ $user->country }}</h4>
                    </div>
                    <div class="followingBox">
                        <h2>Followers</h2>
                        <h3>{{ $user->followers->count() }}</h3>
                    </div>
                </div>
                <div class="playlist">
                    @foreach ($user->sportList as $sport)
                        <div class="playBox">{{ $sport->sports_name }}</div>
                    @endforeach
                </div>
            </div>
        </div>
        @foreach ($user->sportList as $sport)
            @if (View::exists('home.search.user.'.strtolower($sport->sports_name)))
                @include('home.search.user.'.strtolower($sport->sports_name))
            @endif
        @endforeach
    </div>
@endsection