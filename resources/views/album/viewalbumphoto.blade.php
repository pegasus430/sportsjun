<?php $is_widget = (isset($is_widget) && $is_widget) ? $is_widget : false; ?>
@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
	@if($action=='tournaments')
		@include ('tournaments._leftmenu')
	@elseif($action=='facility')
		@include ('facility._leftmenu')
	@elseif($action=='organization')
		@include ('teams.orgleftmenu')
	@elseif($action=='team')
		@include ('teams._leftmenu')
	@elseif($action=='match')
		@include ('album._match_leftmenu')
	@else
		@include ('album._leftmenu')
	@endif

	<div id="UploadalbumImage" class="modal fade">
		<div class="modal-dialog sj_modal sportsjun-forms">
			<div class="modal-content">
				<div  class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Upload Images
					</h4>
				</div>
				<div class="modal-body">
					<div class="sportsjun-forms sportsjun-container wrap-2 sportsjun-forms-modal">
						{!! Form::open(array('class'=>'form-horizontal','method' => 'post', 'files' => true,'id'=>'formgallery')) !!}


						<div class="col-sm-12">
							<label class="form_label label_modal_new">Upload Images</label>
							<div >
								@include ('common.upload')
								@include ('common.uploadfield', ['uploadLimit' => 'null','field'=>'gallery'])
								<p style="color:#a94442;" class="help-block" id="filelist_galleryeResponse"></p>
							</div>
						</div>

						<input type="hidden" name='album_id' value='<?php echo $album_id;?>' id='album_id'>
						<input type="hidden" name='action' value='<?php echo $action;?>' id='action'>
						<input type="hidden" name='$action_id' value='<?php echo $action_id;?>' id='action_id'>
						{!! Form::close() !!}
					</div>
				</div>
				<div class="modal-footer">

					<button type="button" id="saveGallery" class="button btn-primary" onclick="saveGallery();">Save</button>
					<button type="button" id="saveGallery" class="button btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>




	<div id="content" class="col-sm-10">

		@include('album.share')
		@if (!(isset($is_widget) && $is_widget))
		@if($action=='team' )
		@if(isset($uploadimage)&& count($uploadimage))
		<a href="#" class="launch-modal uploadimgages add-tour" data-toggle="modal" data-target=".bs-example-modal-sm" style="width: 300px; margin-top: 15px;"><i class="fa fa-plus"></i> Upload Images </a>

		@endif
		@endif

		@if($action=='match' )
		@if((isset($upload)&& count($upload))|| $flag=='yes')
		<a href="#" class="launch-modal uploadimgages add-tour" data-toggle="modal" data-target=".bs-example-modal-sm" style="width: 300px; margin-top: 15px;"><i class="fa fa-plus"></i> Upload Images </a>
		@endif
		@endif

		@if($action=='tournaments')
		@if($result=='1')
		<a href="#" class="launch-modal  uploadimgages add-tour" data-toggle="modal" data-target=".bs-example-modal-sm" style="width: 300px; margin-top: 15px;"><i class="fa fa-plus"></i> Upload Images </a>
		@endif
		@endif

		@if($action=='user' && $action_id== $loginid)
		<a href="#" class="launch-modal uploadimgages add-tour" data-toggle="modal" data-target=".bs-example-modal-sm" style="width: 300px; margin-top: 15px;"><i class="fa fa-plus"></i> Upload Images </a>

		@endif
		@if($action=='organization')
		@if(isset($orgphotocreate)&& count($orgphotocreate))
		<a href="#" class="launch-modal uploadimgages add-tour" data-toggle="modal" data-target=".bs-example-modal-sm" style="width: 300px; margin-top: 15px;"><i class="fa fa-plus"></i> Upload Images </a>
		@endif
		@endif

		@endif
		@if (session('status'))
		<div class="alert alert-success">
			{{ session('status') }}
		</div>
		@endif

		<?php //echo "<pre>";print_r($photo_array );exit;?>

		<div id="marketplaceid" class="gallery-pg clear_both">
			<br>
			<div class="stage_head">Media Gallery</div>
			<div id="galleryimages">
				<ul class="row list-unstyled market_gallery clearfix">
					@foreach($photo_array as $album)
					<?php //echo $album['id'];exit; ?>
					<li class="col-xs-6 col-sm-4 col-md-3 remove_li_{{$album['id']}}" >
						<div class="thumbnail">
							<div class="ImageWrapper" id="id_{{$album['id']}}">
								@if($album['imageable_type']=='gallery_user' || $album['imageable_type']=='gallery_team' || $album['imageable_type']=='gallery_tournaments' || $album['imageable_type']=='gallery_facility' || $album['imageable_type']=='gallery_organization' || $album['imageable_type']=='gallery_match')

								<!--<img alt="{{$album['title']}}" src="{{  url('/uploads/gallery/'.$album['imageable_type'].'/'.$action_id.'/'.$album['url']) }}">-->
								<a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  class="view_gallery_album">{!! Helper::Images($album['url'],'gallery/'.$album['imageable_type'],array('height'=>200,'width'=>200,'id'=>$action_id ) )
									!!}</a>
								<div class="actions">
									<!-- <p><a href="javascript:void(0);" class="view_gallery_album" data-pid="{{$album['id']}}">VIEW GALLERY</a></p>-->
									<ul>
										<li><a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  data-toggle="tooltip" data-placement="top" class="view_gallery_album" title="View Gallery" ><i class="fa fa-info-circle"></i></a></li>
										<li class='dropup'><a title='Share'  href="javascript:void(0);" data-toggle='dropdown' ><i class="fa fa-share-alt"></i></a>
											@include('album.share_single_photo')
										</li>

										<li><a data-pid="{{$album['id']}}"><span>Likes</span>:&nbsp;<label id="likecountid_{{$album['id']}}">{{ $album['like_count'] }}</label></a></li>

										<li>


											<div class="" id="main_{{$album['id']}}">
												<?php $a = explode(',',$album['likes']);?>
												@if(!in_array($userId,$a) )

													<a href="javascript:void(0);" class="likecount green" data-pid="{{$album['id']}}"><i class="fa fa-thumbs-up"></i></a> <!--{{ $album['like_count'] }} --></a>
												@else
													<a href="javascript:void(0);" class="Dislike black" data-pid="{{$album['id']}}" >
														<i class="fa fa-thumbs-down"></i></a> <!--{{ $album['like_count'] }} --></a>

												@endif
											</div>


											<div class="" id="like_{{$album['id']}}" style="display:none">

												<a href="javascript:void(0);"  class="likecount green" data-pid="{{$album['id']}}"><i class="fa fa-thumbs-up"></i> </a>

											<!--  <div  id="ab_{{$album['id']}}"> 0 </div>-->

											</div>
											<div class="" id="dislike_{{$album['id']}}" style="display:none">
												<a   href="javascript:void(0);" class="Dislike black" data-pid="{{$album['id']}}" ><i class="fa fa-thumbs-down"></i></a>
											<!--  <div  id="abc_{{$album['id']}}"> 0 </div>-->

											</div>
										</li>
										@if($action=='team')
											@if($album['accessflag']=='yes')
												<li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>

											@endif
										@endif

										@if($action=='match' )
											@if($album['accessflag']=='yes' || $flag=='yes')
												<li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>

											@endif
										@endif

										@if($action=='tournaments')
											@if($result=='1')
												<li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
											@endif
										@endif
										@if($action=='organization')
											@if(isset($orgphotocreate)&& count($orgphotocreate))
												<li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
											@endif
										@endif


										@if($action=='user' && $action_id== $loginid)
											<li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
										@endif
									</ul>
								</div>
							@else

								<!--<img alt="{{$album['title']}}" src="{{ url('/uploads/user_profile/'.$album['url']) }}">-->
									{!! Helper::Images($album['url'],'user_profile',array('height'=>200,'width'=>200) )
                                    !!}
									<div class="actions">
									<!-- <p><a href="javascript:void(0);" class="view_gallery_album" data-pid="{{$album['id']}}">VIEW GALLERY</a></p>-->
										<ul>
											<li>
												<a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  data-toggle="tooltip" data-placement="top" class="view_gallery_album"title="View Gallery" ><i class="fa fa-info-circle"></i></a></li>


											@if( $user_id== $loginid)
												<li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'userprofile' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
											@endif


											<?php  /* @if($action=='team')
											@if($album['accessflag']=='yes')
											<li>
											<a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
											@endif
											@endif
											@if($action=='tournaments')
											@if($result=='1')
											<li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
											@endif
											@endif
											@if($action=='user' && $action_id== $loginid)
											<li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
											@endif */?>
										</ul>
									</div>
								@endif

							</div>
						</div>
						<div class="caption">
							<span class="lead">{{$album['title']}}</span>
							<br />
							<span class="lt-grey">{{date(config('constants.DATE_FORMAT.VALIDATION_DATE_FORMAT'), strtotime($album['created_at']))}}</span>
						</div>
					</li>
					@endforeach
				</ul>
			</div>



		</div>
		<div aria-hidden="true" style="display: none;" class="modal" id="modal-gallery" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content sportsjun-forms">
					<div class="modal-header">
						<button class="close" type="button" data-dismiss="modal">×</button>
						<!--    <h3 class="modal-title">Image 11</h3>-->
					</div>
					<div class="modal-body">
						<div id="modal-carousel" class="carousel slide">
							<div class="carousel-inner">
								@foreach($photo_array as $key=>$album)
									<div class="item" id="image-{{$album['id']}}">
										@if($album['imageable_type']=='gallery_user' || $album['imageable_type']=='gallery_team' || $album['imageable_type']=='gallery_tournaments' || $album['imageable_type']=='gallery_facility' || $album['imageable_type']=='gallery_organization' || $album['imageable_type']=='gallery_match')
											{!! Helper::Images($album['url'],'gallery/'.$album['imageable_type'],array('class'=>'img-center','height'=>500,'width'=>500,'id'=>$action_id ) )!!}
										@else
											{!! Helper::Images($album['url'],'user_profile',array('class'=>'img-thumbnail','height'=>500,'width'=>500) )!!}
										@endif
									</div>
								@endforeach
							</div>
							<a class="carousel-control left" href="#modal-carousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
							<a class="carousel-control right" href="#modal-carousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
						</div>
					</div>
					<div class="modal-footer">
						<button class="button btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>


	</div>
	<script type="text/javascript">

		$(document).ready(function(){


			$('.uploadimgages').click(function(){
				$("#loader").remove();
				$('#saveGallery').show();
				$("#filelist_galleryeResponse").html("");
				$('#filelist_gallery').val("");
				$(".jFiler-item").remove();
				$('#UploadalbumImage').modal({
					backdrop: 'static'
				});
			});

		});
		function saveGallery()
		{
			var album_id=	$( "#album_id" ).val();
			var action=	$( "#action" ).val();
			var id=	$( "#action_id" ).val();
			var filelist_gallery=$( "#filelist_gallery" ).val();
			var b = "{{csrf_token()}}";
			$("#filelist_galleryeResponse").html("");

			$("#saveGallery").before("<div id='loader'></div>");
			$("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
			$('#saveGallery').hide();


			$.ajax({
				url:  base_url+'/user/album/albumphotocreate',
				type: 'POST',
				data: {album_id:album_id,action:action,id:id,filelist_gallery:filelist_gallery,'_token': b},
				success: function(data) {


					var id=data.id;
					var user_id=data.user_id;
					var action=	data.action;
					var action_id=	data.action_id;
					var page='ajax';
					var insertedphotoids =	data.insertedphotoids ;

					var urls =	 base_url+'/galleryajax/'+album_id +'/'+user_id+'/0/'+action+'/'+action_id+'/'+page;
					if(data.msg=="success")
					{

						// $("#saveGallery").before("<div id='loader'></div>");
						// $("#loader").html("<img src="+base_url+"/images/loaderwhite_21X21.gif>");
						// $('#saveGallery').hide();

						$("#loader").remove();

						$.ajax({
							type:"GET",
							//  dataType: 'JSON',
							data: { insertedphotoids:insertedphotoids},
							url:urls ,
							success: function(data) {


								if(data.msg=="success")
								{

									// alert("success");
									$('#UploadalbumImage').modal('hide');
									$('.market_gallery').append(data.html);
									$('.carousel-inner').append(data.viewhtml);
									$.alert({
										title: "Alert!",
										content: "Images uploaded successfully."
									});

								}


							}
						});




					}
					else{
						$("#loader").remove();
						$('#saveGallery').show();
						$.each(data.msg, function(key, value){
							if(key=='filelist_gallery')
								$("#filelist_galleryeResponse").append(value);

						});
					}

				}
			});

		}

	</script>

@endsection
