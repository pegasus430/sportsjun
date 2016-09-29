<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Helpers\SendMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUserRegister  implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $user = $event->user;

        $subject = 'Welcome to SportsJun';
        $view_data = array('name'=>$user->firstname,'verification_key'=> $user->verification_key);
        $view = 'emails.welcome';
        $mail_data = array('view'=>$view,
            'subject'=>$subject,
            'to_email_id'=>$user->email,
            'to_user_id'=> $user->id,
            'view_data'=>$view_data,
            'flag'=>'user',
            'send_flag'=>1,
            'verification_key'=>$user->verification_key);

        if(SendMail::sendmail($mail_data))
        {
            //return redirect()->back()->with('status', trans('message.contactus.emailsent'));
            $response = array(
                'status' => 'success',
                'msg' => 'user registered successfully',
            );
        }
        //End
    }
}
