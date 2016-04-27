<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateOrganizatonRequest extends Request
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
           
            'name' => 'required|max:50|'.config('constants.VALIDATION.CHARACTERSANDSPACE'),
		    'email' => 'required|email',
			'contact_number' =>  'required|'.config('constants.VALIDATION.PHONE'),
            'alternate_contact_number' =>  config('constants.VALIDATION.PHONE'),
			'contact_name' => 'required',
			'state_id' => 'required',			
			'city_id' => 'required',
			'zip' => ['required', config('constants.VALIDATION.ZIPCODE')],
		    'organization_type' => 'required',	
        ];
    }
}
