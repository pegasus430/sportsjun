<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MarketPlaceLogs extends Model
{
	use SoftDeletes;
  
    protected $table = 'marketplace_logs';
    protected $fillable = array('id','user_id','marketplace_id','catgory_id');
    protected $morphClass = 'marketplace_logs';
    protected $dates = ['deleted_at'];

}
