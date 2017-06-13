<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\MarketPlace;
use Payum\LaravelPackage\Controller\PayumController;
use App\Model\Organization;

use Cart;
use Session;

class CartController extends PayumController
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
        //
    }

    public function add_to_cart(Request $request){
        $item_id = $request->id;

        $cart = Cart::get($item_id);
        $item = MarketPlace::find($item_id);

        if($cart){
            Cart::update($item_id,['quantity'=>$cart->quantity+1]);
        }
        else{
            $cart = Cart::add($item->id, $item->item,$item->actual_price,1, $item);
          }

        return Cart::getContent();
    }

    public function list(Request $request){

        return view('cart.list');
    }

    public function checkout(request $request){
        switch ($request->payment_option) {
            case 'paypal':
                return redirect()->to('cart/payments/paypal');
                break;
            
            default:
                # code...
                break;
        }
    }


  public function payment_done(Request $request){
        $payum_token=$request->payum_token;

        /** @var Request $request */
        $request = \App::make('request');
        $request->attributes->set('payum_token', $payum_token);

        $token = $this->getPayum()->getHttpRequestVerifier()->verify($request);
        $gateway = $this->getPayum()->getGateway($token->getGatewayName());

        $gateway->execute($status = new GetHumanStatus($token));

        return \Response::json(array(
            'status' => $status->getValue(),
            'details' => json_encode($status->getFirstModel())
        ));
    }

    public function prepare_paypal(Request $request){




        $storage = $this->getPayum()->getStorage('Payum\Core\Model\ArrayObject');
        $details = $storage->create();

        $pack =  Cart::getContent();
        $organization = organization::find(Session::get('organization_id'));
            
        $details['PAYMENTREQUEST_0_AMT'] = cart::getSubTotal().".00";
        $details['pack'] = $pack;
        $details['PAYMENTREQUEST_0_DESC'] = 'Item Purchases on Sportsjun from '.$organization->name;
               
        $details['PAYMENTREQUEST_0_CURRENCYCODE'] = 'USD';
        $details['BRANDNAME'] = 'Sportsjun';
        $details['description'] = '';
        $storage->update($details);

        return $this->getPayum()->getGateways();
          $captureToken = $this->getPayum()->getTokenFactory()->createCaptureToken('paypal_ec', $details, 'paypal_payment_done');
         return \Redirect::to($captureToken->getTargetUrl());
    }





    public function paypal_done(Request $request){
             $payum_token=$request->payum_token;

        /** @var Request $request */
        $request = \App::make('request');
        $request->attributes->set('payum_token', $payum_token);

        $token = $this->getPayum()->getHttpRequestVerifier()->verify($request);
        $gateway = $this->getPayum()->getGateway($token->getGatewayName());

        $gateway->execute($status = new GetHumanStatus($token));
        $details = iterator_to_array($status->getFirstModel());

        if($status->isCaptured()){
          $this->add_subscription($details['pack'], 'Paypal');
         }
         else {
            return redirect()->to('payment_prepare/'.$details['pack'])->withInput()->withErrors(['Sorry, payment failed!'])->with('message', 'Sorry, payment failed');
         }       
        return redirect()->to('/transactions')->with('message', 'payment done');

//        return \Response::json(array(
//            'status' => $status->getValue(),
//            'details' => json_encode($status->getFirstModel())
//        ));

    }

      public function pay_paypal_premium_done(Request $request){
             $payum_token=$request->payum_token;

        /** @var Request $request */
        $request = \App::make('request');
        $request->attributes->set('payum_token', $payum_token);

        $token = $this->getPayum()->getHttpRequestVerifier()->verify($request);
        $gateway = $this->getPayum()->getGateway($token->getGatewayName());

        $gateway->execute($status = new GetHumanStatus($token));
        $details = iterator_to_array($status->getFirstModel());

        $video = video::findOrFail($details['video_id']);

        if($status->isCaptured()){
            $set_token_data= $this->set_token($details['video_id'], 'Paypal');
         }
         else {
            return redirect()->to("/premium/$video->slug/get_token?failed=failed")->withInput()->withErrors(['Sorry, payment failed!'])->with('message', 'Sorry, payment failed');
         }       
        return redirect()->to("/premium/$video->slug?successfuls=one")->with('message', 'payment done');

        return \Response::json(array(
            'status' => $status->getValue(),
            'details' => json_encode($status->getFirstModel())
        ));

    }

    public function offline_done(Request $request){
             $payum_token=$request->payum_token;

        /** @var Request $request */
        $request = \App::make('request');
        $request->attributes->set('payum_token', $payum_token);

        $token = $this->getPayum()->getHttpRequestVerifier()->verify($request);
        $gateway = $this->getPayum()->getGateway($token->getGatewayName());

        $gateway->execute($status = new GetHumanStatus($token));
        
        return \Response::json(array(
            'status' => $status->getValue(),
            'details' => json_encode($status->getFirstModel())
        ));
    }

    public function add_subscription($pack_id, $payment_provider, $user_id=null){     

    
    }

    public function addTransactions($start_date,$new_date,$pack,$payment_provider){
      
    }

}
