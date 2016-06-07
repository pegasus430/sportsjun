<?php

namespace App\Http\Controllers\user;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\organization;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Team;
use App\Helpers\Helper;
use Auth;
use App\Model\Photo;

//use Helper;

class OrganizationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $type = config('constants.ENUM.ORGANIZATION.ORGANIZATION_TYPE');  
        $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
        $teams = Team::where('team_owner_id', Auth::user()->id)->orderBy('name')->lists('name', 'id')->all();
        $states = [];
		$cities=[];
        return view('organization.create', array('countries' =>  [''=>'Select Country']+$countries, 'states' => ['' => 'Select State'] + $states, 'cities' => ['' => 'Select City'] + $cities, 'type' => ['' => 'Select Organization Type'] +$type, 'id' => '', 'roletype' => 'user', 'teams' =>['' => 'Select Team'] + $teams, 'selectedTeams' => ''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateOrganizatonRequest $request) {
		 	
        $request['user_id'] = Auth::user()->id;
        $request['country'] = !empty($request['country_id']) ? Country::where('id', $request['country_id'])->first()->country_name : 'null';
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
        $request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
        $organization = Organization::create($request->all());
        if (count($request->team)) {
            Team::where('team_owner_id', Auth::user()->id)->whereIn('id', $request->team)->update(['organization_id' => $organization->id]);
        }
        $id = $organization->id; //Inserted record ID
        $user_id = Auth::user()->id;
        //Upload Photos
        $albumID = 1; //Default album if no album is not selected.
        $coverPic = 1;
        if (isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if (isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic'];
        Helper::uploadPhotos($request['filelist_photos'], config('constants.PHOTO_PATH.ORGANIZATION'), $id, $albumID, $coverPic, config('constants.PHOTO.ORGANIZATION_LOGO'), $user_id);
	    Helper::uploadPhotos($request['filelist_gallery'], config('constants.PHOTO_PATH.ORGANIZATION_PROFILE'), $id, $albumID, $coverPic, config('constants.PHOTO.ORGANIZATION_PROFILE'), $user_id);
		$logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.ORGANIZATION_LOGO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->where('is_album_cover',1)->get()->toArray();
		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				  Organization::where('id', $id)->update(['logo' => $l['url']]);
				//echo $l['url'];exit;
			}
			
		}
        //End Upload Photos        
        // redirect('/');
        return redirect()->back()->with('status', trans('message.organization.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $request['user_id'] = Auth::user()->id;
        $organization = Organization::findOrFail($id);
        $type = config('constants.ENUM.ORGANIZATION.ORGANIZATION_TYPE');  
        $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
        $states = State::where('country_id', $organization->country_id)->orderBy('state_name')->lists('state_name', 'id')->all();
        $cities = City::where('state_id', $organization->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
        $teams = Team::where('team_owner_id', Auth::user()->id)->orderBy('name')->lists('name', 'id')->all();
        $selectedTeams = Team::where('team_owner_id', Auth::user()->id)->where('organization_id', $id)->get(['id']);
        $selectedTeamsIds = '';
        if (count($selectedTeams)) {
            $selectedTeamsIds = array_divide(array_flatten($selectedTeams->toArray()));
        }
		if(  $selectedTeamsIds=='')
		{
			$selectedTeams = '';
		}
		else
		{
			$selectedTeams = $selectedTeamsIds[1];
		}

        return view('organization.edit', compact('organization'))->with(array('id' => $id,'countries' => ['' => 'Select Country'] + $countries,'states' => ['' => 'Select State'] + $states, 'cities' => ['' => 'Select City'] + $cities, 'type' => ['' => 'Select Organization Type'] + $type, 'roletype' => 'user', 'organization' => $organization, 'teams' =>['' => 'Select Teams'] + $teams, 'selectedTeams' =>  $selectedTeams));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CreateOrganizatonRequest $request, $id) {
        $request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
        $request['country'] = !empty($request['country_id']) ? Country::where('id', $request['country_id'])->first()->country_name : 'null';
        $location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
        Organization::whereId($id)->update($request->except(['_method', '_token', 'files', 'filelist_photos', 'team','filelist_gallery','jfiler-items-exclude-files-0']));
        if (count($request->team)) {
            Team::where('team_owner_id', Auth::user()->id)->where('organization_id', $id)->update(['organization_id' => NULL]);
            Team::where('team_owner_id', Auth::user()->id)->whereIn('id', $request->team)->update(['organization_id' => $id]);
        }
        if (!empty($request['filelist_photos'])) {
            Photo::where(['imageable_id'=>$id, 'imageable_type' => config('constants.PHOTO.ORGANIZATION_LOGO')])->update(['is_album_cover' => 0]);
            //Upload Photos
            $albumID = 1; //Default album if no album is not selected.
            $coverPic = 1;
            $user_id = Auth::user()->id;
            if (isset($input['album_id']) && $input['album_id'])
                $albumID = $input['album_id'];
            if (isset($input['cover_pic']) && $input['cover_pic'])
                $coverPic = $input['cover_pic'];
            Helper::uploadPhotos($request['filelist_photos'], config('constants.PHOTO_PATH.ORGANIZATION'), $id, $albumID, $coverPic, config('constants.PHOTO.ORGANIZATION_LOGO'), $user_id);
			
            //End Upload Photos
        }
		 if (!empty($request['filelist_gallery'])) {
        //  Photo::where(['user_id' => Auth::user()->id, 'imageable_type' => config('constants.PHOTO.ORGANIZATION_PROFILE')])->update(['is_album_cover' => 0]);
            //Upload Photos
            $albumID = 1; //Default album if no album is not selected.
            $coverPic = 1;
            $user_id = Auth::user()->id;
            if (isset($input['album_id']) && $input['album_id'])
                $albumID = $input['album_id'];
            if (isset($input['cover_pic']) && $input['cover_pic'])
                $coverPic = $input['cover_pic'];
			 Helper::uploadPhotos($request['filelist_gallery'], config('constants.PHOTO_PATH.ORGANIZATION_PROFILE'), $id, $albumID, $coverPic, config('constants.PHOTO.ORGANIZATION_PROFILE'), $user_id);
            //End Upload Photos
        }
		$logo=Photo::select('url')->where('is_album_cover',1)->where('imageable_type',config('constants.PHOTO.ORGANIZATION_LOGO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->get()->toArray();
		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				  Organization::where('id', $id)->update(['logo' => $l['url']]);
			}
			
		}
        return redirect()->back()->with('status', trans('message.organization.update'))->with('div_sel_org','active');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
		public function deleteorganization($org_id,$flag)
	 {
		$request = Request::all();
		$delete_id =$org_id;
		if(is_numeric($delete_id) && $delete_id > 0)
		{
			if($flag == 'd')
			{
				if(Organization::where('id',$delete_id)->update(['isactive'=>0]))
				{
					return redirect()->back()->with('status',  trans('message.organization.delete'))->with('div_sel_org','active');
				}
				else
				{
					return redirect()->back()->with('error_msg', trans('message.organization.deletefail'))->with('div_sel_org','active');
				}
			}
			elseif($flag == 'a')
			{
				if(Organization::where('id',$delete_id)->update(['isactive'=>1]))
				{
					return redirect()->back()->with('status',  trans('message.organization.activate'))->with('div_sel_org','active');
				}
				else
				{
					return redirect()->back()->with('error_msg', trans('message.organization.activatefail'))->with('div_sel_org','active');
				}
			}
			else
			{
				return redirect()->back()->with('error_msg', trans('message.organization.updatefail'))->with('div_sel_org','active');
			}
		}
		else
		{
			return redirect()->back()->with('error_msg', trans('message.organization.updatefail'))->with('div_sel_org','active');
		}
	}

}
