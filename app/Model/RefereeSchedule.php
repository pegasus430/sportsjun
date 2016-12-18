<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RefereeSchedule extends Model
{
    //

    protected $table = 'match_schedules_referees';

    function user(){	
    	return $this->belongsTo('App\User', 'user_id');
    }

    function referee(){	
    	return $this->belongsTo('App\Model\Referee', 'referee_id');
    }

}
