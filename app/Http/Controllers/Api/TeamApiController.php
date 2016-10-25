<?php

namespace App\Http\Controllers\Api;

use App\Model\Team;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TeamApiController extends BaseApiController
{
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
        return $this->ApiResponse($teams);
    }

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
        return $this->ApiResponse($team);
    }

}
