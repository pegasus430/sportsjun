<?php

Route::group(['prefix' => '/api/v1', 'middleware' => 'cors'], function ($router) {

    Route::post('register', 'Api\AuthApiController@register');
    Route::post('login', 'Api\AuthApiController@login');

    Route::group(['middleware'=> ['jwt.api.auth'] , 'namespace'=>'Api','alias'=>'api'],function ($router){
        $router->get('/users','UserApiController@index');
        $router->get('/users/{id}','UserApiController@show');
        $router->post('/users/{id?}','UserApiController@update');

        $router->get('/cities','DataApiController@cities');
        $router->get('/countries','DataApiController@countries');
        $router->get('/states','DataApiController@states');


    });

//'jwt.refresh'
    Route::group(['middleware' => ['jwt.api.auth']], function ($router) {
        Route::post('/otp/generate', ['uses' => 'Api\AuthApiController@generateOTP']);
        Route::post('/otp/verify', ['uses' => 'Api\AuthApiController@verifyOTP']);
        Route::post('/otp/issent', ['uses' => 'Api\AuthApiController@isOtpSent']);








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


