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