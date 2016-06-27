<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreateOrganizationGroupRequest;
use App\Http\Services\OrganizationGroupService;
use App\Model\Organization;
use Illuminate\Http\Request;

class OrganizationGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $organization = Organization::findOrFail($id);

        $staffList = $organization->staff->pluck('email', 'id');

        $groups = $organization->groups;
        $groups->load('manager');

        return view('organization.groups.list',
            compact('id', 'staffList', 'groups'));
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
    public function edit($id)
    {
        //
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
}
