<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GenerateMatchRequest extends Request {

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
        $validationArray = [ 
            'auto_start_time' => 'required|date_format:'.config('constants.DATE_FORMAT.VALIDATION_TIME_FORMAT'),
            'auto_end_time' => 'required|date_format:'.config('constants.DATE_FORMAT.VALIDATION_TIME_FORMAT'),
            'noofplaces' => 'required|numeric',			
            'matchperday' => 'required|numeric',			
            'minutespermatch' => 'required|numeric',			
            'breakeachmatch' => 'required|numeric',
            'roundofplay' => 'numeric'
        ];
       
       return $validationArray;
        
    } 
}