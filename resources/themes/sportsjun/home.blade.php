@extends('home.layout')

@section('content')
    @include('home.section.slider')
    @include('home.section.info')
    @include('home.section.tournaments')
    @include('home.section.app')
    @include('home.section.market_place')
    @include('home.section.testimonials')
    @include('home.section.our_clients')
@endsection