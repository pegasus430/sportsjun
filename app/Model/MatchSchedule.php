<?php

namespace App\Model;

use App\Helpers\Helper;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchSchedule extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'match_schedules';
    protected $dates = ['deleted_at'];
    protected $fillable = array(
        'tournament_id',
        'tournament_group_id',
        'tournament_round_number',
        'tournament_match_number',
        'sports_id',
        'facility_id',
        'facility_name',
        'created_by',
        'match_category',
        'schedule_type',
        'match_type',
        'match_start_date',
        'match_start_time',
        'match_end_date',
        'match_end_time',
        'match_location',
        'longitude',
        'latitude',
        'address',
        'city_id',
        'city',
        'state_id',
        'state',
        'country_id',
        'country',
        'zip',
        'match_status',
        'a_id',
        'b_id',
        'player_a_ids',
        'player_b_ids',
        'score_a',
        'score_b',
        'winner_id',
        'match_details',
        'number_of_rubber',
        'game_type',
        'is_third_position',
        'sports_category',
        'player_or_team_ids'
    );

    public function scheduleteamone()
    {
        return $this->belongsTo('App\Model\Team', 'a_id');
    }

    public function scheduleteamtwo()
    {
        return $this->belongsTo('App\Model\Team', 'b_id');
    }

    public function scheduleuserone()
    {
        return $this->belongsTo('App\User', 'a_id');
    }

    public function scheduleusertwo()
    {
        return $this->belongsTo('App\User', 'b_id');
    }

    public function sport()
    {
        return $this->belongsTo('App\Model\Sport', 'sports_id');
    }

    public function rubbers()
    {
        return $this->hasMany('App\Model\MatchScheduleRubber', 'match_id');
    }

    public function tournament()
    {
        return $this->belongsTo('App\Model\Tournaments', 'tournament_id');
    }

    /**
     * Attributes
     */

    public function getMatchDetailsPAttribute()
    {
        return json_decode($this->match_details);
    }

    public function getMatchDetailsAAttribute()
    {
        return json_decode($this->match_details, true);
    }

    public function getSideAAttribute()
    {
        if ($this->schedule_type == 'team') {
            return $this->scheduleteamone ? $this->scheduleteamone : $this->scheduleteamone()->get();
        } else {
            return $this->scheduleuserone ? $this->scheduleuserone : $this->scheduleuserone()->get();
        }
    }

    public function getSideBAttribute()
    {
        if ($this->schedule_type == 'team') {
            return $this->scheduleteamtwo ? $this->scheduleteamtwo : $this->scheduleteamtwo()->get();
        } else {
            return $this->scheduleusertwo ? $this->scheduleusertwo : $this->scheduleusertwo()->get();
        }
    }

    public function getSideALogoAttribute()
    {
        if ($this->schedule_type == 'team') {
            return Team::logoImage($this->a_id);
        } else {
            return User::logoImage($this->a_id);
        }
    }

    public function getSideBLogoAttribute()
    {
        if ($this->schedule_type == 'team') {
            return Team::logoImage($this->b_id);
        } else {
            return User::logoImage($this->b_id);
        }
    }

    function extractScoreString($id)
    {
        switch ($this->sports_id) {
            case Sport::$BADMINTON:
                return object_get($this->matchDetailsP,'scores.'.$id.'_score'). ' sets';
            case Sport::$VOLEYBALL:
            case Sport::$SQUASH:
            case Sport::$THROW_BALL:
                return object_get($this->matchDetailsP,'scores.'.$id.'_score'). ' sets';
            case Sport::$SOCCER:
            case Sport::$HOKKEY:
                return  object_get($this->matchDetailsP,$id.'.goals');
            case Sport::$BASKETBALL:
            case Sport::$KABADDI:
            case Sport::$ULTIMATE_FRISBEE:
            case Sport::$WATER_POLO :
                return  object_get($this->matchDetailsP,$id.'.total_points');
            case Sport::$CRICKET:
                return  object_get($this->matchDetailsP,$id.'.fst_ing_score') . "/"
                        . object_get($this->matchDetailsP,$id.'.fst_ing_wkt') .
                        (!empty(object_get($this->matchDetailsP,$id.'.scnd_ing_overs')) ?
                            ", " .object_get($this->matchDetailsP,$id.'.scnd_ing_overs') . "/" .
                            object_get($this->matchDetailsP,$id.'.scnd_ing_wkt') : "");
            default:
                return '';
        }
    }

    function extractOversString($id)
    {
        switch ($this->sports_id) {
            case Sport::$BADMINTON:
                return '';
            case Sport::$VOLEYBALL:
            case Sport::$SQUASH:
            case Sport::$THROW_BALL:
              return '';
            case Sport::$SOCCER:
            case Sport::$HOKKEY:
                return '';
            case Sport::$BASKETBALL:
            case Sport::$KABADDI:
            case Sport::$ULTIMATE_FRISBEE:
            case Sport::$WATER_POLO :
                return object_get($this->matchDetailsP,$id.'.total_points');
            case Sport::$CRICKET:
                return object_get($this->matchDetailsP,$id.'.fst_ing_overs').
                         (object_get($this->matchDetailsP,$id.'.scnd_ing_overs') ?
                             '/'.object_get($this->matchDetailsP,$id.'.scnd_ing_overs') :
                             '');
            default:
                return '';
        }

    }




    public function getSideAScoreAttribute()
    {
        return $this->extractScoreString($this->a_id);
    }

    public function getSideBScoreAttribute()
    {
        return $this->extractScoreString($this->b_id);
    }

    public function getSideAOversAttribute(){
        return $this->extractOversString($this->a_id);
    }

    public function getSideBOversAttribute(){
        return $this->extractOversString($this->b_id);
    }


    public function getScoresAttribute()
    {
        if ($this->game_type != 'normal') {
            return $this->a_score . ' - ' . $this->b_score;
        }
        if ($this->match_details != null) {
            $match_details = json_decode($this->match_details);
            $a_id = $this->a_id;
            $b_id = $this->b_id;
            return Helper::getScoresFromMatchDetails($match_details, $this->sports_id, $a_id, $b_id);
        }
        return ' - ';
    }

    public function getWinnerAttribute()
    {
        if (!empty($this->winner_id)) {
            if ($this->schedule_type == 'player') {
                return User::find($this->winner_id)->name;
            } else {
                return Team::find($this->winner_id)->name;
            }
        }
    }

    public function getScoreMoreAttribute()
    {
        if ($this->match_status == 'completed') {
            return trans('message.schedule.viewscore');
        } else {
            if ($this->match_start_date && Carbon::now()->gte(Carbon::createFromFormat('Y-m-d',
                    $this->match_start_date))
            ) {
                $scoreOwner = Helper::isValidUserForScoreEnter($this->toArray());
                if ($scoreOwner) {
                    return Helper::getCurrentScoringStatus($this);
                } else {
                    return trans('message.schedule.viewscore');
                }
            }
        }
    }

   public function referees(){
        return $this->hasMany('App\Model\RefereeSchedule', 'match_id');    
    }

   public function archery_rounds(){
        return $this->hasMany('App\Model\ArcheryRound', 'match_id');
   }

   public function archery_players($team_id=null,$order=null){

    if($this->schedule_type=='player')
        $players = $this->hasMany('App\Model\ArcheryPlayerStats','match_id');
    else
        $players = $this->hasMany('App\Model\ArcheryTeamStats', 'match_id');


        if($team_id) $players = $players->where('team_id',$team_id);

        if($order)   $players = $players->orderBy($order,'desc');
        return $players->get();
   }

    public function match()
    {
        return $this->hasOne('App\Model\SmiteMatch');
    }

    public function smitematchstats()
    {
        return $this->hasOne('App\Model\SmiteMatchStats');
    }
}
