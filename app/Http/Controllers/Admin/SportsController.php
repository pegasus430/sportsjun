<?php

namespace App\Http\Controllers\admin;

//namespace App\Http\Controllers\Controller;

use App\User;
use App\Model\Sport;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;
use Auth;
use Zofe\Rapyd\RapydServiceProvider;
use Request;
use App\Model\SportQuestion;
use App\Model\SportQuestionOption;
Use App\Model\Photo;
use App\Http\Requests;
use Response;
use App\Model\TeamPlayer;
use App\Helpers\Helper;

class SportsController extends Controller {

    public function index() {
        //
		  $sports_tpe= config('constants.ENUM.SPORTS.SPORTS_TYPE'); 
		 return view('admin.sports.createsports',array('sports_type'=>  $sports_tpe));
        //return redirect()->route('viewsports');
    }
	public function insertsport()
	{
			$request = Request::all();
			$rules = ['sportsname' => 'required|max:20|unique:sports,sports_name|'.config('constants.VALIDATION.CHARACTERSANDSPACE')];
			$v = Validator::make($request, $rules);
			$msg=array();
			$msg=$v->getMessageBag()->toArray();			
			
			if ($v->fails())
			{
								
					foreach($msg as $m)
					{
						   return redirect()->back()->withErrors(["sportsname" => [0 =>$m[0]]]);
					}

			}
			$user = User::find(Auth::user()->id);
			$created_by = $user->id;
			
			$sport_name = Request::get('sportsname');
			$sports_type  =  Request::get('sports_type');
			$sports = new Sport();
			$sports->sports_name = $sport_name;
			$sports->sports_type = $sports_type;
			$sports->created_by = $created_by;
	 
			$last_inserted_sport_id = 0;
			if($sports->save())
			{
				$last_inserted_sport_id = $sports->id;
			}
			if($last_inserted_sport_id>0)
			{
				
				$destinationPath = config('constants.SPORT_LOGO_UPLOAD_PATH');
				
				$request['user_id'] = Auth::user()->id;
				//Upload Photos
				$albumID = 1;//Default album if no album is not selected.
				$coverPic = 1;
				if(isset($input['album_id']) && $input['album_id'])
					$albumID = $input['album_id'];
				if(isset($input['cover_pic']) && $input['cover_pic'])
					$coverPic = $input['cover_pic'];   
				
				Helper::uploadPhotos($request['filelist_logo'],config('constants.PHOTO_PATH.SPORTS'),$last_inserted_sport_id,$albumID,$coverPic,config('constants.PHOTO.SPORT_LOGO'),$user_id = Auth::user()->id);
				//End Upload Photos        
				//redirect('/');
				
			}
		   return redirect()->route('viewsports')->with('status', trans('message.sports.created'));
	}
	public function viewsports()
	{
		$sports_array2 = Sport::select('sports_name', 'id')->get();
		$sports_array1 = array('0'=>'select sport');
		$sport_id_arr=array();
		
		foreach($sports_array2  as $cat)
		{
			$sports_array1[$cat->id] = $cat->sports_name;
		}
		
		$filter = \DataFilter::source(Sport::where('isactive',1)->orderBy('id','ASC'));
		 $filter->add('id','Sports name','select',true)->options($sports_array1)
		  ->scope( function ($query, $value) use ($sports_array2) {
			
			 if($value>0)
			 {
				 return $query->whereIn('id', [$value] );  
			 }else if($value==0)
			 {
				 $sports_array2 = Sport::where('isactive','=',1)->lists('sports_name', 'id')->toArray();
				 foreach($sports_array2 as $key => $val)
				 {
					 $sport_id_arr[] = $key;
				 }
				 
				  return $query->whereIn('id', $sport_id_arr);  
			 }
			
			});	
	//	$filter->add('sports_name','Sport Name', 'text');
		$filter->submit('search');
        $filter->reset('reset');
        $filter->build();
		$grid = \DataGrid::source($filter);
		$grid->add('id','ID', true)->style("width:100px");
		$grid->add('sports_name','SPORT NAME', true);
       
		$grid->link('/admin/createsport',"Add New", "TR");  //add button
		$grid->add('view','Question & Option View')->cell( function( $value, $row) {
			//return "<a href='/admin/viewoption/".$row->id."'>Create Sports Option</a>";
			return "<a href='".url()."/admin/viewoption/".$row->id."'>Create Sports Option</a>";
		}); 
		
		$grid->edit( url('admin/deletesport'), 'Operation','modify|delete');
		
		//$grid->edit('deletesport', 'Operation','modify');
		
        $grid->orderBy('id','desc');
        $grid->paginate(config('constants.DEFAULT_PAGINATION'));
		$feild_name = 'Sports List';
		// $filter='';
		
		return view('admin.sports.viewsports',array('grid' => $grid,'feild_name' => $feild_name,'filter'=>$filter));
	}
	public function deletesport()
	{
		$request = Request::all();
		$delete_sport_id = Request::get('delete');
		$edit_sport_id = Request::get('modify');
	
		if($delete_sport_id!='' && $delete_sport_id>0)
		{
			if(!($delete_sport_id==1 || $delete_sport_id==2 || $delete_sport_id==3 || $delete_sport_id==4))
			{
				$sports = Sport::find($delete_sport_id)->delete();
				return redirect()->route('viewsports')->with('status', trans('message.sports.deletesport'));
			}else
			{
				return redirect()->route('viewsports')->with('status', 'You Can Not Delete This Sport.');
			}
			
		}else if($edit_sport_id!='' && $edit_sport_id>0)
		{
			$sports = new Sport();
			$sports_array = $sports->select()->where('id', $edit_sport_id)->get();
			$sport_image = Sport::findOrFail($edit_sport_id);
			$sports_type= config('constants.ENUM.SPORTS.SPORTS_TYPE'); 
			$a = $sport_image->photos;
			$sport_logo = photo::select()->where('imageable_id', $edit_sport_id)->where('imageable_type','sports')->where('is_album_cover',1)->get();
		

			return view('admin.sports.createsports',array('sports_array' => $sports_array,'sport_image' => $sport_image,'sport_logo' => $sport_logo, 'sports_type'=> $sports_type));
		}
	}
	public function createoption()
	{
		//$sports = Sports::find(1)->where('isactive',1);
		
		$sports = new Sport();
		$array = $sports->select()->where('isactive', 1)->get();
		$sports_name_arr = array();
		foreach($array as $arr)
		{
			$sports_name_arr[$arr->id] = $arr->sports_name;
		}
		return view('admin.sports.createoption',array('sports_array' => $sports_name_arr));
	}
	public function saveoptions()
	{
		$request = Request::all();

		$rules = ['sports_id' => 'required','question' => 'required','radio_button' => 'required','option_type' => 'required'];
	
		if(Request::get('sports_id') && Request::get('question') && Request::get('option_type') && Request::get('radio_button')){
			$v = Validator::make($request, $rules);
			if ($v->fails()) {
				 return redirect()->back()->withErrors(["sports_id" => [0 => "Select sportsname."]]);
			return redirect()->back()->withErrors($v->errors());
		}
			$sports_id = Request::get('sports_id');
			$question = Request::get('question');
			$option_type_array = Request::get('option_type');
			$radio_or_check = Request::get('radio_button');
			
			foreach($question as $key =>$value)
			{ 
				$user = User::find(Auth::user()->id);
				$created_by = $user->id;
				$sports_questions = new SportQuestion();
				$sports_questions->created_by = $created_by;
				$sports_questions->sports_id = $sports_id;
				$sports_questions->sports_question = $value;
				$sports_questions->sports_element = $radio_or_check[$key]; 
				
				$last_inserted_question_id = 0;
				if($sports_questions->save())
				{
					$last_inserted_question_id = $sports_questions->id;
				}
				if($last_inserted_question_id>0)
				{
					foreach($option_type_array[$key] as $options)
					{
						$sports_question_options = new SportQuestionOption();
						$sports_question_options->sports_questions_id = $last_inserted_question_id;
						$sports_question_options->options = $options;
						$sports_question_options->created_by = $created_by;
						$sports_question_options->save();
					}
				}
			}
		}

		if(Request::get('sports_id') && Request::get('questions') && Request::get('option_types') && Request::get('radio_buttons'))
		{
			$sports_id = Request::get('sports_id');
			$question = Request::get('questions');
			$option_type_array = Request::get('option_types');
			$radio_or_check = Request::get('radio_buttons');
			$deleted_options = Request::get('delete_options');
			$new_option_types = Request::get('new_option_types');
			$delete_questions = Request::get('delete_questions');

			$options_array = array();
			if($deleted_options!='')
			{
				$options_array = array_filter(explode(',',$deleted_options));
			}
			foreach($options_array as $option_id)
			{
				$sports = SportQuestionOption::find($option_id)->delete();
			}
			$question_array = array();
			if($delete_questions!='')
			{
				$question_array= array_filter(explode(',',$delete_questions));
			}
			foreach($question_array as $q_id)
			{
				$sports = SportQuestion::find($q_id)->delete();
			}
			if(count($new_option_types)>0)
			{
				foreach($new_option_types as $question_id =>$option_value)
				{
					$user = User::find(Auth::user()->id);
					$created_by = $user->id;
					$sports_question_options = new SportQuestionOption();
					$sports_question_options->sports_questions_id = $question_id;
					$sports_question_options->options = $option_value;
					$sports_question_options->created_by = $created_by;
					$sports_question_options->save();
				}
			}
			foreach($question as $qid => $val)
			{
				
				SportQuestion::where('id', '=', $qid)->update(['sports_question' => $val,'sports_element' => $radio_or_check[$qid]]);
			}
			foreach($option_type_array as $option_id => $option)
			{
				SportQuestionOption::where('id', '=', $option_id)->update(['options' => $option]);
				
			}
				
		}
		return redirect()->route('viewsports')->with('status', trans('message.sports.created'));
	}
	public function editsport()
	{
		
		$request = Request::all();
		$sports_id = Request::get('sports_id');
		$sportsname = Request::get('sportsname');
		 $sports_type= Request::get('sports_type');
		 
		 $rules = ['sportsname' => 'required|max:20|unique:sports,sports_name,'.$sports_id.'|'.config('constants.VALIDATION.CHARACTERSANDSPACE')];
		 
		
			$v = Validator::make($request, $rules);
			$msg=array();
			$msg=$v->getMessageBag()->toArray();			
			
			if ($v->fails())
			{
								
					foreach($msg as $m)
					{
						   return redirect()->back()->withErrors(["sportsname" => [0 =>$m[0]]]);
					}

			}
	
//		if($sports_id!='' && $sportsname!='')
		if($sports_id!='')
		{
			if(!($sports_id==1 || $sports_id==2 || $sports_id==3 || $sports_id==4))
			{
				if($sportsname!='')
				{
					Sport::where('id', '=', $sports_id)->update(['sports_name' => $sportsname,'sports_type'=> $sports_type]);
					
				}
				
			}
			Sport::where('id', '=', $sports_id)->update(['sports_type'=> $sports_type]);
				$destinationPath = config('constants.SPORT_LOGO_UPLOAD_PATH');
				if(!empty($request['filelist_logo']))
				{
					$userPhotos = Photo::where(['imageable_id' => $sports_id, 'imageable_type' => config('constants.PHOTO.SPORT_LOGO')])->update(['is_album_cover' => 0]);
					 
					
					$request['user_id'] = Auth::user()->id;
					//Upload Photos
					$albumID = 1;//Default album if no album is not selected.
					$coverPic = 1;
					if(isset($input['album_id']) && $input['album_id'])
						$albumID = $input['album_id'];
					if(isset($input['cover_pic']) && $input['cover_pic'])
						$coverPic = $input['cover_pic'];        
					Helper::uploadPhotos($request['filelist_logo'],config('constants.PHOTO_PATH.SPORTS'),$sports_id,$albumID,$coverPic,config('constants.PHOTO.SPORT_LOGO'),$user_id = Auth::user()->id);
				}
				
			
		}
		return redirect()->route('viewsports')->with('status', trans('message.sports.updated'));
	}
	public function viewoption($id)
	{
		$questions = SportQuestion::with('options')->where('sports_id','=',$id)->get();
		$sports = new Sport();
		$sports_name = $sports->select()->where('id', $id)->get();
		return view('admin.sports.createoption',array('sports_array' => $questions,'sports_name' => $sports_name));
	}
	public function teamplayer()
	{
		return view('admin.sports.teamplayer');
	}
	public function source($sport_id,$team_id)
	{
		$user = Request::get('term');
		//$sport_id = Request::get('sport_id');//sport ids
		//$team_id = Request::get('team_id');
		$results = array();
		
		$team_player_qry = DB::table('team_players')
						->select('team_players.user_id')
						 ->where('team_id', '=', $team_id)->get();
		$team_user_id = array();				
		foreach($team_player_qry as $team)
		{
			$team_user_id[] = $team->user_id;
		}

		$users = array();
			$users = DB::table('users')
            ->join('user_statistics', 'users.id', '=', 'user_statistics.user_id')
            ->select('users.id','users.name')
			->where('users.name','LIKE','%'.$user.'%')
			// ->where('users.is_available','=','1')
			// ->where('user_statistics.following_sports','LIKE','%,'.$sport_id.',%')
			->where('user_statistics.allowed_sports','LIKE','%,'.$sport_id.',%')
			->whereNotIn('users.id',$team_user_id)
            ->get();
		if(count($users)>0)
		{
			foreach ($users as $query)
			{
				$results[] = ['id' => $query->id, 'value' => $query->name];
			}
		}
		return Response::json($results);
	}
	public function addplayer()
	{
		$user_id = Request::get('response');
		if($user_id>0)
		{
			$team_id = Request::get('team_id');
			$role = 'player';
			$user_id = Request::get('response');
			$TeamPlayer = new TeamPlayer();
			$TeamPlayer->team_id = $team_id;
			$TeamPlayer->user_id = $user_id;
			$TeamPlayer->role = $role;
			$TeamPlayer->save();
			//return Response::json($results);
			return Response()->json( array('status' => 'success','msg' => trans('message.sports.teamplayer')) );
		}
		else{
			return Response()->json( array('status' => 'fail','msg' => trans('message.sports.invalidplayername')) );
		}
		
	}
}
?>