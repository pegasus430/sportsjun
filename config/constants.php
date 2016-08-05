<?php

return [
        'JS_VERSION'               => 8,
        'CSS_VERSION'              => 8,
        'COUNTRY_INDIA'            => 101,
        'LIMIT'                    => 8,
        'SCHEDULE_LIMIT'           => 5,
        'SEARCHLIMIT'              => 8,
        'USER_PROFILE_UPLOAD_PATH' => 'uploads/user_profile',
        'SPORT_LOGO_UPLOAD_PATH'   => 'uploads/sports',
        // Imageable Type for all the photos
        'PHOTO'                    => [
                'MARKETPLACE_PHOTO' => 'marketplace',
                'SPORT_LOGO'           => 'sports',
                //user
                'USER_PHOTO'           => 'user_photo', //logo
                'GALLERY_USER'         => 'gallery_user', //user gallery    
                //teams
                'TEAM_PHOTO'           => 'teams',
                'GALLERY_TEAM'         => 'gallery_team', //team gallery
                //tournaments
                'TOURNAMENT_LOGO'      => 'tournaments', //logo
                'GALLERY_TOURNAMENTS'  => 'gallery_tournaments',
                'TOURNAMENT_PROFILE'   => 'form_gallery_tournaments', //form gallery
                //facility
                'FACILITY_LOGO'        => 'facility', //logo
                'GALLERY_FACILITY'     => 'gallery_facility',
                'FACILITY_PROFILE'     => 'form_gallery_facility', //form gallery
                //organization
                'ORGANIZATION_LOGO'    => 'organization', //logo
                'GALLERY_ORGANIZATION' => 'gallery_organization',
                'ORGANIZATION_PROFILE' => 'form_gallery_organization', //form gallery		
                'GALLERY_MATCH'        => 'gallery_match', //scorecard gallery		
        ],
        // folder path for storing and retrieving all the photos
        'PHOTO_PATH'               => [
                'TEAMS_FOLDER_PATH'    => 'teams',
                'USERS_PROFILE'        => 'user_profile',
                'SPORTS'               => 'sports',
                'GALLERY'              => 'gallery',
                'FACILITY'             => 'facility',
                'TOURNAMENT'           => 'tournaments',
                'ORGANIZATION'         => 'organization',
                'ORGANIZATION_PROFILE' => 'form_gallery_organization',
                'TOURNAMENT_PROFILE'   => 'form_gallery_tournaments',
                'FACILITY_PROFILE'     => 'form_gallery_facility',
        ],
        'ENUM'                     => [
                'MARKETPLACE'  =>
                ['ITEMTYPE' => ['new' => 'NEW', 'used' => 'USED']]
                ,
                'USERS'        =>
                ['ROLES' => ['superadmin' => 'SUPERADMIN', 'admin' => 'ADMIN', 'general' => 'GENERAL']]
                ,
                'TOURNAMENTS'  =>
                ['TYPE'          => ['league' => 'Group Tournament', 'knockout' => 'Knock-Out',
                                'multistage' => 'Groups + Knock-Out'],
                        'SCHEDULE_TYPE' => ['team' => 'TEAM', 'individual' => 'INDIVIDUAL']
                ]
                ,
                'SCHEDULE'     =>
                [
                        'MATCH_TYPE'  => [
                                'CRICKET'      => ['odi' => 'ODI', 't20' => 'T20',
                                        'test' => 'TEST'],
                                'TENNIS'       => ['singles' => 'SINGLES', 'doubles' => 'DOUBLES'],
                                'TABLE TENNIS' => ['singles' => 'SINGLES', 'doubles' => 'DOUBLES'],
                                'FOOTBALL'     => ['other' => 'OTHERS'],
                                'BADMINTON'    => ['singles' => 'SINGLES', 'doubles' => 'DOUBLES'],
                                'SQUASH'    => ['singles' => 'SINGLES', 'doubles' => 'DOUBLES'],
                                'OTHERS'       => ['other' => 'OTHERS']
                        ],
                        'PLAYER_TYPE' => ['men' => 'MEN', 'women' => 'WOMEN', 'mixed' => 'MIXED']
                ],
                'TOURNAMENT_SCHEDULE'     =>
                [
                        'MATCH_TYPE'  => [
                                'CRICKET'      => ['odi' => 'ODI', 't20' => 'T20',
                                        'test' => 'TEST', 'any'=>'ANY'],
                                'TENNIS'       => ['singles' => 'SINGLES', 'doubles' => 'DOUBLES',
                                         'any'=>'ANY'],

                                'TABLE TENNIS' => ['singles' => 'SINGLES', 'doubles' => 'DOUBLES',
                                         'any'=>'ANY'],
                                'FOOTBALL'     => ['other' => 'OTHERS'],

                                'BADMINTON'    => ['singles' => 'SINGLES', 'doubles' => 'DOUBLES', 
                                         'any'=>'ANY'],
                                'SQUASH'    => ['singles' => 'SINGLES', 'doubles' => 'DOUBLES', 
                                         'any'=>'ANY'],
                                'OTHERS'       => ['other' => 'OTHERS']
                        ],
                        'PLAYER_TYPE' => ['men' => 'MEN', 'women' => 'WOMEN', 'mixed' => 'MIXED', 'any'=>'ANY']
                ],
                'TEAMS'        =>
                [
                        'TEAM_LEVEL' => [ 'ANY' => 'ANY', 'U12' => 'U12', 'U14' => 'U14',
                                'U16' => 'U16', 'U19' => 'U19', 'U21' => 'U21']
                ],
                'ORGANIZATION' =>
                [
                        'ORGANIZATION_TYPE' => [ 'academy' => 'ACADEMY', 'college' => 'COLLEGE',
                                'school' => 'SCHOOL', 'corporate' => 'CORPORATE',
                                'other' => 'OTHER']
                ],
                'SCORE_CARD'   =>
                [
                        'OUT_AS' => [ 'bowled' => 'Bowled', 'caught' => 'Caught',
                                'handled_ball' => 'Handled the ball', 'hit_ball_twice' => 'Hit the ball twice',
                                'hit_wicket' => 'Hit wicket', 'lbw' => 'LBW', 'obstructing_the_field' => 'Obstructing the field',
                                'retired' => 'Retired', 'run_out' => 'Run out', 'stumped' => 'Stumped',
                                'timed_out' => 'Timed out', 'not_out' => 'Not out']
                ],
                'SPORTS'       =>
                [
                        'SPORTS_TYPE' => [ 'player' => 'PLAYER', 'team' => 'TEAM',
                                'both' => 'BOTH']
                ]
        ],
        'SPORT'                    => [
                1 => 'CricketStatistic',
                2 => 'TennisStatistic',
                3 => 'TtStatistic',
                4 => 'SoccerStatistic',
                5 => 'BadmintonStatistic',
                6 => 'BasketBallStatistic',
                7 => 'VolleyBallStatistic',
                11=> 'HockeyStatistic',
                13=> 'SquashStatistic'
        ],
        'SPORT_NAME'               => [
                1 => 'Cricket',
                2 => 'Tennis',
                3 => 'Table Tennis',
                4 => 'Soccer',
                5 => 'Badminton',
                6 => 'BasketBall',
                7 => 'VolleyBall',
                11 => 'Hockey',
                13 => 'Squash'

        ],
        'SPORT_ID'                 => [
                'Cricket'      => 1,
                'Tennis'       => 2,
                'Table Tennis' => 3,
                'Soccer'       => 4,
                'Badminton'    => 5,
                'BasketBall'   => 6,
                'VolleyBall'   => 7,
                'Hockey'       => 11,
                'Squash'       => 13

        ],
        'SERVICES'                 => [
                //'facility' => 'Facility',
                'team'        => 'Team',
                'tournament'  => 'Tournament',
                'user'        => 'Player',
                'marketplace' => 'Market Place',
                'organization'=> 'Organization'
        ],
        'STATISTICS'               => [
                'MATCH_TYPE' => 'MATCH TYPE',
                'MATCHES'    => 'MATCHES',
                'INNINGS'    => 'INNINGS',
        ],
        'CRICKET_STATS'            => [
                'BATTING_STATS'  => [
                        'INNINGS_BAT'        => 'INNINGS_BAT',
                        'NOT_OUTS'           => 'NOT OUTS',
                        'TOTAL_RUNS'         => 'TOTAL RUNS',
                        "50s"                => "50's",
                        "100s"               => "100's",
                        "4s"                 => "4's",
                        "6s"                 => "6's",
                        'AVERAGE'            => 'AVERAGE',
                        'HIGH_SCORE'         => 'HIGH SCORE',
                        'STRIKE_RATE'        => 'STRIKE RATE',
                        'BATTING_STATISTICS' => 'BATTING',
                ],
                'BOWLLING_STATS' => [
                        'MATCHES_BOWLED'      => 'MATCHES_BOWLED',
                        'WICKETS'             => 'WICKETS',
                        'RUNS CONCEDED'       => 'RUNS CONCEDED',
                        'AVERAGE'             => 'AVERAGE',
                        'ECONOMY'             => 'ECONOMY',
                        'BOWLLING_STATISTICS' => 'BOWLING',
                ],
                'MATCH_TYPE'     => 'MATCH TYPE',
                'MATCHES'        => 'MATCHES',
                'INNINGS'        => 'INNINGS',
        ],
        'TENNIS_OR_TT_STATS'       => [
                'WON'               => 'WON',
                'LOST'              => 'LOST',
                'TIED'              => 'TIED',
                'WON_PERCENTAGE'    => 'WON PERCENTAGE',
                'POINTS'            => 'POINTS',
                'DOUBLE_FAULTS'     => 'DOUBLE FAULTS',
                'ACES'              => 'ACES',
                'TENNIS_STATISTICS' => 'TENNIS',
                'TT_STATISTICS'     => 'TABLE TENNIS',
        ],
        'SOCCER_STATS'             => [
                'YELLOW_CARDS'      => 'YELLOW CARDS',
                'RED_CARDS'         => 'RED CARDS',
                'GOALS_SCORED'      => 'GOALS SCORED',
                'GOALS_SAVED'       => 'GOALS SAVED',
                'GOALS_ASSIST'      => 'GOALS ASSIST',
                'GOALS_PENALTIES'   => 'GOALS PENALTIES',
                'SOCCER_STATISTICS' => 'SOCCER',
        ],
        'BADMINTON_STATS'       => [
                'WON'               => 'WON',
                'LOST'              => 'LOST',
                'TIED'              => 'TIED',
                'WON_PERCENTAGE'    => 'WON PERCENTAGE',
                'POINTS'            => 'POINTS',                
                'BADMINTON_STATISTICS' => 'BADMINTON', 
                'SQUASH_STATISTICS' => 'SQUASH',                
        ],
        'BASKETBALL_STATS'       => [
                'WON'               => 'WON',
                'LOST'              => 'LOST',
                'TIED'              => 'TIED',
                'WON_PERCENTAGE'    => 'WON PERCENTAGE',
                'POINTS'            => 'POINTS',                
                'BASKETBALL_STATISTICS' => 'BASKETBALL ', 
                'POINTS_1'          => 'POINTS_1', 
                'POINTS_2'          => 'POINTS_2', 
                'POINTS_3'          => 'POINTS_3',
                'TOTAL_POINTS'      => 'TOTAL_POINTS',
                'FOULS'             => 'FOULS'                
        ],
        'DEFAULT_PAGINATION'       => 10,
        'YEAR'                     => [
                'START_YEAR' => 2014,
                'END_YEAR'   => 2017,
        ],
        'AUTOCOMPLETE_LIMIT'       => 3,
        'DATE_FORMAT'              => [
                'PHP_DATE_TIME_FORMAT'           => 'd/m/Y H:i:s',      /* not used */
                'PHP_DATE_FORMAT'                => 'd/m/Y',            /* not used */
                'PHP_TIME_FORMAT'                => 'H:i:s',
                'JQUERY_DATE_TIME_FORMAT'        => 'DD/MM/YYYY h:mm A',
                'JQUERY_DATE_FORMAT'             => 'DD/MM/YYYY',
                'JQUERY_TIME_FORMAT'             => 'h:mm A',
                'MY_SQL_DATE_TIME_FORMAT'        => '%d-%m-%Y %H:%i:%s',
                'VALIDATION_DATE_TIME_FORMAT'    => 'd/m/Y g:i A',
                'VALIDATION_DATE_FORMAT'         => 'd/m/Y',
                'VALIDATION_TIME_FORMAT'         => 'g:i A',
                'DISPLAY_TIME_FORMAT'            => 'm/d/Y',
                'JQUERY_DISPLAY_DATE_FORMAT'     => 'dd/mm/yyyy',
                'PHP_DISPLAY_DATE_FORMAT'        => 'd/m/Y',
                'JQUERY_DISPLAY_DATETIME_FORMAT' => 'dd/mm/yyyy H:i:s',
                'DB_STORE_TIME_FORMAT'           => 'Y-m-d H:i:s',
                'DB_STORE_DATE_FORMAT'           => 'Y-m-d',
                'DISPLAY_DATE_FORMAT'            => 'jS F, Y'
        ],
        'REQUEST_TYPE'             => [
                'PLAYER_TO_TEAM'       => 1,
                'PLAYER_TO_TOURNAMENT' => 2,
                'TEAM_TO_PLAYER'       => 3,
                'TEAM_TO_TOURNAMENT'   => 4,
                'TEAM_TO_TEAM'         => 5,
                'PLAYER_TO_PLAYER'     => 6,
        ],
        'VALIDATION'               => [
                'CHARACTERSANDSPACE' => 'regex:/^[a-zA-Z0-9-_ ]+$/',
                //'CHARACTERSANDSPACE' => 'regex:/^[a-zA-Z0-9\s]+$/',
                //'ZIPCODE' => 'regex:/^([0-9\s\-\+\(\)]{3,10})$/',
                'ZIPCODE'            => 'regex:/^([0-9]{6})$/',
                'PHONE'              => 'regex:/^([*\d\s\-\+\(\)]{8,15})$/',
                'PHONEMP'            => 'regex:/^([0-9]{10})$/'
        ]
];