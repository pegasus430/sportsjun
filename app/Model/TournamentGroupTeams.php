<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentGroupTeams extends Model {

     use SoftDeletes;
	 protected $table = 'tournament_group_teams';
	 protected $dates = ['deleted_at'];
   
}


