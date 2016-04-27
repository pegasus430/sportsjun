<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Notifications extends Model {
	use SoftDeletes, Eloquence;	
    protected $table = 'notifications';
    protected $fillable = array('request_id','type','user_id','message','url','is_read');
}
