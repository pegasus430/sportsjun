<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\MarketPlaceCategories;
use App\Model\MarketPlace;
use DB;
use Auth;
use Input;
use Validator;
use Session;
use Redirect;
use App\Helpers\Helper;
use Zofe\Rapyd\RapydServiceProvider;
use Response;
use App\Helpers\AllRequests;

class MarketplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$categorios_array = MarketPlaceCategories::select('name','id')->get();
		$cate_array = array('0'=>'Select');
		$cate_arrayy = array();
		foreach($categorios_array as $cat)
		{
			$cate_array[$cat->id] = $cat->name;
		}
		
		$filter = \DataFilter::source(MarketPlace::with('categories'));
		$filter->add('item','Item Name', 'text');
		$filter->add('marketplace_category_id','role(0: ,admin:admin,general:general)', 'select')->options($cate_array)->scope( 
		function ($queryy, $valuey){
			if($valuey == 0)
			{
				$categorios_array = MarketPlaceCategories::select('name','id')->get();
				foreach($categorios_array as $cat)
				{
					$cate_arrayy[] = $cat->id;
				}
				 return $queryy->whereIn('marketplace_category_id', $cate_arrayy);
			}else if($valuey > 0)
				{
					return $queryy->whereIn('marketplace_category_id', [$valuey]);
				}
		});
		
		$filter->add('approved','role(0: ,yes:Approved,no:Rejected)', 'select')->options(['Select Status','Approved','Not Approved'])
         ->scope( function ($query, $value) {
				 if($value == 0)
				 {
					 return $query->whereIn('approved', ["yes","no"]);
				 }
				else if($value == 1)
				{
					return $query->whereIn('approved', ["yes"]);
				}else if($value == 2)
				{
					return $query->whereIn('approved', ["no"]);
				}
				
			});	
		$filter->submit('search');
        $filter->reset('reset');
        $filter->build();
			
		$grid = \DataGrid::source($filter);
        
		
        // $grid->add('<a href="#" data-pid={{ $id }}  data-page="myitems" data-toggle="tooltip" data-placement="top" title="View Detail" class="view_gallery icon-info mp_info">{{$item}}</a>','Name',true);
		
		 
		$grid->add('item','ITEAM', true);
        $grid->add('{{ implode(", ", $categories->lists("name")->all()) }}','Categories');
		
		$grid->add('view','Approve & Reject')->cell( function( $value, $row) {
			if($row->item_status=='available')
			{
				if($row->approved=='yes')
				{
					$status = 1;
					return "<a href='javascript:void(0);' onclick='changestatus(".$status.",".$row->id.");' >Reject</a>";
				}
					
				else
				{
					$status = 2;
					return "<a href='javascript:void(0);' onclick='changestatus(".$status.",".$row->id.");'>Approve</a>";
				}
					
			}else{
				return '';
			}
			
		}); 
		  $grid->add('<a href="#" data-pid={{ $id }}  data-page="myitems" data-toggle="tooltip" data-placement="top" title="View Detail" class="view_gallery icon-info mp_info">View</a>','View Details');
	
           // $grid->add('View Details','View Details');
		     // $grid->add('<a href="#" data-pid="{{ $id }}"  data-page="{{ $marketplace }}" data-toggle="tooltip" data-placement="top" title="View Detail" class="view_gallery icon-info mp_info"><i class="fa fa-info"></i></a>','item',true);
		   	// $grid->add('<a href="/adminteam/members/{{ $id }}">{{ $name }}</a>','NAME');
			
		
        $grid->orderBy('id','desc');
        $grid->paginate(config('constants.DEFAULT_PAGINATION'));
		$feild_name = 'MarketPlace';
		return view('admin.marketplace.filtergrid',array('grid' => $grid,'feild_name' => $feild_name,'filter'=>$filter));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //http://www.elcoderino.com/use-concat-when-populating-dropdown-list-from-database-with-laravel/
        $marketPlaceCategories = MarketPlaceCategories::where('isactive','=',1)->lists('name', 'id');
        $type = DB::select( DB::raw("SHOW COLUMNS FROM marketplace WHERE Field = 'item_type'") )[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach( explode(',', $matches[1]) as $value )
        {
        $v = trim( $value, "'" );
        $enum = array_add($enum, $v, $v);
        }
  //return $enum;

        return view('admin.marketplace.create')->with(array('marketPlaceCategories'=> ['' => 'Select Category'] + $marketPlaceCategories,'enum'=> ['' => 'Select Filter'] +$enum));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateMarketPlaceRequest $request)
    {
        //Log::error($request->filelist);
        $request['user_id'] = Auth::user()->id;
        $request['item_status'] = 'available';
        $marketplace = Marketplace::create($request->all());
        $id = $marketplace->id; //Inserted record ID
        //Upload Photos
        $albumID = 1;//Default album if no album is not selected.
        $coverPic = '';
        if(isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if(isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic'];        
        Helper::uploadPhotos($request['filelist'],'marketplace',$id,$albumID,$coverPic,config('constants.PHOTO.MARKETPLACE_PHOTO'));
        //End Upload Photos        
        redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //http://stackoverflow.com/questions/21474189/laravel-fill-form-with-database-saved-fields
        $marketPlaceCategories = MarketPlaceCategories::where('isactive','=',1)->lists('name', 'id');
        $type = DB::select( DB::raw("SHOW COLUMNS FROM marketplace WHERE Field = 'item_type'") )[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach( explode(',', $matches[1]) as $value )
        {
        $v = trim( $value, "'" );
        $enum = array_add($enum, $v, $v);
        }        
        $marketplace = MarketPlace::findOrFail($id);
        //$a = $marketplace->photos;
        //DB::enableQueryLog();
        //dd(DB::getQueryLog());
         
        return view('admin.marketplace.edit',compact('marketplace'))->with(array('marketPlaceCategories'=> ['' => 'Select Category'] + $marketPlaceCategories,'enum'=> ['' => 'Select Filter'] +$enum,'id'=>$id));
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
		
		 $id = $request['id'];
		 $status = $request['status'];//if 1->reject 2->approve
		 $marketplace = MarketPlace::findOrFail($id);
	     $user_id= $marketplace ->user_id;
		 $itemname=$marketplace ->item;
		
		 if($status==1)
		 {
			   MarketPlace::where(['id' => $id])->update(['approved' => 'no']);
			   // $message=  trans('message.marketplace.fields.rejectimage') ;
			    $message=  'Your Store item'.' '.$itemname.' '.'has been rejected.';
			   $url= url('/marketplace/myitems') ;
		       AllRequests::sendnotifications($user_id,$message,$url);
			   return Response()->json( array('success' => 'Rejected.') );
		 }else
		 {
			 MarketPlace::where(['id' => $id])->update(['approved' => 'yes']); 	
              // $message=  trans('message.marketplace.fields.approveimage') ;			 
			 $message=  'Your Store item'.' '.$itemname.' '.'has been approved for sale - Happy Selling :)' ;
		     $url= url('/marketplace/myitems') ;
			 AllRequests::sendnotifications($user_id,$message,$url);
			 return Response()->json( array('success' => 'Approved.') );
		 }
		
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

    public function saveImagable()
    {
        $imageable = new Image();
        $imageable->path = $this->destination_path . '/' . $this->filename;
        $imageable->imageable_id = $this->model_id;
        $imageable->imageable_type = $this->model_class_path;
        $imageable->save();
    }

}
