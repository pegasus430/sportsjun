<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Organization;
use App\Model\TeamPlayers;
use App\User;


class OrganizationMembersController extends Controller
{
    public function teamList($id)
    {
        $term = \Request::has('term') ? filter_var(\Request::get('term'), FILTER_SANITIZE_STRING) : false;
        $organization = Organization::with('staff')->findOrFail($id);
        $allTeams = $organization->teamplayers();
        if ($term) {
            $allTeams->where('name', 'LIKE', '%' . $term . '%');
        }
        return $allTeams->orderBy('name', 'ASC')->get()->unique('name')->lists('name', 'id');
    }

    public function index($id)
    {
        $authUserId = \Auth::guest() ? false : \Auth::user()->id;
        $organization = Organization::with('staff')->findOrFail($id);

        $staffList = $organization->staff->pluck('name', 'id');
        $allTeams = $organization->teamplayers()->get();

        $teamIds = $allTeams->lists('id');
        #$teamPlayers = TeamPlayers::whereIn('team_players.team_id',$teamIds)->get();

        $filter_team = \Request::has('filter-team') ? filter_var(\Request::get('filter-team'),
            FILTER_SANITIZE_STRING) : null;

        $members = User::whereHas('userdetails', function ($query) use ($teamIds, $id, $filter_team) {
            $query->whereIn('team_id', $teamIds)
                ->whereNotIn('role', ['owner', 'manager'])
                ->join('teams', 'teams.id', '=', 'team_players.team_id');
            if ($filter_team) {
                $query->where('teams.name', 'LIKE', '%' . $filter_team . '%');
            }
        })->with([
            'userdetails' => function ($query) use ($id) {
                $query->with(['team' => function ($query) use ($id) {
                    $query->where('organization_id', $id);
                }]);

                $query->whereNotIn('role', ['owner', 'manager']);
            },
            'userdetails.team.sports'
        ]);
        if ($authUserId) {
            $members->leftJoin('ratings', function ($join) use ($id) {
                return $join->on('ratings.to_id', '=', 'users.id')
                    ->where('ratings.user_id', '=', \Auth::user()->id)
                    ->where('ratings.type', '=', \App\Model\Rating::$RATE_USER);
            })->select('users.*', 'ratings.rate');
        } else {
            $members ->select('users.*',\DB::raw('0 as `rate`'));
        }
        $members = $members
            ->orderBy('rate', 'desc')
            ->paginate(15);

        if (\Request::ajax()) {
            if (\Request::wantsJson()) {
                return ['error' => 'Json response is not set'];
            } else {
                return view('organization.members.partials.member_list', compact('id', 'members', 'filter_team'));
            }
        }


        return view('organization.members.list',
            compact('id', 'organization', 'staffList', 'members', 'filter_team'),
            ['orgInfoObj' => $organization]
        );
    }

}
