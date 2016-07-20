<?php
//Dashboard

//Uploads
Route::resource('tempphoto', 'TempphotoController');
Route::post('tempphoto/store', 'TempphotoController@store1');
//End Uploads

//Teams
Route::get('team/teams/{user_id?}', [
    'as'   => 'team/teams',
    'uses' => 'User\TeamController@teamslist',
]);
Route::get('getorgteamdetails/{id}', [
    'as'   => 'getorgteamdetails',
    'uses' => 'User\TeamController@getorgDetails',
]);
Route::get('gettournamentdetails/{id}', [
    'as'   => 'gettournamentdetails',
    'uses' => 'User\TournamentsController@getTournamentDetails',
]);
Route::get('organizationTeamlist/{id}', [
    'as'   => 'organizationTeamlist',
    'uses' => 'User\TeamController@organizationTeamlist',
]);

Route::group(['prefix' => 'organization/{id}'], function () {
    Route::get('staff', [
        'as'   => 'organization.staff',
        'uses' => 'User\OrganizationStaffController@index',
    ]);

    Route::post('staff', [
        'as'   => 'organization.staff',
        'uses' => 'User\OrganizationStaffController@store',
    ]);

    Route::get('groups', [
        'as'   => 'organization.groups.list',
        'uses' => 'User\OrganizationGroupsController@index',
    ]);

    Route::post('groups', [
        'as'   => 'organization.groups.store',
        'uses' => 'User\OrganizationGroupsController@store',
    ]);
});

Route::get('getteamdetails', [
    'as'   => 'getteamdetails',
    'uses' => 'User\TournamentsController@getteamdetails',
]);
Route::get('team/members/{team_id}/{status?}', [
    'as'   => 'team/members',
    'uses' => 'User\TeamController@myteam',
]);
//to make as team manager
Route::get('team/maketeammanager/{team_id}/{user_id}', [
    'as'   => 'team/maketeammanager',
    'uses' => 'User\TeamController@makeasteammanager',
]);

//to remove from team
Route::get('team/removefromteam/{team_id}/{user_id}', [
    'as'   => 'team/removefromteam',
    'uses' => 'User\TeamController@removeplayerfromteam',
]);

//to remove a manager
Route::get('team/removeteammanager/{team_id}/{user_id}', [
    'as'   => 'team/removeteammanager',
    'uses' => 'User\TeamController@removeasteammanager',
]);

//to make as captain
Route::get('team/maketeamcaptain/{team_id}/{user_id}', [
    'as'   => 'team/maketeamcaptain',
    'uses' => 'User\TeamController@makeasteamcaptain',
]);

//to make as vice-captain
Route::get('team/maketeamvicecaptain/{team_id}/{user_id}', [
    'as'   => 'team/maketeamvicecaptain',
    'uses' => 'User\TeamController@makeasteamvicecaptain',
]);

//to send invite reminder
Route::get('team/sendinvitereminder/{team_id}/{user_id}', [
    'as'   => 'team/sendinvitereminder',
    'uses' => 'User\TeamController@sendinvitereminder',
]);

//to edit/delete team
Route::get('team/editdeleteteam', [
    'as'   => 'editdeleteteam',
    'uses' => 'User\TeamController@editanddeleteteam',
]);

//to edit/delete team
Route::get('team/edit/{team_id}', [
    'as'   => 'team/edit',
    'uses' => 'User\TeamController@editteam',
]);

//to edit/delete team
Route::get('team/deleteteam/{team_id}/{flag}', [
    'as'   => 'team/deleteteam',
    'uses' => 'User\TeamController@deleteteam',
]);

//to update team details
Route::post('team/update/{team_id}', [
    'as'   => 'team/update',
    'uses' => 'User\TeamController@updateteam',
]);

//to update player/team details
Route::post('team/updateavailability', [
    'as'   => 'team/updateavailability',
    'uses' => 'User\TeamController@updateplayerorteamavailability',
]);

//to update player/team details
Route::post('team/saverequest', [
    'as'   => 'team/saverequest',
    'uses' => 'User\TeamController@saverequest',
]);

//to display playerrequests
Route::get('team/getteamrequests/{team_id}', [
    'as'   => 'team/getteamrequests',
    'uses' => 'User\TeamController@getteamrequests',
]);

