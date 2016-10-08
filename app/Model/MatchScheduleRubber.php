<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MatchScheduleRubber extends Model
{
    //

    protected $table = 'match_schedules_rubber';

    protected $fillable = array('tournament_id','tournament_group_id','tournament_round_number','tournament_match_number','sports_id','facility_id','facility_name','created_by','match_category','schedule_type','match_type','match_start_date','match_start_time','match_end_date','match_end_time','match_location','longitude','latitude','address','city_id','city','state_id','state','country_id','country','zip','match_status','a_id','b_id','player_a_ids','player_b_ids','score_a','score_b','winner_id','match_details', 'rubber_number', 'a_score', 'b_score');


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

    public function  match(){
        return $this->belongsTo('App\Model\MatchSchedule', 'match_id');
      }
}
