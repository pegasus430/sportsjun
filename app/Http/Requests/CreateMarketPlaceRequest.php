<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateMarketPlaceRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
    public function rules()
    {
        return [
            'marketplace_category_id' => 'required',
			'item_type'=> 'required',
            'item' => 'required|max:100'.config('constants.VALIDATION.CHARACTERSANDSPACE'),
            'contact_number' =>  'required|'.config('constants.VALIDATION.PHONEMP'),
            'actual_price' => 'required|numeric',    
			'base_price' => 'numeric',    
            'state_id' => 'required',
			'city_id' => 'required',
			 'zip' => ['required', config('constants.VALIDATION.ZIPCODE')],	
            // 'files[]'=>'required',
           // 'filelist_photos'=>'required',		   
        ];
    }
}


