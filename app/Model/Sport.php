<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sport extends Model {

    use SoftDeletes;

    public static $CRICKET = 1;
    public static $TENNIS = 2;
    public static $TABLE_TENNIS = 3;
    public static $SOCCER = 4;
    public static $BADMINTON = 5;
    public static $BASKETBALL = 6;
    public static $VOLEYBALL = 7;
    public static $SWIMMING = 8;
    public static $RUNNING = 9;
    public static $HOKKEY = 11;
    public static $BASEBALL = 12;
    public static $SQUASH = 13;
    public static $KABADDI = 14;
    public static $ULTIMATE_FRISBEE = 15;
    public static $WATER_POLO = 16;
    public static $THROW_BALL = 17;

    public static $ARCHERY = 1000; //TODO: set correct value



    public static $CATEGORY_ATHLETICS = 'athletics';

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
