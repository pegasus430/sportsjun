<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
//use Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\MarketPlaceCategories;
use App\Model\MarketPlace;
use App\Model\MarketPlaceLogs;
use DB;
use Auth;
use Input;
use Validator;
use Session;
use Redirect;
use App\Helpers\Helper;
use View;
use Response;
use App\Model\State;
use App\Model\City;
use App\Model\Country;
use App\User;
use App\Model\Photo;
use App\Model\Otp;
use App\Model\Organization;

use Illuminate\Http\Request as ObjRequest;
use App\Model\BasicSettings;

class MarketplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
	 $max = MarketPlace::select()->max('base_price');			
	 $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
	 $cities=[];
     $marketPlaceCategories = MarketPlaceCategories::where('isactive','=',1)->get();
     Helper::setMenuToSelect(7,0,$marketPlaceCategories);	
	// Session::flash('message', 'My message');
	 return view('marketplace.list')->with(array('states' => ['' => 'Select State'] + $states,'cities' => ['' => 'Select City'] + $cities,'max'=>$max,'page'=>'marketplace','search_by'=>$request['search_by']));	
    }

    public function organization_marketplace($id, request $request){
             $id = $request->route()->parameter('id');
          $this->is_owner = false;
          $this->new_template = false;
          $this->view ='organization';        

          $allow_newtemplate_setting  = BasicSettings::where('name', 'organization_new_template')->first();


          if($allow_newtemplate_setting && $allow_newtemplate_setting->description=='1'){
             $this->new_template=true;
          }

        if($id && (Auth::user()->type==1 && count(Auth::user()->organizations))){

            if(Auth::user()->organizations[0]->id == $id && $this->new_template){
                 $this->is_owner = true;
                 $this->view = 'organization_2';
                 $organization = Organization::find($id);
                 view()->share('organisation', $organization);
            }
            
        }    


        if($this->is_owner){
            $max = MarketPlace::select()->max('base_price');           
            $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
            $cities=[];
            $marketPlaceCategories = MarketPlaceCategories::where('isactive','=',1)->get();
            $marketplace = Marketplace::all();            
            $categories = Helper::setMenuToSelect(7,0,$marketPlaceCategories);   
            $categories = $marketPlaceCategories;
                return view($this->view.'.marketplace', compact('categories','states','cities','max','marketplace','search_by'));
        }


    }
   
   //view more based on search items
    public function marketplaceSearch(Request $request)
	 {
	
	  //dd($request);
	   $limit = $request['limit'];
       $offset = $request['offset'];
	   $amount=explode("-",$request['amount']);
       $page =$request['query'];
       $loginuserid=Auth::user()->id;
	   
	    if($request['query']=='marketplace')
		{
		
       $query = MarketPlace::with('photos')->where('approved','yes')
	                       
                           ->where('user_id','<>',Auth::user()->id)->where('item_status','available')->orderBy('id','desc');	
						   
						 
		}
		  if($request['query']=='myitems')
		{
       $query = MarketPlace::with('photos')
	                       
                           ->where('user_id',Auth::user()->id)->orderBy('id','desc');	
						  
		}	
		  if($request['searchType']=='all')
		{
       $query = MarketPlace::with('photos')->where('approved','yes')
	                       
                           ->where('user_id','<>',Auth::user()->id)->where('item_status','available')->limit($limit)->offset($offset);	
		}
		  if($request['searchType']=='myitems')
		{
       $query = MarketPlace::with('photos')
	                       
                           ->where('user_id',Auth::user()->id)->limit($limit)->offset($offset);	
		}	
	   if($request['categorytype']!=''){
		$query->whereIn('marketplace_category_id',$request['categorytype']);
	   }
	   if($request['itemtype']!=''){
		$query->whereIn('item_type',$request['itemtype']);
	   }
	     if($request['itemstatus']!=''){
		$query->whereIn('item_status',$request['itemstatus']);
	   }
	   if($request['amount']!=''){		  
	   $query->whereBetween('base_price',$amount);
	  }
	   if($request['state']!=''){		  
	   $query ->where('state_id',$request['state']);
                         
	  }
	   if($request['city']!=''){		  
	    $query ->where('city_id',$request['city']);
                         
	  }
	    if($request['name']!=''){		
				
	    $query ->where('item',$request['name']);
                         
	  }
	   $list_coun = $query->get();
       $list_count=count(   $list_coun );	
	   $list = $query->limit($limit)->offset($offset)->get();
	   
 //Helper::printQueries();exit;
      	
	   $returnHTML =  View::make('marketplace.show')->with(array('list'=>$list,'offset'=> $offset,'marketplace' =>$page,'loginuserid'=>$loginuserid))->render();	 
       return \Response::json(array('html' =>$returnHTML,'offset'=> $offset+$limit,'limit'=> $limit ,'list_count' => $list_count));
	  
	 }
	 
	 
	 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
	    $marketPlaceCategories = MarketPlaceCategories::where('isactive','=',1)->lists('name', 'id')->all();
		$userDetails = User::where('id', Auth::user()->id)->first();
		if(!empty($userDetails))
		{
            $countries = Country::orderBy('country_name')
                                ->lists('country_name', 'id')
                                ->all();
            $states = [];
            if ($userDetails->country_id) {
                $states = State::where('country_id', $userDetails->country_id)
                               ->orderBy('state_name')
                               ->lists('state_name', 'id')
                               ->all();
            }
            $cities = [];
            if ($userDetails->state_id) {
                $cities = City::where('state_id', $userDetails->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
            }
		}
		else
		{
            $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
            $states = [];
            $cities =[];
		}
		$marketplace =new MarketPlace();
        $enum = config('constants.ENUM.MARKETPLACE.ITEMTYPE');
        Helper::setMenuToSelect(7,0);
        return view('marketplace.create')->with(array( 'marketPlaceCategories' => ['' => 'Select Category'] + $marketPlaceCategories,'enum'=> ['' => 'Select Item Type'] +$enum,'countries' =>  ['' => 'Select Country'] +$countries,'states' =>  ['' => 'Select State'] +$states,'cities' =>  ['' => 'Select City'] + $cities));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	 
	 //store item
    public function store(Requests\CreateMarketPlaceRequest $request)
    {
        //Log::error($request->filelist);
        $request['country'] = !empty($request['country_id']) ? Country::where('id', $request['country_id'])->first()->country_name : 'null';
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
		$request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
        $request['user_id'] = Auth::user()->id;
		 $request['country_id'] = config('constants.COUNTRY_INDIA');
		$request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
		$request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
        $request['item_status'] = 'available';
        $marketplace = Marketplace::create($request->all());
        $id = $marketplace->id; //Inserted record ID
        //Upload Photos
        $albumID = 1;//Default album if no album is not selected.
        $coverPic = 1;
		$user_id=Auth::user()->id;
        if(isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if(isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic'];       
        Helper::uploadPhotos($request['filelist_photos'],'marketplace',$id,$albumID,$coverPic,config('constants.PHOTO.MARKETPLACE_PHOTO'),$user_id);
        //End Upload Photos
		Session::flash('message', 'Admin needs to approve your item');
        return redirect('/marketplace/myitems');
		
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
    public function edit($id,Request $request)
    {
        //http://stackoverflow.com/questions/21474189/laravel-fill-form-with-database-saved-fields
        $marketPlaceCategories = MarketPlaceCategories::where('isactive','=',1)->lists('name', 'id')->all();       
        $enum = config('constants.ENUM.MARKETPLACE.ITEMTYPE');    
        $marketplace = MarketPlace::findOrFail($id);
        $a = $marketplace->photos;
        Helper::setMenuToSelect(7,0);
        $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
        $states = State::where('country_id', $marketplace->country_id)->orderBy('state_name')->lists('state_name', 'id')->all();
	    $cities = City::where('state_id',    $marketplace->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
        return view('marketplace.edit')->with(array('marketPlaceCategories'=>['' => 'Select Category'] + $marketPlaceCategories,'countries' =>  ['' => 'Select Country'] +$countries,'states' =>  ['' => 'Select State'] +$states,'cities' =>  ['' => 'Select City'] + $cities,'enum'=>['' => 'Select Item type']+$enum,'id'=>$id,'marketplace'=>$marketplace));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	 
	 
	 //update item
    public function update(Requests\CreateMarketPlaceRequest $request, $id)
    {
        $request['country'] = !empty($request['country_id']) ? Country::where('id', $request['country_id'])->first()->country_name : 'null';
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
		$request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
		$user_id=Auth::user()->id;
        Marketplace::whereId($id)->update($request->except(['_method','_token','files','filelist_photos','contact_number','verificationcode','time_token','jfiler-items-exclude-files-0']));
        //Upload Photos
		  if (!empty($request['filelist_photos'])) {
			    // Photo::where(['imageable_id'=>$id, 'imageable_type' => config('constants.PHOTO.MARKETPLACE_PHOTO')])->update(['is_album_cover' => 0]);
        $albumID = 1;//Default album if no album is not selected.
         $coverPic = 1;
        if(isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if(isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic'];        
        Helper::uploadPhotos($request['filelist_photos'],'marketplace',$id,$albumID,$coverPic,config('constants.PHOTO.MARKETPLACE_PHOTO'),$user_id);
		  }
		   return redirect('/marketplace/myitems');
        //End Upload Photos
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function myItems()
   {    
	   $max = MarketPlace::select()->max('base_price');		
	   $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
	   $cities=[];
       $marketPlaceCategories = MarketPlaceCategories::where('isactive','=',1)->get();
       Helper::setMenuToSelect(7,0,$marketPlaceCategories);	
	   return view('marketplace.list')->with(array('states' => ['' => 'Select State'] + $states,'cities' => ['' => 'Select City'] + $cities,'max'=>$max,'page'=>'myitems'));	
    }

    public function showGallery($id){
	
        $marketplace = MarketPlace::findOrFail($id);
	     $photos = $marketplace->photos;
	    $photo=array();
		foreach($photos as $lis)	
		{
			$photo[]= $lis['url'];
		}
		 $item=$marketplace->item;
	     $itemdescription=$marketplace->item_description;
	     $base_price=$marketplace->base_price;
		 $actual_price=$marketplace->actual_price;
		  $contact_number=$marketplace->contact_number;
		  $user_id=$marketplace->user_id;
		   $loginuser_id=Auth::user()->id;
		   $location=$marketplace->location;
		  
  return view('marketplace.showgallery')->with(array('item'=>$marketplace,'photos'=> $photo,'itemname'=>$item, 'itemdescription'=>$itemdescription, 'base_price'=> $base_price,'actual_price' => $actual_price,'id'=>$id, 'contact_number'=> $contact_number,'page'=>'myitems','user_id'=>$user_id,   'loginuser_id'=>$loginuser_id,'location'=>$location));        
    }   
    
	public function marketplaceLogInfo(Request $request)
	 {	

		$id=$request->id;
		$catid='';
		$details=MarketPlace::select('marketplace_category_id')->where('id',$id)->get()->toArray();	
     foreach(  $details as $d)
	{
	$catid=$d['marketplace_category_id'];
	}
	$marketplacelogs = new MarketPlaceLogs();
	$marketplacelogs->marketplace_id = $id;
	$marketplacelogs->user_id = Auth::user()->id;
	$marketplacelogs->catgory_id = $catid;
	$marketplacelogs->save();
	return Response::json(array(
        
        'msg' =>  'success',
		 

          ));

	
	 }
	   public function showavailableitems($id)
	   {
		 // echo $id;exit;
		    $status= MarketPlace::select('item_status')->where('id',$id)->get()->toArray();
		//	echo "<pre>";print_r( $status) ;exit;
	            if(isset($status[0]['item_status'])) 
				{
					if($status[0]['item_status']=='available')
					{
						  MarketPlace::where('id',$id)->update(['item_status' => 'sold out']);
					}
					else{
						  MarketPlace::where('id',$id)->update(['item_status' => 'available']);
					}
				}
		     
			  
			   	return Response::json(array(
        
        'msg' =>  'success',
		 

          ));
	   }
	  public function deletephoto($id)
	  {
   		 $delete_photo_id =$id;
		 MarketPlace::find($delete_photo_id)->delete();
		 return Response::json(array('msg'=>'success','id'=>$delete_photo_id));
  	  }

      public function getMarketplaceCategories(){
        $marketPlaceCategories = MarketPlaceCategories::where('isactive','=',1)->get();
        $categories = array();
        foreach ($marketPlaceCategories as $key => $value) {
            $categories[$value->id] = $value->name;
        }
        return Response::json($categories);
      }
	  
	  //START OTP functions
	public function callGenerateAPI($request) { 
    	$otpApiUrl = "https://sendotp.msg91.com/api";
        $mobileNumber = preg_replace('/\D/', '', $request->mobileNumber);
        $senderId = "102234";
        $data = array(
            'sender' => $senderId,
            'route' => 4,
            'countryCode' => '91',
            'mobileNumber' =>  $mobileNumber,
            'getGeneratedOTP' => true
        );
        $data_string = json_encode($data);
        $ch = curl_init($otpApiUrl.'/generateOTP');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
            'application-Key:o5rdsGw-cy8bPNk0b4xL0R9eCiVY2msHICA8mvn8cYaBR9yKPtwpxATwtYS5ADR2bXLkSKukw6sTudSlh6UyD8cmdvAWJFhntjODOYz3i_MZbJ0LP6Bqra3LTibQA779C1Yp_ibtQQov5piO_nD76tZVJEMMOuTBE1xxDNEdtko='
        ));
        $result = curl_exec($ch);
		//dd($result);
        curl_close($ch);
        return $result;
    }
		
	public function generateOTP(Request $request){
        //call generateOTP API
        $response  = $this->callGenerateAPI($request);
        $response = json_decode($response,true);
		//dd($response);
        if($response["status"] == "error"){
            //customize this as per your framework
            $resp['message'] =  $response["response"]["code"];
            return \Response::json($resp);
        }
        //save the OTP on your server
        //if($this->saveOTP($response["response"]["oneTimePassword"])){
        if(true){
            $resp['message'] = "OTP SENT SUCCESSFULLY";
            //$request::header('X-CSRF-TOKEN')
            $data['user_id'] = Auth::user()->id;
            $data['token'] = $request->time_token;//$request->header('X-CSRF-TOKEN');
            $data['otp'] = $response["response"]["oneTimePassword"];
            $data['contact_number'] = preg_replace('/\D/', '', $request->mobileNumber);
            Otp::create($data);
            return \Response::json($resp);
        }
    }
	public function saveOTP($OTP){
       //save OTP to your session
       //$_SESSION["oneTimePassword"] = $OTP;
       // OR save the OTP to your database
       //connect db and save it to a table
       return true;
    }
    public function verifyOTP(Request $request){
        //This is the sudo logic you have to customize it as needed.
        //your verify logic here
        $whereArray['user_id'] = Auth::user()->id;
        $whereArray['token'] = $request->time_token;//$request->header('X-CSRF-TOKEN');
        $whereArray['otp'] = $request->otp;
        $whereArray['contact_number'] = preg_replace('/\D/', '', $request->mobileNumber);
        $query = Otp::where('is_verified', '=', 0);
        foreach($whereArray as $key=>$value){
              $query->where($key, '=', $value);
        }
        $query->orderBy('id', 'desc')->limit(1);
        $results = $query->get();
		
        if(count($results) == 1){
            //Update status
            Otp::where('id',$results[0]['id'])->update(['is_verified'=>1]);
            $resp['message'] = "NUMBER VERIFIED SUCCESSFULLY";
        }else{
            $resp['message'] =  "OTP INVALID";
            
        }
        return json_encode($resp);
        // OR get the OTP from your db and check against the OTP from client
    }
	//END OTP functions
	
	//check is OTP sent to mobileNumber
	public function isOtpSent(Request $request)
	{
		$contact_number = $request['contact_number'];
		$token = $request['token'];
		//check otp is sent to mobile number or not
		$isOtpSent = Otp::where('contact_number',$contact_number)->where('token',$token)->where('is_verified',1)->count();
		if($isOtpSent==0)
			return Response::json(['status'=>'fail']);
		else
			return Response::json(['status'=>'success']);
	}


}
