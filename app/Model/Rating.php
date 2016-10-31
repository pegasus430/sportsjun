<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Rating extends Model {
    use SoftDeletes, Eloquence;

    protected $fillable = [
        'user_id','type','to_id','rate'
    ];

    static $RATE_USER = 1;
    static $RATE_TEAM = 2;
    static $RATE_TOURNAMENT = 3;
    static $RATE_PARENT_TOURNAMENT = 4;
    static $RATE_ORGANIZATION = 5;


    public static function overralRate($id,$type){
        return Rating::where('to_id', $id)
            ->where('type',$type)
            ->select(\DB::raw('SUM(rate)/COUNT(rate) as rate' ))
            ->first()
            ->pluck('rate');
    }

    public static function activeUserRate($id,$type){
        if (\Auth::user()) {
            return Rating::where('to_id', $id)
                ->where('type', $type)
                ->where('user_id', \Auth::user()->id)
                ->select('rate')->first();
        }
    }


}
