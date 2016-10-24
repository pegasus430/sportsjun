<?php

Route::group(['prefix' => '/api/v1', 'middleware' => 'cors'], function ($router) {

    $router->get('/', function () {
        //return response()->json({'me'});
    });

    Route::post('login', 'Api\AuthApiController@login');
    Route::post('register', 'Api\AuthApiController@register');

//'jwt.refresh'
    Route::group(['middleware' => ['jwt.api.auth']], function ($router) {
        Route::post('/otp/generate', ['uses' => 'Api\AuthApiController@generateOTP']);
        Route::post('/otp/verify', ['uses' => 'Api\AuthApiController@verifyOTP']);
        Route::post('/otp/issent', ['uses' => 'Api\AuthApiController@isOtpSent']);

        #$router->resource('/users', 'Api\UserApiController');
        $router->post('users/{id?}','Api\UserApiController@update');

        $router->get('/organizations','Api\OrganizationApiController@getList');
        $router->get('/organizations/{id}','Api\OrganizationApiController@getOrganization');

        //handle teams
        $router->resource('/teams', 'Api\TeamApiController');

        //handle tournaments
        $router->get('/tournaments/{filter?}', 'Api\TournamentApiController@index')->where('filter', '[a-zA-Z]+');;
        $router->get('/tournaments/{id}', 'Api\TournamentApiController@show')->where('id', '[0-9]+');;
        $router->get('/tournaments/{id}/parent', 'Api\TournamentApiController@parent');
        $router->get('/tournaments/{id}/follow', 'Api\TournamentApiController@follow_tournament');
        $router->get('/tournaments/{id}/un_follow', 'Api\TournamentApiController@unfollow_tournament');
        $router->get('/tournaments/{id}/gallery', 'Api\TournamentApiController@gallery');
        $router->get('/tournaments/{id}/groups', 'Api\TournamentApiController@group_stage');
        $router->get('/tournaments/{id}/final', 'Api\TournamentApiController@final_stage');
        $router->get('/tournaments/{id}/player_standing', 'Api\TournamentApiController@player_standing');
        //update tournament details;
        $router->post('/tournaments/{id}/update', 'Api\TournamentApiController@update');
        $router->get('/tournaments/search', 'Api\TournamentApiController@search');

        //get sports
        $router->get('/sports', ['as' => 'get.sports', 'uses' => 'Api\SportApiController@index']);

        $router->get('/match-schedules',['uses'=>'Api\MatchSchedulesApiController@getList']);

        $router->group(['prefix'=>'sports',],function($router){
            $router->group(['prefix'=>'cricket'],function($router){
                $router->get('/statistics', ['uses' => 'Api\SportCricketApiController@cricketStatistics']);
                $router->get('/player-match-score', ['uses' => 'Api\SportCricketApiController@cricketPlayerMatchScore']);
                $router->get('/overwise-score', ['uses' => 'Api\SportCricketApiController@cricketOverwiseScore']);
                $router->post('/overwise-score/{id?}', ['uses' => 'Api\SportCricketApiController@setCricketOverwiseScore']);
            });
        });



        //user interaction
        $router->get('/send_feedback', 'Api\FunctionsApiController@sendFeedback');
        $router->get('/search', 'Api\FunctionsApiController@search');

        $router->get('logout', 'Api\AuthApiController@logout');

    });
});


