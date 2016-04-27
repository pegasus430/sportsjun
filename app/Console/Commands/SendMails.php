<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Email;
use App\Helpers\SendMail;

class SendMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:sendmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Start Send emails 		
		$pending_emails = Email::where('status', 0)->limit(50)->get();
		//dd($pending_emails);		
		if(count($pending_emails) > 0){
			
			foreach($pending_emails as $mailContent){
				$to_email_id =  $mailContent->toAddress;
				$to_user_id = $mailContent->to_user_id;				
				$subject = $mailContent->subject;					
				$content = $mailContent->mailText;
				$view_data = array('content'=>$content);
				$view = 'emails.emailcron';
				$cc_address = $mailContent->ccAddress;
				$bcc_address = $mailContent->bccAddress;
				$mail_data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'to_user_id'=>$to_user_id,'cc_address'=>$cc_address,'bcc_address'=>$bcc_address,'view_data'=>$view_data);				 
				if(SendMail::sendcronemails($mail_data)){
					//Update mail send status to 1
					$update_email = Email::where('id', $mailContent->id)->update(['status' => 1]);
				}
			}
			echo "Success";exit;
		}
		else{
			echo "No records found.";exit;
		}
		//End
    }
}
