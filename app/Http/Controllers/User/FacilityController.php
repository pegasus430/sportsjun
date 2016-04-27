<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\organization;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Facilityprofile;
use Auth;
use App\Model\Photo;
use App\Helpers\Helper;
use DB;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        	$filter = \DataFilter::source(Facilityprofile::where('isactive',1));
        $filter->add('name','Name','text');
		$filter->add('facility_type','facility_type(0: ,1:INDOOR,2:OUTDOOR,3:BOTH)', 'select')->options(['Facility Type','INDOOR','OUTDOOR','BOTH'])
         ->scope( function ($query, $value) {
			  if($value == 0)
				return $query->whereIn('facility_type', ["1","2","3",$value]);
			else
				return $query->whereIn('facility_type', [$value]);
			});		
		$filter->add('facility_service','facility_service((0: ,1:ACADEMY,2:COACH,3:BOTH)', 'select')->options(['Facility Service','ACADEMY','COACH','BOTH'])
          ->scope( function ($query, $value) {
			  if($value == 0)
				return $query->whereIn('facility_service', ["1","2","3",$value]);
			else
				return $query->whereIn('facility_service', [$value]);
			});
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
		$grid = \DataGrid::source($filter);
		$grid->attributes(array("class"=>"table table-striped"));
        $grid->add('id','ID', true)->style("width:100px");
		$grid->add('user_id','USER ID',true);
		$grid->add('name','NAME',true);
	    $grid->add('facility_type','FACILITY TYPE',true);		
		$grid->add('facility_service','FACILITY SERVICE',true);
		$grid->add('view','View Gallery')->cell( function( $value, $row) {
			return "<a href='".url()."/user/album/show".'/facility'.'/0'.'/'.$row->id."'>Gallery</a>";
		}); 
        //$grid->edit('user/editFacility', 'Operation','modify|delete');
        $grid->orderBy('id','desc');		
       // $grid->link('user/facility/create',"New Facility", "TR");
		$grid->paginate(config('constants.DEFAULT_PAGINATION'));
        $grid->row(function ($row) {
           if ($row->cell('facility_type')->value == 1) {
               $row->cell('facility_type')->value = 'INDOOR';
           } elseif ($row->cell('facility_type')->value == 2) {
                 $row->cell('facility_type')->value = 'OUTDOOR';
              
           }
		   elseif ($row->cell('facility_type')->value == 3) {
                 $row->cell('facility_type')->value = 'BOTH';
              
           }
		   
        });
		 $grid->row(function ($row) {
           if ($row->cell('facility_service')->value == 1) {
               $row->cell('facility_service')->value = 'ACADEMY';
           } elseif ($row->cell('facility_service')->value == 2) {
                 $row->cell('facility_service')->value = 'COACH';
              
           }
		   elseif ($row->cell('facility_service')->value == 3) {
                 $row->cell('facility_service')->value = 'BOTH';
              
           }
		   
        });
        return  view('facility.filtergrid', compact('filter', 'grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
	     $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
		 $cities = [];
		 Helper::setMenuToSelect(8,1);
         return view('facility.create',array('states' => ['' => 'Select State'] + $states,'cities' => ['' => 'Select City'] + $cities,'roletype'=>'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateFacilityRequest $request)
    {
		$type_count = count($request['facility_type']);
		$facilityType = 0;
		if($type_count==3)
		{
			$facilityType = 3;
		}else if($type_count==1 && in_array('1',$request['facility_type']))
		{
			$facilityType = 1;
		}else if($type_count==1 && in_array('2',$request['facility_type']))
		{
			$facilityType = 2;
		}
		$facility_count = count($request['facility_service']);
		$facilityService = 0;
		if($facility_count==3)
		{
			$facilityService = 3;
		}else if($facility_count==1 && in_array('1',$request['facility_service']))
		{
			$facilityService = 1;
		}else if($facility_count==1 && in_array('2',$request['facility_service']))
		{
			$facilityService = 2;
		}
		$request['user_id']=Auth::user()->id;
		$request['country_id'] = config('constants.COUNTRY_INDIA');
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
		$request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
        $request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
		$request['facility_type'] =  $facilityType;
		$request['facility_service'] = $facilityService ;
        $request['created_by'] = Auth::user()->id;
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
        $facility=Facilityprofile::create($request->all());
		 $id = $facility->id; //Inserted record ID
	     $user_id= Auth::user()->id;
        //Upload Photos
        $albumID = 1;//Default album if no album is not selected.
        $coverPic = 1;
        if(isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if(isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic'];        
      
	     Helper::uploadPhotos($request['filelist_photos'],config('constants.PHOTO_PATH.FACILITY'),$id,$albumID,$coverPic,config('constants.PHOTO.FACILITY_LOGO'),$user_id);
		 Helper::uploadPhotos($request['filelist_gallery'],config('constants.PHOTO_PATH.FACILITY_PROFILE'),$id,$albumID,$coverPic,config('constants.PHOTO.FACILITY_PROFILE'),$user_id);
		
		$logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.FACILITY_LOGO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->where('is_album_cover',1)->get()->toArray();
		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				  Facilityprofile::where('id', $id)->update(['logo' => $l['url']]);
				//echo $l['url'];exit;
			}
			
		}
		
        //End Upload Photos        
       // redirect('/');
        return redirect()->route('facility.index')->with('status', trans('message.facility.facilitycreated'));
       
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
	  $request['user_id'] = Auth::user()->id;
	  $facility = Facilityprofile::findOrFail($id);
	  $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
	  $cities = City::where('state_id',  $facility->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
	  return view('facility.edit',compact('facility'))->with(array('id'=>$id,'states' => ['' => 'Select State'] + $states,'cities' => ['' => 'Select City'] + $cities,'roletype'=>'user','facility'=>$facility));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CreateFacilityRequest $request, $id)
    {		$type_count = count($request['facility_type']);
		$facilityType = 0;
		if($type_count==2)
		{
			$facilityType = 3;
		}else if($type_count==1 && in_array('1',$request['facility_type']))
		{
			$facilityType = 1;
		}else if($type_count==1 && in_array('2',$request['facility_type']))
		{
			$facilityType = 2;
		}
		$facility_count = count($request['facility_service']);
		$facilityService = 0;
		if(	$facility_count==2)
		{
			$facilityService = 3;
		}else if($facility_count==1 && in_array('1',$request['facility_service']))
		{
			$facilityService = 1;
		}else if($facility_count==1 && in_array('2',$request['facility_service']))
		{
			$facilityService = 2;
		}
		$request['country_id'] = config('constants.COUNTRY_INDIA');
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
		$request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
        $request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
		$request['facility_type'] = $facilityType ;
		$request['facility_service'] =  $facilityService ;
		Facilityprofile::whereId($id)->update($request->except(['_method','_token','files','filelist_photos','filelist_gallery','jfiler-items-exclude-files-0']));
		if (!empty($request['filelist_photos'])) {
		Photo::where(['imageable_id'=>$id, 'imageable_type' => config('constants.PHOTO.FACILITY_LOGO')])->update(['is_album_cover' => 0]);
		 //Upload Photos
        $albumID = 1;//Default album if no album is not selected.
        $coverPic = 1;
	    $user_id= Auth::user()->id;
        if(isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if(isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic'];      		
     
		
		  Helper::uploadPhotos($request['filelist_photos'],config('constants.PHOTO_PATH.FACILITY'),$id,$albumID,$coverPic,config('constants.PHOTO.FACILITY_LOGO'),$user_id);
		}
		 if (!empty($request['filelist_gallery'])) {
		//Photo::where(['user_id' => Auth::user()->id, 'imageable_type' => config('constants.PHOTO.FACILITY_PROFILE')])->update(['is_album_cover' => 0]);
		 //Upload Photos
        $albumID = 1;//Default album if no album is not selected.
        $coverPic = 1;
	    $user_id= Auth::user()->id;
        if(isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if(isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic']; 
      	Helper::uploadPhotos($request['filelist_gallery'],config('constants.PHOTO_PATH.FACILITY_PROFILE'),$id,$albumID,$coverPic,config('constants.PHOTO.FACILITY_PROFILE'),$user_id);
        //End Upload Photos
		}
				
			$logo=Photo::select('url')->where('is_album_cover',1)->where('imageable_type',config('constants.PHOTO.FACILITY_LOGO'))->where('imageable_id',  $id )->where('user_id', Auth::user()->id)->get()->toArray();
		if(!empty($logo))
		{
			foreach($logo as $l)
			{
				  Facilityprofile::where('id', $id)->update(['logo' => $l['url']]);
				//echo $l['url'];exit;
			}
			
		}
		return redirect()->route('facility.index')->with('status',  trans('message.facility.facilityupdate'));
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
