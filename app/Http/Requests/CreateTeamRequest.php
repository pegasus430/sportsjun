<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateTeamRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'image' => 'mimes:png,gif,jpeg,jpg',
            'sports_id' => 'required',
            //'name' => 'required|max:50|alpha_num',//.config('constants.VALIDATION.CHARACTERSANDSPACE'),
            'name' => 'required|max:50|'.config('constants.VALIDATION.CHARACTERSANDSPACE'),
			//'organization_id' => 'required',
            'state_id' => 'required',
			'city_id' => 'required',
            'zip' => 'required|alpha_num|max:12',
            'gender'=>'required',
        ];
    }
}
