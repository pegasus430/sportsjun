<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\OrganizationController; 
use App\Model\subscription_method;
class OrganizationCoachingController extends OrganizationController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   


    public function coaching_index(){
        return view('organization_2.coaching.index');
    }

    public function create_session(){
    	$types = config('constants.ENUM.SUBSCRIPTION_TYPE');
    	return view('organization_2.coaching.create_session', compact('types'));
    }

    public function store_session($id, Request $request){
        return $request->all();
    }
}
