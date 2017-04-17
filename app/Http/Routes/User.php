<?php
//Dashboard

require('Scorecard.php');
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
    'uses' => 'User\OrganizationController@getorgDetails',
]);
Route::get('gettournamentdetails/{id}', [
    'as'   => 'gettournamentdetails',
    'uses' => 'User\TournamentsController@getTournamentDetails',
]);
Route::get('organizationTeamlist/{id}/{group_id?}', [
    'as'   => 'organizationTeamlist',
    'uses' => 'User\TeamController@organizationTeamlist',
]);

Route::get('organization/organizations/{user_id?}', [
    'as'   => 'organizations',
    'uses' => 'User\OrganizationController@organizationList',
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
    Route::put('groups', [
        'as'   => 'organization.groups.update',
        'uses' => 'User\OrganizationGroupsController@edit',
    ]);

    Route::get('players', [
        'as'   => 'organization.members.list',
        'uses' => 'User\OrganizationMembersController@index',
    ]);

    Route::get('/', 'User\OrganizationController@index');
    Route::get('/delete_actions','User\OrganizationController@delete_actions');
    Route::post('/save_player', 'User\OrganizationMembersController@save_player');
    Route::get('/create_team', 'User\TeamController@create');
    Route::get('/gallery', 'User\OrganizationController@gallery');
    Route::post('/album/add', 'User\OrganizationController@album_save');
    Route::post('/photo/add', 'User\OrganizationController@photo_save');
    Route::get('/update_fields', 'User\OrganizationController@update_fields');

    Route::get('schedules', [
        'as'   => 'organization.schedules.list',
        'uses' => 'User\OrganizationSchedulesController@index',
    ]);

    Route::get('marketplace','User\MarketplaceController@organization_marketplace');
    Route::get('new_tournament', 'User\OrganizationController@new_tournament');



    Route::get('widget',[
        'as' =>'organization.widget.code',
        'uses' => 'User\OrganizationController@widgetCode'
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

//TODO: maketeam* methods should be POST instead GET
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

//to make as coach
Route::get('team/maketeamcoach/{team_id}/{user_id}', [
    'as'   => 'team/maketeamcoach',
    'uses' => 'User\TeamController@makeasteamcoach',
]);

Route::get('team/removeteamcoach/{team_id}/{user_id}', [
    'as'   => 'team/removeteamcoach',
    'uses' => 'User\TeamController@removeasteamcoach',
]);


//to make as coach
Route::get('team/maketeamphysio/{team_id}/{user_id}', [
    'as'   => 'team/maketeamphysio',
    'uses' => 'User\TeamController@makeasteamphysio',
]);

Route::get('team/removeteamphysio/{team_id}/{user_id}', [
    'as'   => 'team/removeteamphysio',
    'uses' => 'User\TeamController@removeasteamphysio',
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


Route::post('team/change_ownership', [
    'as'   => 'team.change_ownership',
    'uses' => 'User\TeamController@changeOwnership',
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

Route::post('parent_tournament/{id}/add_sport', [
    'as'   => 'addSportsOrganizationGroup',
    'uses' => 'User\OrganizationController@addGroupSportPoints',
]);
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
    [
        'as'=>'user.album.show',
        'uses'=>'User\AlbumController@show'
    ]);
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
Route::get('tournaments/settings/{id}/', [
    'as'   => 'Tournament Settings',
    'uses' => 'User\TournamentsController@settings',
]);
Route::post('tournaments/settings/{id}/update', [
    'as'   => 'Tournament Settings Update',
    'uses' => 'User\TournamentsController@updateSettings',
]);
Route::get('tournaments/groups/{id}/player_standing', [
    'as'   => 'groups',
    'uses' => 'User\TournamentsController@playerStanding',
]);
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
Route::get('tournaments/rubberEdit', [
    'as'   => 'rubberEdit',
    'uses' => 'User\TournamentsController@rubberEdit',
]);
Route::post('tournaments/rubber/{id}/update_schedule', [
    'as'   => 'rubberUpdateSchedule',
    'uses' => 'User\TournamentsController@rubberUpdateSchedule',
]);
Route::get('tournaments/end_tournament/{id}', [
    'as'   => 'endTournament',
    'uses' => 'User\TournamentsController@endTournament',
]);
Route::post('tournaments/match/{id}/add_rubber', [
    'as'   => 'addRubber',
    'uses' => 'User\TournamentsController@addRubber',
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
Route::post('tournaments/{id}/update', 'User\TournamentsController@update');
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
Route::get('schedule/getmymanagingteams', [
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
    'uses' => 'User\ScoreCard\TabletennisScoreCardController@insertTableTennisScoreCard',
]);
Route::post('match/insertCricketScoreCard', [
    'as'   => 'match/insertCricketScoreCard',
    'uses' => 'User\ScoreCard\CricketScoreCardController@insertCricketScoreCard',
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
    'uses' 	=> 'User\ScoreCard\SoccerScoreCardController@insertAndUpdateSoccerScoreCard'
]);
Route::post('match/insertAndUpdateHockeyCard', [
    'as'    => 'match/insertAndUpdateHockeyCard',
    'uses'  => 'User\ScoreCard\HockeyScorecardController@insertAndUpdateHockeyScorecard'
]);

Route::post('match/scoreCardStatus', [
    'as'   => 'match/scoreCardStatus',
    'uses' => 'User\ScoreCardController@scoreCardStatus',
]);

Route::post('match/checkScoreEnterd', [
    'as'   => 'match/checkScoreEnterd',
    'uses' => 'User\ScoreCardController@checkScoreEnterd',
]);


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
Route::get('/searchUser', [
    'as'   => 'searchAnyUser',
    'uses' => 'User\TeamController@searchAnyUser',
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
Route::post('/addemailtoplayer', 'User\InvitePlayerController@addEmailToPlayers');
Route::post('user/set-sports', ['as'=>'select-sports','uses'=>'User\UserController@setSports']);

Route::get('user/info/{id?}', 'User\UserController@info');
Route::get('user/team/{id?}', 'User\UserController@team');
Route::get('user/notifications', 'User\UserController@getNotifications');
Route::get('user/viewmorenotifications',
    'User\UserController@getViewMoreNotifications');
Route::resource('user/inviteplayer', 'User\InvitePlayerController');
Route::resource('user', 'User\UserController');

Route::post('/add_referee', 'User\InvitePlayerController@add_referee');
Route::post('/invite_referee', 'User\InvitePlayerController@invite_referee');
Route::post('/remove_referee', 'User\InvitePlayerController@remove_referee');






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


Route::get('/reloadgroupteampoints', 'User\OrganizationController@testTournaments');
Route::get('/updatehalftime/{match_id?}/{half_time}', 'User\ScoreCardController@updatehalftime');

Route::post('/rating/set','User\RateController@setUserRate');



Route::get('tournaments/eventregistration/{id}', [
    'as'   => 'eventregistration',
    'uses' => 'User\TournamentsController@eventregistration',
]);


Route::post('tournaments/registrationdata', [
    'as'   => 'registrationdata',
    'uses' => 'User\TournamentsController@registrationdata',
]);

Route::get('tournaments/registerstep2/{id}', [
    'as'   => 'registerstep2',
    'uses' => 'User\TournamentsController@registerstep2',
]);


Route::get('tournaments/registerstep3/{id}', [
    'as'   => 'registerstep3',
    'uses' => 'User\TournamentsController@registerstep3',
]);


Route::get('tournaments/registerstep3/{id}/{event_id}', [
    'as'   => 'registerstep4',
    'uses' => 'User\TournamentsController@registerstep4',
]);

Route::post('tournaments/registrationstep5', [
    'as'   => 'registerstep5',
    'uses' => 'User\TournamentsController@registerstep5',
]);



Route::get('tournaments/paymentform/{id}', [
    'as'   => 'paymentform',
    'uses' => 'User\TournamentsController@getPaymentform',
]);

Route::post('tournaments/paymentform', [
    'as'   => 'paymentform',
    'uses' => 'User\TournamentsController@postPaymentform',
]);


Route::post('tournaments/payment_success', [
    'as'   => 'paymentsuccess',
    'uses' => 'User\TournamentsController@postPaymentsuccess',
]);


Route::post('tournaments/payment_failure', [
    'as'   => 'paymentsuccess',
    'uses' => 'User\TournamentsController@postPaymentfailure',
]);


Route::post('tournaments/payment_details', [
    'as'   => 'paymentdetails',
    'uses' => 'User\TournamentsController@getPaymentdetails',
]);



Route::get('mytransactions/{userId}', [
    'as'   => 'mytransactions',
    'uses' => 'User\TournamentsController@Transactions',
]);

Route::get('mytournamentregistrations/{userId}', [
    'as'   => 'mytournamentregistrations',
    'uses' => 'User\TournamentsController@myTournamentRegistrations',
]);