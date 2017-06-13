<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class poll_setting extends Model
{
    //

    protected $fillable = ['organization_id', 'poll_result','block_votes'];
    
    function organization(){
    	return $this->belongsTo('App\Model\Organization');
    }
}
