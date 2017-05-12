<?php

namespace App\Model;

use App\Helpers\Helper;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Organization extends Model
{

        use SoftDeletes,
            Eloquence;

        protected $table             = 'organization';
        protected $dates             = ['deleted_at'];
        protected $searchableColumns = ['name', 'email', 'city'];
        protected $fillable          = array('user_id', 'name', 'contact_number',
                'email', 'organization_type', 'social_facebook', 'social_twitter',
                'social_linkedin', 'social_googleplus', 'website_url', 'about', 'location',
                'longitude',
                'latitude', 'address', 'city_id', 'city', 'state_id', 'state', 'country_id',
                'country', 'zip', 'alternate_contact_number', 'contact_name', 'logo',
                'subdomain'
        );
        protected $morphClass        = 'organization';


    protected $appends = ['logoImage'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function staff()
    {
        return $this->belongsToMany(User::class, 'organization_staffs',
            'organization_id', 'user_id')
                    ->withPivot('organization_role_id', 'status','id as staff_id')
                    ->withTimestamps();
    }




    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(OrganizationGroup::class, 'organization_id', 'id');
    }

        public function photos()
        {
                $this->morphClass = 'organization';
                return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type', 'organization')->where('is_album_cover', 1);
        }

        public function photo()
        {
                $this->morphClass = 'form_gallery_organization';
                return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type', 'form_gallery_organization')->where('is_album_cover', 1);
        }

        public function user()
        {
                return $this->hasOne('App\User', 'id', 'user_id');
        }

        public function teamplayers()
        {
                return $this->hasMany('App\Model\Team', 'organization_id', 'id');
        }

        //Global team search
        public function searchResults($req_params)
        {
                // echo $req_params['search_city_id'];exit;
                $user_id = Auth::user()->id;
                $offset  = !empty($req_params['offset']) ? $req_params['offset'] : 0;
                $limit   = !empty($req_params['limit']) ? $req_params['limit'] : config('constants.LIMIT');

                $query = $this->search($req_params['search_by']);
                
                
                if (trim($req_params['sport']) != '')
                {
                        $query = $query->leftJoin('teams', function ($join) use ($req_params) {
                                $join->on('organization.id', '=', 'teams.organization_id')
                                     ->whereIn('teams.sports_id',explode(",",$req_params['sport']));
                        });
                        $query = $query->select('organization.*', 'teams.sports_id');
                        //$query = $query->select('organization.*', DB::raw('GROUP_CONCAT(DISTINCT teams.sports_id) AS sports_ids'));
                }
                
                if (trim($req_params['search_city_id']) != '')
                {
                        $query = $query->where('organization.city_id', trim($req_params['search_city_id']));
                }

                $query = $query->where('organization.isactive', 1);
                //$query = $query->whereNotIn('organization.user_id', [$user_id]);
                $query = $query->whereNull('organization.deleted_at');
                
                if (trim($req_params['sport']) != '')
                {
                        $query = $query->groupBy('organization.id');
                }
                
                $totalresult = $query->get();
                $total       = count($totalresult);
                $result      = $query->limit($limit)->offset($offset)->orderBy('organization.updated_at', 'desc')->get();
                $response    = array('result' => $result, 'total' => $total);
                // Helper::printQueries();exit;
                return $response;
        }
        //End

        function followers(){
            return $this->hasMany('App\Model\Followers','type_id')->whereType('organization');
        }

        public function getLogoImageAttribute()
        {
            return Helper::getImagePath($this->logo,'organization');
        }

        public function tournaments(){
            return $this->hasManyThrough('App\Model\Tournaments','App\Model\TournamentParent');
        }

        public function parent_tournaments(){
            return $this->hasMany('App\Model\TournamentParent', 'organization_id');
        }


        // public function reports(){

        // }

        public function news(){
            return $this->hasMany('App\Model\news');
        }

        public function polls(){
            return $this->hasMany('App\Model\poll');
        }
      




}
