<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class news extends Model
{
    //
    function category(){
    	return $this->belongsTo('App\Model\Sport', 'category_id');
    }
}
