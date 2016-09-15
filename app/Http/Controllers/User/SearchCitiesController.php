<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\City;
use Auth;
use Illuminate\Http\Request;

class SearchCitiesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function search(Request $request)
    {
        $term = $request->input('term');

        $cities =
            City::with('state.country')->where('city_name', 'LIKE', "{$term}%")
                ->take(15)
                ->get();

        return response()->json($this->transformCities($cities));

    }

    /**
     * @param $cities
     *
     * @return array
     */
    private function transformCities($cities)
    {
        $result = [];

        foreach ($cities as $city) {
            $result[] = $this->transform($city);
        }

        return $result;
    }

    /**
     * @param $city
     *
     * @return array
     */
    private function transform($city)
    {
        return [
            'id'    => $city->id,
            'label' => $city->city_name,
            'desc'  => "{$city->state->state_name}, {$city->state->country->country_name}",
        ];
    }
}
