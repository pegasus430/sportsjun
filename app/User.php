<?php

namespace App;

use App\Model\Organization;
use App\Model\OrganizationRole;
use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Validator;
use Sofa\Eloquence\Eloquence;

class User extends Model implements AuthenticatableContract,
									AuthorizableContract,
									CanResetPasswordContract
{

	use Authenticatable,
		Authorizable,
		CanResetPassword,
		SoftDeletes,
		Eloquence;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $morphClass = 'user_photo';

	protected $table = 'users';

	protected $searchableColumns = ['name', 'location'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'firstname',
		'lastname',
		'name',
		'email',
		'password',
		'dob',
		'gender',
		'location',
		'address',
		'city_id',
		'city',
		'state_id',
		'state',
		'country_id',
		'country',
		'zip',
		'contact_number',
		'about',
		'newsletter',
		'profile_updated',
		'role',
		'verification_key',
		'is_verified',
		'isactive',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	protected $dates = ['deleted_at'];

    /** Extra */
    /**
     * @param $orgId
     *
     * @return mixed
     */
    public function roleForOrganization($orgId)
    {
        return $this->staff_role()->wherePivot('organization_id', $orgId)
                    ->first();
    }

	/**
	 * A user can be staff of many organizations.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function staff_of_organizations()
	{
		return $this->belongsToMany(Organization::class, 'organization_staffs',
			'user_id', 'organization_id')
					->withPivot('organization_role_id', 'status')
					->withTimestamps();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function staff_role()
	{
        return $this->belongsToMany(OrganizationRole::class,
            'organization_staffs', 'user_id', 'organization_role_id');
	}
    

	/**
	 *
	 * @param type $data
	 * @param type $context
	 */
	public static function validate($data, $context)
	{

		if ($context == 'edit') {
			$rules = [
				'name'           => 'required|max:50',
				'dob'            => 'date',
				'contact_number' => 'numeric',
				'zip'            => 'numeric',
			];
			$messages = [
				'name.required'          => trans('validation.required'),
				'name.max'               => trans('validation.max.numeric'),
				'dob.date'               => trans('validation.date'),
				'contact_number.numeric' => trans('validation.numeric',
					['attribute' => 'contact number']),
				'zip.numeric'            => trans('validation.numeric'),
			];
		}

		return Validator::make($data, $rules, $messages);
	}

	public function providers()
	{
		return $this->hasMany('App\Model\UserProvider');
	}

	public function usersfollowingsports()
	{
		return $this->hasMany('App\Model\UserStatistic', 'user_id');
	}

	//a user can be a multiple team player
	public function userdetails()
	{
		return $this->hasMany('App\Model\TeamPlayer');
	}

	public function photos()
	{
		return $this->morphMany('App\Model\Photo', 'imageable')
					->where('is_album_cover', '1');
	}

	public function tournaments()
	{
		return $this->hasMany('App\Model\Tournaments', 'created_by', 'id');
	}

	public function searchResults($req_params)
	{
		$user_id = Auth::user()->id;
		$offset = !empty($req_params['offset']) ? $req_params['offset'] : 0;
		$limit = !empty($req_params['limit']) ? $req_params['limit']
			: config('constants.LIMIT');
		$query = $this->search($req_params['search_by'])
					  ->join('user_statistics', 'user_statistics.user_id', '=',
						  'users.id');

		if (trim($req_params['sport']) != '') {
			$sports_arr = explode(",", $req_params['sport']);
			$i = 0;
			foreach ($sports_arr as $sport) {
				$sport = "," . $sport . ",";
				if ($i == 0) {
					$query = $query->where('user_statistics.following_sports',
						'LIKE', "%$sport%");
				} else {
					$query = $query->orwhere('user_statistics.following_sports',
						'LIKE', "%$sport%");
				}

				$i++;
			}
		}
		if (trim($req_params['gender']) != '') {
			$query = $query->whereIn('users.gender',
				explode(",", $req_params['gender']));
		}
		/* if(trim($req_params['avialability']) != ''){
			$query = $query->whereIn('team_available',explode(",",$req_params['avialability']));
		} */
		//Available to join a team
		if (trim($req_params['joinavialability']) == 1) {
			$sports_arr = explode(",", $req_params['sport']);
			$i = 0;
			foreach ($sports_arr as $sport) {
				$sport = "," . $sport . ",";
				if ($i == 0) {
					$query =
						$query->where('user_statistics.allowed_sports', 'LIKE',
							"%$sport%");
				} else {
					$query = $query->orwhere('user_statistics.allowed_sports',
						'LIKE', "%$sport%");
				}

				$i++;
			}


		}

		if (trim($req_params['search_city_id']) != '') {
			$query =
				$query->where('city_id', trim($req_params['search_city_id']));
		}

		$query = $query->where('users.isactive', 1);
		$query = $query->whereNotIn('users.id', [$user_id]);
		$query = $query->whereNull('users.deleted_at');

		$totalresult = $query->get();
		$total = count($totalresult);
		$result = $query->limit($limit)
						->offset($offset)
						->orderBy('users.updated_at', 'desc')
						->get();

		//echo "<pre>";print_r($result);exit;
		$response = ['result' => $result, 'total' => $total];

		return $response;
	}

	public function userscheduleone()
	{
		return $this->hasMany('App\Model\MatchSchedule', 'a_id', 'id');
	}

	public function userscheduletwo()
	{
		return $this->hasMany('App\Model\MatchSchedule', 'b_id', 'id');
	}

	public static function checkPermission($params)
	{
		if ($params['loggedin_user_role'] != 'general'
			|| $params['loggedin_user_role'] == $params['owner_user_id']
		) {
			return true;
		} else {
			return false;
		}
	}
}
