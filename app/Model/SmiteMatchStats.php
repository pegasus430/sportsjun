<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SmiteMatchStats extends Model
{
    protected $table = 'smite_matches';

    protected $fillable = [];

    public function match()
    {
        return $this->belongsTo('App\Model\MatchSchedule');
    }

    public function smitematch()
    {
        return $this->belongsTo('App\Model\SmiteMatch');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
