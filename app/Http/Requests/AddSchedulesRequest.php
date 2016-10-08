<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddSchedulesRequest extends Request {

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
        $tournamentid = Request::get('tournament_id');
        $validationArray = [
      'scheduletype' => 'required',
      'myteam' => 'required',
      'oppteam' => 'required',
      'match_start_date' => 'required|date_format:'.config('constants.DATE_FORMAT.VALIDATION_DATE_FORMAT'),
      'match_start_time' => 'date_format:'.config('constants.DATE_FORMAT.VALIDATION_TIME_FORMAT'),
      // 'start_time' => 'required|',
      //'start_time' => 'required|date_format:'.config('constants.DATE_FORMAT.VALIDATION_DATE_TIME_FORMAT'),
      //'end_time' => 'date_format:'.config('constants.DATE_FORMAT.VALIDATION_DATE_TIME_FORMAT'),
      'venue' => 'required|max:100',			
      'address' => 'max:100',			
      'state_id' => 'required|numeric',			
      'city_id' => 'required|numeric',
      'zip' => 'required|alpha_num|max:12',
//      'number_of_games'=>'required|numeric|min:0',
//      'player_type' => 'required',
//      'match_type' => 'required'
        ];
       if(empty($tournamentid)) {
           $validationArray['player_type'] = 'required';
           $validationArray['match_type'] = 'required';
       } 
       
       return $validationArray;
        
    }

    public function messages() {
        return [
          'scheduletype.required' => trans('validation.required'),
          'myteam.required' => trans('validation.required'),
          // 'myteam.max' => trans('validation.required'),
          'oppteam.required' => trans('validation.required'),
          // 'oppteam.max' => trans('validation.required'),
          'start_time.required' => trans('validation.required'),
          'start_time.date' => trans('validation.date'),
          'end_time.date' => trans('validation.date'),
          'address.max' => trans('validation.max.numeric'),
          'venue.required' => trans('validation.required'),
          'venue.max' => trans('validation.max.numeric'),			
          'state_id.required' => trans('validation.required'),
          'city_id.required' => trans('validation.required'),
          'zip.required' => trans('validation.required'),
          'player_type.required' => trans('validation.required'),			
          'match_type.required' => trans('validation.required'),
        ];
    }

}
