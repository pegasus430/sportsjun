<?php

namespace App\Model;

use App\Helpers\Helper;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Sofa\Eloquence\Eloquence;

class Tournaments extends Model
{
    protected $_finalStageTeams;

    use SoftDeletes,
        Eloquence;

    protected $table = 'tournaments';

    protected $dates = ['deleted_at'];

    protected $searchableColumns = [
        'name',
        'location',
    ];

    protected $morphClass = 'tournaments';

    protected $fillable = [
        'id',
        'name',
        'created_by',
        'sports_id',
        'start_date',
        'end_date',
        'contact_number',
        'email',
        'location',
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
        'groups_number',
        'groups_teams',
        'facility_id',
        'prize_money',
        'enrollment_fee',
        'points_win',
        'points_loose',
        'points_tie',
        'status',
        'description',
        'final_stage_teams',
        'type',
        'facility_name',
        'schedule_type',
        'alternate_contact_number',
        'contact_name',
        'logo',
        'tournament_parent_id',
        'tournament_parent_name',
        'manager_id',
        'match_type',
        'player_type',
        'game_type',
        'number_of_rubber'
    ];

    protected $appends = ['logoImage'];

    public function scopeJoinParent($query)
    {
        return $query->join('tournament_parent', function ($join) {
                $join->on('tournaments.tournament_parent_id', '=', 'tournament_parent.id');
            })
            ->select([
                'tournaments.*',
                'tournament_parent.logo as tournament_parent_logo'
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournamentParent()
    {
        return $this->belongsTo(TournamentParent::class, 'tournament_parent_id',
            'id');
    }

    public function sports()
    {
        return $this->hasMany('App\Model\Sport', 'id', 'sports_id');
    }

    public function logo()
    {
        return $this->hasOne('App\Model\Photo', 'imageable_id', 'tournament_parent_id')->where('imageable_type',
            'tournaments')->where('is_album_cover', 1)->select('imageable_id', 'url');
    }

    public function photos()
    {
        $this->morphClass = 'tournaments';
        return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type',
            'tournaments')->where('is_album_cover', 1);
    }

    public function photo()
    {
        $this->morphClass = 'form_gallery_tournaments';
        return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type',
            'form_gallery_tournaments')->where('is_album_cover', 1);
    }

    public function groups()
    {
        return $this->hasMany('App\Model\TournamentGroups', 'tournament_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }

    public function sport()
    {
        return $this->belongsTo('App\Model\Sport', 'sports_id');
    }

    public function searchResults($req_params)
    {
        $offset = !empty($req_params['offset']) ? $req_params['offset'] : 0;
        $limit = !empty($req_params['limit']) ? $req_params['limit'] : config('constants.LIMIT');
        $query = $this->with('logo')->search($req_params['search_by']);
        if (trim($req_params['sport']) != '') {
            $query = $this->with('logo')
                ->search($req_params['search_by'])
                ->whereIn('sports_id', explode(",", $req_params['sport']));
        }
        if ($req_params['amount'] != '') {
            $amount = explode("-", $req_params['amount']);
            $query = $query->whereBetween('enrollment_fee', $amount);
        }
        if (trim($req_params['search_city_id']) != '') {
            $query = $query->where('city_id', trim($req_params['search_city_id']));
        }

        $query = $query->where('isactive', 1);
        $query = $query->whereNull('deleted_at');
        //echo $query;exit;	

        $totalresult = $query->get();
        $total = count($totalresult);
        $result = $query->limit($limit)->offset($offset)->orderBy('updated_at', 'desc')->get();
        $response = array(
            'result' => $result,
            'total' => $total
        );
        return $response;
    }

    function getGroupPoints($tournament_id, $organization_group_id)
    {
        $points = OrganizationGroupTeamPoint::whereTournamentId($tournament_id)->whereOrganizationGroupId($organization_group_id)->first();
        if (empty($points)) {
            $team_id = DB::table('organization_group_teams')
                ->where('organization_group_id', $organization_group_id)
                ->lists('team_id');
            $teams = null;
            if ($team_id) {
                $teams = TournamentGroupTeams::whereTournamentId($tournament_id);
                if (is_array($team_id)) {
                    $teams->whereIn('team_id', $team_id);
                } else {
                    $teams->where('team_id', $team_id);
                }
                $teams = $teams->get();
            }
            if (!$teams) {
                return 0;
            }

            $final_points = $teams->sum('final_points');
            $points = $final_points ? $final_points : $teams->sum('points');
            return $points;
        } else {
            $points = $points->points;
        }
        return $points;

    }

    function finalMatches()
    {
        return $this->hasMany(MatchSchedule::class,'tournament_id')
                        ->where('match_schedules.tournament_round_number','is not', null);
    }

    function settings()
    {
        return $this->hasOne('App\Model\TournamentMatchPreference', 'tournament_id');
    }

    function matches()
    {
        return $this->hasMany('App\Model\MatchSchedule', 'tournament_id');
    }

    function followers(){
            return $this->hasMany('App\Model\Followers','type_id')->whereType('tournament');
    }

    public function getLogoImageAttribute()
    {
        $logo = $this->logo ? $this->logo :
            (array_key_exists('tournament_parent_logo',$this->attributes) ?  $this->tournament_parent_logo : object_get($this->tournamentParent,'logo')) ;
        return Helper::getImagePath($logo, 'tournaments');
    }

    public function getFinalStageTeamsListAttribute(){
        if (!$this->_finalStageTeams) {
            $schedule_type = $this->schedule_type ? $this->schedule_type : 'team';
            $teamIDs = explode(',', trim($this->final_stage_teams_ids, ','));
            switch($schedule_type){
                case 'team':
                    $this->_finalStageTeams = Team::whereIn('id', $teamIDs)->orderBy('name')->select(['id','name','logo'])->get();
                    break;
                case 'individual':
                    $this->_finalStageTeams = User::whereIn('id', $teamIDs)->orderBy('name')->select(['id','name','logo'])->get();
                    break;
                default:
                    $this->_finalStageTeams = new Collection();
                    break;
            }
        }

        return $this->_finalStageTeams;
    }

}