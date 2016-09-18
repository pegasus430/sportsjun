<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
	use SoftDeletes;
    //
    protected $table = 'photos';
    protected $fillable = array('item','item_description','marketplace_category_id','base_price','actual_price','item_type','item_status','user_id');
    protected $morphClass = 'gallery';
    protected $dates = ['deleted_at'];


    public function photos()
    {
        return $this->morphMany('App\Model\Photo', 'imageable');
    }
}
