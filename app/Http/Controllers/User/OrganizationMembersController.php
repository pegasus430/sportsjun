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

        $teamPlayers = TeamPlayers::whereIn('team_players.team_id',$teamIds)->get();

        $member_ids = $teamPlayers->lists('user_id');
        $members = User::whereIn('id',$member_ids)->get();

       #  dd($members->lists('id'));
       # $members =

        return view('organization.members.list',
            compact('id','organization','staffList','members')
        );
    }

}
