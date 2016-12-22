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


    public static function ApiResponse($response, $code = 200)
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
                    if (is_array($mapped)) {
                        $item_data[$key] = $this->mappedExtract($mapped, $item, $item);
                    }
                }
            }
            $data[] = $item_data;
        }

        $result = new LengthAwarePaginator($data, $paginator->total(), $paginator->perPage(),
            $paginator->currentPage(), ['path' => \Request::url(), 'query' => \Request::query()]);

        return self::ApiResponse($result);
    }

    function mappedExtract($mapped, $item, $base)
    {
        $source = array_get($mapped, 'source');
        $type = array_get($mapped, 'type');

        $data = object_get($item, $source);
        if ($data) {
            switch ($type) {
                case 'list':
                    $fields = $mapped['fields'];
                    if (is_callable($fields))
                        $fields = $fields($item, $base);
                    $result = [];
                    $counter = 1;
                    foreach ($data as $sub) {
                        $item_data = [];
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
                                if (is_array($mapped)) {
                                    $item_data[$key] = $this->mappedExtract($mapped, $sub, $base);
                                }
                            }
                        }
                        $result[] = $item_data;
                        $counter++;
                    }
                    return $result;
                    break;
                case 'model':
                    $fields = $mapped['fields'];
                    if (is_callable($fields))
                        $fields = $fields($item, $base);
                    $item_data = [];
                    foreach ($fields as $key => $mapped) {
                        if (is_string($mapped)) {
                            if (!is_integer($key)) {
                                $item_data[$key] = object_get($data, $mapped);
                            } else {
                                $item_data[$mapped] = object_get($data, $mapped);
                            }
                        } else {
                            if (is_array($mapped)) {
                                $item_data[$key] = $this->mappedExtract($mapped, $data, $base);
                            }
                        }
                    }
                    return $item_data;
                    break;
                case 'value':
                    $value = $mapped['value'];
                    if (is_callable($value)) {
                        $value = $value($item, $base);
                    }
                    return $value;

                case 'array':
                    $fields = $mapped['fields'];
                    if (is_callable($fields))
                        $fields = $fields($item, $base);
                    $item_data = [];
                    foreach ($fields as $key => $mapped) {
                        if (is_string($mapped)) {
                            if (!is_integer($key)) {
                                $item_data[$key] = object_get($data, $mapped);
                            } else {
                                $item_data[] = object_get($data, $mapped);
                            }
                        } else {
                            if (is_array($mapped)) {
                                $item_data[$key] = $this->mappedExtract($mapped, $data, $base);
                            }
                        }
                    }
                    return $item_data;
                    break;
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
                    if (is_array($mapped)) {
                        $item_data[$key] = $this->mappedExtract($mapped, $item, $item);
                    }
                }

            }
            $data[] = $item_data;
        }

        return self::ApiResponse($data);
    }

    function ModelMapResponse(Model $model, $map)
    {
        $data = [];
        foreach ($map as $key => $mapped) {
            if (is_string($mapped)) {
                if (!is_integer($key)) {
                    $data[$key] = object_get($model, $mapped);
                } else {
                    $data[$mapped] = object_get($model, $mapped);
                }
            } else {
                if (is_array($mapped)) {
                    $data[$key] = $this->mappedExtract($mapped, $model, $model);
                }
            }
        }

        return self::ApiResponse($data);
    }


}