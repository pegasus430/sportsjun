<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Country;
use App\Model\PaymentGateWays;
use Auth;
use Illuminate\Support\Facades\DB;
use Response;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\Model\UserStatistic;
use App\User;
use App\Model\MatchSchedule;
use App\Model\PaymentSetups;
use Illuminate\Support\Facades\Validator;

use Input;


class PaymentGateWaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {

		$globalurl=url(); 
		
		
       	$filter = \DataFilter::source(PaymentGateWays::with('country'));	
        $filter->add('name','Gateway Name','text');
        
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
		$grid = \DataGrid::source($filter);
		$grid->attributes(array("class"=>"table table-striped"));

		$grid->add('id','S#');
		$grid->add('name','Gateway Name');
		$grid->add('country.country_name','Country Name');
		$grid->add('status','Status');

        $grid->edit('paymentgateways/edit', 'Operation','modify');
        $grid->orderBy('id','desc');		
        $grid->link('admin/paymentgateways/create',"New Payment Gateway", "TR");
		$grid->paginate(
        	config('constants.DEFAULT_PAGINATION')
        );
		//Helper::printQueries();
        return  view('admin.paymentgateways.index', compact('filter', 'grid'));
    }
    public function getCreate(){

    	$countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
    	$payment = new PaymentGateWays;
    	return view(
			'admin.paymentgateways.create',
			['payment'=>$payment, 'button_text'=>'Save']
		)->with([
			'countries' => ['' => 'Select Country'] + $countries,
		]);
    }

    public function postCreate(Requests\CreatePaymentRequest $request){

  
    	if($request['status'] === null){
    		$request['status'] = 'off';
    	}
    	$reqest['created_at'] = date('Y-m-d H:i:s');
    	$reqest['updated_at'] = date('Y-m-d H:i:s');

    	if(!$request['id']){
    		$payment = PaymentGateWays::create($request->all());
    	} else {
    		PaymentGateWays::whereId($request['id'])->update($request->except(['_method','_token','created_at']));
    	}
    	
    	return redirect('admin/paymentgateways')->with('status', trans('message.payment_gate_way.create_message'));
    }

    public function getEdit(Request $request){
    	$id = $request->modify;
    	$countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
    	$payment = PaymentGateWays::findOrFail($id);
    	if($payment->status == 'off'){
    		$payment->status = '';
    	}
    	return view(
    		'admin.paymentgateways.create',
    		['payment'=>$payment,'button_text'=>'Update']
		)->with([
		'countries' => ['' => 'Select Country'] + $countries,
		]);
    }



    public function postAvailability(){

        $id=$_POST['c_id'];
        $countries = PaymentGateWays::where(['country_id'=>$id,'status'=>'on'])->get();
        $available=count($countries);
        return $available;
    }


     public function getAvailability(){

        $id=$_GET['c_id'];
        $countries = PaymentGateWays::where(['country_id'=>$id,'status'=>'on'])->get();
        $available=count($countries);
        return $available;
    }


    public function getSetup(){
        $globalurl=url(); 
        
        $filter = \DataFilter::source(PaymentGateWays::with('country'));    
        $filter->add('name','Gateway Name','text');
        
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
        $grid = \DataGrid::source($filter);
        $grid->attributes(array("class"=>"table table-striped admin-setups"));

        $grid->add('id','S#');
        $grid->add('<a href="newsetup/{{ $id }}">{{ $name }}</a>','Gateways(Click the name for Setup page)');
        $grid->link('name','Gateway Name');
       

        
        $grid->orderBy('id','desc');        
        $grid->link('admin/paymentgateways/create',"New Payment Gateway", "TR");
        $grid->paginate(
            config('constants.DEFAULT_PAGINATION')
        );
        //Helper::printQueries();
        return  view('admin.paymentgateways.setup', compact('filter', 'grid'));
        
    }


     public function getNewsetup($id){
        $globalurl=url(); 
        
        $filter = \DataFilter::source(PaymentSetups::where('gateway_id',$id)->where('status','active'));    
        $filter->add('name','Service Name','text');
        $filter->add('value','Service chare','text','ghhg');
        
        $filter->submit('add');
        
        $filter->build();

        
        $filter->submit('search');

        $grid = \DataGrid::source($filter);
        $grid->attributes(array("class"=>"table table-striped"));

        $grid->add('id','S#');
        $grid->add('setup_name','Service Name');
        $grid->add('setup_value','Service Charge(In percentage)');
        $grid->add('<a href="/admin/paymentgateways/delete/{{ $id }}">Delete</a>','Operations');
        $grid->orderBy('id','desc');        
        
        $grid->paginate(
            config('constants.DEFAULT_PAGINATION')
        );
        //Helper::printQueries();
        return  view('admin.paymentgateways.addsetup', compact('filter', 'grid'))->with(['id' => $id]);
        //echo $id; exit;
        
    }

    public function postNewsetup(){
       //dd($_REQUEST);
        $validator = Validator::make($_REQUEST, [
            'setup_value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            //dd($validator);
            return back()->withErrors($validator)->withInput();
        }


       $data=Input::except('_token');
       $data['status']='active';
       
       $paymentsetups = new PaymentSetups;
      // $paymentsetups=$data;
       foreach($data as $key=>$value){
        $paymentsetups->$key=$value;
       }
       //dd($paymentsetups);
       if($paymentsetups->save()) {
       return redirect('admin/paymentgateways/newsetup/'. $data['gateway_id']);
       } else {
            return back()->withErrors(['Operation failed']);
       }
   }
       
       public function getDelete($id){
    
        $gateway_id=PaymentSetups::where('id', $id)->value('gateway_id');
        PaymentSetups::where('id', $id)->update(['status' => 'inactive']);
        return redirect('admin/paymentgateways/newsetup/'. $gateway_id);
       
       

        
    }



}
