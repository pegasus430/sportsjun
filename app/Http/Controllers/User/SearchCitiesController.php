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
        $user = Auth::user();
        $term = $request->input('term');

        if (is_null($user->state_id)) {
            return null;
        }

        $cities = City::where('state_id', $user->state_id)
                      ->where('city_name', 'LIKE', "%{$term}%")->take(15)->get();

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
        ];
    }
}