//to display playerrequests
Route::get('team/getviewmoreteamrequests', [
    'as'   => 'team/getviewmoreteamrequests',
    'uses' => 'User\TeamController@getviewmoreteamrequests',
]);

//to display playerrequests
Route::post('team/updateplayerrequest', [
    'as'   => 'team/updateplayerrequest',
    'uses' => 'User\TeamController@acceptrejectrequest',
]);

//to get teams
Route::post('team/getteams', [
    'as'   => 'team/getteams',
    'uses' => 'User\TeamController@getteams',
]);

//to get teamsdiv for teams page
Route::post('team/getTeamPlayersDiv', [
    'as'   => 'team/getTeamPlayersDiv',
    'uses' => 'User\TeamController@getTeamPlayersDiv',
]);


//End Teams

//Organizations
//to edit/delete organization
Route::get('organization/delete/{id}/{flag}', [
    'as'   => 'organization/delete',
    'uses' => 'User\OrganizationController@deleteorganization',
]);
Route::get('organizationTournaments/{id}', [
    'as'   => 'organizationTournaments',
    'uses' => 'User\OrganizationController@organizationTournaments',
]);
Route::resource('organization', 'User\OrganizationController');
//End Organizations

//Gallery
Route::resource('photo', 'PhotosController');
Route::get('/gallery/deleteAlbumPhoto/{id}/{flag}', [
    'as'   => '/gallery/deleteAlbumPhoto',
    'uses' => 'User\AlbumController@deleteAlbumPhoto',
]);
Route::get('/gallery/{id}/{type}', 'User\GalleryController@index');
Route::get('createphoto/{album_id}/{user_id}/{is_user?}/{action?}/{team_id?}/{page?}',
    [
        'as'   => 'createphoto',
        'uses' => 'User\AlbumController@createphoto',
    ]);
Route::get('formgallery/{id?}/{action?}', [
    'as'   => 'formgallery',
    'uses' => 'User\AlbumController@Formgallery',
]);
Route::get('user/album/show/{test?}/{id?}/{team_id?}/{page?}',
    'User\AlbumController@show');
Route::get('albumajax/{test?}/{id?}/{team_id?}/{page?}/{lastinsertedid?}',
    'User\AlbumController@albumajax');
Route::get('galleryajax/{album_id}/{user_id}/{is_user?}/{action?}/{team_id?}/{page?}',
    'User\AlbumController@galleryajax');
//Route::post('user/album/albumphotocreate', 'User\AlbumController@albumphotocreate');
Route::post('user/album/albumphotocreate', [
    'as'   => 'user/album/albumphotocreate',
    'uses' => 'User\AlbumController@albumphotocreate',
]);

Route::post('/Likecount', 'User\AlbumController@Likecount');
Route::post('/Dislikecounts', 'User\AlbumController@Dislikecount');
Route::post('/editAlbumPhoto/', 'User\AlbumController@editAlbumPhoto');
Route::post('/editstore/', 'User\AlbumController@editstore');
Route::resource('gallery', 'User\GalleryController');
Route::resource('user/album', 'User\AlbumController');
//End Gallery
//Tournamets
Route::get('tournaments/groups/{id}/{type?}', [
    'as'   => 'groups',
    'uses' => 'User\TournamentsController@groups',
]);
Route::get('tournaments/getSportTeams/{sport_id}/{tournament_id}/{schedule_type}',
    [
        'as'   => 'getSportTeams',
        'uses' => 'User\TournamentsController@getSportTeams',
    ]);
Route::post('tournaments/addteamtotournament', [
    'as'   => 'addteamtotournament',
    'uses' => 'User\TournamentsController@addteamtotournament',
]);
Route::post('tournaments/insertTournamentGroup', [
    'as'   => 'insertTournamentGroup',
    'uses' => 'User\TournamentsController@insertTournamentGroup',
]);
Route::get('tournament/team/delete/{id}', [
    'as'   => 'deleteteam',
    'uses' => 'User\TournamentsController@deleteteam',
]);
Route::get('tournament/schedule/delete', [
    'as'   => 'deleteTournamentSchedule',
    'uses' => 'User\TournamentsController@deleteTournamentSchedule',
]);
Route::get('tournament/deleteGroupTeams', [
    'as'   => 'deleteGroupTeams',
    'uses' => 'User\TournamentsController@deleteGroupTeams',
]);

