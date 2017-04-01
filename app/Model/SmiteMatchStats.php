<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SmiteMatchStats extends Model
{
    protected $table = 'smite_match_stats';

    protected $fillable = ['user_id','match_id','smite_match_id','final_level','kills','deaths','assists','gold_earned','gpm','magical_damage_done','physical_damage_done','healing'];

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
