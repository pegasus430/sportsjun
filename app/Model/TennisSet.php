<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TennisSet extends Model
{
    //
    function game(){
    	return $this->hasMany('App\Model\TennisSetGame');
    }

    function active_game(){
    	return $this->hasOne('App\Model\TennisSetGame', 'table_sets_id')->whereWinnerId(0);
    }
}
