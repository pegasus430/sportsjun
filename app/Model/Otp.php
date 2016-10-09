<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
	protected $table = 'otp';
	protected $fillable = array('user_id','token','otp','contact_number','is_verified','item_id','item_type');
}
