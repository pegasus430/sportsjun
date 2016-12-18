<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\SportController;
use App\Model\Organization;

class AlbumController extends Controller
{

    public function Formgallery($action_id,$action)
    {
        switch ($action){
            case 'formorganization':
                    $organization = Organization::findOrFail($action_id);
                    \View::share('orgInfoObj',$organization);
                break;
        }
        \View::share('is_widget',true);
        $controller=  new \App\Http\Controllers\User\AlbumController();
        return $controller->Formgallery($action_id,$action);
    }

    public function createphoto($album_id,$user_id,$is_user_profile='',$action='',$action_id='',$type='',$fromPublic=false){
        switch ($action){
            case 'organization':
                $organization = Organization::findOrFail($action_id);
                \View::share('orgInfoObj',$organization);
                break;
        }
        \View::share('is_widget',true);
        $controller=  new \App\Http\Controllers\User\AlbumController();
        return $controller->createphoto($album_id,$user_id,$is_user_profile,$action,$action_id,$type,$fromPublic);

    }

}
