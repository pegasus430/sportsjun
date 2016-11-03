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

	public function match_schedules(){
        return $this->hasMany(MatchSchedule::class,'tournament_group_id','id');
    }

    public function tournament(){
        return $this->belongsTo(Tournaments::class,'tournament_id','id');
    }


 
}


