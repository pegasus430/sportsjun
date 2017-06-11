<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class news extends Model
{
    //
    use SoftDeletes; 
    
    function category(){
    	return $this->belongsTo('App\Model\Sport', 'category_id');
    }
}
