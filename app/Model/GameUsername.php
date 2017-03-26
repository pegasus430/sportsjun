<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GameUsername extends Model
{
    //

    protected $table = 'game_usernames';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
