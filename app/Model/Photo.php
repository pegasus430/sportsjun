<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table = 'photos';
    protected $dates = ['deleted_at'];    
    protected $softDelete = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'album_id', 'title', 'url', 'likes', 'like_count', 'is_album_cover', 'imageable_id', 'imageable_type', 'isactive'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function albums()
    {
        return $this->belongsTo('App\Model\Album','id');
    }

}