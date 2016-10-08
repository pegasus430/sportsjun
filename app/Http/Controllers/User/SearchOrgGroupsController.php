<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\OrganizationGroup;
use Illuminate\Http\Request;
use Response;

class SearchOrgGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @internal param $orgId
     *
     */
    public function getGroupsList(Request $request)
    {
        $orgId = $request->input('orgId');

        $groups = OrganizationGroup::whereOrganizationId($orgId)
                                   ->get(['name', 'id'])
                                   ->toArray();

        return Response::json(!empty($groups) ? $groups : []);
    }
}
