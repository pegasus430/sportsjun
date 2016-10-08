<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateFacilityRequest extends Request
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
           
           'name' => 'required|max:50'.config('constants.VALIDATION.CHARACTERSANDSPACE'),
		    'email' => 'email',
			// 'facility_type[]' => 'required',
			//  'facility_service[]' => 'required',
			//'contact_number' =>  'required|numeric|min:10',
             'contact_number' =>  'required|'.config('constants.VALIDATION.PHONE'),
            //'contact_number' => ['required', 'regex:/^(?=.{3,16}$)([0-9\s\-\+\(\)]*)$/'],
			'alternate_contact_number' =>  config('constants.VALIDATION.PHONE'),
			'state_id' => 'required',
			'city_id' => 'required',
			 //'zip' => 'required|numeric',
           'zip' => 'required|alpha_num|max:12',
			
		 
			
        ];
    }
}
