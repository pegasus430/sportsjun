<?php

namespace App\Http\Controllers;


use App\Model\City;
use App\Model\Country;
use App\Model\State;

class DataController extends Controller
{
    public function countries(){
        return Country::select(['id','sortname','country_name'])
            ->lists('country_name','id');
    }

    public function states(){
       return State::select(['id','state_name','country_id'])->where('country_id',\Request::get('selected',""))
           ->lists('state_name','id');
    }


    public function cities(){
        return City::select(['id','city_name','state_id'])->where('state_id',\Request::get('selected',""))
            ->lists('city_name','id');
    }

    public function token(){
        return csrf_token();
    }

}