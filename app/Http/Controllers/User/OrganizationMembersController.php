<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Organization;
use App\Model\TeamPlayers;
use App\User;


class OrganizationMembersController extends Controller
{
    public function index($id){
        $organization = Organization::with('staff')->findOrFail($id);
        $staffList = $organization->staff->pluck('name', 'id');

        $allTeams = $organization->teamplayers()->get();

        $teamIds = $allTeams->lists('id');

        #$teamPlayers = TeamPlayers::whereIn('team_players.team_id',$teamIds)->get();

        $members = User::whereHas('userdetails',function($query) use ($teamIds) {
                $query->whereIn('team_players.team_id',$teamIds);
            })->with('userdetails.team')->get();


        return view('organization.members.list',
            compact('id','organization','staffList','members')
        );
    }

}
