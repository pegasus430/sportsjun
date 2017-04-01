<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SmiteMatch extends Model
{
    //
    protected $table = 'smite_matches';

    protected $fillable = ['match_id','lobby_name','lobby_password','match_statistic','match_status'];

    /*
        Foreign keys passed as second parameter references foreign key in this model (match_id)
        Method name + _id references key constraint
    */
    public function match()
    {
        return $this->belongsTo('App\Model\MatchSchedule');
    }
}
