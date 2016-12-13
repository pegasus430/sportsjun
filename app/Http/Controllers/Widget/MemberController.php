<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\SportController;

class MemberController extends Controller
{

    public function show($id){
        \View::share('is_widget',true);
        $controller=  new SportController();
        return $controller->showSportProfile($id);
    }


}
