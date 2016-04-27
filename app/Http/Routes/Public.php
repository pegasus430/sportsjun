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

//Player Stats

//Marketplace

//Tournaments

//Login/Signup pages
Route::get('/', function () {
    return view('welcome');
});
Route::get('testshare', array('as' => 'testshare', function()
{
    return 'Hello World!!!';
}));
Route::get('/', 'HomeController@index');
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