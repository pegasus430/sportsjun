<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class poll extends Model
{
    //

    public function options(){
    	return $this->hasMany('App\Model\poll_options', 'poll_id');
    }

    public function voters(){
    	return $this->hasMany('App\Model\poll_voters', 'poll_id');
    }
}
