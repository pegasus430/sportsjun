<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamPlayers extends Model
{
	use SoftDeletes;
    protected $table = 'team_players';
	protected $dates = ['deleted_at'];
	protected $fillable = array('team_id','user_id','role','created_at','updated_at','isactive','status');
	//a player belongs to a team
	public function team()
    {
        return $this->belongsTo('App\Model\Team','team_id');
    }
	// a user belongs to a team
	public function user()
    {
		return $this->belongsTo('App\User','user_id')->select('id', 'name');
    }	
}
