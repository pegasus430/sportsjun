<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class CricketOverwiseScore extends Model
{
    use SoftDeletes,
        Eloquence;


    protected $table = 'cricket_overwise_score';

    protected $fillable = [
        'tournamet_id',
        'team_id',
        'user_id',
        'match_id',
        'bowler_id',
    ];

    protected $casts = [
        'ball_by_ball' => 'array'
    ];


}
