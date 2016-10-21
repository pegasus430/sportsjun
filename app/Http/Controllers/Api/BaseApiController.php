<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class BaseApiController extends Controller
{
    function applyFilter($query,$fields =[]){
        $data = \Request::all();
        foreach ($fields as $field){
            if (in_array($field,$data)){
                $query->where($field,$data[$field]);
            }
        }
        return $query;
    }


    function ApiResponse($response,$code = 200){
        if ($code>=200 && $code<400)
            $status = 'Success';
        else
            $status = 'Failure';

        if ($response instanceof LengthAwarePaginator)
            $response = $response->toArray();


        return [
            'status'=>$status,
            'code'=>$code,
            'response'=>$response,
        ];

    }

}