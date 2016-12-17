<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ArcheryRound extends Model
{
    //

    function match(){
    	return $this->belongsTo('App\Model\MatchSchedule','match_id');
    }
}
