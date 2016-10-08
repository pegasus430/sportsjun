<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateTournamentRequest extends Request
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
		$isParent = !empty(Request::get('isParent'))?Request::get('isParent'):'';
		//echo 'isParent:'.$isParent;die();
		if($isParent=='yes')
		{
			return [
				  //'name' => 'required|alpha_num',//.config('constants.VALIDATION.CHARACTERSANDSPACE'),
			'name' => 'required|max:50|'.config('constants.VALIDATION.CHARACTERSANDSPACE'),
				 // 'owner_name' => 'required',
				  'email' =>  'required|email',
				  'contact_number'  =>  'required|'.config('constants.VALIDATION.PHONE'),
				  'manager_id'  =>  'required',
			];
		}else
		{
			return [
				  //'name' => 'required|alpha_num',//.config('constants.VALIDATION.CHARACTERSANDSPACE'),
				'name' => 'required|max:50|'.config('constants.VALIDATION.CHARACTERSANDSPACE'),
				  'email' =>  'required|email',
				  'groups_number'=>'required|numeric|min:1',
				  'groups_teams'=>'required|numeric|min:2',
				   'state_id' => 'required',			
				  'city_id' => 'required',
				  'zip' => 'required|alpha_num|max:12',
				   'address' => 'required',	
				 // 'status' => 'required',	
				  'type'=>'required',
				  'sports_id'=>'required',
				  //'start_date'=>'required',
				  //'end_date'=>'required',
				  //'start_date'=> 'required|date_format:'.config('constants.DATE_FORMAT.PHP_DISPLAY_DATE_FORMAT'),
    			  //'end_date'=> 'required|date_format:'.config('constants.DATE_FORMAT.PHP_DISPLAY_DATE_FORMAT').'|after:start_date',
				  'match_type'=>'required',
				  'player_type'=>'required',
				//  'contact_number' => 'required|alpha_dash',
		 
				 'contact_number' =>  'required|'.config('constants.VALIDATION.PHONE'),

				  'alternate_contact_number' => config('constants.VALIDATION.PHONE'),
				   //'contact_name' => 'required',
				   'manager_id' => 'required',
				   'prize_money' => 'numeric|min:0',
				   'enrollment_fee'=>'numeric|min:0',
				   'points_win' => 'numeric|min:0',
				   'points_loose'=>'numeric|min:0',
			];
		}
       
    }
}
