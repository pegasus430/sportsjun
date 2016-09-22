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

    public function index($id)
    {
        $organization = Organization::findOrFail($id);

        $staffList = $organization->staff()->get()->unique('email')->pluck('userNameEmail', 'id');

        $groups = $organization->groups;

        $groups->load('manager', 'teams');
        $orgInfo = Organization::select()->where('id', $id)->get()->toArray();

        $user_id = (isset(Auth::user()->id)?Auth::user()->id:0);
        $teams = Team::whereDoesntHave('organizationGroups')
                    ->join('users', 'users.id', '=', 'teams.team_owner_id')
                    ->where('teams.organization_id',$id)
                    ->select('teams.id','teams.name as teamname','teams.team_owner_id','teams.logo','teams.description','users.name','teams.isactive', 'teams.sports_id')
                    ->orderBy('isactive','desc')->get();

        return view('organization.groups.list',
            compact('id', 'staffList', 'groups', 'organization', 'orgInfo','teams'),['userId'=>$user_id]);

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

        if($request->has('logo')){
             $file = $request->file('logo');
        
            $orgGroup->logo = $this->generateUniqueName();
           
            $request->file('logo')
                    ->move(public_path($this->uploadPath), $group->logo);
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
