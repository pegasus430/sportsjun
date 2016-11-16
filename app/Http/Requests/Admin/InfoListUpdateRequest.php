<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class InfoListUpdateRequest extends Request {

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
        $type= $this->infolist->type;
        $fields = [
            'name'=> 'required',
            'image'=> 'file|mimes:jpeg,bmp,png',
            'weight'=> 'numeric'
        ];
        if ($type == 'testimonials'){
            $fields['description']='required';
            $fields['data']='array';
            $fields['data.date']='required|date_format:"Y-m-d H:i"';
        }
        if ($type == 'banners'){
            $fields['data']= 'array';

        }
        return $fields;
    }
/*
    public function messages() {
        return [
        ];
    }
*/
}
