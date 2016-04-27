<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{
	use SoftDeletes;
	protected $table = 'albums';
	protected $dates = ['deleted_at'];    
	protected $softDelete = true;

	public function photosWithCover()
    {
        return $this->hasMany('App\Model\Photo','album_id','id')->where('is_album_cover', 1);
    }
}
