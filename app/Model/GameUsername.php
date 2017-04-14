<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GameUsername extends Model
{
    //

    protected $table = 'game_usernames';
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function game()
    {
        return $this->hasOne(Sport::class);
    }
}
