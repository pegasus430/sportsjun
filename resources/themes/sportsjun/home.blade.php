@extends('home.layout')

@section('content')
    @include('home.section.slider')
    @include('home.section.info')
    <div id="tournaments" style="position:relative;top:-150px;"></div>

    @include('home.section.tournaments')

    {{-- @include('home.section.app') --}}
    <div id="marketplace" style="position:relative;top:-150px;"></div>

    {{-- @include('home.section.market_place')--}}
    @include('home.section.testimonials')
    @include('home.section.our_clients')
@endsection