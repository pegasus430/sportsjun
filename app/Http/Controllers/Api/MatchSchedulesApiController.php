<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\MatchSchedule;


class  MatchSchedulesApiController extends BaseApiController
{

    function getList(){
        $schedules =  $this
            ->applyFilter(MatchSchedule::select(),[])
            ->paginate(50);
        return $this->ApiResponse($schedules);
    }


}