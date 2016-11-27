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
            <div class="SportPlayerWrap">
                <div class="leftBG col-lg-2 col-md-2 col-sm-12 cricketbg">
                    <div class="hedcric">
                        <img src="images/ico_cric.png">
                        <h3>{{$sport->sport_name}}</h3>
                    </div>
                </div>
                <?php
                $skillset = array_get($user->skillSet, $sport->id)->lists('answers.0.options.options', 'sports_question');
                ?>

                <div class="spoertSkillLeft col-lg-3 col-md-3 col-sm-12">
                    <h2>Sport Skill</h2>
                    @foreach ($skillset as $key=>$value)
                        <div class="skill_listing">
                            <div class="pleft"><h3>{{$key}}</h3></div>
                            <div class="pright"><h4>{{$value}}</h4></div>
                        </div>
                    @endforeach
                </div>

                <div class="spoertRight col-lg-7 col-md-7 col-sm-12">
                    <h2>Player Sport Status</h2>
                    <div class="user-stats">
                        <?php
                            $stats_view = 'sportprofile.' . strtolower($sport->sports_name) . 'statsview';
                        ?>
                        @if (View::exists($stats_view))
                            @include($stats_view,['sportsPlayerStatistics'=> $user->sportStats($sport->id)])
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection