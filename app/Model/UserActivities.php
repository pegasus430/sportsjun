<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserActivities extends Model {

    //
    protected $table = 'user_activities';
    protected $fillable = array('user_id', 'session_id', 'controller_name', 'method_name', 'url', 'params', 'user_agent', 'ip_address',);
}
