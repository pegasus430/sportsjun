<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SmiteMatch extends Model
{
    //
    protected $table = 'smite_matches';

    protected $fillable = ['match_id','lobby_name','lobby_password','match_statistic','match_status'];

    public function match()
    {
        return $this->hasOne('App\MatchSchedule');
    }
}
