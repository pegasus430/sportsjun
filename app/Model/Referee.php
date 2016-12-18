<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    //
    protected $table = 'referee';

    function user(){
    	return $this->belongsTo('App\User');
    }
}
