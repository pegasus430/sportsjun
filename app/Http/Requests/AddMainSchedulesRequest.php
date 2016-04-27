<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddMainSchedulesRequest extends Request {

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
      'main_sports_id' => 'required',
      'main_scheduletype' => 'required',
      'main_myteam' => 'required',
      'main_oppteam' => 'required',
      'main_match_start_date' => 'required|date_format:'.config('constants.DATE_FORMAT.VALIDATION_DATE_FORMAT'),
      'main_match_start_time' => 'date_format:'.config('constants.DATE_FORMAT.VALIDATION_TIME_FORMAT'),
      // 'start_time' => 'required|',
      //'start_time' => 'required|date_format:'.config('constants.DATE_FORMAT.VALIDATION_DATE_TIME_FORMAT'),
      //'end_time' => 'date_format:'.config('constants.DATE_FORMAT.VALIDATION_DATE_TIME_FORMAT'),
      'main_venue' => 'required|max:100',			
      'address' => 'max:100',     
      'state_id' => 'required|numeric',     
      'city_id' => 'required|numeric',    
      'zip' => ['required', config('constants.VALIDATION.ZIPCODE')],
      'main_player_type' => 'required',
      'main_match_type' => 'required'
        ];
    }

    public function messages() {
        return [
          'main_sports_id.required' => trans('validation.required'),
          'main_scheduletype.required' => trans('validation.required'),
          'main_myteam.required' => trans('validation.required'),
          // 'myteam.max' => trans('validation.required'),
          'main_oppteam.required' => trans('validation.required'),
          // 'oppteam.max' => trans('validation.required'),
          'main_start_time.required' => trans('validation.required'),
          'main_start_time.date' => trans('validation.date'),
          'main_end_time.date' => trans('validation.date'),
          'main_address.max' => trans('validation.max.numeric'),
          'main_venue.required' => trans('validation.required'),
          'main_venue.max' => trans('validation.max.numeric'),			
          'state_id.required' => trans('validation.required'),
          'city_id.required' => trans('validation.required'),
          'zip.required' => trans('validation.required'),
          'main_player_type.required' => trans('validation.required'),			
          'main_match_type.required' => trans('validation.required'),
        ];
    }

}
