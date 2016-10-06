<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;



class BaseApiController extends Controller
{
    function ApiResponse($response,$code = 200){
        if ($code>=200 && $code<400)
            $status = 'Success';
        else
            $status = 'Failure';

        return [
            'status'=>$status,
            'code'=>$code,
            'response'=>$response,
        ];
    }

}