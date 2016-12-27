<?php

Route::group(['prefix' => '/api/v1', 'middleware' => 'cors'], function ($router) {

    Route::post('register', 'Api\AuthApiController@register');
    Route::post('login', 'Api\AuthApiController@login');

    Route::get('/cities','Api\DataApiController@cities');
    Route::get('/countries','Api\DataApiController@countries');
    Route::get('/states','Api\DataApiController@states');

    Route::group(['middleware'=> ['jwt.api.auth'] , 'namespace'=>'Api','alias'=>'api'],function ($router){
        $router->get('logout', 'AuthApiController@logout');


        $router->get('/organizations','OrganizationApiController@index');
        $router->get('/organizations/{id}','OrganizationApiController@show');

        $router->get('/sports', ['as' => 'sports', 'uses' => 'SportApiController@index']);

        $router->get('/teams', 'TeamApiController@index');
        $router->get('/teams/{id}', 'TeamApiController@show');

        $router->get('/tournaments/{filter?}', 'TournamentApiController@index')->where('filter', '[a-zA-Z]+');
        $router->get('/tournaments/{id}', 'TournamentApiController@show')->where('id', '[0-9]+');
        $router->get('/tournaments/{id}/parent', 'TournamentApiController@parent');
        $router->get('/tournaments/{id}/groups', 'TournamentApiController@group_stage');
        $router->get('/tournaments/{id}/groups_matches', 'TournamentApiController@group_stage_matches');
        $router->get('/tournaments/{id}/final', 'TournamentApiController@final_stage');
        $router->get('/tournaments/{id}/brackets', 'TournamentApiController@brackets');
        $router->get('/tournaments/{id}/final_matches', 'TournamentApiController@final_stage_matches');
        $router->get('/tournaments/{id}/player_standing', 'TournamentApiController@player_standing');
        $router->get('/tournaments/{id}/follow', 'TournamentApiController@follow_tournament');
        $router->get('/tournaments/{id}/un_follow', 'TournamentApiController@unfollow_tournament');
        $router->get('/tournaments/{id}/gallery', 'TournamentApiController@gallery');


        $router->get('/users','UserApiController@index');
        $router->get('/users/{id}','UserApiController@show');
        $router->get('/users/{id}/sports','UserApiController@sports');
        $router->post('/users/{id?}','UserApiController@update');
        $router->post('/users/{id}/sports','UserApiController@updateSports');

        $router->get('/match-schedules/{id}',['uses'=>'MatchSchedulesApiController@getInfo']);
        $router->get('/match-schedules',['uses'=>'MatchSchedulesApiController@getList']);



        $router->post('/scores/cricket','ScoreApiController@setScoreCricket');

    });

//'jwt.refresh'
    Route::group(['middleware' => ['jwt.api.auth']], function ($router) {
        Route::post('/otp/generate', ['uses' => 'Api\AuthApiController@generateOTP']);
        Route::post('/otp/verify', ['uses' => 'Api\AuthApiController@verifyOTP']);
        Route::post('/otp/issent', ['uses' => 'Api\AuthApiController@isOtpSent']);



        //handle teams


        //handle tournaments






        //update tournament details;
        $router->post('/tournaments/{id}/update', 'Api\TournamentApiController@update');
        $router->get('/tournaments/search', 'Api\TournamentApiController@search');

        //get sports


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



    });

    Route::any( '{catchall}', 'Api\BaseApiController@routeNotFound')->where('catchall', '(.*)');
});


