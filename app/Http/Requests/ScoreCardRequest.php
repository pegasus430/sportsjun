<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ScoreCardRequest extends Request {

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
		return [
      'name' => 'required',
      'email' =>'email|unique:users,email'
        ];
    }

    public function messages() {
        return [
          'name.required' => trans('validation.required'),
          'email.required' => trans('validation.email'),
         
        ];
    }

}