Route::get('tournament/groupedit/{param}/{id}', [
    'as'   => 'editgroup',
    'uses' => 'User\TournamentsController@editgroup',
]);
Route::post('tournament/updatefinalstageteams', [
    'as'   => 'updatefinalstageteams',
    'uses' => 'User\TournamentsController@updateFinalStageTeams',
]);
Route::post('tournament/deletefinalstageteams', [
    'as'   => 'deletefinalstageteams',
    'uses' => 'User\TournamentsController@deleteFinalStageTeams',
]);
Route::post('tournament/addfinalstageteams', [
    'as'   => 'addfinalstageteams',
    'uses' => 'User\TournamentsController@addFinalStageTeams',
]);
Route::get('tournaments/getfinalstageteams/{tournament_id}', [
    'as'   => 'getfinalstageteams',
    'uses' => 'User\TournamentsController@getFinalStageTeams',
]);
Route::get('tournament/getroundteams', [
    'as'   => 'getroundteams',
    'uses' => 'User\TournamentsController@getRoundTeams',
]);
Route::get('tournaments/getUsers', [
    'as'   => 'getUsers',
    'uses' => 'User\TournamentsController@getUsers',
]);
Route::get('tournaments/subTournamentEdit', [
    'as'   => 'subTournamentEdit',
    'uses' => 'User\TournamentsController@subTournamentEdit',
]);
Route::get('tournaments/getRequestedTeams', [
    'as'   => 'getRequestedTeams',
    'uses' => 'User\TournamentsController@getRequestedTeams',
]);
Route::get('tournament/getsubtournamentdetails/{tournamentid}', [
    'as'   => 'getsubtournamentdetails',
    'uses' => 'User\TournamentsController@getSubTournamentDetails',
]);


Route::resource('tournaments', 'User\TournamentsController');
//End Tournamets


//Marketplace
Route::get('/marketplace/showgallery/{id}',
    'User\MarketplaceController@showGallery');
Route::get('/marketplace/showavailableitems/{id}',
    'User\MarketplaceController@showavailableitems');
Route::get('/marketplace/deletephoto/{id}', [
    'as'   => 'deletephoto',
    'uses' => 'User\MarketplaceController@deletephoto',
]);
Route::get('/marketplace/myitems', 'User\MarketplaceController@myItems');
Route::post('marketplaceSearch', [
    'as'   => 'marketplaceSearch',
    'uses' => 'User\MarketplaceController@marketplaceSearch',
]);
Route::post('viewMore', [
    'as'   => 'viewMore',
    'uses' => 'User\MarketplaceController@viewMore',
]);
Route::post('marketplaceLog', [
    'as'   => 'marketplaceLog',
    'uses' => 'User\MarketplaceController@marketplaceLogInfo',
]);

/*Route::get('/delete/image/{id}', array('as' => 'delete_image',
'uses' => 'PhotosController@destroy','middleware' => 'photos'));*/
Route::get('/getMarketplaceCategories', [
    'as'   => 'getMarketplaceCategories',
    'uses' => 'User\MarketplaceController@getMarketplaceCategories',
]);
Route::get('/deleteimage/{id}', [
    'as'         => 'delete_image',
    'uses'       => 'PhotosController@destroy',
    'middleware' => 'photos',
]);
Route::post('/marketplace/generateOTP', [
    'uses' => 'User\MarketplaceController@generateOTP',
]);
Route::post('/marketplace/verifyOTP', [
    'uses' => 'User\MarketplaceController@verifyOTP',
]);
Route::post('marketplace/isOtpSent', [
    'uses' => 'User\MarketplaceController@isOtpSent',
]);
Route::resource('marketplace', 'User\MarketplaceController');
//End Marketplace 	

//Search
Route::get('/search', 'User\SearchController@searchresults');
Route::post('search/suggestedWidget', 'User\SearchController@suggestedWidget');
Route::get('search/viewMore', [
    'as'   => 'search/viewMore',
    'uses' => 'User\SearchController@viewMore',
]);
Route::get('search/getCities/{service}/{sport}/{search_by}', [
    'as'   => 'search/getCities',
    'uses' => 'User\TeamController@getCities',
]);
Route::post('search/follow_unfollow', [
    'as'   => 'search/follow_unfollow',
    'uses' => 'User\SearchController@follow_unfollow',
]);


