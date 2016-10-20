<?php

namespace App\Providers;

use App\Events\OrganizationStaffWasAdded;
use App\Events\TeamOwnershipChanged;
use App\Events\UserRegistered;
use App\Handlers\Events\Staff\SendEmailToOrganizationStaff;
use App\Listeners\EmailUserRegister;
use App\Listeners\EmailUserTeamOwnership;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            //'SocialiteProviders\LinkedIn\LinkedInExtendSocialite@handle'
        ],

        OrganizationStaffWasAdded::class => [
            SendEmailToOrganizationStaff::class
        ],
        UserRegistered::class => [EmailUserRegister::class],
        TeamOwnershipChanged::class => [EmailUserTeamOwnership::class]

    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
