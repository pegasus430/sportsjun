<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketPlaceCategories extends Model
{
    //
use SoftDeletes;
    protected $table = 'marketplace_categories';
	protected $dates = ['deleted_at'];
	 public function place() {
        return $this->belongsTo('App\Model\MarketPlace', 'id');
    }
}
