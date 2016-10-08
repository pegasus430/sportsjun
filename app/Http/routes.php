<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

require(__DIR__ . "/Routes/Public.php");
require(__DIR__ . "/Routes/Api.php");
$router->group(['middleware' => 'auth'], function ()
{
  require(__DIR__ . "/Routes/User.php");
  require(__DIR__ . "/Routes/Admin.php");
});

Route::get('auth-check', ['uses' => 'User\UserController@authCheck', 'as' => 'auth.check']);

// User Controller with default methods

     

//$router->group(['middleware' => 'auth'], function ()
//{
	  
    
    
   /*  Route::post('/search/searchResultsByType', 'User\SearchController@searchResultsByType');
    Route::post('/search', 'User\SearchController@search');
    Route::get('/search/searchResultsByTypeAutoComplete', 'User\SearchController@searchResultsByTypeAutoComplete'); */
    
   /* Route::post('viewMore', [
    	 'as' => 'viewMore', 'uses' =>  'User\MarketplaceController@marketplaceSearch'
    ]);*/
	 
	
    //Route::resource('search', 'User\SearchController');
    //Route::resource('Schedule', 'ScheduleController');
    //Image Upload
    //Route::get('/', ['as' => 'upload', 'uses' => 'ImageController@getUpload']);
    //Route::post('upload', ['as' => 'upload-post', 'uses' =>'ImageController@postUpload']);
    //Route::post('upload/delete', ['as' => 'upload-remove', 'uses' =>'ImageController@deleteUpload']);
    
    //Route::get('/photo/deleteimage', 'PhotosController@destroy');
    
	   // Router Group For Match Schedules 
  
	//for score card edit
	
	
	
  //Route::get('deleteimage/{id}', array('as' => 'delete_image','uses' => 'PhotosController@destroy'));

  //End Image Upload
  //For Administration
  /*$router->group(['middleware' => 'auth'], function ()
  {

  });*/
      //for teams list

//});
// Route::group(['middleware' => 'team'], function ()
// {
    // Team Controller with default methods
    

    //for myteam(team edit)
    

    
    
	

    

// });

//admin marketplace




//to get facilities for match schedules



//admin tournaments controller

