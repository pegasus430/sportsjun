<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamPlayers extends Model
{
    use SoftDeletes;
    protected $table = 'team_players';
    protected $dates = ['deleted_at'];
    protected $fillable = array('team_id', 'user_id', 'role', 'created_at', 'updated_at', 'isactive', 'status');


    static $ROLE_OWNER = 'owner';
    static $ROLE_MANAGER = 'manager';
    static $ROLE_KEEPER = 'keeper';
    static $ROLE_PLAYER = 'player';
    static $ROLE_COACH = 'coach';
    static $ROLE_PHYSIO = 'physio';
    static $ROLE_CAPTAIN = 'captain';
    static $ROLE_VICE_CAPTAIN ='vice-captain';

    //a player belongs to a team
    public function team()
    {
        return $this->belongsTo('App\Model\Team', 'team_id');
    }

    // a user belongs to a team
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->select('id', 'name','logo');
    }

    public static function setUserRole($team_id, $user_id, $role, $replace_exists = true)
    {
        if ($replace_exists) {
            TeamPlayers::where('team_id', $team_id)->where('role', $role)->update(['role'=> 'player']);
        }
        return TeamPlayers::where('user_id', $user_id)->where('team_id', $team_id)->update(['role'=> $role]) > 0;
    }
}
