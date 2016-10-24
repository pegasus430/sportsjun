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




}
