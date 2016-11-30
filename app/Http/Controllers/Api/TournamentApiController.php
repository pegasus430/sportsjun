<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Model\Album;
use App\Model\Followers;
use App\Model\MatchSchedule;
use App\Model\Photo;
use App\Model\Sport;
use App\Model\TournamentGroups;
use App\Model\TournamentGroupTeams;
use DB;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\FunctionsApiController as functionsApi;
use App\Http\Controllers\User\TournamentsController as tournamentsApi;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Tournaments;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Debug\Dumper;
use Response;

class TournamentApiController extends BaseApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->functionsApi = new functionsApi;
        $this->tournamentsApi = new tournamentsApi;
    }


    public function index($filter = false)
    {
        $tournaments = Tournaments::joinParent()->with('sports');
        $user = \Auth::user();
        if ($filter) {
            if ($user) {
                switch ($filter) {
                    case 'managed':
                        $tournaments = $user->getManagedParentTournamentQuery()
                            ->with(['tournaments', 'tournaments.sports'])
                            ->paginate(15);
                        return $this->PaginatedMapResponse($tournaments,
                            [
                                'id',
                                'name',
                                'manager',
                                'owner',
                                'organization_id',
                                'contact_number',
                                'logoImage',
                                'email',
                                'description',
                                'events' => [
                                    'type' => 'list',
                                    'source' => 'tournaments',
                                    'fields' => function ($obj) {
                                        return [
                                            'id',
                                            'image' => 'logoImage',
                                            'name',
                                            'address' => 'location',
                                            'date' => 'dateString',
                                            'status',
                                            'sports_id',
                                            'sport' => 'sports.sports_name'
                                        ];
                                    }
                                ]
                            ]
                        );


                        break;
                    case 'joined':
                        $tournament_ids = $user->getJoinedTournamentsIds();
                        $tournaments = $tournaments->whereIn('tournaments.id', $tournament_ids);
                        break;
                    case 'following':
                        $tournament_ids = $user->folowers()->tournaments()->lists('type_id');
                        $tournaments = $tournaments->whereIn('tournaments.id', $tournament_ids);
                        break;
                    case false:
                        $tournaments->where(DB::raw('false'));
                        break;
                    default:
                        return $this->ApiResponse(['error' => 'Unknown type requested', 404]);
                        break;
                }
            } else {
                return $this->ApiResponse(['error' => 'Auth required', 404]);
            }
        }

        $tournaments = $tournaments->paginate(20);

        return $this->PaginatedMapResponse($tournaments, [
            'id',
            'image' => 'logoImage',
            'name',
            'address' => 'location',
            'date' => 'dateString',
            'status',
            'sports_id',
            'sport' => 'sports.sports_name'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tournament = Tournaments
            ::with(['tournamentParent', 'sports', 'manager'])
            ->find($id);

        return $this->ModelMapResponse($tournament,
            [
                'id',
                'tournament_parent_name' => 'tournamentParent.name',
                'tournament_parent_id',
                'name',
                'created_by',
                'manager_id',
                'manager' => 'manager.name',
                'sports_id',
                'match_type',
                'player_type',
                'schedule_type',
                'game_type',
                'number_of_rubber',
                'start_date',
                'end_date',
                'contact_number',
                'alternate_contact_number',
                'contact_name',
                'email',
                'location',
                'longitude',
                'latitude',
                'address',
                'city_id',
                'city' => 'city',
                'state_id',
                'state',
                'country_id',
                'country',
                'zip',
                'image' => 'logoImage',
                'type',
                'groups_number',
                'groups_teams',
                'facility_id',
                'facility_name',
                'prize_money',
                'enrollment_fee',
                'points_win',
                'points_tie',
                'points_loose',
                'status',
                'description',
                'final_stage_teams_ids',
                'final_stage_teams',
                'isactive',
                'p_1', 'p_2', 'p_3', 'p_4', 'group_is_ended',
                'sports_name' => 'sports.sports_name',

                /*
                'tournament_parent' => [
                    'type' => 'model',
                    'source'=>'tournamentParent',
                    'fields' => [
                        'id',
                        'name'
                    ]
                ],
                'sports' => [
                    'type' => 'model',
                    'source'=>'sports',
                    'fields' => [
                        'id',
                        'sports_name',
                        'sports_type',
                    ]
                ]*/
            ]
        );
        ##Example fields
        ##"tournament_parent":{"id":63,"name":"test Basketball","manager_id":0,"owner_id":151,"organization_id":13,"created_by":151,"contact_number":"12312312","alternate_contact_number":"12312312","logo":"","email":"test@example.com","description":"","created_at":"2016-10-08 19:33:46","updated_at":"2016-10-08 19:33:46","deleted_at":null,"logoImage":"\/images\/default-profile-pic.jpg"},
        ##"sports":{"id":6,"created_by":1,"sports_name":"BasketBall","sports_type":"team","is_schedule_available":1,"is_scorecard_available":1,"created_at":"2016-07-12 11:21:32","updated_at":"2016-04-27 20:22:42","deleted_at":null,"isactive":1

    }

    public function parent($id)
    {
        $tournament = Tournaments::find($id);
        $parent_tournament = $tournament->tournamentParent;

        return $this->ApiResponse($parent_tournament);
    }

    public function follow_tournament($id)
    {
        return $this->functionsApi->follow_unfollow('tournaments', $id, 1);
    }

    public function unfollow_tournament($id)
    {
        return $this->functionsApi->follow_unfollow('tournaments', $id, 0);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {

    }

    public function group_stage($id)
    {

        $groups = TournamentGroups::
        where('tournament_id', $id)
            ->with(['group_teams' => function ($with) {
                $with->orderBy('points', 'desc');
            }, 'tournament'])
            ->get();


        $map = [
            'id',
            'name',
            'isactive',
            'group_teams' => [
                'type' => 'list',
                'source' => 'group_teams',
                'fields' => function ($obj) {
                    $fields = [
                        'rank' => self::$COUNTER_KEY,
                        'tournament_group_id',
                        'tournament_id',
                        'id',
                        'team_id',
                        'name',
                        'won',
                        'lost',
                        'tie',
                        'matches',
                        'points',
                        'final_points',

                    ];

                    $sports_id = object_get($obj, 'tournament.sports_id');
                    if ($sports_id == Sport::$CRICKET) {
                        $fields['nrr'] = 'nrr';
                    }


                    return $fields;
                }
            ]
        ];


        return $this->CollectionMapResponse($groups, $map);
    }

    public function group_stage_matches($id)
    {
        $groups = TournamentGroups
            ::with(['match_schedules' => function ($with) {
                $with->orderby('match_start_date', 'desc')->orderby('match_start_time', 'desc');
            }])
            ->where('tournament_id', $id)->get();
        $map = [
            'id',
            'name',
            'matches' => [
                'type' => 'list',
                'source' => 'match_schedules',
                'fields' => [
                    'Image1' => 'sideALogo',
                    'Name1' => 'sideA.name',
                    'Score1' => 'sideAScore',
                    'Overs1' => 'sideAOvers',
                    'Image2' => 'sideBLogo',
                    'Name2' => 'sideB.name',
                    'Score2' => 'sideBScore',
                    'Overs2' => 'sideBOvers',
                    'Status' => 'match_status',
                    'Winner' => 'winner',
                    'Date' => 'match_start_date',
                    'match_location',
                    'match_start_time',
                    "match_category",
                    "schedule_type",
                    "match_type",
                ]
            ]
        ];
        return $this->CollectionMapResponse($groups, $map);
    }

    public function final_stage($id)
    {
        $tournament = Tournaments::find($id);
        if ($tournament) {
            $groups = $tournament->finalStageTeamsList;

            $map = [
                'id',
                'name',
                'logoImage'
            ];

            return $this->CollectionMapResponse($groups, $map);
        } else {
            return $this->ApiResponse(['error' => 'Tournament not found'], 404);
        }
    }

    public function final_stage_matches($id)
    {
        $tournament = Tournaments::with(
            ['finalMatches' => function ($with) {
                $with->orderby('match_start_date', 'desc')->orderby('match_start_time', 'desc');
            }]
        )->find($id);

        $map = [
            'Image1' => 'sideALogo',
            'Name1' => 'sideA.name',
            'Score1' => 'sideAScore',
            'Overs1' => 'sideAOvers',
            'Image2' => 'sideBLogo',
            'Name2' => 'sideB.name',
            'Score2' => 'sideBScore',
            'Overs2' => 'sideBOvers',
            'Status' => 'match_status',
            'Winner' => 'winner',
            'Date' => 'match_start_date',
            'match_location',
            'match_start_time',
            "match_category",
            "schedule_type",
            "match_type",

        ];
        if ($tournament) {
            return $this->CollectionMapResponse($tournament->finalMatches, $map);
        } else {
            return $this->ApiResponse(['error' => 'Tournament not found'], 404);
        }


    }


    public function player_standing($id)
    {
        $players = $this->tournamentsApi->playerStanding($id, true);
        return $this->ApiResponse($players);
    }


    public function gallery($id)
    {
        $imageable_type_album = config('constants.PHOTO.GALLERY_TOURNAMENTS');
        $loginUserId = \Auth::user()->id;
        $allowed = Helper::isValidUserForTournamentGallery($id, $loginUserId);
       # $allowed = true;
        if ($allowed) {
            $albums = Album::select('id', 'title', 'user_id')
                ->where('imageable_type', $imageable_type_album)
                ->where('imageable_id', $id)
                ->with(['photos'=> function($with){
                    $imageable_type_name = array(config('constants.PHOTO.GALLERY_TOURNAMENTS'));
                    $with->where('imageable_type', $imageable_type_name);
                }])
                ->get();
            $map = [
                'id',
                'title',
                'user_id',
                'photos'=>[
                    'type' => 'list',
                    'source' => 'photos',
                    'fields' => [
                        'id',
                        'user_id',
                        'album_id',
                        'imagePath',
                        'like_count',
                        'is_album_cover',
                        'isactive'
                    ],
                ]
            ];




            return $this->CollectionMapResponse($albums,$map);
        }
        return $this->ApiResponse(['error' => 'Not allowed'], 500);



    }
}
