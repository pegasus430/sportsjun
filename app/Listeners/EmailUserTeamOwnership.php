<?php

namespace App\Listeners;

use App\Events\TeamOwnershipChanged;
use App\Helpers\SendMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUserTeamOwnership implements ShouldQueue
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

    public function handle(TeamOwnershipChanged $event)
    {
        $user = $event->user;

        $subject = 'Sportsjun - you become owner of team';
        $mail_data = [
            'view' => 'emails.changeOwnership',
            'subject' => $subject,
            'to_email_id' => $user->email,
            'to_user_id' => $user->id,
            'view_data'=>[
                'user'=>$user,
                'team' => $event->team,
            ],

            'flag' => 'user',
            'send_flag' => 1,
        ];

        if (SendMail::sendmail($mail_data)) {
            return true;
        }

    }
}
