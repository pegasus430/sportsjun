<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvitePlayer extends Model {

	  protected $table = 'team_invite';
	  protected $dates = ['deleted_at'];
	  protected $fillable = array('team_id  ','from_user_id','to_user_id','status','invite_text');
 
}


