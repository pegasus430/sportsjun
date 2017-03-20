<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Organization;
use App\Model\TeamPlayers;
use App\User;
use Auth;
use Illuminate\Http\Request as ObjRequest;
use App\Model\BasicSettings;


class OrganizationMembersController extends Controller
{


        public function __construct(ObjRequest $request){
          $id = $request->route()->parameter('id');
          $this->is_owner = false;
          $this->new_template = false;

          $allow_newtemplate_setting  = BasicSettings::where('name', 'organization_new_template')->first();


          if($allow_newtemplate_setting && $allow_newtemplate_setting->description=='1'){
             $this->new_template=true;
          }

        if($id && (Auth::user()->type==1 && count(Auth::user()->organizations))){

            if(Auth::user()->organizations[0]->id == $id && $this->new_template){
                 $this->is_owner = true;
                 $organization = Organization::find($id);
                 view()->share('organisation', $organization);
            }
            
        }    
    }

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
                $query->where('users.name', 'LIKE', '%' . $filter_team . '%');
                   //   ->orWhere('users.name', 'LIKE', '%' . $filter_team . '%');
            }
        })->with([
            'userdetails' => function ($query) use ($id) {
                $query->with(['team' => function ($query) use ($id) {
                    $query->where('organization_id', $id);
                }]);

                $query->whereNotIn('role', ['owner', 'manager']);
            },
            'userdetails.team.sports'
        ])->leftJoin('ratings' ,function ($join) use ($id) {
            return $join->on('ratings.to_id', '=', 'users.id')
                ->where('ratings.user_id','=', \Auth::user()->id)
                ->where('ratings.type','=', \App\Model\Rating::$RATE_USER);
        })
            ->select('users.*','ratings.rate')
            ->orderBy('rate','desc')
            ->paginate(15);

        if (\Request::ajax()) {
            if (\Request::wantsJson()) {
                return ['error' => 'Json response is not set'];
            } else {

                if($this->is_owner){
                    return view('organization_2.members.partials.member_list', compact('id', 'members', 'filter_team'));
                }
                return view('organization.members.partials.member_list', compact('id', 'members', 'filter_team'));
            }
        }

        if($this->is_owner){
             return view('organization_2.members.list',
            compact('id', 'organization', 'staffList', 'members', 'filter_team'),
            ['orgInfoObj' => $organization]
        );
        }
        return view('organization.members.list',
            compact('id', 'organization', 'staffList', 'members', 'filter_team'),
            ['orgInfoObj' => $organization]
        );
    }

}
