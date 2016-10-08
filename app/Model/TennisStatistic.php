<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class TennisStatistic extends Model {
    use SoftDeletes,Eloquence;
    //
    protected $table = 'tennis_statistic';
    //protected $fillable = array('user_id', 'following_sports', 'following_teams', 'managing_teams', 'joined_teams', 'following_tournaments', 'managing_tournaments', 'joined_tournaments', 'following_players', 'following_facilities', 'provider_id', 'provider_token', 'avatar',);

}
