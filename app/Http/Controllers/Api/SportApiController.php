<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\Sport;
use Response;

class SportApiController extends BaseApiController
{
    /**
     * Sport - list
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sports=Sport::select(
            ['id','created_by','sports_name','sports_type','is_schedule_available','is_scorecard_available','isactive']
        )->get();
        return self::ApiResponse(['data'=>$sports]);
    }
}
