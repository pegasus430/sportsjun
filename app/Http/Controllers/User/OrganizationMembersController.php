<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Organization;
use App\Model\TeamPlayers;
use App\Model\City;
use App\Model\Team;
use App\Model\Country;
use App\Model\State;
use App\User;
use App\Model\Child;
use App\Model\Parents;
use Auth;
use Illuminate\Http\Request as ObjRequest;
use App\Model\BasicSettings;
use App\Http\Controllers\User\InvitePlayerController;
use App\Helpers\Helper;
use App\Helpers\SendMail;


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


           $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
           $states = State::where('country_id', $organization->country_id)->orderBy('state_name')->lists('state_name', 'id')->all();
           $cities = City::where('state_id',  $organization->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();

        if (\Request::ajax()) {
            if (\Request::wantsJson()) {
                return ['error' => 'Json response is not set'];
            } else {

                if($this->is_owner){
                    return view('organization_2.members.partials.member_list', compact('id', 'members', 'filter_team','cities','states','countries'));
                }
                return view('organization.members.partials.member_list', compact('id', 'members','cities','states','countries' ,'filter_team'));
            }
        }

        if($this->is_owner){
             return view('organization_2.members.list',
            compact('id', 'organization', 'staffList', 'members', 'filter_team','cities','states','countries'),
            ['orgInfoObj' => $organization]
        );
        }
        return view('organization.members.list',
            compact('id', 'organization', 'staffList', 'members', 'filter_team','cities','states','countries'),
            ['orgInfoObj' => $organization]
        );
    }

    public function save_player(objrequest $request){
     //   return $request->all();

        $teamid = $request->team_id;
        $teamname=Team::select('name')->where('id',$teamid)->get()->toArray();

        $user = User::where(['email'=>$request->email])->first();
        if(!$user){
             $generatedPassword= str_random(6);
                $password=  bcrypt( $generatedPassword);
            $user = new user;
            $user->email = $request->email;
            $user->name = $request->name;
            $user->country_id = $request->country_id;
            $user->state_id = $request->state_id;
            $user->city_id     = $request->city_id;
            $user->password = $request->password;

            $user->save();

                 $to_user_id = $user->id;
                $to_email_id=  $user->email;
                $user_name = $user->name;
                $subject =  trans('message.inviteplayer.subject');
                $view_data = array('email'=>$to_email_id,'password'=>$generatedPassword ,'user_name'=>$user_name,'team_name'=>$teamname[0]['name']);
                $view = 'emails.invitePlayers';
                $data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>  $to_user_id,'flag'=>'user','send_flag'=>1);
                SendMail::sendmail($data);

        }

        $player = TeamPlayers::where(['team_id'=>$teamid,'user_id'=>$user->id])->first();

            if (!$player) {
                $player = new TeamPlayers([
                    'team_id'=>$teamid,
                    'user_id'=>$user->id
                ]);
            }
            
            if($player->save())
            {
               // return $this->getTeamPlayersDiv($request_array = array('team_id'=>$teamid));
               $last_inserted_player_id =  $player->id;
            }
            
            //insert players in match schedule table if match is scheduled on that team
            $insertTeamPlayers = Helper::insertTeamPlayersInSchedules($teamid,$user->id);
            



        $parent = User::where(['email'=>$request->parent_email])->first();
        if(!$parent){
             $generatedPassword= str_random(6);
              $password=  bcrypt( $generatedPassword);

            $parent = new user; 
            $parent->email = $request->parent_email;
            $parent->name = $request->name;
            $parent->country_id = $request->country_id;
            $parent->state_id = $request->state_id;
            $parent->city_id     = $request->city_id;
            $parent->password = $password;
            $parent->save();

                 $to_user_id = $parent->id;
                $to_email_id=  $parent->email;
                $user_name = $parent->name;
                $subject =  trans('message.inviteplayer.subject');
                $view_data = array('email'=>$to_email_id,'password'=>$generatedPassword ,'user_name'=>$user_name,'team_name'=>$teamname[0]['name']);
                $view = 'emails.invitePlayers';
                $data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>  $to_user_id,'flag'=>'user','send_flag'=>1);
                SendMail::sendmail($data);
        }

        $child = new child; 
        $child->child_id   = $user->id;
        $child->parent_id = $parent->id; 
        $child->organization_id = $request->organization_id;
        $child->save();

        return redirect()->back()->with('message', 'Player Added');




    }

}
