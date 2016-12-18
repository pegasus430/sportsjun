<?php

namespace App\Http\Controllers\Api;

use App\Model\City;
use App\Model\Country;
use App\Model\State;

class  DataApiController extends BaseApiController
{
    public function cities(){
        $cities = $this
                    ->applyFilter(City::select(['id','city_name','state_id']),['city_name','state_id'])
                    ->get();
        return $this->ApiResponse($cities);
    }

    public function countries(){
        $countries =  $this
                        ->applyFilter(Country::select(['id','sortname','country_name']),['sortname','country_name'])
                        ->get();
        return $this->ApiResponse($countries);
    }

    public function states(){
        $states =  $this
            ->applyFilter(State::select(['id','state_name','country_id']),['state_name','country_id'])
            ->get();
        return $this->ApiResponse($states);
    }
}