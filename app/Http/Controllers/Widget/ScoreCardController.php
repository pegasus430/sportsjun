<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;

class ScoreCardController extends Controller
{

    public function createScorecardView($id){
        \View::share('is_widget',true);
        $controller=  new \App\Http\Controllers\User\ScoreCardController();
        return $controller->createScorecardView($id);
    }


}
