<?php

namespace App\Http\Controllers\Api;

use App\Model\Team;

class TeamApiController extends BaseApiController
{
    /**
     * Team - list
     * @return array
     */
    public function index()
    {
        $teams = Team::select([
            'id',
            'team_owner_id',
            'sports_id',
            'organization_id',
            'team_level',
            'gender',
            'location',
            'address',
            'city_id',
            'city',
            'state_id',
            'state',
            'country_id',
            'country',
            'zip',
            'logo',
            'description',
            'team_available',
            'player_available'
        ])->paginate(50);
        return self::ApiResponse($teams);
    }

    /**
     * Team - info
     * @param $id
     * @return array
     */

    public function show($id)
    {
        $team = Team::where('id', $id)
            ->select([
                'id',
                'team_owner_id',
                'sports_id',
                'organization_id',
                'team_level',
                'gender',
                'location',
                'address',
                'city_id',
                'city',
                'state_id',
                'state',
                'country_id',
                'country',
                'zip',
                'logo',
                'description',
                'team_available',
                'player_available'
            ])->first();
        return self::ApiResponse($team);
    }

}
