<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Organization;
use App\Model\TeamPlayers;
use App\User;


class OrganizationMembersController extends Controller
{
    public function teamList($id){
        $term = \Request::has('term') ? filter_var(\Request::get('term'), FILTER_SANITIZE_STRING) : false;
        $organization = Organization::with('staff')->findOrFail($id);
        $allTeams = $organization->teamplayers();
        if ($term){
            $allTeams->where('name','LIKE','%'.$term.'%');
        }
        return $allTeams->orderBy('name','ASC')->get()->unique('name')->lists('name','id');
    }

    public function index($id)
    {
        $organization = Organization::with('staff')->findOrFail($id);

        $staffList = $organization->staff->pluck('name', 'id');
        $allTeams = $organization->teamplayers()->get();

        $teamIds = $allTeams->lists('id');
        #$teamPlayers = TeamPlayers::whereIn('team_players.team_id',$teamIds)->get();

        $filter_team = \Request::has('filter-team') ? filter_var(\Request::get('filter-team'),FILTER_SANITIZE_STRING) : null;

        $members = User::whereHas('userdetails', function ($query) use ($teamIds, $id,$filter_team) {
            $query->whereIn('team_players.team_id', $teamIds)->whereNotIn('team_players.role', ['owner', 'manager'])
                ->join('teams', 'teams.id', '=', 'team_players.team_id')
                ->where('teams.organization_id', $id);
                if ($filter_team){
                    $query->where('teams.name','LIKE','%'.$filter_team.'%');
                }
        })->with('userdetails.team.sports')->paginate(15);

        if (\Request::ajax()) {
            if (\Request::wantsJson()) {
                return ['error' => 'Json response is not set'];
            } else {
                return view('organization.members.partials.member_list', compact('id','members','filter_team'));
            }
        }

        $orgInfo= [$organization->toArray()];

        return view('organization.members.list',
            compact('id', 'organization', 'staffList', 'members','filter_team','orgInfo')
        );
    }

}
