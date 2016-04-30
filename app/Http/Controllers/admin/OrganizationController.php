<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
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

class OrganizationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $filter = \DataFilter::source(Organization::where('isactive', 1));
        $filter->add('name', 'Name', 'text');
        $filter->add('organization_type', 'organization_type(0: ,academy:academy,college:college,school:school,other:other)', 'select')->options(['Organization Type', 'academy', 'college', 'school', 'other'])
                ->scope(function ($query, $value) {
                    if ($value == 0)
                        return $query->whereIn('organization_type', ["academy", "college", "school", "other", $value]);
                    elseif ($value == 1)
                        return $query->whereIn('organization_type', ["academy"]);
                    elseif ($value == 2)
                        return $query->whereIn('organization_type', ["college"]);
                    elseif ($value == 3)
                        return $query->whereIn('organization_type', ["school"]);
                    elseif ($value == 4)
                        return $query->whereIn('organization_type', ["other"]);
                });
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
        $grid = \DataGrid::source($filter);
        $grid->attributes(array("class" => "table table-striped"));
        $grid->add('id', 'ID', true)->style("width:100px");
        $grid->add('user_id', 'USER ID', true);
        $grid->add('name', 'NAME', true);
        $grid->add('organization_type', 'Organization Type', true);
        $grid->edit('edit', 'Operation', 'modify|delete');
        $grid->orderBy('id', 'desc');
        $grid->link('admin/organization/create', "New Organization", "TR");
        $grid->paginate(config('constants.DEFAULT_PAGINATION'));
        return view('organization.filtergrid', compact('filter', 'grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $type = array('academy' => 'academy', 'college' => 'college', 'school' => 'school', 'other' => 'other');
        $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
		$cities=[];
        $teams = Team::where('team_owner_id', Auth::user()->id)->orderBy('name')->lists('name', 'id')->all();
        return view('organization.create', array('states' => ['' => 'Select State'] + $states,'cities' =>  ['' => 'Select City'] + $cities, 'type' =>['' => 'Select Organization Type'] + $type, 'id' => '', 'roletype' => 'admin', 'teams' => ['' => 'Select Team'] + $teams, 'selectedTeams' => ''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateOrganizatonRequest $request) {
        $request['country_id'] = config('constants.COUNTRY_INDIA');
        $request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
        $request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
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
        return redirect()->route('admin.organization.index')->with('status', trans('message.organization.create'));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CreateOrganizatonRequest $request, $id) {
        $request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
        $request['country_id'] = config('constants.COUNTRY_INDIA');
        $request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
        Organization::whereId($id)->update($request->except(['_method', '_token', 'files', 'filelist_photos', 'team']));
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
        return redirect()->route('admin.organization.index')->with('status', trans('message.organization.update'));
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

    public function edit(Request $request) {

        $delete_organization_id = $request->delete;
        $edit_organization_id = $request->modify;
        if ($delete_organization_id != '' && $delete_organization_id > 0) {
            $organization = Organization::find($delete_organization_id)->delete();
            return redirect()->route('admin.organization.index')->with('status', trans('message.organization.delete'));
        } else if ($edit_organization_id != '' && $edit_organization_id > 0) {
            $organization = Organization::findOrFail($edit_organization_id);
            $type = array('academy' => 'academy', 'college' => 'college', 'school' => 'school', 'other' => 'other');
            $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
            $cities = City::where('state_id', $organization->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
            $teams = Team::where('team_owner_id', Auth::user()->id)->orderBy('name')->lists('name', 'id')->all();
            $selectedTeams = Team::where('team_owner_id', Auth::user()->id)->where('organization_id', $edit_organization_id)->get(['id']);
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
            return view('organization.edit', compact('organization'))->with(array('id' => $edit_organization_id, 'states' => $states, 'cities' => $cities, 'type' =>['' => 'Select Organization Type'] + $type , 'roletype' => 'admin', 'organization' => $organization, 'teams' =>['' => 'Select Teams'] + $teams, 'selectedTeams' => $selectedTeams));
        }
    }

}
