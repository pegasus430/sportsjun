<?php

return [
        'JS_VERSION'               => 9,
        'CSS_VERSION'              => 9,
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
                        'SCHEDULE_TYPE' => ['team' => 'TEAM', 'individual' => 'INDIVIDUAL'],
                        'GAME_TYPE'     => ['normal'    =>'NORMAL - ONE GAME',     'rubber'   =>   'RUBBER - MULTIPLE GAMES'],
                        'ENROLLMENT_TYPE'     => [''=>'LISTING TYPE','online'    =>'ONLINE PAYMENT',     'offline'   =>   'OFFLINE PAYMENT','onlylisting'   =>   'JUST LISTING'],
                        'STATUS'     => ['0'    =>'PENDING',     '1'   =>   'APPROVED','2'   =>   'REJECTED']
                ]
                ,
                'SCHEDULE'     => 
                [
                        'MATCH_TYPE'  => [
                                'CRICKET'      => ['odi' => 'ODI', 't20' => 'T20',
                                        'test' => 'TEST',
                                        'F5'=>'F5',
                                        'T10'=>'T10',
                                        'F15'=>'F15',
                                        'T25'=>'T25',
                                        'T30'=>'T30','T35'=>'T35',
                                        'F40'=>'F40','F45'=>'F45'],
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
                                        'test' => 'TEST', 
                                        'F5'=>'F5',
                                        'T10'=>'T10',
                                        'T15'=>'T15','T25'=>'T25',
                                        'T30'=>'T30','T35'=>'T35',
                                        'F40'=>'F40','F45'=>'F45','any'=>'ANY'],
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
                6 => 'BasketballStatistic',
                7 => 'VolleyballStatistic',
                11=> 'HockeyStatistic',
                13=> 'SquashStatistic',
                14=> 'KabaddiStatistic',
                15=> 'UltimatefrisbeeStatistic',
                16=> 'WaterpoloStatistic',
                17=> 'ThrowballStatistic'

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
                13 => 'Squash',
                14 => 'Kabaddi',
                15 => 'Ultimate Frisbee',
                16 => 'Water Polo',
                17 => 'Throw Ball'

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
                'Squash'       => 13, 
                'Kabaddi'      => 14,
                'Ultimate Frisbee'      => 15,
                'Water Polo'   => 16,
                'Throw Ball'   => 17

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
                'HOCKEY_STATISTICS' => 'HOCKEY',
                'WATERPOLO_STATISTICS'          => 'WATER POLO',
                'ULTIMATEFRISBEE_STATISTICS'    => 'ULTIMATE FRISBEE'
        ],
        'BADMINTON_STATS'       => [
                'WON'               => 'WON',
                'LOST'              => 'LOST',
                'TIED'              => 'TIED',
                'WON_PERCENTAGE'    => 'WON PERCENTAGE',
                'POINTS'            => 'POINTS',                
                'BADMINTON_STATISTICS' => 'BADMINTON', 
                'SQUASH_STATISTICS' => 'SQUASH',  
                'VOLLEYBALL_STATISTICS'     => 'VOLLEYBALL',
                'THROWBALL_STATISTICS'      => 'THROWBALL',
                'KABADDI'                   => 'KABADDI'              
        ],
        'BASKETBALL_STATS'       => [
                'WON'               => 'WON',
                'LOST'              => 'LOST',
                'TIED'              => 'TIED',
                'WON_PERCENTAGE'    => 'WON PERCENTAGE',
                'POINTS'            => 'POINTS',                
                'BASKETBALL_STATISTICS' => 'BASKETBALL ', 
                'POINTS_1'          => '1 POINT', 
                'POINTS_2'          => '2 POINTS', 
                'POINTS_3'          => '3 POINTS',
                'TOTAL_POINTS'      => 'TOTAL POINTS',
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
        ],
        'SPORTS_PREFERENCES'      => [
                1 => [],
                2 => [
                    'number_of_sets'=>3,
                    'score_to_win'  =>6,
                    'end_point'     =>6,
                    'enable_two_points'     => 'off',
                    'active_players' => 6,
                    'substitute'     => 6,

                ],
                3 => [
                    'number_of_sets'=>5,
                    'score_to_win'  =>21,
                    'end_point'     =>29,
                    'enable_two_points'     => 'on',
                    'active_players' => 6,
                    'substitute'     => 6,

                ], 
                5 => [
                    'number_of_sets'=>3,
                    'score_to_win'  =>21,
                    'end_point'     =>29,
                    'enable_two_points'     => 'on',
                    'active_players' => 6,
                    'substitute'     => 6,

                ],
                13 => [
                    'number_of_sets'=>3,
                    'score_to_win'  =>21,
                    'end_point'     =>29,
                    'enable_two_points'     => 'on',
                    'active_players' => 6,
                    'substitute'     => 6,

                ], 
               
                6 => [
                    'number_of_sets' => 5,
                    'active_players' => 6,
                    'substitute'     => 6,                   
                    'maximum_points' => 25,
                    'maximum_substitutes' => 3,
                    'number_of_quarters' => 4

                ],
                7 => [
                   'number_of_sets' => 5,
                    'active_players' => 6,
                    'substitute'     => 6,                   
                    'maximum_points' => 25,
                    'maximum_substitutes' => 3,

                ],
                17 => [
                    'number_of_sets' => 3,
                    'active_players' => 7,
                    'substitute'     => 3,
                    'maximum_points' => 15,
                    'maximum_substitutes' => 3,
                ],
                14 => [
                    'number_of_sets' => 5,
                    'active_players' => 6,
                    'substitute'     => 6,
                    'maximum_points' => 25,
                    'maximum_substitutes' => 3,
                ],
                15 => [
                    'active_players' => 5,
                    'number_of_halves' =>2,
                    'walk_over_points' =>13,
                    'max_fouls'        =>5,
                ]

        ]
];