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
}
