<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\MatchSchedule;
use App\Model\Organization;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\TournamentParent;
use App\Model\Tournaments;
use App\User;


class OrganizationSchedulesController extends Controller
{
    public function tournamentList($id){
        $term = \Request::has('term') ? filter_var(\Request::get('term'), FILTER_SANITIZE_STRING) : false;

        $tournamentsQuery = Tournaments::whereHas('tournamentParent', function ($query) use ($id) {
            $query->where('organization_id', $id);
        });

        if ($term){
            $tournamentsQuery->where('name','LIKE','%'.$term.'%');
        }
        return $tournamentsQuery->orderBy('name','ASC')->get()->unique('name')->lists('name','id');
    }


    public function index($id)
    {
        $organization = Organization::with('staff')->findOrFail($id);
        $staffList = $organization->staff->pluck('name', 'id');

        #$tournament_parents = TournamentParent::where('organization_id', $id)->get();
        #$tournament_parent_ids = $tournament_parents->lists('id');

        $filter_event = \Request::has('filter-event') ? filter_var(\Request::get('filter-event'),
            FILTER_SANITIZE_STRING) : null;

        $tournamentsQuery = Tournaments::whereHas('tournamentParent', function ($query) use ($id) {
            $query->where('organization_id', $id);
        })
            ->with([
                'tournamentParent',
                'matches' => function ($query) {
                    $query->orderBy('match_start_date', 'match_start_time');
                },
                'matches.sport'
            ]);

        $teamNames = Team::lists('name','id');
        $userNames = User::lists('name','id');

        if($filter_event){
            $tournamentsQuery->where('name','LIKE','%'.$filter_event.'%');
        }

        $tournaments = $tournamentsQuery->paginate(5);


        /*
        $schedules = MatchSchedule::whereHas('tournament', function ($query) use ($id, $tournament_parent_ids) {
            $query->whereIn('tournament_parent_id', $tournament_parent_ids);
        })->with(['tournament', 'scheduleteamone', 'scheduleteamtwo', 'scheduleuserone', 'scheduleusertwo'])
            ->orderBy('match_start_date', 'match_start_time')
            ->get();
        */




        if (\Request::ajax()) {
            if (\Request::wantsJson()) {
                return ['error' => 'Json response is not set'];
            } else {
                return view('organization.schedules.partials.schedule_list',
                    compact('id', 'organization', 'staffList', 'tournaments', 'filter_event','teamNames','userNames'));
            }
        }

        return view('organization.schedules.list',
            compact('id', 'organization', 'staffList', 'tournaments', 'filter_event','teamNames','userNames'),
            ['orgInfoObj'=>$organization]
        );
    }

}
