<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SmiteMatch extends Model
{
    //
    protected $table = 'smite_matches';

    public function match()
    {
        return $this->hasOne('App\MatchSchedule');
    }
}