//End Search

//Facilities
Route::resource('facility', 'User\FacilityController');
//End Facilities

//Contactus
Route::resource('contact', 'admin\contactusController');
//End Contactus

//Schedules
//	Route::group(['middleware' => 'matchschedule'], function (){
Route::get('team/schedule/{teamId}/{sportId}', [
    'as'   => 'team/schedule',
    'uses' => 'User\ScheduleController@showSchedules',
]);
Route::get('team/stats/{teamId}/{sportId}', [
    'as'   => 'team/stats',
    'uses' => 'User\ScheduleController@showStats',
]);
Route::get('team/viewmorestats', [
    'as'   => 'team/viewmorestats',
    'uses' => 'User\ScheduleController@viewMoreStats',
]);
Route::get('team/scores/{teamId}/{sportId}', [
    'as'   => 'team/score',
    'uses' => 'User\ScheduleController@showScores',
]);
Route::get('viewmorescores', [
    'as'   => 'viewmorescores',
    'uses' => 'User\ScheduleController@viewMoreScores',
]);
Route::get('getevents', [
    'as'   => 'getevents',
    'uses' => 'User\ScheduleController@getEvents',
]);
Route::get('gettooltipcontent', [
    'as'   => 'gettooltipcontent',
    'uses' => 'User\ScheduleController@getTooltipContent',
]);
Route::get('getlistviewevents', [
    'as'   => 'getlistviewevents',
    'uses' => 'User\ScheduleController@getListViewEvents',
]);
Route::get('team/viewmorelist', [
    'as'   => 'team/viewmorelist',
    'uses' => 'User\ScheduleController@viewMoreList',
]);
//	});
Route::get('schedule/getstates', [
    'as'   => 'schedule/getstates',
    'uses' => 'User\ScheduleController@getstates',
]);
Route::get('schedule/getcountries', [
    'as'   => 'schedule/getcountries',
    'uses' => 'User\ScheduleController@getcountries',
]);
Route::get('facilities', [
    'as'   => 'facilities',
    'uses' => 'User\ScheduleController@getfacilities',
]);
//to get my team details
Route::get('myteamdetails', [
    'as'   => 'myteamdetails',
    'uses' => 'User\ScheduleController@getmyteamdetails',
]);
//to get opp team details
Route::get('oppositeteamdetails', [
    'as'   => 'oppositeteamdetails',
    'uses' => 'User\ScheduleController@getoppositeteamdetails',
]);
//to save match schedule
Route::get('addschedule', [
    'as'   => 'addschedule',
    'uses' => 'User\ScheduleController@saveschedule',
]);
//to save match schedule
Route::get('main_addschedule', [
    'as'   => 'main_addschedule',
    'uses' => 'User\ScheduleController@main_saveschedule',
]);
//to edit match schedule
Route::get('editteamschedule', [
    'as'   => 'editteamschedule',
    'uses' => 'User\ScheduleController@editschedule',
]);
//Route::get('deleteimage/{id}', array('as' => 'delete_image','uses' => 'PhotosController@destroy'));

//to save match schedule
Route::get('addschedule', [
    'as'   => 'addschedule',
    'uses' => 'User\ScheduleController@saveschedule',
]);
//to edit match schedule
Route::get('editteamschedule', [
    'as'   => 'editteamschedule',
    'uses' => 'User\ScheduleController@editschedule',
]);
Route::get('schedule/getmatchandplayertypes', [
    'as'   => 'schedule/getmatchandplayertypes',
    'uses' => 'User\ScheduleController@getmatchandplayertypes',
]);
Route::post('schedule/getmymanagingteams', [
    'as'   => 'schedule/getmymanagingteams',
    'uses' => 'User\ScheduleController@getmymanagingteams',
]);
//End Schedules

