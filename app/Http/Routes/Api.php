<?php

Route::group(['prefix'=>'/api/v1','middleware' => 'cors'], function($router){
    	
    	$router->get('/', function(){
    		//return response()->json({'me'});
    	});

    	$router->resource('/user', 'Api\UserApiController');
    	$router->resource('/team', 'Api\TeamApiController');
    	$router->resource('/tournament', 'Api\TournamentApiController');
});

