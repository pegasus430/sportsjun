<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Model\Followers;
use App\Model\MatchSchedule;
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
                            ->with(['tournaments','tournaments.sports'])
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
                                'events'=>[
                                    'type'=>'list',
                                    'source'=>'tournaments',
                                    'fields' => function($obj) {
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
                        return $this->ApiResponse(['error'=>'Unknown type requested',404]);
                        break;
                }
            } else {
                return $this->ApiResponse(['error'=>'Auth required',404]);
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
            ::with(['tournamentParent', 'sports'])
            ->find($id);

        return $this->ApiResponse($tournament);
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
            ->with(['group_teams','tournament'])
            ->get();


        $map = [
            'id',
            'name',
            'isactive',
            'group_teams' => [
                'type'=>'list',
                'source'=>'group_teams',
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

                    $sports_id = object_get($obj,'tournament.sports_id');
                    if($sports_id == Sport::$CRICKET){
                        $fields['nrr']='nrr';
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
            ::with('match_schedules')
            ->where('tournament_id', $id)->get();

        $result = [];
        foreach ($groups as $group) {
            $result_group = [
                'id' => $group->id,
                'name' => $group->name,
                'matches' => []
            ];
            foreach ($group->match_schedules as $schedule) {

                $result_group['matches'][] = [
                    'Image1' => $schedule->sideALogo,
                    'Name1' => object_get($schedule->sideA, 'name'),
                    'Score1' => $schedule->sideAScore,
                    'Overs1' => '',
                    'Image2' => $schedule->sideBLogo,
                    'Name2' => object_get($schedule->sideB, 'name'),
                    'Score2' => $schedule->sideBScore,
                    'Overs2' => '',
                    'Status' => $schedule->match_status,
                    'Winner' => $schedule->winner,
                    'Date' => $schedule->start_date,
                ];
            }
            $result [] = $result_group;
        }
        return $this->ApiResponse($result);
    }

    public function final_stage($id)
    {
        $tournament = Tournaments::find($id);
        if ($tournament) {
            $groups = $tournament->finalStageTeamsList;

            return $this->ApiResponse($groups);
        } else {
            return $this->ApiResponse(['error' => 'Tournament not found'], 404);
        }
    }

    public function final_stage_matches($id)
    {
        $tournament = Tournaments::find($id);
        if ($tournament) {
            $matches = $tournament->finalMatches;
            $result = [];
            foreach ($matches as $schedule) {
                $result[] = [
                    'Image1' => $schedule->sideALogo,
                    'Name1' => object_get($schedule->sideA, 'name'),
                    'Score1' => $schedule->sideAScore,
                    'Overs1' => '',
                    'Image2' => $schedule->sideBLogo,
                    'Name2' => object_get($schedule->sideB, 'name'),
                    'Score2' => $schedule->sideBScore,
                    'Overs2' => '',
                    'Status' => $schedule->match_status,
                    'Winner' => $schedule->winner,
                    'Date' => $schedule->start_date,
                ];
            }
            return $this->ApiResponse($result);
        } else {
            return $this->ApiResponse(['error' => 'Tournament not found'], 404);
        }

    }


    public function player_standing($id)
    {
        $players = $this->tournamentsApi->playerStanding($id, true);
        return $this->ApiResponse($players);
    }
}
