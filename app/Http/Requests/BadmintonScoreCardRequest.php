<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BadmintonScoreCardRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'set_end_point' => 'numberic|min:0'
        ];
    }
}
