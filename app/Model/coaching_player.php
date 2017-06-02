<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class coaching_player extends Model
{
    //
     protected $fillable = ['coaching_id', 'user_id'];

     public function user(){
     	return $this->belongsTo('App\User');
     }
}
