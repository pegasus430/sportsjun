<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class coaching extends Model
{
    //
    use SoftDeletes;

    function coach(){
    	return $this->belongsTo('App\User', 'staff_id');
    }

    function payment_options(){
        return $this->hasMany('App\Model\coaching_pay_options', 'coaching_id')->where('payment_method', $this->payment_method);
    }

    function payment_option($subscription_id){
        return \App\Model\coaching_pay_options::where('coaching_id',$this->id)->where('subscription_id',$subscription_id)->first();
    }

    function players(){
        return $this->hasMany('App\Model\coaching_player');
    }

}
