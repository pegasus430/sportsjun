<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamPlayer extends Model 
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'team_players';
    protected $dates = ['deleted_at'];

    public function team()
    {
        return $this->belongsTo(\App\Model\Team::class,'team_id','id');
    }

    public function user(){
        return $this->belongsTo(\App\User::class,'user_id','id');
    }
   
}
