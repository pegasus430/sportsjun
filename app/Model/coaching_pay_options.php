<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class coaching_pay_options extends Model
{
    //

    function package(){
    	return $this->belongsTo('App\Model\subscription_method', 'subscription_id');
    }
}
