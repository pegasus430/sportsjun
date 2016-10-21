<?php

namespace App\Model;

use App\Helpers\Helper;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

//use SoftDeletes;

class Team extends Model
{
    use SoftDeletes,
        Eloquence;

    protected $table = 'teams';

    protected $searchableColumns = ['name', 'location'];

    protected $morphClass = 'teams';

    protected $fillable = [
        'team_owner_id',
        'sports_id',
        'organization_id',
        'name',
        'address',
        'city_id',
        'state_id',
        'country_id',
        'zip',
        'description',
        'city',
        'state',
        'country',
        'location',
        'team_available',
        'player_available',
        'gender',
        'team_level',
        'logo',
    ];

    protected $dates = ['deleted_at'];

    //one team can have many players
    public function teamplayers()
    {
        return $this->hasMany('App\Model\TeamPlayers', 'team_id', 'id');
    }

    public function photos()
    {
        return $this->morphMany('App\Model\Photo', 'imageable')
                    ->where('is_album_cover', '1');
    }

    public function matchscheduleone()
    {
        return $this->hasMany('App\Model\MatchSchedule', 'a_id', 'id');
    }

    public function matchscheduletwo()
    {
        return $this->hasMany('App\Model\MatchSchedule', 'b_id', 'id');
    }

    public function sports()
    {
        return $this->hasOne('App\Model\Sport', 'id', 'sports_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'team_owner_id');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'id', 'team_owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organizationGroups()
    {
        return $this->belongsToMany(OrganizationGroup::class,
            'organization_group_teams', 'team_id', 'organization_group_id');
    }


    //Global team search
    public function searchResults($req_params)
    {
        // echo $req_params['search_city_id'];exit;
        $user_id = Auth::user()->id;
        $offset = !empty($req_params['offset']) ? $req_params['offset'] : 0;
        $limit = !empty($req_params['limit']) ? $req_params['limit']
            : config('constants.LIMIT');

        $query = $this->search($req_params['search_by']);

        if (trim($req_params['sport']) != '') {
            $query = $query->whereIn('sports_id',
                explode(",", $req_params['sport']));

        }
        if (trim($req_params['gender']) != '') {
            $query =
                $query->whereIn('gender', explode(",", $req_params['gender']));
        }
        if (trim($req_params['avialability']) != '') {
            $query = $query->whereIn('team_available',
                explode(",", $req_params['avialability']));
        }
        if (trim($req_params['joinavialability']) != '') {
            $query = $query->whereIn('player_available',
                explode(",", $req_params['joinavialability']));
        }
        if (trim($req_params['search_city_id']) != '') {
            $query =
                $query->where('city_id', trim($req_params['search_city_id']));
        }

        $query = $query->where('isactive', 1);
        $query = $query->whereNotIn('team_owner_id', [$user_id]);
        $query = $query->whereNull('deleted_at');

        $totalresult = $query->get();
        $total = count($totalresult);
        $result = $query->limit($limit)
                        ->offset($offset)
                        ->orderBy('updated_at', 'desc')
                        ->get();
        $response = ['result' => $result, 'total' => $total];

        // Helper::printQueries();exit;
        return $response;
    }
    //End

    //Get Managing and joined teams
    public function getTeamsByRole($user_id, $flag = 0)
    {
        $condition = '';
        if ($flag == 0) {
            $condition = " and t.isactive=1 ";
        }
        $query = "SELECT 
			   GROUP_CONCAT(CASE WHEN tp.role = 'owner' OR tp.role = 'manager'  THEN team_id END ) AS managing_teams, 
			   GROUP_CONCAT(CASE WHEN tp.role != 'owner' AND tp.role != 'manager' THEN team_id END ) AS joined_teams
			   FROM team_players as tp
			   inner join teams as t on tp.team_id=t.id
			   WHERE tp.user_id = $user_id AND tp.status != 'rejected' and tp.deleted_at is null $condition  and t.deleted_at is null order by t.isactive desc";
        $team_array = DB::select(DB::raw($query));

        return $team_array;
    }

    //End

    public function sport()
    {
        return $this->hasMany('App\Model\Sport', 'id', 'sports_id');
    }

    public function requests()
    {
        return $this->hasMany('App\Model\RequestsModel', 'to_id', 'id');
    }

    public function playersByRole($role){
        if ($this->teamplayers)
           return $this->teamplayers->where('role',$role);
        else
            return $this->teamplayers()->where('role',$role)->get();
    }

    /**
     * Attributes
     */

    public static function logoImage($id){
        $logo = Photo::where('imageable_id', $id)
            ->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))
            ->orderBy('id', 'desc')
            ->first();
        if ($logo && $logo->url) {
            return Helper::getImagePath($logo->url,'teams');
        }
    }

    public function getLogoImageAttribute()
    {
        return self::logoImage($this->id);
    }

    public function getOwnersNameAttribute(){
        if( $this->teamplayers)
            return $this->teamplayers->where('role','owner')->implode('user.name',',');
    }

    public function getManagersNameAttribute(){
        if( $this->teamplayers)
            return $this->teamplayers->where('role','manager')->implode('user.name',',');
    }

    public function getCoachsNameAttribute(){
        if( $this->teamplayers)
            return $this->teamplayers->where('role','coach')->implode('user.name',',');
    }

}
