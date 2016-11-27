<?php

namespace App\Http\Requests\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

abstract class BaseApiRequest extends FormRequest
{
    //https://laracasts.com/discuss/channels/general-discussion/laravel-5-modify-input-before-validation?page=2
    //https://gist.github.com/drakakisgeo/3bba2a2600b4c554f836
    public function all()
    {
        $attributes = parent::all();
        return array_map(array($this, 'sanitizeInput'), $attributes);
        //return $attributes;
    }

    public function sanitizeInput($var)
    {
        return is_string($var) ? trim($var) : $var;
    }

    public function response(array $errors)
    {
        $key = array_keys($errors)[0];

        $error = $key . ' ' . $errors[$key][0];

        return new JsonResponse(
            [
                'status' => 'Failure',
                'code' => 500,
                'response' => ['error' => $error],
            ]
            , 200);

    }


}
