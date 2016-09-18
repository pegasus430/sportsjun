<?php

namespace App\Http\Controllers\admin;

//namespace App\Http\Controllers\Controller;

use App\User;
use App\Sports;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;
use Auth;
use Zofe\Rapyd\RapydServiceProvider;
use App\Http\Requests;
use Request;
use App\SportsQuestions;
use App\SportsQuestionOptions;
use App\Model\State;
use App\Model\City;
use App\Model\Country;
use Carbon\Carbon;
use App\Model\Photo;
use App\Helpers\Helper;
use App\Model\UserStatistic;
use App\Helpers\SendMail;

use PDO;

class UserController extends Controller {
	public function listusers()
	{
	   
		$filter = \DataFilter::source(User::with('usersfollowingsports','providers')->orderBy('isactive','desc'));
		$filter->add('name','User Name', 'text');
		$filter->add('email','User Email', 'text');

        $filter->add('role','role(0: ,admin:admin,general:general)', 'select')->options(['Select Role','admin','general'])
         ->scope( function ($query, $value) {
				 if($value == 0)
				 {
					 return $query->whereIn('role', ["admin","general"]);
				 }
				else if($value == 1)
				{
					return $query->whereIn('role', ["admin"]);
				}else if($value == 2)
				{
					return $query->whereIn('role', ["general"]);
				}
				
			});	
			
			$filter->add('isactive','isactive(0: ,active:1,inactive:0)', 'select')->options(['Select Status','ACTIVE','INACTIVE'])
         ->scope( function ($query, $value) {
			 
				 if($value == 0)
				 {
					 return $query->whereIn('isactive', [0,1]);
				 }
				else if($value == 1)
				{
					return $query->where('isactive',1);
				}else if($value == 2)
				{
					return $query->where('isactive',0);
					
				}
				
			});	
			
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
		//$grid = \DataGrid::source(User::with('providers'));
		$grid = \DataGrid::source($filter);
		 $grid->attributes(array("class"=>"table table-striped"));
        
		$grid->add('{{ $name }}','USE NAME',true);
        $grid->add('email','User Email');
		$grid->add('city','Location');
		 $grid->add('<img src="{{URL()}}/uploads/user_profile/{{ $logo }}" onerror=this.onerror=null;this.src="{{URL()}}/images/default-profile-pic.jpg" height=30 width=30>','LOGO');
        $grid->add('{{ implode(", ", $providers->lists("provider")->all()) }}','Providers');
	
	   // $grid->add('{{ implode(", ", $usersfollowingsports->lists("following_sports")->all()) }}','sports');
	    $grid->add('{{  Helper::getUserFollowingSportNames(implode(", ", $usersfollowingsports->lists("following_sports")->all())) }}','sports');
		
		
		
      // $grid->add('Status','Status')->cell( function( $value, $row) {
			// if($row->isactive==1)
			// {
				// return 'Active';
					
			// }else{
				// return 'Inactive';
			// }
			
		// }); 
		
		$grid->add('view','Active & Inactive')->cell( function( $value, $row) {
			
				if($row->isactive==0)
				{
					$status = 1;//do inactive
					return "<a onclick='changeuserStatus(".$status.",".$row->id.");' >Active</a>";
				}
					
				else
				{
					$status = 2;//do active
					return "<a onclick='changeuserStatus(".$status.",".$row->id.");'>Inactive</a>";
				}
					
			
			
		}); 
		
		
	
		$grid->link('/admin/createuser',"Add New", "TR");  //add button
		
	    $grid->edit( url('/admin/edituser'), 'Operation', 'modify');
        $grid->orderBy('id','desc');
        $grid->paginate(config('constants.DEFAULT_PAGINATION'));
		$feild_name = 'Users List';
	
		
		return view('admin.userprofile.filtergrid',array('grid' => $grid,'filter' =>$filter, 'feild_name'=>$feild_name));
	}
	public function createuser()
	{
		$is_from_admin = 1;
		$enum = config('constants.ENUM.USERS.ROLES'); 
		unset($enum['superadmin']);
		$states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
		$cities = [];
       // $cities = City::where('state_id', 1)->orderBy('city_name')->lists('city_name', 'id')->all();
		return view('admin.userprofile.createprofile',array('is_from_admin' => $is_from_admin,'states' => ['' => 'Select State'] + $states,'cities' => ['' => 'Select City'] + $cities,'enum'=>['' => 'Select Role'] + $enum));
	}
	public function createuserprofile(Requests\UpdateUserProfileRequest $request)
	{
		
		$request = Request::all();
		 
		$generatedPassword= str_random(6);
	    $password=  bcrypt( $generatedPassword);
		$users = new User();
		$users->firstname = Request::get('firstname');
		$users->lastname = Request::get('lastname');
		$users->name = Request::get('firstname').' '.Request::get('lastname');
		$users->dob = !empty(Request::get('dob'))? Helper::storeDate(Request::get('dob'),'date'):null;
		$users->gender = Request::get('gender');		
		$email = Request::get('email');
		$users->address = Request::get('address');
		$users->city_id = Request::get('city_id');
		$users->city = !empty(Request::get('city_id')) ? City::where('id',Request::get('city_id'))->first()->city_name : 'null';
		$users->state_id = Request::get('state_id');
		$users->state = !empty(Request::get('state_id')) ? State::where('id', Request::get('state_id'))->first()->state_name : 'null';
		$users->country_id = config('constants.COUNTRY_INDIA');
		$users->country = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
		$locationn=Helper::address($users->address,$users->city ,$users->state,$users->country);
	    $users->location=trim($locationn,",");
		$users->zip = Request::get('zip');
		$users->contact_number = Request::get('contact_number');
		$users->about = Request::get('about');
		$users->newsletter = Request::get('newsletter');
		$users->role = (Request::get('role')>0)?'admin':'general';
		// $users->is_available = !empty($request['is_available'])?$request['is_available']:0;
		
		 if($email !="")
		 {
			 $generatedPassword= str_random(6);
			 $password=  bcrypt( $generatedPassword);
			$users->email =  $email; 
			$users->password = $password;
			$users->verification_key = md5($email);
		 }
		$users->save();
		
				
		$last_inserted_player_id =  $users->id;
	  	 if( $last_inserted_player_id > 0)
			{					      	
				$to_user_id = $users->id;
				$to_email_id=  $users->email;
				$user_name = $users->name;
				$subject =  trans('message.inviteplayer.addusersubject');
				$view_data = array('email'=>$to_email_id,'password'=>$generatedPassword,'user_name'=>$user_name);
				$view = 'emails.addUser';
				$data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>  $to_user_id,'flag'=>'user','send_flag'=>1);
				SendMail::sendmail($data); 

			}
		
