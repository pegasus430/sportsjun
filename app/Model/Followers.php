<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use SoftDeletes;
use Sofa\Eloquence\Eloquence;
use App\Helpers\Helper;
use Auth;
use DB;

class Followers extends Model {
	use SoftDeletes,
		Eloquence;	
    protected $table = 'followers';
    protected $searchableColumns = ['type'];
	protected $morphClass = 'followers';	
	protected $fillable = array('user_id','type','type_id');
	protected $dates = ['deleted_at'];

    static $TYPE_TOURNAMENTS = 'tournament';

    static $TYPE_ORGANIZATIONS = 'organization';


	//Get Managing and joined teams
	public function getFollowingList($user_id,$type)
	{		
		$query = "SELECT 
			   GROUP_CONCAT(f.type_id) AS following_list
			   FROM followers as f			  
			   WHERE f.user_id = $user_id AND f.type = '".$type."' and f.deleted_at is null";
		$team_array =DB::select(DB::raw($query));	
		return $team_array;
	}
	//End

    public function scopeTournaments($query){
        return $query->where('type',self::$TYPE_TOURNAMENTS);
    }

    public function scopeOrganizations($query){
        return $query->where('type',self::$TYPE_ORGANIZATIONS);
    }

}
