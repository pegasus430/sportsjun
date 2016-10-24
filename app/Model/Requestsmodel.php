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
						when 1  then (select logo from users where id=request.from_id)
						when 2  then (select logo from users where id=request.from_id)
						when 3  then (select logo from teams where id=request.from_id)
						when 4  then (select logo from teams where id=request.from_id)
						when 5  then (select logo from teams where id=request.from_id)
						when 6  then (select logo from users where id=request.from_id)
						else 'logo'
					end as logoFrom,
					case request.type 
						when 1  then (select logo from teams where id=request.from_id)
						when 2  then (select logo from tournaments where id=request.from_id)
						when 3  then (select logo from users where id=request.from_id)
						when 4  then (select logo from tournaments where id=request.from_id)
						when 5  then (select logo from teams where id=request.from_id)
						when 6  then (select logo from users where id=request.from_id)
						else 'logo'
					end as logoTo						
				    "))
                        ->where('request.action_status',0);
        if(!empty($type_ids)) {
            $sent_requests->whereIn('request.type',$type_ids);
        }
        return $sent_requests;
    }

    public function getLogoFromTypeAttribute(){
        switch($this->type) {
            case 1:
                return 'USERS_PROFILE';
            case 2:
                return 'USERS_PROFILE';
            case 3:
                return 'TEAMS_FOLDER_PATH';
            case 4:
                return 'TEAMS_FOLDER_PATH';
            case 5:
                return 'TEAMS_FOLDER_PATH';
            case 6:
                return 'USERS_PROFILE';
            default:
                return 'logo';
        }
    }

    public function getLogoToTypeAttribute(){
            switch($this->type) {
                case 1:
                    return 'TEAMS_FOLDER_PATH';
                case 2:
                    return 'TOURNAMENT_PROFILE';
                case 3:
                    return 'USERS_PROFILE';
                case 4:
                    return 'TOURNAMENT_PROFILE';
                case 5:
                    return 'TEAMS_FOLDER_PATH';
                case 6:
                    return 'USERS_PROFILE';
                default:
                    return 'logo';
            }
    }




}
