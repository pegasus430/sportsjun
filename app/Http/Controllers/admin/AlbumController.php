<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use App\Model\Photo;
use App\Model\Album;
use App\Model\Country;
use App\Model\State;
use App\Model\UserStatistic;
use App\Model\City;
use App\Model\Team;
use App\Model\UserProvider;
use Illuminate\Http\Request;
use Session;
use Hash;
use Auth;
use Socialite;
use Carbon\Carbon;
use Response;
use DB;
use App\Helpers\Helper;

class AlbumController extends Controller {

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
		Helper::setMenuToSelect(3,4);
		return view('album.createalbum');
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
		$name=$request->title;
		$description=$request->description;
		$action=$request->action;
		$action_id=$request->action_id;
		$rules = array( 'title' => 'required');
        $validator = Validator::make($request->all(), $rules);
		Helper::setMenuToSelect(3,4);
		if ($validator->fails())
		{
		   return Response::json(array(
			'status' => 'fail',
			'msg' => $validator->getMessageBag()->toArray()

			  ));
		}else
		{
			$imageable_type_name = config('constants.PHOTO.GALLERY_USER');
			if($action=='team')
			{
				$imageable_type_name = config('constants.PHOTO.GALLERY_TEAM');
			}else if($action=='tournaments')
			{
				$imageable_type_name = config('constants.PHOTO.GALLERY_TOURNAMENTS');
			}else if($action=='facility')
			{
				$imageable_type_name = config('constants.PHOTO.GALLERY_FACILITY');
			}else if($action=='organization')
			{
				$imageable_type_name = config('constants.PHOTO.GALLERY_ORGANIZATION');
			}
			$album = new Album();
			 $album->title =  $name;
			 $album->description =  $description;
			 $album->imageable_type =  $imageable_type_name;
			 $album->imageable_id =  $action_id;
			 $album->user_id =  Auth::user()->id;
			//model call to save the data
			$album->save();
			$response = array(
            'status' => 'success',
            'msg' => trans('message.album.albumcreate'),
             );
		  return \Response::json($response);
		}
       
    }
	public function update(Requests\UpdateUserProfileRequest $request, $id) {
		
	}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($action='',$id='',$action_id='') {

		if($id=='' || $id==0)
			$user_id = Auth::user()->id;
		else
			$user_id = $id;
		if($action_id=='')
		{
			$action_id = $user_id;
		}
		$imageable_type_album = config('constants.PHOTO.GALLERY_USER');
			if($action=='team')
			{
				$imageable_type_album = config('constants.PHOTO.GALLERY_TEAM');
			}else if($action=='tournaments')
			{
				$imageable_type_album = config('constants.PHOTO.GALLERY_TOURNAMENTS');
			}else if($action=='facility')
			{
				$imageable_type_album = config('constants.PHOTO.GALLERY_FACILITY');
			}else if($action=='organization')
			{
				$imageable_type_album = config('constants.PHOTO.GALLERY_ORGANIZATION');
			}
		$album_array = Album::select('id','title','user_id')->where('user_id',$user_id)->where('imageable_type',$imageable_type_album)->where('imageable_id',$action_id)->get()->toArray();//albums related to users
		$imageable_type = config('constants.PHOTO.USER_PHOTO');

		$user_profile_album = Photo::select()->where('user_id',$user_id)->where('imageable_type',$imageable_type)->get()->toArray();
		// to show active profice pic in gallery
		$user_profile_albums = Photo::select()->where('user_id',$user_id)->where('imageable_type',$imageable_type)->where('is_album_cover',1)->get()->toArray();
		//get individual album photo count
		$album_photo_count = array();
		$photo_arrayy=array();
		foreach($album_array as $album_id)
		{
			
			$imageable_type_name = array(config('constants.PHOTO.USER_PHOTO'),config('constants.PHOTO.GALLERY_USER'));
			if($action=='team')
			{
				$imageable_type_name = array(config('constants.PHOTO.GALLERY_TEAM'));
			}else if($action=='tournaments')
			{
				$imageable_type_name = array(config('constants.PHOTO.GALLERY_TOURNAMENTS'));
			}else if($action=='facility')
			{
				$imageable_type_name = array(config('constants.PHOTO.GALLERY_FACILITY'));
			}else if($action=='organization')
			{
				$imageable_type_name = array(config('constants.PHOTO.GALLERY_ORGANIZATION'));
			}
			$photo_array = Photo::select()->where('album_id',$album_id['id'])->whereIn('imageable_type', $imageable_type_name)->get()->toArray();
			$album_photo_count[$album_id['id']] = count($photo_array);
			// to display one pic
	    	$photo_arrayy[$album_id['id']] = Photo::select()->where('album_id',$album_id['id'])->whereIn('imageable_type', $imageable_type_name)->get()->toArray();
			
		}

		if($action=='team')
		{
			Helper::setMenuToSelect(2,4);
			Helper::leftMenuVariables($action_id);
		}else if($action=='tournaments')
		{
			Helper::setMenuToSelect(6,2);
		}else if($action=='facility')
		{
			Helper::setMenuToSelect(8,2);
		}else if($action=='organization')
		{
			Helper::setMenuToSelect(9,2);
		}else{
			Helper::setMenuToSelect(3,4);
		}
		$lef_menu_condition = 'display_gallery';
		return view('admin.album.viewalbum',array('album_array'=>$album_array,'album_photo_count'=>$album_photo_count,'user_profile_album'=>$user_profile_album,'action'=>$action,'action_id'=>$action_id,'action'=>$action,'lef_menu_condition'=>$lef_menu_condition,'photo_arrayy' =>$photo_arrayy,'user_profile_albums'=>$user_profile_albums ));
    }
	public function createphoto($album_id,$user_id,$is_user_profile='',$action='',$action_id='')
	{
		$create_photo = 0;
		if($user_id==Auth::user()->id && ($is_user_profile=='' || $is_user_profile==0))
		{
			$create_photo = 1;
		}
		$imageable_type = array();
		$imageable_type = array(config('constants.PHOTO.USER_PHOTO'),config('constants.PHOTO.GALLERY_USER'));
		if($action=='team')//if team gallery
		{
			$imageable_type = array(config('constants.PHOTO.GALLERY_TEAM'));
		}else if($action=='tournaments')//if tournaments gallery
		{
			$imageable_type = array(config('constants.PHOTO.GALLERY_TOURNAMENTS'));
		}else if($action=='facility')
		{
			$imageable_type = array(config('constants.PHOTO.GALLERY_FACILITY'));
		}else if($action=='organization')
		{
			$imageable_type = array(config('constants.PHOTO.GALLERY_ORGANIZATION'));
		}
		if($is_user_profile!='' && $is_user_profile!=0)
		{
			$photo_array = Photo::select()->where('user_id',$user_id)->where('imageable_type', config('constants.PHOTO.USER_PHOTO'))->get()->toArray();
		}else{
			$photo_array = Photo::select()->where('album_id',$album_id)->whereIn('imageable_type', $imageable_type)->get()->toArray();
		}
		if($action=='team')
		{
			Helper::setMenuToSelect(2,4);
			Helper::leftMenuVariables($action_id);
		}else if($action=='tournaments')
		{
			Helper::setMenuToSelect(6,2);
		}else if($action=='facility')
		{
			Helper::setMenuToSelect(8,2);
		}else if($action=='organization')
		{
			Helper::setMenuToSelect(9,2);
		}else
		{
			Helper::setMenuToSelect(3,4);
		}
		$lef_menu_condition = 'display_gallery';
		$userid = Auth::user()->id;
		return view('album.viewalbumphoto',array('photo_array'=>$photo_array,'create_photo'=>$create_photo,'album_id'=>$album_id,'action'=>$action,'action_id'=>$action_id,'lef_menu_condition'=>$lef_menu_condition,'userid'=> $userid));
	}

	public function albumphotocreate(Request $request)
	{
		if(!empty($request['filelist_gallery']))
		{
			$request['user_id'] = Auth::user()->id;
			//Upload Photos
			$albumID = $request['album_id'];//Default album if no album is not selected.
			$action = $request['action'];//Default album if no album is not selected.
			$id = $request['id'];
			$coverPic = 0;
			if(isset($input['album_id']) && $input['album_id'])
				$albumID = $input['album_id'];
			if(isset($input['cover_pic']) && $input['cover_pic'])
				$coverPic = $input['cover_pic']; 
			$image_type = config('constants.PHOTO.GALLERY_USER');
			if($action=='team')
			{
				$image_type = config('constants.PHOTO.GALLERY_TEAM');
			}else if($action=='tournaments')
			{
				$image_type = config('constants.PHOTO.GALLERY_TOURNAMENTS');
			}else if($action=='facility')
			{
				$image_type = config('constants.PHOTO.GALLERY_FACILITY');
			}else if($action=='organization')
			{
				$image_type = config('constants.PHOTO.GALLERY_ORGANIZATION');
			}
			Helper::uploadPhotos($request['filelist_gallery'],config('constants.PHOTO_PATH.GALLERY'),$id,$albumID,$coverPic,$image_type,$user_id = Auth::user()->id,$action,$id,$image_type);
		}
		Helper::setMenuToSelect(3,4);
		return redirect()->back()->with('status', trans('message.album.albumphotocreate'));
	}
	public function Likecount(Request $request)
	{
		 $idval = $request['id'];
		  $id = ','. Auth::user()->id;
		 $list= Photo::select()->where('id', $idval)->get()->toArray();
		 // echo "<pre>";print_r($list);exit;
		foreach( $list as $l)
		{
			if(!empty($l['like_count']))
			{
			$count =$l['like_count']+1;
			}
			else{
				$count =1;
			}
			if(!empty($l['likes']))
			{
			$likes =$l['likes']. $id ;
			}
			else{
				$likes =  $id ;
			}
			
		}
		// echo $likes ;exit;
		// echo "<pre>";print_r($list);exit;
		
		// echo  $id;exit;
		   Photo::where(['id' =>  $idval ])->update(array('likes' => $likes,'like_count'=>$count));
		   	return Response::json(array(
        
        'msg' =>  'success',
	    'like_count'=>$count,
          ));
	}
	public function Dislikecount(Request $request)
	{
		
		 $idval = $request['id'];
		
		 $id = ','. Auth::user()->id;		
		
		 $list= Photo::select()->where('id', $idval)->get()->toArray();
		foreach( $list as $l)
		{
			if(!empty($l['like_count']))
			{
			$count =$l['like_count']-1;
			}
			else{
				$count=-1;
			}
			if(!empty($l['likes']))
			{
				 $l=explode(',',$l['likes']);
				
		
		      if (($key = array_search(Auth::user()->id, $l)) !== false)
				{
				unset($l[$key]);
                }
				$likes=implode(',', $l);
			}
			else{
				$likes = 'null' ;
			}
			
		}	

		   Photo::where(['id' =>  $idval ])->update(array('likes' => $likes,'like_count'=>$count));
		  	return Response::json(array(
        
        'msg' =>  'success',
		    'like_count'=>$count,


          ));
	}


}
?>