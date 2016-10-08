<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Model\MatchSchedule;
use App\Model\Tournaments;
use App\Helpers\AllRequests;

class NotifySchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:notifyschedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify schedules before match starts';

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
        //Query to notify users about match schedule
		$currentDay = Carbon::now()->day;
        $nextTwoDays = Carbon::now()->addDays(2); //Date after 2 days
		$target_date = $nextTwoDays->toDateString(); //Date and time to date format Y-m-d
		$matchScheduleData = MatchSchedule::where('match_start_date', $target_date)->get();
		if (count($matchScheduleData) > 0){
            foreach ($matchScheduleData as $key => $schedule){
				AllRequests::sendMatchNotifications($schedule->tournament_id,$schedule->schedule_type,$schedule->a_id,$schedule->b_id,$schedule->match_start_date);
			}
			echo "Success";exit;
		}
		else{
			echo "No records found.";exit;
		}
		
    }
}
