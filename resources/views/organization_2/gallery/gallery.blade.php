@extends('layouts.organisation')


@section('styles')
	<link rel="stylesheet" type="text/css" href="/org/css/gallery.css">
@stop
@section('content')


    <!-- Body Section -->

   
         <div class="container">        
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Gallery</h2>

                    <div class="create-btn-link"> <a href="" class="wg-cnlink" data-toggle="modal" data-target="#new_album" style="margin-right: 150px;">New Album</a> <a href="" class="wg-cnlink" data-toggle="modal" data-target="#new_photo">Add Photo</a></div>

                    </div>
                <div class="col-md-12">
                    <div align="center">

                        <button class="btn btn-default filter-button" data-filter="all">All</button>

                        @foreach($album_array as $album)
                        <button class="btn btn-default filter-button" data-filter="fil-{{$album->id}}">{{$album->title}}</button>
                       	@endforeach
                    </div>
                    <br/>

                    @foreach($photos as $photo)
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter fil-{{$photo->album_id}}"> <img src="/uploads/{{$photo->imageable_type}}/{{$photo->url}}" class="img-responsive"> </div>
                    @endforeach
                    <!-- Just to display, will work on it later -->
                     
                </div>
            </div>
        </div>


        @include('organization_2.gallery.new_album')
        @include('organization_2.gallery.new_photo')



@stop


@section('end_scripts')

	<script type="text/javascript">
			$('#album_form_add').submit(function(){
				 var data = $(this).serialize();
				 var url = $(this).attr('action');

				 $.ajax({
				 		url:url,
				 		data:data,
				 		type:'post',
				 		success:function(response){
				 			$('#album_select').html(response);
				 			$('#new_album').modal('hide');
				 		}
				 })

				return false; 
			});
	</script>

@stop