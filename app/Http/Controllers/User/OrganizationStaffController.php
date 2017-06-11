<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Services\OrganizationStaffService;
use App\Model\Organization;
use App\Model\OrganizationRole;
use App\User;
use Auth;
use Illuminate\Http\Request; 
use App\Model\BasicSettings;
use Illuminate\Http\Request as ObjRequest;

class OrganizationStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */

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
        $organization = Organization::findOrFail($id);

        $staffList = $organization->staff;

        $staffRoles = OrganizationRole::lists('name', 'id')->all();
        $orgInfoObj = $organization;

        if($this->is_owner){        
            return view('organization_2.staff.list',compact('id', 'staffList', 'staffRoles', 'orgInfoObj'));
        }

        return view('organization.staff.list',
            compact('id', 'staffList', 'staffRoles', 'orgInfoObj'));
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
