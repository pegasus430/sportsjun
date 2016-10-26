<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;


class BaseApiController extends Controller
{
    function applyFilter($query,$fields =[]){
        $data = \Request::all();
        foreach ($fields as $field){
            if (isset($data[$field])){
                $query->where($field,$data[$field]);
            }
        }
        return $query;
    }


    function ApiResponse($response,$code = 200){
        if (!$response && !is_array($response)) {
            $code = 404;
            $response = 'Not found';
        }

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

    function PaginatedMapResponse(LengthAwarePaginator $paginator,$map){
        $data = [];
        foreach ($paginator as $item) {
            $item_data =[];
            foreach ($map as $key => $mapped){
                if (!is_integer($key))
                    $item_data[$key]= object_get($item,$mapped);
                else
                    $item_data[$mapped] = object_get($item,$mapped);
            }
            $data[]=$item_data;
        }

        $result = new LengthAwarePaginator($data, $paginator->total(), $paginator->perPage(),
            $paginator->currentPage(), ['path' => \Request::url(), 'query' => \Request::query()]);

        return $this->ApiResponse($result);
    }


}