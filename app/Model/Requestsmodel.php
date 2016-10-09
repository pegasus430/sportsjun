<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Requestsmodel extends Model {
	use SoftDeletes, Eloquence;	
    protected $table = 'request';
    protected $fillable = array('type','from_id','to_id','from_to_names','action_status','message','message_sent','url','is_read','id_to_update');
}
