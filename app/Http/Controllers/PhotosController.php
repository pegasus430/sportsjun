<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Photo;
use Auth;
use App\User;
use Response;
use App\Model\Team;
use App\Model\TournamentParent;
use App\Model\Organization;
use App\Model\Facilityprofile;

class PhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
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
    public function update(Request $request, $id)
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
		
		   $loginuserid=Auth::user()->id;
		   $url="";
		   $profileurl= photo::where('id',  $id )->select('url')->get()->toArray();
		   $profilephotos=Photo::select()->where('id', $id)->get()->toArray();
		  
	 
			 if($profilephotos[0]['imageable_type']==config('constants.PHOTO.USER_PHOTO')) 
			{
				
					 if(count( $profileurl)!=0)
					 {
						 if( $profileurl[0]['url']==session('profilepic'))
						 {
							if(Auth::user()->role=='general')
							{
							session(['profilepic' =>  $url]);
							}
							Photo::find($id)->delete();
							  User::where('id',$profilephotos[0]['imageable_id'])->update(['logo' =>null]);
							return Response::json(array('msg'=>'success','id'=>$id));
						 }
						  else
						  {
							 Photo::find($id)->delete();
							   User::where('id',$profilephotos[0]['imageable_id'])->update(['logo' =>null]);
							 return Response::json(array('msg'=>'success','id'=>$id));
						 }
					 }
						}
			
			else if($profilephotos[0]['imageable_type']==config('constants.PHOTO.TEAM_PHOTO'))
			{
				 Photo::find($id)->delete();
				 Team::where('id',$profilephotos[0]['imageable_id'])->update(['logo' =>null]);
			}
			else if($profilephotos[0]['imageable_type']==config('constants.PHOTO.TOURNAMENT_LOGO'))
			{
				Photo::find($id)->delete();
				TournamentParent::where('id',$profilephotos[0]['imageable_id'])->update(['logo' =>null]);
			}
			else if($profilephotos[0]['imageable_type']==config('constants.PHOTO.ORGANIZATION_LOGO'))
			{
				Photo::find($id)->delete();
				Organization::where('id',$profilephotos[0]['imageable_id'])->update(['logo' =>null]);
			}
			else if($profilephotos[0]['imageable_type']==config('constants.PHOTO.FACILITY_LOGO'))
			{
				Photo::find($id)->delete();
				Facilityprofile::where('id',$profilephotos[0]['imageable_id'])->update(['logo' =>null]);
			}
			else{
				
					//Check authentication before delete the photo
					Photo::find($id)->delete();
					//dd(Photo);
			}
		

    }
}
