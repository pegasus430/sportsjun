<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class OrganizationRegisterRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'org_name' => 'required',
            'org_type' => 'required',
            'org_logo' => 'required|file',
            'about' => 'required',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|unique:users,email|email|max:255',
            'password' => 'required|min:6|confirmed',
            'address' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'terms_accept' => 'required',
            'captcha' => 'required|captcha'
        ];
    }


}
