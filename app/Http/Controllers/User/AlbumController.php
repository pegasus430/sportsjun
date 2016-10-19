<?php

namespace App\Http\Controllers\User;
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
use App\Model\Tournaments;
use App\Model\UserProvider;
use App\Model\Organization;
use Illuminate\Http\Request;
use Session;
use Hash;
use Auth;
use Socialite;
use Carbon\Carbon;
use Response;
use DB;
use App\Helpers\Helper;
use View;
use PDO;
use App\Model\MatchSchedule;

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
		$page=$request->page;
		$count=0;
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
		else if($action=='match')
		{

			$imageable_type_name = config('constants.PHOTO.GALLERY_MATCH');
		}
		if($request['duplicate_album']=='yes')
		{
			// $rules = array( 'title' => 'required|max:20|unique:albums,title,$name,imageable_id,$action_id|'.config('constants.VALIDATION.CHARACTERSANDSPACE'));

			$rules = array( 'title' => 'required|max:20|'.config('constants.VALIDATION.CHARACTERSANDSPACE'));
			$count=Album::select()->where('title',$name)->where('imageable_id',$action_id )->where('imageable_type',$imageable_type_name )->count();

		}
		else
		{
			$rules = array( 'title' => 'required|max:20|'.config('constants.VALIDATION.CHARACTERSANDSPACE'));
		}
		$validator = Validator::make($request->all(), $rules);
		Helper::setMenuToSelect(3,4);

		if ($validator->fails() || $count>0)
		{

			if($count>0)
			{

				return Response::json(array(
					'status' => 'fail',
					'msg' => "The title has already been taken."

				));
			}

			else
			{
				return Response::json(array(
					'status' => 'fail',
					'msg' => $validator->getMessageBag()->toArray()

				));
			}


		}
		else
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
			else if($action=='match')
			{

				$imageable_type_name = config('constants.PHOTO.GALLERY_MATCH');
			}
			$album = new Album();
			$album->title =  $name;
			$album->description =  $description;
			$album->imageable_type =  $imageable_type_name;
			$album->imageable_id =  $action_id;
			$album->user_id =  Auth::user()->id;
			//model call to save the data
			$album->save();
			$lastinsertedid=$album->id;


			return Response::json(array('status'=>'success','msg'=> trans('message.album.albumcreate'),'action'=>$action,'action_id'=>$action_id,'id'=> Auth::user()->id,'lastinsertedid'=>$lastinsertedid));


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

	//show albums and check delete edit permisssions
	public function show($action='',$id='',$action_id='',$type='') {

		if($id=='' || $id==0)
			$user_id = isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
		else
			$user_id = $id;

		//for userprofile actoin_id =userid
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
		else if($action=='match')
		{
			$imageable_type_album = config('constants.PHOTO.GALLERY_MATCH');
		}
		// $album_array = Album::select('id','title','user_id')->where('user_id',$user_id)->where('imageable_type',$imageable_type_album)->where('imageable_id',$action_id)->get()->toArray();//albums related to users
		$albumcreate="";
		$tournamentresult="";
		$result="";
		$flag="";
		$matchalbumcreate="";
		$tournament_type='';
		$left_menu_data = array();
		$id="";
		$loginUserId= "";
		$orgalbumcreate="";
		$orgInfoObj = null;
		if($action=='team')
		{

			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
			//edit delete permisssions
			DB::setFetchMode(PDO::FETCH_ASSOC);
			$album_array = DB::select("SELECT a.id,a.user_id,a.title,
									(CASE tp.role
									WHEN 'owner' THEN 'yes'
									WHEN 'manager' THEN 'yes'
									WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
									FROM `albums` a
									LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_team'   AND tp.user_id =$loginUserId AND `status` = 'accepted'
									WHERE a.imageable_id = $action_id  AND a.deleted_at is NULL");
			//to create album permissions
			$albumcreate=DB::select("SELECT * FROM `team_players` tp WHERE tp.user_id = $loginUserId AND tp.team_id = $action_id AND `status` = 'accepted'  AND tp.deleted_at is NULL");
			// echo count( $albumcreate);exit;
			DB::setFetchMode(PDO::FETCH_CLASS);

		}
		else if($action=='tournaments')
		{
			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest

			$result=Helper::isValidUserForTournamentGallery($action_id,	$loginUserId);
			$album_array = Album::select('id','title','user_id')->where('imageable_type',$imageable_type_album)->where('imageable_id',$action_id)->get()->toArray();//albums related to users

			$tournamentDetails = Tournaments::where('id',$action_id)->get(['type','tournament_parent_id','manager_id','name','match_type','sports_id']);
			$tournament_type = $tournamentDetails[0]['type'];
			//left menu
			$parent_tournamet_id = $tournamentDetails[0]['tournament_parent_id'];
			$tournamentManagerId = $tournamentDetails[0]['manager_id'];
			$tournamentName  = $tournamentDetails[0]['name'];
			$left_menu_data = Helper::getLeftMenuData($parent_tournamet_id,$tournamentManagerId,$tournamentDetails );

		}
		else if($action=='organization')
		{

			$id= $action_id;
			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
			//to create album permissions
			$orgalbumcreate = DB::select("SELECT * FROM `organization` tp WHERE `user_id` = $loginUserId AND `id`=$action_id AND tp.deleted_at is NULL");
			$orgInfoObj= Organization::find($action_id);
			$album_array = Album::select('id','title','user_id')->where('imageable_type',$imageable_type_album)->where('imageable_id',$action_id)->get()->toArray();//albums related to users



		}
		else if($action=='match')
		{
			DB::setFetchMode(PDO::FETCH_ASSOC);
			$teamids = DB::select("SELECT * FROM `match_schedules` tp WHERE id = $action_id AND tp.deleted_at is NULL") ;

			$tournament=MatchSchedule::select('tournament_id')->where('id',$action_id)->get()->toArray();
			$flag="no";
			if(isset( $tournament[0]['tournament_id']) && $tournament[0]['tournament_id']!='')
			{
				$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
				$tournamentresult=Helper::isValidUserForTournamentGallery($tournament[0]['tournament_id'],	$loginUserId);
				if($tournamentresult==1)
				{
					$flag="yes";
				}
			}
			$ids = '';
			$match_schedule_type = '';
			if(count($teamids) > 0)	{

				$match_schedule_type = $teamids[0]['schedule_type'];

				$teamid = array();
				foreach($teamids as $t)
				{
					$teamid[]= $t['a_id'];
					if(!empty($t['b_id']))
						$teamid[]= $t['b_id'];
				}
				$ids=implode(',',$teamid);
			}

			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest

			if($match_schedule_type == 'player'){

				//to create album permissions
				$matchalbumcreate = DB::select("SELECT * FROM `match_schedules` tp WHERE id = $action_id AND (a_id = $loginuserId OR b_id = $loginuserId) AND tp.deleted_at is NULL");

				DB::setFetchMode(PDO::FETCH_ASSOC);
				$album_array = DB::select("SELECT a.id,a.user_id,a.title,
									(CASE a.user_id
									WHEN $loginuserId THEN 'yes'
									ELSE 'no' END) AS 'accessflag'
									FROM `albums` a
									LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'player'									
									WHERE a.imageable_id = $action_id  AND a.deleted_at is NULL GROUP BY a.id;
									");
			}
			else{

				//to create album permissions
				$matchalbumcreate = DB::select("SELECT * FROM `team_players` tp WHERE tp.user_id =$loginuserId AND tp.team_id in ($ids) AND `status` = 'accepted'  AND tp.deleted_at is NULL");
				DB::setFetchMode(PDO::FETCH_ASSOC);
				$album_array = DB::select("SELECT a.id,a.user_id,a.title,
									(CASE tp.role
									WHEN 'owner' THEN 'yes'
									WHEN 'manager' THEN 'yes'
									WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
									FROM `albums` a
									LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'team' 
									LEFT JOIN `team_players` tp ON (ms.a_id = tp.team_id OR ms.b_id = tp.team_id) AND tp.user_id = $loginuserId
									WHERE a.imageable_id = $action_id  AND a.deleted_at is NULL GROUP BY a.id;
									");
				//echo $flag.count($matchalbumcreate)	;exit;
			}
			DB::setFetchMode(PDO::FETCH_CLASS);

		}
		else if($action=='user')
		{
			$id=isset(Auth::user()->id)?Auth::user()->id:0;
			$album_array = Album::select('id','title','user_id')->where('user_id',$user_id)->where('imageable_type','gallery_user')->where('imageable_id',$user_id)->get()->toArray();

		}


		$imageable_type = config('constants.PHOTO.USER_PHOTO');

		$user_profile_album = Photo::select()->where('user_id',$user_id)->where('imageable_type',$imageable_type)->get()->toArray();
		// to show active profice pic in gallery
		$user_profile_albums = Photo::select()->where('user_id',$user_id)->where('imageable_type',$imageable_type)->where('is_album_cover',1)->get()->toArray();
		$tournament_profile_albums = Photo::select()->where('imageable_id',$action_id)->where('imageable_type','form_gallery_tournaments')->get()->toArray();
		$organization_profile_albums = Photo::select()->where('imageable_id',$action_id)->where('imageable_type','form_gallery_organization')->get()->toArray();

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
			else if($action=='match')
			{
				$imageable_type_name = array(config('constants.PHOTO.GALLERY_MATCH'));
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
			// Helper::setMenuToSelect(9,2);
		}else{
			Helper::setMenuToSelect(3,4);
		}
		$lef_menu_condition = 'display_gallery';

		// if (!empty($type))
		// {

		// $returnHTML =  View::make('album.album')->with(array('album_array'=>$album_array,'album_photo_count'=>$album_photo_count,'user_profile_album'=>$user_profile_album,'action'=>$action,'action_id'=>$action_id,'action'=>$action,'lef_menu_condition'=>$lef_menu_condition,'photo_arrayy' =>$photo_arrayy,'user_profile_albums'=>$user_profile_albums,'userId' => $user_id, 'albumcreate'=>  $albumcreate,	'result'=>	$result,'id'=>$id, 'tournamentresult'=> $tournamentresult,'flag'=>$flag,  'matchalbumcreate'=>  $matchalbumcreate,'tournament_type'=>$tournament_type,'tournament_id'=>$action_id,'loginUserId'=>$loginUserId,'tournament_profile_albums'=>$tournament_profile_albums,'orgalbumcreate'=>$orgalbumcreate,'organization_profile_albums'=>$organization_profile_albums))->render();

		// return Response::json(array('html' =>$returnHTML,'msg'=>'success'));

		// }
		// else
		// {
		return view('album.viewalbum',array(
			'album_array'                 => $album_array,
			'album_photo_count'           => $album_photo_count,
			'user_profile_album'          => $user_profile_album,
			'action'                      => $action,
			'action_id'                   => $action_id,
			'lef_menu_condition'          => $lef_menu_condition,
			'photo_arrayy'                => $photo_arrayy,
			'user_profile_albums'         => $user_profile_albums,
			'userId'                      => $user_id,
			'albumcreate'                 => $albumcreate,
			'result'                      => $result,
			'id'                          => $id,
			'tournamentresult'            => $tournamentresult,
			'flag'                        => $flag,
			'matchalbumcreate'            => $matchalbumcreate,
			'tournament_type'             => $tournament_type,
			'left_menu_data'              => $left_menu_data,
			'tournament_id'               => $action_id,
			'tournament_profile_albums'   => $tournament_profile_albums,
			'loginUserId'                 => $loginUserId,
			'orgalbumcreate'              => $orgalbumcreate,
			'organization_profile_albums' => $organization_profile_albums,
			'orgInfoObj'                     => $orgInfoObj
		));

		// }

	}
	// in albumajax add only created album to a div and check delete create edit permisssons
	public function albumajax($action='',$id='',$action_id='',$type='',$lastinsertedid="")
	{

		$lastinsertedid=$lastinsertedid;
		if($id=='' || $id==0)
			$user_id = isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
		else
			$user_id = $id;
		//for userprofile actoin_id =userid
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
		else if($action=='match')
		{
			$imageable_type_album = config('constants.PHOTO.GALLERY_MATCH');
		}
		// $album_array = Album::select('id','title','user_id')->where('user_id',$user_id)->where('imageable_type',$imageable_type_album)->where('imageable_id',$action_id)->get()->toArray();//albums related to users
		$albumcreate="";
		$tournamentresult="";
		$result="";
		$flag="";
		$matchalbumcreate="";
		$tournament_type='';
		$id="";
		$loginUserId= "";
		$orgalbumcreate="";
		if($action=='team')
		{
			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
			DB::setFetchMode(PDO::FETCH_ASSOC);
			//to edit delete permissions
			$album_array = DB::select("SELECT a.id,a.user_id,a.title,
									(CASE tp.role
									WHEN 'owner' THEN 'yes'
									WHEN 'manager' THEN 'yes'
									WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
									FROM `albums` a
									LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_team'   AND tp.user_id =$loginUserId AND `status` = 'accepted'
									WHERE a.imageable_id = $action_id  AND  a.id=$lastinsertedid  AND a.deleted_at is NULL");
			//to create album permissions
			$albumcreate=DB::select("SELECT * FROM `team_players` tp WHERE tp.user_id = $loginUserId AND tp.team_id = $action_id AND `status` = 'accepted'  AND tp.deleted_at is NULL");
			DB::setFetchMode(PDO::FETCH_CLASS);

		}
		else if($action=='tournaments')
		{
			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest

			$result=Helper::isValidUserForTournamentGallery($action_id,	$loginUserId);
			$album_array = Album::select('id','title','user_id')->where('imageable_type',$imageable_type_album)->where('imageable_id',$action_id)->where('id',$lastinsertedid)->get()->toArray();//albums related to users

			$tournamentDetails = Tournaments::where('id',$action_id)->first(['type']);
			$tournament_type = $tournamentDetails['type'];

		}
		else if($action=='organization')
		{
			$id=$action_id;
			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
			$album_array = Album::select('id','title','user_id')->where('imageable_type',$imageable_type_album)->where('imageable_id',$action_id)->where('id',$lastinsertedid)->get()->toArray();//albums related to users

		}

		else if($action=='match')
		{

			DB::setFetchMode(PDO::FETCH_ASSOC);
			$teamids = DB::select("SELECT * FROM `match_schedules` tp WHERE id = $action_id  AND tp.deleted_at is NULL") ;

			$tournament=MatchSchedule::select('tournament_id')->where('id',$action_id)->get()->toArray();
			$flag="no";
			if(isset( $tournament[0]['tournament_id']) && $tournament[0]['tournament_id']!='')
			{
				$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
				$tournamentresult=Helper::isValidUserForTournamentGallery($tournament[0]['tournament_id'],	$loginUserId);
				if($tournamentresult==1)
				{
					$flag="yes";
				}
			}
			$ids = '';
			$match_schedule_type = '';
			if(count($teamids) > 0)	{

				$match_schedule_type = $teamids[0]['schedule_type'];

				$teamid = array();
				foreach($teamids as $t)
				{
					$teamid[]= $t['a_id'];
					if(!empty($t['b_id']))
						$teamid[]= $t['b_id'];
				}
				$ids=implode(',',$teamid);
			}

			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest

			if($match_schedule_type == 'player'){

				$matchalbumcreate = DB::select("SELECT * FROM `match_schedules` tp WHERE id = $action_id AND (a_id = $loginuserId OR b_id = $loginuserId) AND tp.deleted_at is NULL");

				DB::setFetchMode(PDO::FETCH_ASSOC);
				$album_array = DB::select("SELECT a.id,a.user_id,a.title,
									(CASE a.user_id
									WHEN $loginuserId THEN 'yes'
									ELSE 'no' END) AS 'accessflag'
									FROM `albums` a
									LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'player'									
									WHERE a.imageable_id = $action_id  AND a.id=$lastinsertedid  AND a.deleted_at is NULL GROUP BY a.id;
									");
			}
			else{

				$matchalbumcreate = DB::select("SELECT * FROM `team_players` tp WHERE tp.user_id =$loginuserId AND tp.team_id in ($ids) AND `status` = 'accepted'");
				DB::setFetchMode(PDO::FETCH_ASSOC);
				$album_array = DB::select("SELECT a.id,a.user_id,a.title,
									(CASE tp.role
									WHEN 'owner' THEN 'yes'
									WHEN 'manager' THEN 'yes'
									WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
									FROM `albums` a
									LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'team' 
									LEFT JOIN `team_players` tp ON (ms.a_id = tp.team_id OR ms.b_id = tp.team_id) AND tp.user_id = $loginuserId
									WHERE a.imageable_id = $action_id  AND a.id=$lastinsertedid  AND a.deleted_at is NULL GROUP BY a.id;
									");
				//echo $flag.count($matchalbumcreate)	;exit;
			}
			DB::setFetchMode(PDO::FETCH_CLASS);

		}
		else if($action=='user')
		{
			$id=Auth::user()->id;
			$album_array = Album::select('id','title','user_id')->where('user_id',$user_id)->where('imageable_type','gallery_user')->where('imageable_id',$user_id)->where('id',$lastinsertedid)->get()->toArray();

		}


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
			else if($action=='match')
			{
				$imageable_type_name = array(config('constants.PHOTO.GALLERY_MATCH'));
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
			// Helper::setMenuToSelect(9,2);
		}else{
			Helper::setMenuToSelect(3,4);
		}
		$lef_menu_condition = 'display_gallery';


		if ($type!='')
		{

			$returnHTML =  View::make('album.album')->with(array('album_array'=>$album_array,'album_photo_count'=>$album_photo_count,'user_profile_album'=>$user_profile_album,'action'=>$action,'action_id'=>$action_id,'action'=>$action,'lef_menu_condition'=>$lef_menu_condition,'photo_arrayy' =>$photo_arrayy,'user_profile_albums'=>$user_profile_albums,'userId' => $user_id, 'albumcreate'=>  $albumcreate,	'result'=>	$result,'id'=>$id, 'tournamentresult'=> $tournamentresult,'flag'=>$flag,  'matchalbumcreate'=>  $matchalbumcreate,'tournament_type'=>$tournament_type,'tournament_id'=>$action_id ,'loginUserId'=>$loginUserId))->render();

			return Response::json(array('html' =>$returnHTML,'msg'=>'success'));

		}
		// else
		// {

		// return view('album.viewalbum',array('album_array'=>$album_array,'album_photo_count'=>$album_photo_count,'user_profile_album'=>$user_profile_album,'action'=>$action,'action_id'=>$action_id,'action'=>$action,'lef_menu_condition'=>$lef_menu_condition,'photo_arrayy' =>$photo_arrayy,'user_profile_albums'=>$user_profile_albums,'userId' => $user_id,  'albumcreate'=>  $albumcreate,	'result'=>	$result,'id'=>$id,'tournamentresult'=> $tournamentresult,'flag'=>$flag, 'matchalbumcreate'=>  $matchalbumcreate,'tournament_type'=>$tournament_type,'tournament_id'=>$action_id,'loginUserId'=>$loginUserId ));

		// }



	}
	//in album upload images and check delete permissions

	public function createphotopublic($album_id,$user_id,$is_user_profile='',$action='',$action_id='',$type=''){
		return $this->createphoto($album_id,1,$is_user_profile,$action,$action_id,$type,true);
	}
	public function createphoto($album_id,$user_id,$is_user_profile='',$action='',$action_id='',$type='',$fromPublic=false)
	{

		$loginuserid="";
		$create_photo = 0;
		$tournament_type="";
		$left_menu_data=array();
		if((Auth::user() &&$user_id==Auth::user()->id )&& ($is_user_profile=='' || $is_user_profile==0))
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
		else if($action=='match')
		{
			$imageable_type = array(config('constants.PHOTO.GALLERY_MATCH'));
		}
		if($is_user_profile!='' && $is_user_profile!=0)
		{
			$photo_array = Photo::select()->where('user_id',$user_id)->where('imageable_type', config('constants.PHOTO.USER_PHOTO'))->get()->toArray();
		}



		$uploadimage="";
		$upload="";
		$result='';
		$loginid=isset(Auth::user()->id)?Auth::user()->id:0;	//return 0 if not logged in
		$flag="";
		$orgphotocreate=array();
		// $photo_array1 =array();
		if($action=='team')
		{
			$loginuserid=isset(Auth::user()->id)?Auth::user()->id:0;
			DB::setFetchMode(PDO::FETCH_ASSOC);

			//upload images permission
			$photo_array = 	 DB::select("SELECT a.*,
											(CASE tp.role 
											WHEN 'owner' THEN 'yes' 
											WHEN 'manager' THEN 'yes' 
											WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
											FROM `photos` a
											LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_team' AND tp.user_id =  $loginuserid
											WHERE a.imageable_id = $action_id AND a.album_id = $album_id  AND a.deleted_at is NULL");

			$uploadimage=DB::select("SELECT * FROM `albums` a
									LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_team' AND tp.user_id =   $loginuserid AND `status` = 'accepted' 
									WHERE a.imageable_id = $action_id AND a.id = $album_id AND a.user_id=  $loginuserid AND a.deleted_at is NULL");


			DB::setFetchMode(PDO::FETCH_CLASS);


		}
		else if($action=='tournaments')
		{
			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;
			$result=Helper::isValidUserForTournamentGallery($action_id,	$loginUserId);
			$photo_array = Photo::select()->where('album_id',$album_id)->whereIn('imageable_type', $imageable_type)->get()->toArray();

			$tournamentDetails = Tournaments::where('id',$action_id)->get(['type','tournament_parent_id','manager_id','name','match_type','sports_id']);
			$tournament_type = $tournamentDetails[0]['type'];
			//left menu
			$parent_tournamet_id = $tournamentDetails[0]['tournament_parent_id'];
			$tournamentManagerId = $tournamentDetails[0]['manager_id'];
			$tournamentName  = $tournamentDetails[0]['name'];
			$left_menu_data = Helper::getLeftMenuData($parent_tournamet_id,$tournamentManagerId,$tournamentDetails);


		}
		else if($action=='organization')
		{

			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
			$orgphotocreate = DB::select("SELECT * FROM `organization` tp WHERE `user_id` = $loginUserId AND `id`=$action_id AND tp.deleted_at is NULL");

			$photo_array = Photo::select()->where('album_id',$album_id)->whereIn('imageable_type', $imageable_type)->get()->toArray();


		}
		else if($action=='match')
		{

			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
			$tournament=MatchSchedule::select()->where('id',$action_id)->get()->toArray();
			$flag="no";
			if(isset( $tournament[0]['tournament_id']) && $tournament[0]['tournament_id']!='')
			{

				$tournamentresult=Helper::isValidUserForTournamentGallery($tournament[0]['tournament_id'],	$loginUserId);
				if($tournamentresult==1)
				{
					$flag="yes";
				}
			}

			$loginuser=Auth::user()->id;
			DB::setFetchMode(PDO::FETCH_ASSOC);
			// $photo_array = Photo::select()->where('album_id',$album_id)->whereIn('imageable_type', $imageable_type)->get()->toArray();
			$match_schedule_type = $tournament[0]['schedule_type'];
			if($match_schedule_type == 'player'){
				//upload images permission
				$upload = DB::select("SELECT * FROM `albums`  WHERE user_id = $loginUserId AND imageable_id = $action_id  AND deleted_at is NULL");

				$photo_array = DB::select("SELECT a.*,
									(CASE a.user_id
									WHEN $loginUserId THEN 'yes'
									ELSE 'no' END) AS 'accessflag'
									FROM `photos` a
									LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'player'									
									WHERE a.imageable_id = $action_id  AND a.album_id=$album_id AND a.deleted_at is NULL  GROUP BY a.id;
									");

			}
			else{

				$photo_array = 	 DB::select(
					"SELECT a.*,
								(CASE tp.role
								WHEN 'owner' THEN 'yes'
								WHEN 'manager' THEN 'yes'
								WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
								FROM `photos` a
								LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'team' 
								LEFT JOIN `team_players` tp ON (ms.a_id = tp.team_id OR ms.b_id = tp.team_id) AND tp.user_id =$loginuser
								WHERE a.imageable_id = $action_id  AND a.album_id=$album_id AND a.deleted_at is NULL GROUP BY a.id");

				$upload=DB::select("SELECT * FROM `albums` a
									LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_match' AND tp.user_id =   $loginuser AND `status` = 'accepted' 
									WHERE a.imageable_id = $action_id AND a.id = $album_id AND a.user_id= $loginuser AND a.deleted_at is NULL");
			}

			DB::setFetchMode(PDO::FETCH_CLASS);


		}
		else if($action=='user')
		{

			$photo_array = Photo::select()->where('album_id',$album_id)->where('imageable_type', 'gallery_user')->get()->toArray();

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
			// Helper::setMenuToSelect(9,2);
		}else
		{
			Helper::setMenuToSelect(3,4);
		}

		$lef_menu_condition = 'display_gallery';
		$userid = isset(Auth::user()->id)?Auth::user()->id:0;


		if($imageable_type[0]!='user_photo')
		{

			$album_array = Album::select('title', 'id')->where('user_id',$userid )->where('imageable_type',$imageable_type[0])->where('imageable_id',$action_id)->get()->toArray();


		}
		else
		{
			$album_array = Album::select('title', 'id')->where('user_id',$userid )->where('imageable_type','gallery_user')->where('imageable_id',$action_id)->get()->toArray();

		}

		if(!isset($photo_array)){
			$photo_array=[];
		}

		return 	view('album.viewalbumphoto',array('photo_array'=>$photo_array,'create_photo'=>$create_photo,'album_id'=>$album_id,'action'=>$action,'action_id'=>$action_id,'lef_menu_condition'=>$lef_menu_condition,'userId'=> $userid, 'album_array' =>$album_array,'uploadimage'=>$uploadimage,'result'=>	$result,'upload'=>$upload,'loginid'=>$loginid,'user_id'=>$user_id,'flag'=>$flag,'tournament_type'=>$tournament_type,'id'=>$action_id,'left_menu_data'=>$left_menu_data,'tournament_id'=>$action_id,'orgphotocreate'=>$orgphotocreate ));



	}
	//in galleryajax add photos to a div
	public function galleryajax(Request $request,$album_id,$user_id,$is_user_profile='',$action='',$action_id='',$type='')
	{

		$insertedids=$request->insertedphotoids;
		$loginuserid="";
		$create_photo = 0;
		$tournament_type="";
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
		else if($action=='match')
		{
			$imageable_type = array(config('constants.PHOTO.GALLERY_MATCH'));
		}
		if($is_user_profile!='' && $is_user_profile!=0)
		{
			$photo_array = Photo::select()->where('user_id',$user_id)->where('imageable_type', config('constants.PHOTO.USER_PHOTO'))->get()->toArray();
		}



		$uploadimage="";
		$upload="";
		$result='';
		$loginid=Auth::user()->id;
		$flag="";
		$photo_array=array();
		$orgphotocreate=array();
		foreach($insertedids as $insertedid)
		{
			if($action=='team')
			{
				$loginuserid=isset(Auth::user()->id)?Auth::user()->id:0;
				DB::setFetchMode(PDO::FETCH_ASSOC);
				// $photo_array = Photo::select()->where('album_id',$album_id)->whereIn('imageable_type', $imageable_type)->get()->toArray();
				$photo_array[] = 	 DB::select("SELECT a.*,
											(CASE tp.role 
											WHEN 'owner' THEN 'yes' 
											WHEN 'manager' THEN 'yes' 
											WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
											FROM `photos` a
											LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_team' AND tp.user_id =  $loginuserid
											WHERE a.imageable_id = $action_id AND a.id=$insertedid AND a.album_id = $album_id  AND a.deleted_at is NULL");
				$uploadimage=DB::select("SELECT * FROM `albums` a
									LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_team' AND tp.user_id =   $loginuserid AND `status` = 'accepted' 
									WHERE a.imageable_id = $action_id AND a.id = $album_id AND a.user_id=  $loginuserid AND a.deleted_at is NULL");


				DB::setFetchMode(PDO::FETCH_CLASS);

			}
			else if($action=='tournaments')
			{
				$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
				$result=Helper::isValidUserForTournamentGallery($action_id,	$loginUserId);
				$photo_array[] = Photo::select()->where('album_id',$album_id)->where('id',$insertedid )->whereIn('imageable_type', $imageable_type)->get()->toArray();

				$tournamentDetails = Tournaments::where('id',$action_id)->first(['type']);
				$tournament_type = $tournamentDetails['type'];

			}
			else if($action=='organization')
			{

				$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
				$orgphotocreate = DB::select("SELECT * FROM `organization` tp WHERE `user_id` = $loginUserId AND `id`=$action_id AND tp.deleted_at is NULL");
				$photo_array[] = Photo::select()->where('album_id',$album_id)->where('id',$insertedid )->whereIn('imageable_type', $imageable_type)->get()->toArray();

			}

			else if($action=='match')
			{

				$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
				$tournament=MatchSchedule::select()->where('id',$action_id)->get()->toArray();
				$flag="no";
				if(isset( $tournament[0]['tournament_id']) && $tournament[0]['tournament_id']!='')
				{

					$tournamentresult=Helper::isValidUserForTournamentGallery($tournament[0]['tournament_id'],	$loginUserId);
					if($tournamentresult==1)
					{
						$flag="yes";
					}
				}

				$loginuser=Auth::user()->id;
				DB::setFetchMode(PDO::FETCH_ASSOC);
				// $photo_array = Photo::select()->where('album_id',$album_id)->whereIn('imageable_type', $imageable_type)->get()->toArray();
				$match_schedule_type = $tournament[0]['schedule_type'];
				if($match_schedule_type == 'player'){

					$upload = DB::select("SELECT * FROM `albums`  WHERE user_id = $loginUserId AND imageable_id = $action_id  AND deleted_at is NULL");

					$photo_array[] = DB::select("SELECT a.*,
									(CASE a.user_id
									WHEN $loginuser THEN 'yes'
									ELSE 'no' END) AS 'accessflag'
									FROM `photos` a
									LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'player'									
									WHERE a.imageable_id = $action_id AND a.id=$insertedid AND a.album_id=$album_id  AND a.deleted_at is NULL GROUP BY a.id;
									");
				}
				else{

					$photo_array[] = 	 DB::select("SELECT a.*,
										(CASE tp.role
										WHEN 'owner' THEN 'yes'
										WHEN 'manager' THEN 'yes'
										WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
										FROM `photos` a
										LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'team' 
										LEFT JOIN `team_players` tp ON (ms.a_id = tp.team_id OR ms.b_id = tp.team_id) AND tp.user_id =$loginuser
										WHERE a.imageable_id = $action_id  AND a.id=$insertedid AND a.album_id=$album_id  AND a.deleted_at is NULL GROUP BY a.id");
					$upload=DB::select("SELECT * FROM `albums` a
									LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_match' AND tp.user_id =   $loginuser AND `status` = 'accepted' 
									WHERE a.imageable_id = $action_id AND a.id = $album_id AND a.user_id= $loginuser AND a.deleted_at is NULL");
				}


				DB::setFetchMode(PDO::FETCH_CLASS);

			}
			else if($action=='user')
			{

				$photo_array[] = Photo::select()->where('album_id',$album_id)->where('id',$insertedid)->where('imageable_type', 'gallery_user')->get()->toArray();

			}
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
			// Helper::setMenuToSelect(9,2);
		}else
		{
			Helper::setMenuToSelect(3,4);
		}

		$lef_menu_condition = 'display_gallery';
		$userid = Auth::user()->id;


		if($imageable_type[0]!='user_photo')
		{

			$album_array = Album::select('title', 'id')->where('user_id',$userid )->where('imageable_type',$imageable_type[0])->where('imageable_id',$action_id)->get()->toArray();


		}
		else
		{
			$album_array = Album::select('title', 'id')->where('user_id',$userid )->where('imageable_type','gallery_user')->where('imageable_id',$action_id)->get()->toArray();

		}


		if ($type!='')
		{

			$viewhtml =  View::make('album.viewalbumgallery')->with(array('photo_array'=>$photo_array,'create_photo'=>$create_photo,'album_id'=>$album_id,'action'=>$action,'action_id'=>$action_id,'lef_menu_condition'=>$lef_menu_condition,'userId'=> $userid, 'album_array' =>$album_array,'uploadimage'=>$uploadimage,	'result'=>	$result,'loginid'=>$loginid,'user_id'=>$user_id,'flag'=>$flag,'tournament_type'=>$tournament_type,'tournament_id'=>$action_id,'orgphotocreate'=>$orgphotocreate ))->render();

			$returnHTML =  View::make('album.gallery')->with(array('photo_array'=>$photo_array,'create_photo'=>$create_photo,'album_id'=>$album_id,'action'=>$action,'action_id'=>$action_id,'lef_menu_condition'=>$lef_menu_condition,'userId'=> $userid, 'album_array' =>$album_array,'uploadimage'=>$uploadimage,	'result'=>	$result,'loginid'=>$loginid,'user_id'=>$user_id,'flag'=>$flag,'tournament_type'=>$tournament_type,'tournament_id'=>$action_id,'orgphotocreate'=>$orgphotocreate ))->render();

			return Response::json(array('html' =>$returnHTML,'msg'=>'success','upload'=>$upload,'viewhtml'=>$viewhtml));

		}
		// else
		// {
		// return 	view('album.viewalbumphoto',array('photo_array'=>$photo_array,'create_photo'=>$create_photo,'album_id'=>$album_id,'action'=>$action,'action_id'=>$action_id,'lef_menu_condition'=>$lef_menu_condition,'userId'=> $userid, 'album_array' =>$album_array,'uploadimage'=>$uploadimage,'result'=>	$result,'upload'=>$upload,'loginid'=>$loginid,'user_id'=>$user_id,'flag'=>$flag,'tournament_type'=>$tournament_type,'tournament_id'=>$action_id,'id'=>$action_id));

		// }


	}
//for album upload images window
	public function albumphotocreate(Request $request)
	{


		$rules = array( 'filelist_gallery' => 'required');

		$validator = Validator::make($request->all(), $rules);
		Helper::setMenuToSelect(3,4);
		if ($validator->fails())
		{
			return Response::json(array(
				'status' => 'fail',
				'msg' => $validator->getMessageBag()->toArray()

			));

		}


		else
		{

			if(!empty($request['filelist_gallery']))
			{

				$request['user_id'] = Auth::user()->id;
				//Upload Photos
				if(!empty($request['albumid']))
				{
					$albumID = $request['albumid'];
				}
				else
				{
					$albumID = $request['album_id'];//Default album if no album is not selected.
				}
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
				else if($action=='match')
				{
					$image_type = config('constants.PHOTO.GALLERY_MATCH');
				}
				$insertedphotoids =	Helper::uploadPhotos($request['filelist_gallery'],config('constants.PHOTO_PATH.GALLERY'),$id,$albumID,$coverPic,$image_type,$user_id = Auth::user()->id,$action,$id,$image_type);

			}



			Helper::setMenuToSelect(3,4);

			return Response::json(array('msg'=>'success','album_id'=>$albumID ,'user_id'=>Auth::user()->id,'is_user_profile'=>'','action'=>$action,'action_id'=>$id,'insertedphotoids' =>$insertedphotoids ));
		}

	}
	public function Likecount(Request $request)
	{
		$idval = $request['id'];
		$id = ','. Auth::user()->id;
		$list= Photo::select()->where('id', $idval)->get()->toArray();
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
	//delete photo
	public function deleteAlbumPhoto($id,$flag)
	{


		$loginuserid=isset(Auth::user()->id)?Auth::user()->id:0;

		$url="";
		$delete_photo_id =$id;
		$orgphotocreate = array();
		if($flag=='album' || $flag=='albumwithnoimages')
		{
			$album=Album::select()->where('id',$delete_photo_id)->get()->toArray();
			$imageable_id=$album[0]['imageable_id'];
			$album_id=$album[0]['id'];

			if($album[0] ['imageable_type']=="gallery_user")
			{
				//to check delete permission
				if($album[0] ['imageable_id']== $loginuserid)
				{
					$photos=Photo::where('album_id',  $delete_photo_id )->get();
					$count= count( $photos);
					if($count!=0)
					{
						Album::where('id',  $delete_photo_id )->delete();
						Photo::where('album_id',  $delete_photo_id )->delete();
					}
					else
					{
						Album::where('id',  $delete_photo_id )->delete();
					}
					return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
				}
				else
				{
					return Response::json(array('msg'=>'fail','id'=>$delete_photo_id));
				}
			}
			else if($album[0]['imageable_type']=="gallery_team")
			{
				//to check delete permission
				DB::setFetchMode(PDO::FETCH_ASSOC);
				$album_array = DB::select("SELECT a.id,a.user_id,a.title,
										(CASE tp.role
										WHEN 'owner' THEN 'yes'
										WHEN 'manager' THEN 'yes'
										WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
										FROM `albums` a
										LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_team'   AND tp.user_id =$loginuserid AND `status` = 'accepted'
										WHERE a.imageable_id =  $imageable_id AND a.id= $album_id  AND a.deleted_at is NULL");

				DB::setFetchMode(PDO::FETCH_CLASS);
				if($album_array[0]['accessflag']=="yes")
				{

					$photos=Photo::where('album_id',  $delete_photo_id )->get();
					$count= count( $photos);
					if($count!=0)
					{
						Album::where('id',  $delete_photo_id )->delete();
						Photo::where('album_id',  $delete_photo_id )->delete();
					}
					else
					{
						Album::where('id',  $delete_photo_id )->delete();
					}
					return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
				}
				else
				{

					return Response::json(array('msg'=>'fail','id'=>$delete_photo_id));
				}

			}
			else if($album[0]['imageable_type']=="gallery_organization")
			{

				//to check delete permission
				$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
				DB::setFetchMode(PDO::FETCH_ASSOC);
				$orgphotocreate = DB::select("SELECT * FROM `organization` tp WHERE `user_id` = $loginUserId AND `id`=$imageable_id AND tp.deleted_at is NULL");
				DB::setFetchMode(PDO::FETCH_CLASS);
				if(count($orgphotocreate)!=0)
				{


					$photos=Photo::where('album_id',  $delete_photo_id )->get();
					$count= count( $photos);
					if($count!=0)
					{
						Album::where('id',  $delete_photo_id )->delete();
						Photo::where('album_id',  $delete_photo_id )->delete();
					}
					else
					{
						Album::where('id',  $delete_photo_id )->delete();
					}
					return Response::json(array('msg'=>'success','id'=>$delete_photo_id));

				}




			}

			else if($album[0]['imageable_type']=="gallery_match")
			{

				/************************/
				DB::setFetchMode(PDO::FETCH_ASSOC);

				$teamids = DB::select("SELECT * FROM `match_schedules` tp WHERE id = $imageable_id  AND tp.deleted_at is NULL") ;

				$tournament=MatchSchedule::select('tournament_id')->where('id', $imageable_id)->get()->toArray();
				$flag="no";
				if(isset( $tournament[0]['tournament_id']) && $tournament[0]['tournament_id']!='')
				{
					$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
					$tournamentresult=Helper::isValidUserForTournamentGallery($tournament[0]['tournament_id'],	$loginUserId);
					if($tournamentresult==1)
					{
						$flag="yes";
					}
				}
				$ids = '';
				$match_schedule_type = '';
				if(count($teamids) > 0)	{

					$match_schedule_type = $teamids[0]['schedule_type'];

					$teamid = array();
					foreach($teamids as $t)
					{
						$teamid[]= $t['a_id'];
						if(!empty($t['b_id']))
							$teamid[]= $t['b_id'];
					}
					$ids=implode(',',$teamid);
				}
				$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest

				if($match_schedule_type == 'player'){

					// $matchalbumcreate = DB::select("SELECT * FROM `match_schedules` tp WHERE id = $album[0]['imageable_id'] AND (a_id = $loginuserId OR b_id = $loginuserId)");

					DB::setFetchMode(PDO::FETCH_ASSOC);
					$album_array = DB::select("SELECT a.id,a.user_id,a.title,
									(CASE a.user_id
									WHEN $loginuserId THEN 'yes'
									ELSE 'no' END) AS 'accessflag'
									FROM `albums` a
									LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'player'									
									WHERE a.imageable_id =  $imageable_id AND a.deleted_at is NULL GROUP BY a.id;
									");
					DB::setFetchMode(PDO::FETCH_CLASS);
				}
				else{

					// $matchalbumcreate = DB::select("SELECT * FROM `team_players` tp WHERE tp.user_id =$loginuserId AND tp.team_id in ($ids) AND `status` = 'accepted'");
					DB::setFetchMode(PDO::FETCH_ASSOC);
					$album_array = DB::select("SELECT a.id,a.user_id,a.title,
									(CASE tp.role
									WHEN 'owner' THEN 'yes'
									WHEN 'manager' THEN 'yes'
									WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
									FROM `albums` a
									LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'team' 
									LEFT JOIN `team_players` tp ON (ms.a_id = tp.team_id OR ms.b_id = tp.team_id) AND tp.user_id = $loginuserId
									WHERE a.imageable_id =  $imageable_id  AND a.deleted_at is NULL GROUP BY a.id;
									");
					DB::setFetchMode(PDO::FETCH_CLASS);
					//echo $flag.count($matchalbumcreate)	;exit;
				}

				if($album_array[0]['accessflag']=="yes")
				{

					$photos=Photo::where('album_id',  $delete_photo_id )->get();
					$count= count( $photos);
					if($count!=0)
					{
						Album::where('id',  $delete_photo_id )->delete();
						Photo::where('album_id',  $delete_photo_id )->delete();
					}
					else
					{
						Album::where('id',  $delete_photo_id )->delete();
					}
					return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
				}
				else
				{

					return Response::json(array('msg'=>'fail','id'=>$delete_photo_id));
				}

			}
			else if($album[0]['imageable_type']=="gallery_tournaments")
			{
				$result=Helper::isValidUserForTournamentGallery($imageable_id,$loginuserid);
				if($result=='1')
				{
					$photos=Photo::where('album_id',  $delete_photo_id )->get();
					$count= count( $photos);
					if($count!=0)
					{
						Album::where('id',  $delete_photo_id )->delete();
						Photo::where('album_id',  $delete_photo_id )->delete();
					}
					else
					{
						Album::where('id',  $delete_photo_id )->delete();
					}
					return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
				}
				else
				{

					return Response::json(array('msg'=>'fail','id'=>$delete_photo_id));
				}
			}


			// Album::where('id',  $delete_photo_id )->delete();
			// Photo::where('album_id',  $delete_photo_id )->delete();
		}

		else if($flag=='userprofile')
		{

			$profileurl= photo::where('id',  $delete_photo_id )->select('url')->get()->toArray();
			$profilephotos=Photo::select()->where('id',$delete_photo_id)->get()->toArray();
			// echo "<pre>";print_r( $profileurl);exit;
			if($profilephotos[0]['imageable_type']=="user_photo")
			{
				if($profilephotos[0]['imageable_id']== $loginuserid)
				{
					if(count( $profileurl)!=0)
					{
						if( $profileurl[0]['url']==session('profilepic'))
						{
							session(['profilepic' =>  $url]);
							Photo::find($delete_photo_id)->delete();
							User::where('id',$profilephotos[0]['imageable_id'])->update(['logo' =>null]);
							return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
						}
						else
						{
							Photo::find($delete_photo_id)->delete();
							User::where('id',$profilephotos[0]['imageable_id'])->update(['logo' =>null]);
							return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
						}
					}
				}
				else
				{
					return Response::json(array('msg'=>'fail','id'=>$delete_photo_id));

				}
			}

		}
		else
		{
			$p=Photo::select()->where('id',$delete_photo_id)->get()->toArray();
			$imageable_id=$p[0]['imageable_id'];
			$album_id=$p[0]['album_id'];
			$photo_id=$p[0]['id'];

			if($p[0] ['imageable_type']=="gallery_user")
			{
				if($p[0] ['imageable_id']== $loginuserid)
				{
					Photo::find($delete_photo_id)->delete();
					return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
				}
				else{
					return Response::json(array('msg'=>'fail','id'=>$delete_photo_id));
				}
			}
			else if($p[0] ['imageable_type']=="gallery_team")
			{



				DB::setFetchMode(PDO::FETCH_ASSOC);
				$photo_array = 	 DB::select("SELECT a.*,
												(CASE tp.role 
												WHEN 'owner' THEN 'yes' 
												WHEN 'manager' THEN 'yes' 
												WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
												FROM `photos` a
												LEFT JOIN `team_players` tp ON a.imageable_id = tp.team_id AND a.imageable_type = 'gallery_team' AND tp.user_id = $loginuserid
												WHERE a.imageable_id =$imageable_id AND a.album_id =$album_id AND a.id=$photo_id   AND a.deleted_at is NULL");

				DB::setFetchMode(PDO::FETCH_CLASS);

				if($photo_array[0]['accessflag']=="yes")
				{
					Photo::find($delete_photo_id)->delete();
					return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
				}
				else{

					return Response::json(array('msg'=>'fail','id'=>$delete_photo_id));
				}

			}
			else if($p[0] ['imageable_type']=="gallery_tournaments")
			{
				$result=Helper::isValidUserForTournamentGallery( $imageable_id,	$loginuserid);
				if($result=='1')
				{
					Photo::find($delete_photo_id)->delete();
					return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
				}
				else{

					return Response::json(array('msg'=>'fail','id'=>$delete_photo_id));
				}
			}
			else if($p[0] ['imageable_type']=="form_gallery_tournaments")
			{

				Photo::find($delete_photo_id)->delete();
				return Response::json(array('msg'=>'success','id'=>$delete_photo_id));

			}
			else if($p[0] ['imageable_type']=="form_gallery_organization")
			{

				Photo::find($delete_photo_id)->delete();
				return Response::json(array('msg'=>'success','id'=>$delete_photo_id));

			}
			else if($p[0] ['imageable_type']=="gallery_match")
			{

				/************************/

				$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
				$tournament=MatchSchedule::select()->where('id',$p[0]['imageable_id'])->get()->toArray();
				$flag="no";
				if(isset( $tournament[0]['tournament_id']) && $tournament[0]['tournament_id']!='')
				{

					$tournamentresult=Helper::isValidUserForTournamentGallery($tournament[0]['tournament_id'],	$loginUserId);
					if($tournamentresult==1)
					{
						$flag="yes";
					}
				}

				$loginuser=Auth::user()->id;
				DB::setFetchMode(PDO::FETCH_ASSOC);
				// $photo_array = Photo::select()->where('album_id',$album_id)->whereIn('imageable_type', $imageable_type)->get()->toArray();
				$match_schedule_type = $tournament[0]['schedule_type'];
				if($match_schedule_type == 'player'){

					$photo_array = DB::select("SELECT a.*,
										(CASE a.user_id
										WHEN $loginUserId THEN 'yes'
										ELSE 'no' END) AS 'accessflag'
										FROM `photos` a
										LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'player'									
										WHERE a.imageable_id = $imageable_id AND a.album_id=$album_id  AND a.id = $photo_id  AND a.deleted_at is NULL GROUP BY a.id;
										");
				}
				else{

					$photo_array = 	 DB::select("SELECT a.*,
										(CASE tp.role
										WHEN 'owner' THEN 'yes'
										WHEN 'manager' THEN 'yes'
										WHEN 'player' THEN IF(a.user_id = tp.user_id,'yes','no') ELSE 'no' END) AS 'accessflag'
										FROM `photos` a
										LEFT JOIN `match_schedules` ms ON a.imageable_id = ms.id AND a.imageable_type = 'gallery_match' AND ms.schedule_type = 'team' 
										LEFT JOIN `team_players` tp ON (ms.a_id = tp.team_id OR ms.b_id = tp.team_id) AND tp.user_id =$loginuser
										WHERE a.imageable_id = $imageable_id  AND a.album_id=$album_id   AND a.id=$photo_id AND a.deleted_at is NULL GROUP BY a.id");
				}

				DB::setFetchMode(PDO::FETCH_CLASS);

				if( $photo_array[0]['accessflag']=="yes"  || $flag=='yes')
				{
					Photo::find($delete_photo_id)->delete();
					return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
				}
				else{
					return Response::json(array('msg'=>'fail','id'=>$delete_photo_id));
				}
			}

		}

		// return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
	}

	public function editAlbumPhoto(Request $request)
	{
		$action=$request['action'];
		$action_id=$request['action_id'];
		$editid = $request['id'];
		$album_array = Album::select('id','title','description')->where('id',  $editid)->get()->toArray();
		return view('album.edit')->with(array('album_array'=>$album_array,'action'=>$action,'action_id'=>$action_id));

	}
	//album edit
	public function editstore(Request $request)
	{

		$action=$request['action'];
		$action_id=$request['action_id'];
		$id = $request['id'];
		$count=0;
		$title = $request['title'];
		$description = $request['description'];

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
		else if($action=='match')
		{

			$imageable_type_name = config('constants.PHOTO.GALLERY_MATCH');
		}
		// $rules = array( 'title' => 'required|max:20|unique:albums,title|'.config('constants.VALIDATION.CHARACTERSANDSPACE'));
		if($request['duplicate_album']=='yes')
		{
			$rules = array( 'title' => 'required|max:20|'.config('constants.VALIDATION.CHARACTERSANDSPACE') );

			$count=Album::select()->where('title',$title )->where('imageable_id',$action_id )->where('id','<>',$id )->where('imageable_type',$imageable_type_name )->count();

		}
		else
		{
			$rules = array( 'title' => 'required|max:20|'.config('constants.VALIDATION.CHARACTERSANDSPACE'));
		}
		$validator = Validator::make($request->all(), $rules);



		if ($validator->fails() || $count>0 )
		{
			if($count>0)
			{

				return Response::json(array(
					'status' => 'fail',
					'msg' => "The title has already been taken."

				));
			}

			else
			{
				return Response::json(array(
					'status' => 'fail',
					'msg' => $validator->getMessageBag()->toArray()

				));
			}


		}
		else
		{
			Album::where('id', $id )->update(['title' =>  $title]);
			Album::where('id', $id )->update(['description' =>$description ]);


			return Response::json(array('status'=>'success','msg'=> trans('message.album.albumcreate'),'action'=>$action,'action_id'=>$action_id,'user_id'=>Auth::user()->id,'lastinsertedid'=>$id,'name'=>$title ));
			// return view('album.edit')->with(array('album_array'=>$album_array));
		}

	}

	//for form gallery
	public function Formgallery($action_id,$action)
	{


		$orgformcreate  ="";
		$tournamentformcreate="";
		$photo_array = array();
		$left_menu_data="";
		$lef_menu_condition="";
		$tournament_type="";
		$formaction ="";
		if($action=="formtournaments")
		{

			$lef_menu_condition = 'display_gallery';
			$formaction ="tournaments";
			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
			$result=Helper::isValidUserForTournamentGallery($action_id,	$loginUserId);
			$photo_array = Photo::select()->where('imageable_id',$action_id)->where('imageable_type','form_gallery_tournaments')->get()->toArray();

			$tournamentDetails = Tournaments::where('id',$action_id)->get(['type','tournament_parent_id','manager_id','name','match_type','sports_id']);
			$tournament_type = $tournamentDetails[0]['type'];
			//left menu
			$parent_tournamet_id = $tournamentDetails[0]['tournament_parent_id'];
			$tournamentManagerId = $tournamentDetails[0]['manager_id'];
			$tournamentName  = $tournamentDetails[0]['name'];
			DB::setFetchMode(PDO::FETCH_ASSOC);
			$tournamentformcreate = DB::select("SELECT * FROM `tournaments` tp WHERE `created_by` = $loginUserId AND `id`=$action_id AND tp.deleted_at is NULL");
			DB::setFetchMode(PDO::FETCH_CLASS);
			$left_menu_data = Helper::getLeftMenuData($parent_tournamet_id,$tournamentManagerId,$tournamentDetails);


		}
		if($action=="formorganization")
		{

			$loginUserId= isset(Auth::user()->id)?Auth::user()->id:0;  //user or guest
			DB::setFetchMode(PDO::FETCH_ASSOC);
			$orgformcreate = DB::select("SELECT * FROM `organization` tp WHERE `user_id` = $loginUserId AND `id`=$action_id AND tp.deleted_at is NULL");
			$photo_array = Photo::select()->where('imageable_id',$action_id)->where('imageable_type','form_gallery_organization')->get()->toArray();
			DB::setFetchMode(PDO::FETCH_CLASS);
			$formaction ="organization";


		}

		return 	view('album.formgallery',array('photo_array'=>$photo_array,'imageable_id'=>$action_id,'action'=>$formaction ,'left_menu_data' =>$left_menu_data,'action_id'=>$action_id, 'tournament_id' => $action_id,'lef_menu_condition'=>$lef_menu_condition,	'tournament_type'=>	$tournament_type,'id'=>$action_id,'orgformcreate' =>$orgformcreate ,'tournamentformcreate' =>$tournamentformcreate  ));
	}


}
?>