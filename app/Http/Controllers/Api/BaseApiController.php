<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class BaseApiController extends Controller
{
    static $COUNTER_KEY = 999;

    function applyFilter($query, $fields = [])
    {
        $data = \Request::all();
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $query->where($field, $data[$field]);
            }
        }
        return $query;
    }


    function ApiResponse($response, $code = 200)
    {
        if (!$response && !is_array($response)) {
            $code = 404;
            $response = 'Not found';
        }

        if ($code >= 200 && $code < 400) {
            $status = 'Success';
        } else {
            $status = 'Failure';
        }

        if ($response instanceof LengthAwarePaginator) {
            $response = $response->toArray();
        }


        return [
            'status' => $status,
            'code' => $code,
            'response' => $response,
        ];
    }

    function PaginatedMapResponse(LengthAwarePaginator $paginator, $map)
    {
        $data = [];
        foreach ($paginator as $item) {
            $item_data = [];
            foreach ($map as $key => $mapped) {
                if (is_string($mapped)) {
                    if (!is_integer($key)) {
                        $item_data[$key] = object_get($item, $mapped);
                    } else {
                        $item_data[$mapped] = object_get($item, $mapped);
                    }
                } else {
                    if (is_array($mapped)){
                        $item_data[$key] = $this->mappedExtract($mapped,$item,$item);
                    }
                }
            }
            $data[] = $item_data;
        }

        $result = new LengthAwarePaginator($data, $paginator->total(), $paginator->perPage(),
            $paginator->currentPage(), ['path' => \Request::url(), 'query' => \Request::query()]);

        return $this->ApiResponse($result);
    }

    function mappedExtract($mapped,$item,$base){
        $source = array_get ($mapped,'source');
        $type = array_get($mapped,'type');

        $data = object_get($item,$source);
        if ($data){
            switch($type) {
                case 'list':
                    $fields = $mapped['fields'];
                    if (is_callable($fields))
                        $fields = $fields($item);
                    $result = [];
                    $counter=1;
                    foreach ($data as $sub){
                        $item_data =[];
                        foreach ($fields as $key => $mapped) {
                            if (is_string($mapped)) {
                                if (!is_integer($key)) {
                                    $item_data[$key] = object_get($sub, $mapped);
                                } else {
                                    $item_data[$mapped] = object_get($sub, $mapped);
                                }
                            } else {
                                if ($mapped === self::$COUNTER_KEY) {
                                    $item_data[$key] = $counter;
                                    continue;
                                }
                                if (is_array($mapped)){
                                    $item_data[$key] = $this->mappedExtract($mapped,$sub,$base);
                                }
                            }
                        }
                        $result[]= $item_data;
                        $counter++;
                    }
                    return $result;
                    break;
                case 'value':
                    $value = $mapped['value'];
                    if (is_callable($value)){
                        $value= $value($item,$base);
                    }
                    return $value;
                default:
                    return [];
            }
        } else {
            return [];
        }

    }


    function CollectionMapResponse(Collection $collection, $map)
    {
        $data = [];
        foreach ($collection as $item) {
            $item_data = [];
            foreach ($map as $key => $mapped) {
                if (is_string($mapped)) {
                    if (!is_integer($key)) {
                        $item_data[$key] = object_get($item, $mapped);
                    } else {
                        $item_data[$mapped] = object_get($item, $mapped);
                    }
                } else {
                    if (is_array($mapped)){
                        $item_data[$key] = $this->mappedExtract($mapped,$item,$item);
                    }
                }

            }
            $data[] = $item_data;
        }

        return $this->ApiResponse($data);
    }

    function ModelMapResponse(Model $model, $map)
    {
        $data = [];
        foreach ($map as $key => $mapped) {
            if (!is_integer($key)) {
                $data[$key] = object_get($model, $mapped);
            } else {
                $data[$mapped] = object_get($model, $mapped);
            }
        }

        return $this->ApiResponse($data);
    }


}