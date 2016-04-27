<?php

namespace App\Http\Controllers\User;

//use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\MarketPlaceCategories;
use Zofe\Rapyd\RapydServiceProvider;
use App\Model\MarketPlace;
use App\User;
use DB;
use Auth;
use Input;
use Request;
use Validator;
use Session;
use Redirect;
use App\Helpers\Helper;

class MarketplaceCategoriesController extends Controller
{
	public function index()
	{
		 return view('marketplace.createcategory');
	}
   public function viewMarcketPlaceCategories()
   {
	   	$filter = \DataFilter::source(MarketPlaceCategories::where('isactive',1));
		$filter->add('name','Category', 'text');
		$filter->submit('search');
        $filter->reset('reset');
        $filter->build();
		
		$grid = \DataGrid::source($filter);
        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','Category',true);
		$grid->link('/createcategory',"Add New", "TR"); 
	   $grid->edit('/deletecategory', 'Operation','modify|delete');
        $grid->orderBy('id','desc');
        $grid->paginate(config('constants.DEFAULT_PAGINATION'));
		$feild_name = 'Categories';
		//$filter='';
		return view('sports.viewsports',array('grid' => $grid,'feild_name' => $feild_name,'filter'=>$filter));
   }
   public function deletecategory()
   {
		$delete_category_id = Request::get('delete');
		$edit_category_id = Request::get('modify');
		$category_array = array();
		if(isset($delete_category_id) && $delete_category_id>0)
		{
			MarketPlaceCategories::find($delete_category_id)->delete();
			return redirect()->route('viewcategories')->with('status', trans('message.marketplacecategory.delete'));
			//MarketPlaceCategories::where('id', '=', $delete_category_id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		}else if(isset($edit_category_id) && $edit_category_id>0)
		{
			$category = new MarketPlaceCategories();
			$category_array = $category->select()->where('id', $edit_category_id)->get();
		}
		return view('marketplace.createcategory',array('category_array' => $category_array));
   }
   public function insertcategory()
   {
	   		$request = Request::all();
			$rules = ['category' => 'required'];
			$v = Validator::make($request, $rules);
			if ($v->fails()) {
				 return redirect()->back()->withErrors(["category" => [0 => trans('message.marketplacecategory.category')]]);
			}
			$user = User::find(Auth::user()->id);
			$created_by = $user->id;
			
			$category = Request::get('category');
			$categories = new MarketPlaceCategories();
			$categories->name = $category;
			$categories->created_by = $created_by;
			$categories->save();
			return redirect()->route('viewcategories')->with('status', trans('message.marketplacecategory.create'));
   }
   public function updatecategory()
   {
	   $request = Request::all();
		$category_id = Request::get('category_id');
		$category = Request::get('category');
		if($category_id!='' && $category!='')
		{
			MarketPlaceCategories::where('id', '=', $category_id)->update(['name' => $category]);
		}
		return redirect()->route('viewcategories')->with('status', trans('message.marketplacecategory.update'));
   }
}
