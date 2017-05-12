<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\OrganizationController; 
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
    	return view('organization_2.coaching.create_session');
    }
}
