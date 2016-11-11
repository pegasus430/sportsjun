<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentGroupTeams extends Model
{
    protected $_matchSchedules;
    protected $_teamStats;

    use SoftDeletes;
    protected $table = 'tournament_group_teams';
    protected $dates = ['deleted_at'];

    protected $appends = ['matches','tie'];

    public function scopeJoinTeam($query)
    {
        return $query->join('teams', function ($join) {
            $join->on('tournament_group_teams.team_id', '=', 'teams.id');
        })
            ->select([
                'tournament_group_teams.*'
            ]);
    }

    public function tournament(){
        return $this->belongsTo(Tournaments::class,'tournament_id','id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    function appendMatchSchedule()
    {
        $team_id = $this->team_id;
        $schedules = MatchSchedule::whereTournamentId($this->tournament_id)
            ->whereTournamentGroupId($this->tournament_group_id)
            ->where(function ($query) use ($team_id) {
                $query->where('a_id', '=', $team_id)
                    ->orWhere('b_id', '=', $team_id);
            })->get();
        $this->_matchSchedules = $schedules;
    }

    public function getMatchSchedulesAttribute()
    {
        if (!$this->_matchSchedules) {
            $this->appendMatchSchedule();
        }
        return $this->_matchSchedules;
    }


    public function getMatchesAttribute(){
        return $this->matchSchedules
            ->where('match_status', 'completed')
            ->count();
    }

    public function getTieAttribute()
    {
        return $this->matchSchedules
            ->where('is_tied', 1)
            ->count();
    }


    public function groupDetails()
    {
        $details = [];
        $details['gf'] = 0;
        $details['ga'] = 0;
        $sports_id = $this->tournament->sports_id;
        switch ($sports_id) {
            case ($sports_id == Sport::$SOCCER || $sports_id == Sport::$HOKKEY):
                //die(json_encode($team_id));
                foreach ($this->matchSchedules as $key => $match) {
                    if ($match->a_id == $this->team_id) {         //sets the home and againts team
                        $gf_team = $match->a_id;
                        $ga_team = $match->b_id;
                    } elseif ($match->b_id == $this->team_id) {
                        $gf_team = $match->b_id;
                        $ga_team = $match->a_id;
                    }
                    $match_details = json_decode($match->match_details);
                    if (!empty($match->match_details)) {
                        $details['gf'] += $match_details->{$gf_team}->goals;
                        $details['ga'] += $match_details->{$ga_team}->goals;
                    }

                }
                break;

            case in_array($sports_id, [
                Sport::$TABLE_TENNIS,
                Sport::$BADMINTON,
                Sport::$SQUASH,
                Sport::$THROW_BALL,
                Sport::$KABADDI,
                Sport::$VOLEYBALL
            ]):
                //die(json_encode($team_id));
                foreach ($this->matchSchedules as $key => $match) {

                    if ($match->game_type == 'normal') {
                        if ($match->a_id == $this->team_id) {         //sets the home and againts team
                            $gf_team = $match->a_id;
                            $ga_team = $match->b_id;
                        } elseif ($match->b_id == $this->team_id) {
                            $gf_team = $match->b_id;
                            $ga_team = $match->a_id;
                        }
                        $match_details = json_decode($match->match_details);
                        if (!empty($match->match_details)) {
                            $details['gf'] += $match_details->scores->{$gf_team . '_score'};
                            $details['ga'] += $match_details->scores->{$ga_team . '_score'};
                        }
                    } else {
                        if ($match->a_id == $this->team_id) {         //sets the home and againts team
                            $details['gf'] += $match->a_score;
                            $details['ga'] += $match->b_score;
                        } elseif ($match->b_id == $this->team_id) {
                            $details['gf'] += $match->b_score;
                            $details['ga'] += $match->a_score;
                        }
                    }

                }

                break;
            case ($sports_id == Sport::$BASKETBALL
                || $sports_id == Sport::$ULTIMATE_FRISBEE
                || $sports_id == Sport::$WATER_POLO):
                foreach ($this->matchSchedules as $key => $match) {
                    if ($match->a_id == $this->team_id) {         //sets the home and againts team
                        $gf_team = $match->a_id;
                        $ga_team = $match->b_id;
                    } elseif ($match->b_id == $this->team_id) {
                        $gf_team = $match->b_id;
                        $ga_team = $match->a_id;
                    }
                    $match_details = json_decode($match->match_details);
                    if (!empty($match->match_details)) {
                        $details['gf'] += $match_details->{$gf_team}->total_points;
                        $details['ga'] += $match_details->{$ga_team}->total_points;
                    }
                }
                break;
            default:
                break;
        }
        return $details;
    }


    public function getTeamStatsAttribute(){
        if (!$this->_teamStats) {
            $team_stats = [];
            foreach ($this->matchSchedules as $schedule) {
                switch ($schedule->match_type) {
                    case 't20':
                        $maxOverCount = 20;
                        break;
                    case 'odi':
                        $maxOverCount = 50;
                        break;
                    case 'test':
                        $maxOverCount = 90;
                        break;
                    default:
                        $maxOverCount = 0;
                }
                $match_stats =  $schedule->matchDetailsA;

                if ($match_stats) {
                    $team_ids = array_keys($match_stats);

                    foreach ($match_stats as $team_id => $team_stat) {
                        if (empty($team_stats[$team_id])) {
                            $team_stats[$team_id] = [];
                            $team_stats[$team_id]['total_runs_scored'] = $team_stats[$team_id]['total_runs_conceded'] = 0;
                            $team_stats[$team_id]['total_overs_faced'] = $team_stats[$team_id]['total_overs_bowled'] = 0;
                        }
                        $team_stats[$team_id]['total_runs_scored'] += (int)$team_stat['fst_ing_score'] + (int)$team_stat['scnd_ing_score'];
                        $team_stats[$team_id]['total_overs_faced'] += (((int)$team_stat['fst_ing_wkt'] == 10) ? (float)$maxOverCount : (float)$team_stat['fst_ing_overs']) + (((int)$team_stat['scnd_ing_wkt'] == 10) ? (float)$maxOverCount : (float)$team_stat['scnd_ing_overs']);
                        $other_team_id = ($team_ids[0] == $team_id) ? $team_ids[1] : $team_ids[0];
                        $team_stats[$team_id]['total_runs_conceded'] += (int)$match_stats[$other_team_id]['fst_ing_score'] + (int)$match_stats[$other_team_id]['scnd_ing_score'];
                        $team_stats[$team_id]['total_overs_bowled'] += (((int)$match_stats[$other_team_id]['fst_ing_wkt'] == 10) ? (float)$maxOverCount : (float)$match_stats[$other_team_id]['fst_ing_overs']) + (((int)$match_stats[$other_team_id]['scnd_ing_wkt'] == 10) ? (float)$maxOverCount : (float)$match_stats[$other_team_id]['scnd_ing_overs']);
                    }
                }
            }
            $this->_teamStats = $team_stats;
        }
        return $this->_teamStats;
    }

    public function getNrrAttribute(){
        $team_stats = $this->teamStats;
        $nrr = '';
        if (array_get($team_stats,$this->team_id.'.total_overs_faced',0) > 0
            && array_get($team_stats,$this->team_id.'.total_overs_bowled',0) > 0)
        {
            $nrr = (array_get($team_stats,$this->team_id.'.total_runs_scored',0) / array_get($team_stats,$this->team_id.'.total_overs_faced',0));
            $nrr -= (array_get($team_stats,$this->team_id.'.total_runs_conceded',0) /  array_get($team_stats,$this->team_id.'.total_overs_bowled',0));
            $nrr = round($nrr, 3);
        }
        return $nrr;
    }


}


