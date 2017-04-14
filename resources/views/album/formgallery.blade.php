@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
    @if($action=='tournaments')
        @include ('tournaments._leftmenu')
    @elseif($action=='facility')
        @include ('facility._leftmenu')
    @elseif($action=='team')
        @include ('teams._leftmenu')
    @elseif($action=='match')
        @include ('album._match_leftmenu')
    @elseif($action=='organization')
        @if(!Helper::check_if_org_template_enabled())
            @include ('teams.orgleftmenu')
        @endif
    @else
        @include ('album._leftmenu')
    @endif




@if(Helper::check_if_org_template_enabled())
        <div class="col-md-2">
        </div>
        @endif

    <div id="content" class="col-sm-10">


        <?php
        $data_url='';
        $data_text='';
        $data_title='';
        $data_image=!empty($left_menu_data['path'])?$left_menu_data['path']:'';
        ?>

        <div class="row">
            <div class='col-sm-6 col-sm-offset-6'>
                <br>
                <div class="ssk-group col-md-10 col-md-offset-1 " data-url="{{$data_url}}" data-text="{{$data_text}}" data-title="{{$data_title}}" data-image={{$data_image}}>

                    <a class="ssk ssk-facebook f_b " href="#" ><i class="fa fa-facebook"></i>Share</a>

                    <a class="ssk ssk-twitter tw_r" href="#" ><i class="fa fa-twitter"></i>Tweet</a>

                    <a class="ssk ssk-google-plus gp_l" href="#" ><i class="fa fa-google-plus"></i>Share</a>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div id="marketplaceid" class="gallery-pg clear_both">
            <br>
            <div class="stage_head">Form Gallery</div>
            <div id="galleryimages">
                <ul class="row list-unstyled market_gallery clearfix">
                    @foreach($photo_array as $album)

                        <?php //echo $album['id'];exit; ?>
                        <li class="col-xs-6 col-sm-4 col-md-3 remove_li_{{$album['id']}}" >
                            <div class="thumbnail">
                                <div class="ImageWrapper" id="id_{{$album['id']}}">

                                    @if($album['imageable_type']=='gallery_user' || $album['imageable_type']=='gallery_team' || $album['imageable_type']=='form_gallery_tournaments' || $album['imageable_type']=='form_gallery_facility' || $album['imageable_type']=='form_gallery_organization' || $album['imageable_type']=='gallery_match')
                                    @if($action=='tournaments')

                                    <a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  class="view_gallery_album">{!! Helper::Images($album['url'],'form_gallery_tournaments',array('height'=>200,'width'=>200,'id'=>$action_id ) )
                                        !!}</a>
                                    @endif
                                    @if($action=='organization')

                                    <a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  class="view_gallery_album">{!! Helper::Images($album['url'],'form_gallery_organization',array('height'=>200,'width'=>200,'id'=>$action_id ) )
                                        !!}</a>

                                    @endif
                                    <div class="actions">

                                        <ul>
                                            <li>
                                                <a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  data-toggle="tooltip" data-placement="top" class="view_gallery_album" title="View Gallery" ><i class="fa fa-info-circle"></i></a>
                                            </li>
                                            @if($action=='tournaments')
                                            @if(count($tournamentformcreate)!=0 )

                                            <li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>

                                            @endif
                                            @endif
                                            @if($action=='organization')
                                            @if($action=='organization'  && count($orgformcreate )!=0)
                                            <li><a href="javascript:void(0);" class="removeAlbumPhoto"  data-pid="{{ $album['id'] }}"   data-page="{{ 'viewalbum' }}" data-toggle="tooltip" data-placement="top" title="Delete" ><i class="fa fa-remove"></i></a></li>
                                            @endif
                                            @endif
                                        </ul>
                                    </div>

                                    @else

                                    <!--<img alt="{{$album['title']}}" src="{{ url('/uploads/user_profile/'.$album['url']) }}">-->
                                    {!! Helper::Images($album['url'],'user_profile',array('height'=>200,'width'=>200) )
                                    !!}

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
                                    @if($album['imageable_type']=='gallery_user' || $album['imageable_type']=='gallery_team' || $album['imageable_type']=='form_gallery_tournaments' || $album['imageable_type']=='gallery_facility' || $album['imageable_type']=='form_gallery_organization' || $album['imageable_type']=='gallery_match')


                                    @if($action=='tournaments')
                                    <a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  class="view_gallery_album">{!! Helper::Images($album['url'],'form_gallery_tournaments',array('class'=>'img-center','height'=>500,'width'=>500,'id'=>$action_id ) )!!}
                                        @endif


                                        @if($action=='organization')
                                        <a href="javascript:void(0);"  data-pid="{{ $album['id'] }}"  class="view_gallery_album">{!! Helper::Images($album['url'],'form_gallery_organization',array('class'=>'img-center','height'=>500,'width'=>500,'id'=>$action_id ) )!!}
                                            @endif



                                </div>
                                @endif
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


    @endsection
