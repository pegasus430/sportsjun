<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sport extends Model {

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sports';
    protected $dates = ['deleted_at'];
	protected $morphClass = 'sports';
	public function photos()
    {
        return $this->morphMany('App\Model\Photo', 'imageable')->where('is_album_cover',1);
    }
	public function facilitysports() {
        return $this->hasMany('App\Model\Facilitysports', 'sports_id', 'id');
    }
    
    public function tournaments()
    {
        return $this->hasMany('App\Model\Tournaments','sports_id','id');
    }
	  public function teams()
    {
        return $this->hasMany('App\Model\Team','sports_id','id');
    }
    
    public function schedules()
    {
        return $this->hasMany('App\Model\MatchSchedule','sports_id','id');
    }

}
