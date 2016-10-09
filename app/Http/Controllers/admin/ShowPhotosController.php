<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Photo;
use App\user;
use Auth;
use Input;
use Validator;
use Session;
use Redirect;
use App\Helpers\Helper;
use Response;
use View;
use App\Model\Tournaments;
use App\Model\Organization;
use App\Model\Facilityprofile;
use App\Model\Team;

class ShowPhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$limit = config('constants.LIMIT');
	    $offset = 0;
		
		   $list=photo::select( )->limit($limit)->offset($offset)->orderBy('created_at','desc')->get()->toArray();
		    $totalcount=photo::select( )->count();
		
			$name=array();
			foreach( $list as $l)
			{
					$name[$l['user_id']] = User::select('id','name')->where('id',$l['user_id'])->get()->toArray();
				
			}
		
	    //$returnHTML =  View::make('admin.adminphotos.list')->with(array('list'=>$list,'name'=>$name))->render();	

	     return view('admin.adminphotos.show')->with(array('list'=>$list,'totalcount'=>  $totalcount,'limit'=>$limit,'offset'=>$limit+$offset ,'name'=>$name));	
    }
	
	
	
	
	
	public function viewMorePhotos(Request $request)
   {
	  $limit=$request['limit'];
	  $offset=$request['offset'];
	  $list=photo::select( )->limit($limit)->offset($offset)->orderBy('created_at','desc')->get();
	  $totalcount=photo::select( )->count();

			$name=array();
			foreach( $list as $l)
			{
					$name[$l['user_id']] = User::select('id','name')->where('id',$l['user_id'])->get()->toArray();
				
			}
			
		
			// $returnHTML =  View::make('admin.adminphotos.list')->with(array('list'=>$list,'name'=>$name))->render();	

			//   return view('admin.adminphotos.show')->with(array('list'=>$list,'html' =>$returnHTML,'totalcount'=> $totalcount_photos,'limit'=>$limit,'offset'=>$limit+$offset ));	

			//return \Response::json(array('list'=>$list,'html' =>$returnHTML,'totalcount'=>  $totalcount_photos,'limit'=>$limit,'offset'=>$limit+$offset));
			  
	    return view('admin.adminphotos.list')->with(array('list'=>$list,'totalcount'=>  $totalcount,'limit'=>$limit,'offset'=>$limit+$offset,'name'=>$name ));	
	   
	  
   }
   public function deletephoto(Request $request)
	{
		
   		 $delete_photo_id =$request['id'];
		 $imagetype= $request['imagetype'];
		  $photodetails= photo::where('id',  $delete_photo_id )->select('imageable_id')->get()->toArray();
		 Photo::find($delete_photo_id)->delete();
		 
		 if( $imagetype=="organization")
		 {
			   Organization::where('id', $photodetails[0]['imageable_id'])->update(['logo' =>null]);
		 }
		  if( $imagetype=="facility")
		 {
			   Facilityprofile::where('id', $photodetails[0]['imageable_id'])->update(['logo' =>null]);
		 }
		  if( $imagetype=="tournaments")
		 {
			   Tournaments::where('id', $photodetails[0]['imageable_id'])->update(['logo' =>null]);
		 }
		
		 if( $imagetype=="teams")
		 {
			   Team::where('id', $photodetails[0]['imageable_id'])->update(['logo' =>null]);
		 }
		  if( $imagetype=="user_photo")
		 {
			   User::where('id', $photodetails[0]['imageable_id'])->update(['logo' =>null]);
		 }
		 return \Response::json(array('msg'=>'success','id'=>$delete_photo_id));
	
	}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateMarketPlaceRequest $request)
    {
       //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		//
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
		

   
}
