<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RefereeSchedule extends Model
{
    //

    protected $table = 'match_schedules_referees';

    public function user(){	
    	$this->belongsTo('App\Model\User', 'user_id');
    }
}
