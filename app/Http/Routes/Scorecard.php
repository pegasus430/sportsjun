<?php
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
    Route::post('confirmSquadHockey',         ['as'=>'match/confirmSquadHockey', 'uses'=>'User\ScoreCard\HockeyScorecardController@confirmSquad']);
    Route::post('hockeySwapPlayers',    ['as'=>'match/hockeySwapPlayersHockey', 'uses'=>'User\ScoreCard\HockeyScorecardController@hockeySwapPlayers']);
    Route::post('choosePenaltyPlayersHockey', ['as'=>'match/choosePenaltyPlayersHockey', 'uses'=>'User\ScoreCard\HockeyScorecardController@choosePenaltyPlayers']);
    Route::post('scorePenaltyHockey', ['as'=>'match/scorePenalty', 'uses'=>'User\ScoreCard\HockeyScorecardController@scorePenalty']);
    Route::post('/saveMatchRecordHockey', 'User\ScoreCard\HockeyScorecardController@hockeyStoreRecord');
    Route::get('/getHockeyDetails', 'User\ScoreCard\HockeyScorecardController@getHockeyDetails');


      //routes for Ultimate Frisbee
    Route::post('confirmSquadultimateFrisbee',         ['as'=>'match/confirmSquadUltimateFrisbee', 'uses'=>'User\ScoreCard\UltimateFrisbeeScorecardController@confirmSquad']);
    Route::post('ultimateFrisbeeSwapPlayers',    ['as'=>'match/hockeySwapPlayersUltimateFrisbee', 'uses'=>'User\ScoreCard\UltimateFrisbeeScorecardController@UltimateFrisbeeSwapPlayers']);
    Route::post('/endMatchRecordultimateFrisbee', 'User\ScoreCard\UltimateFrisbeeScorecardController@UltimateFrisbeeStoreRecord');
    Route::post('/saveMatchRecordultimateFrisbee', 'User\ScoreCard\UltimateFrisbeeScorecardController@UltimateFrisbeeSaveRecord');
    Route::post('manualScoringultimateFrisbee', ['as'=>'match/manualScoringUltimateFrisbee', 'uses'=>'User\ScoreCard\UltimateFrisbeeScorecardController@manualScoring']);


     //routes for basketball
    Route::post('confirmSquadbasketball',         ['as'=>'match/confirmSquadBasketball', 'uses'=>'User\ScoreCard\BasketballScoreCardController@confirmSquad']);
    Route::post('basketballSwapPlayers',    ['as'=>'match/hockeySwapPlayersBasketball', 'uses'=>'User\ScoreCard\BasketballScoreCardController@basketballSwapPlayers']);
    Route::post('/endMatchRecordBasketball', 'User\ScoreCard\BasketballScoreCardController@basketballStoreRecord');
    Route::post('/saveMatchRecordBasketball', 'User\ScoreCard\BasketballScoreCardController@basketballSaveRecord');
    Route::post('manualScoringBasketball', ['as'=>'match/manualScoringBasketball', 'uses'=>'User\ScoreCard\BasketballScoreCardController@manualScoring']);

      //routes for waterpolo
    Route::post('confirmSquadwaterpolo',         ['as'=>'match/confirmSquadwaterpolo', 'uses'=>'User\ScoreCard\WaterPoloScorecardController@confirmSquad']);
    Route::post('waterpoloSwapPlayers',    ['as'=>'match/hockeySwapPlayersWaterpolo', 'uses'=>'User\ScoreCard\WaterPoloScorecardController@waterpoloSwapPlayers']);
    Route::post('/endMatchRecordwaterpolo', 'User\ScoreCard\WaterPoloScorecardController@waterpoloStoreRecord');
    Route::post('/saveMatchRecordwaterpolo', 'User\ScoreCard\WaterPoloScorecardController@waterpoloSaveRecord');
    Route::post('manualScoringwaterpolo', ['as'=>'match/manualScoringwaterpolo', 'uses'=>'User\ScoreCard\WaterPoloScorecardController@manualScoring']);


     //routes for volleyball
    Route::post('confirmSquadvolleyball',        
         ['as'=>'match/confirmSquadVolleyball', 
         'uses'=>'User\ScoreCard\VolleyballScoreCardController@confirmSquad']);

    Route::post('submitServingPlayersvolleyball',         
        ['as'=>'match/submitServingPlayersVolleyball', 
        'uses'=>'User\ScoreCard\VolleyballScoreCardController@submitServingPlayers']);

    Route::post('volleyballSwapPlayers',    ['as'=>'match/volleyballSwapPlayersVolleyball', 'uses'=>'User\ScoreCard\VolleyballScoreCardController@volleyballSwapPlayers']);
    Route::post('/endMatchRecordvolleyball', 'User\ScoreCard\VolleyballScoreCardController@volleyballStoreRecord');
    Route::post('/saveMatchRecordVolleyball', 'User\ScoreCard\BasketballScoreCardController@volleyballSaveRecord');
    Route::post('manualScoringvolleyball', ['as'=>'match/manualScoringVolleyball', 'uses'=>'User\ScoreCard\VolleyballScoreCardController@manualScoring']);
      Route::post('volleyballAddScore', ['as'=>'match/volleyballAddScore', 'uses'=>'User\ScoreCard\VolleyballScoreCardController@addScore']);



     //routes for kabaddi
    Route::post('confirmSquadkabaddi',         ['as'=>'match/confirmSquadKabaddi', 'uses'=>'User\ScoreCard\KabaddiScoreCardController@confirmSquad']);
    Route::post('kabaddiSwapPlayers',    ['as'=>'match/hockeySwapPlayersKabaddi', 'uses'=>'User\ScoreCard\KabaddiScoreCardController@kabaddiSwapPlayers']);
    Route::post('/endMatchRecordkabaddi', 'User\ScoreCard\KabaddiScoreCardController@kabaddiStoreRecord');
    Route::post('/saveMatchRecordkabaddi', 'User\ScoreCard\KabaddiScoreCardController@kabaddiSaveRecord');
    Route::post('manualScoringkabaddi', ['as'=>'match/manualScoringKabaddi', 'uses'=>'User\ScoreCard\KabaddiScoreCardController@manualScoring']);

        //routes for Throwball
    Route::post('confirmSquadthrowball',        
         ['as'=>'match/confirmSquadThrowball', 
         'uses'=>'User\ScoreCard\ThrowballScoreCardController@confirmSquad']);

    Route::post('submitServingPlayersthrowball',         
        ['as'=>'match/submitServingPlayersThrowball', 
        'uses'=>'User\ScoreCard\ThrowballScoreCardController@submitServingPlayers']);

    Route::post('throwballSwapPlayers',    ['as'=>'match/volleyballSwapPlayersThrowball', 'uses'=>'User\ScoreCard\ThrowballScoreCardController@throwballSwapPlayers']);
    Route::post('/endMatchRecordthrowball', 'User\ScoreCard\ThrowballScoreCardController@throwballStoreRecord');

    Route::post('/saveMatchRecordthrowball', 'User\ScoreCard\BasketballScoreCardController@throwballSaveRecord');

    Route::post('manualScoringthrowball', ['as'=>'match/manualScoringThrowball', 'uses'=>'User\ScoreCard\ThrowballScoreCardController@manualScoring']);

      Route::post('throwballAddScore', ['as'=>'match/throwballAddScore', 'uses'=>'User\ScoreCard\ThrowballScoreCardController@addScore']);




    //routes for badminton

    Route::post('saveBadmintonPreferences', ['as'=>'match/saveBadmintonPreferences', 'uses'=>'User\ScoreCard\BadmintonScoreCardController@savePreferences']);

        //automatic scoring for badminton
    Route::post('badmintonAddScore', ['as'=>'match/badmintonAddScore', 'uses'=>'User\ScoreCard\BadmintonScoreCardController@addScore']);
    Route::post('badmintonRemoveScore', ['as'=>'match/badmintonRemoveScore', 'uses'=>'User\ScoreCard\BadmintonScoreCardController@removeScore']);

        //manual scoring for badminton
    Route::post('manualScoringBadminton', ['as'=>'match/manualScoringBadminton', 'uses'=>'User\ScoreCard\BadmintonScoreCardController@manualScoring']);

        //save final match record. after clicking on end match
    Route::post('saveMatchRecordBadminton', ['as'=>'match/saveMatchRecordBadminton', 'uses'=>'User\ScoreCard\BadmintonScoreCardController@badmintonStoreRecord']);

    Route::post('updatePreferencesBadminton', ['as'=>'match/updatePreferencesBadminton', 'uses'=>'User\ScoreCard\BadmintonScoreCardController@updatePreferences']);

        //get match details for badminton
    Route::get('/getBadmintonDetails', 'User\ScoreCard\BadmintonScoreCardController@getBadmintonDetails');

       
  
    //routes for squash
    Route::post('savesquashPreferences', ['as'=>'match/savesquashPreferences', 'uses'=>'User\ScoreCard\SquashScoreCardController@savePreferences']);
        //automatic scoring for squash
    Route::post('squashAddScore', ['as'=>'match/squashAddScore', 'uses'=>'User\ScoreCard\SquashScoreCardController@addScore']);
    Route::post('squashRemoveScore', ['as'=>'match/squashRemoveScore', 'uses'=>'User\ScoreCard\SquashScoreCardController@removeScore']);

        //manual scoring for squash
    Route::post('manualScoringsquash', ['as'=>'match/manualScoringsquash', 'uses'=>'User\ScoreCard\SquashScoreCardController@manualScoring']);

        //save final match record. after clicking on end match
    Route::post('saveMatchRecordsquash', ['as'=>'match/saveMatchRecordsquash', 'uses'=>'User\ScoreCard\SquashScoreCardController@squashStoreRecord']);

    Route::post('updatePreferencessquash', ['as'=>'match/updatePreferencesSquash', 'uses'=>'User\ScoreCard\SquashScoreCardController@updatePreferences']);

        //get match details for squash
    Route::get('/getsquashDetails', 'User\ScoreCard\SquashScoreCardController@getsquashDetails');



    //routes for table tennis

    Route::post('savetableTennisPreferences', ['as'=>'match/savetableTennisnPreferences', 'uses'=>'User\ScoreCard\TabletennisScoreCardController@savePreferences']);
        //automatic scoring for badminton
    Route::post('tableTennisAddScore', ['as'=>'match/tableTennisAddScore', 'uses'=>'User\ScoreCard\TabletennisScoreCardController@addScore']);
    Route::post('tableTennisRemoveScore', ['as'=>'match/tableTennisRemoveScore', 'uses'=>'User\ScoreCard\TabletennisScoreCardController@removeScore']);
        //manual scoring for badminton
    Route::post('manualScoringtableTennis', ['as'=>'match/manualScoringtableTennis', 'uses'=>'User\ScoreCard\TabletennisScoreCardController@manualScoring']);
        //save final match record. after clicking on end match
    Route::post('saveMatchRecordtableTennis', ['as'=>'match/saveMatchRecordtableTennis', 'uses'=>'User\ScoreCard\TabletennisScoreCardController@tableTennisStoreRecord']);

    Route::post('updatePreferencestableTennis', ['as'=>'match/updatePreferencestableTennis', 'uses'=>'User\ScoreCard\TabletennisScoreCardController@updatePreferences']);


    //routes for  tennis

     Route::post('savetennisPreferences', ['as'=>'match/savetennisnPreferences', 'uses'=>'User\ScoreCard\TennisScoreCardController@savePreferences']);
        //automatic scoring for badminton
    Route::post('tennisAddScore', ['as'=>'match/tennisAddScore', 'uses'=>'User\ScoreCard\TennisScoreCardController@addScore']);
    Route::post('tennisRemoveScore', ['as'=>'match/tennisRemoveScore', 'uses'=>'User\ScoreCard\TennisScoreCardController@removeScore']);
        //manual scoring for badminton
    Route::post('manualScoringtennis', ['as'=>'match/manualScoringtennis', 'uses'=>'User\ScoreCard\TennisScoreCardController@manualScoring']);
        //save final match record. after clicking on end match
    Route::post('saveMatchRecordtennis', ['as'=>'match/saveMatchRecordtennis', 'uses'=>'User\ScoreCard\TennisScoreCardController@tennisStoreRecord']);

    Route::post('updatePreferencestennis', ['as'=>'match/updatePreferencestennis', 'uses'=>'User\ScoreCard\TennisScoreCardController@updatePreferences']);

    Route::post('tennis_scoring', ['as'=>'match/tennis_scoring', 'uses'=>'User\ScoreCard\TennisScoreCardController@tennis_scoring']);

    Route::post('tennis_scoring_tb', ['as'=>'match/tennis_scoring_tb', 'uses'=>'User\ScoreCard\TennisScoreCardController@tennis_scoring_tb']);
    Route::post('end_set', ['as'=>'match/end_set', 'uses'=>'User\ScoreCard\TennisScoreCardController@end_set']);


//Match Helpers
    Route::get('set_active_rubber/{rubber_id?}', 'User\ScoreCardController@setActiveRubber');
    Route::get('end_match_completely_badminton/{match_id?}', 'User\ScoreCard\BadmintonScoreCardController@endMatchCompletely');
    Route::get('end_match_completely_tableTennis/{match_id?}', 'User\ScoreCard\TabletennisScoreCardController@endMatchCompletely');
     Route::get('end_match_completely_squash/{match_id?}', 'User\ScoreCard\SquashScoreCardController@endMatchCompletely');
     Route::get('end_match_completely_tennis/{match_id?}', 'User\ScoreCard\TennisScoreCardController@endMatchCompletely');


     Route::get('allow_match_edit_by_admin', 'User\ScoreCardController@allow_match_edit_by_admin');
});

//End Matches

