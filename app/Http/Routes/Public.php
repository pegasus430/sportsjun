<?php
//Scorecards
/* Route::get('public/completedmatches', [
	'as' => 'public/completedmatches', 'uses' =>  'Auth\ScoreCardController@completedMatches'
	]);
	
Route::get('public/scorecard/view/{match_id}', [
			'as' => 'public/scorecard/view', 'uses' => 'Auth\ScoreCardController@createScorecardView'
	]); */

Route::get('matchpublic/scorecard/view/{match_id}', [
	'as' => 'matchpublic/scorecard/view', 'uses' => 'User\ScoreCardController@createScorecardpublicView'
]);
Route::group(['prefix'=>'viewpublic'], function(){
	Route::get('createphoto/{album_id}/{user_id}/{is_user?}/{action?}/{team_id?}/{page?}','User\AlbumController@createphotopublic');
	Route::get('user/album/show/{test?}/{id?}/{team_id?}/{page?}', 'User\AlbumController@show');
	Route::get('gettournamentdetails/{id}', 'User\TournamentsController@getTournamentDetails');
	Route::get('tournaments/groups/{id}/{type?}','User\TournamentsController@groups');
	Route::get('editsportprofile/{userId}', 'User\SportController@editSportProfile');
	Route::get('showsportprofile/{userId}','User\SportController@showSportProfile');
	Route::get('team/members/{team_id}/{status?}','User\TeamController@myteam');

	Route::get('getquestions', 'User\SportController@getQuestions');

	// Routes for match details loaded with ajax

	Route::get('/match/getBadmintonDetails', 'User\ScoreCard\BadmintonScorecardController@getBadmintonDetails');
	Route::get('/match/getSoccerDetails', 'User\ScoreCardController@getSoccerDetails');


});


//Player Stats

//Marketplace

//Tournaments

//Login/Signup pages
/*
Route::get('/', function () {
    return view('welcome');
});
 * 
 */
Route::get('js_close',function(){ return  "<script type=\"text/javascript\">window.close();</script>"; });
Route::get('testshare', array('as' => 'testshare', function()
{
	return 'Hello World!!!';
}));
Route::get('/', 'HomeController@index');
Route::get('/{page}.html', function ($page) {
	if ($page == 'index') { return view('home'); }
	return view('home.' . $page);
});
Route::get('/skip', 'HomeController@skip');

// Default Laravel Routes for login,registration and reset password
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
//Start Auth
Route::get('verifyemail', [
	'as' => 'verifyemail', 'uses' => 'Auth\AuthController@verifyEmail'
]);

Route::get('confirmemail/{code}', [
	'as' => 'confirmemail/{code}', 'uses' => 'Auth\AuthController@confirmEmail'
]);
Route::get('resendverifyemail/{email}', [
	'as' => 'resendverifyemail/{email}', 'uses' => 'Auth\AuthController@resendVerifyEmail'
]);
//End auth
Route::get('refereshcapcha', [
	'as' => 'refereshcapcha', 'uses' =>  'Auth\AuthController@refereshcapcha'
]);

//START CRONS
Route::get('/schedulescron', function()
{
	$exitCode = Artisan::call('cron:notifyschedules');
	//
});
Route::get('/mailscron', function()
{
	$exitCode = Artisan::call('cron:sendmails');
	//
});
//END CRONS

//Login/Signup
Route::get('social/redirect/{provider}', ['uses' => 'User\UserController@redirectToProvider', 'as' => 'social.login']);
Route::get('social/callback/{provider}', 'User\UserController@handleProviderCallback');
//End Login/Signup