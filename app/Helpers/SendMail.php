<?php

namespace App\Helpers;
use App\Model\Email;
use Mail;
use Auth;
use HTML;
use Carbon\Carbon;

class SendMail {
	public static function sendmail($data)
	{
		$view = $data['view'];
		$subject = $data['subject'];
		$to_email_id = $data['to_email_id'];
		$view_data = $data['view_data'];
		$send_flag = !empty($data['send_flag'])?$data['send_flag']:0;
		$to_user_id = $data['to_user_id'];
		$type = !empty($type)?$type:NULL;
		$cc_address = !empty($cc_address)?$cc_address:NULL;
		$bcc_address = !empty($bcc_address)?$bcc_address:NULL;
		$status = 0;
		$flag = $data['flag'];
		$header = '<div style="width:100%; min-width:480px;"><div style="background:#ececec; padding:50px 0;"><div style="width:80%; height:70px; background:#65c178; margin:auto;">
       
               <table cellspacing="0" cellpadding="0" border="0" width="100%" height="70">
                   <tbody><tr>
                     <td align="left" valign="middle"><!-- <h1 style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:bold; margin:0 0 0 20px; color:#fff;">Email Text</h1> --></td>
                       <td align="right"><img width="254" height="60" style="margin:0 20px 0 0;" src="'.url().'/images/SportsJun_Logo.png"></td>
                   </tr>
               </tbody></table>

       </div><div style="width:80%; background:#fff; margin:auto; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; line-height:30px;"><div style="padding:30px;"><div style="padding: 0; text-align: left; font-size:14px; color:#333; font-family:Arial, Helvetica, sans-serif;">';
		
		$footer = '<div style="padding:10px 0; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; line-height:30px;">
                        Regards,<br>
                  <b style="color:#2f3c4d;">SportsJun Team</b>
                </div><div style="padding:5px 0; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; line-height:20px;">SportsJun Media &amp; Entertainment Pvt Ltd | Email: contact@sportsjun.com<br>
					The information contained in this email may be confidential and is intended only for the
					addressee. If you are not the intended recipient and have received this communication
					in error, please notify the sender and delete the message. Any unauthorised use of this
					communication is prohibited.</div></div></div></div></div></div>';		
		$view_data['header'] = $header;
		$view_data['footer'] = $footer;
  		if(!empty($view) && !empty($subject) && !empty($to_email_id))
		{	
			//if send flag is 1, then send the email immediately
			if($send_flag)
			{
				Mail::send(['html' => $view], ['view_data'=>$view_data], function($message) use ($to_email_id,$subject)
				{    
					$message->to($to_email_id)->subject($subject);    
				});
				//check for failure
				if(count(Mail::failures()) > 0)
				{
					//insert into emails table
					SendMail::insert_emails(Auth::user()->id,$to_user_id,$type,$subject,$view,$to_email_id,$cc_address,$bcc_address,$status,$flag,$view_data);					
					return false;
				}
				else
				{
					//if success make status 1 as mail is sent
					$status = 1;
				}
			}
			//insert into emails table
			if(isset(Auth::user()->id)) {
				//return redirect('/home');
				$from_user_id = Auth::user()->id;
			}else{
				$from_user_id = $to_user_id;
			}
			SendMail::insert_emails($from_user_id,$to_user_id,$type,$subject,$view,$to_email_id,$cc_address,$bcc_address,$status,$flag,$view_data);
			return true;
		} 
	}
	//insert into emails table
	public static function insert_emails($from_user_id,$to_user_id,$type,$subject,$mail_text_view,$to_address,$cc_address,$bcc_address,$status,$flag,$view_data)
	{
		//making a view 
		$mail_text_view = view($mail_text_view,['view_data' => $view_data]);
		//getting the view as a string into mail_text variable
		$mail_text = HTML::entities($mail_text_view->render());
		$email_details = array(
			'from_user_id' => $from_user_id,
			'to_user_id' => $to_user_id,
			'type' => $type,
			'subject' => $subject,
			'mailText' => $mail_text,
			'toAddress' => $to_address,
			'ccAddress' => $cc_address,
			'bccAddress' => $bcc_address,
			'status' => $status,
			'flag' => $flag,
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
			);
		if(Email::insert($email_details))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	public static function sendcronemails($data)
	{
		$view = $data['view'];
		$subject = $data['subject'];
		$to_email_id = $data['to_email_id'];
		$view_data = $data['view_data'];		
		$to_user_id = $data['to_user_id'];
		$cc_address = !empty($data['cc_address'])?$data['cc_address']:NULL;
		$bcc_address = !empty($data['bcc_address'])?$data['bcc_address']:NULL;
		
		
		$header = '<div style="width:100%; min-width:480px;"><div style="background:#ececec; padding:50px 0;"><div style="width:80%; height:70px; background:#65c178; margin:auto;">
       
               <table cellspacing="0" cellpadding="0" border="0" width="100%" height="70">
                   <tbody><tr>
                     <td align="left" valign="middle"><h1 style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:bold; margin:0 0 0 20px; color:#fff;">Email Text</h1></td>
                       <td align="right"><img width="254" height="60" style="margin:0 20px 0 0;" src="'.url().'/images/SportsJun_Logo.png"></td>
                   </tr>
               </tbody></table>

       </div><div style="width:80%; background:#fff; margin:auto; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; line-height:30px;"><div style="padding:30px;"><div style="padding: 0; text-align: left; font-size:14px; color:#333; font-family:Arial, Helvetica, sans-serif;">';
		
		$footer = '<div style="padding:10px 0; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal; line-height:30px;">
                        Regards,<br>
                  <b style="color:#2f3c4d;">SportsJun Team</b>
                </div></div></div></div></div></div>';		
		$view_data['header'] = $header;
		$view_data['footer'] = $footer;
  		if(!empty($view) && !empty($subject) && !empty($to_email_id))
		{			
			Mail::send(['html' => $view], ['view_data'=>$view_data], function($message) use ($to_email_id,$subject)
			{    
				$message->to($to_email_id)->subject($subject);    
			});
			//check for failure
			if(count(Mail::failures()) > 0)
			{								
				return false;
			}
			else
			{				
				return true;
			}			
			
			return true;
		} 
	}
}
