<?php

namespace App\Http\Controllers\Widget;


use App\Http\Controllers\Controller;

use App\Http\Controllers\User\OrganizationGroupsController;
use App\Http\Controllers\User\OrganizationMembersController;
use App\Http\Controllers\User\OrganizationSchedulesController;
use App\Http\Controllers\User\OrganizationStaffController;
use App\Http\Controllers\User\TeamController;

class OrganizationController extends Controller
{

    public function show($id){
        \View::share('is_widget',true);
        $controller=  new TeamController();
        return $controller->getorgDetails($id);
    }

    public function staff($id){
        \View::share('is_widget',true);
        $controller=  new OrganizationStaffController();
        return $controller->index($id);
    }
    public function groups($id){
        \View::share('is_widget',true);
        $controller=  new OrganizationGroupsController();
        return $controller->index($id);
    }
    public function members($id){
        \View::share('is_widget',true);
        $controller=  new OrganizationMembersController();
        return $controller->index($id);
    }
    public function tournaments($id){
        \View::share('is_widget',true);
        $controller=  new \App\Http\Controllers\User\OrganizationController();
        return $controller->organizationTournaments($id);
    }
    public function schedule($id){
        \View::share('is_widget',true);
        $controller=  new OrganizationSchedulesController();
        return $controller->index($id);
    }
    public function gallery($id){
        \View::share('is_widget',true);
        $controller=  new  App\Http\Controllers\User\AlbumController();
        return $controller->show('organization',0,$id);
    }


    public function teams($id,$group_id = false){
        \View::share('is_widget',true);
        $controller=  new TeamController();
        return $controller->organizationTeamlist($id,$group_id);
    }


    public function profile(){

    }

}
