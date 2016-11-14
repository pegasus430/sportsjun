<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class InfoListStoreRequest extends Request {

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
        $type= $this->type;
        $fields = [
            'name'=> 'required',
            'image'=> 'required|image|mimes:jpeg,bmp,png',
            'weight'=> 'required|numeric'
        ];
        if ($type == 'testimonials'){
            $fields['description']='required';
        }
        return $fields;
    }

        public function messages() {
            return [
                'name.required' => trans ('validation.required')
            ];
        }

}
