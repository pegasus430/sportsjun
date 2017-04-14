<?php

namespace App\Http\Controllers\Api;

use App\Model\City;
use App\Model\Country;
use App\Model\State;

class  DataApiController extends BaseApiController
{
    /**
     * Cities - list
     * @return array
     */
    public function cities(){
        $cities = $this
                    ->applyFilter(City::select(['id','city_name','state_id']),['city_name','state_id'])
                    ->get();
        return self::ApiResponse($cities);
    }

    /**
     * Countries - list
     * @return array
     */

    public function countries(){
        $countries =  $this
                        ->applyFilter(Country::select(['id','sortname','country_name']),['sortname','country_name'])
                        ->get();
        return self::ApiResponse($countries);
    }

    /**
     * States - list
     * @return array
     */

    public function states(){
        $states =  $this
            ->applyFilter(State::select(['id','state_name','country_id']),['state_name','country_id'])
            ->get();
        return self::ApiResponse($states);
    }
}