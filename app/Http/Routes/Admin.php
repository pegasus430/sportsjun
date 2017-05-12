<?php
Route::group(array('middleware' => 'role'), function() {
    //Dashboard
    Route::get("admin/dashboard", array("as"=>"admin", 'uses'=>'admin\DashboardController@index') );
    //End Dashboard

    //Home



    Route::group(["as"=>'admin.home.',"prefix"=>"admin","namespace"=>"admin"],function(\Illuminate\Routing\Router $router){
        $router->get('infolist/{infolist}',['as'=>'infolists.edit','uses'=>"HomepageInfolistsController@edit"]);
        $router->put('infolist/{infolist}',['uses'=>"HomepageInfolistsController@update"]);
        $router->delete('infolist/{infolist}',['as'=>'infolists.delete','uses'=>"HomepageInfolistsController@delete"]);

        $router->get('infolists/{type}',['as'=>'infolists','uses'=>"HomepageInfolistsController@index"]);
        $router->get('infolists/{type}/create',['as'=>'infolists.add','uses'=>"HomepageInfolistsController@create"]);
        $router->post('infolist/{type}/order',['as'=>'infolists.order','uses'=>"HomepageInfolistsController@order"]);
        $router->post('infolists/{type}/create',['uses'=>"HomepageInfolistsController@store"]);

       });
    //
    Route::group(['as'=>'admin.pages.',"prefix"=>"admin","namespace"=>"admin"],function($router){
        $router->model('dbpage',\App\Model\Page::class);

        $router->get('pages',['as'=>'index','uses'=>'PagesController@index']);
        $router->get('pages/blocks',['as'=>'blocks','uses'=>'PagesController@blocks']);
        $router->get('pages/create',['as'=>'add','uses'=>'PagesController@create']);
        $router->post('pages/create',['as'=>'create','uses'=>'PagesController@store']);

        $router->get('pages/{dbpage}',['as'=>'edit','uses'=>'PagesController@edit']);
        $router->put('pages/{dbpage}','PagesController@update');
        $router->delete('pages/{dbpage}',['as'=>'delete','uses'=>'PagesController@delete']);



    });

    //Sports
    Route::get('admin/createsport', [
        'as' => 'createsport', 'uses' => 'admin\SportsController@index'
    ]);
    Route::get('admin/showsportprofile/{userId}', [
        'as' => 'admin/showsportprofile', 'uses' => 'admin\SportsController@showSportProfile'
    ]);
    Route::get('admin/editsportprofile/{userId}', [
        'as' => 'admin/editsportprofile', 'uses' => 'admin\SportsController@editSportProfile'
    ]);
    Route::post('admin/insertsport', [
        'as' => 'insertsport', 'uses' => 'admin\SportsController@insertsport'
    ]);
    Route::get('admin/viewsports', [
        'as' => 'viewsports', 'uses' => 'admin\SportsController@viewsports'
    ]);
    Route::get('admin/deletesport', [
        'as' => 'deletesport', 'uses' => 'admin\SportsController@deletesport'
    ]);
    Route::get('admin/createoption', [
         'as' => 'createoption', 'uses' => 'admin\SportsController@createoption'
    ]);
    Route::post('admin/saveoptions', [
         'as' => 'saveoptions', 'uses' => 'admin\SportsController@saveoptions'
    ]);
    Route::post('admin/editsport', [
         'as' => 'editsport', 'uses' => 'admin\SportsController@editsport'
    ]);
    Route::get('admin/viewoption/{id}', [
         'as' => 'viewoption', 'uses' => 'admin\SportsController@viewoption'
    ]);
    //End Sports


    //Teams
    Route::get('admin/teamplayer', [
         'as' => 'teamplayer', 'uses' => 'admin\SportsController@teamplayer'
    ]);
    Route::get('adminteam/members/{team_id}/{status?}', [
            'as' => 'adminteam/members', 'uses' => 'admin\TeamController@myteam'
    ]);
    Route::get('admin/teamedit', [
    'as' => 'teamedit', 'uses' => 'admin\TeamController@editTeam'
    ]);

    //End Teams

    //Tournaments
    Route::get('admin/editTournament', [
         'as' => 'editTournament', 'uses' => 'admin\TournamentsController@edit'
    ]);
    Route::get('admin/admintournaments/groups/{id}', [
      'as' => 'groups', 'uses' => 'admin\TournamentsController@groups'
    ]);
    Route::get('admin/tournaments/getUsers', [
      'as' => 'getUsers', 'uses' => 'admin\TournamentsController@getUsers'
    ]);
    Route::resource('admin/tournaments', 'admin\TournamentsController');
    //End Tournaments

    //Marketplace
    Route::get('admin/viewcategories', [
         'as' => 'admin/viewcategories', 'uses' => 'admin\MarketplaceCategoriesController@viewMarcketPlaceCategories'
     ]);

    Route::post('admin/updatecategory', [
        'as' => 'admin/updatecategory', 'uses' => 'admin\MarketplaceCategoriesController@updatecategory'
    ]);

    Route::get('admin/deletecategory', [
        'as' => 'admin/deletecategory', 'uses' => 'admin\MarketplaceCategoriesController@deletecategory'
    ]);
    Route::get('admin/createcategory', [
        'as' => 'admin/createcategory', 'uses' => 'admin\MarketplaceCategoriesController@index'
    ]);
    Route::post('admin/insertcategory', [
        'as' => 'admin/insertcategory', 'uses' => 'admin\MarketplaceCategoriesController@insertcategory'
    ]);
    Route::get('usermarketplace/update', [
        'as' => 'usermarketplace/update', 'uses' => 'admin\MarketplaceController@update'
    ]);


    //End Marketplace

    //Users
    Route::get('admin/user/updateUsers', [
        'as' => 'admin/user/updateUsers', 'uses' => 'admin\UserController@updateUser'
    ]);
    Route::get('admin/listusers', [
         'as' => 'listusers', 'uses' => 'admin\UserController@listusers'
    ]);

    Route::get('admin/createuser', [
         'as' => 'createuser', 'uses' => 'admin\UserController@createuser'
    ]);

    Route::put('admin/createuserprofile', [
         'as' => 'createuserprofile', 'uses' => 'admin\UserController@createuserprofile'
    ]);
    Route::get('admin/edituser', [
         'as' => 'edituser', 'uses' => 'admin\UserController@edituser'
    ]);
    Route::put('admin/updateuserprofile/{id}', [
         'as' => 'updateuserprofile', 'uses' => 'admin\UserController@updateuserprofile'
    ]);


    //End Users

    //Albums
    //Route::get('admin/album/show/{test?}/{id?}/{team_id?}', 'admin\AlbumController@show');
    //End Albums

    //Organization
    Route::get('admin/edit', [
         'as' => 'edit', 'uses' => 'admin\OrganizationController@edit'
    ]);

    //End Organization

    //Facilities
    Route::get('admin/editFacility', [
         'as' => 'editFacility', 'uses' => 'admin\FacilityController@editFacility'
    ]);
    Route::get('getfacilitieslist', [
      'as' => 'getfacilitieslist', 'uses' => 'admin\TournamentsController@tournaments_list'
    ]);

    //End Facilities

    //Photos/Gallery
    Route::get('/viewMorePhotos', 'admin\ShowPhotosController@viewMorePhotos');     
    Route::post('/deletephoto', 'admin\ShowPhotosController@deletephoto');
    Route::resource('admin/photos', 'admin\ShowPhotosController');
    //End Photos/Gallery


    //admin score card start
    Route::get('admin/scheduledMatches', [
         'as' => 'admin/scheduledMatches', 'uses' => 'admin\ScoreCardController@scheduledMatches'
    ]);
    Route::get('admin/getlistviewevents', [
    'as' => 'admin/getlistviewevents', 'uses' => 'admin\ScoreCardController@getListViewEvents'
    ]);
    Route::get('admin/Schedules/viewmorelist', [
    'as' => 'admin/Schedules/viewmorelist', 'uses' => 'admin\ScoreCardController@viewMoreList']);

    Route::get('admin/match/scorecard/edit/{match_id}', [
    'as' => 'admin/match/scorecard/edit', 'uses' => 'User\ScoreCardController@createScorecard']);

    Route::get('admin/match/scorecard/view/{match_id}', [
    'as' => 'admin/match/scorecard/view', 'uses' => 'User\ScoreCardController@createScorecardView']);



    //  Route::postt('admin/online/{c_id}', [
    // 'as' => 'admin/online/availability', 'uses' => 'admin\ScoreCardController@createScorecardView']);


    Route::get('/admin/subscription_methods', 'admin\subscriptionMethodController@index');
    Route::post('/admin/subscription_methods/add', 'admin\subscriptionMethodController@store');


    //admin score card end

    Route::resource('admin/facility', 'admin\FacilityController');
    Route::resource('admin/organization', 'admin\OrganizationController');
    Route::resource('admin/marketplace', 'admin\MarketplaceController');
    Route::resource('admin/team', 'admin\TeamController');
    Route::resource('admin/tournamentevents', 'admin\TournamentEventsController');
    Route::controller('admin/bankaccounts', 'admin\BankAccountsController');
    Route::controller('admin/paymentgateways', 'admin\PaymentGateWaysController');
     Route::post('/admin/settings/add', 'admin\SettingsController@add');

    Route::controller('admin/settings', 'admin\SettingsController');
    Route::resource('admin', 'admin\UserController');

    //Route::get('admin/paymentgateways/availability/{c_id}', 'admin\PaymentGateWaysController@postAvailability');

 });
