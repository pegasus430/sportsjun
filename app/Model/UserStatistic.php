<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserStatistic extends Model {

    //
    protected $table = 'user_statistics';
    protected $fillable = array('user_id', 'following_sports', 'following_teams', 'managing_teams', 'joined_teams', 'following_tournaments', 'managing_tournaments', 'joined_tournaments', 'following_players', 'following_facilities', 'provider_id', 'provider_token', 'avatar',);

   public function user() {
        return $this->belongsTo('App\User');
    }


}
