<?php

namespace App\Http\Controllers\admin\facility;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Facilityprofile;
use App\User;
use Zofe\Rapyd\RapydServiceProvider;
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
        $grid->edit('delete', 'Operation','modify|delete');
        $grid->orderBy('id','desc');		
        $grid->link('admin/facility/create',"New Facility", "TR");
		$grid->paginate(5);
		
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
        return  view('admin.facility.filtergrid', compact('filter', 'grid'));
			
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.facility.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateFacilityRequest $request)
    {
		$request['facility_type'] =  $request->facilitytype;
		$request['facility_service'] =  $request->facilityservice;
        $request['created_by'] = Auth::user()->id;
        Facilityprofile::create($request->all());
        return redirect()->back()->with('status', 'Facility Inserted succesfully');
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
	  $facility = Facilityprofile::findOrFail($id);
      return view('admin.facility.edit',compact('facility'))->with(array('id'=>$id));
	
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
        Facilityprofile::whereId($id)->update($request->except(['_method','_token']));
		return redirect()->back()->with('status', 'Sports Updated succesfully');
	}
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		
    }
	 public function delete(Request $request)
    {
		//print_r($request->all()) ;
		//print_r(	$request);exit;
		//$delete_sport_id = Request::get('delete');
		//$edit_sport_id = Request::get('modify');
        $delete_sport_id = $request->delete;
		$edit_sport_id = $request->modify;
		if($delete_sport_id!='' && $delete_sport_id>0)
		{
			DB::table('facility_profile')
            ->where('id', $delete_sport_id)
            ->update(array('isactive'=>'0'));
			return redirect()->back()->with('status', 'Facility Deleted succesfully');
		}else if($edit_sport_id!='' && $edit_sport_id>0)
		{
			 $this->edit($edit_sport_id);
		}
    }
}
