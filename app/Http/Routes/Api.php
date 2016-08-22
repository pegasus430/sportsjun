<?php

Route::group(['prefix'=>'/api/v1','middleware' => 'cors'], function($router){
    	
    	$router->get('/', function(){
    		//return response()->json({'me'});
    	});

    	Route::post('login', 'Api\AuthApiController@login');

//'jwt.refresh'
    	Route::group(['middleware' => ['jwt.auth']], function($router) {	
		    	$router->resource('/users',                 'Api\UserApiController');

                //handle teams
		    	$router->resource('/teams',                 'Api\TeamApiController');

                //handle tournaments
		    	$router->get('/tournaments',                'Api\TournamentApiController@index');    
                $router->get('/tournaments/{id}',           'Api\TournamentApiController@show');
                $router->get('/tournaments/{id}/parent',    'Api\TournamentApiController@parent');
                $router->get('/tournaments/{id}/follow',    'Api\TournamentApiController@follow_tournament');
                $router->get('/tournaments/{id}/un_follow', 'Api\TournamentApiController@unfollow_tournament');
                $router->get('/tournaments/{id}/gallery',   'Api\TournamentApiController@gallery');
                $router->get('/tournaments/{id}/groups',    'Api\TournamentApiController@group_stage');
                $router->get('/tournaments/{id}/final',     'Api\TournamentApiController@final_stage');
                $router->get('/tournaments/{id}/player_standing',     'Api\TournamentApiController@player_standing');
                //update tournament details;
                $router->post('/tournaments/{id}/update',   'Api\TournamentApiController@update');
                $router->get('/tournaments/search',         'Api\TournamentApiController@search');

                //get sports 
                $router->get('/sports', ['as'=>'get.sports','uses'=>'Api\SportApiController@index']);


                //user interaction
                $router->get('/send_feedback',               'Api\FunctionsApiController@sendFeedback');
                $router->get('/search',                      'Api\FunctionsApiController@search');

       			$router->get('logout', 'Api\AuthController@logout');
			    
    });
});