		$request['user_id'] = $users->id;
		if(!empty($request['filelist_profilepic']))
		{
			$albumID = 1;//Default album if no album is not selected.
			$coverPic = 1;
			if(isset($input['album_id']) && $input['album_id'])
				$albumID = $input['album_id'];
			if(isset($input['cover_pic']) && $input['cover_pic'])
				$coverPic = $input['cover_pic'];        
			Helper::uploadPhotos($request['filelist_profilepic'],config('constants.PHOTO_PATH.USERS_PROFILE'),$users->id,$albumID,$coverPic,config('constants.PHOTO.USER_PHOTO'),$user_id = $users->id);
			//End Upload Photos        
			//redirect('/');
		}
       $logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->where('imageable_id',$users->id )->where('user_id', Auth::user()->id)->where('is_album_cover',1)->get()->toArray();
	
		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				    User::where('id', $id)->update(['logo' => $l['url']]);
			}
			
		}

		return redirect()->route('listusers')->with('status', trans('message.users.userprofilecreate'));
	}
	public function edituser() {
		$request = Request::all();
		$delete_user_id = Request::get('delete');
		$edit_user_id = Request::get('modify');
		$is_from_admin = 1;
		$enum = config('constants.ENUM.USERS.ROLES'); 
		unset($enum['superadmin']);
		if(isset($edit_user_id) && $edit_user_id>0)
		{

				$enum = config('constants.ENUM.USERS.ROLES'); 
				unset($enum['superadmin']);
			$userDetails = User::where('id', $edit_user_id)->first();
			$user_photo=User::findOrFail($edit_user_id );
			
			if (!empty($userDetails)) {
				$states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
				$cities = [];
				if ($userDetails->state_id) {
					$cities = City::where('state_id', $userDetails->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
				}
				// if (empty($userDetails->dob))
				// $userDetails->dob =Helper::displayDate(Carbon::now()->toDateString());
				 if (!empty($userDetails->dob))
				  $userDetails->dob = Helper::displayDate( $userDetails->dob);
				else
				  $userDetails->dob = null;
				
						return view('admin.userprofile.editprofile')->with('userDetails',$userDetails->toArray())
							->with('is_from_admin',$is_from_admin)
							->with('edit_user_id',$edit_user_id)
							->with('enum',$enum)
                            ->with('states', ['' => 'Select State'] + $states)
                            ->with('user_photo', $user_photo)
                            ->with('cities', ['' => 'Select City'] + $cities)
							->with('enum',['' => 'Select Role'] + $enum);
							
			}
		}else if(isset($delete_user_id) && $delete_user_id>0)
		{
			// User::find($delete_user_id)->delete();
			// return redirect()->route('listusers')->with('status', trans('message.users.userprofiledelete'));
			
			 $user_status  = User::where('id',$delete_user_id)->pluck('isactive');
		   if( $user_status==1)
		   {
			   User::where('id', '=', $delete_user_id)->update(['isactive'=>0]);
				return redirect()->route('listusers')->with('status', 'User is Inactivated.');
		   }else
		   {
			   User::where('id', '=', $delete_user_id)->update(['isactive'=>1]);
				return redirect()->route('listusers')->with('status', 'User is Activated.');
		   }
		}
       
    }
	public function update(Requests\UpdateUserProfileRequest $request, $id)
	{
		$request = Request::all();
		
        $destinationPath = config('constants.USER_PROFILE_UPLOAD_PATH');
        // If profile photo is updated then inserting into photo table.
       
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
			Helper::uploadPhotos($request['filelist_profilepic'],config('constants.PHOTO_PATH.USERS_PROFILE'),$id,$albumID,$coverPic,config('constants.PHOTO.USER_PHOTO'),$user_id = Auth::user()->id);
			//$filename = array_filter(explode(',', $request['filelist_profilepic']));
			//session(['profilepic' => $filename[1]]);
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
		
	
		$users = array();
		$users['firstname'] = Request::get('firstname');
		$users['lastname'] = Request::get('lastname');
		$users['name'] = Request::get('firstname').' '.Request::get('lastname');
		$users['dob'] = !empty(Request::get('dob'))? Helper::storeDate(Request::get('dob'),'date'):null;
		$users['gender'] = Request::get('gender');
		$users['email'] = Request::get('email');
		$users['address'] = Request::get('address');
		$users['city_id'] = Request::get('city_id');
		$users['state_id'] = Request::get('state_id');
		$users['country_id'] = 100;
		$users['zip'] = Request::get('zip');
		$users['contact_number'] = Request::get('contact_number');
		$users['about'] = Request::get('about');
		$users['newsletter'] = Request::get('newsletter');
		
		$users['city'] = !empty($users['city_id']) ? City::where('id', $users['city_id'])->first()->city_name : 'null';
        $users['state'] = !empty($users['state_id']) ? State::where('id', $users['state_id'])->first()->state_name : 'null';
        $users['country_id'] = config('constants.COUNTRY_INDIA');
        $users['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
        $location=Helper::address($users['address'],$users['city'],$users['state'],$users['country']);
	    $users['location']=trim($location,",");		
		
		 $generatedPassword= str_random(6);
	    $password=  bcrypt( $generatedPassword);		
		$email=User::select('email','name')->where('id',$id)->get()->toArray();
		if(isset($email[0]['email']) && $email[0]['email']!=Request::get('email'))
		{
			$users['password'] = $password;
			$users['verification_key'] = md5(Request::get('email'));
			
	  						      	
				$to_user_id = $id;
				$to_email_id= Request::get('email');
				$user_name = !empty($email[0]['name'])?$email[0]['name']:"";
				$subject = 'Created User';
				$view_data = array('email'=>$to_email_id,'password'=>$generatedPassword,'user_name'=>$user_name );
				$view = 'emails.addUser';
				$data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>  $to_user_id,'flag'=>'user','send_flag'=>1);
				SendMail::sendmail($data); 

			
		}
		
		//$users->where('id', '=', $id)->update();
		User::where('id', '=', $id)->update($users);
		
		$logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->where('is_album_cover',1)->get()->toArray();
		
		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				    User::where('id', $id)->update(['logo' => $l['url']]);
			}
			
		}
		
		return redirect()->route('listusers')->with('status',trans('message.users.userprofileupdate'));
			//return redirect()->route('admin/photos')->with('status', trans('message.users.userprofileupdate'));
	}
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
            // if (empty($userDetails->dob))
                // $userDetails->dob = Carbon::now()->toDateString();
			
		  
	
            return view('admin.userprofile.editprofile')->with('userDetails',$userDetails->toArray())
                            ->with('states', ['' => 'Select State'] + $states)
                            ->with('cities', ['' => 'Select City'] + $cities)
                            ->with('enum',['' => 'Select Role'] + $enum)
                            ->with('edit_user_id', Auth::user()->id);
        }
    }
	
	 public function updateUser(Request $request)
    {
		
	     $id = Request::get('id');
		 $status = Request::get('status');//if 1->reject 2->approve
		
					 
		 if($status==1)
		 {
			
			  User::where('id', '=',  $id)->update(['isactive'=>1]);			          
			return Response()->json( array('success' => 'Inactive.') );
		 }else
		 {
			  
	       User::where('id', '=',  $id)->update(['isactive'=>0]);			          
			 return Response()->json( array('success' => 'Active.') );
		 }
					
		
    }


}
?>