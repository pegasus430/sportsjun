<!--// Main Banner //-->
<div id="mainbanner">
    <div class="flexslider">
        <ul class="slides">
            <li>
                <img src="{{ asset('/home/extra-images/slide1.jpg') }}" alt="" />
                <div class="kode-caption">
                    <h2>Multi sports <span>Data</span> management <span>System</span></h2>
                    <div class="clearfix"></div>
                    <div id="home-btn-fb">
                        <a class="btn-continue-fb" href="{{ route('social.login', ['facebook']) }}"></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="home-slider-login-btns">
                        <a class="kode-modren-btn thbg-colortwo" href="javascript:void(0);" data-toggle="modal" data-target="#home-login-modal">Login</a>&nbsp;&nbsp;<span class="or_text">OR</span>&nbsp;&nbsp;
                        <a class="kode-modren-btn thbg-colortwo" href="javascript:void(0);" data-toggle="modal" data-target="#registerModal">Register</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
<!--// Main Banner //-->
<?php
/*


@if (count($banners))
    <div class="bgSlider mainwarppslide">
        @foreach ($banners as $slide)
            <div class="slideBox">
                <img src="{{Helper::getImagePath($slide->image,$slide->type)}}">
                <div class="slidcontentholder">
                    <h1>{!! array_get($slide->data,'h1') !!}</h1>
                    <h2>{!! array_get($slide->data,'h2') !!}</h2>
                    <h3>{!! array_get($slide->data,'h3') !!}</h3>
                    <div class="btBox">
                        <a href="#" data-toggle="modal"
                           data-target="#home-login-modal" class="loginL">LOGIN</a>
                        <a href="#" data-toggle="modal" data-target="#myModal" class="registerR">GET STARTED</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<script>

    $(document).ready(function () {


        $('.mainwarppslide').owlCarousel({
            nav: false,
            autoplay: true,
            pagination: true,
            loop: true,
            dots: true,

            responsive: {
                0: {items: 1},
                600: {items: 1},
                900: {items: 1},
                1000: {items: 1}
            }
        });
    });
</script>
*/?>