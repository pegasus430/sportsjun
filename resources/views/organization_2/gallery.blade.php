@extends('layouts.organisation')


@section('styles')
	<link rel="stylesheet" type="text/css" href="/org/css/gallery.css">
@stop
@section('content')


    <!-- Body Section -->

   
         <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Gallery</h2></div>
                <div class="col-md-12">
                    <div align="center">

                        <button class="btn btn-default filter-button" data-filter="all">All</button>

                        @foreach($album_array as $album)
                        <button class="btn btn-default filter-button" data-filter="{{$album->id}}">Tennis</button>
                       	@endforeach
                    </div>
                    <br/>

                    @foreach($photos as $photo)
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter {{$photo->album_id}}"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                    @endforeach
                    <!-- Just to display, will work on it later -->
                     <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter {{$photo->album_id}}"> <img src="http://fakeimg.pl/365x365/" class="img-responsive"> </div>
                </div>
            </div>
        </div>

        

@stop