<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreateOrganizationGroupRequest;
use App\Http\Services\OrganizationGroupService;
use App\Model\Organization;
use App\Model\Team;
use Illuminate\Http\Request;
use App\Model\OrganizationGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request as ObjRequest;
use App\Model\BasicSettings;

class OrganizationGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    private $uploadPath = 'uploads/org/groups/logo';

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

        view()->share('is_owner', $this->is_owner);     
    }

    public function index($id)
    {
        $organization = Organization::with('staff')->findOrFail($id);

        $staffList = $organization->staff->pluck('name', 'id');

        $groups = $organization->groups;

        $groups->load('manager', 'teams');
        $orgInfoObj = Organization::find($id);

        $user_id = (isset(Auth::user()->id)?Auth::user()->id:0);
        $teams = Team::whereDoesntHave('organizationGroups')
                    ->join('users', 'users.id', '=', 'teams.team_owner_id')
                    ->where('teams.organization_id',$id)
                    ->select('teams.id','teams.name as teamname','teams.team_owner_id','teams.logo','teams.description','users.name','teams.isactive', 'teams.sports_id')
                    ->orderBy('isactive','desc')->get();

        if($this->is_owner){        
             return view('organization_2.groups.list',
            compact('id', 'staffList', 'groups', 'organization', 'orgInfoObj','teams'),['userId'=>$user_id]);

        }

        return view('organization.groups.list',
            compact('id', 'staffList', 'groups', 'organization', 'orgInfoObj','teams'),['userId'=>$user_id]);

    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @param \App\Http\Requests\CreateOrganizationGroupRequest $request
     * @param $id
     * @param \App\Http\Services\OrganizationGroupService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function store(
        CreateOrganizationGroupRequest $request,
        $id,
        OrganizationGroupService $service
    ) {
        $organization = Organization::findOrFail($id);

        $group = $service->createGroup($request, $organization);
        
        return redirect()
            ->route('organization.groups.list', $id)
            ->with('status', 'Group created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
        
        $orgGroup=OrganizationGroup::findOrFail($request->group_id);
        $orgGroup->manager_id=$request->manager_id;
        $orgGroup->name = $request->name;


        if($request->hasFile('logo')){
             $file = $request->file('logo');
        
            $orgGroup->logo = $this->generateUniqueName();
           
            $request->file('logo')
                    ->move(public_path($this->uploadPath), $orgGroup->logo);

        }
        $orgGroup->save();

       
        return redirect()->back();


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function generateUniqueName()
    {
        return md5(microtime(true)) . '.png';
    }
}
