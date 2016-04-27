<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentGroups extends Model {

     use SoftDeletes;
	 protected $table = 'tournament_groups';
	 protected $dates = ['deleted_at'];
	 protected $fillable = array('id','tournament_id','name' );
	public function group_teams()
	{
		return $this->hasMany('App\Model\TournamentGroupTeams','tournament_group_id','id');
	}

 
}


