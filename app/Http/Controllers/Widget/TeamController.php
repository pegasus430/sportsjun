<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;

class TeamController extends Controller
{

    public function show($id,$status = null){
        \View::share('is_widget',true);
        $controller=  new \App\Http\Controllers\User\TeamController();
        return $controller->myteam($id,$status);
    }


}