//My Schedules
Route::get('myschedule/{userId}', [
    'as'   => 'myschedule',
    'uses' => 'User\MyScheduleController@showMySchedules',
]);
Route::get('getmyevents/{userId}', [
    'as'   => 'getmyevents',
    'uses' => 'User\MyScheduleController@getMyEvents',
]);
Route::get('getmytooltipcontent/{userId}', [
    'as'   => 'getmytooltipcontent',
    'uses' => 'User\MyScheduleController@getMyTooltipContent',
]);
Route::get('getmylistviewevents/{userId}', [
    'as'   => 'getmylistviewevents',
    'uses' => 'User\MyScheduleController@getMyListViewEvents',
]);
Route::get('viewmoremylist/{userId}', [
    'as'   => 'viewmoremylist',
    'uses' => 'User\MyScheduleController@viewMoreMyList',
]);

// End My Schedules        

// Following
Route::get('following/{userId}', [
    'as'   => 'following',
    'uses' => 'User\FollowController@index',
]);

//Matches
Route::get('match/scorecard/edit/{match_id}', [
    'as'   => 'match/scorecard/edit',
    'uses' => 'User\ScoreCardController@createScorecard',
]);
//for score card view
Route::get('match/scorecard/view/{match_id}', [
    'as'   => 'match/scorecard/view',
    'uses' => 'User\ScoreCardController@createScorecardView',
]);
Route::get('match/scorecard/gallery/{id}', [
    'as'   => 'match/scorecard/gallery',
    'uses' => 'User\ScoreCardController@scorecardGallery',
]);
Route::post('match/insertTennisScoreCard', [
    'as'   => 'match/insertTennisScoreCard',
    'uses' => 'User\ScoreCardController@insertTennisScoreCard',
]);
Route::post('match/insertTableTennisScoreCard', [
    'as'   => 'match/insertTableTennisScoreCard',
    'uses' => 'User\ScoreCardController@insertTableTennisScoreCard',
]);
Route::post('match/insertCricketScoreCard', [
    'as'   => 'match/insertCricketScoreCard',
    'uses' => 'User\ScoreCardController@insertCricketScoreCard',
]);
Route::get('match/getplayers', [
    'as'   => 'match/getplayers',
    'uses' => 'User\ScoreCardController@getplayers',
]);
Route::get('match/get_outas_enum', [
    'as'   => 'match/get_outas_enum',
    'uses' => 'User\ScoreCardController@get_outas_enum',
]);

Route::post('match/addPlayertoTeam', [
    'as'   => 'match/addPlayertoTeam',
    'uses' => 'User\ScoreCardController@addPlayertoTeam',
]);
Route::post('match/insertAndUpdateSoccerCard', [
    'as' 	=> 'match/insertAndUpdateSoccerCard',
    'uses' 	=> 'User\ScoreCardController@insertAndUpdateSoccerScoreCard'
]);
Route::post('match/insertAndUpdateHockeyCard', [
    'as'    => 'match/insertAndUpdateHockeyCard',
    'uses'  => 'User\ScoreCard\HockeyScorecardController@insertAndUpdateHockeyScoreCard'
]);

Route::post('match/scoreCardStatus', [
    'as'   => 'match/scoreCardStatus',
    'uses' => 'User\ScoreCardController@scoreCardStatus',
]);

Route::post('match/checkScoreEnterd', [
    'as'   => 'match/checkScoreEnterd',
    'uses' => 'User\ScoreCardController@checkScoreEnterd',
]);

