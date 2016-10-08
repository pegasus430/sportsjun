<?php

namespace App\Handlers\Events\Staff;

use App\Events\OrganizationStaffWasAdded;
use App\Helpers\SendMail;

class SendEmailToOrganizationStaff
{
    /**
     * Create the event handler.
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
     * @param  OrganizationStaffWasAdded $event
     *
     * @return void
     */
    public function handle(OrganizationStaffWasAdded $event)
    {
        $to_user_id = $event->user->id;
        $to_email = $event->user->email;
        $user_name = $event->user->name ?: null;
        $subject = trans('message.staff.invite_subject');
        $view_data = [
            'email'             => $to_email,
            'password'          => $event->password,
            'user_name'         => $user_name,
            'organization_name' => $event->organization->name,
            'role_name'         => $event->user->roleForOrganization($event->organization->id)->name,
        ];

        $view = 'emails.inviteStaff';

        $data = [
            'view'        => $view,
            'subject'     => $subject,
            'to_email_id' => $to_email,
            'view_data'   => $view_data,
            'to_user_id'  => $to_user_id,
            'flag'        => 'user',
            'send_flag'   => 1,
        ];
        SendMail::sendmail($data);
    }
}
