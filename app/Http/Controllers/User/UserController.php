<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Model\Photo;
use App\Model\Country;
use App\Model\State;
use App\Model\UserStatistic;
use App\Model\City;
use App\Model\Team;
use App\Model\UserProvider;
use Request;
use Session;
use Hash;
use Auth;
use Socialite;
use Carbon\Carbon;
use Response;
use DB;
use App\Helpers\Helper;
use Illuminate\Contracts\Auth\Guard;

class UserController extends Controller {

    public function __construct(Guard $auth){
        $this->auth = $auth;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $userDetails = User::where('id', Auth::user()->id)->first();
        $enum = config('constants.ENUM.USERS.ROLES'); 
        unset($enum['superadmin']);
        if (!empty($userDetails)) {
            $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
            $cities = [];
            if ($userDetails->state_id) {
                $cities = City::where('state_id', $userDetails->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
            }
//            if (empty($userDetails->dob))
//                $userDetails->dob = Carbon::now()->toDateString();
           
            if (empty($userDetails->dob) || $userDetails->dob=='0000-00-00')
            {    
                $userDetails->dob = NULL;
            }    
            else{
//              $userDetails->dob = date(config('constants.DATE_FORMAT.DISPLAY_TIME_FORMAT'), strtotime($userDetails->dob));
              $userDetails->dob = date(config('constants.DATE_FORMAT.VALIDATION_DATE_FORMAT'), strtotime($userDetails->dob));
              
            }
            //echo $userDetails->dob;dd($userDetails->dob);
            return view('userprofile.editprofile')->with('userDetails',$userDetails->toArray())
                            ->with('states', ['' => 'Select State'] + $states)
                            ->with('cities', ['' => 'Select City'] + $cities)
                            ->with('enum',['' => 'Select Role'] + $enum)
                            ->with('edit_user_id', Auth::user()->id)
							->with( 'userDetails', $userDetails  );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    /* public function update($id) {
      //
      $request = Request::all();
      $validation = User::validate(Request::all(),'edit');

      if ($validation->fails()) {
      return redirect()->back()->withInput()->withErrors($validation->errors());
      }

      $user = User::find(Auth::user()->id);
      $user->name = $request['name'];
      $user->dob = date('Y-m-d', strtotime($request['dob']));
      $user->gender = $request['gender'];
      $user->address = $request['address'];
      $user->city_id = $request['city_id'];
      $user->city = !empty($request['city_id'])?City::where('id', $request['city_id'])->first()->city_name:'';
      $user->state_id = $request['state_id'];
      $user->state = !empty($request['state_id'])?State::where('id', $request['state_id'])->first()->state_name:'';
      $user->country_id = config('constants.COUNTRY_INDIA');
      $user->country = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
      $user->zip = $request['zip'];
      $user->contact_number = $request['contact_number'];
      $user->about = $request['about'];
      $user->profile_updated = 1;
      $user->newsletter = $request['newsletter'];

      $user->save();

      return redirect()->back()->with('status', 'Your profile updated succesfully');
      } */

    public function update(Requests\UpdateUserProfileRequest $request, $id) {
        
        $isProfileUpdated = $request['profile_updated'];
        $file = $request['image'];
        $destinationPath = config('constants.USER_PROFILE_UPLOAD_PATH');
        if(!empty($request['filelist_profilepic']))
		{
             $userPhotos = Photo::where(['imageable_id'=>$id, 'imageable_type' => config('constants.PHOTO.USER_PHOTO')])->update(['is_album_cover' => 0]);

		
			 $request['user_id'] = Auth::user()->id;
			//Upload Photos
			$albumID = 1;//Default album if no album is not selected.
			$coverPic = 1;
			if(isset($input['album_id']) && $input['album_id'])
				$albumID = $input['album_id'];
			if(isset($input['cover_pic']) && $input['cover_pic'])
				$coverPic = $input['cover_pic']; 
			Helper::uploadPhotos($request['filelist_profilepic'],config('constants.PHOTO_PATH.USERS_PROFILE'),$id,$albumID,$coverPic,config('constants.PHOTO.USER_PHOTO'),$user_id = $id);
			$filename = array_filter(explode(',', $request['filelist_profilepic']));
			if(!empty($filename)) {
				foreach ($filename as $key => $value) {
					$file_name = explode('####SPORTSJUN####',$value);
					$url = $file_name[1];
					session(['profilepic' => $url]);
					break;
				}	
			}
			//End Upload Photos        
		}
	    $request['name']=$request['firstname'].' '.$request['lastname'];
      if (empty($request['dob']) || $request['dob']=='0000-00-00'){
        $request['dob'] = NULL;        
      }else{
          
//        $request['dob'] = date('Y-m-d', strtotime($request['dob']));
        $request['dob'] = Helper::storeDate($request['dob'],'date');
      }
        $request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
        $request['country_id'] = config('constants.COUNTRY_INDIA');
        $request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
        $location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
        $request['profile_updated'] = 1;    
        User::find(Auth::user()->id)->update($request->except(['_method', '_token']));
		
		$logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->where('is_album_cover',1)->get()->toArray();
			
		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				    User::where('id', $id)->update(['logo' => $l['url']]);
			}
			
		}
        if($isProfileUpdated) {       
            return redirect()->back()->with('status', trans('message.users.userprofileupdate'));
        }else {
            return redirect(url('/showsportprofile',[Auth::user()->id]))->with('status', trans('message.users.userprofileupdate'));
        }    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    /**
     * Function to display change password form
     */
    public function changepassword() {
        return view('auth.changepassword');
    }

    /*
     * Function to update new password.
     */

    public function updatepassword() {
        $request = Request::all();
        $rules = ['old_password' => 'required', 'password' => 'required|confirmed|min:6', 'password_confirmation' => 'required'];
        $v = Validator::make($request, $rules);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $user = User::find(Auth::user()->id);
        if (!Hash::check($request['old_password'], $user->password)) {
            return redirect()->back()->withErrors(["old_password" => [0 => trans('message.users.passwordvalidation')]]);
        }

        $user->password = bcrypt($request['password']);
        $user->save();
        return redirect()->back()->with('status', trans('message.users.passwordchanged'));
    }

    public function redirectToProvider($provider = '') {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback($provider = '') {
//        dd($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('auth/' . $provider);
        }

        $authUser = $this->findOrCreateUser($socialUser, $provider);
        if($authUser->isactive==0) {
            return redirect(url('/auth/login'))->withErrors(['socialloginerror'=>trans('message.login.accountdeactived',['email'=>$authUser->email])]);
        }else{
            Auth::login($authUser, true);
            session(['socialuser' => $provider, 'avatar' => $socialUser->avatar]);
            return redirect('/');
        }
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $SocialUser,$pr
     * @return User
     */
    private function findOrCreateUser($socialUser, $provider) {
        $providerExist = UserProvider::where('provider_id', $socialUser->id)->first();
        //If email is empty then based on API id check user exist and return data
        if(empty($socialUser->email)) {
                if(count($providerExist)) {
                        $authUser = User::where('id', $providerExist->user_id)->first();
                        return $authUser;
                }
        }
        else
        {
                $authUser = User::where('email', $socialUser->email)->first();
                if ($authUser) {
                        if (!$providerExist) {
                                UserProvider::create([
                                        'user_id' => $authUser->id,
                                        'provider' => $provider,
                                        'provider_id' => !empty($socialUser->id) ? $socialUser->id : '',
                                        'provider_token' => !empty($socialUser->token) ? $socialUser->token : '',
                                        'avatar' => !empty($socialUser->avatar) ? $socialUser->avatar : '',
                                ]);
                        }
                        return $authUser;
                }
        }
        $newAuthUser = User::create([
                    'firstname' => !empty($socialUser->user->first_name) ? $socialUser->user->first_name : $socialUser->name,
                    'name' => !empty($socialUser->name) ? $socialUser->name : NULL,
                    'email' => !empty($socialUser->email) ? $socialUser->email : NULL,
                    'isactive' => 1
        ]);
        $userProvider = [
            'user_id' => $newAuthUser->id,
            'provider' => $provider,
            'provider_id' => !empty($socialUser->id) ? $socialUser->id : '',
            'provider_token' => !empty($socialUser->token) ? $socialUser->token : '',
            'avatar' => !empty($socialUser->avatar) ? $socialUser->avatar : '',
        ];
        UserProvider::firstOrCreate($userProvider);

        return $newAuthUser;
    }

    public function getCities() {
        $stateId = Request::get('id');
        if ($stateId)
            $cities = City::where('state_id', $stateId)->orderBy('city_name')->get(['id', 'city_name'])->toArray();

        return Response::json(!empty($cities) ? $cities : []);
    }
	//function to view user profile
	public function info($id='')
	{
		if($id == '')
			$user_id = Auth::user()->id;
		else
			$user_id = $id;
		$userDetails = User::where('id', $user_id)->first();
		Helper::setMenuToSelect(3,1);
		return view('userprofile.info',array('userDetails'=>$userDetails));
	}
	public function team($id='')
	{
		if($id == '')
			$user_id = Auth::user()->id;
		else
			$user_id = $id;
		$joinTeamArray = array();
		$followingTeamArray = array();
		$manageTeamArray = array();
		$teamDetails = UserStatistic::where('user_id', $user_id)->first();
		if(isset($teamDetails) && count($teamDetails)>0)
		{
			$joined_team_array = array_filter(explode(',',$teamDetails->joined_teams));
			$following_team_array = array_filter(explode(',',$teamDetails->following_teams));
			$managed_team_array = array_filter(explode(',',$teamDetails->managing_teams));

			foreach($joined_team_array as $join_team_id)
			{
				$team_details = Team::where('id', $join_team_id)->first();
				$joinTeamArray[$team_details->id] = $team_details->name;
			}
			foreach($following_team_array as $follow_team_id)
			{
				$follow_details = Team::where('id', $follow_team_id)->first();
				$followingTeamArray[$follow_details->id] = $follow_details->name;
			}
			foreach($managed_team_array as $manage_team_id)
			{
				$manage_details = Team::where('id', $manage_team_id)->first();
				$manageTeamArray[$manage_details->id] = $manage_details->name;
			}
		}
		Helper::setMenuToSelect(3,3);
		return view('userprofile.team',array('joinTeamArray'=>$joinTeamArray,'followingTeamArray'=>$followingTeamArray,'manageTeamArray'=>$manageTeamArray,'userId'=>$user_id));
	}

    /*
    * returns {status: false} or {status: true}
    */
    public function authCheck()
    {
        return response(['status' => $this->auth->check()])
                      ->header('Content-Type', 'application/json');
    }
    //function to get notificaitons
    public function getNotifications()
    {
        $limit = config('constants.LIMIT');
        $offset = 0;
        $notifications = Helper::getNotifications($limit,$offset);
        $notificationsCount = Helper::getNotificationsCount();
        return view('userprofile.notifications')
                ->with('notifications',$notifications)
                ->with('notificationsCount',$notificationsCount)
                ->with('limit',$limit)
                ->with('offset',$limit+$offset);
    }
    //function to get view more notificaitons
    public function getViewMoreNotifications()
    {
        $limit = !empty(Request::get('limit'))?Request::get('limit'):0;
        $offset = !empty(Request::get('offset'))?Request::get('offset'):0;
        $notifications = Helper::getNotifications($limit,$offset);
        $notificationsCount = Helper::getNotificationsCount();
        return view('userprofile.viewmorenotifications')
                ->with('notifications',$notifications)
                ->with('notificationsCount',$notificationsCount)
                ->with('limit',$limit)
                ->with('offset',$limit+$offset);
    }    
}
