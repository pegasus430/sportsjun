<?php
$slides = [
        [
                'img' => '/themes/sportsjun/images/slider-old.jpg',
                'data' => [
                        'h1' => 'Multi sports data management system',
                        'h2' => 'Your\'s Sports Profile. Your team. Your sports organization. Your Sports Events',
                        'h3' => 'One product. Everything you need.'
                ]
        ],
        [
                'img' => '/themes/sportsjun/images/slider.jpg',
                'data' => [
                        'h1' => 'MARKET PLACE',
                        'h2' => 'Athelets Buy and Sell your Sporting Equipmenr',
                        'h3' => 'One product. Everything you need.'
                ],
        ],
        [
                'img' => '/themes/sportsjun/images/slider1.jpg',
                'data' => [
                        'h1' => 'Tournament / Leagues management',
                        'h2' => '<i class="fa fa-circle" aria-hidden="true"></i>Organize and Manage event\'s Digitally <i
    class="fa fa-circle" aria-hidden="true"></i>online Registration(Team/Player)<i
    class="fa fa-circle" aria-hidden="true"></i> Automatic events Schedule<i
    class="fa fa-circle"
    aria-hidden="true"></i>',
                        'h3' => 'One product. Everything you need.'
                ],
        ],


];
?>


<div class="bgSlider mainwarppslide">
    @foreach ($slides as $slide)
        <div class="slideBox">
            <img src="{{array_get($slide,'img')}}">
            <div class="slidcontentholder">
                <h1>{!! array_get($slide,'data.h1') !!}</h1>
                <h2>{!! array_get($slide,'data.h2') !!}</h2>
                <h3>{!! array_get($slide,'data.h3') !!}</h3>
                <div class="btBox">
                    <a href="#" data-toggle="modal"
                       data-target="#home-login-modal" class="loginL">LOGIN</a>
                    <a href="#" data-toggle="modal" data-target="#myModal" class="registerR">GET STARTED</a>
                </div>
            </div>
        </div>
    @endforeach
</div>