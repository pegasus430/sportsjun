<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Model\Contactus;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Helpers\Helper;
use App\Helpers\SendMail;
class contactusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	  $type=array('I have a general question'=>'I have a general question','suggest a new sport'=>'suggest a new sport','Report spam or abuse'=>'Report spam or abuse','report a bug'=>'report a bug','i have a suggestion'=>'i have a suggestion');
      return  view('contactus.create',array('type'=>['' => 'Select Subject'] + $type));
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
    public function store(Requests\contactusRequest $request)
    {
		$request['created_by'] = Auth::user()->id;
        $contactus=Contactus::create($request->all());
		$message=$contactus['message'];	
		if(!empty($contactus))
		{
			
			$to_email_id =  $contactus['email'];
			//blade view for sendinvitereminder from emails folder in views
	  	    //	$view = 'emails.sendinvitereminder';
			$subject =  $contactus['subject'];
	         $view_data = array('message'=>	$message);
			$view = 'emails.store';
			$data = array('view'=>	$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>'','flag'=>'user','send_flag'=>1);
			if(SendMail::sendmail($data))
			{
				return redirect()->back()->with('status', trans('message.contactus.emailsent'));
			}
			else
			{
			    return redirect()->back()->with('error_msg', trans('message.contactus.emailsentfail'));
			}
		}
		
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
}