//new routes for soccer match
Route::group(['prefix'=>'match'], function(){

    //routes for soccer
    Route::post('confirmSquad', 	 	['as'=>'match/confirmSquad', 'uses'=>'User\ScoreCardController@confirmSquad']);
    Route::post('soccerSwapPlayers', 	['as'=>'match/soccerSwapPlayers', 'uses'=>'User\ScoreCardController@soccerSwapPlayers']);
    Route::post('choosePenaltyPlayers', ['as'=>'match/choosePenaltyPlayers', 'uses'=>'User\ScoreCardController@choosePenaltyPlayers']);
    Route::post('scorePenalty', ['as'=>'match/scorePenalty', 'uses'=>'User\ScoreCardController@scorePenalty']);
    Route::post('/saveMatchRecord', 'User\ScoreCardController@soccerStoreRecord');
    Route::get('/getSoccerDetails', 'User\ScoreCardController@getSoccerDetails');

    //routes for hockey
    Route::post('confirmSquadHockey',         ['as'=>'match/confirmSquadHockey', 'uses'=>'User\ScoreCard\HockeyScoreCardController@confirmSquad']);
    Route::post('hockeySwapPlayers',    ['as'=>'match/hockeySwapPlayersHockey', 'uses'=>'User\ScoreCard\HockeyScoreCardController@hockeySwapPlayers']);
    Route::post('choosePenaltyPlayersHockey', ['as'=>'match/choosePenaltyPlayersHockey', 'uses'=>'User\ScoreCard\HockeyScoreCardController@choosePenaltyPlayers']);
    Route::post('scorePenaltyHockey', ['as'=>'match/scorePenalty', 'uses'=>'User\ScoreCard\HockeyScoreCardController@scorePenalty']);
    Route::post('/saveMatchRecordHockey', 'User\ScoreCard\HockeyScoreCardController@hockeyStoreRecord');
    Route::get('/getHockeyDetails', 'User\ScoreCard\HockeyScoreCardController@getHockeyDetails');

});

//End Matches

//Users

//route for allowed sports in sports profile edit
Route::post('sport/updateUserStats', [
    'as'   => 'sport/updateUserStats',
    'uses' => 'User\SportController@updateUserStatistics',
]);


// route to display changepassword form
Route::get('changepassword', [
    'as'   => 'changepassword',
    'uses' => 'User\UserController@changePassword',
]);
Route::post('updatepassword', [
    'as'   => 'updatepassword',
    'uses' => 'User\UserController@updatePassword',
]);

Route::get('showsportprofile/{userId}', [
    'as'   => 'showsportprofile',
    'uses' => 'User\SportController@showSportProfile',
]);

Route::get('editsportprofile/{userId}', [
    'as'   => 'editsportprofile',
    'uses' => 'User\SportController@editSportProfile',
]);
//function to get requests for a player
Route::get('sport/playerrequests/{id}', [
    'as'   => 'sport/playerrequests',
    'uses' => 'User\SportController@getplayerrequests',
]);

//function to get requests for a player
Route::get('sport/getviewmoreplayerrequests', [
    'as'   => 'sport/getviewmoreplayerrequests',
    'uses' => 'User\SportController@getviewmoreplayerrequests',
]);

Route::get('getquestions', [
    'as'   => 'getquestions',
    'uses' => 'User\SportController@getQuestions',
]);

Route::get('removefollowedsport/{sport_id}', [
    'as'   => 'removefollowedsport',
    'uses' => 'User\SportController@removeFollowedSport',
]);

Route::get('/searchUser/{sport_id}/{team_id}', [
    'as'   => 'searchUser',
    'uses' => 'User\TeamController@searchUser',
]);
Route::post('/addplayer', [
    'as'   => 'addplayer',
    'uses' => 'User\TeamController@addplayer',
]);

//Route::resource('sport', 'User\SportController');


//route to get sports list
Route::get('sport/getsports', [
    'as'   => 'sport/getsports',
    'uses' => 'User\SportController@getsports',
]);

Route::post('getplayers', [
    'as'   => 'getplayers',
    'uses' => 'User\InvitePlayerController@invitePlayers',
]);
Route::get('user/info/{id?}', 'User\UserController@info');
Route::get('user/team/{id?}', 'User\UserController@team');
Route::get('user/notifications', 'User\UserController@getNotifications');
Route::get('user/viewmorenotifications',
    'User\UserController@getViewMoreNotifications');
Route::resource('user/inviteplayer', 'User\InvitePlayerController');
Route::resource('user', 'User\UserController');
//End Users


//Others
Route::get('getstates', [
    'as'   => 'getstates',
    'uses' => 'User\UserController@getStates',
]);

Route::get('getcities', [
    'as'   => 'getcities',
    'uses' => 'User\UserController@getCities',
]);

Route::get('search_cities', [
    'as'   => 'search_cities',
    'uses' => 'User\SearchCitiesController@search',
]);

Route::get('get_org_groups_list', [
    'as'   => 'organization.groups.get_list',
    'uses' => 'User\SearchOrgGroupsController@getGroupsList',
]);


Route::resource('sport', 'User\SportController');
Route::resource('team', 'User\TeamController');