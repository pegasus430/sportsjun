@extends('home.layout')

@section('content')
    @include('home.section.slider')
    <!--// Main Content //-->
    <div class="kode-content padding-top-0">
        @include('home.partials.search')
        @include('home.section.how_it_works')
        @include('home.section.features')
        @include('home.section.tournaments')
    </div>
    <div class="kd-divider divider4"><span></span></div>
    @include('home.section.testimonials')
    @include('home.section.our_clients')

@endsection