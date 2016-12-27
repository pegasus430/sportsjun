<?php

namespace App\Model;

use App\Helpers\Helper;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\StateRepository;
use App\User;
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
        'enrollment_type',
        'number_of_rubber',
        'enrollment_type',
        'is_sold_out',
        'price_per_enrolment',
        'reg_opening_date',
        'reg_opening_time',
        'reg_closing_date',
        'reg_closing_time',
        'total_enrollment',
        'min_enrollment',
        'max_enrollment',
        'terms_conditions',
        'vendor_bank_account_id',
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
        return $this->hasOne(Sport::class, 'id', 'sports_id');
    }

    public function logo()
    {
        return $this->hasOne('App\Model\Photo', 'imageable_id', 'tournament_parent_id')->where('imageable_type',
            'tournaments')->where('is_album_cover', 1)->select('imageable_id', 'url');
    }

    public function albums()
    {
        return $this->hasMany(Album::class, 'imageable_id', 'id')
            ->where('imageable_type', config('constants.PHOTO.GALLERY_TOURNAMENTS'));
    }

    public function profile_album_photos()
    {
        return $this->hasMany(Photo::class, 'imageable_id', 'id')
            ->where('imageable_type', 'form_gallery_tournaments');
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

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function sport()
    {
        return $this->belongsTo('App\Model\Sport', 'sports_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(VendorBankAccounts::class, 'vendor_bank_account_id', 'id');
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
            $team_id = \DB::table('organization_group_teams')
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
        return $this->hasMany(MatchSchedule::class, 'tournament_id')
            ->whereNotNull('tournament_round_number');
    }

    function settings()
    {
        return $this->hasOne('App\Model\TournamentMatchPreference', 'tournament_id');
    }

    function matches()
    {
        return $this->hasMany('App\Model\MatchSchedule', 'tournament_id');
    }

    function followers()
    {
        return $this->hasMany('App\Model\Followers', 'type_id')->whereType('tournament');
    }

    public function getLogoImageAttribute()
    {
        $logo = $this->logo ? $this->logo :
            (array_key_exists('tournament_parent_logo', $this->attributes) ? $this->tournament_parent_logo : object_get($this->tournamentParent, 'logo'));
        return Helper::getImagePath($logo, 'tournaments');
    }

    public function getLogoImageRealAttribute()
    {
        $logo = $this->logo ? $this->logo :
            (array_key_exists('tournament_parent_logo', $this->attributes) ? $this->tournament_parent_logo : object_get($this->tournamentParent, 'logo'));
        return Helper::getImagePath($logo, 'tournaments', '', false, false);
    }

    public function getFinalStageTeamsListAttribute()
    {
        if (!$this->_finalStageTeams) {
            $schedule_type = $this->schedule_type ? $this->schedule_type : 'team';
            $teamIDs = explode(',', trim($this->final_stage_teams_ids, ','));
            switch ($schedule_type) {
                case 'team':
                    $this->_finalStageTeams = Team::whereIn('id', $teamIDs)->orderBy('name')->select(['id', 'name', 'logo'])->get();
                    break;
                case 'individual':
                    $this->_finalStageTeams = User::whereIn('id', $teamIDs)->orderBy('name')->select(['id', 'name', 'logo'])->get();
                    break;
                default:
                    $this->_finalStageTeams = new Collection();
                    break;
            }
        }

        return $this->_finalStageTeams;
    }

    //


    public function getDateStringAttribute()
    {
        return Helper::displayDate($this->start_date) . ' to ' . Helper::displayDate($this->end_date);
    }


    public function cartDetails()

    {
        return $this->hasMany('App\Model\CartDetails', 'event_id', 'id');
    }

    public function getCityAttribute()
    {
        // return $this->attributes['city'];
        return object_get(CityRepository::getModel($this->city_id), 'city_name');
    }

    public function getStateAttribute()
    {
        // return $this->attributes['state'];
        return object_get(StateRepository::getModel($this->state_id), 'state_name');
    }

    public function getCountryAttribute()
    {
        // return $this->attributes['country'];
        return object_get(CountryRepository::getModel($this->country_id), 'country_name');
    }

    public function getRoundStageString($round_number)
    {
        $count = $this->final_stage_teams;
        $total_rounds = ceil(log($count, 2));

        $round_names = [
            -2 => '',
            -1 => 'WINNER',
            0 => 'FINAL',
            1 => 'SEMI FINAL',
            2 => 'QUARTER FINAL'
        ];

        return array_get($round_names, $total_rounds - $round_number, "ROUND " . $round_number);
    }


    public function playerStanding(){
        return self::getPlayerStanding($this->sports_id, $this->id);
    }

    public static function getPlayerStanding($sport_id, $tournament_id)
    {
        switch ($sport_id) {
            case  Sport::$SOCCER:
                $player = SoccerPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'soccer_player_matchwise_stats.match_id')
                    ->join('teams', 'teams.id', '=', 'soccer_player_matchwise_stats.team_id')
                    ->join('users', 'users.id', '=', 'soccer_player_matchwise_stats.user_id')
                    ->where('match_schedules.tournament_id', $tournament_id)
                    ->select('soccer_player_matchwise_stats.*', 'users.*')
                    ->selectRaw('sum(yellow_cards) as yellow_cards')
                    ->selectRaw('count(match_schedules.id) as matches')
                    ->selectRaw('sum(red_cards) as red_cards')
                    ->selectRaw('sum(goals_scored) as goals')
                    ->orderBy('goals', 'desc')
                    ->groupBy('user_id')
                    ->get();
                break;
            case Sport::$HOKKEY:
                $player = HockeyPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'hockey_player_matchwise_stats.match_id')
                    ->join('teams', 'teams.id', '=', 'hockey_player_matchwise_stats.team_id')
                    ->join('users', 'users.id', '=', 'hockey_player_matchwise_stats.user_id')
                    ->where('match_schedules.tournament_id', $tournament_id)
                    ->select('hockey_player_matchwise_stats.*', 'users.*')
                    ->selectRaw('sum(yellow_cards) as yellow_cards')
                    ->selectRaw('count(match_schedules.id) as matches')
                    ->selectRaw('sum(red_cards) as red_cards')
                    ->selectRaw('sum(goals_scored) as goals')
                    ->orderBy('goals', 'desc')
                    ->groupBy('user_id')
                    ->get();
                break;
            case Sport::$BASKETBALL:
                $player = BasketballPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'basketball_player_matchwise_stats.match_id')
                    ->join('teams', 'teams.id', '=', 'basketball_player_matchwise_stats.team_id')
                    ->join('users', 'users.id', '=', 'basketball_player_matchwise_stats.user_id')
                    ->where('match_schedules.tournament_id', $tournament_id)
                    ->select('basketball_player_matchwise_stats.*', 'users.*')
                    ->selectRaw('sum(points_1) as points_1')
                    ->selectRaw('count(match_schedules.id) as matches')
                    ->selectRaw('sum(points_2) as points_2')
                    ->selectRaw('sum(points_3) as points_3')
                    ->selectRaw('sum(total_points) as total_points')
                    ->selectRaw('sum(fouls) as fouls')
                    ->orderBy('total_points', 'desc')
                    ->groupBy('user_id')
                    ->get();
                break;
            case Sport::$KABADDI:
                $player = KabaddiPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'kabaddi_player_matchwise_stats.match_id')
                    ->join('teams', 'teams.id', '=', 'kabaddi_player_matchwise_stats.team_id')
                    ->join('users', 'users.id', '=', 'kabaddi_player_matchwise_stats.user_id')
                    ->where('match_schedules.tournament_id', $tournament_id)
                    ->select('kabaddi_player_matchwise_stats.*', 'users.*')
                    ->selectRaw('sum(points_1) as points_1')
                    ->selectRaw('count(match_schedules.id) as matches')
                    ->selectRaw('sum(points_2) as points_2')
                    ->selectRaw('sum(points_3) as points_3')
                    ->selectRaw('sum(total_points) as total_points')
                    ->selectRaw('sum(fouls) as fouls')
                    ->orderBy('total_points', 'desc')
                    ->groupBy('user_id')
                    ->get();
                break;
            case Sport::$WATER_POLO:
                $player = WaterpoloPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'waterpolo_player_matchwise_stats.match_id')
                    ->join('teams', 'teams.id', '=', 'waterpolo_player_matchwise_stats.team_id')
                    ->join('users', 'users.id', '=', 'waterpolo_player_matchwise_stats.user_id')
                    ->where('match_schedules.tournament_id', $tournament_id)
                    ->select('waterpolo_player_matchwise_stats.*', 'users.*')
                    ->selectRaw('sum(points_1) as points_1')
                    ->selectRaw('count(match_schedules.id) as matches')
                    ->selectRaw('sum(total_points) as total_points')
                    ->selectRaw('sum(fouls) as fouls')
                    ->orderBy('total_points', 'desc')
                    ->groupBy('user_id')
                    ->get();
                break;
            case Sport::$CRICKET:
                $player = [];
                $player['batting'] = CricketPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'cricket_player_matchwise_stats.match_id')
                    ->join('teams', 'teams.id', '=', 'cricket_player_matchwise_stats.team_id')
                    ->join('users', 'users.id', '=', 'cricket_player_matchwise_stats.user_id')
                    ->where('match_schedules.tournament_id', $tournament_id)
                    ->select('cricket_player_matchwise_stats.*', 'users.*')
                    ->selectRaw('count(innings) as innings_bat')
                    ->selectRaw('sum(totalruns) as totalruns')
                    ->selectRaw('sum(balls_played) as balls_played')
                    ->selectRaw('sum(fifties) as fifties')
                    ->selectRaw('sum(hundreds) as hundreds')
                    ->selectRaw('sum(fours) as fours')
                    ->selectRaw('sum(sixes) as sixes')
                    ->selectRaw('sum(IF(bat_status="notout", 1, 0)) as notouts')
                    ->selectRaw('max(totalruns) as highscore')
                    ->orderBy('totalruns', 'desc')
                    ->groupBy('user_id')
                    ->get();
                $player['bowling'] = CricketPlayerMatchwiseStats::join('match_schedules', 'match_schedules.id', '=', 'cricket_player_matchwise_stats.match_id')
                    ->join('teams', 'teams.id', '=', 'cricket_player_matchwise_stats.team_id')
                    ->join('users', 'users.id', '=', 'cricket_player_matchwise_stats.user_id')
                    ->where('match_schedules.tournament_id', $tournament_id)
                    ->select('cricket_player_matchwise_stats.*', 'users.*')
                    ->selectRaw('count(DISTINCT(match_id)) as matches')
                    ->selectRaw('sum(wickets) as wickets')
                    ->selectRaw('sum(runs_conceded) as runs_conceded')
                    ->selectRaw('sum(overs_bowled) as overs_bowled')
                    ->selectRaw('SUM(innings) innings_bowl')
                    ->selectRaw('count(innings) as innings_bowled')
                    ->selectRaw('CAST(AVG(average_bowl) AS DECIMAL(10,2))  average_bowl')
                    ->selectRaw('CAST(AVG(ecomony) AS DECIMAL(10,2)) ecomony')
                    ->orderBy('wickets', 'desc')
                    ->groupBy('user_id')
                    ->get();
                return $player;
                break;
            default:
                # code...
                $player = [];
                break;
        }

        return $player;
    }


}