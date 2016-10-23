<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Requestsmodel extends Model {
	use SoftDeletes, Eloquence;	
    protected $table = 'request';
    protected $fillable = array('type','from_id','to_id','from_to_names','action_status','message','message_sent','url','is_read','id_to_update');

    public static function requestQuery($type_ids)
    {
         $sent_requests = Requestsmodel::select(DB::raw("
					request.*,
					case request.type 
						when 1  then (select logo from teams where id=request.from_id)
						when 2  then (select logo from tournaments where id=request.from_id)
						when 3  then (select logo from users where id=request.from_id)
						when 4  then (select logo from tournaments where id=request.from_id)
						when 5  then (select logo from teams where id=request.from_id)
						when 6  then (select logo from users where id=request.from_id)
						else 'logo'
						end as logo,
					case request.type 
						when 1  then 'TEAMS_FOLDER_PATH'
						when 2  then 'TOURNAMENT_PROFILE'
						when 3  then 'USERS_PROFILE'
						when 4  then 'TOURNAMENT_PROFILE'
						when 5  then 'TEAMS_FOLDER_PATH'
						when 6  then 'USERS_PROFILE'
						else 'logo'
						end as logo_type	
					"))
                        ->where('request.action_status',0);
        if(!empty($type_ids)) {
            $sent_requests->whereIn('request.type',$type_ids);
        }
        return $sent_requests;
    }


}
