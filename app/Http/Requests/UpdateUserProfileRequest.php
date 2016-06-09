<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateUserProfileRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        //return false; //Add validation for created users only.
        /*
          $article = $this->route()->parameter('article');
          return $this->user()->id == $article->user_id;
         */
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
		$user_id = Request::segment(2);
		if(Request::segment(2)=="edituser")
		{
			$user_id = Request::get('modify');
		}else if(Request::segment(2)=="createuser")
		{
			$user_id = '';
		}
		return [
            'firstname' => 'required|max:50|'.config('constants.VALIDATION.CHARACTERSANDSPACE'),
			'lastname' => 'required|max:50|'.config('constants.VALIDATION.CHARACTERSANDSPACE'),
			//'role' => 'required',
			'country_id' => 'required',
			'state_id' => 'required',
			'city_id' => 'required',
			'zip' => 'required|alpha_num|max:12',
//            'dob' => 'date',
            'dob' => 'date_format:'.config('constants.DATE_FORMAT.VALIDATION_DATE_FORMAT'),
            'image' => 'mimes:png,gif,jpeg,jpg',
			// 'email' => 'email|unique:users,email,'.Request::get('id')
			'email' =>'required|email|unique:users,email,'.$user_id,
            'contact_number' => 'required|'.config('constants.VALIDATION.PHONE')			
			];
    }

    public function messages() {
        return [
            'firstname.required' => trans('validation.required'),
            'firstname.max' => trans('validation.max.numeric'),
			'lastname.required' => trans('validation.required'),
            'lastname.max' => trans('validation.max.numeric'),
            'dob.date' => trans('validation.date'),
            'zip.numeric' => trans('validation.numeric'),
			'state_id.required' =>trans('validation.required'),
		    'city_id.required' =>trans('validation.required'),
		    'zip.required' =>trans('validation.required'),
        ];
    }

}
