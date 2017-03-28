<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEsportsMatchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esports:match-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send information about the eSports matches to Managers, Owners or Players';

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
        //Query to notify users about the match info (lobby creator, lobby name, lobby password)
        $now = Carbon::now()->day;
        $target_date = now->toDateString(); //Date and time to date format Y-m-d
        $matchScheduleData = MatchSchedule::where('match_start_date', $target_date)->get();
        if (count($matchScheduleData) > 0)
        {
            foreach ($matchScheduleData as $key => $schedule){
                AllRequests::sendMatchNotifications($schedule->tournament_id,$schedule->schedule_type,$schedule->a_id,$schedule->b_id,$schedule->match_start_date);
            }
            echo "Success";exit;
        }
        else
        {
            echo "No records found.";exit;
        }
    }
}
