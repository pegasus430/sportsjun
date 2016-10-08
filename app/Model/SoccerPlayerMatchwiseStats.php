<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
class SoccerPlayerMatchwiseStats extends Model {
	use SoftDeletes,
	Eloquence;	
    //
    protected $table = 'soccer_player_matchwise_stats';
  
}
