<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        'App\Console\Commands\NotifySchedules',
        'App\Console\Commands\NotifyNotVerifiedUsers',
        'App\Console\Commands\SendMails',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*$schedule->command('inspire')
                 ->hourly();*/
        $schedule->command('cron:notifyschedules')
                 ->daily();
        $schedule->command('cron:notifyusers')
                 ->daily();
        $schedule->command('cron:sendmails')
                 ->cron('*/10 * * * *');
    }
}
