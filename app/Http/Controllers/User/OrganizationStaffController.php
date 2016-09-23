<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Services\OrganizationStaffService;
use App\Model\Organization;
use App\Model\OrganizationRole;
use App\User;
use Illuminate\Http\Request;

class OrganizationStaffController extends Controller
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

        $staffList = $organization->staff;

        $staffRoles = OrganizationRole::lists('name', 'id')->all();
        $orgInfo= Organization::select()->where('id',$id)->get()->toArray();
        return view('organization.staff.list',
            compact('id', 'staffList', 'staffRoles', 'orgInfo'));
    }

    /**
     * Add Staff to organization
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @param \App\Http\Services\OrganizationStaffService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        $id,
        OrganizationStaffService $service
    ) {
        $this->validate($request, [
            'name' => 'required|string',
            'email'      => 'email',
            'staff_role' => 'required|exists:organization_roles,id',
        ]);
        $organization = Organization::findOrFail($id);
        $user = $service->addStaff($request, $organization);
        $redirect = redirect()->route('organization.staff', [$id]);

        if ($user instanceof User) {
            return $redirect->with('status',
                trans('message.staff.added_message'));
        } else {
            switch($user){
                case User::$USER_EXISTS:
                    return $redirect->with('alert',
                        trans('message.staff.already_exists_message'));
                case USER::$USER_EMAIL_REQUIRED:
                    return $redirect->with('alert',
                        trans('message.staff.user_create_email_required'));
            }
        }

        return $redirect;
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
