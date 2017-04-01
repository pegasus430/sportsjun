<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmiteSession extends Model
{
    //
    protected $table = 'smite_session';

    protected $fillable = ['match_id','lobby_name','lobby_password','match_statistic','match_status'];
}
