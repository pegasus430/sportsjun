<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\MatchSchedule;
use App\Model\Organization;
use App\Model\TeamPlayers;
use App\Model\TournamentParent;
use App\User;


class OrganizationSchedulesController extends Controller
{
    public function index($id){
        $organization = Organization::with('staff')->findOrFail($id);
        $staffList = $organization->staff->pluck('name', 'id');

        $tournament_parents = TournamentParent::where('organization_id',$id);
        $tournament_parent_ids = $tournament_parents->lists('id');


        $schedules = MatchSchedule::whereHas('tournament',function ($query) use($id,$tournament_parent_ids){
            $query->whereIn('tournament_parent_id',$tournament_parent_ids);
        })->with(['tournament','scheduleteamone','scheduleteamtwo','scheduleuserone','scheduleusertwo'])
            ->orderBy('match_start_date','match_start_time')
            ->get();

        return view('organization.schedules.list',
            compact('id','organization','staffList','schedules')
        );
    }

}
