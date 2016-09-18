<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchSchedule extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'match_schedules';
    protected $dates = ['deleted_at'];
	protected $fillable = array('tournament_id','tournament_group_id','tournament_round_number','tournament_match_number','sports_id','facility_id','facility_name','created_by','match_category','schedule_type','match_type','match_start_date','match_start_time','match_end_date','match_end_time','match_location','longitude','latitude','address','city_id','city','state_id','state','country_id','country','zip','match_status','a_id','b_id','player_a_ids','player_b_ids','score_a','score_b','winner_id','match_details', 'number_of_rubber', 'game_type');
    public function scheduleteamone() {
        return $this->belongsTo('App\Model\Team', 'a_id');
    }

    public function scheduleteamtwo() {
        return $this->belongsTo('App\Model\Team', 'b_id');
    }
    
    public function scheduleuserone() {
        return $this->belongsTo('App\User', 'a_id');
    }

    public function scheduleusertwo() {
        return $this->belongsTo('App\User', 'b_id');
    }
    
    public function sport() {
        return $this->belongsTo('App\Model\Sport', 'sports_id');
    }

    public function rubbers(){
        return $this->hasMany('App\Model\MatchScheduleRubber', 'match_id');
    }

    public function tournament(){
        return $this->belongsTo('App\Model\Tournaments', 'tournament_id');
    }
}
