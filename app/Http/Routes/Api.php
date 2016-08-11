<?php

Route::group(['prefix'=>'/api/v1','middleware' => 'cors'], function($router){
    	
    	$router->get('/', function(){
    		//return response()->json({'me'});
    	});

    	Route::post('login', 'Api\AuthApiController@login');

//'jwt.refresh'
    	Route::group(['middleware' => ['jwt.auth']], function($router) {	
		    	$router->resource('/user', 'Api\UserApiController');
		    	$router->resource('/team', 'Api\TeamApiController');
		    	$router->resource('/tournament', 'Api\TournamentApiController');    	 

       			Route::post('logout', 'Api\AuthController@logout');
			    Route::get('test', function(){
			           
			        });
    });
});

